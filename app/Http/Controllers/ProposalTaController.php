<?php

namespace App\Http\Controllers;

use App\Models\AnggotaTugasAkhir;
use App\Models\ProposalTa;
use App\Models\TugasAkhir;
use App\Models\TopikTa;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProposalTaController extends Controller
{
    /**
     * Mahasiswa TA mengajukan proposal untuk topik tertentu
     */
    public function store(Request $request, TopikTa $topik): RedirectResponse
    {
        $user = Auth::user();

        // 1. Validasi hak akses: Hanya Mahasiswa TA aktif
        if (! $user->isMahasiswa()) {
            abort(403, 'Akses ditolak: Hanya mahasiswa yang berwenang mengajukan proposal.');
        }

        if ($user->isMahasiswaNonTa() || ! ($user->mahasiswaProfile?->is_ta ?? false)) {
            abort(403, 'Akses ditolak: Hanya Mahasiswa TA aktif yang berhak mengajukan proposal Tugas Akhir.');
        }

        // 2. Validasi topik terbuka dan dipublikasikan
        if (! $topik->isDipublikasikan()) {
            return redirect()->back()
                ->with('error', 'Topik ini belum atau tidak lagi menerima pengajuan proposal.');
        }

        $mahasiswaProfile = $user->mahasiswaProfile;

        // 3. Cek apakah mahasiswa sudah memiliki proposal yang LOLOS
        $hasLolos = ProposalTa::where('mahasiswa_id', $mahasiswaProfile->id)
            ->where('status', 'lolos')
            ->exists();

        if ($hasLolos) {
            return redirect()->back()
                ->with('error', 'Anda telah memiliki proposal yang dinyatakan LOLOS. Tidak dapat mengajukan topik lain.');
        }

        // 4. Cek apakah mahasiswa sudah mengajukan untuk topik ini
        $alreadySubmitted = ProposalTa::where('topik_ta_id', $topik->id)
            ->where('mahasiswa_id', $mahasiswaProfile->id)
            ->exists();

        if ($alreadySubmitted) {
            return redirect()->back()
                ->with('error', 'Anda sudah pernah mengajukan proposal untuk topik ini.');
        }

        // 5. Validasi input
        $request->validate([
            'judul_proposal' => ['required', 'string', 'max:255'],
            'ringkasan' => ['nullable', 'string'],
            'link_ecourse' => ['nullable', 'url', 'max:500'],
            'file_proposal' => ['nullable', 'file', 'mimes:pdf,docx', 'max:25600'],
        ]);

        $filePath = null;
        if ($request->hasFile('file_proposal')) {
            $filePath = $request->file('file_proposal')->store('proposals', 'public');
        }

        ProposalTa::create([
            'topik_ta_id' => $topik->id,
            'mahasiswa_id' => $mahasiswaProfile->id,
            'judul_proposal' => $request->judul_proposal,
            'ringkasan' => $request->ringkasan,
            'file_proposal' => $filePath,
            'link_ecourse' => $request->link_ecourse,
            'status' => 'diajukan',
        ]);

        return redirect()->route('topik.index')
            ->with('success', 'Proposal Anda berhasil diajukan untuk topik ini. Silakan tunggu evaluasi dari dosen.');
    }

    /**
     * Dosen pemilik topik memberikan keputusan seleksi (Lolos / Tidak Lolos)
     */
    public function keputusan(Request $request, ProposalTa $proposal): RedirectResponse
    {
        $user = Auth::user();

        // 1. Otorisasi: Dosen pemilik topik atau Koordinator TA
        $isOwner = $user->isDosen() && $user->dosenProfile?->id === $proposal->topik->dosen_id;
        $isKoor = $user->isKoordinator();

        if (! $isOwner && ! $isKoor) {
            abort(403, 'Akses ditolak: Hanya dosen pemilik topik atau Koordinator TA yang dapat mengevaluasi proposal ini.');
        }

        $request->validate([
            'keputusan' => ['required', 'in:lolos,tidak_lolos'],
            'catatan_dosen' => ['nullable', 'string', 'max:1000'],
        ]);

        $topik = $proposal->topik;
        $mahasiswa = $proposal->mahasiswa;

        DB::transaction(function () use ($proposal, $topik, $mahasiswa, $request) {
            $status = $request->keputusan;

            $proposal->update([
                'status' => $status,
                'catatan_dosen' => $request->catatan_dosen,
                'tanggal_keputusan' => now(),
            ]);

            // Jika dinyatakan LOLOS: Kaitkan ke tugas_akhir dan tetapkan pembimbing
            if ($status === 'lolos') {
                // 1. Cari tugas akhir milik mahasiswa ini
                $ta = TugasAkhir::where('mahasiswa_id', $mahasiswa->id)->first();

                if ($ta) {
                    // Update judul dan dosen pembimbing 1
                    $ta->update([
                        'judul' => $proposal->judul_proposal,
                        'dosen_pembimbing_1_id' => $topik->dosen_id,
                    ]);
                } else {
                    // Buat TugasAkhir baru
                    $ta = TugasAkhir::create([
                        'mahasiswa_id' => $mahasiswa->id,
                        'dosen_pembimbing_1_id' => $topik->dosen_id,
                        'judul' => $proposal->judul_proposal,
                        'prodi' => $mahasiswa->prodi,
                        'tahun_ajaran' => '2025/2026',
                        'status' => 'proposal',
                    ]);
                }

                // Daftarkan ke anggota_tugas_akhir jika belum ada
                AnggotaTugasAkhir::firstOrCreate(
                    [
                        'tugas_akhir_id' => $ta->id,
                        'mahasiswa_id' => $mahasiswa->id,
                    ],
                    [
                        'peran' => 'ketua',
                    ]
                );

                // Tandai proposal lain dari mahasiswa ini yang belum lolos menjadi tidak_lolos
                ProposalTa::where('mahasiswa_id', $mahasiswa->id)
                    ->where('id', '!=', $proposal->id)
                    ->where('status', '!=', 'lolos')
                    ->update([
                        'status' => 'tidak_lolos',
                        'catatan_dosen' => 'Mahasiswa telah diterima pada topik lain.',
                        'tanggal_keputusan' => now(),
                    ]);

                // Jika kuota topik terpenuhi, tutup topik
                if ($topik->sisaKuota() <= 0) {
                    $topik->update(['status' => 'ditutup']);
                }
            }
        });

        $statusMsg = $request->keputusan === 'lolos'
            ? "Proposal mahasiswa {$mahasiswa->user->name} dinyatakan LOLOS. Pembimbing resmi telah ditetapkan."
            : "Proposal mahasiswa {$mahasiswa->user->name} dinyatakan TIDAK LOLOS.";

        return redirect()->back()->with('success', $statusMsg);
    }
}

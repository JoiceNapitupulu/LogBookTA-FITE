<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreJanjiBimbinganRequest;
use App\Models\DosenProfile;
use App\Models\JanjiBimbingan;
use App\Models\TugasAkhir;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class JanjiBimbinganController extends Controller
{
    public function index(Request $request): View
    {
        $user = Auth::user();

        if ($user->isTA() || $user->isBaak()) {
            abort(403, 'Akses ditolak: Fitur Janji Bimbingan hanya tersedia untuk Mahasiswa TA dan Dosen Pembimbing.');
        }

        $query = JanjiBimbingan::with(['tugasAkhir.mahasiswa.user', 'dosen.user']);

        if ($user->isMahasiswa()) {
            $tugasAkhir = $user->tugasAkhir();

            if ($tugasAkhir) {
                $query->where('tugas_akhir_id', $tugasAkhir->id);
            } else {
                $query->whereRaw('1 = 0');
            }
        } elseif ($user->isDosen() || $user->isKaprodi()) {
            $dosenProfile = $user->dosenProfile;
            if ($dosenProfile) {
                $query->where('dosen_id', $dosenProfile->id);
            }
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $janjiList = $query->latest('tanggal_waktu')->paginate(15)->withQueryString();

        // Get active lecturers for booking dropdown
        $dosenList = DosenProfile::with('user')->get();

        // Get user's active tugas akhir for modal
        $myTugasAkhir = null;
        $bimbinganTaList = collect();
        if ($user->isMahasiswa()) {
            $myTugasAkhir = $user->tugasAkhir();
        } elseif (($user->isDosen() || $user->isKaprodi()) && $user->dosenProfile) {
            $bimbinganTaList = $user->dosenProfile->tugasAkhirBimbingan()
                ->with('mahasiswa.user')
                ->get();
        }

        return view('bimbingan.index', compact('janjiList', 'dosenList', 'myTugasAkhir', 'bimbinganTaList'));
    }

    public function store(StoreJanjiBimbinganRequest $request): RedirectResponse
    {
        $user = $request->user() ?? Auth::user();
        $validated = $request->validated();

        if ($user && $user->isMahasiswa()) {
            $tugasAkhir = $user->tugasAkhir();
            if (! $tugasAkhir) {
                abort(403, 'Akses ditolak: Anda belum memiliki Tugas Akhir aktif.');
            }
            if (empty($validated['dosen_id'])) {
                abort(422, 'Dosen pembimbing wajib dipilih.');
            }
            $validated['tugas_akhir_id'] = $tugasAkhir->id;
            $validated['inisiator'] = 'mahasiswa';
        } else {
            // Inisiator Dosen: hanya boleh untuk mahasiswa bimbingannya sendiri
            $dosenProfile = $user?->dosenProfile;
            if (! $dosenProfile) {
                abort(403, 'Akses ditolak: Profil dosen tidak ditemukan.');
            }

            $tugasAkhir = TugasAkhir::find($validated['tugas_akhir_id'] ?? null);
            if (! $tugasAkhir || ! $dosenProfile->isPembimbingOf($tugasAkhir)) {
                abort(403, 'Akses ditolak: Anda hanya dapat mengajukan janji bimbingan kepada mahasiswa bimbingan Anda.');
            }

            $validated['inisiator'] = 'dosen';
            $validated['dosen_id'] = $dosenProfile->id;
        }

        $validated['status'] = 'menunggu_persetujuan';

        DB::transaction(function () use ($validated) {
            JanjiBimbingan::create($validated);
        });

        return redirect()->back()
            ->with('success', 'Jadwal janji bimbingan berhasil diajukan dan dikirimkan!');
    }

    public function updateStatus(Request $request, JanjiBimbingan $janjiBimbingan): RedirectResponse
    {
        $user = $request->user() ?? Auth::user();
        if (! $user || ! $user->isDosen()) {
            abort(403, 'Akses ditolak: Hanya Dosen yang berwenang memperbarui status janji bimbingan.');
        }

        $dosenProfile = $user->dosenProfile;
        if (! $dosenProfile || $janjiBimbingan->dosen_id !== $dosenProfile->id) {
            abort(403, 'Akses ditolak: Anda tidak memiliki wewenang untuk mengubah status janji bimbingan dosen lain.');
        }

        $validated = $request->validate([
            'status' => ['required', 'in:disetujui,ditolak,selesai'],
            'catatan_hasil' => [
                'nullable',
                'required_if:status,selesai',
                'string',
                'min:10',
                'max:2000',
            ],
            'alasan_penolakan' => ['nullable', 'string', 'max:500'],
        ], [
            'status.required' => 'Status janji bimbingan wajib dipilih.',
            'status.in' => 'Status janji bimbingan tidak valid.',
            'catatan_hasil.required_if' => 'Catatan hasil bimbingan wajib diisi ketika menandai sesi bimbingan telah selesai.',
            'catatan_hasil.min' => 'Catatan hasil bimbingan minimal berisi 10 karakter.',
            'catatan_hasil.max' => 'Catatan hasil bimbingan maksimal berisi 2000 karakter.',
        ]);

        DB::transaction(function () use ($validated, $janjiBimbingan) {
            $data = [
                'status' => $validated['status'],
            ];

            if ($validated['status'] === 'selesai') {
                $data['catatan_hasil'] = $validated['catatan_hasil'];
            } elseif ($validated['status'] === 'ditolak') {
                $data['alasan_penolakan'] = $validated['alasan_penolakan'] ?? null;
            }

            $janjiBimbingan->update($data);
        });

        $message = match ($validated['status']) {
            'disetujui' => 'Janji bimbingan berhasil disetujui!',
            'ditolak' => 'Janji bimbingan telah ditolak.',
            'selesai' => 'Sesi bimbingan berhasil ditandai selesai beserta catatan hasil bimbingan!',
        };

        return redirect()->back()->with('success', $message);
    }
}

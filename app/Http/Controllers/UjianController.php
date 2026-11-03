<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUjianTaRequest;
use App\Models\DosenProfile;
use App\Models\TugasAkhir;
use App\Models\UjianTa;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class UjianController extends Controller
{
    public function index(Request $request): View
    {
        $user = Auth::user();

        // 1. Mahasiswa active application
        $myUjian = null;
        $myTugasAkhir = null;
        if ($user->isMahasiswa()) {
            $myTugasAkhir = $user->tugasAkhir();
            if ($myTugasAkhir) {
                $myUjian = UjianTa::where('tugas_akhir_id', $myTugasAkhir->id)
                    ->with([
                        'verifiedByTa',
                        'tugasAkhir.pembimbing1.user',
                        'tugasAkhir.pembimbing2.user',
                        'penguji1.user',
                        'penguji2.user',
                    ])
                    ->latest()
                    ->first();
            }

            // Mahasiswa (baik TA maupun Non-TA) hanya menerima myUjian dan myTugasAkhir
            return view('ujian.index', compact('myUjian', 'myTugasAkhir'));
        }

        // 2. Data khusus Staf (Teaching Assistant, Koordinator, BAAK, atau Dosen)
        $query = UjianTa::with([
            'tugasAkhir.mahasiswa.user',
            'tugasAkhir.pembimbing1.user',
            'tugasAkhir.pembimbing2.user',
            'penguji1.user',
            'penguji2.user',
            'verifiedByTa',
        ]);

        // Dosen Pembimbing biasa (bukan Koordinator/Kaprodi/TA/BAAK) hanya boleh melihat ujian mahasiswa bimbingannya
        if ($user->isDosen() && ! $user->isKoordinator() && ! $user->isKaprodi()) {
            $dosenProfile = $user->dosenProfile;
            if ($dosenProfile) {
                $query->whereHas('tugasAkhir', function ($q) use ($dosenProfile) {
                    $q->where('dosen_pembimbing_1_id', $dosenProfile->id)
                      ->orWhere('dosen_pembimbing_2_id', $dosenProfile->id);
                });
            } else {
                $query->whereRaw('1 = 0');
            }
        }

        // Prioritas antrean operasional:
        // - TA: dahulukan berkas yang menunggu verifikasi artefak
        // - Koordinator: dahulukan ujian yang berkasnya sudah lengkap tapi belum ada tanggal/penguji
        // - BAAK: dahulukan ujian yang sudah ada tanggal & penguji namun belum dialokasikan ruangan
        if ($user->isTA()) {
            $query->orderByRaw("CASE WHEN status_dokumen = 'menunggu_verifikasi' THEN 0 ELSE 1 END");
        } elseif ($user->isKoordinator()) {
            $query->orderByRaw("CASE WHEN status_dokumen = 'berkas_lengkap' AND (tanggal_ujian IS NULL OR dosen_penguji_1_id IS NULL OR dosen_penguji_2_id IS NULL) THEN 0 ELSE 1 END");
        } elseif ($user->isBaak()) {
            $query->orderByRaw("CASE WHEN tanggal_ujian IS NOT NULL AND dosen_penguji_1_id IS NOT NULL AND dosen_penguji_2_id IS NOT NULL AND ruangan IS NULL THEN 0 ELSE 1 END");
        }

        $allUjian = $query->latest()->paginate(20);

        // Active lecturers for examiner selection (Koordinator TA & Kaprodi)
        $dosenList = ($user->isKoordinator() || $user->isKaprodi())
            ? DosenProfile::with('user')->get()
            : collect();

        return view('ujian.index', compact('myUjian', 'myTugasAkhir', 'allUjian', 'dosenList'));
    }

    public function store(StoreUjianTaRequest $request): RedirectResponse
    {
        $user = $request->user() ?? Auth::user();
        if (! $user || ! $user->isMahasiswa()) {
            abort(403, 'Akses ditolak: Hanya Mahasiswa yang berwenang mendaftar ujian TA.');
        }

        $tugasAkhir = $user->tugasAkhir();
        if (! $tugasAkhir) {
            return redirect()->back()->with('error', 'Pendaftaran ujian gagal: Anda belum terdaftar dalam Tugas Akhir aktif.');
        }

        $validated = $request->validated();
        $jenisUjian = $validated['jenis_ujian'];

        // Cek syarat akademik dasar pendaftaran ujian
        $approvedBabsCount = $tugasAkhir->logbooks()
            ->where('status_verifikasi', 'disetujui')
            ->distinct('bab_progress')
            ->count('bab_progress');

        if ($jenisUjian === 'sempro') {
            $allowedStatuses = ['proposal', 'pengerjaan', 'sempro'];
            if (! in_array($tugasAkhir->status, $allowedStatuses)) {
                return redirect()->back()->with('error', 'Pendaftaran Seminar Proposal gagal: Status Tugas Akhir Anda saat ini belum memenuhi syarat untuk Sempro.');
            }
        } elseif ($jenisUjian === 'seminar_ta') {
            $isAdvanced = in_array($tugasAkhir->status, ['seminar_ta', 'sidang_akhir', 'selesai']);
            if (! $isAdvanced && $approvedBabsCount < 3) {
                return redirect()->back()->with('error', "Pendaftaran Seminar TA gagal: Minimal 3 bab logbook harus disetujui oleh Dosen Pembimbing (saat ini: {$approvedBabsCount} bab disetujui).");
            }
        } elseif ($jenisUjian === 'sidang_akhir') {
            $isAdvanced = in_array($tugasAkhir->status, ['sidang_akhir', 'selesai']);
            if (! $isAdvanced && $approvedBabsCount < 5) {
                return redirect()->back()->with('error', "Pendaftaran Sidang Akhir gagal: Minimal 5 bab logbook harus disetujui oleh Dosen Pembimbing (saat ini: {$approvedBabsCount} bab disetujui).");
            }
        }

        $validated['tugas_akhir_id'] = $tugasAkhir->id;

        if ($request->hasFile('file_persyaratan')) {
            $path = $request->file('file_persyaratan')->store('uploads/persyaratan', 'public');
            $validated['file_persyaratan'] = $path;
        }

        $validated['status_dokumen'] = 'menunggu_verifikasi';
        $validated['status_ujian'] = 'diajukan';

        DB::transaction(function () use ($validated) {
            UjianTa::create($validated);
        });

        return redirect()->back()
            ->with('success', 'Pendaftaran ujian berhasil dikirim! Berkas Anda sedang dalam antrean verifikasi Teaching Assistant FITE.');
    }

    public function verifikasiDokumen(Request $request, UjianTa $ujian): RedirectResponse
    {
        $user = Auth::user();
        if (! ($user->isTA() || $user->isKoordinator())) {
            abort(403, 'Akses ditolak: Hanya Teaching Assistant atau Koordinator TA yang berwenang memverifikasi dokumen ujian.');
        }

        $request->validate([
            'status_dokumen' => ['required', 'in:berkas_lengkap,berkas_kurang'],
            'catatan_verifikasi_ta' => ['nullable', 'string'],
        ]);

        DB::transaction(function () use ($request, $ujian) {
            $ujian->update([
                'status_dokumen' => $request->status_dokumen,
                'catatan_verifikasi_ta' => $request->catatan_verifikasi_ta,
                'verified_by_ta_id' => Auth::id(),
            ]);
        });

        $msg = $request->status_dokumen === 'berkas_lengkap'
            ? 'Dokumen dinyatakan LENGKAP & VALID! Diteruskan ke Koordinator TA.'
            : 'Dokumen ditandai KURANG LENGKAP. Catatan telah disampaikan ke mahasiswa.';

        return redirect()->back()->with('success', $msg);
    }

    public function tetapkanJadwal(Request $request, UjianTa $ujian): RedirectResponse
    {
        $user = Auth::user();
        if (! $user || ! $user->isKoordinator()) {
            abort(403, 'Akses ditolak: Hanya Koordinator TA yang berwenang menetapkan jadwal ujian dan menunjuk dosen penguji.');
        }

        if ($ujian->status_dokumen !== 'berkas_lengkap') {
            return redirect()->back()->with('error', 'Penetapan jadwal gagal: Berkas dokumen persyaratan ujian belum dinyatakan lengkap oleh Teaching Assistant.');
        }

        $validated = $request->validate([
            'tanggal_ujian' => ['required', 'date'],
            'dosen_penguji_1_id' => ['required', 'exists:dosen_profiles,id'],
            'dosen_penguji_2_id' => ['required', 'exists:dosen_profiles,id', 'different:dosen_penguji_1_id'],
        ], [
            'tanggal_ujian.required' => 'Tanggal pelaksanaan ujian wajib ditentukan.',
            'dosen_penguji_1_id.required' => 'Dosen Penguji 1 wajib dipilih.',
            'dosen_penguji_1_id.exists' => 'Dosen Penguji 1 tidak valid.',
            'dosen_penguji_2_id.required' => 'Dosen Penguji 2 wajib dipilih.',
            'dosen_penguji_2_id.exists' => 'Dosen Penguji 2 tidak valid.',
            'dosen_penguji_2_id.different' => 'Dosen Penguji 1 dan Dosen Penguji 2 harus merupakan dosen yang berbeda.',
        ]);

        DB::transaction(function () use ($validated, $ujian) {
            $ujian->update([
                'tanggal_ujian' => $validated['tanggal_ujian'],
                'dosen_penguji_1_id' => $validated['dosen_penguji_1_id'],
                'dosen_penguji_2_id' => $validated['dosen_penguji_2_id'],
                'status_ujian' => 'terjadwal',
            ]);
        });

        return redirect()->back()
            ->with('success', 'Tanggal pelaksanaan ujian dan 2 Dosen Penguji berhasil ditetapkan oleh Koordinator TA! Berkas siap dialokasikan fasilitasnya oleh BAAK.');
    }

    public function alokasiFasilitas(Request $request, UjianTa $ujian): RedirectResponse
    {
        $user = Auth::user();
        if (! $user || ! $user->isBaak()) {
            abort(403, 'Akses ditolak: Hanya BAAK yang berwenang mengalokasikan fasilitas dan ruangan ujian/sidang.');
        }

        if (! $ujian->tanggal_ujian) {
            return redirect()->back()->with('error', 'Alokasi fasilitas gagal: Koordinator TA belum menetapkan tanggal pelaksanaan ujian/sidang untuk berkas ini.');
        }

        if (! $ujian->dosen_penguji_1_id || ! $ujian->dosen_penguji_2_id) {
            return redirect()->back()->with('error', 'Alokasi fasilitas gagal: Koordinator TA belum menetapkan Dosen Penguji 1 dan Dosen Penguji 2 untuk ujian/sidang ini.');
        }

        $request->validate([
            'ruangan' => ['required', 'string', 'max:100'],
            'jam_mulai' => ['required'],
            'jam_selesai' => ['required'],
            'link_zoom' => ['nullable', 'url', 'max:255'],
        ], [
            'ruangan.required' => 'Ruangan ujian/sidang wajib dipilih.',
            'jam_mulai.required' => 'Jam mulai ujian/sidang wajib diisi.',
            'jam_selesai.required' => 'Jam selesai ujian/sidang wajib diisi.',
        ]);

        DB::transaction(function () use ($request, $ujian) {
            $ujian->update([
                'ruangan' => $request->ruangan,
                'jam_mulai' => $request->jam_mulai,
                'jam_selesai' => $request->jam_selesai,
                'link_zoom' => $request->link_zoom,
            ]);
        });

        return redirect()->back()
            ->with('success', 'Fasilitas ruangan, jam pelaksanaan ujian/sidang, dan link Zoom berhasil diterbitkan oleh BAAK IT Del.');
    }
}

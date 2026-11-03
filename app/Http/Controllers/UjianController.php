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
                $myTugasAkhir->loadMissing('anggota.user');
                $myUjian = UjianTa::where('tugas_akhir_id', $myTugasAkhir->id)
                    ->with([
                        'verifiedByTa',
                        'tugasAkhir.mahasiswa.user',
                        'tugasAkhir.anggota.user',
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
            'tugasAkhir.anggota.user',
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
        // - Koordinator: antrean berkas yang menunggu verifikasi pendaftaran
        // - BAAK: antrean berkas lengkap yang belum lengkap tanggal/penguji/fasilitas ruangan
        if ($user->isKoordinator()) {
            $query->orderByRaw("CASE WHEN status_dokumen = 'menunggu_verifikasi' THEN 0 ELSE 1 END");
        } elseif ($user->isBaak()) {
            $query->orderByRaw("CASE WHEN status_dokumen = 'berkas_lengkap' AND (tanggal_ujian IS NULL OR dosen_penguji_1_id IS NULL OR dosen_penguji_2_id IS NULL OR ruangan IS NULL) THEN 0 ELSE 1 END");
        }

        $allUjian = $query->latest()->paginate(20);

        // Active lecturers for examiner selection (BAAK, Koordinator, Kaprodi)
        $dosenList = ($user->isBaak() || $user->isKoordinator() || $user->isKaprodi())
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

        if (! $tugasAkhir->isAnggota($user)) {
            abort(403, 'Akses ditolak: Anda bukan anggota kelompok Tugas Akhir ini.');
        }

        if ($request->filled('tugas_akhir_id') && (int) $request->tugas_akhir_id !== $tugasAkhir->id) {
            return redirect()->back()->with('error', 'Pendaftaran ujian gagal: ID Tugas Akhir tidak sesuai dengan kelompok Anda.');
        }

        $validated = $request->validated();
        $jenisUjian = $validated['jenis_ujian'];

        // Cek syarat akademik dasar pendaftaran ujian (4 jenis resmi FITE)
        $approvedBabsCount = $tugasAkhir->logbooks()
            ->where('status_verifikasi', 'disetujui')
            ->distinct('bab_progress')
            ->count('bab_progress');

        if ($jenisUjian === 'seminar_proposal') {
            $allowedStatuses = ['proposal', 'pengerjaan', 'sempro'];
            if (! in_array($tugasAkhir->status, $allowedStatuses)) {
                return redirect()->back()->with('error', 'Pendaftaran Seminar Proposal gagal: Status Tugas Akhir Anda saat ini belum memenuhi syarat untuk Sempro.');
            }
            if ($approvedBabsCount < 1) {
                return redirect()->back()->with('error', "Pendaftaran Seminar Proposal gagal: Minimal 1 bab logbook harus disetujui oleh Dosen Pembimbing (saat ini: {$approvedBabsCount} bab disetujui).");
            }
        } elseif ($jenisUjian === 'seminar_ta_1') {
            $isAdvanced = in_array($tugasAkhir->status, ['seminar_ta', 'sidang_akhir', 'selesai']);
            if (! $isAdvanced && $approvedBabsCount < 3) {
                return redirect()->back()->with('error', "Pendaftaran Seminar TA 1 gagal: Minimal 3 bab logbook harus disetujui oleh Dosen Pembimbing (saat ini: {$approvedBabsCount} bab disetujui).");
            }
        } elseif ($jenisUjian === 'seminar_hasil') {
            $isAdvanced = in_array($tugasAkhir->status, ['sidang_akhir', 'selesai']);
            if (! $isAdvanced && $approvedBabsCount < 4) {
                return redirect()->back()->with('error', "Pendaftaran Seminar Hasil gagal: Minimal 4 bab logbook harus disetujui oleh Dosen Pembimbing (saat ini: {$approvedBabsCount} bab disetujui).");
            }
        } elseif ($jenisUjian === 'seminar_skripsi') {
            $isAdvanced = in_array($tugasAkhir->status, ['sidang_akhir', 'selesai']);
            if (! $isAdvanced && $approvedBabsCount < 5) {
                return redirect()->back()->with('error', "Pendaftaran Seminar Tugas Akhir / Skripsi gagal: Minimal 5 bab logbook harus disetujui oleh Dosen Pembimbing (saat ini: {$approvedBabsCount} bab disetujui).");
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
            ->with('success', 'Pendaftaran ujian berhasil dikirim! Berkas Anda sedang dalam antrean verifikasi Koordinator TA FITE.');
    }

    public function verifikasiDokumen(Request $request, UjianTa $ujian): RedirectResponse
    {
        $user = Auth::user();
        if (! $user || ! $user->isKoordinator()) {
            abort(403, 'Akses ditolak: Hanya Koordinator TA yang berwenang memverifikasi dokumen pendaftaran ujian.');
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
            ? 'Dokumen dinyatakan LENGKAP & VALID oleh Koordinator TA! Disposisi diteruskan ke BAAK untuk penetapan jadwal, tim penguji, dan fasilitas ruangan.'
            : 'Dokumen ditandai KURANG LENGKAP. Catatan revisi telah diteruskan ke mahasiswa.';

        return redirect()->back()->with('success', $msg);
    }

    public function tetapkanJadwal(Request $request, UjianTa $ujian): RedirectResponse
    {
        $user = Auth::user();
        if (! $user || ! $user->isBaak()) {
            abort(403, 'Akses ditolak: Hanya BAAK yang berwenang menetapkan tanggal ujian dan menunjuk dosen penguji.');
        }

        if ($ujian->status_dokumen !== 'berkas_lengkap') {
            return redirect()->back()->with('error', 'Penetapan jadwal gagal: Berkas dokumen persyaratan ujian belum dinyatakan lengkap oleh Koordinator TA.');
        }

        $validated = $request->validate([
            'tanggal_ujian' => ['required', 'date'],
            'dosen_penguji_1_id' => ['required', 'exists:dosen_profiles,id'],
            'dosen_penguji_2_id' => ['required', 'exists:dosen_profiles,id', 'different:dosen_penguji_1_id'],
            'ruangan' => ['nullable', 'string', 'max:100'],
            'jam_mulai' => ['nullable'],
            'jam_selesai' => ['nullable'],
            'link_zoom' => ['nullable', 'url', 'max:255'],
        ], [
            'tanggal_ujian.required' => 'Tanggal pelaksanaan ujian wajib ditentukan.',
            'dosen_penguji_1_id.required' => 'Dosen Penguji 1 wajib dipilih.',
            'dosen_penguji_1_id.exists' => 'Dosen Penguji 1 tidak valid.',
            'dosen_penguji_2_id.required' => 'Dosen Penguji 2 wajib dipilih.',
            'dosen_penguji_2_id.exists' => 'Dosen Penguji 2 tidak valid.',
            'dosen_penguji_2_id.different' => 'Dosen Penguji 1 dan Dosen Penguji 2 harus merupakan dosen yang berbeda.',
        ]);

        DB::transaction(function () use ($validated, $ujian) {
            $dataToUpdate = [
                'tanggal_ujian' => $validated['tanggal_ujian'],
                'dosen_penguji_1_id' => $validated['dosen_penguji_1_id'],
                'dosen_penguji_2_id' => $validated['dosen_penguji_2_id'],
                'status_ujian' => 'terjadwal',
            ];

            if (! empty($validated['ruangan'])) {
                $dataToUpdate['ruangan'] = $validated['ruangan'];
            }
            if (! empty($validated['jam_mulai'])) {
                $dataToUpdate['jam_mulai'] = $validated['jam_mulai'];
            }
            if (! empty($validated['jam_selesai'])) {
                $dataToUpdate['jam_selesai'] = $validated['jam_selesai'];
            }
            if (array_key_exists('link_zoom', $validated)) {
                $dataToUpdate['link_zoom'] = $validated['link_zoom'];
            }

            $ujian->update($dataToUpdate);
        });

        return redirect()->back()
            ->with('success', 'Tanggal pelaksanaan ujian, 2 Dosen Penguji, dan fasilitas berhasil diterbitkan oleh BAAK IT Del.');
    }

    public function alokasiFasilitas(Request $request, UjianTa $ujian): RedirectResponse
    {
        $user = Auth::user();
        if (! $user || ! $user->isBaak()) {
            abort(403, 'Akses ditolak: Hanya BAAK yang berwenang mengalokasikan fasilitas dan ruangan ujian/sidang.');
        }

        if ($ujian->status_dokumen !== 'berkas_lengkap') {
            return redirect()->back()->with('error', 'Alokasi fasilitas gagal: Berkas pendaftaran belum diverifikasi lengkap oleh Koordinator TA.');
        }

        if (! $ujian->tanggal_ujian) {
            return redirect()->back()->with('error', 'Alokasi fasilitas gagal: Tanggal pelaksanaan ujian/sidang belum ditetapkan.');
        }

        if (! $ujian->dosen_penguji_1_id || ! $ujian->dosen_penguji_2_id) {
            return redirect()->back()->with('error', 'Alokasi fasilitas gagal: Dosen Penguji 1 dan Dosen Penguji 2 belum ditetapkan.');
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

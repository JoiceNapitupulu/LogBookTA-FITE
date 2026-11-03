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
                    ->with(['verifiedByTa', 'tugasAkhir.pembimbing1.user', 'tugasAkhir.pembimbing2.user'])
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

        $allUjian = $query->latest()->paginate(20);

        // Active lecturers for examiner selection
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
            abort(403, 'Akses ditolak: Anda belum terdaftar dalam Tugas Akhir aktif.');
        }

        $validated = $request->validated();
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
        if (! $user->isKoordinator()) {
            abort(403, 'Akses ditolak: Hanya Koordinator TA yang berwenang menetapkan jadwal ujian.');
        }

        $request->validate([
            'tanggal_ujian' => ['required', 'date'],
            'dosen_penguji_1' => ['nullable', 'string'],
            'dosen_penguji_2' => ['nullable', 'string'],
        ]);

        DB::transaction(function () use ($request, $ujian) {
            $ujian->update([
                'tanggal_ujian' => $request->tanggal_ujian,
                'status_ujian' => 'terjadwal',
            ]);
        });

        return redirect()->back()
            ->with('success', 'Tanggal pelaksanaan ujian telah ditetapkan oleh Koordinator TA! Disposisi diteruskan ke BAAK.');
    }

    public function alokasiFasilitas(Request $request, UjianTa $ujian): RedirectResponse
    {
        $user = Auth::user();
        if (! $user->isBaak()) {
            abort(403, 'Akses ditolak: Hanya BAAK yang berwenang mengalokasikan fasilitas dan ruangan ujian.');
        }

        $request->validate([
            'ruangan' => ['required', 'string', 'max:100'],
            'jam_mulai' => ['required'],
            'jam_selesai' => ['required'],
            'link_zoom' => ['nullable', 'url', 'max:255'],
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
            ->with('success', 'Fasilitas ruangan, jam ujian, dan link Zoom berhasil diterbitkan oleh BAAK IT Del.');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\DosenProfile;
use App\Models\TugasAkhir;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class KaprodiController extends Controller
{
    public function index(Request $request): View
    {
        $user = Auth::user();
        if (! $user || ! $user->isKaprodi()) {
            abort(403, 'Akses ditolak: Hanya Kaprodi yang berwenang mengakses panel eksekutif Kaprodi.');
        }

        // Program studi Kaprodi yang sedang aktif
        $kaprodiProdi = $user->kaprodiProdi() ?? $user->prodi() ?? 'S1 Sistem Informasi';

        // 1 Koordinator TA aktif di program studi Kaprodi ini
        $koordinatorProdiIni = DosenProfile::getKoordinatorByProdi($kaprodiProdi);

        // Ambil daftar dosen, kelompokkan/urutkan: prodi Kaprodi terlebih dahulu
        $dosenList = DosenProfile::with(['user', 'tugasAkhirPembimbing1', 'tugasAkhirPembimbing2'])
            ->get()
            ->sortBy(function ($dosen) use ($kaprodiProdi) {
                // Bobot 0 untuk prodi Kaprodi (tampil paling atas), 1 untuk prodi lain
                return $dosen->prodi === $kaprodiProdi ? 0 : 1;
            })
            ->values();

        $totalDosen = $dosenList->count();
        $totalKoordinator = $dosenList->where('is_koordinator_ta', true)->count();
        $totalMhsAktif = TugasAkhir::count();
        $rasioDosen = $totalDosen > 0 ? round($totalMhsAktif / $totalDosen, 1) : 0;
        $siapSidang = TugasAkhir::whereIn('status', ['seminar_ta', 'sidang_akhir'])->count();
        $selesaiCount = TugasAkhir::where('status', 'selesai')->count();
        $persentaseSiapLulus = $totalMhsAktif > 0 ? round((($siapSidang + $selesaiCount) / $totalMhsAktif) * 100, 1) : 0;

        // Sebaran status TA konsisten dengan enum di DB:
        // ['proposal', 'pengerjaan', 'sempro', 'seminar_ta', 'sidang_akhir', 'selesai', 'revisi']
        $sebaranBab = [
            'proposal' => TugasAkhir::where('status', 'proposal')->count(),
            'pengerjaan' => TugasAkhir::where('status', 'pengerjaan')->count(),
            'sempro' => TugasAkhir::where('status', 'sempro')->count(),
            'seminar_ta' => TugasAkhir::where('status', 'seminar_ta')->count(),
            'sidang_akhir' => TugasAkhir::where('status', 'sidang_akhir')->count(),
            'selesai' => $selesaiCount,
            'revisi' => TugasAkhir::where('status', 'revisi')->count(),
        ];

        // Distribusi program studi riil dari DB
        $sebaranProdi = [
            'S1 Informatika' => TugasAkhir::where('prodi', 'S1 Informatika')->count(),
            'S1 Sistem Informasi' => TugasAkhir::where('prodi', 'S1 Sistem Informasi')->count(),
            'S1 Teknik Elektro' => TugasAkhir::where('prodi', 'S1 Teknik Elektro')->count(),
        ];

        return view('kaprodi.index', compact(
            'dosenList',
            'kaprodiProdi',
            'koordinatorProdiIni',
            'totalDosen',
            'totalKoordinator',
            'totalMhsAktif',
            'rasioDosen',
            'siapSidang',
            'persentaseSiapLulus',
            'sebaranBab',
            'sebaranProdi'
        ));
    }

    /**
     * Toggle penugasan Koordinator TA.
     * Aturan:
     * 1. Kaprodi hanya bisa menetapkan koor TA di prodinya sendiri.
     * 2. Koor TA hanya ada 1 tiap prodi.
     */
    public function toggleKoordinator(Request $request, DosenProfile $dosen): RedirectResponse
    {
        $user = Auth::user();
        if (! $user || ! $user->isKaprodi()) {
            abort(403, 'Akses ditolak: Hanya Kaprodi yang berwenang mengubah penugasan Koordinator TA.');
        }

        $kaprodiProdi = $user->kaprodiProdi() ?? $user->prodi();

        // Validasi aturan: Kaprodi hanya bisa menetapkan koor TA di prodinya sendiri
        if (! $kaprodiProdi || $dosen->prodi !== $kaprodiProdi) {
            abort(403, "Akses ditolak: Anda adalah Kaprodi {$kaprodiProdi}. Anda hanya berwenang menetapkan Koordinator TA untuk program studi {$kaprodiProdi}, bukan program studi {$dosen->prodi}.");
        }

        DB::transaction(function () use ($dosen) {
            if ($dosen->is_koordinator_ta) {
                $dosen->nonaktifkanKoordinator();
            } else {
                // Otomatis menonaktifkan koordinator lama di prodi yang sama (koor TA hanya ada 1 per prodi)
                $dosen->tetapkanSebagaiKoordinator();
            }
        });

        $dosen->refresh();
        $dosenName = $dosen->user->name ?? 'Dosen';
        $status = $dosen->is_koordinator_ta
            ? "berhasil ditetapkan sebagai Koordinator TA program studi {$dosen->prodi}. Koordinator TA aktif untuk program studi {$dosen->prodi} kini tepat 1 orang."
            : "telah dinonaktifkan dari status Koordinator TA program studi {$dosen->prodi}.";

        return redirect()->back()
            ->with('success', "Penugasan dosen {$dosenName} {$status}");
    }

    /**
     * Method eksplisit untuk menetapkan seorang dosen sebagai Koordinator TA di prodinya.
     */
    public function tetapkanKoordinator(Request $request, DosenProfile $dosen): RedirectResponse
    {
        $user = Auth::user();
        if (! $user || ! $user->isKaprodi()) {
            abort(403, 'Akses ditolak: Hanya Kaprodi yang berwenang menetapkan Koordinator TA.');
        }

        $kaprodiProdi = $user->kaprodiProdi() ?? $user->prodi();

        if (! $kaprodiProdi || $dosen->prodi !== $kaprodiProdi) {
            abort(403, "Akses ditolak: Anda adalah Kaprodi {$kaprodiProdi}. Anda hanya berwenang menetapkan Koordinator TA untuk program studi {$kaprodiProdi}, bukan program studi {$dosen->prodi}.");
        }

        DB::transaction(function () use ($dosen) {
            $dosen->tetapkanSebagaiKoordinator();
        });

        $dosenName = $dosen->user->name ?? 'Dosen';

        return redirect()->back()
            ->with('success', "Dosen {$dosenName} berhasil ditetapkan sebagai Koordinator TA {$dosen->prodi}. Koordinator TA aktif pada prodi ini kini tepat 1 orang.");
    }

    /**
     * Method eksplisit untuk menonaktifkan seorang dosen dari Koordinator TA di prodinya.
     */
    public function nonaktifkanKoordinator(Request $request, DosenProfile $dosen): RedirectResponse
    {
        $user = Auth::user();
        if (! $user || ! $user->isKaprodi()) {
            abort(403, 'Akses ditolak: Hanya Kaprodi yang berwenang menonaktifkan Koordinator TA.');
        }

        $kaprodiProdi = $user->kaprodiProdi() ?? $user->prodi();

        if (! $kaprodiProdi || $dosen->prodi !== $kaprodiProdi) {
            abort(403, "Akses ditolak: Anda adalah Kaprodi {$kaprodiProdi}. Anda hanya berwenang mengelola Koordinator TA untuk program studi {$kaprodiProdi}, bukan program studi {$dosen->prodi}.");
        }

        DB::transaction(function () use ($dosen) {
            $dosen->nonaktifkanKoordinator();
        });

        $dosenName = $dosen->user->name ?? 'Dosen';

        return redirect()->back()
            ->with('success', "Dosen {$dosenName} berhasil dinonaktifkan dari Koordinator TA {$dosen->prodi}.");
    }
}

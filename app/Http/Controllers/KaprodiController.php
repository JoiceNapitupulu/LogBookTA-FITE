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

        $dosenList = DosenProfile::with(['user', 'tugasAkhirPembimbing1', 'tugasAkhirPembimbing2'])->get();

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
            'S1 Teknik Komputer' => TugasAkhir::where('prodi', 'S1 Teknik Komputer')->count(),
        ];

        return view('kaprodi.index', compact(
            'dosenList',
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

    public function toggleKoordinator(Request $request, DosenProfile $dosen): RedirectResponse
    {
        $user = Auth::user();
        if (! $user || ! $user->isKaprodi()) {
            abort(403, 'Akses ditolak: Hanya Kaprodi yang berwenang mengubah penugasan Koordinator TA.');
        }

        DB::transaction(function () use ($dosen) {
            $dosen->update([
                'is_koordinator_ta' => ! $dosen->is_koordinator_ta,
            ]);
        });

        $dosenName = $dosen->user->name ?? 'Dosen';
        $status = $dosen->is_koordinator_ta ? 'diaktifkan sebagai Koordinator TA.' : 'dinonaktifkan dari status Koordinator TA.';

        return redirect()->back()
            ->with('success', "Penugasan dosen {$dosenName} {$status}");
    }
}

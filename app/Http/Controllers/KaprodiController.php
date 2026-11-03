<?php

namespace App\Http\Controllers;

use App\Models\DosenProfile;
use App\Models\TugasAkhir;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class KaprodiController extends Controller
{
    public function index(Request $request): View
    {
        $dosenList = DosenProfile::with(['user', 'tugasAkhirPembimbing1', 'tugasAkhirPembimbing2'])->get();

        $totalMhsAktif = TugasAkhir::count();
        $proyeksiLulus = 89.4; // Persentase kelulusan tepat waktu FITE
        $rasioDosen = $dosenList->count() > 0 ? round($totalMhsAktif / $dosenList->count(), 1) : 0;
        $siapSidang = TugasAkhir::whereIn('status', ['seminar_ta', 'sidang_akhir'])->count();

        // Sebaran bab progres
        $sebaranBab = [
            'proposal' => TugasAkhir::where('status', 'proposal')->count(),
            'bab_1_2' => TugasAkhir::where('status', 'pengerjaan')->count(),
            'sempro' => TugasAkhir::where('status', 'sempro')->count(),
            'seminar_ta' => TugasAkhir::where('status', 'seminar_ta')->count(),
            'sidang_akhir' => TugasAkhir::where('status', 'sidang_akhir')->count(),
            'selesai' => TugasAkhir::where('status', 'selesai')->count(),
        ];

        return view('kaprodi.index', compact(
            'dosenList',
            'totalMhsAktif',
            'proyeksiLulus',
            'rasioDosen',
            'siapSidang',
            'sebaranBab'
        ));
    }

    public function toggleKoordinator(Request $request, DosenProfile $dosen): RedirectResponse
    {
        if (! Auth::user()->isKaprodi()) {
            abort(403, 'Akses ditolak: Hanya Kaprodi yang berwenang mengubah penugasan Koordinator TA.');
        }

        DB::transaction(function () use ($dosen) {
            $dosen->update([
                'is_koordinator_ta' => !$dosen->is_koordinator_ta,
            ]);
        });

        $status = $dosen->is_koordinator_ta ? 'diaktifkan sebagai Koordinator TA.' : 'dinonaktifkan dari Koordinator TA.';

        return redirect()->back()
            ->with('success', "Penugasan dosen {$dosen->user->name} {$status}");
    }
}

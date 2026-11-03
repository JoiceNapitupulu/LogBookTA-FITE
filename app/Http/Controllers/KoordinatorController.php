<?php

namespace App\Http\Controllers;

use App\Models\DosenProfile;
use App\Models\MahasiswaProfile;
use App\Models\TugasAkhir;
use App\Models\UjianTa;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class KoordinatorController extends Controller
{
    public function index(Request $request): View
    {
        $user = Auth::user();
        if (! $user || ! $user->isKoordinator()) {
            abort(403, 'Akses ditolak: Hanya Koordinator TA yang berwenang mengakses panel ini.');
        }

        $query = TugasAkhir::with([
            'mahasiswa.user',
            'pembimbing1.user',
            'pembimbing2.user',
            'logbooks',
            'ujianTa',
        ]);

        if ($request->filled('prodi')) {
            $query->where('prodi', $request->prodi);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $tugasAkhirList = $query->paginate(20)->withQueryString();

        $dosenList = DosenProfile::with('user')->get();
        $unassignedMhs = MahasiswaProfile::where('is_ta', true)
            ->whereDoesntHave('tugasAkhir')
            ->with('user')
            ->get();

        // Metrik Ringkasan Khusus Koordinator TA
        $totalTaAktif = TugasAkhir::count();
        $menungguAssignCount = TugasAkhir::whereNull('dosen_pembimbing_1_id')->count() + $unassignedMhs->count();
        $siapJadwalCount = UjianTa::siapJadwal()->count();
        $totalSiapSidang = TugasAkhir::whereIn('status', ['seminar_ta', 'sidang_akhir'])->count();

        return view('koordinator.index', compact(
            'tugasAkhirList',
            'dosenList',
            'unassignedMhs',
            'totalTaAktif',
            'menungguAssignCount',
            'siapJadwalCount',
            'totalSiapSidang'
        ));
    }

    public function assignPembimbing(Request $request): RedirectResponse
    {
        $user = Auth::user();
        if (! $user || ! $user->isKoordinator()) {
            abort(403, 'Akses ditolak: Hanya Koordinator TA yang berwenang menetapkan Dosen Pembimbing.');
        }

        $request->validate([
            'tugas_akhir_id' => ['required', 'exists:tugas_akhir,id'],
            'dosen_pembimbing_1_id' => ['required', 'exists:dosen_profiles,id'],
            'dosen_pembimbing_2_id' => ['nullable', 'exists:dosen_profiles,id', 'different:dosen_pembimbing_1_id'],
        ]);

        DB::transaction(function () use ($request) {
            $ta = TugasAkhir::findOrFail($request->tugas_akhir_id);
            $ta->update([
                'dosen_pembimbing_1_id' => $request->dosen_pembimbing_1_id,
                'dosen_pembimbing_2_id' => $request->dosen_pembimbing_2_id,
            ]);
        });

        return redirect()->back()
            ->with('success', 'Penetapan Dosen Pembimbing 1 & 2 berhasil disimpan oleh Koordinator TA.');
    }
}

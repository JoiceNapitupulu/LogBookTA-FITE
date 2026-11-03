<?php

namespace App\Http\Controllers;

use App\Models\ArsipTugasAkhir;
use App\Models\DosenProfile;
use App\Models\JanjiBimbingan;
use App\Models\Logbook;
use App\Models\MahasiswaProfile;
use App\Models\TugasAkhir;
use App\Models\UjianTa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user = Auth::user();

        // 1. Context data for Mahasiswa
        $mahasiswaProfile = null;
        $tugasAkhir = null;
        $logbooks = collect();
        $janjiBimbingan = collect();
        $ujianTa = null;

        if ($user->isMahasiswa()) {
            $mahasiswaProfile = MahasiswaProfile::where('user_id', $user->id)
                ->with(['tugasAkhir' => function ($query) {
                    $query->with([
                        'pembimbing1.user',
                        'pembimbing2.user',
                        'logbooks' => fn ($q) => $q->with('verifiedBy.user')->latest('tanggal'),
                        'janjiBimbingan' => fn ($q) => $q->with('dosen.user')->latest('tanggal_waktu'),
                        'ujianTa' => fn ($q) => $q->with('verifiedByTa')->latest(),
                    ]);
                }])
                ->first();

            if ($mahasiswaProfile && $mahasiswaProfile->tugasAkhir) {
                $tugasAkhir = $mahasiswaProfile->tugasAkhir;
                $logbooks = $tugasAkhir->logbooks;
                $janjiBimbingan = $tugasAkhir->janjiBimbingan;
                $ujianTa = $tugasAkhir->ujianTa->first();
            }
        }

        // 2. Context data for Dosen Pembimbing
        $dosenProfile = null;
        $mahasiswaBimbingan = collect();
        $pendingReviewsCount = 0;
        $upcomingMeetingsCount = 0;

        if ($user->isDosen() || $user->isKaprodi()) {
            $dosenProfile = DosenProfile::where('user_id', $user->id)->first();
            if ($dosenProfile) {
                $mahasiswaBimbingan = TugasAkhir::where(function ($q) use ($dosenProfile) {
                    $q->where('dosen_pembimbing_1_id', $dosenProfile->id)
                        ->orWhere('dosen_pembimbing_2_id', $dosenProfile->id);
                })
                ->with([
                    'mahasiswa.user',
                    'logbooks' => fn ($q) => $q->latest('tanggal')->limit(1),
                    'pembimbing1.user',
                    'pembimbing2.user',
                ])
                ->get();

                $tugasAkhirIds = $mahasiswaBimbingan->pluck('id');
                $pendingReviewsCount = Logbook::whereIn('tugas_akhir_id', $tugasAkhirIds)
                    ->where('status_verifikasi', 'pending')
                    ->count();

                $upcomingMeetingsCount = JanjiBimbingan::where('dosen_id', $dosenProfile->id)
                    ->where('tanggal_waktu', '>=', now())
                    ->where('status', 'disetujui')
                    ->count();
            }
        }

        // 3. Context metrics for Executive / Kaprodi / Koordinator / TA / BAAK
        $totalTaAktif = TugasAkhir::count();
        $totalMhsSiapSidang = TugasAkhir::whereIn('status', ['seminar_ta', 'sidang_akhir'])->count();
        $totalArsipCount = ArsipTugasAkhir::count();
        $pendingUjianCount = UjianTa::where('status_dokumen', 'menunggu_verifikasi')->count();
        $unassignedExamsCount = UjianTa::whereNull('tanggal_ujian')->where('status_dokumen', 'berkas_lengkap')->count();
        $unscheduledBaakCount = UjianTa::whereNotNull('tanggal_ujian')->whereNull('ruangan')->count();

        // All active lecturers for coordinator & supervisor assignment
        $allDosen = DosenProfile::with('user')->get();

        return view('dashboard.index', compact(
            'user',
            'mahasiswaProfile',
            'tugasAkhir',
            'logbooks',
            'janjiBimbingan',
            'ujianTa',
            'dosenProfile',
            'mahasiswaBimbingan',
            'pendingReviewsCount',
            'upcomingMeetingsCount',
            'totalTaAktif',
            'totalMhsSiapSidang',
            'totalArsipCount',
            'pendingUjianCount',
            'unassignedExamsCount',
            'unscheduledBaakCount',
            'allDosen'
        ));
    }
}

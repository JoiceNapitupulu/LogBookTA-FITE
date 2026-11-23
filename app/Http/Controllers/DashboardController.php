<?php

namespace App\Http\Controllers;

use App\Models\ArsipTugasAkhir;
use App\Models\DosenProfile;
use App\Models\JanjiBimbingan;
use App\Models\Logbook;
use App\Models\MahasiswaProfile;
use App\Models\TugasAkhir;
use App\Models\UjianTa;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View|RedirectResponse
    {
        $user = Auth::user();

        // Mahasiswa Non-TA langsung diarahkan ke Pustaka Arsip
        if ($user->isMahasiswaNonTa()) {
            return redirect()->route('arsip.index');
        }

        // 1. Context data for Mahasiswa
        $mahasiswaProfile = null;
        $tugasAkhir = null;
        $logbooks = collect();
        $janjiBimbingan = collect();
        $ujianTa = null;
        $taSteps = [];

        if ($user->isMahasiswa()) {
            $mahasiswaProfile = $user->mahasiswaProfile;
            $tugasAkhir = $user->tugasAkhir();

            if ($tugasAkhir) {
                $tugasAkhir->loadMissing([
                    'pembimbing1.user',
                    'pembimbing2.user',
                    'anggota.user',
                    'logbooks' => fn ($q) => $q->with('verifiedBy.user')->latest('tanggal'),
                    'janjiBimbingan' => fn ($q) => $q->with('dosen.user')->latest('tanggal_waktu'),
                    'ujianTa' => fn ($q) => $q->with('verifiedByTa')->latest(),
                ]);

                $logbooks = $tugasAkhir->logbooks;
                $janjiBimbingan = $tugasAkhir->janjiBimbingan;
                $ujianTa = $tugasAkhir->ujianTa->first();
                $taSteps = $this->buildOfficialTaSteps($tugasAkhir, $logbooks);
            }
        }

        // 2. Context data for Dosen Pembimbing
        $dosenProfile = null;
        $mahasiswaBimbingan = collect();
        $pendingReviewsCount = 0;
        $upcomingMeetingsCount = 0;

        if ($user->isDosen()) {
            $dosenProfile = $user->dosenProfile ?? DosenProfile::where('user_id', $user->id)->first();

            if ($dosenProfile) {
                $mahasiswaBimbingan = $dosenProfile->tugasAkhirBimbingan()
                    ->with([
                        'mahasiswa.user',
                        'anggota.user',
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

        // 3. Context metrics for Koordinator / TA / BAAK
        $totalTaAktif = TugasAkhir::count();
        $totalMhsSiapSidang = TugasAkhir::whereIn('status', ['seminar_ta', 'sidang_akhir'])->count();
        $totalArsipCount = ArsipTugasAkhir::count();
        $pendingUjianCount = UjianTa::menungguVerifikasi()->count();
        $unassignedExamsCount = UjianTa::siapJadwal()->count();
        $unscheduledBaakCount = UjianTa::menungguFasilitas()->count();

        $menungguAssignCount = TugasAkhir::whereNull('dosen_pembimbing_1_id')->count() +
            MahasiswaProfile::where('is_ta', true)->whereDoesntHave('tugasAkhir')->count();

        // All active lecturers for coordinator & supervisor assignment
        $allDosen = DosenProfile::with('user')->get();

        return view('dashboard.index', compact(
            'user',
            'mahasiswaProfile',
            'tugasAkhir',
            'logbooks',
            'janjiBimbingan',
            'ujianTa',
            'taSteps',
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
            'menungguAssignCount',
            'allDosen'
        ));
    }

    /**
     * Menyusun 10 tahapan resmi progres Tugas Akhir mahasiswa
     * Urutan wajib:
     * 1. Proposal
     * 2. Bab 1
     * 3. Bab 2
     * 4. Bab 3
     * 5. Seminar TA 1
     * 6. Bab 5
     * 7. Bab 6
     * 8. Seminar Hasil
     * 9. Bab 7
     * 10. Sidang akhir
     */
    private function buildOfficialTaSteps(TugasAkhir $tugasAkhir, $logbooks): array
    {
        $ujianList = $tugasAkhir->ujianTa ?? collect();
        $memberIds = $tugasAkhir->anggota->pluck('id')->push($tugasAkhir->mahasiswa_id)->filter()->unique();

        // 1. Status Proposal
        // Selesai jika proposal kelompok lolos, dospem sudah ditugaskan, atau status TA sudah melewati proposal
        $hasLolosProposal = \App\Models\ProposalTa::whereIn('mahasiswa_id', $memberIds)
            ->where('status', 'lolos')
            ->exists();

        $hasSubmittedProposal = \App\Models\ProposalTa::whereIn('mahasiswa_id', $memberIds)
            ->whereIn('status', ['diajukan', 'tahap1_direview'])
            ->exists();

        $isProposalApproved = $hasLolosProposal 
            || !empty($tugasAkhir->dosen_pembimbing_1_id)
            || in_array($tugasAkhir->status, ['pengerjaan', 'sempro', 'seminar_ta', 'seminar_ta_1', 'seminar_hasil', 'sidang_akhir', 'selesai']);

        if ($isProposalApproved) {
            $proposalStatus = 'approved';
            $proposalLabel = 'Disetujui';
        } elseif ($hasSubmittedProposal || $tugasAkhir->status === 'proposal') {
            $proposalStatus = 'pending';
            $proposalLabel = 'Menunggu';
        } else {
            $proposalStatus = 'empty';
            $proposalLabel = 'Belum';
        }

        // Helper untuk menghitung status bab logbook
        $getBabStatus = function (string $babName) use ($logbooks): array {
            $normalizedTarget = strtoupper(trim($babName));
            $matchedLogs = $logbooks->filter(function ($log) use ($normalizedTarget) {
                $babs = [];
                if (!empty($log->bab_progresses)) {
                    $babs = is_array($log->bab_progresses)
                        ? $log->bab_progresses
                        : (json_decode($log->bab_progresses, true) ?: []);
                }
                if (!empty($log->bab_progress) && !in_array($log->bab_progress, $babs)) {
                    $babs[] = $log->bab_progress;
                }

                $upperBabs = array_map(fn($b) => strtoupper(trim((string)$b)), $babs);

                if (in_array($normalizedTarget, $upperBabs)) {
                    return true;
                }

                if ($normalizedTarget === 'BAB 5' && in_array('PENGUJIAN', $upperBabs)) {
                    return true;
                }

                return false;
            });

            if ($matchedLogs->where('status_verifikasi', 'disetujui')->isNotEmpty()) {
                return ['approved', 'Disetujui'];
            }
            if ($matchedLogs->where('status_verifikasi', 'revisi')->isNotEmpty()) {
                return ['revision', 'Revisi'];
            }
            if ($matchedLogs->where('status_verifikasi', 'pending')->isNotEmpty()) {
                return ['pending', 'Menunggu'];
            }

            return ['empty', 'Belum'];
        };

        // Helper untuk menghitung status Ujian/Seminar
        $getExamStatus = function (string $jenisUjian, array $bypassTaStatuses = []) use ($ujianList, $tugasAkhir): array {
            $exam = $ujianList->firstWhere('jenis_ujian', $jenisUjian);

            // Cek apakah status tugas akhir sudah melampaui tahapan ini
            if (!empty($bypassTaStatuses) && in_array($tugasAkhir->status, $bypassTaStatuses)) {
                return ['approved', 'Disetujui'];
            }

            if (!$exam) {
                return ['empty', 'Belum'];
            }

            // Selesai jika berkas lengkap, terjadwal, atau lulus
            $isComplete = $exam->status_dokumen === 'berkas_lengkap'
                || in_array($exam->status_ujian, ['terjadwal', 'lulus'])
                || $exam->status_persetujuan_pembimbing === 'diizinkan';

            if ($isComplete) {
                return ['approved', 'Disetujui'];
            }

            // Revisi jika berkas kurang atau ditolak pembimbing
            if ($exam->status_dokumen === 'berkas_kurang' || $exam->status_persetujuan_pembimbing === 'tidak_diizinkan') {
                return ['revision', 'Revisi'];
            }

            // Menunggu jika masih diverifikasi atau diajukan
            if ($exam->status_dokumen === 'menunggu_verifikasi' || $exam->status_ujian === 'diajukan' || $exam->status_persetujuan_pembimbing === 'menunggu_verifikasi') {
                return ['pending', 'Menunggu'];
            }

            return ['empty', 'Belum'];
        };

        [$bab1Status, $bab1Label] = $getBabStatus('BAB 1');
        [$bab2Status, $bab2Label] = $getBabStatus('BAB 2');
        [$bab3Status, $bab3Label] = $getBabStatus('BAB 3');
        [$sem1Status, $sem1Label] = $getExamStatus('seminar_ta_1', ['seminar_hasil', 'sidang_akhir', 'selesai']);
        [$bab5Status, $bab5Label] = $getBabStatus('BAB 5');
        [$bab6Status, $bab6Label] = $getBabStatus('BAB 6');
        [$semHasilStatus, $semHasilLabel] = $getExamStatus('seminar_hasil', ['sidang_akhir', 'selesai']);
        [$bab7Status, $bab7Label] = $getBabStatus('BAB 7');
        [$sidangStatus, $sidangLabel] = $getExamStatus('seminar_skripsi', ['selesai']);

        return [
            [
                'order' => 1,
                'name' => 'Proposal',
                'type' => 'proposal',
                'url' => route('topik.index'),
                'status' => $proposalStatus,
                'label' => $proposalLabel,
            ],
            [
                'order' => 2,
                'name' => 'Bab 1',
                'type' => 'bab',
                'bab' => 'BAB 1',
                'url' => route('logbook.index', ['bab' => 'BAB 1']),
                'status' => $bab1Status,
                'label' => $bab1Label,
            ],
            [
                'order' => 3,
                'name' => 'Bab 2',
                'type' => 'bab',
                'bab' => 'BAB 2',
                'url' => route('logbook.index', ['bab' => 'BAB 2']),
                'status' => $bab2Status,
                'label' => $bab2Label,
            ],
            [
                'order' => 4,
                'name' => 'Bab 3',
                'type' => 'bab',
                'bab' => 'BAB 3',
                'url' => route('logbook.index', ['bab' => 'BAB 3']),
                'status' => $bab3Status,
                'label' => $bab3Label,
            ],
            [
                'order' => 5,
                'name' => 'Seminar TA 1',
                'type' => 'seminar',
                'jenis_ujian' => 'seminar_ta_1',
                'url' => route('ujian.index'),
                'status' => $sem1Status,
                'label' => $sem1Label,
            ],
            [
                'order' => 6,
                'name' => 'Bab 5',
                'type' => 'bab',
                'bab' => 'BAB 5',
                'url' => route('logbook.index', ['bab' => 'BAB 5']),
                'status' => $bab5Status,
                'label' => $bab5Label,
            ],
            [
                'order' => 7,
                'name' => 'Bab 6',
                'type' => 'bab',
                'bab' => 'BAB 6',
                'url' => route('logbook.index', ['bab' => 'BAB 6']),
                'status' => $bab6Status,
                'label' => $bab6Label,
            ],
            [
                'order' => 8,
                'name' => 'Seminar Hasil',
                'type' => 'seminar',
                'jenis_ujian' => 'seminar_hasil',
                'url' => route('ujian.index'),
                'status' => $semHasilStatus,
                'label' => $semHasilLabel,
            ],
            [
                'order' => 9,
                'name' => 'Bab 7',
                'type' => 'bab',
                'bab' => 'BAB 7',
                'url' => route('logbook.index', ['bab' => 'BAB 7']),
                'status' => $bab7Status,
                'label' => $bab7Label,
            ],
            [
                'order' => 10,
                'name' => 'Sidang akhir',
                'type' => 'seminar',
                'jenis_ujian' => 'seminar_skripsi',
                'url' => route('ujian.index'),
                'status' => $sidangStatus,
                'label' => $sidangLabel,
            ],
        ];
    }
}

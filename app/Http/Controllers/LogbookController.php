<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLogbookRequest;
use App\Http\Requests\UpdateLogbookReviewRequest;
use App\Models\Logbook;
use App\Models\TugasAkhir;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LogbookController extends Controller
{
    public function index(Request $request): View
    {
        $user = Auth::user();
        $query = Logbook::with(['tugasAkhir.mahasiswa.user', 'verifiedBy.user']);
        $myTa = null;

        if ($user->isMahasiswa()) {
            $myTa = $user->tugasAkhir();

            if ($myTa) {
                $query->where('tugas_akhir_id', $myTa->id);
            } else {
                $query->whereRaw('1 = 0');
            }
        } elseif ($user->isDosen()) {
            $dosenProfile = $user->dosenProfile;
            if ($dosenProfile) {
                $query->whereHas('tugasAkhir', function ($q) use ($dosenProfile) {
                    $q->where('dosen_pembimbing_1_id', $dosenProfile->id)
                      ->orWhere('dosen_pembimbing_2_id', $dosenProfile->id);
                });
            }
        }

        // Filters
        if ($request->filled('status')) {
            $query->where('status_verifikasi', $request->status);
        }
        if ($request->filled('bab')) {
            $query->where('bab_progress', $request->bab);
        }

        $logbooks = $query->latest('tanggal')->paginate(15)->withQueryString();

        return view('logbook.index', compact('logbooks', 'myTa'));
    }

    public function store(StoreLogbookRequest $request): RedirectResponse
    {
        $user = $request->user() ?? Auth::user();
        $validated = $request->validated();

        if ($user && $user->isMahasiswa()) {
            $tugasAkhir = $user->tugasAkhir();
            if (! $tugasAkhir) {
                abort(403, 'Akses ditolak: Anda belum terdaftar dalam pengerjaan Tugas Akhir.');
            }
            $validated['tugas_akhir_id'] = $tugasAkhir->id;
        }

        if ($request->hasFile('file_dokumen_mhs')) {
            $path = $request->file('file_dokumen_mhs')->store('uploads/logbooks', 'public');
            $validated['file_dokumen_mhs'] = $path;
        }

        $validated['status_verifikasi'] = 'pending';

        Logbook::create($validated);

        return redirect()->back()
            ->with('success', 'Entri logbook baru berhasil dicatat dan diajukan ke Dosen Pembimbing.');
    }

    public function updateReview(UpdateLogbookReviewRequest $request, Logbook $logbook): RedirectResponse
    {
        $user = $request->user() ?? Auth::user();
        if (! $user || ! $user->isDosen()) {
            abort(403, 'Akses ditolak: Hanya Dosen Pembimbing yang berwenang mereview logbook mahasiswa.');
        }

        $dosenProfile = $user->dosenProfile;
        if (! $dosenProfile || ! $dosenProfile->isPembimbingOf($logbook->tugasAkhir)) {
            abort(403, 'Akses ditolak: Anda bukan dosen pembimbing untuk tugas akhir mahasiswa ini.');
        }

        $validated = $request->validated();

        if ($request->hasFile('file_revisi_dosen')) {
            $path = $request->file('file_revisi_dosen')->store('uploads/revisi', 'public');
            $validated['file_revisi_dosen'] = $path;
        }

        $validated['verified_by_dosen_id'] = $dosenProfile->id;

        $logbook->update($validated);

        return redirect()->back()
            ->with('success', 'Catatan evaluasi & status verifikasi logbook berhasil disimpan.');
    }
}

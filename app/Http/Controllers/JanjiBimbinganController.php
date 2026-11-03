<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreJanjiBimbinganRequest;
use App\Models\DosenProfile;
use App\Models\JanjiBimbingan;
use App\Models\TugasAkhir;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class JanjiBimbinganController extends Controller
{
    public function index(Request $request): View
    {
        $user = Auth::user();
        $query = JanjiBimbingan::with(['tugasAkhir.mahasiswa.user', 'dosen.user']);

        if ($user->isMahasiswa()) {
            $tugasAkhir = TugasAkhir::whereHas('mahasiswa', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })->first();

            if ($tugasAkhir) {
                $query->where('tugas_akhir_id', $tugasAkhir->id);
            } else {
                $query->whereRaw('1 = 0');
            }
        } elseif ($user->isDosen() || $user->isKaprodi()) {
            $dosenProfile = $user->dosenProfile;
            if ($dosenProfile) {
                $query->where('dosen_id', $dosenProfile->id);
            }
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $janjiList = $query->latest('tanggal_waktu')->paginate(15)->withQueryString();

        // Get active lecturers for booking dropdown
        $dosenList = DosenProfile::with('user')->get();

        // Get user's active tugas akhir for modal
        $myTugasAkhir = null;
        if ($user->isMahasiswa()) {
            $myTugasAkhir = $user->tugasAkhir();
        }

        return view('bimbingan.index', compact('janjiList', 'dosenList', 'myTugasAkhir'));
    }

    public function store(StoreJanjiBimbinganRequest $request): RedirectResponse
    {
        $user = $request->user() ?? Auth::user();
        $validated = $request->validated();

        if ($user && $user->isMahasiswa()) {
            $tugasAkhir = $user->tugasAkhir();
            if (! $tugasAkhir) {
                abort(403, 'Akses ditolak: Anda belum memiliki Tugas Akhir aktif.');
            }
            $validated['tugas_akhir_id'] = $tugasAkhir->id;
            $validated['inisiator'] = 'mahasiswa';
        } else {
            $validated['inisiator'] = 'dosen';
            if ($user?->dosenProfile) {
                $validated['dosen_id'] = $user->dosenProfile->id;
            }
        }

        $validated['status'] = 'menunggu_persetujuan';

        DB::transaction(function () use ($validated) {
            JanjiBimbingan::create($validated);
        });

        return redirect()->back()
            ->with('success', 'Jadwal janji bimbingan berhasil diajukan dan dikirimkan!');
    }

    public function updateStatus(Request $request, JanjiBimbingan $janjiBimbingan): RedirectResponse
    {
        if (! Auth::user()->isDosen()) {
            abort(403, 'Akses ditolak: Hanya Dosen yang berwenang memperbarui status janji bimbingan.');
        }

        $request->validate([
            'status' => ['required', 'in:disetujui,ditolak,selesai'],
            'alasan_penolakan' => ['nullable', 'string', 'max:500'],
        ]);

        DB::transaction(function () use ($request, $janjiBimbingan) {
            $janjiBimbingan->update([
                'status' => $request->status,
                'alasan_penolakan' => $request->alasan_penolakan,
            ]);
        });

        $message = $request->status === 'disetujui'
            ? 'Janji bimbingan telah disetujui!'
            : ($request->status === 'ditolak' ? 'Janji bimbingan telah ditolak.' : 'Sesi bimbingan ditandai telah selesai.');

        return redirect()->back()->with('success', $message);
    }
}

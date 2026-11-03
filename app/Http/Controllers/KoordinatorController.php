<?php

namespace App\Http\Controllers;

use App\Models\AnggotaTugasAkhir;
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
            'anggota.user',
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

        // Daftar mahasiswa eligible yang belum terdaftar di kelompok TA manapun
        $eligibleMhsList = MahasiswaProfile::with('user')
            ->eligibleAnggotaTa()
            ->orderBy('prodi')
            ->orderBy('nim')
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
            'eligibleMhsList',
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

    public function addAnggota(Request $request, TugasAkhir $tugasAkhir): RedirectResponse
    {
        $user = Auth::user();
        if (! $user || ! $user->isKoordinator()) {
            abort(403, 'Akses ditolak: Hanya Koordinator TA yang berwenang mengelola anggota Tugas Akhir.');
        }

        $validated = $request->validate([
            'mahasiswa_id' => ['required', 'exists:mahasiswa_profiles,id'],
        ], [
            'mahasiswa_id.required' => 'Mahasiswa wajib dipilih.',
            'mahasiswa_id.exists' => 'Data mahasiswa tidak ditemukan.',
        ]);

        // 1. Cek batas maksimal anggota (max 3)
        if (! $tugasAkhir->canAddAnggota()) {
            return redirect()->back()->with('error', 'Gagal menambah anggota: Kelompok Tugas Akhir telah mencapai batas maksimal (3 mahasiswa).');
        }

        $mahasiswa = MahasiswaProfile::with('user')->findOrFail($validated['mahasiswa_id']);

        // 2. Cek apakah mahasiswa sudah terdaftar di Tugas Akhir manapun
        $existingMembership = AnggotaTugasAkhir::where('mahasiswa_id', $mahasiswa->id)->first();
        if ($existingMembership) {
            return redirect()->back()->with('error', "Gagal: Mahasiswa {$mahasiswa->user->name} sudah terdaftar pada Tugas Akhir lain.");
        }

        // 3. Tambahkan ke pivot anggota_tugas_akhir dan pastikan status is_ta aktif
        DB::transaction(function () use ($tugasAkhir, $mahasiswa) {
            if (! $mahasiswa->is_ta) {
                $mahasiswa->update(['is_ta' => true]);
            }

            AnggotaTugasAkhir::create([
                'tugas_akhir_id' => $tugasAkhir->id,
                'mahasiswa_id' => $mahasiswa->id,
            ]);

            // Sinkronisasi legacy mahasiswa_id jika sebelumnya null
            if (! $tugasAkhir->mahasiswa_id) {
                $tugasAkhir->update(['mahasiswa_id' => $mahasiswa->id]);
            }
        });

        return redirect()->back()
            ->with('success', "Mahasiswa {$mahasiswa->user->name} ({$mahasiswa->nim}) berhasil ditambahkan ke dalam kelompok Tugas Akhir.");
    }

    public function removeAnggota(TugasAkhir $tugasAkhir, MahasiswaProfile $mahasiswa): RedirectResponse
    {
        $user = Auth::user();
        if (! $user || ! $user->isKoordinator()) {
            abort(403, 'Akses ditolak: Hanya Koordinator TA yang berwenang mengelola anggota Tugas Akhir.');
        }

        $membership = AnggotaTugasAkhir::where('tugas_akhir_id', $tugasAkhir->id)
            ->where('mahasiswa_id', $mahasiswa->id)
            ->first();

        if (! $membership) {
            return redirect()->back()->with('error', 'Gagal: Mahasiswa tersebut bukan anggota dari kelompok Tugas Akhir ini.');
        }

        // Validasi hapus: tidak boleh menghapus anggota terakhir (minimal 1)
        if ($tugasAkhir->jumlahAnggota() <= 1) {
            return redirect()->back()->with('error', 'Gagal: Tidak dapat menghapus anggota terakhir. Kelompok Tugas Akhir wajib memiliki minimal 1 mahasiswa.');
        }

        DB::transaction(function () use ($tugasAkhir, $membership) {
            $membership->delete();

            // Sync tugas_akhir.mahasiswa_id ke salah satu anggota tersisa agar tidak orphan
            $sisaAnggota = $tugasAkhir->anggota()->first();
            if ($sisaAnggota) {
                $tugasAkhir->update(['mahasiswa_id' => $sisaAnggota->id]);
            }
        });

        return redirect()->back()
            ->with('success', "Mahasiswa {$mahasiswa->user->name} ({$mahasiswa->nim}) berhasil dikeluarkan dari kelompok Tugas Akhir.");
    }
}

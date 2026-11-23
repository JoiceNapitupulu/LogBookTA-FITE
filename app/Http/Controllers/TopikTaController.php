<?php

namespace App\Http\Controllers;

use App\Models\DosenProfile;
use App\Models\ProposalTa;
use App\Models\TopikTa;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class TopikTaController extends Controller
{
    /**
     * Halaman utama katalog Topik & Proposal TA
     * (Menyesuaikan konteks: Mahasiswa TA (Blind), Dosen Pembimbing, atau Koordinator TA)
     */
    public function index(Request $request): View
    {
        $user = Auth::user();

        // 1. Context: Mahasiswa TA (Aturan BLIND: Dosen tidak ditampilkan)
        if ($user->isMahasiswa()) {
            $tugasAkhir = $user->tugasAkhir();
            $hasTugasAkhir = $tugasAkhir !== null;

            $query = TopikTa::dipublikasikan()->withCount('proposals');

            if ($request->filled('prodi') && $request->prodi !== 'semua') {
                $query->where(function ($q) use ($request) {
                    $q->where('prodi', $request->prodi)
                      ->orWhere('prodi', 'Semua Prodi');
                });
            }

            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('judul_topik', 'like', "%{$search}%")
                      ->orWhere('deskripsi', 'like', "%{$search}%")
                      ->orWhere('bidang', 'like', "%{$search}%");
                });
            }

            $topikList = $query->latest()->paginate(12)->withQueryString();

            // Proposal yang sudah diajukan oleh mahasiswa ini
            $myProposals = collect();
            if ($user->mahasiswaProfile) {
                $myProposals = ProposalTa::where('mahasiswa_id', $user->mahasiswaProfile->id)
                    ->with('topik')
                    ->latest()
                    ->get();
            }

            $hasAcceptedProposal = $hasTugasAkhir || $myProposals->contains(fn ($p) => $p->status === 'lolos');

            return view('topik.index-mahasiswa', compact('topikList', 'myProposals', 'hasAcceptedProposal', 'tugasAkhir', 'hasTugasAkhir'));
        }

        // 2. Context: Dosen Pembimbing
        if ($user->isDosen() && ! $user->isKoordinator()) {
            $dosenProfile = $user->dosenProfile;

            $myTopik = collect();
            $incomingProposals = collect();

            if ($dosenProfile) {
                $myTopik = TopikTa::where('dosen_id', $dosenProfile->id)
                    ->withCount('proposals')
                    ->latest()
                    ->get();

                $topikIds = $myTopik->pluck('id');
                $incomingProposals = ProposalTa::whereIn('topik_ta_id', $topikIds)
                    ->with(['topik', 'mahasiswa.user'])
                    ->latest()
                    ->get();
            }

            // Semua topik terpublikasi FITE untuk referensi
            $allPublishedTopik = TopikTa::dipublikasikan()
                ->with(['dosen.user'])
                ->latest()
                ->take(15)
                ->get();

            return view('topik.index-dosen', compact('myTopik', 'incomingProposals', 'allPublishedTopik', 'dosenProfile'));
        }

        // 3. Context: Koordinator TA (dan Staf FITE/BAAK jika membuka halaman)
        $allTopik = TopikTa::with(['dosen.user'])
            ->withCount(['proposals', 'proposals as lolos_count' => function ($q) {
                $q->where('status', 'lolos');
            }])
            ->latest()
            ->paginate(15);

        $rekapLolos = ProposalTa::where('status', 'lolos')
            ->with(['topik.dosen.user', 'mahasiswa.user'])
            ->latest('tanggal_keputusan')
            ->get();

        $dosenList = DosenProfile::with('user')->get();

        $totalTopikDipublikasikan = TopikTa::dipublikasikan()->count();
        $totalProposalMasuk = ProposalTa::count();
        $totalProposalLolos = ProposalTa::where('status', 'lolos')->count();

        return view('topik.index-koordinator', compact(
            'allTopik',
            'rekapLolos',
            'dosenList',
            'totalTopikDipublikasikan',
            'totalProposalMasuk',
            'totalProposalLolos'
        ));
    }

    /**
     * Dosen atau Koordinator membuat topik baru
     */
    public function store(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $request->validate([
            'judul_topik' => ['required', 'string', 'max:255'],
            'deskripsi' => ['nullable', 'string'],
            'bidang' => ['nullable', 'string', 'max:100'],
            'prodi' => ['required', 'string', 'max:100'],
            'kuota' => ['required', 'integer', 'min:1', 'max:10'],
            'dosen_id' => ['nullable', 'exists:dosen_profiles,id'],
        ]);

        $dosenId = null;
        if ($user->isDosen() && $user->dosenProfile) {
            $dosenId = $user->dosenProfile->id;
        } elseif ($user->isKoordinator() && $request->filled('dosen_id')) {
            $dosenId = $request->dosen_id;
        } else {
            abort(403, 'Hanya dosen pembimbing atau Koordinator TA yang dapat membuat topik.');
        }

        TopikTa::create([
            'dosen_id' => $dosenId,
            'judul_topik' => $request->judul_topik,
            'deskripsi' => $request->deskripsi,
            'bidang' => $request->bidang,
            'prodi' => $request->prodi,
            'kuota' => $request->kuota,
            'status' => $user->isKoordinator() ? 'dipublikasikan' : 'draft',
        ]);

        return redirect()->route('topik.index')
            ->with('success', 'Topik tugas akhir berhasil ditambahkan.');
    }

    /**
     * Dosen atau Koordinator memperbarui topik
     */
    public function update(Request $request, TopikTa $topik): RedirectResponse
    {
        $user = Auth::user();

        // Autorisasi: pemilik topik atau Koordinator
        if ($user->isDosen() && $user->dosenProfile?->id !== $topik->dosen_id && ! $user->isKoordinator()) {
            abort(403, 'Anda tidak berwenang mengubah topik ini.');
        }

        $request->validate([
            'judul_topik' => ['required', 'string', 'max:255'],
            'deskripsi' => ['nullable', 'string'],
            'bidang' => ['nullable', 'string', 'max:100'],
            'prodi' => ['required', 'string', 'max:100'],
            'kuota' => ['required', 'integer', 'min:1', 'max:10'],
            'status' => ['nullable', 'in:draft,dipublikasikan,ditutup'],
        ]);

        $data = $request->only(['judul_topik', 'deskripsi', 'bidang', 'prodi', 'kuota']);
        if ($request->filled('status')) {
            $data['status'] = $request->status;
        }

        $topik->update($data);

        return redirect()->route('topik.index')
            ->with('success', 'Perubahan topik berhasil disimpan.');
    }

    /**
     * Hapus topik (jika belum ada proposal)
     */
    public function destroy(TopikTa $topik): RedirectResponse
    {
        $user = Auth::user();

        if ($user->isDosen() && $user->dosenProfile?->id !== $topik->dosen_id && ! $user->isKoordinator()) {
            abort(403, 'Anda tidak berwenang menghapus topik ini.');
        }

        $topik->delete();

        return redirect()->route('topik.index')
            ->with('success', 'Topik berhasil dihapus.');
    }

    /**
     * Toggle status publikasi topik (Koordinator TA)
     */
    public function togglePublikasi(TopikTa $topik): RedirectResponse
    {
        $newStatus = $topik->status === 'dipublikasikan' ? 'draft' : 'dipublikasikan';
        $topik->update(['status' => $newStatus]);

        $statusMsg = $newStatus === 'dipublikasikan'
            ? 'Topik berhasil DIPUBLIKASIKAN ke mahasiswa (Blind: nama dosen dirahasiakan).'
            : 'Topik dikembalikan ke status DRAFT.';

        return redirect()->back()->with('success', $statusMsg);
    }

    /**
     * Koordinator menandai batas pengumpulan proposal Tahap 1 masuk
     */
    public function tandaiTahap1(TopikTa $topik): RedirectResponse
    {
        $newTahap = ! $topik->tahap1_selesai;
        $topik->update(['tahap1_selesai' => $newTahap]);

        if ($newTahap) {
            // Update status proposal yang masih diajukan menjadi 'tahap1_direview'
            $topik->proposals()->where('status', 'diajukan')->update(['status' => 'tahap1_direview']);
            $msg = 'Tahap 1 pengumpulan proposal ditutup & siap direview oleh dosen pemilik topik.';
        } else {
            $msg = 'Status Tahap 1 dibuka kembali.';
        }

        return redirect()->back()->with('success', $msg);
    }

    /**
     * Rekap proposal lolos untuk Koordinator TA
     */
    public function rekapProposalLolos(): View
    {
        $rekapLolos = ProposalTa::where('status', 'lolos')
            ->with(['topik.dosen.user', 'mahasiswa.user'])
            ->latest('tanggal_keputusan')
            ->get();

        return view('topik.rekap-lolos', compact('rekapLolos'));
    }
}

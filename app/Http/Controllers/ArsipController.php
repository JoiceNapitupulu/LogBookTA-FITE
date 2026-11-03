<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreArsipRequest;
use App\Models\ArsipTugasAkhir;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ArsipController extends Controller
{
    public function index(Request $request): View
    {
        $query = ArsipTugasAkhir::with('uploadedBy');

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sub) use ($q) {
                $sub->where('judul', 'like', "%{$q}%")
                    ->orWhere('penulis', 'like', "%{$q}%")
                    ->orWhere('nim', 'like', "%{$q}%")
                    ->orWhere('nama_dospem_1', 'like', "%{$q}%")
                    ->orWhere('kata_kunci', 'like', "%{$q}%");
            });
        }

        if ($request->filled('prodi') && $request->prodi !== 'all') {
            $query->where('prodi', $request->prodi);
        }

        if ($request->filled('tahun') && $request->tahun !== 'all') {
            $query->where('tahun_lulus', $request->tahun);
        }

        $arsipList = $query->latest('tahun_lulus')->paginate(12)->withQueryString();

        $availableYears = ArsipTugasAkhir::select('tahun_lulus')
            ->distinct()
            ->orderByDesc('tahun_lulus')
            ->pluck('tahun_lulus');

        return view('arsip.index', compact('arsipList', 'availableYears'));
    }

    public function store(StoreArsipRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        if ($request->hasFile('file_dokumen')) {
            $path = $request->file('file_dokumen')->store('uploads/arsip', 'public');
            $validated['file_dokumen'] = $path;
        }

        $validated['uploaded_by_user_id'] = Auth::id();

        ArsipTugasAkhir::create($validated);

        return redirect()->route('arsip.index')
            ->with('success', 'Naskah Tugas Akhir alumni berhasil diarsipkan ke Repositori Digital FITE.');
    }
}

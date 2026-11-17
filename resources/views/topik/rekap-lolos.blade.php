@extends('layouts.notion')

@section('title', 'Rekapitulasi Proposal Lolos Seleksi')
@section('breadcrumb', 'Rekap Proposal Lolos')

@section('content')
<div class="d-flex flex-column gap-4">

  <!-- Header & Tombol Cetak -->
  <div class="d-flex flex-column flex-md-row md:align-items-center justify-content-between gap-3 pb-3 border-bottom">
    <div>
      <h1 class="h4 fw-bold text-dark mb-1">Berita Acara Rekapitulasi Proposal Tugas Akhir Lolos Seleksi</h1>
      <p class="text-secondary small mb-0">Fakultas Informatika & Teknik Elektro (FITE) — Institut Teknologi Del</p>
    </div>

    <div class="d-flex align-items-center gap-2">
      <a href="{{ route('topik.index') }}" class="btn btn-outline-secondary btn-sm d-inline-flex align-items-center gap-1.5">
        <i class="bi bi-arrow-left"></i>
        <span>Kembali ke Panel Topik</span>
      </a>
      <button type="button" onclick="window.print()" class="btn btn-dark btn-sm d-inline-flex align-items-center gap-1.5 shadow-xs">
        <i class="bi bi-printer"></i>
        <span>Cetak Halaman (Print / PDF)</span>
      </button>
    </div>
  </div>

  <div class="card shadow-sm border-light-subtle">
    <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between">
      <div class="d-flex align-items-center gap-2">
        <span class="badge bg-success-subtle text-success border border-success-subtle">Resmi</span>
        <span class="text-secondary small">Total Mahasiswa Lolos: <strong>{{ $rekapLolos->count() }} Orang</strong></span>
      </div>
      <span class="text-secondary small">Tahun Ajaran 2025/2026</span>
    </div>

    <div class="table-responsive">
      <table class="table table-bordered table-hover align-middle mb-0" style="font-size: 0.8125rem;">
        <thead class="table-light">
          <tr>
            <th class="py-2.5 px-3 text-center" style="width: 40px;">No</th>
            <th class="py-2.5 px-3">Nama Mahasiswa & NIM</th>
            <th class="py-2.5 px-3">Program Studi</th>
            <th class="py-2.5 px-3">Judul Naskah Proposal</th>
            <th class="py-2.5 px-3">Topik Terkait</th>
            <th class="py-2.5 px-3">Dosen Pembimbing Resmi</th>
            <th class="py-2.5 px-3 text-center">Tanggal Ditetapkan</th>
          </tr>
        </thead>
        <tbody>
          @forelse ($rekapLolos as $idx => $item)
            <tr>
              <td class="text-center text-secondary py-3">{{ $idx + 1 }}</td>
              <td class="py-3">
                <strong class="text-dark d-block">{{ $item->mahasiswa->user->name ?? '-' }}</strong>
                <span class="text-secondary small font-monospace">{{ $item->mahasiswa->nim ?? '-' }}</span>
              </td>
              <td class="py-3 text-nowrap">
                <span class="badge bg-secondary-subtle text-secondary">{{ $item->mahasiswa->prodi }}</span>
              </td>
              <td class="py-3">
                <p class="fw-semibold text-dark mb-0">{{ $item->judul_proposal }}</p>
                @if ($item->file_proposal)
                  <a href="{{ asset('storage/' . $item->file_proposal) }}" target="_blank" class="small text-primary text-decoration-none">
                    <i class="bi bi-file-earmark-arrow-down me-0.5"></i>Lihat Naskah
                  </a>
                @endif
              </td>
              <td class="py-3 text-secondary">
                {{ $item->topik->judul_topik ?? '-' }}
              </td>
              <td class="py-3 text-nowrap">
                <span class="fw-bold text-dark d-block">{{ $item->topik->dosen->user->name ?? '-' }}</span>
                <span class="text-secondary small">Kode: {{ $item->topik->dosen->kode_dosen ?? '-' }}</span>
              </td>
              <td class="py-3 text-center text-nowrap text-secondary">
                {{ $item->tanggal_keputusan ? $item->tanggal_keputusan->format('d/m/Y') : '-' }}
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="7" class="py-4 text-center text-secondary">
                Belum ada data rekapitulasi proposal yang lolos seleksi.
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

</div>
@endsection

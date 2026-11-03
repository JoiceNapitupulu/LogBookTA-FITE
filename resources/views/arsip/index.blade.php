@extends('layouts.notion')

@section('title', 'Pustaka Arsip Tugas Akhir')
@section('breadcrumb', 'Pustaka Arsip TA')

@section('content')
<div class="d-flex flex-column gap-3" x-data="{ uploadModal: false }">

  <!-- Header Halaman Akademik -->
  <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 pb-3 border-bottom">
    <div>
      <h1 class="h4 fw-bold text-dark mb-1">Pustaka Arsip Tugas Akhir FITE</h1>
      <p class="text-secondary small mb-0">Basis repositori digital naskah ilmiah skripsi alumni FITE IT Del yang dapat diakses oleh seluruh mahasiswa dan dosen.</p>
    </div>

    @if (auth()->user()->isTA())
      <button @click="uploadModal = true" class="btn btn-dark btn-sm d-inline-flex align-items-center gap-1.5 shrink-0">
        <i class="bi bi-cloud-arrow-up"></i>
        <span>Unggah Arsip Tugas Akhir</span>
      </button>
    @else
      <div class="badge bg-light text-secondary border px-2.5 py-1.5 fw-normal align-self-start align-self-md-center">
        Akses: <strong class="text-dark">Katalog Publik</strong>
      </div>
    @endif
  </div>

  <!-- Search Bar & Filters Form -->
  <div class="card shadow-sm border-light-subtle">
    <div class="card-body p-3 p-md-4">
      <form method="GET" action="{{ route('arsip.index') }}" class="d-flex flex-column gap-3">
        
        <!-- Big Search Input -->
        <div class="input-group">
          <span class="input-group-text bg-light border-end-0 text-secondary">
            <i class="bi bi-search"></i>
          </span>
          <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari berdasarkan judul naskah, nama penulis, NIM, dosen pembimbing, atau kata kunci..."
                 class="form-control bg-light border-start-0 ps-0" style="font-size: 0.875rem;">
          <button type="submit" class="btn btn-dark px-3">Cari</button>
        </div>

        <!-- Filter Pills Row -->
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 pt-1" style="font-size: 0.8125rem;">
          
          <!-- Filter Prodi Pills -->
          <div class="d-flex flex-wrap align-items-center gap-1.5">
            <span class="text-secondary fw-semibold me-1">Program Studi:</span>
            <a href="{{ route('arsip.index', array_merge(request()->query(), ['prodi' => 'all'])) }}"
               class="badge {{ !request('prodi') || request('prodi') === 'all' ? 'bg-dark text-white' : 'bg-light text-dark border' }} text-decoration-none py-1.5 px-2.5">
              Semua Prodi
            </a>
            <a href="{{ route('arsip.index', array_merge(request()->query(), ['prodi' => 'S1 Informatika'])) }}"
               class="badge {{ request('prodi') === 'S1 Informatika' ? 'bg-dark text-white' : 'bg-light text-dark border' }} text-decoration-none py-1.5 px-2.5">
              S1 Informatika
            </a>
            <a href="{{ route('arsip.index', array_merge(request()->query(), ['prodi' => 'S1 Sistem Informasi'])) }}"
               class="badge {{ request('prodi') === 'S1 Sistem Informasi' ? 'bg-dark text-white' : 'bg-light text-dark border' }} text-decoration-none py-1.5 px-2.5">
              S1 Sistem Informasi
            </a>
            <a href="{{ route('arsip.index', array_merge(request()->query(), ['prodi' => 'S1 Teknik Elektro'])) }}"
               class="badge {{ request('prodi') === 'S1 Teknik Elektro' ? 'bg-dark text-white' : 'bg-light text-dark border' }} text-decoration-none py-1.5 px-2.5">
              S1 Teknik Elektro
            </a>
          </div>

          <!-- Filter Tahun Dropdown -->
          <div class="d-flex align-items-center gap-2">
            <label for="tahunSelect" class="text-secondary small fw-semibold text-nowrap mb-0">Tahun Lulus:</label>
            <select id="tahunSelect" name="tahun" onchange="this.form.submit()" class="form-select form-select-sm py-1" style="width: auto; font-size: 0.8125rem;">
              <option value="all">Semua Tahun</option>
              @foreach ($availableYears as $year)
                <option value="{{ $year }}" {{ request('tahun') == $year ? 'selected' : '' }}>{{ $year }}</option>
              @endforeach
            </select>
          </div>

        </div>

      </form>
    </div>
  </div>

  <!-- Tabel Naskah Skripsi FITE -->
  <div class="card shadow-sm border-light-subtle">
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0" style="font-size: 0.8125rem;">
        <thead class="table-light">
          <tr>
            <th class="py-2.5 px-3">Judul Naskah Skripsi</th>
            <th class="py-2.5 px-3">Penulis & NIM</th>
            <th class="py-2.5 px-3">Prodi & Tahun</th>
            <th class="py-2.5 px-3">Dosen Pembimbing</th>
            <th class="py-2.5 px-3">Kata Kunci</th>
            <th class="py-2.5 px-3 text-end">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse ($arsipList as $item)
            <tr>
              <td class="px-3 py-3" style="max-width: 320px;">
                <p class="fw-bold text-dark mb-1 lh-sm">{{ $item->judul }}</p>
                <p class="text-secondary small mb-0 line-clamp-2">{{ $item->abstrak }}</p>
              </td>
              <td class="px-3 py-3 text-nowrap">
                <p class="fw-semibold text-dark mb-0">{{ $item->penulis }}</p>
                <p class="text-secondary small mb-0">{{ $item->nim }}</p>
              </td>
              <td class="px-3 py-3 text-nowrap">
                <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle d-block mb-1 text-center">{{ $item->prodi }}</span>
                <span class="text-secondary small d-block text-center">Lulus: {{ $item->tahun_lulus }}</span>
              </td>
              <td class="px-3 py-3 text-nowrap">
                <p class="fw-medium text-dark mb-0">{{ $item->nama_dospem_1 }}</p>
                @if ($item->nama_dospem_2)
                  <p class="text-secondary small mb-0">{{ $item->nama_dospem_2 }}</p>
                @endif
              </td>
              <td class="px-3 py-3" style="max-width: 220px;">
                <div class="d-flex flex-wrap gap-1">
                  @foreach (explode(',', $item->kata_kunci) as $kw)
                    <span class="badge bg-light text-secondary border">{{ trim($kw) }}</span>
                  @endforeach
                </div>
              </td>
              <td class="px-3 py-3 text-end text-nowrap">
                <a href="{{ asset('storage/' . $item->file_dokumen) }}" target="_blank" class="btn btn-outline-dark btn-sm d-inline-flex align-items-center gap-1.5">
                  <i class="bi bi-file-earmark-pdf text-danger"></i>
                  <span>Unduh PDF</span>
                </a>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="6" class="py-4 text-center text-secondary">
                Tidak ada arsip yang sesuai dengan kriteria pencarian.
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    @if ($arsipList->hasPages())
      <div class="card-footer bg-light p-3">
        {{ $arsipList->links() }}
      </div>
    @endif
  </div>

  @if (auth()->user()->isTA())
  <!-- MODAL: UNGGAH ARSIP TUGAS AKHIR (Teaching Assistant) -->
  <div x-show="uploadModal" x-cloak class="modal-backdrop-custom">
    <div class="modal-dialog modal-dialog-centered w-100 mx-3" style="max-width: 540px;" @click.away="uploadModal = false">
      <div class="modal-content bg-white shadow-lg border-0 rounded-3">
        
        <div class="modal-header border-bottom py-3 px-4">
          <div>
            <h5 class="modal-title fs-6 fw-bold text-dark mb-0">Unggah Naskah Arsip Tugas Akhir</h5>
            <p class="text-secondary small mb-0">Pengarsipan naskah akhir skripsi alumni FITE IT Del</p>
          </div>
          <button type="button" class="btn-close" @click="uploadModal = false"></button>
        </div>

        <form action="{{ route('arsip.store') }}" method="POST" enctype="multipart/form-data">
          @csrf
          <div class="modal-body p-4 d-flex flex-column gap-3" style="font-size: 0.8125rem;">

            <div>
              <label class="form-label fw-semibold text-secondary small mb-1">Judul Lengkap Tugas Akhir</label>
              <input type="text" name="judul" required placeholder="Judul naskah tugas akhir alumni..." class="form-control form-control-sm">
            </div>

            <div class="row g-2">
              <div class="col-6">
                <label class="form-label fw-semibold text-secondary small mb-1">Nama Penulis Alumni</label>
                <input type="text" name="penulis" required placeholder="Grace Hutapea" class="form-control form-control-sm">
              </div>
              <div class="col-6">
                <label class="form-label fw-semibold text-secondary small mb-1">NIM</label>
                <input type="text" name="nim" required placeholder="11S20011" class="form-control form-control-sm">
              </div>
            </div>

            <div class="row g-2">
              <div class="col-6">
                <label class="form-label fw-semibold text-secondary small mb-1">Program Studi</label>
                <select name="prodi" required class="form-select form-select-sm">
                  <option value="S1 Informatika">S1 Informatika</option>
                  <option value="S1 Sistem Informasi">S1 Sistem Informasi</option>
                  <option value="S1 Teknik Elektro">S1 Teknik Elektro</option>
                </select>
              </div>
              <div class="col-6">
                <label class="form-label fw-semibold text-secondary small mb-1">Tahun Lulus</label>
                <input type="number" name="tahun_lulus" value="2025" min="2010" max="2030" required class="form-control form-control-sm">
              </div>
            </div>

            <div class="row g-2">
              <div class="col-6">
                <label class="form-label fw-semibold text-secondary small mb-1">Nama Pembimbing 1</label>
                <input type="text" name="nama_dospem_1" required placeholder="Dr. Johannes Harungguan" class="form-control form-control-sm">
              </div>
              <div class="col-6">
                <label class="form-label fw-semibold text-secondary small mb-1">Nama Pembimbing 2 (Opsional)</label>
                <input type="text" name="nama_dospem_2" placeholder="Marlina Valentina, M.Sc." class="form-control form-control-sm">
              </div>
            </div>

            <div>
              <label class="form-label fw-semibold text-secondary small mb-1">Kata Kunci (Pisahkan dengan koma)</label>
              <input type="text" name="kata_kunci" required placeholder="IoT, Deep Learning, Danau Toba" class="form-control form-control-sm">
            </div>

            <div>
              <label class="form-label fw-semibold text-secondary small mb-1">Abstrak Ringkas</label>
              <textarea name="abstrak" rows="3" required placeholder="Tuliskan ringkasan tujuan, metodologi, dan hasil utama..." class="form-control form-control-sm"></textarea>
            </div>

            <div>
              <label class="form-label fw-semibold text-secondary small mb-1">File Naskah Lengkap (PDF Maks. 50MB)</label>
              <input type="file" name="file_dokumen" required accept=".pdf" class="form-control form-control-sm">
            </div>

          </div>

          <div class="modal-footer border-top py-2 px-4 d-flex justify-content-end gap-2">
            <button type="button" @click="uploadModal = false" class="btn btn-outline-secondary btn-sm">Batal</button>
            <button type="submit" class="btn btn-dark btn-sm">Simpan ke Pustaka</button>
          </div>
        </form>

      </div>
    </div>
  </div>
  @endif

</div>
@endsection

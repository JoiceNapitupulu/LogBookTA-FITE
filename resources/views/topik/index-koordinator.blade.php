@extends('layouts.notion')

@section('title', 'Kelola Topik & Rekapitulasi Proposal TA')
@section('breadcrumb', 'Koordinator Topik & Proposal')

@section('content')
<div class="d-flex flex-column gap-4" x-data="{ 
    activeTab: 'kelola-topik',
    createTopikModal: false
}">

  <!-- Header Halaman & Aksi -->
  <div class="d-flex flex-column flex-md-row md:align-items-center justify-content-between gap-3 pb-3 border-bottom">
    <div>
      <h1 class="h4 fw-bold text-dark mb-1">Pengelolaan Topik & Rekapitulasi Proposal Tugas Akhir</h1>
      <p class="text-secondary small mb-0">Wewenang Koordinator TA: mempublikasikan topik blind ke mahasiswa, mengelola batas Tahap 1, dan merekap kelolosan bimbingan.</p>
    </div>

    <div class="d-flex flex-wrap align-items-center gap-2">
      <!-- Tab Selector -->
      <div class="d-flex align-items-center gap-1 bg-white p-1 rounded border shadow-xs">
        <button type="button" 
                @click="activeTab = 'kelola-topik'" 
                :class="activeTab === 'kelola-topik' ? 'btn-dark' : 'btn-light text-secondary border-0'"
                class="btn btn-sm d-inline-flex align-items-center gap-1.5 py-1 px-3">
          <i class="bi bi-grid-3x3-gap"></i>
          <span>Kelola Publikasi Topik</span>
        </button>
        <button type="button" 
                @click="activeTab = 'rekap-lolos'" 
                :class="activeTab === 'rekap-lolos' ? 'btn-dark' : 'btn-light text-secondary border-0'"
                class="btn btn-sm d-inline-flex align-items-center gap-1.5 py-1 px-3 position-relative">
          <i class="bi bi-file-earmark-check"></i>
          <span>Rekap Proposal Lolos</span>
          @if ($rekapLolos->isNotEmpty())
            <span class="badge bg-success rounded-pill ms-1">{{ $rekapLolos->count() }}</span>
          @endif
        </button>
      </div>

      <!-- Tombol Tambah Topik atas Nama Dosen -->
      <button type="button" @click="createTopikModal = true" class="btn btn-outline-dark btn-sm d-inline-flex align-items-center gap-1.5 shadow-xs">
        <i class="bi bi-plus-lg"></i>
        <span>Tambah Topik Dosen</span>
      </button>
    </div>
  </div>

  <!-- Alur Standar Operasional Koordinator TA -->
  <div class="alert alert-light border border-light-subtle d-flex align-items-start gap-2.5 mb-0 shadow-xs" role="alert">
    <i class="bi bi-info-circle-fill text-primary fs-5 shrink-0 mt-0.5"></i>
    <div class="w-100" style="font-size: 0.8125rem;">
      <strong class="text-dark d-block mb-1">Panduan Alur Penetapan Bimbingan via Topik & Proposal:</strong>
      <ol class="mb-0 ps-3 text-secondary lh-base">
        <li><strong>Publikasikan Topik:</strong> Topik dosen yang berstatus draft dipublikasikan agar mahasiswa dapat melihatnya di katalog secara <em>blind submission</em> (nama dosen pemilik dirahasiakan).</li>
        <li><strong>Tandai Tahap 1 Selesai:</strong> Setelah batas waktu pengumpulan proposal berakhir, klik tombol <em>"Tandai Tahap 1 Selesai"</em> agar dosen pemilik topik dapat memulai seleksi.</li>
        <li><strong>Rekapitulasi Lolos:</strong> Dosen pemilik topik menentukan kelolosan proposal. Mahasiswa yang lolos otomatis tercatat dan dapat dipantau pada tab <em>"Rekap Proposal Lolos"</em>.</li>
      </ol>
    </div>
  </div>

  <!-- Statistik Utama Koordinator -->
  <div class="row g-3">
    <div class="col-6 col-md-4">
      <div class="card shadow-sm border-light-subtle h-100 p-3 bg-white">
        <span class="text-secondary small fw-semibold text-uppercase d-block mb-1" style="font-size: 0.7rem;">Topik Terpublikasi</span>
        <p class="h4 fw-bold text-dark mb-0">{{ $totalTopikDipublikasikan }}</p>
        <span class="text-secondary small" style="font-size: 0.75rem;">Dari total {{ $allTopik->total() }} topik terdaftar</span>
      </div>
    </div>
    <div class="col-6 col-md-4">
      <div class="card shadow-sm border-light-subtle h-100 p-3 bg-white">
        <span class="text-secondary small fw-semibold text-uppercase d-block mb-1" style="font-size: 0.7rem;">Total Proposal Masuk</span>
        <p class="h4 fw-bold text-dark mb-0">{{ $totalProposalMasuk }}</p>
        <span class="text-secondary small" style="font-size: 0.75rem;">Diajukan oleh Mahasiswa TA</span>
      </div>
    </div>
    <div class="col-12 col-md-4">
      <div class="card shadow-sm border-success-subtle h-100 p-3 bg-success-subtle/30">
        <span class="text-success-emphasis small fw-semibold text-uppercase d-block mb-1" style="font-size: 0.7rem;">Proposal Lolos Seleksi</span>
        <p class="h4 fw-bold text-success mb-0">{{ $totalProposalLolos }}</p>
        <span class="text-success small" style="font-size: 0.75rem;">Mahasiswa siap bimbingan resmi</span>
      </div>
    </div>
  </div>

  <!-- ================================================================= -->
  <!-- TAB 1: KELOLA PUBLIKASI TOPIK FITE -->
  <!-- ================================================================= -->
  <div x-show="activeTab === 'kelola-topik'" class="d-flex flex-column gap-3">
    <div class="card shadow-sm border-light-subtle">
      <div class="card-header bg-white py-3">
        <h2 class="h6 fw-bold text-dark mb-0">Daftar Seluruh Topik Tugas Akhir Pengajar FITE</h2>
        <p class="text-secondary small mb-0">Atur publikasi blind untuk mahasiswa dan kelola penutupan periode proposal Tahap 1.</p>
      </div>

      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0" style="font-size: 0.8125rem;">
          <thead class="table-light">
            <tr>
              <th class="py-2.5 px-3">Topik & Bidang</th>
              <th class="py-2.5 px-3">Dosen Pemilik Topik</th>
              <th class="py-2.5 px-3">Prodi</th>
              <th class="py-2.5 px-3">Pelamar / Lolos</th>
              <th class="py-2.5 px-3">Status Publikasi</th>
              <th class="py-2.5 px-3">Status Tahap 1</th>
              <th class="py-2.5 px-3 text-end">Aksi Koordinator</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($allTopik as $topik)
              @php $badge = $topik->status_badge; @endphp
              <tr>
                <td class="px-3 py-3">
                  <p class="fw-bold text-dark mb-1">{{ $topik->judul_topik }}</p>
                  @if ($topik->bidang)
                    <span class="badge bg-light text-secondary border">{{ $topik->bidang }}</span>
                  @endif
                  @if ($topik->deskripsi)
                    <p class="text-secondary small mt-1 mb-0 text-truncate" style="max-width: 260px;">
                      {{ $topik->deskripsi }}
                    </p>
                  @endif
                </td>
                <td class="px-3 py-3 text-nowrap">
                  <span class="fw-semibold text-dark d-block">{{ $topik->dosen->user->name ?? '-' }}</span>
                  <span class="text-secondary small">{{ $topik->dosen->kode_dosen ?? '-' }} ({{ $topik->dosen->prodi ?? '-' }})</span>
                </td>
                <td class="px-3 py-3 text-nowrap">
                  <span class="badge bg-secondary-subtle text-secondary">{{ $topik->prodi }}</span>
                </td>
                <td class="px-3 py-3 text-nowrap">
                  <span class="fw-bold text-dark">{{ $topik->proposals_count }}</span> Masuk
                  <span class="text-success small d-block">({{ $topik->lolos_count }} Lolos / Kuota {{ $topik->kuota }})</span>
                </td>
                <td class="px-3 py-3 text-nowrap">
                  <span class="badge {{ $badge['class'] }} py-1 px-2">
                    {{ $badge['label'] }}
                  </span>
                </td>
                <td class="px-3 py-3 text-nowrap">
                  @if ($topik->tahap1_selesai)
                    <span class="badge bg-info-subtle text-info-emphasis border border-info-subtle py-1 px-2">
                      <i class="bi bi-check-all me-1"></i>Tahap 1 Masuk
                    </span>
                  @else
                    <span class="badge bg-light text-secondary border py-1 px-2">
                      <i class="bi bi-hourglass me-1"></i>Pengumpulan Buka
                    </span>
                  @endif
                </td>
                <td class="px-3 py-3 text-end text-nowrap">
                  <div class="d-inline-flex gap-1">
                    <!-- Toggle Publikasi -->
                    <form action="{{ route('topik.publikasi', $topik->id) }}" method="POST" class="d-inline">
                      @csrf
                      @method('PATCH')
                      @if ($topik->isDipublikasikan())
                        <button type="submit" class="btn btn-outline-secondary btn-sm" title="Kembalikan ke status Draft">
                          Tarik ke Draft
                        </button>
                      @else
                        <button type="submit" class="btn btn-dark btn-sm" title="Publikasikan ke mahasiswa (Blind)">
                          Publikasikan
                        </button>
                      @endif
                    </form>

                    <!-- Toggle Tahap 1 -->
                    <form action="{{ route('topik.tahap1', $topik->id) }}" method="POST" class="d-inline">
                      @csrf
                      @method('PATCH')
                      <button type="submit" 
                              class="btn {{ $topik->tahap1_selesai ? 'btn-outline-info' : 'btn-outline-primary' }} btn-sm" 
                              title="{{ $topik->tahap1_selesai ? 'Buka kembali pengumpulan Tahap 1' : 'Tandai proposal Tahap 1 masuk & buka seleksi dosen' }}">
                        {{ $topik->tahap1_selesai ? 'Buka Tahap 1' : 'Tutup Tahap 1' }}
                      </button>
                    </form>
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="7" class="py-4 text-center text-secondary">Belum ada topik yang diajukan oleh dosen FITE.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      @if ($allTopik->hasPages())
        <div class="card-footer bg-white py-2 px-3">
          {{ $allTopik->links() }}
        </div>
      @endif
    </div>
  </div>

  <!-- ================================================================= -->
  <!-- TAB 2: REKAPITULASI PROPOSAL LOLOS (SELEKSI DOSEN) -->
  <!-- ================================================================= -->
  <div x-show="activeTab === 'rekap-lolos'" class="d-flex flex-column gap-3">
    <div class="card shadow-sm border-light-subtle">
      <div class="card-header bg-white py-3 d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-2">
        <div>
          <h2 class="h6 fw-bold text-dark mb-0">Rekapitulasi Proposal Mahasiswa yang Dinyatakan Lolos Seleksi</h2>
          <p class="text-secondary small mb-0">Daftar resmi mahasiswa, topik, dan dosen pembimbing yang telah ditetapkan dari seleksi proposal.</p>
        </div>
        <a href="{{ route('koordinator.rekap-proposal') }}" target="_blank" class="btn btn-outline-dark btn-sm d-inline-flex align-items-center gap-1.5">
          <i class="bi bi-printer"></i>
          <span>Cetak / Ekspor Rekap</span>
        </a>
      </div>

      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0" style="font-size: 0.8125rem;">
          <thead class="table-light">
            <tr>
              <th class="py-2.5 px-3">No</th>
              <th class="py-2.5 px-3">Mahasiswa (NIM)</th>
              <th class="py-2.5 px-3">Program Studi</th>
              <th class="py-2.5 px-3">Judul Proposal Lolos</th>
              <th class="py-2.5 px-3">Topik Asal</th>
              <th class="py-2.5 px-3">Dosen Pembimbing Resmi</th>
              <th class="py-2.5 px-3">Tgl Keputusan</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($rekapLolos as $idx => $item)
              <tr>
                <td class="px-3 py-3 text-secondary text-center">{{ $idx + 1 }}</td>
                <td class="px-3 py-3">
                  <p class="fw-bold text-dark mb-0">{{ $item->mahasiswa->user->name ?? '-' }}</p>
                  <p class="text-secondary small mb-0 font-monospace">{{ $item->mahasiswa->nim ?? '-' }}</p>
                </td>
                <td class="px-3 py-3 text-nowrap">
                  <span class="badge bg-secondary-subtle text-secondary">{{ $item->mahasiswa->prodi }}</span>
                </td>
                <td class="px-3 py-3">
                  <strong class="text-dark d-block">{{ $item->judul_proposal }}</strong>
                  @if ($item->file_proposal)
                    <a href="{{ asset('storage/' . $item->file_proposal) }}" target="_blank" class="small text-primary text-decoration-none">
                      <i class="bi bi-file-earmark-arrow-down me-0.5"></i>Berkas Proposal
                    </a>
                  @endif
                </td>
                <td class="px-3 py-3">
                  <span class="text-secondary small">{{ $item->topik->judul_topik ?? '-' }}</span>
                </td>
                <td class="px-3 py-3 text-nowrap">
                  <span class="fw-semibold text-dark d-block">{{ $item->topik->dosen->user->name ?? '-' }}</span>
                  <span class="text-secondary small">{{ $item->topik->dosen->kode_dosen ?? '-' }}</span>
                </td>
                <td class="px-3 py-3 text-nowrap text-secondary">
                  {{ $item->tanggal_keputusan ? $item->tanggal_keputusan->format('d M Y') : '-' }}
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="7" class="py-4 text-center text-secondary">
                  Belum ada proposal yang dinyatakan lolos seleksi oleh dosen pembimbing.
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- ================================================================= -->
  <!-- MODAL: TAMBAH TOPIK ATAS NAMA DOSEN (KOORDINATOR TA) -->
  <!-- ================================================================= -->
  <div x-show="createTopikModal" x-cloak class="modal-backdrop-custom">
    <div class="modal-dialog modal-dialog-centered w-100 mx-3" style="max-width: 540px;" @click.away="createTopikModal = false">
      <div class="modal-content bg-white shadow-lg border-0 rounded-3">
        
        <div class="modal-header border-bottom py-3 px-4">
          <div>
            <h5 class="modal-title fs-6 fw-bold text-dark mb-0">Tambah Topik Tugas Akhir Pengajar</h5>
            <p class="text-secondary small mb-0">Koordinator TA mendaftarkan usulan topik dari dosen program studi.</p>
          </div>
          <button type="button" class="btn-close" @click="createTopikModal = false"></button>
        </div>

        <form action="{{ route('topik.store') }}" method="POST">
          @csrf

          <div class="modal-body p-4 d-flex flex-column gap-3" style="font-size: 0.8125rem;">
            <div>
              <label class="form-label fw-semibold text-dark small mb-1">Dosen Pemilik Topik <span class="text-danger">*</span></label>
              <select name="dosen_id" required class="form-select form-select-sm">
                <option value="" disabled selected>-- Pilih Dosen Pengusul --</option>
                @foreach ($dosenList as $dosen)
                  <option value="{{ $dosen->id }}">{{ $dosen->user->name }} ({{ $dosen->kode_dosen ?? '-' }}) — {{ $dosen->prodi }}</option>
                @endforeach
              </select>
            </div>

            <div>
              <label class="form-label fw-semibold text-dark small mb-1">Judul Topik Penelitian <span class="text-danger">*</span></label>
              <input type="text" name="judul_topik" required placeholder="Judul topik penelitian resmi..." class="form-control form-control-sm">
            </div>

            <div class="row g-2">
              <div class="col-6">
                <label class="form-label fw-semibold text-dark small mb-1">Program Studi Dituju <span class="text-danger">*</span></label>
                <select name="prodi" required class="form-select form-select-sm">
                  <option value="S1 Sistem Informasi">S1 Sistem Informasi</option>
                  <option value="S1 Informatika">S1 Informatika</option>
                  <option value="S1 Teknik Elektro">S1 Teknik Elektro</option>
                  <option value="Semua Prodi">Semua Prodi FITE</option>
                </select>
              </div>

              <div class="col-6">
                <label class="form-label fw-semibold text-dark small mb-1">Bidang / Peminatan</label>
                <input type="text" name="bidang" placeholder="Misal: Sistem Informasi, Rekayasa Data" class="form-control form-control-sm">
              </div>
            </div>

            <div>
              <label class="form-label fw-semibold text-dark small mb-1">Deskripsi Ringkas & Ruang Lingkup</label>
              <textarea name="deskripsi" rows="3" placeholder="Uraian gambaran topik penelitian..." class="form-control form-control-sm"></textarea>
            </div>

            <div>
              <label class="form-label fw-semibold text-dark small mb-1">Kapasitas Kuota Mahasiswa <span class="text-danger">*</span></label>
              <input type="number" name="kuota" value="1" min="1" max="5" required class="form-control form-control-sm" style="max-width: 140px;">
            </div>
          </div>

          <div class="modal-footer border-top py-2 px-4 d-flex justify-content-end gap-2">
            <button type="button" @click="createTopikModal = false" class="btn btn-outline-secondary btn-sm">Batal</button>
            <button type="submit" class="btn btn-dark btn-sm">Terbitkan Topik</button>
          </div>
        </form>

      </div>
    </div>
  </div>

</div>
@endsection

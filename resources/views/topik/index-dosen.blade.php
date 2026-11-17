@extends('layouts.notion')

@section('title', 'Manajemen Topik & Seleksi Proposal Dosen')
@section('breadcrumb', 'Topik & Proposal Dosen')

@section('content')
<div class="d-flex flex-column gap-4" x-data="{ 
    activeTab: 'proposal-masuk',
    createTopikModal: false,
    evalModalOpen: false,
    selectedProposal: null,
    editTopikModal: false,
    selectedTopik: null,
    openEval(proposal) {
        this.selectedProposal = proposal;
        this.evalModalOpen = true;
    },
    openEdit(topik) {
        this.selectedTopik = topik;
        this.editTopikModal = true;
    }
}">

  <!-- Header Halaman & Tombol Aksi -->
  <div class="d-flex flex-column flex-md-row md:align-items-center justify-content-between gap-3 pb-3 border-bottom">
    <div>
      <h1 class="h4 fw-bold text-dark mb-1">Manajemen Topik & Seleksi Proposal Mahasiswa</h1>
      <p class="text-secondary small mb-0">Ajukan topik tugas akhir yang Anda tawarkan dan seleksi proposal mahasiswa bimbingan Anda.</p>
    </div>

    <div class="d-flex flex-wrap align-items-center gap-2">
      <!-- Tab Selector -->
      <div class="d-flex align-items-center gap-1 bg-white p-1 rounded border shadow-xs">
        <button type="button" 
                @click="activeTab = 'proposal-masuk'" 
                :class="activeTab === 'proposal-masuk' ? 'btn-dark' : 'btn-light text-secondary border-0'"
                class="btn btn-sm d-inline-flex align-items-center gap-1.5 py-1 px-3">
          <i class="bi bi-inbox"></i>
          <span>Proposal Masuk</span>
          @if ($incomingProposals->isNotEmpty())
            <span class="badge bg-danger rounded-pill ms-1">{{ $incomingProposals->where('status', 'diajukan')->count() }}</span>
          @endif
        </button>
        <button type="button" 
                @click="activeTab = 'topik-saya'" 
                :class="activeTab === 'topik-saya' ? 'btn-dark' : 'btn-light text-secondary border-0'"
                class="btn btn-sm d-inline-flex align-items-center gap-1.5 py-1 px-3">
          <i class="bi bi-card-list"></i>
          <span>Topik Saya</span>
          <span class="badge bg-secondary-subtle text-secondary rounded-pill ms-1">{{ $myTopik->count() }}</span>
        </button>
        <button type="button" 
                @click="activeTab = 'katalog-fite'" 
                :class="activeTab === 'katalog-fite' ? 'btn-dark' : 'btn-light text-secondary border-0'"
                class="btn btn-sm d-inline-flex align-items-center gap-1.5 py-1 px-3">
          <i class="bi bi-collection"></i>
          <span>Katalog FITE</span>
        </button>
      </div>

      <!-- Tombol Buat Topik Baru -->
      <button type="button" @click="createTopikModal = true" class="btn btn-primary btn-sm d-inline-flex align-items-center gap-1.5 shadow-xs">
        <i class="bi bi-plus-lg"></i>
        <span>Ajukan Topik Baru</span>
      </button>
    </div>
  </div>

  <!-- Ringkasan Statistik Dosen -->
  <div class="row g-3">
    <div class="col-6 col-md-3">
      <div class="card shadow-sm border-light-subtle h-100 p-3 bg-white">
        <span class="text-secondary small fw-semibold text-uppercase d-block mb-1" style="font-size: 0.7rem;">Topik Anda</span>
        <p class="h4 fw-bold text-dark mb-0">{{ $myTopik->count() }}</p>
        <span class="text-secondary small" style="font-size: 0.75rem;">{{ $myTopik->where('status', 'dipublikasikan')->count() }} Terpublikasi</span>
      </div>
    </div>
    <div class="col-6 col-md-3">
      <div class="card shadow-sm border-light-subtle h-100 p-3 bg-white">
        <span class="text-secondary small fw-semibold text-uppercase d-block mb-1" style="font-size: 0.7rem;">Total Proposal Masuk</span>
        <p class="h4 fw-bold text-dark mb-0">{{ $incomingProposals->count() }}</p>
        <span class="text-secondary small" style="font-size: 0.75rem;">Dari Mahasiswa TA</span>
      </div>
    </div>
    <div class="col-6 col-md-3">
      <div class="card shadow-sm border-warning-subtle h-100 p-3 bg-warning-subtle/30">
        <span class="text-warning-emphasis small fw-semibold text-uppercase d-block mb-1" style="font-size: 0.7rem;">Menunggu Keputusan</span>
        <p class="h4 fw-bold text-warning-emphasis mb-0">{{ $incomingProposals->whereIn('status', ['diajukan', 'tahap1_direview'])->count() }}</p>
        <span class="text-warning-emphasis small" style="font-size: 0.75rem;">Perlu evaluasi seleksi</span>
      </div>
    </div>
    <div class="col-6 col-md-3">
      <div class="card shadow-sm border-success-subtle h-100 p-3 bg-success-subtle/30">
        <span class="text-success-emphasis small fw-semibold text-uppercase d-block mb-1" style="font-size: 0.7rem;">Proposal Lolos</span>
        <p class="h4 fw-bold text-success mb-0">{{ $incomingProposals->where('status', 'lolos')->count() }}</p>
        <span class="text-success small" style="font-size: 0.75rem;">Mahasiswa Bimbingan Baru</span>
      </div>
    </div>
  </div>

  <!-- ================================================================= -->
  <!-- TAB 1: PROPOSAL MASUK (SELEKSI & KEPUTUSAN DOSEN) -->
  <!-- ================================================================= -->
  <div x-show="activeTab === 'proposal-masuk'" class="d-flex flex-column gap-3">
    <div class="card shadow-sm border-light-subtle">
      <div class="card-header bg-white py-3">
        <h2 class="h6 fw-bold text-dark mb-0">Daftar Proposal Masuk untuk Topik Anda</h2>
        <p class="text-secondary small mb-0">Periksa substansi proposal mahasiswa dan tentukan proposal yang lolos untuk dibimbing.</p>
      </div>

      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0" style="font-size: 0.8125rem;">
          <thead class="table-light">
            <tr>
              <th class="py-2.5 px-3">Mahasiswa Pengusul</th>
              <th class="py-2.5 px-3">Topik Terkait</th>
              <th class="py-2.5 px-3">Judul & Berkas Proposal</th>
              <th class="py-2.5 px-3">Status Seleksi</th>
              <th class="py-2.5 px-3 text-end">Aksi Dosen</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($incomingProposals as $proposal)
              @php $badge = $proposal->status_badge; @endphp
              <tr class="{{ $proposal->isLolos() ? 'table-success' : ($proposal->isTidakLolos() ? 'table-danger' : '') }}">
                <td class="px-3 py-3">
                  <p class="fw-bold text-dark mb-0">{{ $proposal->mahasiswa->user->name ?? '-' }}</p>
                  <p class="text-secondary small mb-0">{{ $proposal->mahasiswa->nim ?? '-' }} · {{ $proposal->mahasiswa->prodi }}</p>
                </td>
                <td class="px-3 py-3 text-nowrap">
                  <span class="fw-semibold text-dark d-block">{{ Str::limit($proposal->topik->judul_topik ?? '-', 35) }}</span>
                  <span class="badge bg-secondary-subtle text-secondary" style="font-size: 0.65rem;">{{ $proposal->topik->prodi ?? '-' }}</span>
                </td>
                <td class="px-3 py-3">
                  <p class="fw-bold text-dark mb-1">{{ $proposal->judul_proposal }}</p>
                  @if ($proposal->ringkasan)
                    <p class="text-secondary small mb-1 fst-italic text-truncate" style="max-width: 280px;">"{{ $proposal->ringkasan }}"</p>
                  @endif
                  <div class="d-flex align-items-center gap-2">
                    @if ($proposal->file_proposal)
                      <a href="{{ asset('storage/' . $proposal->file_proposal) }}" target="_blank" class="small text-primary text-decoration-none fw-semibold">
                        <i class="bi bi-file-earmark-arrow-down me-1"></i>Unduh Berkas
                      </a>
                    @endif
                    @if ($proposal->link_ecourse)
                      <a href="{{ $proposal->link_ecourse }}" target="_blank" class="small text-secondary text-decoration-none">
                        <i class="bi bi-box-arrow-up-right me-1"></i>Tautan eCourse
                      </a>
                    @endif
                  </div>
                </td>
                <td class="px-3 py-3 text-nowrap">
                  <span class="badge {{ $badge['class'] }} d-inline-flex align-items-center gap-1 py-1 px-2">
                    <i class="bi {{ $badge['icon'] }}"></i>
                    <span>{{ $badge['label'] }}</span>
                  </span>
                  @if ($proposal->catatan_dosen)
                    <div class="text-secondary small mt-1 text-truncate" style="max-width: 180px;" title="{{ $proposal->catatan_dosen }}">
                      Catatan: {{ $proposal->catatan_dosen }}
                    </div>
                  @endif
                </td>
                <td class="px-3 py-3 text-end text-nowrap">
                  <button type="button" 
                          @click="openEval({{ json_encode($proposal) }})" 
                          class="btn btn-dark btn-sm d-inline-flex align-items-center gap-1">
                    <i class="bi bi-clipboard-check"></i>
                    <span>Evaluasi Proposal</span>
                  </button>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="5" class="py-4 text-center text-secondary">
                  Belum ada proposal yang masuk untuk topik Anda.
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- ================================================================= -->
  <!-- TAB 2: DAFTAR TOPIK SAYA -->
  <!-- ================================================================= -->
  <div x-show="activeTab === 'topik-saya'" class="d-flex flex-column gap-3">
    <div class="card shadow-sm border-light-subtle">
      <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between">
        <div>
          <h2 class="h6 fw-bold text-dark mb-0">Daftar Topik Tugas Akhir yang Anda Tawarkan</h2>
          <p class="text-secondary small mb-0">Kelola draft dan publikasi topik untuk mahasiswa program studi FITE.</p>
        </div>
      </div>

      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0" style="font-size: 0.8125rem;">
          <thead class="table-light">
            <tr>
              <th class="py-2.5 px-3">Judul Topik & Bidang</th>
              <th class="py-2.5 px-3">Program Studi</th>
              <th class="py-2.5 px-3">Kuota & Pelamar</th>
              <th class="py-2.5 px-3">Status Topik</th>
              <th class="py-2.5 px-3 text-end">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($myTopik as $topik)
              @php $badge = $topik->status_badge; @endphp
              <tr>
                <td class="px-3 py-3">
                  <p class="fw-bold text-dark mb-1">{{ $topik->judul_topik }}</p>
                  @if ($topik->bidang)
                    <span class="badge bg-light text-secondary border">{{ $topik->bidang }}</span>
                  @endif
                  @if ($topik->deskripsi)
                    <p class="text-secondary small mt-1 mb-0 text-truncate" style="max-width: 320px;">
                      {{ $topik->deskripsi }}
                    </p>
                  @endif
                </td>
                <td class="px-3 py-3 text-nowrap">
                  <span class="badge bg-secondary-subtle text-secondary">{{ $topik->prodi }}</span>
                </td>
                <td class="px-3 py-3 text-nowrap">
                  <span class="fw-bold text-dark">{{ $topik->proposals_count }}</span> Pelamar / Kuota {{ $topik->kuota }} Mhs
                </td>
                <td class="px-3 py-3 text-nowrap">
                  <span class="badge {{ $badge['class'] }} py-1 px-2">
                    {{ $badge['label'] }}
                  </span>
                  @if ($topik->tahap1_selesai)
                    <span class="badge bg-info-subtle text-info-emphasis border border-info-subtle d-block mt-1">Tahap 1 Masuk</span>
                  @endif
                </td>
                <td class="px-3 py-3 text-end text-nowrap">
                  <button type="button" @click="openEdit({{ json_encode($topik) }})" class="btn btn-outline-dark btn-sm me-1">
                    <i class="bi bi-pencil me-0.5"></i>Edit
                  </button>
                  <form action="{{ route('topik.destroy', $topik->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus topik ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger btn-sm">
                      <i class="bi bi-trash"></i>
                    </button>
                  </form>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="5" class="py-4 text-center text-secondary">
                  Anda belum mengajukan topik tugas akhir. Klik tombol "Ajukan Topik Baru" di kanan atas.
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- ================================================================= -->
  <!-- TAB 3: KATALOG SEMUA TOPIK FITE (REFERENSI) -->
  <!-- ================================================================= -->
  <div x-show="activeTab === 'katalog-fite'" class="d-flex flex-column gap-3">
    <div class="card shadow-sm border-light-subtle">
      <div class="card-header bg-white py-3">
        <h2 class="h6 fw-bold text-dark mb-0">Katalog Seluruh Topik Terpublikasi FITE IT Del</h2>
        <p class="text-secondary small mb-0">Daftar topik dari seluruh dosen untuk koordinasi dan transparansi akademik.</p>
      </div>

      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0" style="font-size: 0.8125rem;">
          <thead class="table-light">
            <tr>
              <th class="py-2.5 px-3">Judul Topik</th>
              <th class="py-2.5 px-3">Dosen Pengusul</th>
              <th class="py-2.5 px-3">Prodi & Bidang</th>
              <th class="py-2.5 px-3">Kuota</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($allPublishedTopik as $item)
              <tr>
                <td class="px-3 py-3">
                  <p class="fw-bold text-dark mb-0">{{ $item->judul_topik }}</p>
                  <p class="text-secondary small mb-0 text-truncate" style="max-width: 320px;">{{ $item->deskripsi }}</p>
                </td>
                <td class="px-3 py-3 text-nowrap">
                  <span class="fw-semibold text-dark">{{ $item->dosen->user->name ?? '-' }}</span>
                  <span class="text-secondary small d-block">{{ $item->dosen->kode_dosen ?? '-' }}</span>
                </td>
                <td class="px-3 py-3 text-nowrap">
                  <span class="badge bg-secondary-subtle text-secondary">{{ $item->prodi }}</span>
                  @if ($item->bidang)
                    <span class="badge bg-light text-dark border ms-1">{{ $item->bidang }}</span>
                  @endif
                </td>
                <td class="px-3 py-3 text-nowrap text-dark">
                  {{ $item->kuota }} Mahasiswa
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="4" class="py-4 text-center text-secondary">Belum ada topik yang dipublikasikan.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- ================================================================= -->
  <!-- MODAL: FORMULIR AJUKAN TOPIK BARU (DOSEN) -->
  <!-- ================================================================= -->
  <div x-show="createTopikModal" x-cloak class="modal-backdrop-custom">
    <div class="modal-dialog modal-dialog-centered w-100 mx-3" style="max-width: 540px;" @click.away="createTopikModal = false">
      <div class="modal-content bg-white shadow-lg border-0 rounded-3">
        
        <div class="modal-header border-bottom py-3 px-4">
          <div>
            <h5 class="modal-title fs-6 fw-bold text-dark mb-0">Ajukan Topik Tugas Akhir Baru</h5>
            <p class="text-secondary small mb-0">Tawarkan topik penelitian untuk mahasiswa program studi Anda.</p>
          </div>
          <button type="button" class="btn-close" @click="createTopikModal = false"></button>
        </div>

        <form action="{{ route('topik.store') }}" method="POST">
          @csrf

          <div class="modal-body p-4 d-flex flex-column gap-3" style="font-size: 0.8125rem;">
            <div>
              <label class="form-label fw-semibold text-dark small mb-1">Judul Topik Penelitian <span class="text-danger">*</span></label>
              <input type="text" name="judul_topik" required placeholder="Contoh: Otomasi Pengujian Kerentanan Aplikasi Web..." class="form-control form-control-sm">
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
                <input type="text" name="bidang" placeholder="Misal: Sistem Informasi, AI, IoT" class="form-control form-control-sm">
              </div>
            </div>

            <div>
              <label class="form-label fw-semibold text-dark small mb-1">Deskripsi Ringkas & Ruang Lingkup Topik</label>
              <textarea name="deskripsi" rows="3" placeholder="Jelaskan gambaran umum penelitian, teknologi yang digunakan, serta kualifikasi mahasiswa yang diharapkan..." class="form-control form-control-sm"></textarea>
            </div>

            <div>
              <label class="form-label fw-semibold text-dark small mb-1">Kapasitas Kuota Mahasiswa (Kelompok) <span class="text-danger">*</span></label>
              <input type="number" name="kuota" value="1" min="1" max="5" required class="form-control form-control-sm" style="max-width: 140px;">
              <span class="text-secondary small d-block mt-0.5" style="font-size: 0.72rem;">Jumlah maksimum mahasiswa/kelompok yang akan diterima pada topik ini.</span>
            </div>
          </div>

          <div class="modal-footer border-top py-2 px-4 d-flex justify-content-end gap-2">
            <button type="button" @click="createTopikModal = false" class="btn btn-outline-secondary btn-sm">Batal</button>
            <button type="submit" class="btn btn-dark btn-sm">Simpan Topik</button>
          </div>
        </form>

      </div>
    </div>
  </div>

  <!-- ================================================================= -->
  <!-- MODAL: EVALUASI / KEPUTUSAN PROPOSAL (DOSEN) -->
  <!-- ================================================================= -->
  <div x-show="evalModalOpen" x-cloak class="modal-backdrop-custom">
    <div class="modal-dialog modal-dialog-centered w-100 mx-3" style="max-width: 520px;" @click.away="evalModalOpen = false">
      <div class="modal-content bg-white shadow-lg border-0 rounded-3">
        
        <div class="modal-header border-bottom py-3 px-4">
          <div>
            <h5 class="modal-title fs-6 fw-bold text-dark mb-0">Evaluasi Keputusan Proposal Mahasiswa</h5>
            <p class="text-secondary small mb-0">Tentukan apakah mahasiswa ini lolos untuk Anda bimbing.</p>
          </div>
          <button type="button" class="btn-close" @click="evalModalOpen = false"></button>
        </div>

        <template x-if="selectedProposal">
          <form :action="'/proposal/' + selectedProposal.id + '/keputusan'" method="POST">
            @csrf
            @method('PATCH')

            <div class="modal-body p-4 d-flex flex-column gap-3" style="font-size: 0.8125rem;">
              
              <!-- Profil Mahasiswa & Proposal -->
              <div class="p-3 rounded bg-light border border-light-subtle">
                <span class="text-secondary small fw-bold text-uppercase d-block mb-1" style="font-size: 0.65rem;">Mahasiswa Pengusul:</span>
                <p class="fw-bold text-dark mb-0" x-text="selectedProposal.mahasiswa?.user?.name ?? 'Mahasiswa'"></p>
                <p class="text-secondary small mb-2" x-text="(selectedProposal.mahasiswa?.nim ?? '-') + ' · ' + (selectedProposal.mahasiswa?.prodi ?? '-')"></p>

                <span class="text-secondary small fw-bold text-uppercase d-block mb-1" style="font-size: 0.65rem;">Judul Proposal:</span>
                <p class="text-dark small fw-medium mb-0" x-text="selectedProposal.judul_proposal"></p>
              </div>

              <!-- Pilihan Keputusan -->
              <div>
                <label class="form-label fw-semibold text-dark small mb-2">Keputusan Seleksi <span class="text-danger">*</span></label>
                <div class="d-flex gap-2">
                  <label class="btn btn-outline-success btn-sm flex-fill d-flex align-items-center justify-content-center gap-2 py-2 cursor-pointer">
                    <input type="radio" name="keputusan" value="lolos" checked class="form-check-input mt-0">
                    <span class="fw-semibold">Loloskan Proposal</span>
                  </label>
                  <label class="btn btn-outline-danger btn-sm flex-fill d-flex align-items-center justify-content-center gap-2 py-2 cursor-pointer">
                    <input type="radio" name="keputusan" value="tidak_lolos" class="form-check-input mt-0">
                    <span class="fw-semibold">Tolak / Tidak Lolos</span>
                  </label>
                </div>
                <div class="alert alert-light border mt-2 mb-0 py-2 px-3 small text-secondary" style="font-size: 0.75rem;">
                  <i class="bi bi-info-circle me-1 text-primary"></i>
                  Jika <strong>Lolos</strong>, mahasiswa ini otomatis ditetapkan resmi dalam pengerjaan Tugas Akhir dengan Anda sebagai Pembimbing 1.
                </div>
              </div>

              <!-- Catatan Evaluasi Dosen -->
              <div>
                <label class="form-label fw-semibold text-dark small mb-1">Catatan Evaluasi / Masukan Dosen (Opsional)</label>
                <textarea name="catatan_dosen" rows="3" placeholder="Tuliskan catatan perbaikan rumusan masalah, metodologi, atau alasan penolakan..." class="form-control form-control-sm"></textarea>
              </div>

            </div>

            <div class="modal-footer border-top py-2 px-4 d-flex justify-content-end gap-2">
              <button type="button" @click="evalModalOpen = false" class="btn btn-outline-secondary btn-sm">Batal</button>
              <button type="submit" class="btn btn-dark btn-sm">Simpan Keputusan</button>
            </div>
          </form>
        </template>

      </div>
    </div>
  </div>

  <!-- ================================================================= -->
  <!-- MODAL: EDIT TOPIK (DOSEN) -->
  <!-- ================================================================= -->
  <div x-show="editTopikModal" x-cloak class="modal-backdrop-custom">
    <div class="modal-dialog modal-dialog-centered w-100 mx-3" style="max-width: 540px;" @click.away="editTopikModal = false">
      <div class="modal-content bg-white shadow-lg border-0 rounded-3">
        
        <div class="modal-header border-bottom py-3 px-4">
          <div>
            <h5 class="modal-title fs-6 fw-bold text-dark mb-0">Edit Rincian Topik Tugas Akhir</h5>
            <p class="text-secondary small mb-0">Perbarui judul, bidang, kuota, atau deskripsi topik.</p>
          </div>
          <button type="button" class="btn-close" @click="editTopikModal = false"></button>
        </div>

        <template x-if="selectedTopik">
          <form :action="'/topik/' + selectedTopik.id" method="POST">
            @csrf
            @method('PUT')

            <div class="modal-body p-4 d-flex flex-column gap-3" style="font-size: 0.8125rem;">
              <div>
                <label class="form-label fw-semibold text-dark small mb-1">Judul Topik Penelitian <span class="text-danger">*</span></label>
                <input type="text" name="judul_topik" :value="selectedTopik.judul_topik" required class="form-control form-control-sm">
              </div>

              <div class="row g-2">
                <div class="col-6">
                  <label class="form-label fw-semibold text-dark small mb-1">Program Studi Dituju</label>
                  <select name="prodi" required class="form-select form-select-sm">
                    <option value="S1 Sistem Informasi" :selected="selectedTopik.prodi === 'S1 Sistem Informasi'">S1 Sistem Informasi</option>
                    <option value="S1 Informatika" :selected="selectedTopik.prodi === 'S1 Informatika'">S1 Informatika</option>
                    <option value="S1 Teknik Elektro" :selected="selectedTopik.prodi === 'S1 Teknik Elektro'">S1 Teknik Elektro</option>
                    <option value="Semua Prodi" :selected="selectedTopik.prodi === 'Semua Prodi'">Semua Prodi FITE</option>
                  </select>
                </div>

                <div class="col-6">
                  <label class="form-label fw-semibold text-dark small mb-1">Bidang / Peminatan</label>
                  <input type="text" name="bidang" :value="selectedTopik.bidang ?? ''" class="form-control form-control-sm">
                </div>
              </div>

              <div>
                <label class="form-label fw-semibold text-dark small mb-1">Deskripsi Ringkas</label>
                <textarea name="deskripsi" rows="3" :value="selectedTopik.deskripsi ?? ''" class="form-control form-control-sm"></textarea>
              </div>

              <div class="row g-2">
                <div class="col-6">
                  <label class="form-label fw-semibold text-dark small mb-1">Kapasitas Kuota Mahasiswa</label>
                  <input type="number" name="kuota" :value="selectedTopik.kuota" min="1" max="10" required class="form-control form-control-sm">
                </div>
                <div class="col-6">
                  <label class="form-label fw-semibold text-dark small mb-1">Status Publikasi</label>
                  <select name="status" class="form-select form-select-sm">
                    <option value="draft" :selected="selectedTopik.status === 'draft'">Draft</option>
                    <option value="dipublikasikan" :selected="selectedTopik.status === 'dipublikasikan'">Dipublikasikan</option>
                    <option value="ditutup" :selected="selectedTopik.status === 'ditutup'">Ditutup</option>
                  </select>
                </div>
              </div>
            </div>

            <div class="modal-footer border-top py-2 px-4 d-flex justify-content-end gap-2">
              <button type="button" @click="editTopikModal = false" class="btn btn-outline-secondary btn-sm">Batal</button>
              <button type="submit" class="btn btn-dark btn-sm">Simpan Perubahan</button>
            </div>
          </form>
        </template>

      </div>
    </div>
  </div>

</div>
@endsection

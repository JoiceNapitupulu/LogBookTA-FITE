@extends('layouts.notion')

@section('title', ($hasTugasAkhir ?? false) ? 'Informasi Topik TA' : 'Katalog Topik & Proposal TA')
@section('breadcrumb', 'Topik & Proposal')

@section('content')
<div class="d-flex flex-column gap-4" x-data="{ 
    activeTab: '{{ ($hasTugasAkhir ?? false) ? 'topik_saya' : 'topik' }}',
    selectedTopik: null,
    submitModalOpen: false,
    openSubmit(topik) {
        this.selectedTopik = topik;
        this.submitModalOpen = true;
    }
}">

  <!-- Header Halaman -->
  <div class="d-flex flex-column flex-md-row md:align-items-center justify-content-between gap-3 pb-3 border-bottom">
    <div>
      @if ($hasTugasAkhir ?? false)
        <h1 class="h4 fw-bold text-dark mb-1">Informasi Topik Tugas Akhir</h1>
        <p class="text-secondary small mb-0">Detail topik dan dosen pembimbing resmi Tugas Akhir yang sedang Anda kerjakan.</p>
      @else
        <h1 class="h4 fw-bold text-dark mb-1">Katalog Topik & Pengajuan Proposal TA</h1>
        <p class="text-secondary small mb-0">Eksplorasi topik tugas akhir yang ditawarkan dan ajukan proposal penelitian Anda.</p>
      @endif
    </div>

    <!-- Tab Navigasi -->
    <div class="d-flex align-items-center gap-1 bg-white p-1 rounded border shadow-xs">
      @if ($hasTugasAkhir ?? false)
        <button type="button" 
                @click="activeTab = 'topik_saya'" 
                :class="activeTab === 'topik_saya' ? 'btn-dark' : 'btn-light text-secondary border-0'"
                class="btn btn-sm d-inline-flex align-items-center gap-1.5 py-1 px-3">
          <i class="bi bi-bookmark-check"></i>
          <span>Topik TA Saya</span>
        </button>
        <button type="button" 
                @click="activeTab = 'topik'" 
                :class="activeTab === 'topik' ? 'btn-dark' : 'btn-light text-secondary border-0'"
                class="btn btn-sm d-inline-flex align-items-center gap-1.5 py-1 px-3">
          <i class="bi bi-grid"></i>
          <span>Katalog Referensi</span>
        </button>
        @if ($myProposals->isNotEmpty())
          <button type="button" 
                  @click="activeTab = 'proposalku'" 
                  :class="activeTab === 'proposalku' ? 'btn-dark' : 'btn-light text-secondary border-0'"
                  class="btn btn-sm d-inline-flex align-items-center gap-1.5 py-1 px-3 position-relative">
            <i class="bi bi-file-earmark-text"></i>
            <span>Riwayat Proposal</span>
            <span class="badge bg-secondary-subtle text-secondary rounded-pill ms-1">{{ $myProposals->count() }}</span>
          </button>
        @endif
      @else
        <button type="button" 
                @click="activeTab = 'topik'" 
                :class="activeTab === 'topik' ? 'btn-dark' : 'btn-light text-secondary border-0'"
                class="btn btn-sm d-inline-flex align-items-center gap-1.5 py-1 px-3">
          <i class="bi bi-grid"></i>
          <span>Katalog Topik</span>
        </button>
        <button type="button" 
                @click="activeTab = 'proposalku'" 
                :class="activeTab === 'proposalku' ? 'btn-dark' : 'btn-light text-secondary border-0'"
                class="btn btn-sm d-inline-flex align-items-center gap-1.5 py-1 px-3 position-relative">
          <i class="bi bi-file-earmark-text"></i>
          <span>Proposal Saya</span>
          @if ($myProposals->isNotEmpty())
            <span class="badge bg-secondary-subtle text-secondary rounded-pill ms-1">{{ $myProposals->count() }}</span>
          @endif
        </button>
      @endif
    </div>
  </div>

  @if ($hasTugasAkhir && $tugasAkhir)
    <!-- Banner Informasi Topik TA Resmi yang Sudah Dimiliki -->
    <div class="alert alert-success d-flex align-items-start gap-2.5 mb-0 shadow-xs border-success-subtle" role="alert">
      <i class="bi bi-check-circle-fill fs-5 text-success shrink-0 mt-0.5"></i>
      <div class="w-100" style="font-size: 0.8125rem;">
        <strong class="text-success-emphasis d-block mb-0.5">Anda Sudah Memiliki Topik Tugas Akhir Resmi</strong>
        <p class="text-secondary mb-0">
          Akun Anda telah terdaftar memiliki topik Tugas Akhir resmi dan sedang menjalani proses bimbingan/seminar. <strong>Bagian pengajuan proposal dinonaktifkan untuk akun Anda.</strong> Silakan fokus pada pencatatan logbook dan penjadwalan janji bimbingan.
        </p>
      </div>
    </div>
  @else
    <!-- Alert Informasi Kebijakan Blind Submission (Hanya untuk mahasiswa yang belum punya topik) -->
    <div class="alert alert-info d-flex align-items-start gap-2.5 mb-0 shadow-xs border-info-subtle" role="alert">
      <i class="bi bi-shield-lock-fill fs-5 text-primary shrink-0 mt-0.5"></i>
      <div class="w-100" style="font-size: 0.8125rem;">
        <strong class="text-dark d-block mb-0.5">Regulasi Seleksi Proposal (Blind Submission FITE IT Del)</strong>
        <p class="text-secondary mb-0">
          Untuk menjamin objektivitas akademik, identitas dosen pembimbing / pengusul topik <strong class="text-dark">dirahasiakan</strong>. Mahasiswa memilih topik murni berdasarkan kesesuaian minat keilmuan dan menyusun naskah proposal untuk diseleksi oleh dosen pembimbing terkait.
        </p>
      </div>
    </div>
  @endif

  @if ($hasAcceptedProposal && !$hasTugasAkhir)
    <!-- Banner Notifikasi: Sudah Memiliki Proposal yang Lolos -->
    <div class="alert alert-success d-flex align-items-center gap-2.5 mb-0 shadow-xs border-success-subtle" role="alert">
      <i class="bi bi-check-circle-fill fs-5 text-success shrink-0"></i>
      <div style="font-size: 0.8125rem;">
        <strong class="text-success-emphasis">Selamat! Anda telah memiliki proposal yang dinyatakan LOLOS seleksi.</strong>
        <span class="text-secondary d-block mt-0.5">Dosen pembimbing resmi telah ditetapkan pada sistem. Anda dapat memantau riwayat pada tab "Proposal Saya".</span>
      </div>
    </div>
  @endif

  @if ($hasTugasAkhir && $tugasAkhir)
    <!-- ================================================================= -->
    <!-- TAB 0: TOPIK TA SAYA (UNTUK MAHASISWA YANG SUDAH PUNYA TOPIK) -->
    <!-- ================================================================= -->
    <div x-show="activeTab === 'topik_saya'" class="d-flex flex-column gap-3">
      <div class="card shadow-sm border-primary-subtle bg-white">
        <div class="card-body p-3.5 p-md-4">
          <div class="d-flex flex-column flex-md-row align-items-md-start justify-content-between gap-3 mb-3">
            <div>
              <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
                <span class="badge bg-primary text-white">Topik TA Aktif</span>
                <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle">{{ $tugasAkhir->prodi }}</span>
                <span class="badge bg-success-subtle text-success border border-success-subtle">TA {{ $tugasAkhir->tahun_ajaran }}</span>
                <span class="badge bg-light text-dark border">Status: {{ strtoupper($tugasAkhir->status) }}</span>
              </div>
              <h2 class="h5 fw-bold text-dark lh-sm mb-2">
                {{ $tugasAkhir->judul }}
              </h2>
              <div class="d-flex flex-wrap align-items-center gap-3 text-secondary small">
                <div>Dosen Pembimbing 1: <strong class="text-dark">{{ $tugasAkhir->pembimbing1?->user?->name ?? $tugasAkhir->dosenPembimbing1?->user?->name ?? '-' }}</strong></div>
                @if ($tugasAkhir->pembimbing2 || $tugasAkhir->dosenPembimbing2)
                  <span>·</span>
                  <div>Dosen Pembimbing 2: <strong class="text-dark">{{ $tugasAkhir->pembimbing2?->user?->name ?? $tugasAkhir->dosenPembimbing2?->user?->name ?? '-' }}</strong></div>
                @endif
              </div>
            </div>

            <div class="d-flex align-items-center gap-2 shrink-0">
              <a href="{{ route('dashboard') }}" class="btn btn-outline-dark btn-sm d-inline-flex align-items-center gap-1.5">
                <i class="bi bi-grid-1x2"></i> Dashboard TA
              </a>
              <a href="{{ route('logbook.index') }}" class="btn btn-dark btn-sm d-inline-flex align-items-center gap-1.5">
                <i class="bi bi-journal-text"></i> Logbook Bimbingan
              </a>
            </div>
          </div>

          @if ($tugasAkhir->abstrak)
            <div class="pt-3 border-top">
              <span class="text-secondary small fw-bold text-uppercase d-block mb-1" style="font-size: 0.7rem;">Abstrak / Deskripsi Topik Penelitian:</span>
              <p class="text-secondary small mb-0 lh-base" style="font-size: 0.8125rem;">
                {{ $tugasAkhir->abstrak }}
              </p>
            </div>
          @endif
        </div>
      </div>
    </div>
  @endif

  <!-- ================================================================= -->
  <!-- TAB 1: KATALOG TOPIK YANG DITAWARKAN (BLIND) -->
  <!-- ================================================================= -->
  <div x-show="activeTab === 'topik'" class="d-flex flex-column gap-3">
    
    <!-- Filter & Pencarian -->
    <div class="card shadow-sm border-light-subtle">
      <div class="card-body p-3">
        <form action="{{ route('topik.index') }}" method="GET" class="row g-2 align-items-center">
          <div class="col-12 col-md-5">
            <div class="input-group input-group-sm">
              <span class="input-group-text bg-white border-end-0 text-secondary"><i class="bi bi-search"></i></span>
              <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari topik, kata kunci, bidang riset..." class="form-control border-start-0">
            </div>
          </div>
          <div class="col-12 col-md-4">
            <select name="prodi" class="form-select form-select-sm" onchange="this.form.submit()">
              <option value="semua" {{ request('prodi') === 'semua' || !request('prodi') ? 'selected' : '' }}>Semua Program Studi</option>
              <option value="S1 Sistem Informasi" {{ request('prodi') === 'S1 Sistem Informasi' ? 'selected' : '' }}>S1 Sistem Informasi</option>
              <option value="S1 Informatika" {{ request('prodi') === 'S1 Informatika' ? 'selected' : '' }}>S1 Informatika</option>
              <option value="S1 Teknik Elektro" {{ request('prodi') === 'S1 Teknik Elektro' ? 'selected' : '' }}>S1 Teknik Elektro</option>
            </select>
          </div>
          <div class="col-12 col-md-3 d-flex gap-2">
            <button type="submit" class="btn btn-dark btn-sm flex-fill">Terapkan Filter</button>
            @if (request()->hasAny(['search', 'prodi']))
              <a href="{{ route('topik.index') }}" class="btn btn-outline-secondary btn-sm" title="Reset Filter"><i class="bi bi-arrow-counterclockwise"></i></a>
            @endif
          </div>
        </form>
      </div>
    </div>

    <!-- Grid Kartu Topik (Blind) -->
    <div class="row g-3">
      @forelse ($topikList as $topik)
        @php
          $alreadyApplied = $myProposals->contains(fn($p) => $p->topik_ta_id === $topik->id);
          $isFull = $topik->sisaKuota() <= 0;
        @endphp
        <div class="col-12 col-md-6 col-lg-4">
          <div class="card shadow-sm border-light-subtle h-100 d-flex flex-column {{ $alreadyApplied ? 'border-primary' : '' }}">
            <div class="card-body p-3.5 d-flex flex-column flex-grow-1">
              
              <!-- Badges Header -->
              <div class="d-flex flex-wrap align-items-center justify-content-between gap-1 mb-2">
                <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle" style="font-size: 0.7rem;">
                  {{ $topik->prodi }}
                </span>
                @if ($topik->bidang)
                  <span class="badge bg-light text-dark border" style="font-size: 0.7rem;">
                    {{ $topik->bidang }}
                  </span>
                @endif
              </div>

              <!-- Judul Topik -->
              <h2 class="h6 fw-bold text-dark mb-2 lh-base" style="font-size: 0.9375rem;">
                {{ $topik->judul_topik }}
              </h2>

              <!-- Deskripsi Topik -->
              <p class="text-secondary small mb-3 flex-grow-1 lh-sm" style="font-size: 0.8125rem;">
                {{ Str::limit($topik->deskripsi ?: 'Tidak ada deskripsi rinci untuk topik ini.', 130) }}
              </p>

              <!-- Meta Info: Kuota & Blind Info -->
              <div class="border-top pt-2.5 mt-auto mb-3" style="font-size: 0.75rem;">
                <div class="d-flex align-items-center justify-content-between text-secondary mb-1">
                  <span>Sisa Kuota Dosen:</span>
                  <strong class="{{ $isFull ? 'text-danger' : 'text-dark' }}">
                    {{ $topik->sisaKuota() }} / {{ $topik->kuota }} Mahasiswa
                  </strong>
                </div>
                <div class="d-flex align-items-center justify-content-between text-secondary">
                  <span>Dosen Pembimbing:</span>
                  <span class="badge bg-light text-secondary border font-monospace" style="font-size: 0.65rem;">
                    <i class="bi bi-lock-fill me-0.5"></i> Dirahasiakan (Blind)
                  </span>
                </div>
              </div>

              <!-- Tombol Aksi -->
              <div class="mt-auto">
                @if ($hasTugasAkhir ?? false)
                  <div class="text-center py-1.5 px-2.5 rounded bg-light border text-secondary font-monospace" style="font-size: 0.75rem;">
                    <i class="bi bi-shield-check text-success me-1"></i> Topik TA Anda Sudah Aktif
                  </div>
                @elseif ($hasAcceptedProposal)
                  <button type="button" disabled class="btn btn-light border text-secondary btn-sm w-100" style="font-size: 0.8125rem;">
                    <i class="bi bi-check2-all me-1"></i> Sudah Diterima di Topik Lain
                  </button>
                @elseif ($alreadyApplied)
                  <button type="button" disabled class="btn btn-primary-subtle text-primary border border-primary-subtle btn-sm w-100" style="font-size: 0.8125rem;">
                    <i class="bi bi-check-circle me-1"></i> Proposal Sudah Diajukan
                  </button>
                @elseif ($isFull)
                  <button type="button" disabled class="btn btn-light border text-secondary btn-sm w-100" style="font-size: 0.8125rem;">
                    <i class="bi bi-dash-circle me-1"></i> Kuota Topik Penuh
                  </button>
                @else
                  <button type="button" 
                          @click="openSubmit({{ json_encode($topik) }})" 
                          class="btn btn-dark btn-sm w-100 d-inline-flex align-items-center justify-content-center gap-1.5"
                          style="font-size: 0.8125rem;">
                    <i class="bi bi-pencil-square"></i>
                    <span>Ajukan Proposal Topik Ini</span>
                  </button>
                @endif
              </div>

            </div>
          </div>
        </div>
      @empty
        <div class="col-12">
          <div class="card shadow-sm border-light-subtle p-5 text-center">
            <i class="bi bi-folder-x fs-1 text-secondary mb-2"></i>
            <h3 class="h6 fw-bold text-dark">Belum ada topik TA yang dipublikasikan</h3>
            <p class="text-secondary small mb-0">Koordinator TA dan dosen pembimbing sedang menyusun dan meninjau daftar topik. Silakan cek kembali secara berkala.</p>
          </div>
        </div>
      @endforelse
    </div>

    <!-- Pagination -->
    @if ($topikList->hasPages())
      <div class="d-flex justify-content-center pt-3">
        {{ $topikList->links() }}
      </div>
    @endif

  </div>

  <!-- ================================================================= -->
  <!-- TAB 2: PROPOSAL SAYA (RIWAYAT DAN STATUS KEPUTUSAN DOSEN) -->
  <!-- ================================================================= -->
  <div x-show="activeTab === 'proposalku'" class="d-flex flex-column gap-3">
    <div class="card shadow-sm border-light-subtle">
      <div class="card-header bg-white py-3">
        <h2 class="h6 fw-bold text-dark mb-0">Daftar Proposal Tugas Akhir yang Anda Ajukan</h2>
        <p class="text-secondary small mb-0">Pantau perkembangan evaluasi dan catatan kelolosan dari dosen penilai.</p>
      </div>

      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0" style="font-size: 0.8125rem;">
          <thead class="table-light">
            <tr>
              <th class="py-2.5 px-3">Judul Proposal & Topik</th>
              <th class="py-2.5 px-3">Tgl Pengajuan</th>
              <th class="py-2.5 px-3">Berkas / Dokumen</th>
              <th class="py-2.5 px-3">Status Seleksi</th>
              <th class="py-2.5 px-3">Catatan Dosen Evaluator</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($myProposals as $proposal)
              @php $badge = $proposal->status_badge; @endphp
              <tr class="{{ $proposal->isLolos() ? 'table-success' : ($proposal->isTidakLolos() ? 'table-danger' : '') }}">
                <td class="px-3 py-3">
                  <p class="fw-bold text-dark mb-1">{{ $proposal->judul_proposal }}</p>
                  <span class="text-secondary small d-block">
                    Topik: <span class="fw-medium text-dark">{{ $proposal->topik->judul_topik ?? '-' }}</span>
                  </span>
                  @if ($proposal->ringkasan)
                    <p class="text-secondary small mt-1 mb-0 fst-italic text-truncate" style="max-width: 320px;">
                      "{{ $proposal->ringkasan }}"
                    </p>
                  @endif
                </td>
                <td class="px-3 py-3 text-nowrap text-secondary">
                  {{ $proposal->created_at->format('d M Y, H:i') }} WIB
                </td>
                <td class="px-3 py-3 text-nowrap">
                  <div class="d-flex flex-column gap-1">
                    @if ($proposal->file_proposal)
                      <a href="{{ asset('storage/' . $proposal->file_proposal) }}" target="_blank" class="small text-primary text-decoration-none fw-semibold">
                        <i class="bi bi-file-earmark-arrow-down me-1"></i>Unduh Berkas
                      </a>
                    @endif
                    @if ($proposal->link_ecourse)
                      <a href="{{ $proposal->link_ecourse }}" target="_blank" class="small text-secondary text-decoration-none">
                        <i class="bi bi-box-arrow-up-right me-1"></i>Tautan Pengumpulan
                      </a>
                    @endif
                    @if (!$proposal->file_proposal && !$proposal->link_ecourse)
                      <span class="text-secondary small">-</span>
                    @endif
                  </div>
                </td>
                <td class="px-3 py-3 text-nowrap">
                  <span class="badge {{ $badge['class'] }} d-inline-flex align-items-center gap-1 py-1 px-2">
                    <i class="bi {{ $badge['icon'] }}"></i>
                    <span>{{ $badge['label'] }}</span>
                  </span>
                  @if ($proposal->tanggal_keputusan)
                    <span class="d-block text-secondary small mt-1" style="font-size: 0.7rem;">
                      {{ $proposal->tanggal_keputusan->format('d M Y') }}
                    </span>
                  @endif
                </td>
                <td class="px-3 py-3">
                  @if ($proposal->catatan_dosen)
                    <div class="p-2 rounded bg-light border text-dark small" style="white-space: pre-line;">
                      {{ $proposal->catatan_dosen }}
                    </div>
                  @else
                    <span class="text-secondary small fst-italic">Belum ada catatan evaluasi.</span>
                  @endif
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="5" class="py-4 text-center text-secondary">
                  Anda belum pernah mengajukan proposal. Silakan pilih topik yang diminati pada tab "Katalog Topik".
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>

  @if (!($hasTugasAkhir ?? false))
  <!-- ================================================================= -->
  <!-- MODAL: FORMULIR PENGAJUAN PROPOSAL TUGAS AKHIR (MAHASISWA) -->
  <!-- ================================================================= -->
  <div x-show="submitModalOpen" x-cloak class="modal-backdrop-custom">
    <div class="modal-dialog modal-dialog-centered w-100 mx-3" style="max-width: 560px;" @click.away="submitModalOpen = false">
      <div class="modal-content bg-white shadow-lg border-0 rounded-3">
        
        <div class="modal-header border-bottom py-3 px-4">
          <div>
            <h5 class="modal-title fs-6 fw-bold text-dark mb-0">Ajukan Proposal Tugas Akhir</h5>
            <p class="text-secondary small mb-0">Isi rincian proposal berdasarkan topik yang Anda pilih.</p>
          </div>
          <button type="button" class="btn-close" @click="submitModalOpen = false"></button>
        </div>

        <template x-if="selectedTopik">
          <form :action="'/topik/' + selectedTopik.id + '/proposal'" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="modal-body p-4 d-flex flex-column gap-3" style="font-size: 0.8125rem;">
              
              <!-- Info Topik Pilihan (Readonly) -->
              <div class="p-3 rounded bg-light border border-light-subtle">
                <span class="text-secondary small fw-bold text-uppercase d-block mb-1" style="font-size: 0.65rem;">Topik Penelitian yang Dipilih:</span>
                <p class="fw-bold text-dark mb-1" x-text="selectedTopik.judul_topik"></p>
                <div class="d-flex align-items-center gap-2 mt-1">
                  <span class="badge bg-secondary-subtle text-secondary" x-text="selectedTopik.prodi"></span>
                  <span class="badge bg-white text-secondary border font-monospace text-[9px]">
                    <i class="bi bi-lock-fill me-0.5"></i> Dosen Dirahasiakan (Blind)
                  </span>
                </div>
              </div>

              <!-- Judul Proposal -->
              <div>
                <label class="form-label fw-semibold text-dark small mb-1">Judul Proposal Penelitian Anda <span class="text-danger">*</span></label>
                <input type="text" name="judul_proposal" required placeholder="Contoh: Analisis dan Perancangan Sistem Otomasi..." class="form-control form-control-sm">
                <span class="text-secondary small d-block mt-0.5" style="font-size: 0.72rem;">Dapat mengadopsi judul topik atau merumuskan judul spesifik turunan topik.</span>
              </div>

              <!-- Ringkasan / Latar Belakang Masalah -->
              <div>
                <label class="form-label fw-semibold text-dark small mb-1">Ringkasan / Rumusan Masalah Singkat</label>
                <textarea name="ringkasan" rows="3" placeholder="Tuliskan latar belakang singkat dan metodologi penyelesaian tugas akhir..." class="form-control form-control-sm"></textarea>
              </div>

              <!-- Unggah File Proposal -->
              <div>
                <label class="form-label fw-semibold text-dark small mb-1">Unggah Dokumen Proposal (Opsional)</label>
                <input type="file" name="file_proposal" accept=".pdf,.docx" class="form-control form-control-sm">
                <span class="text-secondary small d-block mt-0.5" style="font-size: 0.72rem;">Format PDF atau DOCX (Maksimal 25 MB).</span>
              </div>

              <!-- Link Pengumpulan eCourse / GDrive -->
              <div>
                <label class="form-label fw-semibold text-dark small mb-1">Tautan Pengumpulan eCourse / Google Drive (Opsional)</label>
                <input type="url" name="link_ecourse" placeholder="https://ecourse.del.ac.id/... atau https://drive.google.com/..." class="form-control form-control-sm">
              </div>

            </div>

            <div class="modal-footer border-top py-2 px-4 d-flex justify-content-end gap-2">
              <button type="button" @click="submitModalOpen = false" class="btn btn-outline-secondary btn-sm">Batal</button>
              <button type="submit" class="btn btn-dark btn-sm d-inline-flex align-items-center gap-1.5">
                <i class="bi bi-send"></i>
                <span>Kirimkan Pengajuan Proposal</span>
              </button>
            </div>
          </form>
        </template>

      </div>
    </div>
  </div>
  @endif

</div>
@endsection

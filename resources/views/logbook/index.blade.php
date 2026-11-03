@extends('layouts.notion')

@section('title', 'Logbook Aktivitas')
@section('breadcrumb', 'Logbook Aktivitas')

@section('content')
<div class="d-flex flex-column gap-3" x-data="{ 
  viewMode: 'table',
  openCreateModal: false,
  selectedLogbook: null,
  reviewDrawerOpen: false,
  openReview(log) {
    this.selectedLogbook = log;
    this.reviewDrawerOpen = true;
  }
}">

  <!-- Header Halaman Akademik -->
  <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 pb-3 border-bottom">
    <div>
      <h1 class="h4 fw-bold text-dark mb-1">Logbook Aktivitas & Progres TA</h1>
      <p class="text-secondary small mb-0">Dokumentasi berkala pelaksanaan riset tugas akhir dan riwayat evaluasi pembimbing.</p>
    </div>

    @if (auth()->user()->isMahasiswa())
      <button @click="openCreateModal = true" class="btn btn-dark btn-sm d-inline-flex align-items-center gap-1.5 shrink-0">
        <i class="bi bi-pencil-square"></i>
        <span>Isi Logbook Baru</span>
      </button>
    @endif
  </div>

  @if (auth()->user()->isMahasiswa() && $myTa)
    <div class="p-3 bg-white border rounded shadow-xs d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-2" style="font-size: 0.8125rem;">
      <div class="d-flex align-items-center gap-2">
        <span class="fw-bold text-dark">{{ Str::limit($myTa->judul, 70) }}</span>
        <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle">{{ $myTa->prodi }}</span>
      </div>
      @if ($myTa->anggota && $myTa->anggota->isNotEmpty())
        <div class="d-flex align-items-center gap-1.5 text-secondary small">
          <span class="fw-semibold text-dark">Kelompok:</span>
          @foreach ($myTa->anggota as $member)
            <span class="badge bg-light text-dark border">{{ $member->user->name ?? '-' }} ({{ $member->nim }})</span>
          @endforeach
        </div>
      @endif
    </div>
  @endif

  <!-- View Mode Switcher & Filter Controls -->
  <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">
    
    <!-- View Switcher -->
    <div class="btn-group btn-group-sm" role="group">
      <button type="button" @click="viewMode = 'table'" 
              :class="viewMode === 'table' ? 'btn btn-dark' : 'btn btn-outline-secondary'"
              class="d-inline-flex align-items-center gap-1.5">
        <i class="bi bi-table"></i>
        <span>Tabel</span>
      </button>
      <button type="button" @click="viewMode = 'kanban'" 
              :class="viewMode === 'kanban' ? 'btn btn-dark' : 'btn btn-outline-secondary'"
              class="d-inline-flex align-items-center gap-1.5">
        <i class="bi bi-kanban"></i>
        <span>Papan</span>
      </button>
      <button type="button" @click="viewMode = 'list'" 
              :class="viewMode === 'list' ? 'btn btn-dark' : 'btn btn-outline-secondary'"
              class="d-inline-flex align-items-center gap-1.5">
        <i class="bi bi-list-ul"></i>
        <span>Daftar</span>
      </button>
    </div>

    <!-- Quick Filters Form -->
    <form method="GET" action="{{ route('logbook.index') }}" class="d-flex align-items-center gap-2">
      <select name="status" onchange="this.form.submit()" class="form-select form-select-sm" style="width: auto; font-size: 0.8125rem;">
        <option value="">Semua Status</option>
        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Menunggu Evaluasi</option>
        <option value="revisi" {{ request('status') === 'revisi' ? 'selected' : '' }}>Perlu Revisi</option>
        <option value="disetujui" {{ request('status') === 'disetujui' ? 'selected' : '' }}>Disetujui</option>
      </select>

      <select name="bab" onchange="this.form.submit()" class="form-select form-select-sm" style="width: auto; font-size: 0.8125rem;">
        <option value="">Semua Bab</option>
        <option value="BAB 1" {{ request('bab') === 'BAB 1' ? 'selected' : '' }}>BAB 1</option>
        <option value="BAB 2" {{ request('bab') === 'BAB 2' ? 'selected' : '' }}>BAB 2</option>
        <option value="BAB 3" {{ request('bab') === 'BAB 3' ? 'selected' : '' }}>BAB 3</option>
        <option value="BAB 4" {{ request('bab') === 'BAB 4' ? 'selected' : '' }}>BAB 4</option>
        <option value="BAB 5" {{ request('bab') === 'BAB 5' ? 'selected' : '' }}>BAB 5</option>
      </select>
    </form>

  </div>

  <!-- VIEW 1: TABLE VIEW -->
  <div x-show="viewMode === 'table'" class="card shadow-sm border-light-subtle">
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0" style="font-size: 0.8125rem;">
        <thead class="table-light">
          <tr>
            <th class="py-2.5 px-3">Tgl / Bab</th>
            @if (!auth()->user()->isMahasiswa())
              <th class="py-2.5 px-3">Mahasiswa</th>
            @endif
            <th class="py-2.5 px-3">Aktivitas Progres</th>
            <th class="py-2.5 px-3">Kendala / Rencana</th>
            <th class="py-2.5 px-3">Dokumen Draf</th>
            <th class="py-2.5 px-3">Catatan Evaluasi Dosen</th>
            <th class="py-2.5 px-3">Status</th>
            <th class="py-2.5 px-3 text-end">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse ($logbooks as $log)
            <tr>
              <td class="px-3 py-3 text-nowrap">
                <span class="fw-bold text-dark d-block">{{ $log->tanggal->format('d M Y') }}</span>
                <span class="badge bg-light text-secondary border mt-0.5">{{ $log->bab_progress }}</span>
              </td>

              @if (!auth()->user()->isMahasiswa())
                <td class="px-3 py-3">
                  @if ($log->tugasAkhir->anggota && $log->tugasAkhir->anggota->isNotEmpty())
                    <div class="d-flex flex-column gap-1">
                      <div class="d-flex flex-wrap gap-1">
                        @foreach ($log->tugasAkhir->anggota as $member)
                          <span class="badge bg-light text-dark border">
                            <span class="fw-semibold">{{ $member->user->name ?? '-' }}</span>
                            <span class="text-secondary">({{ $member->nim }})</span>
                          </span>
                        @endforeach
                      </div>
                    </div>
                  @else
                    <p class="fw-bold text-dark mb-0">{{ $log->tugasAkhir->mahasiswa->user->name ?? '-' }}</p>
                    <p class="text-secondary small mb-0">{{ $log->tugasAkhir->mahasiswa->nim ?? '-' }}</p>
                  @endif
                </td>
              @endif

              <td class="px-3 py-3" style="max-width: 280px;">
                <p class="fw-medium text-dark mb-0 line-clamp-2">{{ $log->deskripsi_kegiatan }}</p>
              </td>

              <td class="px-3 py-3 text-secondary" style="max-width: 240px;">
                @if ($log->kendala)
                  <p class="text-warning-emphasis small mb-0"><strong>Kendala:</strong> {{ $log->kendala }}</p>
                @endif
                @if ($log->rencana_berikutnya)
                  <p class="small text-secondary mb-0"><strong>Rencana:</strong> {{ $log->rencana_berikutnya }}</p>
                @endif
                @if (!$log->kendala && !$log->rencana_berikutnya)
                  <span class="small">-</span>
                @endif
              </td>

              <td class="px-3 py-3 text-nowrap">
                @if ($log->file_dokumen_mhs)
                  <a href="{{ asset('storage/' . $log->file_dokumen_mhs) }}" target="_blank" class="btn btn-outline-dark btn-sm py-1 px-2 d-inline-flex align-items-center gap-1" style="font-size: 0.75rem;">
                    <i class="bi bi-file-earmark-text text-primary"></i> Draf Mhs
                  </a>
                @else
                  <span class="text-secondary small">-</span>
                @endif

                @if ($log->file_revisi_dosen)
                  <a href="{{ asset('storage/' . $log->file_revisi_dosen) }}" target="_blank" class="btn btn-outline-danger btn-sm py-1 px-2 d-inline-flex align-items-center gap-1 ms-1" style="font-size: 0.75rem;">
                    <i class="bi bi-pencil-fill text-danger"></i> Coretan
                  </a>
                @endif
              </td>

              <td class="px-3 py-3 text-secondary" style="max-width: 240px;">
                <p class="fst-italic text-dark mb-0 small">{{ $log->catatan_dosen ?? 'Belum ada catatan evaluasi' }}</p>
                @if ($log->verifiedBy)
                  <span class="small text-secondary d-block mt-0.5">— {{ $log->verifiedBy->user->name }}</span>
                @endif
              </td>

              <td class="px-3 py-3 text-nowrap">
                @if ($log->status_verifikasi === 'disetujui')
                  <span class="badge bg-success-subtle text-success border border-success-subtle">Disetujui</span>
                @elseif ($log->status_verifikasi === 'revisi')
                  <span class="badge bg-danger-subtle text-danger border border-danger-subtle">Perlu Revisi</span>
                @else
                  <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle">Menunggu Evaluasi</span>
                @endif
              </td>

              <td class="px-3 py-3 text-end text-nowrap">
                @if (auth()->user()->isDosen() && auth()->user()->isPembimbingOf($log->tugasAkhir))
                  <button @click="openReview({{ $log->toJson() }})" class="btn btn-dark btn-sm">
                    Evaluasi & Revisi
                  </button>
                @elseif (auth()->user()->isDosen())
                  <span class="text-secondary small">Bukan Pembimbing</span>
                @else
                  <span class="badge bg-light text-secondary border">Terdata</span>
                @endif
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="8" class="py-4 text-center text-secondary">
                Tidak ada data logbook yang ditemukan.
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    @if ($logbooks->hasPages())
      <div class="card-footer bg-light p-3">
        {{ $logbooks->links() }}
      </div>
    @endif
  </div>

  <!-- VIEW 2: KANBAN BOARD VIEW -->
  <div x-show="viewMode === 'kanban'" class="row g-3">
    <!-- Col 1: Pending -->
    <div class="col-12 col-md-4">
      <div class="card shadow-sm border-warning-subtle h-100 bg-warning-subtle/20">
        <div class="card-header bg-white py-2.5 d-flex align-items-center justify-content-between border-warning-subtle">
          <span class="fw-bold small text-dark">Menunggu Evaluasi</span>
          <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle">{{ $logbooks->where('status_verifikasi', 'pending')->count() }}</span>
        </div>
        <div class="card-body p-3 d-flex flex-column gap-2">
          @forelse ($logbooks->where('status_verifikasi', 'pending') as $item)
            <div class="card shadow-xs border p-2.5 bg-white">
              <div class="d-flex align-items-center justify-content-between text-secondary small mb-1" style="font-size: 0.75rem;">
                <span class="badge bg-light text-dark border">{{ $item->bab_progress }}</span>
                <span>{{ $item->tanggal->format('d M Y') }}</span>
              </div>
              <p class="small fw-semibold text-dark mb-0 line-clamp-2">{{ $item->deskripsi_kegiatan }}</p>
            </div>
          @empty
            <p class="text-secondary small text-center my-3">Kosong</p>
          @endforelse
        </div>
      </div>
    </div>

    <!-- Col 2: Revisi -->
    <div class="col-12 col-md-4">
      <div class="card shadow-sm border-danger-subtle h-100 bg-danger-subtle/20">
        <div class="card-header bg-white py-2.5 d-flex align-items-center justify-content-between border-danger-subtle">
          <span class="fw-bold small text-dark">Perlu Revisi</span>
          <span class="badge bg-danger-subtle text-danger border border-danger-subtle">{{ $logbooks->where('status_verifikasi', 'revisi')->count() }}</span>
        </div>
        <div class="card-body p-3 d-flex flex-column gap-2">
          @forelse ($logbooks->where('status_verifikasi', 'revisi') as $item)
            <div class="card shadow-xs border p-2.5 bg-white">
              <div class="d-flex align-items-center justify-content-between text-secondary small mb-1" style="font-size: 0.75rem;">
                <span class="badge bg-light text-dark border">{{ $item->bab_progress }}</span>
                <span>{{ $item->tanggal->format('d M Y') }}</span>
              </div>
              <p class="small fw-semibold text-dark mb-1 line-clamp-2">{{ $item->deskripsi_kegiatan }}</p>
              @if ($item->catatan_dosen)
                <p class="text-danger small fst-italic mb-0" style="font-size: 0.75rem;">{{ $item->catatan_dosen }}</p>
              @endif
            </div>
          @empty
            <p class="text-secondary small text-center my-3">Kosong</p>
          @endforelse
        </div>
      </div>
    </div>

    <!-- Col 3: Disetujui -->
    <div class="col-12 col-md-4">
      <div class="card shadow-sm border-success-subtle h-100 bg-success-subtle/20">
        <div class="card-header bg-white py-2.5 d-flex align-items-center justify-content-between border-success-subtle">
          <span class="fw-bold small text-dark">Disetujui</span>
          <span class="badge bg-success-subtle text-success border border-success-subtle">{{ $logbooks->where('status_verifikasi', 'disetujui')->count() }}</span>
        </div>
        <div class="card-body p-3 d-flex flex-column gap-2">
          @forelse ($logbooks->where('status_verifikasi', 'disetujui') as $item)
            <div class="card shadow-xs border p-2.5 bg-white">
              <div class="d-flex align-items-center justify-content-between text-secondary small mb-1" style="font-size: 0.75rem;">
                <span class="badge bg-light text-dark border">{{ $item->bab_progress }}</span>
                <span>{{ $item->tanggal->format('d M Y') }}</span>
              </div>
              <p class="small fw-semibold text-dark mb-0 line-clamp-2">{{ $item->deskripsi_kegiatan }}</p>
            </div>
          @empty
            <p class="text-secondary small text-center my-3">Kosong</p>
          @endforelse
        </div>
      </div>
    </div>
  </div>

  <!-- VIEW 3: LIST VIEW -->
  <div x-show="viewMode === 'list'" class="list-group shadow-sm">
    @forelse ($logbooks as $item)
      <div class="list-group-item list-group-item-action d-flex align-items-center justify-content-between py-2.5 px-3">
        <div class="d-flex align-items-center gap-2.5">
          <span class="badge bg-light text-secondary border">{{ $item->bab_progress }}</span>
          <span class="small fw-semibold text-dark">{{ $item->tanggal->format('d M Y') }} — {{ Str::limit($item->deskripsi_kegiatan, 80) }}</span>
        </div>
        <div>
          @if ($item->status_verifikasi === 'disetujui')
            <span class="badge bg-success-subtle text-success border border-success-subtle">Disetujui</span>
          @elseif ($item->status_verifikasi === 'revisi')
            <span class="badge bg-danger-subtle text-danger border border-danger-subtle">Perlu Revisi</span>
          @else
            <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle">Menunggu</span>
          @endif
        </div>
      </div>
    @empty
      <div class="list-group-item text-center py-4 text-secondary small">
        Tidak ada data logbook.
      </div>
    @endforelse
  </div>

  <!-- ================================================================= -->
  <!-- MODAL: ISI LOGBOOK BARU (Untuk Mahasiswa) -->
  <!-- ================================================================= -->
  @if (auth()->user()->isMahasiswa())
    @php
      $myTa = auth()->user()->tugasAkhir();
    @endphp

    <div x-show="openCreateModal" x-cloak class="modal-backdrop-custom">
      <div class="modal-dialog modal-dialog-centered w-100 mx-3" style="max-width: 540px;" @click.away="openCreateModal = false">
        <div class="modal-content bg-white shadow-lg border-0 rounded-3">
          
          <div class="modal-header border-bottom py-3 px-4">
            <div>
              <h5 class="modal-title fs-6 fw-bold text-dark mb-0">Catat Entri Logbook Aktivitas Baru</h5>
              <p class="text-secondary small mb-0">Laporkan capaian pengerjaan bab tugas akhir Anda</p>
            </div>
            <button type="button" class="btn-close" @click="openCreateModal = false"></button>
          </div>

          @if ($myTa)
            <form action="{{ route('logbook.store') }}" method="POST" enctype="multipart/form-data">
              @csrf
              <input type="hidden" name="tugas_akhir_id" value="{{ $myTa->id }}">

              <div class="modal-body p-4 d-flex flex-column gap-3" style="font-size: 0.8125rem;">
                <div class="row g-2">
                  <div class="col-6">
                    <label class="form-label fw-semibold text-secondary small mb-1">Tanggal Kegiatan</label>
                    <input type="date" name="tanggal" value="{{ date('Y-m-d') }}" required class="form-control form-control-sm">
                  </div>
                  <div class="col-6">
                    <label class="form-label fw-semibold text-secondary small mb-1">Fokus Bab Progres</label>
                    <select name="bab_progress" required class="form-select form-select-sm">
                      <option value="BAB 1">BAB 1 - Pendahuluan</option>
                      <option value="BAB 2">BAB 2 - Tinjauan Pustaka</option>
                      <option value="BAB 3">BAB 3 - Metodologi</option>
                      <option value="BAB 4" selected>BAB 4 - Implementasi & Hasil</option>
                      <option value="BAB 5">BAB 5 - Kesimpulan</option>
                      <option value="Pengujian">Pengujian Sistem / Model</option>
                      <option value="Lainnya">Lainnya</option>
                    </select>
                  </div>
                </div>

                <div>
                  <label class="form-label fw-semibold text-secondary small mb-1">Deskripsi Kegiatan & Progres Naskah</label>
                  <textarea name="deskripsi_kegiatan" rows="3" required placeholder="Jelaskan bagian subbab yang disusun atau eksperimen yang dilakukan..." class="form-control form-control-sm"></textarea>
                </div>

                <div>
                  <label class="form-label fw-semibold text-secondary small mb-1">Kendala / Masalah yang Dihadapi (Opsional)</label>
                  <input type="text" name="kendala" placeholder="Contoh: Overfitting model pada kelas DoS..." class="form-control form-control-sm">
                </div>

                <div>
                  <label class="form-label fw-semibold text-secondary small mb-1">Rencana Kerja Berikutnya</label>
                  <input type="text" name="rencana_berikutnya" placeholder="Contoh: Menyusun confusion matrix dan evaluasi ROC-AUC..." class="form-control form-control-sm">
                </div>

                <div>
                  <label class="form-label fw-semibold text-secondary small mb-1">Unggah Dokumen Progres (PDF / DOCX, maks 25MB)</label>
                  <input type="file" name="file_dokumen_mhs" accept=".pdf,.docx,.zip" class="form-control form-control-sm">
                </div>
              </div>

              <div class="modal-footer border-top py-2 px-4 d-flex justify-content-end gap-2">
                <button type="button" @click="openCreateModal = false" class="btn btn-outline-secondary btn-sm">Batal</button>
                <button type="submit" class="btn btn-dark btn-sm">Simpan Logbook</button>
              </div>
            </form>
          @else
            <div class="modal-body p-4 text-center text-danger small">
              Anda belum memiliki Tugas Akhir yang terdaftar. Hubungi Koordinator TA.
            </div>
          @endif

        </div>
      </div>
    </div>
  @endif

  @if (auth()->user()->isDosen())
  <!-- ================================================================= -->
  <!-- MODAL: EVALUASI & VERIFIKASI LOGBOOK (DOSEN PEMBIMBING) -->
  <!-- ================================================================= -->
  <div x-show="reviewDrawerOpen" x-cloak class="modal-backdrop-custom">
    <div class="modal-dialog modal-dialog-centered w-100 mx-3" style="max-width: 540px;" @click.away="reviewDrawerOpen = false">
      <div class="modal-content bg-white shadow-lg border-0 rounded-3">
        
        <div class="modal-header border-bottom py-3 px-4">
          <div>
            <h5 class="modal-title fs-6 fw-bold text-dark mb-0">Evaluasi & Verifikasi Logbook Mahasiswa</h5>
            <p class="text-secondary small mb-0">Berikan umpan balik catatan dan unggah coretan revisi dokumen</p>
          </div>
          <button type="button" class="btn-close" @click="reviewDrawerOpen = false"></button>
        </div>

        <template x-if="selectedLogbook">
          <form :action="'/logbook/' + selectedLogbook.id + '/review'" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PATCH')

            <div class="modal-body p-4 d-flex flex-column gap-3" style="font-size: 0.8125rem;">
              <!-- Info Mahasiswa & Logbook -->
              <div class="p-3 bg-light rounded border">
                <p class="small fw-bold text-dark mb-1" x-text="'Bab: ' + selectedLogbook.bab_progress + ' (' + selectedLogbook.tanggal + ')'"></p>
                <p class="text-secondary small mb-0" x-text="selectedLogbook.deskripsi_kegiatan"></p>
                <template x-if="selectedLogbook.kendala">
                  <p class="text-warning-emphasis small mt-1 mb-0" x-text="'Kendala: ' + selectedLogbook.kendala"></p>
                </template>
              </div>

              <!-- Form Catatan Revisi Dosen -->
              <div>
                <label class="form-label fw-semibold text-secondary small mb-1">Catatan Evaluasi / Arahan Revisi Dosen</label>
                <textarea name="catatan_dosen" rows="3" required x-model="selectedLogbook.catatan_dosen"
                          placeholder="Tuliskan arahan perbaikan penulisan naskah atau pengujian model..."
                          class="form-control form-control-sm"></textarea>
              </div>

              <!-- Upload File Revisi Coretan -->
              <div>
                <label class="form-label fw-semibold text-secondary small mb-1">Unggah Dokumen Hasil Coretan Revisi (PDF / DOCX)</label>
                <input type="file" name="file_revisi_dosen" accept=".pdf,.docx,.zip" class="form-control form-control-sm">
              </div>

              <!-- Pilihan Status Verifikasi -->
              <div>
                <label class="form-label fw-semibold text-secondary small mb-2">Keputusan Status Verifikasi</label>
                <div class="d-flex gap-2">
                  <label class="btn btn-outline-success btn-sm flex-fill d-flex align-items-center justify-content-center gap-1.5 py-2 cursor-pointer">
                    <input type="radio" name="status_verifikasi" value="disetujui" :checked="selectedLogbook.status_verifikasi === 'disetujui'" class="form-check-input mt-0">
                    <span class="fw-semibold">Disetujui</span>
                  </label>
                  <label class="btn btn-outline-danger btn-sm flex-fill d-flex align-items-center justify-content-center gap-1.5 py-2 cursor-pointer">
                    <input type="radio" name="status_verifikasi" value="revisi" :checked="selectedLogbook.status_verifikasi === 'revisi'" class="form-check-input mt-0">
                    <span class="fw-semibold">Perlu Revisi</span>
                  </label>
                  <label class="btn btn-outline-secondary btn-sm flex-fill d-flex align-items-center justify-content-center gap-1.5 py-2 cursor-pointer">
                    <input type="radio" name="status_verifikasi" value="pending" :checked="selectedLogbook.status_verifikasi === 'pending'" class="form-check-input mt-0">
                    <span class="fw-semibold">Menunggu</span>
                  </label>
                </div>
              </div>
            </div>

            <div class="modal-footer border-top py-2 px-4 d-flex justify-content-end gap-2">
              <button type="button" @click="reviewDrawerOpen = false" class="btn btn-outline-secondary btn-sm">Batal</button>
              <button type="submit" class="btn btn-dark btn-sm">Simpan Evaluasi Dosen</button>
            </div>
          </form>
        </template>

      </div>
    </div>
  </div>
  @endif

</div>
@endsection

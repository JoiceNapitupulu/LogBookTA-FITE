@extends('layouts.notion')

@section('title', 'Manajemen Ujian TA')
@section('breadcrumb', 'Ujian TA')

@section('content')
<div class="d-flex flex-column gap-3" x-data="{ 
  activeTab: '{{ auth()->user()->isBaak() ? 'baak' : (auth()->user()->isKoordinator() ? 'koordinator' : (auth()->user()->isTA() ? 'ta' : 'mahasiswa')) }}',
  selectedUjian: null,
  verifyModalOpen: false,
  scheduleModalOpen: false,
  openVerify(item) {
    this.selectedUjian = item;
    this.verifyModalOpen = true;
  },
  openSchedule(item) {
    this.selectedUjian = item;
    this.scheduleModalOpen = true;
  }
}">

  <!-- Header Halaman Akademik -->
  <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 pb-3 border-bottom">
    <div>
      <h1 class="h4 fw-bold text-dark mb-1">Alur Pendaftaran & Manajemen Ujian TA</h1>
      <p class="text-secondary small mb-0">Kolaborasi terintegrasi: Mahasiswa TA (Pendaftaran) &rarr; Koordinator TA (Verifikasi Berkas) &rarr; BAAK (Jadwal, Penguji & Fasilitas Ruangan).</p>
    </div>

    <!-- Tab Switcher ala Bootstrap Nav Pills -->
    <div class="nav nav-pills bg-light p-1 rounded border gap-1" style="font-size: 0.8125rem;">
      <button @click="activeTab = 'mahasiswa'" :class="activeTab === 'mahasiswa' ? 'active bg-white text-dark shadow-sm fw-semibold' : 'text-secondary'" class="nav-link py-1 px-3 border-0">
        1. Mahasiswa TA
      </button>
      @if (!auth()->user()->isMahasiswa())
      <button @click="activeTab = 'koordinator'" :class="activeTab === 'koordinator' ? 'active bg-white text-dark shadow-sm fw-semibold' : 'text-secondary'" class="nav-link py-1 px-3 border-0">
        2. Koordinator TA
      </button>
      <button @click="activeTab = 'baak'" :class="activeTab === 'baak' ? 'active bg-white text-dark shadow-sm fw-semibold' : 'text-secondary'" class="nav-link py-1 px-3 border-0">
        3. BAAK
      </button>
      <button @click="activeTab = 'ta'" :class="activeTab === 'ta' ? 'active bg-white text-dark shadow-sm fw-semibold' : 'text-secondary'" class="nav-link py-1 px-3 border-0">
        4. Pantauan Staf / TA
      </button>
      @endif
    </div>
  </div>

  <!-- ================================================================= -->
  <!-- TAB 1: MAHASISWA TA -->
  <!-- ================================================================= -->
  <div x-show="activeTab === 'mahasiswa'" class="d-flex flex-column gap-3">
    
    <!-- Status Alur Verifikasi Terkini -->
    @if ($myUjian)
      <div class="card shadow-sm border-light-subtle">
        <div class="card-body p-3 p-md-4">
          <div class="d-flex align-items-center justify-content-between mb-3">
            <div>
              <h2 class="h6 fw-bold text-dark mb-0">Status Pendaftaran Ujian Terkini</h2>
              <p class="text-secondary small mb-0">Jenis Ujian: <strong class="text-dark">{{ $myUjian->jenis_ujian_label }}</strong></p>
            </div>
            <span class="badge bg-light text-dark border">TA Aktif</span>
          </div>

          <div class="row g-2 text-center" style="font-size: 0.75rem;">
            <!-- Step 1 -->
            <div class="col-12 col-sm-6 col-md-3">
              <div class="p-3 rounded border border-success-subtle bg-success-subtle/40 h-100 text-start">
                <span class="text-success fw-bold text-uppercase d-block" style="font-size: 0.65rem;">Langkah 1</span>
                <p class="fw-bold text-dark mb-1">Pengajuan Berkas</p>
                <span class="text-success small fw-medium"><i class="bi bi-check-circle-fill me-0.5"></i> Diajukan Mahasiswa</span>
              </div>
            </div>

            <!-- Step 2 -->
            <div class="col-12 col-sm-6 col-md-3">
              <div class="p-3 rounded border {{ $myUjian->status_dokumen === 'berkas_lengkap' ? 'border-success-subtle bg-success-subtle/40' : 'border-warning-subtle bg-warning-subtle/40' }} h-100 text-start">
                <span class="{{ $myUjian->status_dokumen === 'berkas_lengkap' ? 'text-success' : 'text-warning-emphasis' }} fw-bold text-uppercase d-block" style="font-size: 0.65rem;">Langkah 2</span>
                <p class="fw-bold text-dark mb-1">Verifikasi Berkas</p>
                <span class="{{ $myUjian->status_dokumen === 'berkas_lengkap' ? 'text-success' : 'text-warning-emphasis' }} small fw-medium">
                  @if ($myUjian->status_dokumen === 'berkas_lengkap')
                    <i class="bi bi-check-circle-fill me-0.5"></i> Lengkap & Valid
                  @else
                    <i class="bi bi-clock-history me-0.5"></i> Menunggu Koordinator TA
                  @endif
                </span>
              </div>
            </div>

            <!-- Step 3 -->
            <div class="col-12 col-sm-6 col-md-3">
              <div class="p-3 rounded border {{ $myUjian->tanggal_ujian ? 'border-success-subtle bg-success-subtle/40' : 'border-light-subtle bg-light' }} h-100 text-start">
                <span class="{{ $myUjian->tanggal_ujian ? 'text-success' : 'text-secondary' }} fw-bold text-uppercase d-block" style="font-size: 0.65rem;">Langkah 3</span>
                <p class="fw-bold text-dark mb-1">Tanggal & Penguji</p>
                <span class="{{ $myUjian->tanggal_ujian ? 'text-success' : 'text-secondary' }} small fw-medium">
                  {{ $myUjian->tanggal_ujian ? $myUjian->tanggal_ujian->format('d M Y') . ' (BAAK)' : 'Menunggu BAAK' }}
                </span>
                @if ($myUjian->penguji1 || $myUjian->penguji2)
                  <div class="mt-2 pt-2 border-top text-secondary small" style="font-size: 0.7rem;">
                    <div><strong>P1:</strong> {{ $myUjian->penguji1->user->name ?? '-' }}</div>
                    <div><strong>P2:</strong> {{ $myUjian->penguji2->user->name ?? '-' }}</div>
                  </div>
                @endif
              </div>
            </div>

            <!-- Step 4 -->
            <div class="col-12 col-sm-6 col-md-3">
              <div class="p-3 rounded border {{ $myUjian->ruangan ? 'border-success-subtle bg-success-subtle/40' : 'border-light-subtle bg-light' }} h-100 text-start">
                <span class="{{ $myUjian->ruangan ? 'text-success' : 'text-secondary' }} fw-bold text-uppercase d-block" style="font-size: 0.65rem;">Langkah 4</span>
                <p class="fw-bold text-dark mb-1">Ruang & Fasilitas</p>
                <span class="{{ $myUjian->ruangan ? 'text-success' : 'text-secondary' }} small fw-medium">
                  {{ $myUjian->ruangan ?? 'Menunggu BAAK' }}
                </span>
                @if ($myUjian->jam_mulai && $myUjian->jam_selesai)
                  <p class="text-secondary small mt-1 mb-0" style="font-size: 0.7rem;">{{ substr($myUjian->jam_mulai, 0, 5) }} - {{ substr($myUjian->jam_selesai, 0, 5) }} WIB</p>
                @endif
                @if ($myUjian->link_zoom)
                  <div class="mt-1">
                    <a href="{{ $myUjian->link_zoom }}" target="_blank" class="small text-primary text-decoration-none fw-semibold">
                      <i class="bi bi-camera-video me-0.5"></i> Tautan Zoom
                    </a>
                  </div>
                @endif
              </div>
            </div>
          </div>
        </div>
      </div>
    @endif

    <!-- Formulir Pendaftaran Ujian Baru -->
    @if ($myTugasAkhir)
      <div class="card shadow-sm border-light-subtle">
        <div class="card-header bg-white py-3">
          <h2 class="h6 fw-bold text-dark mb-0">Formulir Pendaftaran Ujian Tugas Akhir FITE</h2>
          <p class="text-secondary small mb-0">Pilih tahapan ujian resmi dan unggah bundel naskah serta berkas persyaratan.</p>
        </div>

        <div class="card-body p-3 p-md-4">
          <form action="{{ route('ujian.store') }}" method="POST" enctype="multipart/form-data" class="d-flex flex-column gap-3" style="font-size: 0.8125rem;">
            @csrf
            <input type="hidden" name="tugas_akhir_id" value="{{ $myTugasAkhir->id }}">

            <div class="row g-3">
              <div class="col-12 col-md-6">
                <label class="form-label fw-semibold text-secondary small mb-1">Pilih Tahap Ujian Resmi</label>
                <select name="jenis_ujian" required class="form-select form-select-sm">
                  <option value="seminar_proposal">1. Seminar Proposal (BAB 1–3)</option>
                  <option value="seminar_ta_1" selected>2. Seminar TA 1</option>
                  <option value="seminar_hasil">3. Seminar Hasil</option>
                  <option value="seminar_skripsi">4. Seminar Tugas Akhir / Skripsi</option>
                </select>
              </div>

              <div class="col-12 col-md-6">
                <label class="form-label fw-semibold text-secondary small mb-1">Judul Tugas Akhir Terdaftar</label>
                <input type="text" readonly value="{{ $myTugasAkhir->judul }}" class="form-control form-control-sm bg-light text-secondary">
                @if ($myTugasAkhir->anggota && $myTugasAkhir->anggota->isNotEmpty())
                  <div class="mt-1 small text-secondary">
                    Kelompok: <strong class="text-dark">{{ $myTugasAkhir->anggota->pluck('user.name')->implode(', ') }}</strong> ({{ $myTugasAkhir->jumlahAnggota() }} Mahasiswa)
                  </div>
                @endif
              </div>
            </div>

            <div>
              <label class="form-label fw-semibold text-secondary small mb-1">Unggah Bundel Persyaratan (Naskah, Form Persetujuan Pembimbing, Bukti Logbook - PDF/ZIP, maks 35MB)</label>
              <input type="file" name="file_persyaratan" required accept=".pdf,.zip,.rar" class="form-control form-control-sm">
            </div>

            <div class="d-flex justify-content-end pt-2">
              <button type="submit" class="btn btn-dark btn-sm d-inline-flex align-items-center gap-1.5">
                <i class="bi bi-send"></i>
                <span>Kirim Pendaftaran Ujian ke Koordinator TA</span>
              </button>
            </div>
          </form>
        </div>
      </div>
    @endif

  </div>

  @if (!auth()->user()->isMahasiswa() && isset($allUjian))
  <!-- ================================================================= -->
  <!-- TAB 2: KOORDINATOR TA (VERIFIKASI BERKAS PENDAFTARAN) -->
  <!-- ================================================================= -->
  <div x-show="activeTab === 'koordinator'" class="d-flex flex-column gap-3">
    <div class="card shadow-sm border-light-subtle">
      <div class="card-header bg-white py-3">
        <h2 class="h6 fw-bold text-dark mb-0">Verifikasi Berkas Pendaftaran Ujian (Wewenang Koordinator TA)</h2>
        <p class="text-secondary small mb-0">Koordinator TA memeriksa kelengkapan administrasi berkas dan naskah sebelum diteruskan ke BAAK.</p>
      </div>

      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0" style="font-size: 0.8125rem;">
          <thead class="table-light">
            <tr>
              <th class="py-2.5 px-3">Mahasiswa & NIM</th>
              <th class="py-2.5 px-3">Jenis Ujian</th>
              <th class="py-2.5 px-3">Bundel Berkas</th>
              <th class="py-2.5 px-3">Status Dokumen</th>
              <th class="py-2.5 px-3 text-end">Aksi Koordinator</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($allUjian as $item)
              <tr class="{{ $item->status_dokumen === 'menunggu_verifikasi' ? 'table-warning' : '' }}">
                <td class="px-3 py-3">
                  @if ($item->tugasAkhir->anggota && $item->tugasAkhir->anggota->isNotEmpty())
                    <div class="d-flex flex-column gap-1">
                      <div class="d-flex flex-wrap gap-1">
                        @foreach ($item->tugasAkhir->anggota as $member)
                          <span class="badge bg-light text-dark border">
                            <span class="fw-semibold">{{ $member->user->name ?? '-' }}</span>
                            <span class="text-secondary">({{ $member->nim }})</span>
                          </span>
                        @endforeach
                      </div>
                      <span class="text-secondary small">{{ $item->tugasAkhir->prodi ?? '-' }} · {{ $item->tugasAkhir->jumlahAnggota() }} Mahasiswa</span>
                    </div>
                  @else
                    <p class="fw-bold text-dark mb-0">{{ $item->tugasAkhir->mahasiswa->user->name ?? '-' }}</p>
                    <p class="text-secondary small mb-0">{{ $item->tugasAkhir->mahasiswa->nim ?? '-' }} · {{ $item->tugasAkhir->prodi ?? '-' }}</p>
                  @endif
                </td>
                <td class="px-3 py-3 text-nowrap">
                  <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle">{{ $item->jenis_ujian_label }}</span>
                </td>
                <td class="px-3 py-3 text-nowrap">
                  <a href="{{ asset('storage/' . $item->file_persyaratan) }}" target="_blank" class="btn btn-outline-dark btn-sm d-inline-flex align-items-center gap-1">
                    <i class="bi bi-file-earmark-arrow-down text-primary"></i>
                    <span>Unduh Berkas</span>
                  </a>
                </td>
                <td class="px-3 py-3 text-nowrap">
                  @if ($item->status_dokumen === 'berkas_lengkap')
                    <span class="badge bg-success-subtle text-success border border-success-subtle">Lengkap & Valid</span>
                  @elseif ($item->status_dokumen === 'berkas_kurang')
                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle">Kurang Lengkap</span>
                  @else
                    <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle">Menunggu Verifikasi</span>
                  @endif
                </td>
                <td class="px-3 py-3 text-end text-nowrap">
                  @if (auth()->user()->isKoordinator())
                    <button @click="openVerify({{ $item->toJson() }})" class="btn btn-dark btn-sm">
                      Verifikasi Dokumen
                    </button>
                  @else
                    <span class="text-secondary small fst-italic">Khusus Koordinator TA</span>
                  @endif
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="5" class="py-4 text-center text-secondary">Belum ada antrean berkas ujian.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- ================================================================= -->
  <!-- TAB 3: BAAK (JADWAL, 2 PENGUJI & FASILITAS RUANGAN) -->
  <!-- ================================================================= -->
  <div x-show="activeTab === 'baak'" class="d-flex flex-column gap-3">
    <div class="card shadow-sm border-light-subtle">
      <div class="card-header bg-white py-3">
        <h2 class="h6 fw-bold text-dark mb-0">Penetapan Tanggal, Tim Penguji & Fasilitas Ruangan (BAAK IT Del)</h2>
        <p class="text-secondary small mb-0">BAAK menetapkan tanggal pelaksanaan ujian, menunjuk 2 Dosen Penguji, alokasi ruang sidang, dan link Zoom.</p>
      </div>

      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0" style="font-size: 0.8125rem;">
          <thead class="table-light">
            <tr>
              <th class="py-2.5 px-3">Mahasiswa & Jenis Ujian</th>
              <th class="py-2.5 px-3">Tgl Ujian</th>
              <th class="py-2.5 px-3">Tim Dosen Penguji</th>
              <th class="py-2.5 px-3">Ruangan & Jam</th>
              <th class="py-2.5 px-3">Link Zoom</th>
              <th class="py-2.5 px-3 text-end">Aksi BAAK</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($allUjian->where('status_dokumen', 'berkas_lengkap') as $item)
              @php
                $needsBaakSetup = ! $item->tanggal_ujian || ! $item->dosen_penguji_1_id || ! $item->dosen_penguji_2_id || ! $item->ruangan;
              @endphp
              <tr class="{{ $needsBaakSetup ? 'table-warning' : '' }}">
                <td class="px-3 py-3">
                  @if ($item->tugasAkhir->anggota && $item->tugasAkhir->anggota->isNotEmpty())
                    <div class="d-flex flex-column gap-1">
                      <div class="d-flex flex-wrap gap-1">
                        @foreach ($item->tugasAkhir->anggota as $member)
                          <span class="badge bg-light text-dark border">
                            <span class="fw-semibold">{{ $member->user->name ?? '-' }}</span>
                            <span class="text-secondary">({{ $member->nim }})</span>
                          </span>
                        @endforeach
                      </div>
                      <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle align-self-start">{{ $item->jenis_ujian_label }}</span>
                    </div>
                  @else
                    <p class="fw-bold text-dark mb-0">{{ $item->tugasAkhir->mahasiswa->user->name ?? '-' }}</p>
                    <p class="text-secondary small mb-0">{{ $item->tugasAkhir->mahasiswa->nim ?? '-' }}</p>
                    <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle mt-1">{{ $item->jenis_ujian_label }}</span>
                  @endif
                </td>
                <td class="px-3 py-3 text-nowrap">
                  @if ($item->tanggal_ujian)
                    <span class="fw-bold text-dark">{{ $item->tanggal_ujian->format('d M Y') }}</span>
                  @else
                    <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle">Belum Ditetapkan</span>
                  @endif
                </td>
                <td class="px-3 py-3 text-nowrap">
                  @if ($item->penguji1 && $item->penguji2)
                    <div class="small">
                      <div><strong class="text-secondary">P1:</strong> <span class="text-dark">{{ $item->penguji1->user->name }}</span></div>
                      <div><strong class="text-secondary">P2:</strong> <span class="text-dark">{{ $item->penguji2->user->name }}</span></div>
                    </div>
                  @else
                    <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle">Wajib 2 Penguji</span>
                  @endif
                </td>
                <td class="px-3 py-3 text-nowrap">
                  @if ($item->ruangan)
                    <p class="fw-semibold text-dark mb-0">{{ $item->ruangan }}</p>
                    <p class="text-secondary small mb-0">{{ $item->jam_mulai ? substr($item->jam_mulai, 0, 5) . ' - ' . substr($item->jam_selesai, 0, 5) . ' WIB' : '-' }}</p>
                  @else
                    <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle">Siap Dijadwalkan</span>
                  @endif
                </td>
                <td class="px-3 py-3 text-nowrap">
                  @if ($item->link_zoom)
                    <a href="{{ $item->link_zoom }}" target="_blank" class="small text-primary text-decoration-none fw-semibold">
                      <i class="bi bi-camera-video me-1"></i>Buka Zoom
                    </a>
                  @else
                    <span class="text-secondary small">-</span>
                  @endif
                </td>
                <td class="px-3 py-3 text-end text-nowrap">
                  @if (auth()->user()->isBaak())
                    <button @click="openSchedule({{ $item->toJson() }})" class="btn btn-dark btn-sm">
                      {{ $needsBaakSetup ? 'Atur Jadwal & Penguji' : 'Ubah Fasilitas' }}
                    </button>
                  @else
                    <span class="text-secondary small fst-italic">Khusus BAAK</span>
                  @endif
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="6" class="py-4 text-center text-secondary">Belum ada berkas berstatus lengkap yang siap dialokasikan oleh BAAK.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- ================================================================= -->
  <!-- TAB 4: TEACHING ASSISTANT / PANTAUAN STAF (READ-ONLY) -->
  <!-- ================================================================= -->
  <div x-show="activeTab === 'ta'" class="d-flex flex-column gap-3">
    <div class="card shadow-sm border-light-subtle">
      <div class="card-header bg-white py-3">
        <h2 class="h6 fw-bold text-dark mb-0">Pantauan Administrasi & Jadwal Ujian TA (Monitoring)</h2>
        <p class="text-secondary small mb-0">Pemantauan menyeluruh alur pendaftaran ujian seluruh mahasiswa FITE (Read-only).</p>
      </div>

      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0" style="font-size: 0.8125rem;">
          <thead class="table-light">
            <tr>
              <th class="py-2.5 px-3">Mahasiswa & NIM</th>
              <th class="py-2.5 px-3">Jenis Ujian</th>
              <th class="py-2.5 px-3">Status Berkas</th>
              <th class="py-2.5 px-3">Tanggal Pelaksanaan</th>
              <th class="py-2.5 px-3">Ruangan Ujian</th>
              <th class="py-2.5 px-3 text-end">Status Ujian</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($allUjian as $item)
              <tr>
                <td class="px-3 py-3">
                  @if ($item->tugasAkhir->anggota && $item->tugasAkhir->anggota->isNotEmpty())
                    <div class="d-flex flex-column gap-1">
                      <div class="d-flex flex-wrap gap-1">
                        @foreach ($item->tugasAkhir->anggota as $member)
                          <span class="badge bg-light text-dark border">
                            <span class="fw-semibold">{{ $member->user->name ?? '-' }}</span>
                            <span class="text-secondary">({{ $member->nim }})</span>
                          </span>
                        @endforeach
                      </div>
                      <span class="text-secondary small">{{ $item->tugasAkhir->prodi ?? '-' }} · {{ $item->tugasAkhir->jumlahAnggota() }} Mahasiswa</span>
                    </div>
                  @else
                    <p class="fw-bold text-dark mb-0">{{ $item->tugasAkhir->mahasiswa->user->name ?? '-' }}</p>
                    <p class="text-secondary small mb-0">{{ $item->tugasAkhir->mahasiswa->nim ?? '-' }}</p>
                  @endif
                </td>
                <td class="px-3 py-3 text-nowrap">
                  <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle">{{ $item->jenis_ujian_label }}</span>
                </td>
                <td class="px-3 py-3 text-nowrap">
                  @if ($item->status_dokumen === 'berkas_lengkap')
                    <span class="badge bg-success-subtle text-success border border-success-subtle">Lengkap & Valid</span>
                  @elseif ($item->status_dokumen === 'berkas_kurang')
                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle">Kurang Lengkap</span>
                  @else
                    <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle">Menunggu Verifikasi</span>
                  @endif
                </td>
                <td class="px-3 py-3 text-nowrap">
                  {{ $item->tanggal_ujian ? $item->tanggal_ujian->format('d M Y') : '-' }}
                </td>
                <td class="px-3 py-3 text-nowrap">
                  {{ $item->ruangan ?? '-' }}
                </td>
                <td class="px-3 py-3 text-end text-nowrap">
                  <span class="badge bg-light text-dark border text-capitalize">{{ $item->status_ujian }}</span>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="6" class="py-4 text-center text-secondary">Belum ada data pendaftaran ujian.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
  @endif

  @if (auth()->user()->isKoordinator())
  <!-- MODAL: VERIFIKASI DOKUMEN (KOORDINATOR TA) -->
  <div x-show="verifyModalOpen" x-cloak class="modal-backdrop-custom">
    <div class="modal-dialog modal-dialog-centered w-100 mx-3" style="max-width: 480px;" @click.away="verifyModalOpen = false">
      <div class="modal-content bg-white shadow-lg border-0 rounded-3">
        
        <div class="modal-header border-bottom py-3 px-4">
          <div>
            <h5 class="modal-title fs-6 fw-bold text-dark mb-0">Verifikasi Berkas Ujian (Koordinator TA)</h5>
            <p class="text-secondary small mb-0">Wewenang Koordinator TA menyatakan kelengkapan berkas</p>
          </div>
          <button type="button" class="btn-close" @click="verifyModalOpen = false"></button>
        </div>

        <template x-if="selectedUjian">
          <form :action="'/ujian/' + selectedUjian.id + '/verifikasi'" method="POST">
            @csrf
            @method('PATCH')

            <div class="modal-body p-4 d-flex flex-column gap-3" style="font-size: 0.8125rem;">
              <div>
                <label class="form-label fw-semibold text-secondary small mb-2">Keputusan Kelengkapan Berkas</label>
                <div class="d-flex gap-2">
                  <label class="btn btn-outline-success btn-sm flex-fill d-flex align-items-center justify-content-center gap-2 py-2 cursor-pointer">
                    <input type="radio" name="status_dokumen" value="berkas_lengkap" checked class="form-check-input mt-0">
                    <span class="fw-semibold">Lengkap & Valid</span>
                  </label>
                  <label class="btn btn-outline-danger btn-sm flex-fill d-flex align-items-center justify-content-center gap-2 py-2 cursor-pointer">
                    <input type="radio" name="status_dokumen" value="berkas_kurang" class="form-check-input mt-0">
                    <span class="fw-semibold">Kurang Lengkap</span>
                  </label>
                </div>
              </div>

              <div>
                <label class="form-label fw-semibold text-secondary small mb-1">Catatan Tambahan Koordinator TA</label>
                <textarea name="catatan_verifikasi_ta" rows="3" placeholder="Semua berkas fisik dan digital telah diperiksa lengkap & valid..." class="form-control form-control-sm"></textarea>
              </div>
            </div>

            <div class="modal-footer border-top py-2 px-4 d-flex justify-content-end gap-2">
              <button type="button" @click="verifyModalOpen = false" class="btn btn-outline-secondary btn-sm">Batal</button>
              <button type="submit" class="btn btn-dark btn-sm">Simpan Verifikasi</button>
            </div>
          </form>
        </template>

      </div>
    </div>
  </div>
  @endif

  @if (auth()->user()->isBaak())
  <!-- MODAL: ATUR TANGGAL, 2 PENGUJI & FASILITAS (BAAK) -->
  <div x-show="scheduleModalOpen" x-cloak class="modal-backdrop-custom">
    <div class="modal-dialog modal-dialog-centered w-100 mx-3" style="max-width: 540px;" @click.away="scheduleModalOpen = false">
      <div class="modal-content bg-white shadow-lg border-0 rounded-3">
        
        <div class="modal-header border-bottom py-3 px-4">
          <div>
            <h5 class="modal-title fs-6 fw-bold text-dark mb-0">Penetapan Jadwal, Penguji & Fasilitas (BAAK IT Del)</h5>
            <p class="text-secondary small mb-0">BAAK menetapkan tanggal pelaksanaan, 2 dosen penguji berbeda, ruang sidang, dan Zoom</p>
          </div>
          <button type="button" class="btn-close" @click="scheduleModalOpen = false"></button>
        </div>

        <template x-if="selectedUjian">
          <form :action="'/ujian/' + selectedUjian.id + '/jadwal'" method="POST">
            @csrf
            @method('PATCH')

            <div class="modal-body p-4 d-flex flex-column gap-3" style="font-size: 0.8125rem;">
              <div>
                <label class="form-label fw-semibold text-secondary small mb-1">Tanggal Pelaksanaan Ujian <span class="text-danger">*</span></label>
                <input type="date" name="tanggal_ujian" :value="selectedUjian?.tanggal_ujian ? selectedUjian.tanggal_ujian.substring(0, 10) : ''" required class="form-control form-control-sm">
              </div>

              <div class="row g-2">
                <div class="col-6">
                  <label class="form-label fw-semibold text-secondary small mb-1">Dosen Penguji 1 <span class="text-danger">*</span></label>
                  <select name="dosen_penguji_1_id" required class="form-select form-select-sm">
                    <option value="" disabled :selected="!selectedUjian?.dosen_penguji_1_id">-- Pilih Dosen Penguji 1 --</option>
                    @foreach (($dosenList ?? []) as $d)
                      <option value="{{ $d->id }}" :selected="selectedUjian?.dosen_penguji_1_id == {{ $d->id }}">{{ $d->user->name }} ({{ $d->kode_dosen ?? $d->nidn }})</option>
                    @endforeach
                  </select>
                </div>

                <div class="col-6">
                  <label class="form-label fw-semibold text-secondary small mb-1">Dosen Penguji 2 <span class="text-danger">* (Berbeda)</span></label>
                  <select name="dosen_penguji_2_id" required class="form-select form-select-sm">
                    <option value="" disabled :selected="!selectedUjian?.dosen_penguji_2_id">-- Pilih Dosen Penguji 2 --</option>
                    @foreach (($dosenList ?? []) as $d)
                      <option value="{{ $d->id }}" :selected="selectedUjian?.dosen_penguji_2_id == {{ $d->id }}">{{ $d->user->name }} ({{ $d->kode_dosen ?? $d->nidn }})</option>
                    @endforeach
                  </select>
                </div>
              </div>

              <div>
                <label class="form-label fw-semibold text-secondary small mb-1">Ruangan Ujian / Sidang FITE <span class="text-danger">*</span></label>
                <select name="ruangan" required class="form-select form-select-sm">
                  <option value="Ruang Sidang FITE GD 514" :selected="selectedUjian?.ruangan === 'Ruang Sidang FITE GD 514'">Ruang Sidang FITE GD 514</option>
                  <option value="Lab Komputer GD 512" :selected="selectedUjian?.ruangan === 'Lab Komputer GD 512'">Lab Komputer GD 512</option>
                  <option value="Ruang Rapat FITE GD 510" :selected="selectedUjian?.ruangan === 'Ruang Rapat FITE GD 510'">Ruang Rapat FITE GD 510</option>
                </select>
              </div>

              <div class="row g-2">
                <div class="col-6">
                  <label class="form-label fw-semibold text-secondary small mb-1">Jam Mulai <span class="text-danger">*</span></label>
                  <input type="time" name="jam_mulai" :value="selectedUjian?.jam_mulai ? selectedUjian.jam_mulai.substring(0, 5) : '09:00'" required class="form-control form-control-sm">
                </div>
                <div class="col-6">
                  <label class="form-label fw-semibold text-secondary small mb-1">Jam Selesai <span class="text-danger">*</span></label>
                  <input type="time" name="jam_selesai" :value="selectedUjian?.jam_selesai ? selectedUjian.jam_selesai.substring(0, 5) : '11:00'" required class="form-control form-control-sm">
                </div>
              </div>

              <div>
                <label class="form-label fw-semibold text-secondary small mb-1">Link Zoom Meeting (Daring / Hybrid)</label>
                <input type="url" name="link_zoom" :value="selectedUjian?.link_zoom ?? 'https://zoom.us/j/itdel-sidang-fite1'" class="form-control form-control-sm">
              </div>
            </div>

            <div class="modal-footer border-top py-2 px-4 d-flex justify-content-end gap-2">
              <button type="button" @click="scheduleModalOpen = false" class="btn btn-outline-secondary btn-sm">Batal</button>
              <button type="submit" class="btn btn-dark btn-sm">Simpan Jadwal & Fasilitas</button>
            </div>
          </form>
        </template>

      </div>
    </div>
  </div>
  @endif

</div>
@endsection

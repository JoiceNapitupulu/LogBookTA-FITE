@extends('layouts.notion')

@section('title', 'Dashboard Utama')
@section('breadcrumb', 'Dashboard')

@section('content')
<div class="d-flex flex-column gap-4">

  <!-- Header Halaman Akademik -->
  <div class="pb-3 mb-1 border-bottom">
    <h1 class="h3 fw-bold text-dark mb-1">
      Workspace Tugas Akhir FITE
    </h1>
    <p class="text-secondary small mb-0">
      Logbook, bimbingan, dan ujian tugas akhir FITE · Institut Teknologi Del
    </p>
  </div>

  <!-- ================================================================= -->
  <!-- KONDISI 1: MAHASISWA NON-TA -->
  <!-- ================================================================= -->
  @if ($user->isMahasiswa() && (!$mahasiswaProfile || !$mahasiswaProfile->is_ta))
    <div class="alert alert-primary d-flex align-items-start gap-3 shadow-sm border-primary-subtle" role="alert">
      <i class="bi bi-info-circle-fill fs-5 text-primary shrink-0 mt-0.5"></i>
      <div>
        <h2 class="h6 fw-bold mb-1">Status: Mahasiswa Non-TA (Tahap Persiapan Tugas Akhir)</h2>
        <p class="small text-secondary mb-3 lh-base">
          Akun Anda tercatat sebagai mahasiswa aktif <strong class="text-dark">{{ $mahasiswaProfile->prodi ?? 'FITE' }} (NIM: {{ $mahasiswaProfile->nim ?? '-' }})</strong> namun belum mengambil mata kuliah Tugas Akhir pada semester ini. Anda memiliki akses penuh untuk menelusuri katalog repositori naskah skripsi alumni FITE sebagai referensi topik penelitian Anda.
        </p>
        <a href="{{ route('arsip.index') }}" class="btn btn-primary btn-sm d-inline-flex align-items-center gap-1.5">
          <i class="bi bi-archive"></i>
          <span>Buka Pustaka Arsip Tugas Akhir</span>
        </a>
      </div>
    </div>

    <!-- Arsip Terpopuler Preview -->
    <div class="card shadow-sm border-light-subtle">
      <div class="card-header bg-white py-3">
        <h2 class="h6 fw-bold text-dark mb-1">Rekomendasi Naskah Tugas Akhir FITE Terdahulu</h2>
        <p class="text-secondary small mb-0">Gunakan naskah alumni berikut sebagai rujukan metodologi, format penulisan, dan instrumen pengujian.</p>
      </div>
      <div class="card-body">
        <div class="row g-3">
          <div class="col-12 col-md-4">
            <div class="p-3 rounded bg-light border h-100 d-flex flex-column justify-content-between">
              <div>
                <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle mb-2">S1 Informatika · 2025</span>
                <h3 class="h6 fw-bold text-dark mb-1">Sistem Pemantauan Kualitas Air Danau Toba Berbasis IoT</h3>
                <p class="text-secondary small mb-0">Penulis: Grace Hutapea</p>
              </div>
              <div class="mt-3">
                <a href="{{ route('arsip.index') }}" class="small fw-semibold text-primary text-decoration-none d-inline-flex align-items-center gap-1">
                  <span>Detail Naskah</span>
                  <i class="bi bi-arrow-right"></i>
                </a>
              </div>
            </div>
          </div>

          <div class="col-12 col-md-4">
            <div class="p-3 rounded bg-light border h-100 d-flex flex-column justify-content-between">
              <div>
                <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle mb-2">S1 Sistem Informasi · 2024</span>
                <h3 class="h6 fw-bold text-dark mb-1">Microservices Architecture Platform E-Commerce Pertanian Toba</h3>
                <p class="text-secondary small mb-0">Penulis: Bona Manurung</p>
              </div>
              <div class="mt-3">
                <a href="{{ route('arsip.index') }}" class="small fw-semibold text-primary text-decoration-none d-inline-flex align-items-center gap-1">
                  <span>Detail Naskah</span>
                  <i class="bi bi-arrow-right"></i>
                </a>
              </div>
            </div>
          </div>

          <div class="col-12 col-md-4">
            <div class="p-3 rounded bg-light border h-100 d-flex flex-column justify-content-between">
              <div>
                <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle mb-2">S1 Teknik Elektro · 2024</span>
                <h3 class="h6 fw-bold text-dark mb-1">Node Sensor Nirkabel LoRaWAN Deteksi Longsor Sigumpar</h3>
                <p class="text-secondary small mb-0">Penulis: Tulus Simanjuntak</p>
              </div>
              <div class="mt-3">
                <a href="{{ route('arsip.index') }}" class="small fw-semibold text-primary text-decoration-none d-inline-flex align-items-center gap-1">
                  <span>Detail Naskah</span>
                  <i class="bi bi-arrow-right"></i>
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  @endif

  <!-- ================================================================= -->
  <!-- KONDISI 2: MAHASISWA TA AKTIF -->
  <!-- ================================================================= -->
  @if ($user->isMahasiswa() && $mahasiswaProfile && $mahasiswaProfile->is_ta)
    
    <!-- Header Card Judul TA -->
    @if ($tugasAkhir)
      <div class="card shadow-sm border-light-subtle mb-4">
        <div class="card-body p-3 p-md-4">
          <div class="d-flex flex-column flex-md-row align-items-md-start justify-content-between gap-3">
            <div>
              <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
                <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle">{{ $tugasAkhir->prodi }}</span>
                <span class="badge bg-success-subtle text-success border border-success-subtle">TA Aktif · TA {{ $tugasAkhir->tahun_ajaran }}</span>
                <span class="small text-secondary">NIM: {{ $mahasiswaProfile->nim }}</span>
              </div>
              <h2 class="h5 fw-bold text-dark lh-sm mb-2">
                {{ $tugasAkhir->judul }}
              </h2>
              <div class="d-flex flex-wrap align-items-center gap-3 text-secondary small mb-3">
                <div>Pembimbing 1: <strong class="text-dark">{{ $tugasAkhir->pembimbing1->user->name ?? '-' }}</strong></div>
                <span>·</span>
                <div>Pembimbing 2: <strong class="text-dark">{{ $tugasAkhir->pembimbing2->user->name ?? '-' }}</strong></div>
                <span>·</span>
                <div>Total Logbook: <span class="badge bg-light text-dark border">{{ $logbooks->count() }} Sesi Terdata</span></div>
              </div>

              @php
                $members = $tugasAkhir->anggota && $tugasAkhir->anggota->isNotEmpty()
                  ? $tugasAkhir->anggota
                  : ($tugasAkhir->mahasiswa ? collect([$tugasAkhir->mahasiswa]) : collect());
                $countMhs = $members->count();
              @endphp
              @if ($countMhs > 0)
                <div class="pt-3 border-top">
                  <div class="d-flex align-items-center gap-2 mb-2">
                    <span class="small fw-semibold text-secondary">
                      Kelompok Tugas Akhir · {{ $countMhs }} Mahasiswa
                    </span>
                  </div>
                  <div class="d-flex flex-wrap align-items-center gap-2">
                    @foreach ($members as $member)
                      @php
                        $isCurrentUser = auth()->check() && (auth()->id() === $member->user_id);
                      @endphp
                      <div class="badge {{ $isCurrentUser ? 'bg-primary-subtle text-primary border border-primary-subtle' : 'bg-light text-dark border' }} py-1.5 px-2.5 d-inline-flex align-items-center gap-1.5 fw-normal" style="font-size: 0.8125rem;">
                        <i class="bi bi-person-fill text-secondary"></i>
                        <span class="fw-semibold">{{ $member->user->name ?? '-' }}</span>
                        <span class="text-secondary small">({{ $member->nim }})</span>
                        @if ($isCurrentUser)
                          <span class="badge bg-primary text-white ms-1" style="font-size: 0.65rem;">Anda</span>
                        @endif
                      </div>
                    @endforeach
                  </div>
                </div>
              @endif
            </div>

            <div class="d-flex align-items-center gap-2 shrink-0">
              <a href="{{ route('bimbingan.index') }}" class="btn btn-outline-dark btn-sm d-inline-flex align-items-center gap-1.5">
                <i class="bi bi-calendar-check"></i> Ajukan Bimbingan
              </a>
              <a href="{{ route('logbook.index') }}" class="btn btn-dark btn-sm d-inline-flex align-items-center gap-1.5">
                <i class="bi bi-journal-text"></i> Isi Logbook
              </a>
            </div>
          </div>
        </div>
      </div>

      <!-- Stepper Horizontal Pencapaian BAB TA -->
      <div class="card shadow-sm border-light-subtle mb-4">
        <div class="card-body p-3 p-md-4">
          <div class="d-flex align-items-center justify-content-between mb-3">
            <div>
              <h3 class="h6 fw-bold text-dark mb-0">Tahapan Capaian Bab Tugas Akhir</h3>
              <p class="text-secondary small mb-0">Pelacakan progres tahapan naskah dari Proposal hingga Sidang Akhir.</p>
            </div>
            <span class="badge bg-light text-dark border py-1.5 px-2.5">
              Status: {{ strtoupper($tugasAkhir->status) }}
            </span>
          </div>

          <div class="row g-2 text-center" style="font-size: 0.75rem;">
            @php
              $steps = [
                [
                  'step' => 'Tahap 1',
                  'name' => 'Proposal',
                  'status' => in_array($tugasAkhir->status, ['sempro', 'seminar_ta', 'sidang_akhir', 'selesai']) ? 'approved' : ($tugasAkhir->status === 'proposal' ? 'in_progress' : 'empty'),
                  'label' => in_array($tugasAkhir->status, ['sempro', 'seminar_ta', 'sidang_akhir', 'selesai']) ? 'Lulus Sempro' : ($tugasAkhir->status === 'proposal' ? 'Pengajuan' : 'Belum Mulai'),
                ],
              ];

              foreach (['BAB 1', 'BAB 2', 'BAB 3', 'BAB 4', 'BAB 5'] as $idx => $bName) {
                $logsForBab = $logbooks->where('bab_progress', $bName);
                if ($bName === 'BAB 5') {
                  $logsForBab = $logbooks->filter(fn($l) => in_array($l->bab_progress, ['BAB 5', 'Pengujian']));
                }

                $isApproved = $logsForBab->where('status_verifikasi', 'disetujui')->isNotEmpty();
                $isRevisi = $logsForBab->where('status_verifikasi', 'revisi')->isNotEmpty();
                $isPending = $logsForBab->where('status_verifikasi', 'pending')->isNotEmpty();

                if ($isApproved) {
                  $sStatus = 'approved';
                  $sLabel = 'Disetujui';
                } elseif ($isRevisi) {
                  $sStatus = 'revision';
                  $sLabel = 'Perlu Revisi';
                } elseif ($isPending) {
                  $sStatus = 'pending';
                  $sLabel = 'Menunggu Evaluasi';
                } else {
                  $sStatus = 'empty';
                  $sLabel = 'Belum Ada Entri';
                }

                $steps[] = [
                  'step' => 'Tahap ' . ($idx + 2),
                  'name' => $bName,
                  'status' => $sStatus,
                  'label' => $sLabel,
                ];
              }

              // Final: Sidang Akhir
              $sidangStatus = 'empty';
              $sidangLabel = 'Terkunci';
              if ($tugasAkhir->status === 'selesai') {
                $sidangStatus = 'approved';
                $sidangLabel = 'Lulus Sidang';
              } elseif ($tugasAkhir->status === 'sidang_akhir') {
                $sidangStatus = 'pending';
                $sidangLabel = 'Terdaftar Sidang';
              } elseif ($tugasAkhir->status === 'seminar_ta') {
                $sidangStatus = 'in_progress';
                $sidangLabel = 'Tahap Seminar TA';
              }
              $steps[] = [
                'step' => 'Final',
                'name' => 'Sidang Akhir',
                'status' => $sidangStatus,
                'label' => $sidangLabel,
              ];
            @endphp

            @foreach ($steps as $st)
              <div class="col-6 col-sm-4 col-md">
                @if ($st['status'] === 'approved')
                  <div class="p-2.5 rounded-3 border border-success-subtle bg-success-subtle/40 h-100 d-flex flex-column justify-content-between">
                    <span class="text-success fw-bold text-uppercase d-block" style="font-size: 0.65rem;">{{ $st['step'] }}</span>
                    <p class="fw-bold text-dark my-1">{{ $st['name'] }}</p>
                    <span class="text-success small fw-medium"><i class="bi bi-check-circle-fill me-0.5"></i> {{ $st['label'] }}</span>
                  </div>
                @elseif ($st['status'] === 'revision')
                  <div class="p-2.5 rounded-3 border border-warning-subtle bg-warning-subtle/50 h-100 d-flex flex-column justify-content-between">
                    <span class="text-warning-emphasis fw-bold text-uppercase d-block" style="font-size: 0.65rem;">{{ $st['step'] }}</span>
                    <p class="fw-bold text-dark my-1">{{ $st['name'] }}</p>
                    <span class="text-warning-emphasis small fw-medium"><i class="bi bi-exclamation-triangle-fill me-0.5"></i> {{ $st['label'] }}</span>
                  </div>
                @elseif ($st['status'] === 'pending' || $st['status'] === 'in_progress')
                  <div class="p-2.5 rounded-3 border border-primary-subtle bg-primary-subtle/40 h-100 d-flex flex-column justify-content-between">
                    <span class="text-primary fw-bold text-uppercase d-block" style="font-size: 0.65rem;">{{ $st['step'] }}</span>
                    <p class="fw-bold text-dark my-1">{{ $st['name'] }}</p>
                    <span class="text-primary small fw-medium"><i class="bi bi-clock-history me-0.5"></i> {{ $st['label'] }}</span>
                  </div>
                @else
                  <div class="p-2.5 rounded-3 border border-light-subtle bg-light text-secondary h-100 d-flex flex-column justify-content-between opacity-75">
                    <span class="text-secondary fw-medium text-uppercase d-block" style="font-size: 0.65rem;">{{ $st['step'] }}</span>
                    <p class="fw-medium text-secondary my-1">{{ $st['name'] }}</p>
                    <span class="text-secondary small"><i class="bi bi-dash-circle me-0.5"></i> {{ $st['label'] }}</span>
                  </div>
                @endif
              </div>
            @endforeach
          </div>
        </div>
      </div>

      <!-- Ringkasan Logbook Terkini -->
      <div class="card shadow-sm border-light-subtle mb-4">
        <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between">
          <div>
            <h3 class="h6 fw-bold text-dark mb-0">Catatan Logbook Terakhir</h3>
            <p class="text-secondary small mb-0">Aktivitas riset dan catatan pembimbing</p>
          </div>
          <a href="{{ route('logbook.index') }}" class="small fw-semibold text-primary text-decoration-none d-inline-flex align-items-center gap-1">
            <span>Buka Halaman Logbook</span>
            <i class="bi bi-arrow-right"></i>
          </a>
        </div>

        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0" style="font-size: 0.8125rem;">
            <thead class="table-light">
              <tr>
                <th class="py-2.5 px-3">Tanggal / Bab</th>
                <th class="py-2.5 px-3">Aktivitas Pengerjaan</th>
                <th class="py-2.5 px-3">Kendala Teknis</th>
                <th class="py-2.5 px-3">Catatan Dosen</th>
                <th class="py-2.5 px-3">Status</th>
              </tr>
            </thead>
            <tbody>
              @forelse ($logbooks->take(3) as $log)
                <tr>
                  <td class="px-3 py-3 text-nowrap">
                    <span class="fw-bold text-dark d-block">{{ $log->tanggal->format('d M Y') }}</span>
                    <span class="badge bg-light text-secondary border mt-0.5">{{ $log->bab_progress }}</span>
                  </td>
                  <td class="px-3 py-3" style="max-width: 280px;">
                    <p class="fw-medium text-dark mb-0 line-clamp-2">{{ $log->deskripsi_kegiatan }}</p>
                  </td>
                  <td class="px-3 py-3 text-secondary" style="max-width: 240px;">
                    {{ $log->kendala ?? 'Tidak ada kendala' }}
                  </td>
                  <td class="px-3 py-3 text-secondary" style="max-width: 240px;">
                    {{ $log->catatan_dosen ?? 'Belum ada catatan' }}
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
                </tr>
              @empty
                <tr>
                  <td colspan="5" class="py-4 text-center text-secondary">
                    Belum ada catatan logbook yang terdata. Klik "Isi Logbook" untuk memulai.
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    @else
      <div class="alert alert-warning d-flex align-items-center gap-2 mb-4" role="alert">
        <i class="bi bi-exclamation-triangle-fill"></i>
        <div>
          <strong class="d-block">Tugas Akhir Belum Terdaftar</strong>
          <span class="small">Akun Anda berstatus Mahasiswa Tugas Akhir aktif, namun data judul dan penunjukan dosen pembimbing belum ditetapkan oleh Koordinator TA.</span>
        </div>
      </div>
    @endif

  @endif

  <!-- ================================================================= -->
  <!-- KONDISI 3: DOSEN PEMBIMBING & KAPRODI -->
  <!-- ================================================================= -->
  @if ($user->isDosen() || $user->isKaprodi())
    
    <!-- Quick Stats Cards Dosen & Kaprodi -->
    <div class="row g-3 mb-4">
      <div class="col-6 col-md-3">
        <div class="card shadow-sm border-light-subtle h-100 p-3 bg-white">
          <div class="d-flex align-items-center justify-content-between text-secondary mb-1">
            <span class="small">Mahasiswa Bimbingan</span>
            <i class="bi bi-people text-secondary"></i>
          </div>
          <p class="h4 fw-bold text-dark mt-1 mb-0">{{ $mahasiswaBimbingan->count() }}</p>
          <span class="text-secondary small">FITE IT Del</span>
        </div>
      </div>

      <div class="col-6 col-md-3">
        <div class="card shadow-sm border-warning-subtle h-100 p-3 bg-warning-subtle/30">
          <div class="d-flex align-items-center justify-content-between text-warning-emphasis mb-1">
            <span class="small">Menunggu Evaluasi</span>
            <i class="bi bi-hourglass-split"></i>
          </div>
          <p class="h4 fw-bold text-warning-emphasis mt-1 mb-0">{{ $pendingReviewsCount }}</p>
          <span class="text-warning-emphasis small">Entri logbook baru</span>
        </div>
      </div>

      <div class="col-6 col-md-3">
        <div class="card shadow-sm border-success-subtle h-100 p-3 bg-success-subtle/30">
          <div class="d-flex align-items-center justify-content-between text-success-emphasis mb-1">
            <span class="small">Pertemuan Minggu Ini</span>
            <i class="bi bi-calendar-check"></i>
          </div>
          <p class="h4 fw-bold text-success mt-1 mb-0">{{ $upcomingMeetingsCount }}</p>
          <span class="text-success small">Asistensi terjadwal</span>
        </div>
      </div>

      <div class="col-6 col-md-3">
        <div class="card shadow-sm border-light-subtle h-100 p-3 bg-white">
          <div class="d-flex align-items-center justify-content-between text-secondary mb-1">
            <span class="small">Total TA FITE Aktif</span>
            <i class="bi bi-folder2-open text-secondary"></i>
          </div>
          <p class="h4 fw-bold text-dark mt-1 mb-0">{{ $totalTaAktif }}</p>
          <span class="text-secondary small">3 Program Studi</span>
        </div>
      </div>
    </div>

    @if ($user->isKoordinator())
      <!-- Panel Ringkas Koordinator TA -->
      <div class="card shadow-sm border-light-subtle mb-4">
        <div class="card-header bg-white py-3 d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-2">
          <div class="d-flex align-items-center gap-2">
            <div class="w-8 h-8 rounded-circle bg-dark text-white d-flex align-items-center justify-content-center shrink-0">
              <i class="bi bi-diagram-3"></i>
            </div>
            <div>
              <h3 class="h6 fw-bold text-dark mb-0">Ringkasan Operasional Koordinator TA</h3>
              <p class="text-secondary small mb-0">Penetapan pembimbing dan penjadwalan ujian program studi FITE</p>
            </div>
          </div>
          <a href="{{ route('koordinator.index') }}" class="btn btn-outline-dark btn-sm d-inline-flex align-items-center gap-1">
            <span>Buka Panel Koordinator</span>
            <i class="bi bi-arrow-right"></i>
          </a>
        </div>

        <div class="card-body p-3 p-md-4">
          <div class="row g-3">
            <div class="col-12 col-md-6">
              <div class="card h-100 border border-light-subtle bg-light shadow-xs d-flex flex-column">
                <div class="card-body d-flex flex-column flex-grow-1 p-3">
                  <span class="text-uppercase text-muted small fw-semibold d-block mb-1">Menunggu Penunjukan Pembimbing</span>
                  <div class="d-flex align-items-baseline gap-1 mb-2">
                    <span class="h3 fw-bold text-dark mb-0">{{ $menungguAssignCount }}</span>
                    <span class="text-muted small">Mahasiswa</span>
                  </div>
                  <div class="mt-auto pt-3">
                    <a href="{{ route('koordinator.index') }}" class="btn btn-dark btn-sm d-inline-flex align-items-center justify-content-center gap-1.5 w-100">
                      <span>Plot Pembimbing</span>
                      <i class="bi bi-arrow-right"></i>
                    </a>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-12 col-md-6">
              <div class="card h-100 border border-light-subtle bg-light shadow-xs d-flex flex-column">
                <div class="card-body d-flex flex-column flex-grow-1 p-3">
                  <span class="text-uppercase text-muted small fw-semibold d-block mb-1">Ujian Siap Dijadwalkan (Berkas Lengkap)</span>
                  <div class="d-flex align-items-baseline gap-1 mb-2">
                    <span class="h3 fw-bold text-success mb-0">{{ $unassignedExamsCount }}</span>
                    <span class="text-muted small">Pengajuan</span>
                  </div>
                  <div class="mt-auto pt-3">
                    <a href="{{ route('ujian.index') }}" class="btn btn-primary btn-sm d-inline-flex align-items-center justify-content-center gap-1.5 w-100">
                      <span>Tetapkan Jadwal</span>
                      <i class="bi bi-arrow-right"></i>
                    </a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    @endif

    @if ($user->isKaprodi())
      <!-- Panel Ringkas Kaprodi -->
      <div class="card shadow-sm border-light-subtle mb-4">
        <div class="card-header bg-white py-3 d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-2">
          <div class="d-flex align-items-center gap-2">
            <div class="w-8 h-8 rounded-circle bg-dark text-white d-flex align-items-center justify-content-center shrink-0">
              <i class="bi bi-bar-chart"></i>
            </div>
            <div>
              <h3 class="h6 fw-bold text-dark mb-0">Ringkasan Mutu & Statistik Kaprodi</h3>
              <p class="text-secondary small mb-0">Pengawasan mutu kelulusan tugas akhir dan beban bimbingan dosen FITE</p>
            </div>
          </div>
          <a href="{{ route('kaprodi.index') }}" class="btn btn-outline-dark btn-sm d-inline-flex align-items-center gap-1">
            <span>Buka Panel Kaprodi</span>
            <i class="bi bi-arrow-right"></i>
          </a>
        </div>

        <div class="card-body p-3 p-md-4">
          <div class="row g-3">
            <div class="col-12 col-md-6">
              <div class="card h-100 border border-light-subtle bg-light shadow-xs d-flex flex-column">
                <div class="card-body d-flex flex-column flex-grow-1 p-3">
                  <span class="text-uppercase text-muted small fw-semibold d-block mb-1">Total Tugas Akhir Berjalan</span>
                  <div class="d-flex align-items-baseline gap-1 mb-2">
                    <span class="h3 fw-bold text-dark mb-0">{{ $totalTaAktif }}</span>
                    <span class="text-muted small">Mahasiswa</span>
                  </div>
                  <div class="mt-auto pt-3">
                    <a href="{{ route('kaprodi.index') }}" class="btn btn-dark btn-sm d-inline-flex align-items-center justify-content-center gap-1.5 w-100">
                      <span>Pantau Mutu TA</span>
                      <i class="bi bi-arrow-right"></i>
                    </a>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-12 col-md-6">
              <div class="card h-100 border border-light-subtle bg-light shadow-xs d-flex flex-column">
                <div class="card-body d-flex flex-column flex-grow-1 p-3">
                  <span class="text-uppercase text-muted small fw-semibold d-block mb-1">Mahasiswa Siap Sidang</span>
                  <div class="d-flex align-items-baseline gap-1 mb-2">
                    <span class="h3 fw-bold text-success mb-0">{{ $totalMhsSiapSidang }}</span>
                    <span class="text-muted small">Mahasiswa</span>
                  </div>
                  <div class="mt-auto pt-3">
                    <a href="{{ route('kaprodi.index') }}" class="btn btn-primary btn-sm d-inline-flex align-items-center justify-content-center gap-1.5 w-100">
                      <span>Lihat Statistik</span>
                      <i class="bi bi-arrow-right"></i>
                    </a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    @endif

    <!-- Tabel Mahasiswa Bimbingan -->
    <div class="card shadow-sm border-light-subtle mb-4">
      <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between">
        <div>
          <h3 class="h6 fw-bold text-dark mb-0">Daftar Mahasiswa Bimbingan Anda</h3>
          <p class="text-secondary small mb-0">Kelola evaluasi draf naskah, revisi coretan, dan persetujuan bimbingan.</p>
        </div>
        <a href="{{ route('logbook.index') }}" class="small fw-semibold text-primary text-decoration-none d-inline-flex align-items-center gap-1">
          <span>Tinjau Semua Logbook</span>
          <i class="bi bi-arrow-right"></i>
        </a>
      </div>

      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0" style="font-size: 0.8125rem;">
          <thead class="table-light">
            <tr>
              <th class="py-2.5 px-3">Mahasiswa & NIM</th>
              <th class="py-2.5 px-3">Judul Tugas Akhir</th>
              <th class="py-2.5 px-3">Tahap Bab</th>
              <th class="py-2.5 px-3">Logbook Terakhir</th>
              <th class="py-2.5 px-3 text-end">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($mahasiswaBimbingan as $ta)
              <tr>
                <td class="px-3 py-3">
                  @php
                    $dosenMembers = $ta->anggota && $ta->anggota->isNotEmpty()
                      ? $ta->anggota
                      : ($ta->mahasiswa ? collect([$ta->mahasiswa]) : collect());
                  @endphp
                  @if ($dosenMembers->isNotEmpty())
                    <div class="d-flex flex-column gap-1">
                      <div class="d-flex flex-wrap gap-1.5">
                        @foreach ($dosenMembers as $member)
                          <span class="badge bg-light text-dark border">
                            <span class="fw-semibold">{{ $member->user->name ?? '-' }}</span>
                            <span class="text-secondary">({{ $member->nim }})</span>
                          </span>
                        @endforeach
                      </div>
                      <span class="text-secondary small">{{ $ta->prodi }} · {{ $dosenMembers->count() }} mahasiswa</span>
                    </div>
                  @else
                    <span class="fw-bold text-dark">-</span>
                    <span class="text-secondary small d-block">{{ $ta->prodi }}</span>
                  @endif
                </td>
                <td class="px-3 py-3" style="max-width: 300px;">
                  <p class="fw-medium text-dark mb-0 line-clamp-2">{{ $ta->judul }}</p>
                </td>
                <td class="px-3 py-3">
                  <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle text-uppercase">{{ $ta->status }}</span>
                </td>
                <td class="px-3 py-3 text-secondary">
                  @if ($ta->logbooks->isNotEmpty())
                    <span class="fw-medium text-dark d-block">{{ $ta->logbooks->first()->tanggal->format('d M Y') }}</span>
                    <span class="small">{{ $ta->logbooks->first()->bab_progress }}</span>
                  @else
                    <span class="small">Belum ada entri</span>
                  @endif
                </td>
                <td class="px-3 py-3 text-end">
                  <a href="{{ route('logbook.index') }}" class="btn btn-outline-primary btn-sm">
                    Evaluasi Dokumen
                  </a>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="5" class="py-4 text-center text-secondary">
                  Belum ada mahasiswa yang dialokasikan ke bimbingan Anda.
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

  @endif

  <!-- ================================================================= -->
  <!-- KONDISI 4: TEACHING ASSISTANT & BAAK -->
  <!-- ================================================================= -->
  @if ($user->isTA() || $user->isBaak())
    <div class="row g-3">
      <div class="col-12 col-md-4">
        <div class="card shadow-sm border-light-subtle h-100 p-3 bg-white">
          <div class="d-flex align-items-center justify-content-between text-secondary mb-2">
            <span class="small fw-bold text-uppercase">Antrean Verifikasi Berkas (TA)</span>
            <i class="bi bi-file-earmark-check fs-5"></i>
          </div>
          <p class="h3 fw-bold text-dark mb-1">{{ $pendingUjianCount }}</p>
          <p class="text-secondary small mb-3">Berkas ujian mahasiswa menunggu validasi fisik/soft-copy</p>
          <a href="{{ route('ujian.index') }}" class="btn btn-dark btn-sm d-inline-flex align-items-center gap-1 w-fit">
            <span>Periksa Berkas</span>
            <i class="bi bi-arrow-right"></i>
          </a>
        </div>
      </div>

      <div class="col-12 col-md-4">
        <div class="card shadow-sm border-light-subtle h-100 p-3 bg-white">
          <div class="d-flex align-items-center justify-content-between text-secondary mb-2">
            <span class="small fw-bold text-uppercase">Alokasi Ruangan Ujian (BAAK)</span>
            <i class="bi bi-building fs-5"></i>
          </div>
          <p class="h3 fw-bold text-dark mb-1">{{ $unscheduledBaakCount }}</p>
          <p class="text-secondary small mb-3">Jadwal tanggal terbit menanti alokasi ruang GD & link Zoom</p>
          <a href="{{ route('ujian.index') }}" class="btn btn-primary btn-sm d-inline-flex align-items-center gap-1 w-fit">
            <span>Kelola Fasilitas</span>
            <i class="bi bi-arrow-right"></i>
          </a>
        </div>
      </div>

      <div class="col-12 col-md-4">
        <div class="card shadow-sm border-light-subtle h-100 p-3 bg-white">
          <div class="d-flex align-items-center justify-content-between text-secondary mb-2">
            <span class="small fw-bold text-uppercase">Total Arsip Digital Alumni</span>
            <i class="bi bi-archive fs-5"></i>
          </div>
          <p class="h3 fw-bold text-dark mb-1">{{ $totalArsipCount }}</p>
          <p class="text-secondary small mb-3">Karya naskah skripsi terunggah di repositori FITE</p>
          <a href="{{ route('arsip.index') }}" class="btn btn-outline-dark btn-sm d-inline-flex align-items-center gap-1 w-fit">
            <span>Pustaka Naskah</span>
            <i class="bi bi-arrow-right"></i>
          </a>
        </div>
      </div>
    </div>
  @endif

</div>
@endsection

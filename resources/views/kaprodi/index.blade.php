@extends('layouts.notion')

@section('title', 'Panel Eksekutif Kaprodi')
@section('breadcrumb', 'Eksekutif Kaprodi')

@section('content')
<div class="d-flex flex-column gap-3">

  <!-- Header Halaman Akademik -->
  <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 pb-3 border-bottom">
    <div>
      <h1 class="h4 fw-bold text-dark mb-1">Panel Eksekutif & Statistik Kelulusan TA</h1>
      <p class="text-secondary small mb-0">Otoritas Ketua Program Studi FITE IT Del: Pengawasan mutu kelulusan dan penetapan Koordinator TA.</p>
    </div>
  </div>

  <!-- Callout Otoritas Kaprodi & Status 1 Koor per Prodi -->
  <div class="card shadow-sm border-light-subtle bg-white">
    <div class="card-body p-3 p-md-4">
      <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
        <div class="d-flex align-items-center gap-3">
          <div class="w-10 h-10 rounded-circle bg-dark text-white d-flex align-items-center justify-content-center shrink-0">
            <i class="bi bi-shield-check fs-5"></i>
          </div>
          <div>
            <div class="d-flex align-items-center gap-2 mb-1">
              <span class="fw-bold text-dark">Otoritas Program Studi Anda:</span>
              <span class="badge bg-dark text-white">{{ $kaprodiProdi }}</span>
            </div>
            <p class="text-secondary small mb-0">
              Sesuai regulasi akademik FITE: Kaprodi hanya dapat menetapkan Koordinator TA di prodinya sendiri, dan setiap prodi hanya memiliki tepat 1 Koordinator TA.
            </p>
          </div>
        </div>

        <div class="p-2.5 rounded bg-light border shrink-0">
          <span class="small text-secondary text-uppercase fw-semibold d-block" style="font-size: 0.7rem;">Koordinator TA Aktif {{ $kaprodiProdi }}</span>
          @if ($koordinatorProdiIni)
            <span class="fw-bold text-success d-inline-flex align-items-center gap-1 mt-0.5 small">
              <i class="bi bi-person-check-fill"></i> {{ $koordinatorProdiIni->user->name ?? '-' }} ({{ $koordinatorProdiIni->kode_dosen }})
            </span>
          @else
            <span class="text-secondary small fst-italic mt-0.5 d-block">Belum ada Koordinator TA yang ditetapkan</span>
          @endif
        </div>
      </div>
    </div>
  </div>

  <!-- Metric Cards -->
  <div class="row g-3">
    <div class="col-6 col-md-3">
      <div class="card shadow-sm border-light-subtle h-100 p-3 bg-white">
        <div class="d-flex align-items-center justify-content-between text-secondary mb-1">
          <span class="small">Total Mhs TA Aktif</span>
          <i class="bi bi-people"></i>
        </div>
        <p class="h3 fw-bold text-dark mt-1 mb-0">{{ $totalMhsAktif }}</p>
        <span class="text-secondary small">3 Program Studi FITE</span>
      </div>
    </div>

    <div class="col-6 col-md-3">
      <div class="card shadow-sm border-success-subtle h-100 p-3 bg-success-subtle/30">
        <div class="d-flex align-items-center justify-content-between text-success-emphasis mb-1">
          <span class="small">Tahap Akhir & Sidang</span>
          <i class="bi bi-mortarboard"></i>
        </div>
        <p class="h3 fw-bold text-success mt-1 mb-0">{{ $siapSidang }}</p>
        <span class="text-success small">{{ $persentaseSiapLulus }}% dari total TA aktif</span>
      </div>
    </div>

    <div class="col-6 col-md-3">
      <div class="card shadow-sm border-light-subtle h-100 p-3 bg-white">
        <div class="d-flex align-items-center justify-content-between text-secondary mb-1">
          <span class="small">Rasio Beban Dosen</span>
          <i class="bi bi-graph-up"></i>
        </div>
        <p class="h3 fw-bold text-dark mt-1 mb-0">1 : {{ $rasioDosen }}</p>
        <span class="text-secondary small">Mhs per Dosen Pembimbing</span>
      </div>
    </div>

    <div class="col-6 col-md-3">
      <div class="card shadow-sm border-dark h-100 p-3 bg-dark text-white">
        <div class="d-flex align-items-center justify-content-between text-white-50 mb-1">
          <span class="small">Koordinator Ditugaskan</span>
          <i class="bi bi-diagram-3"></i>
        </div>
        <p class="h3 fw-bold text-white mt-1 mb-0">{{ $totalKoordinator }}</p>
        <span class="text-white-50 small">Dari total {{ $totalDosen }} dosen FITE</span>
      </div>
    </div>
  </div>

  <!-- Tabel Manajemen Penetapan Koordinator TA (Khusus Kaprodi) -->
  <div class="card shadow-sm border-light-subtle">
    <div class="card-header bg-white py-3 d-flex flex-column sm:flex-row sm:align-items-center justify-content-between gap-2">
      <div>
        <h2 class="h6 fw-bold text-dark mb-0">Penetapan & Penugasan Koordinator Tugas Akhir</h2>
        <p class="text-secondary small mb-0">
          Dosen program studi Anda (<strong class="text-dark">{{ $kaprodiProdi }}</strong>) dapat Anda tetapkan sebagai Koordinator TA. Penetapan baru otomatis menggantikan koordinator lama.
        </p>
      </div>
      <span class="badge bg-light text-secondary border">Total: {{ $totalDosen }} Dosen FITE</span>
    </div>

    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0" style="font-size: 0.8125rem;">
        <thead class="table-light">
          <tr>
            <th class="py-2.5 px-3">Nama Dosen & NIDN</th>
            <th class="py-2.5 px-3">Kode & Prodi</th>
            <th class="py-2.5 px-3">Beban Bimbingan (P1 / P2)</th>
            <th class="py-2.5 px-3">Status Koordinator TA</th>
            <th class="py-2.5 px-3 text-end">Aksi Otoritas Kaprodi</th>
          </tr>
        </thead>
        <tbody>
          @forelse ($dosenList as $dosen)
            @php
              $isSameProdi = ($dosen->prodi === $kaprodiProdi);
            @endphp
            <tr class="{{ $isSameProdi ? 'table-primary-subtle' : '' }}">
              <td class="px-3 py-3">
                <div class="d-flex align-items-center gap-1.5">
                  <span class="fw-bold text-dark">{{ $dosen->user->name }}</span>
                  @if ($isSameProdi)
                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle" style="font-size: 0.7rem;">Prodi Anda</span>
                  @endif
                </div>
                <span class="text-secondary small">NIDN: {{ $dosen->nidn ?? '-' }}</span>
              </td>
              <td class="px-3 py-3 text-nowrap">
                <span class="badge bg-light text-dark border d-block mb-1 text-center">{{ $dosen->kode_dosen }}</span>
                <span class="small {{ $isSameProdi ? 'fw-semibold text-primary' : 'text-secondary' }}">{{ $dosen->prodi }}</span>
              </td>
              <td class="px-3 py-3 text-nowrap">
                <span class="small text-secondary">{{ $dosen->tugasAkhirPembimbing1->count() }} Utama / {{ $dosen->tugasAkhirPembimbing2->count() }} Pendamping</span>
              </td>
              <td class="px-3 py-3 text-nowrap">
                @if ($dosen->is_koordinator_ta)
                  <span class="badge bg-success-subtle text-success border border-success-subtle">Koordinator Aktif</span>
                @else
                  <span class="badge bg-light text-secondary border">Dosen Pengajar</span>
                @endif
              </td>
              <td class="px-3 py-3 text-end text-nowrap">
                @if ($isSameProdi)
                  @if ($dosen->is_koordinator_ta)
                    <form action="{{ route('kaprodi.nonaktifkan-koordinator', $dosen->id) }}" method="POST" class="d-inline">
                      @csrf
                      <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menonaktifkan status Koordinator TA dosen {{ $dosen->user->name }}?')">
                        <i class="bi bi-x-circle me-1"></i> Nonaktifkan
                      </button>
                    </form>
                  @else
                    <form action="{{ route('kaprodi.tetapkan-koordinator', $dosen->id) }}" method="POST" class="d-inline">
                      @csrf
                      <button type="submit" class="btn btn-dark btn-sm" onclick="return confirm('Tetapkan dosen {{ $dosen->user->name }} sebagai Koordinator TA {{ $kaprodiProdi }}? Jika ada koordinator aktif lain di prodi ini, posisinya akan digantikan otomatis.')">
                        <i class="bi bi-check2-circle me-1"></i> Tetapkan Koordinator
                      </button>
                    </form>
                  @endif
                @else
                  <span class="badge bg-light text-secondary border" title="Hanya Kaprodi {{ $dosen->prodi }} yang berwenang">
                    <i class="bi bi-lock me-1"></i> Khusus Kaprodi {{ $dosen->prodi }}
                  </span>
                @endif
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="5" class="py-4 text-center text-secondary">Belum ada data dosen terdaftar.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  <!-- Grafik & Analitik Statistik Kelulusan TA -->
  <div class="row g-3">
    
    <!-- Distribusi TA per Program Studi -->
    <div class="col-12 col-md-6">
      <div class="card shadow-sm border-light-subtle h-100">
        <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between">
          <div>
            <h2 class="h6 fw-bold text-dark mb-0">Distribusi Tugas Akhir per Program Studi</h2>
            <p class="text-secondary small mb-0">Proporsi mahasiswa bimbingan pada fakultas FITE</p>
          </div>
          <span class="badge bg-light text-secondary border">Real-time</span>
        </div>

        <div class="card-body p-3 p-md-4 d-flex flex-column gap-3">
          @foreach ($sebaranProdi as $namaProdi => $jumlahMhs)
            @php
              $persenProdi = $totalMhsAktif > 0 ? round(($jumlahMhs / $totalMhsAktif) * 100, 1) : 0;
            @endphp
            <div>
              <div class="d-flex justify-content-between small fw-semibold text-dark mb-1">
                <span>{{ $namaProdi }}</span>
                <span>{{ $jumlahMhs }} Mhs ({{ $persenProdi }}%)</span>
              </div>
              <div class="progress" style="height: 8px;">
                <div class="progress-bar bg-dark" role="progressbar" style="width: {{ $persenProdi }}%" aria-valuenow="{{ $persenProdi }}" aria-valuemin="0" aria-valuemax="100"></div>
              </div>
            </div>
          @endforeach
        </div>
      </div>
    </div>

    <!-- Sebaran Progres Bab Mahasiswa TA -->
    <div class="col-12 col-md-6">
      <div class="card shadow-sm border-light-subtle h-100">
        <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between">
          <div>
            <h2 class="h6 fw-bold text-dark mb-0">Rekapitulasi Sebaran Status Pengerjaan TA</h2>
            <p class="text-secondary small mb-0">Distribusi tahapan mahasiswa pada semester berjalan</p>
          </div>
          <span class="badge bg-light text-secondary border">Data Terkini</span>
        </div>

        <div class="card-body p-3 p-md-4">
          <div class="row g-2">
            <div class="col-6">
              <div class="p-3 bg-light rounded border h-100">
                <span class="text-secondary small d-block">Proposal & Pengerjaan</span>
                <p class="h4 fw-bold text-dark mt-1 mb-0">{{ ($sebaranBab['proposal'] ?? 0) + ($sebaranBab['pengerjaan'] ?? 0) }} <span class="small fw-normal text-secondary">Mhs</span></p>
              </div>
            </div>
            <div class="col-6">
              <div class="p-3 bg-light rounded border h-100">
                <span class="text-secondary small d-block">Seminar Proposal</span>
                <p class="h4 fw-bold text-dark mt-1 mb-0">{{ $sebaranBab['sempro'] ?? 0 }} <span class="small fw-normal text-secondary">Mhs</span></p>
              </div>
            </div>
            <div class="col-6">
              <div class="p-3 bg-light rounded border h-100">
                <span class="text-secondary small d-block">Seminar TA (Progres)</span>
                <p class="h4 fw-bold text-dark mt-1 mb-0">{{ $sebaranBab['seminar_ta'] ?? 0 }} <span class="small fw-normal text-secondary">Mhs</span></p>
              </div>
            </div>
            <div class="col-6">
              <div class="p-3 bg-success-subtle/40 rounded border border-success-subtle h-100">
                <span class="text-success fw-semibold small d-block">Siap Sidang & Selesai</span>
                <p class="h4 fw-bold text-success mt-1 mb-0">{{ ($sebaranBab['sidang_akhir'] ?? 0) + ($sebaranBab['selesai'] ?? 0) }} <span class="small fw-normal text-success">Mhs</span></p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>

</div>
@endsection

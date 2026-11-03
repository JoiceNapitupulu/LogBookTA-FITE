@extends('layouts.notion')

@section('title', 'Panel Koordinator TA')
@section('breadcrumb', 'Koordinator TA')

@section('content')
<div class="d-flex flex-column gap-3" x-data="{ 
  assignModal: false, 
  kelompokModal: false, 
  selectedTa: null, 
  openAssign(ta) { 
    this.selectedTa = ta; 
    this.assignModal = true; 
  },
  openKelompok(ta) {
    this.selectedTa = ta;
    this.kelompokModal = true;
  }
}">

  <!-- Header Halaman Akademik -->
  <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 pb-3 border-bottom">
    <div>
      <h1 class="h4 fw-bold text-dark mb-1">Panel Koordinator Tugas Akhir FITE</h1>
      <p class="text-secondary small mb-0">Penetapan Dosen Pembimbing 1 & 2 serta pengawasan rekapitulasi capaian bab tugas akhir mahasiswa.</p>
    </div>

    <div class="d-flex align-items-center gap-2 shrink-0">
      <a href="{{ route('ujian.index') }}" class="btn btn-outline-dark btn-sm d-inline-flex align-items-center gap-1.5">
        <i class="bi bi-mortarboard"></i>
        <span>Kelola Jadwal Ujian ({{ $siapJadwalCount }})</span>
      </a>
    </div>
  </div>

  <!-- Metrik Ringkas Koordinator TA -->
  <div class="row g-3">
    <div class="col-6 col-md-3">
      <div class="card shadow-sm border-light-subtle h-100 p-3 bg-white">
        <div class="d-flex align-items-center justify-content-between text-secondary mb-1">
          <span class="small">Total Tugas Akhir</span>
          <i class="bi bi-folder2-open"></i>
        </div>
        <p class="h3 fw-bold text-dark mt-1 mb-0">{{ $totalTaAktif }}</p>
        <span class="text-secondary small">Mahasiswa aktif FITE</span>
      </div>
    </div>

    <div class="col-6 col-md-3">
      <div class="card shadow-sm {{ $menungguAssignCount > 0 ? 'border-warning-subtle bg-warning-subtle/30' : 'border-light-subtle bg-white' }} h-100 p-3">
        <div class="d-flex align-items-center justify-content-between {{ $menungguAssignCount > 0 ? 'text-warning-emphasis' : 'text-secondary' }} mb-1">
          <span class="small">Menunggu Pembimbing</span>
          <i class="bi bi-person-exclamation"></i>
        </div>
        <p class="h3 fw-bold {{ $menungguAssignCount > 0 ? 'text-warning-emphasis' : 'text-dark' }} mt-1 mb-0">{{ $menungguAssignCount }}</p>
        <span class="{{ $menungguAssignCount > 0 ? 'text-warning-emphasis' : 'text-secondary' }} small">Perlu alokasi dospem</span>
      </div>
    </div>

    <div class="col-6 col-md-3">
      <div class="card shadow-sm border-success-subtle h-100 p-3 bg-success-subtle/30">
        <div class="d-flex align-items-center justify-content-between text-success-emphasis mb-1">
          <span class="small">Siap Dijadwalkan</span>
          <i class="bi bi-check-circle"></i>
        </div>
        <p class="h3 fw-bold text-success mt-1 mb-0">{{ $siapJadwalCount }}</p>
        <a href="{{ route('ujian.index') }}" class="small fw-semibold text-success text-decoration-none d-inline-flex align-items-center gap-1 mt-1">
          <span>Atur Tanggal Ujian</span>
          <i class="bi bi-arrow-right"></i>
        </a>
      </div>
    </div>

    <div class="col-6 col-md-3">
      <div class="card shadow-sm border-light-subtle h-100 p-3 bg-white">
        <div class="d-flex align-items-center justify-content-between text-secondary mb-1">
          <span class="small">Tahap Lanjut / Sidang</span>
          <i class="bi bi-mortarboard"></i>
        </div>
        <p class="h3 fw-bold text-dark mt-1 mb-0">{{ $totalSiapSidang }}</p>
        <span class="text-secondary small">Seminar TA & Sidang</span>
      </div>
    </div>
  </div>

  @if ($unassignedMhs->isNotEmpty())
    <div class="alert alert-info d-flex align-items-center gap-2 mb-0 shadow-sm" role="alert">
      <i class="bi bi-info-circle-fill fs-5"></i>
      <div class="small">
        <strong class="d-block">Informasi: Terdapat {{ $unassignedMhs->count() }} Mahasiswa TA yang belum dibuatkan entri Tugas Akhir</strong>
        <span>Mahasiswa berikut terdata sebagai peserta TA aktif: <strong>{{ $unassignedMhs->pluck('user.name')->implode(', ') }}</strong></span>
      </div>
    </div>
  @endif

  <!-- Tabel Rekapitulasi Mahasiswa TA & Penetapan Pembimbing -->
  <div class="card shadow-sm border-light-subtle">
    <div class="card-header bg-white py-3 d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-3">
      <div>
        <h2 class="h6 fw-bold text-dark mb-0">Rekapitulasi Tugas Akhir & Penunjukan Pembimbing</h2>
        <p class="text-secondary small mb-0">Kelola dosen pembimbing utama dan pendamping sesuai keahlian riset.</p>
      </div>

      <form method="GET" action="{{ route('koordinator.index') }}" class="d-flex align-items-center gap-2">
        <select name="prodi" onchange="this.form.submit()" class="form-select form-select-sm" style="width: auto; font-size: 0.8125rem;">
          <option value="">Semua Program Studi</option>
          <option value="S1 Informatika" {{ request('prodi') === 'S1 Informatika' ? 'selected' : '' }}>S1 Informatika</option>
          <option value="S1 Sistem Informasi" {{ request('prodi') === 'S1 Sistem Informasi' ? 'selected' : '' }}>S1 Sistem Informasi</option>
          <option value="S1 Teknik Elektro" {{ request('prodi') === 'S1 Teknik Elektro' ? 'selected' : '' }}>S1 Teknik Elektro</option>
        </select>
      </form>
    </div>

    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0" style="font-size: 0.8125rem;">
        <thead class="table-light">
          <tr>
            <th class="py-2.5 px-3">Mahasiswa & NIM</th>
            <th class="py-2.5 px-3">Judul Tugas Akhir</th>
            <th class="py-2.5 px-3">Dosen Pembimbing 1</th>
            <th class="py-2.5 px-3">Dosen Pembimbing 2</th>
            <th class="py-2.5 px-3">Logbook</th>
            <th class="py-2.5 px-3">Status Tahap</th>
            <th class="py-2.5 px-3 text-end">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse ($tugasAkhirList as $ta)
            <tr>
              <td class="px-3 py-3">
                @if ($ta->anggota && $ta->anggota->isNotEmpty())
                  <div class="d-flex flex-column gap-1">
                    <div class="d-flex flex-wrap gap-1">
                      @foreach ($ta->anggota as $member)
                        <span class="badge bg-light text-dark border">
                          <span class="fw-semibold">{{ $member->user->name ?? '-' }}</span>
                          <span class="text-secondary">({{ $member->nim }})</span>
                        </span>
                      @endforeach
                    </div>
                    <span class="text-secondary small">{{ $ta->prodi }} · {{ $ta->jumlahAnggota() }} Mahasiswa</span>
                  </div>
                @else
                  <p class="fw-bold text-dark mb-0">{{ $ta->mahasiswa->user->name ?? '-' }}</p>
                  <p class="text-secondary small mb-0">{{ $ta->mahasiswa->nim ?? '-' }} · {{ $ta->prodi }}</p>
                @endif
              </td>
              <td class="px-3 py-3" style="max-width: 280px;">
                <p class="fw-medium text-dark mb-0 line-clamp-2">{{ $ta->judul }}</p>
              </td>
              <td class="px-3 py-3 text-nowrap">
                <span class="fw-medium text-dark">{{ $ta->pembimbing1->user->name ?? 'Belum Ditetapkan' }}</span>
              </td>
              <td class="px-3 py-3 text-nowrap">
                <span class="text-secondary">{{ $ta->pembimbing2->user->name ?? '-' }}</span>
              </td>
              <td class="px-3 py-3 text-nowrap">
                <span class="badge bg-success-subtle text-success border border-success-subtle">{{ $ta->logbooks->count() }} Sesi</span>
              </td>
              <td class="px-3 py-3 text-nowrap">
                <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle text-uppercase">{{ str_replace('_', ' ', $ta->status) }}</span>
              </td>
              <td class="px-3 py-3 text-end text-nowrap">
                <div class="btn-group btn-group-sm">
                  <button @click="openKelompok({{ $ta->toJson() }})" class="btn btn-outline-dark">
                    Anggota ({{ $ta->jumlahAnggota() }})
                  </button>
                  <button @click="openAssign({{ $ta->toJson() }})" class="btn btn-dark">
                    Atur Pembimbing
                  </button>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="7" class="py-4 text-center text-secondary">Tidak ada data mahasiswa TA.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    @if ($tugasAkhirList->hasPages())
      <div class="card-footer bg-light p-3">
        {{ $tugasAkhirList->links() }}
      </div>
    @endif
  </div>

  <!-- MODAL: TETAPKAN DOSEN PEMBIMBING -->
  <div x-show="assignModal" x-cloak class="modal-backdrop-custom">
    <div class="modal-dialog modal-dialog-centered w-100 mx-3" style="max-width: 480px;" @click.away="assignModal = false">
      <div class="modal-content bg-white shadow-lg border-0 rounded-3">
        
        <div class="modal-header border-bottom py-3 px-4">
          <h5 class="modal-title fs-6 fw-bold text-dark mb-0">Tetapkan Dosen Pembimbing Mahasiswa</h5>
          <button type="button" class="btn-close" @click="assignModal = false"></button>
        </div>

        <template x-if="selectedTa">
          <form action="{{ route('koordinator.assign') }}" method="POST">
            @csrf
            <input type="hidden" name="tugas_akhir_id" :value="selectedTa.id">

            <div class="modal-body p-4 d-flex flex-column gap-3" style="font-size: 0.8125rem;">
              <div class="p-2.5 bg-light rounded border">
                <p class="fw-bold text-dark mb-0.5" x-text="selectedTa.mahasiswa?.user?.name"></p>
                <p class="text-secondary small mb-0" x-text="selectedTa.judul"></p>
              </div>

              <div>
                <label class="form-label fw-semibold text-secondary small mb-1">Dosen Pembimbing 1 (Utama)</label>
                <select name="dosen_pembimbing_1_id" required class="form-select form-select-sm">
                  @foreach ($dosenList as $d)
                    <option value="{{ $d->id }}">{{ $d->user->name }} ({{ $d->kode_dosen }}) - {{ $d->prodi }}</option>
                  @endforeach
                </select>
              </div>

              <div>
                <label class="form-label fw-semibold text-secondary small mb-1">Dosen Pembimbing 2 (Pendamping, Opsional)</label>
                <select name="dosen_pembimbing_2_id" class="form-select form-select-sm">
                  <option value="">-- Tanpa Pembimbing 2 --</option>
                  @foreach ($dosenList as $d)
                    <option value="{{ $d->id }}">{{ $d->user->name }} ({{ $d->kode_dosen }}) - {{ $d->prodi }}</option>
                  @endforeach
                </select>
              </div>
            </div>

            <div class="modal-footer border-top py-2 px-4 d-flex justify-content-end gap-2">
              <button type="button" @click="assignModal = false" class="btn btn-outline-secondary btn-sm">Batal</button>
              <button type="submit" class="btn btn-dark btn-sm">Simpan Penetapan</button>
            </div>
          </form>
        </template>

      </div>
    </div>
  </div>

  <!-- MODAL: KELOLA ANGGOTA KELOMPOK TUGAS AKHIR -->
  <div x-show="kelompokModal" x-cloak class="modal-backdrop-custom">
    <div class="modal-dialog modal-dialog-centered w-100 mx-3" style="max-width: 520px;" @click.away="kelompokModal = false">
      <div class="modal-content bg-white shadow-lg border-0 rounded-3">
        
        <div class="modal-header border-bottom py-3 px-4">
          <div>
            <h5 class="modal-title fs-6 fw-bold text-dark mb-0">Kelola Anggota Kelompok Tugas Akhir</h5>
            <p class="text-secondary small mb-0">Setiap TA dapat memiliki 1 hingga maksimal 3 mahasiswa setara (tanpa ketua).</p>
          </div>
          <button type="button" class="btn-close" @click="kelompokModal = false"></button>
        </div>

        <template x-if="selectedTa">
          <div class="modal-body p-4 d-flex flex-column gap-3" style="font-size: 0.8125rem;">
            <!-- Info TA -->
            <div class="p-2.5 bg-light rounded border">
              <div class="d-flex align-items-center gap-1.5 mb-1">
                <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle" x-text="selectedTa.prodi"></span>
                <span class="text-secondary small">TA ID: #<span x-text="selectedTa.id"></span></span>
              </div>
              <p class="fw-bold text-dark small mb-0 lh-sm" x-text="selectedTa.judul"></p>
            </div>

            <!-- Daftar Anggota Saat Ini -->
            <div>
              <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="fw-bold text-secondary text-uppercase small" style="font-size: 0.75rem;">
                  Anggota Saat Ini (<span x-text="selectedTa.anggota?.length ?? 0"></span>/3 Mahasiswa)
                </span>
                <span class="text-secondary small">Minimal 1, Maksimal 3</span>
              </div>

              <div class="list-group border rounded">
                <template x-for="(member, index) in (selectedTa.anggota || [])" :key="member.id">
                  <div class="list-group-item d-flex align-items-center justify-content-between py-2 px-3">
                    <div class="d-flex align-items-center gap-2">
                      <div class="badge bg-dark rounded-circle d-flex align-items-center justify-content-center" style="width: 22px; height: 22px; font-size: 10px;" x-text="index + 1"></div>
                      <div>
                        <p class="fw-bold text-dark mb-0 small" x-text="member.user?.name ?? '-'"></p>
                        <p class="text-secondary mb-0" style="font-size: 0.75rem;">NIM: <span x-text="member.nim"></span> • <span x-text="member.user?.email"></span></p>
                      </div>
                    </div>

                    <!-- Tombol Hapus Anggota -->
                    <div>
                      <template x-if="(selectedTa.anggota?.length || 0) > 1">
                        <form :action="'{{ url('/koordinator/tugas-akhir') }}/' + selectedTa.id + '/anggota/' + member.id" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin mengeluarkan mahasiswa ini dari kelompok TA?');">
                          @csrf
                          @method('DELETE')
                          <button type="submit" class="btn btn-outline-danger btn-sm py-0.5 px-2" style="font-size: 0.75rem;">
                            Hapus
                          </button>
                        </form>
                      </template>
                      <template x-if="(selectedTa.anggota?.length || 0) <= 1">
                        <span class="badge bg-light text-secondary border" title="Minimal 1 anggota tersisa">
                          Terkunci (Min. 1)
                        </span>
                      </template>
                    </div>
                  </div>
                </template>
              </div>
            </div>

            <!-- Form Tambah Anggota Baru -->
            <div class="pt-2 border-top">
              <!-- Jika sudah penuh 3 anggota -->
              <template x-if="(selectedTa.anggota?.length || 0) >= 3">
                <div class="alert alert-warning py-2 px-3 small mb-0">
                  Kelompok ini sudah mencapai batas maksimal <strong>3 mahasiswa</strong>. Hapus anggota terlebih dahulu jika ingin mengganti.
                </div>
              </template>

              <!-- Jika masih bisa tambah (< 3) -->
              <template x-if="(selectedTa.anggota?.length || 0) < 3">
                <form :action="'{{ url('/koordinator/tugas-akhir') }}/' + selectedTa.id + '/anggota'" method="POST" class="d-flex flex-column gap-2">
                  @csrf
                  <div>
                    <label class="form-label fw-semibold text-secondary small mb-1">
                      Tambah Mahasiswa ke Kelompok (Slot Tersedia: <span x-text="3 - (selectedTa.anggota?.length || 0)"></span>)
                    </label>
                    <select name="mahasiswa_id" required class="form-select form-select-sm">
                      <option value="">-- Pilih Mahasiswa Belum Berkelompok --</option>
                      @forelse ($eligibleMhsList as $emhs)
                        <option value="{{ $emhs->id }}" x-show="!selectedTa.anggota?.some(m => m.id === {{ $emhs->id }})">
                          {{ $emhs->user->name ?? '-' }} (NIM: {{ $emhs->nim }}) — {{ $emhs->prodi }}
                        </option>
                      @empty
                        <option disabled value="">Tidak ada mahasiswa yang eligible/belum berkelompok</option>
                      @endforelse
                    </select>
                    <span class="text-secondary small d-block mt-1">Hanya menampilkan mahasiswa TA aktif yang belum terdaftar di kelompok manapun.</span>
                  </div>

                  <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-dark btn-sm">
                      Tambahkan ke Kelompok
                    </button>
                  </div>
                </form>
              </template>
            </div>

            <div class="d-flex justify-content-end pt-2 border-top">
              <button type="button" @click="kelompokModal = false" class="btn btn-outline-secondary btn-sm">Tutup</button>
            </div>
          </div>
        </template>

      </div>
    </div>
  </div>

</div>
@endsection

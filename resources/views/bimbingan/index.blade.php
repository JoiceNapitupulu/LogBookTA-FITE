@extends('layouts.notion')

@section('title', 'Janji Bimbingan')
@section('breadcrumb', 'Janji Bimbingan')

@section('content')
<div x-data="{ 
  openModal: false,
  completeModalOpen: false,
  selectedJanji: null,
  openComplete(item) {
    this.selectedJanji = item;
    this.completeModalOpen = true;
  }
}">

  <!-- 1. Header Halaman Akademik Bersih -->
  <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 pb-3 mb-4 border-bottom">
    <div>
      <h1 class="h3 fw-bold text-dark mb-1">Janji Bimbingan</h1>
      <p class="text-secondary small mb-0">Koordinasi jadwal bimbingan mahasiswa dan dosen.</p>
    </div>

    <div>
      <button @click="openModal = true" class="btn btn-primary d-inline-flex align-items-center gap-2">
        <i class="bi bi-plus-lg"></i>
        <span>Ajukan Janji Bimbingan</span>
      </button>
    </div>
  </div>

  <!-- 2. Bagian Menunggu Persetujuan (Pending Approval Cards) -->
  @php
    $pendingList = $janjiList->where('status', 'menunggu_persetujuan');
  @endphp

  @if ($pendingList->isNotEmpty())
    <div class="card border-warning-subtle bg-warning-subtle/20 shadow-sm mb-4">
      <div class="card-header bg-transparent border-warning-subtle py-2.5 d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center gap-2">
          <i class="bi bi-hourglass-split text-warning"></i>
          <h2 class="h6 fw-bold text-dark mb-0">Menunggu Persetujuan</h2>
        </div>
        <span class="badge bg-warning text-dark">{{ $pendingList->count() }} Permintaan</span>
      </div>

      <div class="card-body">
        <div class="row g-3">
          @foreach ($pendingList as $item)
            <div class="col-12 col-lg-6">
              <div class="card h-100 border shadow-xs bg-white">
                <div class="card-body d-flex flex-column justify-content-between">
                  <div>
                    <div class="d-flex align-items-center justify-content-between mb-2">
                      <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle">
                        Inisiator: {{ ucfirst($item->inisiator) }}
                      </span>
                      <span class="small fw-bold text-dark">
                        <i class="bi bi-clock me-1 text-secondary"></i>{{ $item->tanggal_waktu->format('d M Y, H:i') }} WIB
                      </span>
                    </div>

                    <h3 class="h6 fw-bold text-dark mb-1">{{ $item->topik_pembahasan }}</h3>

                    <p class="small text-secondary mb-2">
                      @if ($item->tugasAkhir->anggota && $item->tugasAkhir->anggota->isNotEmpty())
                        Mahasiswa: <strong class="text-dark">{{ $item->tugasAkhir->anggota->pluck('user.name')->implode(', ') }}</strong> · 
                      @else
                        Mahasiswa: <strong class="text-dark">{{ $item->tugasAkhir->mahasiswa->user->name ?? '-' }}</strong> · 
                      @endif
                      Dosen: <strong class="text-dark">{{ $item->dosen->user->name ?? '-' }}</strong>
                    </p>

                    <div class="d-inline-flex align-items-center gap-1.5 px-2.5 py-1 rounded bg-light border text-secondary small">
                      <i class="bi {{ $item->metode === 'offline' ? 'bi-geo-alt' : 'bi-camera-video' }}"></i>
                      <span>{{ $item->metode === 'offline' ? 'Tatap Muka:' : 'Daring:' }} {{ $item->lokasi_atau_link ?? '-' }}</span>
                    </div>
                  </div>

                  <!-- Footer Card Aksi Dosen / Status Mahasiswa -->
                  <div class="pt-3 mt-3 border-top d-flex align-items-center justify-content-between">
                    @if (auth()->user()->isDosen() && auth()->user()->dosenProfile?->id === $item->dosen_id)
                      <span class="small text-secondary">Aksi konfirmasi jadwal:</span>
                      <div class="d-flex align-items-center gap-2">
                        <form action="{{ route('bimbingan.status', $item->id) }}" method="POST" class="mb-0">
                          @csrf
                          @method('PATCH')
                          <input type="hidden" name="status" value="ditolak">
                          <button type="submit" class="btn btn-outline-danger btn-sm">Tolak</button>
                        </form>

                        <form action="{{ route('bimbingan.status', $item->id) }}" method="POST" class="mb-0">
                          @csrf
                          @method('PATCH')
                          <input type="hidden" name="status" value="disetujui">
                          <button type="submit" class="btn btn-success btn-sm">Setujui Jadwal</button>
                        </form>
                      </div>
                    @else
                      <span class="small text-secondary d-flex align-items-center gap-1">
                        <i class="bi bi-clock-history"></i> Menunggu konfirmasi {{ $item->dosen->user->name ?? 'Dosen' }}
                      </span>
                    @endif
                  </div>
                </div>
              </div>
            </div>
          @endforeach
        </div>
      </div>
    </div>
  @endif

  <!-- 3. Tabel Riwayat / Terkonfirmasi -->
  <div class="card shadow-sm border-light-subtle">
    <div class="card-header bg-white py-3 d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-2">
      <div>
        <h2 class="h6 fw-bold text-dark mb-0">Riwayat & Jadwal Terkonfirmasi</h2>
        <p class="text-secondary small mb-0">Seluruh sesi bimbingan tugas akhir yang terdaftar pada sistem.</p>
      </div>
      <span class="badge bg-light text-secondary border">Total: {{ $janjiList->total() }} Sesi</span>
    </div>

    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0" style="font-size: 0.8125rem;">
        <thead class="table-light">
          <tr>
            <th class="py-2.5 px-3">Waktu Pelaksanaan</th>
            <th class="py-2.5 px-3">Mahasiswa & Dosen</th>
            <th class="py-2.5 px-3">Topik Bimbingan</th>
            <th class="py-2.5 px-3">Metode & Lokasi</th>
            <th class="py-2.5 px-3">Status</th>
            <th class="py-2.5 px-3 text-end">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse ($janjiList as $janji)
            <tr>
              <td class="px-3 py-3 text-nowrap">
                <span class="fw-bold text-dark d-block">{{ $janji->tanggal_waktu->format('d M Y') }}</span>
                <span class="text-secondary small">{{ $janji->tanggal_waktu->format('H:i') }} WIB</span>
              </td>
              <td class="px-3 py-3">
                @if ($janji->tugasAkhir->anggota && $janji->tugasAkhir->anggota->isNotEmpty())
                  <div class="d-flex flex-column gap-0.5">
                    @foreach ($janji->tugasAkhir->anggota as $member)
                      <span class="fw-semibold text-dark">{{ $member->user->name ?? '-' }} <span class="text-secondary fw-normal">({{ $member->nim }})</span></span>
                    @endforeach
                  </div>
                @else
                  <span class="fw-semibold text-dark d-block">{{ $janji->tugasAkhir->mahasiswa->user->name ?? '-' }}</span>
                  <span class="text-secondary small">NIM: {{ $janji->tugasAkhir->mahasiswa->nim ?? '-' }}</span>
                @endif
                <div class="text-secondary small mt-1">
                  Dosen: <strong class="text-dark">{{ $janji->dosen->user->name ?? '-' }}</strong>
                </div>
              </td>
              <td class="px-3 py-3" style="max-width: 320px;">
                <p class="fw-medium text-dark mb-1">{{ $janji->topik_pembahasan }}</p>
                
                @if ($janji->status === 'selesai' && $janji->catatan_hasil)
                  <div class="p-2 bg-light rounded border text-secondary mt-1" style="font-size: 0.75rem;">
                    <span class="fw-bold text-dark d-block text-uppercase mb-0.5" style="font-size: 0.7rem;">Catatan Hasil Bimbingan:</span>
                    <span class="text-dark lh-sm">{{ $janji->catatan_hasil }}</span>
                  </div>
                @elseif ($janji->status === 'ditolak' && $janji->alasan_penolakan)
                  <div class="p-2 bg-danger-subtle rounded border border-danger-subtle text-danger mt-1" style="font-size: 0.75rem;">
                    <span class="fw-bold d-block text-uppercase mb-0.5" style="font-size: 0.7rem;">Alasan Penolakan:</span>
                    <span class="lh-sm">{{ $janji->alasan_penolakan }}</span>
                  </div>
                @endif
              </td>
              <td class="px-3 py-3 text-secondary">
                <span class="badge bg-light text-dark border mb-1">{{ ucfirst($janji->metode) }}</span>
                <span class="d-block small text-dark">{{ $janji->lokasi_atau_link ?? '-' }}</span>
              </td>
              <td class="px-3 py-3 text-nowrap">
                @if ($janji->status === 'disetujui')
                  <span class="badge bg-success-subtle text-success border border-success-subtle">
                    <i class="bi bi-check-circle me-1"></i> Terkonfirmasi
                  </span>
                @elseif ($janji->status === 'menunggu_persetujuan')
                  <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle">
                    <i class="bi bi-hourglass me-1"></i> Menunggu Persetujuan
                  </span>
                @elseif ($janji->status === 'ditolak')
                  <span class="badge bg-danger-subtle text-danger border border-danger-subtle">
                    <i class="bi bi-x-circle me-1"></i> Ditolak
                  </span>
                @else
                  <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle">
                    <i class="bi bi-check-all me-1"></i> Selesai
                  </span>
                @endif
              </td>
              <td class="px-3 py-3 text-end text-nowrap">
                @if ($janji->status === 'disetujui' && auth()->user()->isDosen() && auth()->user()->dosenProfile?->id === $janji->dosen_id)
                  <button @click="openComplete({{ $janji->toJson() }})" class="btn btn-outline-success btn-sm d-inline-flex align-items-center gap-1">
                    <i class="bi bi-pencil-square"></i>
                    <span>Selesaikan & Catat</span>
                  </button>
                @elseif ($janji->status === 'selesai')
                  <span class="text-secondary small">Selesai</span>
                @else
                  <span class="text-secondary small">-</span>
                @endif
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="6" class="text-center py-5 text-secondary">
                <i class="bi bi-calendar-x fs-3 d-block mb-2 text-secondary"></i>
                <p class="mb-0">Belum ada data jadwal bimbingan.</p>
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    @if ($janjiList->hasPages())
      <div class="card-footer bg-white border-top py-2">
        {{ $janjiList->links() }}
      </div>
    @endif
  </div>

  <!-- 4. MODAL: AJUKAN JANJI BIMBINGAN (BOOTSTRAP 5) -->
  <div x-show="openModal" x-cloak class="modal-backdrop-custom">
    <div class="modal-dialog modal-dialog-centered w-100 p-3" style="max-width: 520px;" @click.away="openModal = false">
      <div class="modal-content bg-white border-0 shadow-lg rounded-3">
        
        <div class="modal-header border-bottom py-3">
          <h2 class="modal-title h5 fw-bold text-dark mb-0">Ajukan Janji Bimbingan</h2>
          <button type="button" class="btn-close" @click="openModal = false"></button>
        </div>

        <form action="{{ route('bimbingan.store') }}" method="POST">
          @csrf
          <div class="modal-body p-4">
            
            @if (auth()->user()->isMahasiswa() && $myTugasAkhir)
              <input type="hidden" name="tugas_akhir_id" value="{{ $myTugasAkhir->id }}">
            @elseif (auth()->user()->isDosen() || auth()->user()->isKaprodi())
              <div class="mb-3">
                <label class="form-label small fw-semibold text-dark">Pilih Mahasiswa Bimbingan</label>
                <select name="tugas_akhir_id" required class="form-select form-select-sm">
                  @forelse (($bimbinganTaList ?? []) as $ta)
                    @php
                      $namaMhs = $ta->anggota && $ta->anggota->isNotEmpty()
                        ? $ta->anggota->map(fn($m) => ($m->user->name ?? '-') . ' (' . $m->nim . ')')->implode(', ')
                        : (($ta->mahasiswa->user->name ?? 'Mahasiswa') . ' (' . ($ta->mahasiswa->nim ?? '-') . ')');
                    @endphp
                    <option value="{{ $ta->id }}">{{ $namaMhs }} — {{ Str::limit($ta->judul, 45) }}</option>
                  @empty
                    <option disabled value="">Belum ada mahasiswa bimbingan aktif</option>
                  @endforelse
                </select>
              </div>
            @endif

            @if ((auth()->user()->isDosen() || auth()->user()->isKaprodi()) && auth()->user()->dosenProfile)
              <input type="hidden" name="dosen_id" value="{{ auth()->user()->dosenProfile->id }}">
            @else
              <div class="mb-3">
                <label class="form-label small fw-semibold text-dark">Dosen Pembimbing</label>
                <select name="dosen_id" required class="form-select form-select-sm">
                  @foreach ($dosenList as $dosen)
                    <option value="{{ $dosen->id }}">{{ $dosen->user->name }} ({{ $dosen->kode_dosen }})</option>
                  @endforeach
                </select>
              </div>
            @endif

            <div class="mb-3">
              <label class="form-label small fw-semibold text-dark">Tanggal & Waktu Pertemuan</label>
              <input type="datetime-local" name="tanggal_waktu" required class="form-control form-control-sm">
            </div>

            <div class="mb-3">
              <label class="form-label small fw-semibold text-dark">Topik Pembahasan</label>
              <input type="text" name="topik_pembahasan" required placeholder="Contoh: Diskusi Bab 4 dan analisis data..." class="form-control form-control-sm">
            </div>

            <div class="row g-2 mb-2">
              <div class="col-6">
                <label class="form-label small fw-semibold text-dark">Metode</label>
                <select name="metode" required class="form-select form-select-sm">
                  <option value="offline">Tatap Muka (Offline)</option>
                  <option value="online">Daring (Online)</option>
                </select>
              </div>
              <div class="col-6">
                <label class="form-label small fw-semibold text-dark">Lokasi / Tautan</label>
                <input type="text" name="lokasi_atau_link" placeholder="Ruang GD 512 / Tautan Zoom" class="form-control form-control-sm">
              </div>
            </div>

          </div>

          <div class="modal-footer border-top py-2.5">
            <button type="button" @click="openModal = false" class="btn btn-outline-secondary btn-sm">Batal</button>
            <button type="submit" class="btn btn-primary btn-sm">Kirim Pengajuan</button>
          </div>
        </form>

      </div>
    </div>
  </div>

  <!-- 5. MODAL: SELESAIKAN SESI BIMBINGAN & CATATAN HASIL (BOOTSTRAP 5) -->
  <div x-show="completeModalOpen" x-cloak class="modal-backdrop-custom">
    <div class="modal-dialog modal-dialog-centered w-100 p-3" style="max-width: 540px;" @click.away="completeModalOpen = false">
      <div class="modal-content bg-white border-0 shadow-lg rounded-3">
        
        <div class="modal-header border-bottom py-3">
          <h2 class="modal-title h5 fw-bold text-dark mb-0">Selesaikan Sesi Bimbingan</h2>
          <button type="button" class="btn-close" @click="completeModalOpen = false"></button>
        </div>

        <template x-if="selectedJanji">
          <form :action="'/bimbingan/' + selectedJanji.id + '/status'" method="POST">
            @csrf
            @method('PATCH')
            <input type="hidden" name="status" value="selesai">

            <div class="modal-body p-4">
              <div class="p-2.5 rounded bg-light border mb-3">
                <p class="fw-bold text-dark small mb-0.5" x-text="selectedJanji.topik_pembahasan"></p>
                <p class="text-secondary small mb-0">
                  Mahasiswa: <span class="fw-semibold text-dark" x-text="selectedJanji.tugas_akhir?.mahasiswa?.user?.name ?? '-'"></span>
                </p>
              </div>

              <div class="mb-2">
                <label class="form-label small fw-semibold text-dark">
                  Catatan Hasil Bimbingan <span class="text-danger">* (Wajib diisi, minimal 10 karakter)</span>
                </label>
                <textarea name="catatan_hasil" rows="4" required minlength="10" maxlength="2000"
                          placeholder="Tuliskan evaluasi progres, arahan revisi, atau poin tindak lanjut untuk mahasiswa..."
                          class="form-control form-control-sm"></textarea>
                <div class="form-text small">Catatan ini akan tersimpan pada riwayat resmi bimbingan mahasiswa.</div>
              </div>
            </div>

            <div class="modal-footer border-top py-2.5">
              <button type="button" @click="completeModalOpen = false" class="btn btn-outline-secondary btn-sm">Batal</button>
              <button type="submit" class="btn btn-success btn-sm d-inline-flex align-items-center gap-1.5">
                <i class="bi bi-check2-circle"></i>
                <span>Simpan Catatan & Selesaikan</span>
              </button>
            </div>
          </form>
        </template>

      </div>
    </div>
  </div>

</div>
@endsection

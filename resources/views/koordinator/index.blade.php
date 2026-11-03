@extends('layouts.notion')

@section('title', 'Panel Koordinator TA')
@section('breadcrumb', 'Koordinator TA')

@section('content')
<div class="space-y-6" x-data="{ 
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

  <!-- Header Notion -->
  <div class="border-b border-[#E9E9E7] pb-5 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
      <div class="flex items-center gap-2 mb-1">
        <h1 class="text-2xl font-bold tracking-tight text-[#37352F]">Panel Koordinator Tugas Akhir FITE</h1>
      </div>
      <p class="text-xs text-[#787774]">Penetapan Dosen Pembimbing 1 & 2 serta pengawasan rekapitulasi capaian bab tugas akhir mahasiswa.</p>
    </div>

    <div class="flex items-center gap-2">
      <a href="{{ route('ujian.index') }}" class="btn btn-outline-dark btn-sm text-xs d-inline-flex align-items-center gap-1.5">
        <i class="bi bi-mortarboard"></i> Kelola Jadwal Ujian ({{ $siapJadwalCount }})
      </a>
    </div>
  </div>

  <!-- Metrik Ringkas Koordinator TA ala Notion -->
  <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
    <div class="p-4 bg-[#F7F6F3] rounded-lg border border-[#E9E9E7]">
      <p class="text-xs text-[#787774]">Total Tugas Akhir</p>
      <p class="text-2xl font-bold text-[#37352F] mt-1">{{ $totalTaAktif }}</p>
      <p class="text-[10px] text-[#787774] mt-0.5">Mahasiswa aktif FITE</p>
    </div>

    <div class="p-4 {{ $menungguAssignCount > 0 ? 'bg-[#FDF5E6] border-[#F3E0A2]' : 'bg-[#F7F6F3] border-[#E9E9E7]' }} rounded-lg border">
      <p class="text-xs {{ $menungguAssignCount > 0 ? 'text-[#B86200]' : 'text-[#787774]' }}">Menunggu Pembimbing</p>
      <p class="text-2xl font-bold {{ $menungguAssignCount > 0 ? 'text-[#B86200]' : 'text-[#37352F]' }} mt-1">{{ $menungguAssignCount }}</p>
      <p class="text-[10px] {{ $menungguAssignCount > 0 ? 'text-[#B86200]' : 'text-[#787774]' }} mt-0.5">Perlu alokasi dospem</p>
    </div>

    <div class="p-4 bg-[#EBF5EE] rounded-lg border border-[#C4E5D1]">
      <p class="text-xs text-[#2A7E4B]">Siap Dijadwalkan</p>
      <p class="text-2xl font-bold text-[#2A7E4B] mt-1">{{ $siapJadwalCount }}</p>
      <a href="{{ route('ujian.index') }}" class="text-[10px] font-semibold text-[#2A7E4B] underline mt-0.5 inline-block">Atur Tanggal Ujian &rarr;</a>
    </div>

    <div class="p-4 bg-[#F7F6F3] rounded-lg border border-[#E9E9E7]">
      <p class="text-xs text-[#787774]">Tahap Lanjut / Sidang</p>
      <p class="text-2xl font-bold text-[#37352F] mt-1">{{ $totalSiapSidang }}</p>
      <p class="text-[10px] text-[#787774] mt-0.5">Seminar TA & Sidang</p>
    </div>
  </div>

  @if ($unassignedMhs->isNotEmpty())
    <div class="notion-callout">
      <div class="text-xs">
        <p class="font-bold text-[#37352F]">Informasi: Terdapat {{ $unassignedMhs->count() }} Mahasiswa TA yang belum dibuatkan entri Tugas Akhir</p>
        <p class="text-[#787774] mt-0.5">
          Mahasiswa berikut terdata sebagai peserta TA aktif:
          <span class="font-semibold text-[#37352F]">{{ $unassignedMhs->pluck('user.name')->implode(', ') }}</span>
        </p>
      </div>
    </div>
  @endif

  <!-- Tabel Rekapitulasi Mahasiswa TA & Penetapan Pembimbing -->
  <div class="border border-[#E9E9E7] rounded-xl overflow-hidden bg-[#FFFFFF] shadow-xs">
    <div class="p-4 border-b border-[#E9E9E7] flex flex-col md:flex-row md:items-center justify-between gap-3">
      <div>
        <h3 class="text-sm font-bold text-[#37352F]">Rekapitulasi Tugas Akhir & Penunjukan Pembimbing</h3>
        <p class="text-xs text-[#787774]">Kelola dosen pembimbing utama dan pendamping sesuai keahlian riset</p>
      </div>

      <form method="GET" action="{{ route('koordinator.index') }}" class="flex items-center gap-2 text-xs">
        <select name="prodi" onchange="this.form.submit()" class="px-2.5 py-1 bg-[#FFFFFF] border border-[#E9E9E7] rounded text-xs text-[#37352F]">
          <option value="">Semua Program Studi</option>
          <option value="S1 Informatika" {{ request('prodi') === 'S1 Informatika' ? 'selected' : '' }}>S1 Informatika</option>
          <option value="S1 Sistem Informasi" {{ request('prodi') === 'S1 Sistem Informasi' ? 'selected' : '' }}>S1 Sistem Informasi</option>
          <option value="S1 Teknik Elektro" {{ request('prodi') === 'S1 Teknik Elektro' ? 'selected' : '' }}>S1 Teknik Elektro</option>
        </select>
      </form>
    </div>

    <div class="overflow-x-auto">
      <table class="w-full text-left text-xs">
        <thead class="bg-[#F7F7F5] text-[#787774] uppercase text-[10px] tracking-wider border-b border-[#E9E9E7]">
          <tr>
            <th class="py-2.5 px-4 font-semibold">Mahasiswa & NIM</th>
            <th class="py-2.5 px-4 font-semibold">Judul Tugas Akhir</th>
            <th class="py-2.5 px-4 font-semibold">Dosen Pembimbing 1</th>
            <th class="py-2.5 px-4 font-semibold">Dosen Pembimbing 2</th>
            <th class="py-2.5 px-4 font-semibold">Logbook</th>
            <th class="py-2.5 px-4 font-semibold">Status Tahap</th>
            <th class="py-2.5 px-4 text-right font-semibold">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-[#E9E9E7]">
          @forelse ($tugasAkhirList as $ta)
            <tr class="hover:bg-[#F7F7F5] transition-colors">
              <td class="py-3 px-4">
                @if ($ta->anggota && $ta->anggota->isNotEmpty())
                  @foreach ($ta->anggota as $member)
                    <p class="font-bold text-[#37352F] leading-tight">{{ $member->user->name ?? '-' }}</p>
                    <p class="text-[10px] text-[#787774] mb-1">NIM: {{ $member->nim }}</p>
                  @endforeach
                  @if ($ta->jumlahAnggota() > 1)
                    <span class="inline-block px-1.5 py-0.5 rounded text-[10px] font-medium bg-[#E3E2E0] text-[#37352F]">
                      Kelompok ({{ $ta->jumlahAnggota() }} Mhs)
                    </span>
                  @endif
                @else
                  <p class="font-bold text-[#37352F]">{{ $ta->mahasiswa->user->name ?? '-' }}</p>
                  <p class="text-[10px] text-[#787774]">{{ $ta->mahasiswa->nim ?? '-' }} • {{ $ta->prodi }}</p>
                @endif
              </td>
              <td class="py-3 px-4 max-w-xs">
                <p class="font-medium text-[#37352F] line-clamp-2 leading-relaxed">{{ $ta->judul }}</p>
              </td>
              <td class="py-3 px-4">
                <span class="font-medium text-[#37352F]">{{ $ta->pembimbing1->user->name ?? 'Belum Ditetapkan' }}</span>
              </td>
              <td class="py-3 px-4">
                <span class="text-[#787774]">{{ $ta->pembimbing2->user->name ?? '-' }}</span>
              </td>
              <td class="py-3 px-4 whitespace-nowrap">
                <span class="notion-pill-green">{{ $ta->logbooks->count() }} Sesi</span>
              </td>
              <td class="py-3 px-4 whitespace-nowrap">
                <span class="notion-pill-gray uppercase text-[10px]">{{ str_replace('_', ' ', $ta->status) }}</span>
              </td>
              <td class="py-3 px-4 text-right whitespace-nowrap space-x-1.5">
                <button @click="openKelompok({{ $ta->toJson() }})" class="notion-btn-secondary text-xs py-1 px-2.5">
                  Kelola Anggota ({{ $ta->jumlahAnggota() }})
                </button>
                <button @click="openAssign({{ $ta->toJson() }})" class="notion-btn-primary text-xs py-1 px-2.5">
                  Atur Pembimbing
                </button>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="7" class="py-8 text-center text-[#787774]">Tidak ada data mahasiswa TA.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    @if ($tugasAkhirList->hasPages())
      <div class="p-3 border-t border-[#E9E9E7] bg-[#F7F7F5]">
        {{ $tugasAkhirList->links() }}
      </div>
    @endif
  </div>

  <!-- MODAL: TETAPKAN DOSEN PEMBIMBING -->
  <div x-show="assignModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
    <div class="fixed inset-0 bg-[#0A0A0B]/40 backdrop-blur-xs" @click="assignModal = false"></div>
    <div class="flex min-h-full items-center justify-center p-4">
      <div class="relative w-full max-w-md bg-[#FFFFFF] border border-[#E9E9E7] rounded-xl shadow-xl p-6 z-10" @click.away="assignModal = false">
        
        <div class="flex items-center justify-between pb-3 border-b border-[#E9E9E7] mb-4">
          <h3 class="text-sm font-bold text-[#37352F]">Tetapkan Dosen Pembimbing Mahasiswa</h3>
          <button @click="assignModal = false" class="text-gray-400 hover:text-black">&times;</button>
        </div>

        <template x-if="selectedTa">
          <form action="{{ route('koordinator.assign') }}" method="POST" class="space-y-4 text-xs">
            @csrf
            <input type="hidden" name="tugas_akhir_id" :value="selectedTa.id">

            <div class="p-2.5 bg-[#F7F6F3] rounded border border-[#E9E9E7]">
              <p class="font-bold text-[#37352F]" x-text="selectedTa.mahasiswa?.user?.name"></p>
              <p class="text-[10px] text-[#787774]" x-text="selectedTa.judul"></p>
            </div>

            <div>
              <label class="block font-semibold uppercase tracking-wider text-[#787774] mb-1">Dosen Pembimbing 1 (Utama)</label>
              <select name="dosen_pembimbing_1_id" required class="w-full px-3 py-2 bg-[#FFFFFF] border border-[#E9E9E7] rounded text-[#37352F]">
                @foreach ($dosenList as $d)
                  <option value="{{ $d->id }}">{{ $d->user->name }} ({{ $d->kode_dosen }}) - {{ $d->prodi }}</option>
                @endforeach
              </select>
            </div>

            <div>
              <label class="block font-semibold uppercase tracking-wider text-[#787774] mb-1">Dosen Pembimbing 2 (Pendamping, Opsional)</label>
              <select name="dosen_pembimbing_2_id" class="w-full px-3 py-2 bg-[#FFFFFF] border border-[#E9E9E7] rounded text-[#37352F]">
                <option value="">-- Tanpa Pembimbing 2 --</option>
                @foreach ($dosenList as $d)
                  <option value="{{ $d->id }}">{{ $d->user->name }} ({{ $d->kode_dosen }}) - {{ $d->prodi }}</option>
                @endforeach
              </select>
            </div>

            <div class="flex items-center justify-end gap-2 pt-3 border-t border-[#E9E9E7]">
              <button type="button" @click="assignModal = false" class="notion-btn-secondary text-xs">Batal</button>
              <button type="submit" class="notion-btn-primary text-xs">Simpan Penetapan</button>
            </div>
          </form>
        </template>

      </div>
    </div>
  </div>

  <!-- MODAL: KELOLA ANGGOTA KELOMPOK TUGAS AKHIR -->
  <div x-show="kelompokModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
    <div class="fixed inset-0 bg-[#0A0A0B]/40 backdrop-blur-xs" @click="kelompokModal = false"></div>
    <div class="flex min-h-full items-center justify-center p-4">
      <div class="relative w-full max-w-lg bg-[#FFFFFF] border border-[#E9E9E7] rounded-xl shadow-xl p-6 z-10" @click.away="kelompokModal = false">
        
        <div class="flex items-center justify-between pb-3 border-b border-[#E9E9E7] mb-4">
          <div>
            <h3 class="text-sm font-bold text-[#37352F]">Kelola Anggota Kelompok Tugas Akhir</h3>
            <p class="text-[11px] text-[#787774]">Setiap TA dapat memiliki 1 hingga maksimal 3 mahasiswa setara (tanpa ketua).</p>
          </div>
          <button @click="kelompokModal = false" class="text-gray-400 hover:text-black">&times;</button>
        </div>

        <template x-if="selectedTa">
          <div class="space-y-5 text-xs">
            <!-- Info TA -->
            <div class="p-3 bg-[#F7F6F3] rounded border border-[#E9E9E7]">
              <div class="flex items-center gap-1.5 mb-1">
                <span class="notion-pill-gray text-[10px]" x-text="selectedTa.prodi"></span>
                <span class="text-[10px] text-[#787774]">TA ID: #<span x-text="selectedTa.id"></span></span>
              </div>
              <p class="font-bold text-[#37352F] text-xs leading-snug" x-text="selectedTa.judul"></p>
            </div>

            <!-- Daftar Anggota Saat Ini -->
            <div>
              <div class="flex items-center justify-between mb-2">
                <span class="font-bold uppercase tracking-wider text-[#787774] text-[10px]">
                  Anggota Saat Ini (<span x-text="selectedTa.anggota?.length ?? 0"></span>/3 Mahasiswa)
                </span>
                <span class="text-[10px] text-[#787774]">Minimal 1, Maksimal 3</span>
              </div>

              <div class="divide-y divide-[#E9E9E7] border border-[#E9E9E7] rounded-lg overflow-hidden bg-white">
                <template x-for="(member, index) in (selectedTa.anggota || [])" :key="member.id">
                  <div class="p-3 flex items-center justify-between hover:bg-[#F7F7F5] transition-colors">
                    <div class="flex items-center gap-2.5">
                      <div class="w-6 h-6 rounded-full bg-[#E3E2E0] flex items-center justify-center font-bold text-[10px] text-[#37352F]" x-text="index + 1"></div>
                      <div>
                        <p class="font-bold text-[#37352F]" x-text="member.user?.name ?? '-'"></p>
                        <p class="text-[10px] text-[#787774]">NIM: <span x-text="member.nim"></span> • <span x-text="member.user?.email"></span></p>
                      </div>
                    </div>

                    <!-- Tombol Hapus Anggota -->
                    <div>
                      <template x-if="(selectedTa.anggota?.length || 0) > 1">
                        <form :action="'{{ url('/koordinator/tugas-akhir') }}/' + selectedTa.id + '/anggota/' + member.id" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin mengeluarkan mahasiswa ini dari kelompok TA?');">
                          @csrf
                          @method('DELETE')
                          <button type="submit" class="px-2 py-1 text-[11px] font-semibold text-rose-600 hover:text-rose-700 hover:bg-rose-50 rounded border border-rose-200 transition-colors">
                            Hapus
                          </button>
                        </form>
                      </template>
                      <template x-if="(selectedTa.anggota?.length || 0) <= 1">
                        <span class="px-2 py-1 text-[10px] text-[#787774] bg-[#F7F7F5] rounded border border-[#E9E9E7]" title="Minimal 1 anggota tersisa">
                          Terkunci (Min 1)
                        </span>
                      </template>
                    </div>
                  </div>
                </template>
              </div>
            </div>

            <!-- Form Tambah Anggota Baru -->
            <div class="pt-3 border-t border-[#E9E9E7]">
              <!-- Jika sudah penuh 3 anggota -->
              <template x-if="(selectedTa.anggota?.length || 0) >= 3">
                <div class="p-3 rounded bg-amber-50 border border-amber-200 text-amber-800 text-[11px]">
                  <span>Kelompok ini sudah mencapai batas maksimal <strong>3 mahasiswa</strong>. Hapus anggota terlebih dahulu jika ingin mengganti.</span>
                </div>
              </template>

              <!-- Jika masih bisa tambah (< 3) -->
              <template x-if="(selectedTa.anggota?.length || 0) < 3">
                <form :action="'{{ url('/koordinator/tugas-akhir') }}/' + selectedTa.id + '/anggota'" method="POST" class="space-y-3">
                  @csrf
                  <div>
                    <label class="block font-semibold uppercase tracking-wider text-[#787774] text-[10px] mb-1">
                      Tambah Mahasiswa ke Kelompok (Slot Tersedia: <span x-text="3 - (selectedTa.anggota?.length || 0)"></span>)
                    </label>
                    <select name="mahasiswa_id" required class="w-full px-3 py-2 bg-[#FFFFFF] border border-[#E9E9E7] rounded text-[#37352F] text-xs">
                      <option value="">-- Pilih Mahasiswa Belum Berkelompok --</option>
                      @forelse ($eligibleMhsList as $emhs)
                        <option value="{{ $emhs->id }}" x-show="!selectedTa.anggota?.some(m => m.id === {{ $emhs->id }})">
                          {{ $emhs->user->name ?? '-' }} (NIM: {{ $emhs->nim }}) — {{ $emhs->prodi }}
                        </option>
                      @empty
                        <option disabled value="">Tidak ada mahasiswa yang eligible/belum berkelompok</option>
                      @endforelse
                    </select>
                    <p class="text-[10px] text-[#787774] mt-1">Hanya menampilkan mahasiswa TA aktif yang belum terdaftar di kelompok manapun.</p>
                  </div>

                  <div class="flex items-center justify-end gap-2">
                    <button type="submit" class="notion-btn-primary text-xs">
                      Tambahkan ke Kelompok
                    </button>
                  </div>
                </form>
              </template>
            </div>

            <div class="flex justify-end pt-2 border-t border-[#E9E9E7]">
              <button type="button" @click="kelompokModal = false" class="notion-btn-secondary text-xs">Tutup</button>
            </div>
          </div>
        </template>

      </div>
    </div>
  </div>

</div>
@endsection

@extends('layouts.notion')

@section('title', 'Janji Bimbingan')
@section('breadcrumb', 'Janji Bimbingan')

@section('content')
<div class="space-y-6" x-data="{ 
  openModal: false,
  completeModalOpen: false,
  selectedJanji: null,
  openComplete(item) {
    this.selectedJanji = item;
    this.completeModalOpen = true;
  }
}">

  <!-- Header Notion -->
  <div class="border-b border-[#E9E9E7] pb-5 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
      <div class="flex items-center gap-2 mb-1">
        <h1 class="text-2xl font-bold tracking-tight text-[#37352F]">Janji Bimbingan Tugas Akhir</h1>
      </div>
      <p class="text-xs text-[#787774]">Koordinasi jadwal bimbingan tatap muka (Ruang Dosen GD 512/514) atau daring (Zoom IT Del) antara mahasiswa dan dosen.</p>
    </div>

    <button @click="openModal = true" class="notion-btn-primary text-xs">
      Ajukan Janji Bimbingan
    </button>
  </div>

  <!-- Sesi Menunggu Persetujuan (Pending Approval Cards) -->
  @php
    $pendingList = $janjiList->where('status', 'menunggu_persetujuan');
  @endphp

  @if ($pendingList->isNotEmpty())
    <div class="border border-[#F3E0A2] rounded-xl p-5 bg-[#FDF5E6]/40 shadow-xs">
      <div class="flex items-center justify-between mb-3">
        <div class="flex items-center gap-2">
          <span class="w-2.5 h-2.5 rounded-full bg-amber-500 animate-pulse"></span>
          <h3 class="text-sm font-bold text-[#37352F]">Menunggu Persetujuan (Pending Approval)</h3>
        </div>
        <span class="text-[11px] text-[#B86200] font-semibold">{{ $pendingList->count() }} Permintaan</span>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-xs">
        @foreach ($pendingList as $item)
          <div class="bg-[#FFFFFF] border border-[#F3E0A2] rounded-lg p-4 flex flex-col justify-between shadow-xs">
            <div>
              <div class="flex items-center justify-between text-[11px] text-[#787774] mb-1.5">
                <span class="notion-pill-orange">Inisiator: {{ ucfirst($item->inisiator) }}</span>
                <span class="font-bold text-[#37352F]">{{ $item->tanggal_waktu->format('d M Y, H:i') }} WIB</span>
              </div>
              <h4 class="font-bold text-[#37352F] text-xs leading-snug">{{ $item->topik_pembahasan }}</h4>
              <p class="text-[11px] text-[#787774] mt-1.5">
                @if ($item->tugasAkhir->anggota && $item->tugasAkhir->anggota->isNotEmpty())
                  Mahasiswa: <strong class="text-[#37352F]">{{ $item->tugasAkhir->anggota->pluck('user.name')->implode(', ') }}</strong> • 
                @else
                  Mahasiswa: <strong class="text-[#37352F]">{{ $item->tugasAkhir->mahasiswa->user->name ?? '-' }}</strong> • 
                @endif
                Dosen: <strong class="text-[#37352F]">{{ $item->dosen->user->name ?? '-' }}</strong>
              </p>
              <div class="mt-2 text-[11px] text-[#787774] bg-[#F7F7F5] px-2 py-1 rounded inline-block">
                {{ $item->metode === 'offline' ? 'Tatap Muka: ' : 'Daring: ' }} {{ $item->lokasi_atau_link ?? '-' }}
              </div>
            </div>

            <!-- Tombol Setujui / Tolak -->
            <div class="mt-4 pt-3 border-t border-[#E9E9E7] flex items-center justify-end gap-2">
              @if (auth()->user()->isDosen() && auth()->user()->dosenProfile?->id === $item->dosen_id)
                <form action="{{ route('bimbingan.status', $item->id) }}" method="POST">
                  @csrf
                  @method('PATCH')
                  <input type="hidden" name="status" value="ditolak">
                  <button type="submit" class="notion-btn-secondary text-xs py-1 px-2.5">
                    Tolak
                  </button>
                </form>

                <form action="{{ route('bimbingan.status', $item->id) }}" method="POST">
                  @csrf
                  @method('PATCH')
                  <input type="hidden" name="status" value="disetujui">
                  <button type="submit" class="notion-btn-primary text-xs py-1 px-3">
                    Setujui Jadwal
                  </button>
                </form>
              @else
                <span class="text-[10px] text-[#787774] italic">Menunggu konfirmasi {{ $item->dosen->user->name ?? 'Dosen' }}</span>
              @endif
            </div>
          </div>
        @endforeach
      </div>
    </div>
  @endif

  <!-- Tabel Jadwal Terkonfirmasi & Riwayat -->
  <div class="border border-[#E9E9E7] rounded-xl overflow-hidden bg-[#FFFFFF] shadow-xs">
    <div class="p-4 border-b border-[#E9E9E7] flex items-center justify-between">
      <div>
        <h3 class="text-sm font-bold text-[#37352F]">Daftar Jadwal Bimbingan Terkonfirmasi & Riwayat</h3>
        <p class="text-xs text-[#787774]">Seluruh sesi bimbingan yang telah disepakati bersama</p>
      </div>
      <span class="text-xs text-[#787774]">Total: {{ $janjiList->total() }} Sesi</span>
    </div>

    <div class="overflow-x-auto">
      <table class="w-full text-left text-xs">
        <thead class="bg-[#F7F7F5] text-[#787774] uppercase text-[10px] tracking-wider border-b border-[#E9E9E7]">
          <tr>
            <th class="py-2.5 px-4 font-semibold">Waktu Pelaksanaan</th>
            <th class="py-2.5 px-4 font-semibold">Mahasiswa & Dosen</th>
            <th class="py-2.5 px-4 font-semibold">Topik Bimbingan</th>
            <th class="py-2.5 px-4 font-semibold">Metode & Lokasi / Link</th>
            <th class="py-2.5 px-4 font-semibold">Status</th>
            <th class="py-2.5 px-4 text-right font-semibold">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-[#E9E9E7]">
          @forelse ($janjiList as $janji)
            <tr class="hover:bg-[#F7F7F5] transition-colors">
              <td class="py-3 px-4 whitespace-nowrap">
                <span class="font-bold text-[#37352F] block">{{ $janji->tanggal_waktu->format('d M Y') }}</span>
                <span class="text-[11px] text-[#787774]">{{ $janji->tanggal_waktu->format('H:i') }} WIB</span>
              </td>
              <td class="py-3 px-4">
                @if ($janji->tugasAkhir->anggota && $janji->tugasAkhir->anggota->isNotEmpty())
                  @foreach ($janji->tugasAkhir->anggota as $member)
                    <p class="font-semibold text-[#37352F] text-xs leading-tight">{{ $member->user->name ?? '-' }}</p>
                    <p class="text-[10px] text-[#787774] mb-1">NIM: {{ $member->nim }}</p>
                  @endforeach
                @else
                  <p class="font-semibold text-[#37352F]">{{ $janji->tugasAkhir->mahasiswa->user->name ?? '-' }}</p>
                  <p class="text-[10px] text-[#787774]">{{ $janji->tugasAkhir->mahasiswa->nim ?? '-' }}</p>
                @endif
                <p class="text-[10px] text-[#787774] mt-0.5 font-medium">Dosen: {{ $janji->dosen->user->name ?? '-' }}</p>
              </td>
              <td class="py-3 px-4 max-w-sm">
                <p class="font-medium text-[#37352F]">{{ $janji->topik_pembahasan }}</p>
                @if ($janji->status === 'selesai' && $janji->catatan_hasil)
                  <div class="mt-2 p-2 bg-[#F7F7F5] rounded border border-[#E9E9E7] text-[11px]">
                    <div class="font-bold text-[#787774] text-[10px] mb-0.5 uppercase tracking-wider">
                      Catatan hasil bimbingan:
                    </div>
                    <p class="text-[#37352F] whitespace-pre-line leading-relaxed">{{ $janji->catatan_hasil }}</p>
                  </div>
                @elseif ($janji->status === 'ditolak' && $janji->alasan_penolakan)
                  <div class="mt-2 p-2 bg-rose-50 rounded border border-rose-200 text-[11px] text-rose-800">
                    <span class="font-bold text-[10px] block uppercase tracking-wider text-rose-700">Alasan Penolakan:</span>
                    <p class="leading-relaxed">{{ $janji->alasan_penolakan }}</p>
                  </div>
                @endif
              </td>
              <td class="py-3 px-4 text-[#787774]">
                <span class="notion-pill-gray text-[10px] block mb-0.5">{{ ucfirst($janji->metode) }}</span>
                <span class="text-[11px] text-[#37352F]">{{ $janji->lokasi_atau_link ?? '-' }}</span>
              </td>
              <td class="py-3 px-4 whitespace-nowrap">
                @if ($janji->status === 'disetujui')
                  <span class="notion-pill-green">Terkonfirmasi</span>
                @elseif ($janji->status === 'menunggu_persetujuan')
                  <span class="notion-pill-orange">Menunggu</span>
                @elseif ($janji->status === 'ditolak')
                  <span class="notion-pill-red">Ditolak</span>
                @else
                  <span class="notion-pill-gray">Selesai</span>
                @endif
              </td>
              <td class="py-3 px-4 text-right whitespace-nowrap">
                @if ($janji->status === 'disetujui' && auth()->user()->isDosen() && auth()->user()->dosenProfile?->id === $janji->dosen_id)
                  <button @click="openComplete({{ $janji->toJson() }})" class="notion-btn-secondary text-[11px] py-1 px-2.5 inline-flex items-center">
                    Selesaikan & Catat
                  </button>
                @elseif ($janji->status === 'selesai')
                  <span class="text-[11px] text-[#2A7E4B] font-semibold">Telah Selesai</span>
                @else
                  <span class="text-[11px] text-[#787774]">-</span>
                @endif
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="6" class="py-8 text-center text-[#787774]">Belum ada jadwal bimbingan yang terdata.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    @if ($janjiList->hasPages())
      <div class="p-3 border-t border-[#E9E9E7] bg-[#F7F7F5]">
        {{ $janjiList->links() }}
      </div>
    @endif
  </div>

  <!-- MODAL: AJUKAN JANJI BIMBINGAN BARU -->
  <div x-show="openModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
    <div class="fixed inset-0 bg-[#0A0A0B]/40 backdrop-blur-xs" @click="openModal = false"></div>
    <div class="flex min-h-full items-center justify-center p-4">
      <div class="relative w-full max-w-lg bg-[#FFFFFF] border border-[#E9E9E7] rounded-xl shadow-xl p-6 z-10" @click.away="openModal = false">
        
        <div class="flex items-center justify-between pb-3 border-b border-[#E9E9E7] mb-4">
          <div>
            <h3 class="text-base font-bold text-[#37352F]">Ajukan Jadwal Bimbingan</h3>
            <p class="text-xs text-[#787774]">Pilih waktu, topik dan metode tatap muka / daring</p>
          </div>
          <button @click="openModal = false" class="text-gray-400 hover:text-black">&times;</button>
        </div>

        <form action="{{ route('bimbingan.store') }}" method="POST" class="space-y-4 text-xs">
          @csrf

          @if (auth()->user()->isMahasiswa() && $myTugasAkhir)
            <input type="hidden" name="tugas_akhir_id" value="{{ $myTugasAkhir->id }}">
          @elseif (auth()->user()->isDosen() || auth()->user()->isKaprodi())
            <div>
              <label class="block font-semibold uppercase tracking-wider text-[#787774] mb-1">Pilih Mahasiswa Bimbingan</label>
              <select name="tugas_akhir_id" required class="w-full px-3 py-2 bg-[#FFFFFF] border border-[#E9E9E7] rounded text-[#37352F]">
                @forelse (($bimbinganTaList ?? []) as $ta)
                  @php
                    $namaMhs = $ta->anggota && $ta->anggota->isNotEmpty()
                      ? $ta->anggota->map(fn($m) => ($m->user->name ?? '-') . ' (' . $m->nim . ')')->implode(', ')
                      : (($ta->mahasiswa->user->name ?? 'Mahasiswa') . ' (' . ($ta->mahasiswa->nim ?? '-') . ')');
                  @endphp
                  <option value="{{ $ta->id }}">{{ $namaMhs }} — {{ Str::limit($ta->judul, 50) }}</option>
                @empty
                  <option disabled value="">Belum ada mahasiswa bimbingan aktif</option>
                @endforelse
              </select>
            </div>
          @endif

          @if ((auth()->user()->isDosen() || auth()->user()->isKaprodi()) && auth()->user()->dosenProfile)
            <input type="hidden" name="dosen_id" value="{{ auth()->user()->dosenProfile->id }}">
          @else
            <div>
              <label class="block font-semibold uppercase tracking-wider text-[#787774] mb-1">Dosen Pembimbing</label>
              <select name="dosen_id" required class="w-full px-3 py-2 bg-[#FFFFFF] border border-[#E9E9E7] rounded text-[#37352F]">
                @foreach ($dosenList as $dosen)
                  <option value="{{ $dosen->id }}">{{ $dosen->user->name }} ({{ $dosen->kode_dosen }})</option>
                @endforeach
              </select>
            </div>
          @endif

          <div>
            <label class="block font-semibold uppercase tracking-wider text-[#787774] mb-1">Tanggal & Jam Pertemuan</label>
            <input type="datetime-local" name="tanggal_waktu" required class="w-full px-3 py-2 bg-[#FFFFFF] border border-[#E9E9E7] rounded text-[#37352F]">
          </div>

          <div>
            <label class="block font-semibold uppercase tracking-wider text-[#787774] mb-1">Topik Pembahasan</label>
            <input type="text" name="topik_pembahasan" required placeholder="Contoh: Konsultasi Bab 4 & Hasil Training Model..." class="w-full px-3 py-2 bg-[#FFFFFF] border border-[#E9E9E7] rounded text-[#37352F]">
          </div>

          <div class="grid grid-cols-2 gap-3">
            <div>
              <label class="block font-semibold uppercase tracking-wider text-[#787774] mb-1">Metode</label>
              <select name="metode" required class="w-full px-3 py-2 bg-[#FFFFFF] border border-[#E9E9E7] rounded text-[#37352F]">
                <option value="offline">Tatap Muka (Offline)</option>
                <option value="online">Daring (Online)</option>
              </select>
            </div>
            <div>
              <label class="block font-semibold uppercase tracking-wider text-[#787774] mb-1">Lokasi Fisik / Link Pertemuan</label>
              <input type="text" name="lokasi_atau_link" placeholder="Ruang Dosen GD 512 / Link Zoom..." class="w-full px-3 py-2 bg-[#FFFFFF] border border-[#E9E9E7] rounded text-[#37352F]">
            </div>
          </div>

          <div class="flex items-center justify-end gap-2 pt-3 border-t border-[#E9E9E7]">
            <button type="button" @click="openModal = false" class="notion-btn-secondary text-xs">Batal</button>
            <button type="submit" class="notion-btn-primary text-xs">Kirimkan Pengajuan</button>
          </div>
        </form>

      </div>
    </div>
  </div>

  <!-- MODAL: SELESAIKAN SESI BIMBINGAN & CATATAN HASIL (DOSEN) -->
  <div x-show="completeModalOpen" x-cloak class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
    <div class="fixed inset-0 bg-[#0A0A0B]/40 backdrop-blur-xs" @click="completeModalOpen = false"></div>
    <div class="flex min-h-full items-center justify-center p-4">
      <div class="relative w-full max-w-lg bg-[#FFFFFF] border border-[#E9E9E7] rounded-xl shadow-xl p-6 z-10" @click.away="completeModalOpen = false">
        
        <div class="flex items-center justify-between pb-3 border-b border-[#E9E9E7] mb-4">
          <div>
            <h3 class="text-sm font-bold text-[#37352F]">Selesaikan Sesi Bimbingan & Catatan Hasil</h3>
            <p class="text-[11px] text-[#787774] mt-0.5">Dosen pembimbing wajib mengisi catatan ringkasan evaluasi, progres, atau arahan revisi</p>
          </div>
          <button @click="completeModalOpen = false" class="text-gray-400 hover:text-black">&times;</button>
        </div>

        <template x-if="selectedJanji">
          <form :action="'/bimbingan/' + selectedJanji.id + '/status'" method="POST" class="space-y-4 text-xs">
            @csrf
            @method('PATCH')
            <input type="hidden" name="status" value="selesai">

            <div class="p-3 bg-[#F7F7F5] rounded border border-[#E9E9E7] text-[11px]">
              <p class="font-bold text-[#37352F]" x-text="selectedJanji.topik_pembahasan"></p>
              <p class="text-[#787774] mt-0.5">
                Mahasiswa: <span class="font-semibold text-[#37352F]" x-text="selectedJanji.tugas_akhir?.mahasiswa?.user?.name ?? '-'"></span>
              </p>
            </div>

            <div>
              <label class="block font-semibold uppercase tracking-wider text-[#787774] mb-1">
                Catatan Hasil Bimbingan <span class="text-red-500">* (Wajib diisi, min 10 karakter)</span>
              </label>
              <textarea name="catatan_hasil" rows="4" required minlength="10" maxlength="2000"
                        placeholder="Tuliskan evaluasi progres, poin revisi naskah/kode, atau action item yang harus dikerjakan mahasiswa untuk pertemuan berikutnya..."
                        class="w-full px-3 py-2 bg-[#FFFFFF] border border-[#E9E9E7] rounded text-[#37352F]"></textarea>
              <p class="text-[10px] text-[#787774] mt-1">Catatan ini akan tampil pada riwayat bimbingan mahasiswa dan dosen pembimbing.</p>
            </div>

            <div class="flex items-center justify-end gap-2 pt-3 border-t border-[#E9E9E7]">
              <button type="button" @click="completeModalOpen = false" class="notion-btn-secondary text-xs">Batal</button>
              <button type="submit" class="notion-btn-primary text-xs">Simpan & Tandai Selesai</button>
            </div>
          </form>
        </template>

      </div>
    </div>
  </div>

</div>
@endsection

@extends('layouts.notion')

@section('title', 'Panel Koordinator TA')
@section('breadcrumb', 'Koordinator TA')

@section('content')
<div class="space-y-6" x-data="{ assignModal: false, selectedTa: null, openAssign(ta) { this.selectedTa = ta; this.assignModal = true; } }">

  <!-- Header Notion -->
  <div class="border-b border-[#E9E9E7] pb-5 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
      <div class="flex items-center gap-2 mb-1">
        <span class="text-2xl">⚙️</span>
        <h1 class="text-2xl font-bold tracking-tight text-[#37352F]">Panel Koordinator Tugas Akhir FITE</h1>
      </div>
      <p class="text-xs text-[#787774]">Penetapan Dosen Pembimbing 1 & 2 serta pengawasan rekapitulasi capaian bab tugas akhir mahasiswa.</p>
    </div>
  </div>

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
          <option value="S1 Teknik Komputer" {{ request('prodi') === 'S1 Teknik Komputer' ? 'selected' : '' }}>S1 Teknik Komputer</option>
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
                <p class="font-bold text-[#37352F]">{{ $ta->mahasiswa->user->name ?? '-' }}</p>
                <p class="text-[10px] text-[#787774]">{{ $ta->mahasiswa->nim ?? '-' }} • {{ $ta->prodi }}</p>
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
              <td class="py-3 px-4 text-right whitespace-nowrap">
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

</div>
@endsection

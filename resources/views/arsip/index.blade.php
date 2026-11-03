@extends('layouts.notion')

@section('title', 'Pustaka Arsip Tugas Akhir')
@section('breadcrumb', 'Pustaka Arsip TA')

@section('content')
<div class="space-y-6" x-data="{ uploadModal: false }">

  <!-- Header Notion -->
  <div class="border-b border-[#E9E9E7] pb-5 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
      <div class="flex items-center gap-2 mb-1">
        <span class="text-2xl">📚</span>
        <h1 class="text-2xl font-bold tracking-tight text-[#37352F]">Pustaka Arsip Tugas Akhir FITE</h1>
      </div>
      <p class="text-xs text-[#787774]">Basis repositori digital naskah ilmiah skripsi alumni FITE IT Del yang dapat diakses oleh seluruh mahasiswa & dosen.</p>
    </div>

    @if (auth()->user()->isTA())
      <button @click="uploadModal = true" class="notion-btn-primary text-xs">
        <span>+</span> Unggah Arsip Tugas Akhir
      </button>
    @else
      <div class="text-[11px] text-[#787774] bg-[#F7F7F5] px-2.5 py-1 rounded border border-[#E9E9E7]">
        Akses: <strong>Read-Only Katalog</strong>
      </div>
    @endif
  </div>

  <!-- Search Bar & Filters Form -->
  <div class="border border-[#E9E9E7] rounded-xl p-5 bg-[#FFFFFF] shadow-xs space-y-4">
    <form method="GET" action="{{ route('arsip.index') }}" class="space-y-3">
      
      <!-- Big Search Input -->
      <div class="relative">
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari berdasarkan judul naskah, nama penulis, NIM, dosen pembimbing, atau kata kunci..."
               class="w-full pl-10 pr-4 py-2.5 bg-[#F7F7F5] border border-[#E9E9E7] rounded-lg text-xs text-[#37352F] placeholder-gray-400 focus:outline-none focus:bg-white focus:border-[#2C2C2F]">
        <svg class="w-4 h-4 text-[#787774] absolute left-3.5 top-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <circle cx="11" cy="11" r="8"></circle>
          <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
        </svg>
      </div>

      <!-- Filter Pills Row -->
      <div class="flex flex-wrap items-center justify-between gap-3 text-xs pt-1">
        
        <!-- Filter Prodi Pills -->
        <div class="flex flex-wrap items-center gap-1.5">
          <span class="text-[#787774] font-semibold mr-1">Prodi:</span>
          <a href="{{ route('arsip.index', array_merge(request()->query(), ['prodi' => 'all'])) }}"
             class="px-2.5 py-1 rounded-full border border-[#E9E9E7] transition-colors {{ !request('prodi') || request('prodi') === 'all' ? 'bg-[#2C2C2F] text-white' : 'bg-[#F7F7F5] text-[#37352F] hover:bg-[#EBEBE9]' }}">
            Semua Prodi
          </a>
          <a href="{{ route('arsip.index', array_merge(request()->query(), ['prodi' => 'S1 Informatika'])) }}"
             class="px-2.5 py-1 rounded-full border border-[#E9E9E7] transition-colors {{ request('prodi') === 'S1 Informatika' ? 'bg-[#2C2C2F] text-white' : 'bg-[#F7F7F5] text-[#37352F] hover:bg-[#EBEBE9]' }}">
            S1 Informatika
          </a>
          <a href="{{ route('arsip.index', array_merge(request()->query(), ['prodi' => 'S1 Sistem Informasi'])) }}"
             class="px-2.5 py-1 rounded-full border border-[#E9E9E7] transition-colors {{ request('prodi') === 'S1 Sistem Informasi' ? 'bg-[#2C2C2F] text-white' : 'bg-[#F7F7F5] text-[#37352F] hover:bg-[#EBEBE9]' }}">
            S1 Sistem Informasi
          </a>
          <a href="{{ route('arsip.index', array_merge(request()->query(), ['prodi' => 'S1 Teknik Komputer'])) }}"
             class="px-2.5 py-1 rounded-full border border-[#E9E9E7] transition-colors {{ request('prodi') === 'S1 Teknik Komputer' ? 'bg-[#2C2C2F] text-white' : 'bg-[#F7F7F5] text-[#37352F] hover:bg-[#EBEBE9]' }}">
            S1 Teknik Komputer
          </a>
        </div>

        <!-- Filter Tahun Dropdown -->
        <div class="flex items-center gap-2">
          <select name="tahun" onchange="this.form.submit()" class="px-2.5 py-1 bg-[#FFFFFF] border border-[#E9E9E7] rounded text-xs text-[#37352F] focus:outline-none">
            <option value="all">Semua Tahun Lulus</option>
            @foreach ($availableYears as $year)
              <option value="{{ $year }}" {{ request('tahun') == $year ? 'selected' : '' }}>Tahun {{ $year }}</option>
            @endforeach
          </select>
        </div>

      </div>

    </form>
  </div>

  <!-- Notion Database View: Tabel Naskah Skripsi FITE -->
  <div class="border border-[#E9E9E7] rounded-xl overflow-hidden bg-[#FFFFFF] shadow-xs">
    <div class="overflow-x-auto">
      <table class="w-full text-left text-xs">
        <thead class="bg-[#F7F7F5] text-[#787774] uppercase text-[10px] tracking-wider border-b border-[#E9E9E7]">
          <tr>
            <th class="py-2.5 px-4 font-semibold">Judul Naskah Skripsi</th>
            <th class="py-2.5 px-4 font-semibold">Penulis & NIM</th>
            <th class="py-2.5 px-4 font-semibold">Prodi & Tahun</th>
            <th class="py-2.5 px-4 font-semibold">Dosen Pembimbing</th>
            <th class="py-2.5 px-4 font-semibold">Kata Kunci</th>
            <th class="py-2.5 px-4 text-right font-semibold">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-[#E9E9E7]">
          @forelse ($arsipList as $item)
            <tr class="hover:bg-[#F7F7F5] transition-colors">
              <td class="py-3 px-4 max-w-sm">
                <p class="font-bold text-[#37352F] leading-snug">{{ $item->judul }}</p>
                <p class="text-[11px] text-[#787774] line-clamp-1 mt-0.5">{{ $item->abstrak }}</p>
              </td>
              <td class="py-3 px-4 whitespace-nowrap">
                <p class="font-semibold text-[#37352F]">{{ $item->penulis }}</p>
                <p class="text-[10px] text-[#787774]">{{ $item->nim }}</p>
              </td>
              <td class="py-3 px-4 whitespace-nowrap">
                <span class="notion-pill-gray text-[10px] block mb-0.5">{{ $item->prodi }}</span>
                <span class="text-[11px] text-[#787774]">Lulus: {{ $item->tahun_lulus }}</span>
              </td>
              <td class="py-3 px-4 text-[#37352F]">
                <p class="font-medium">{{ $item->nama_dospem_1 }}</p>
                @if ($item->nama_dospem_2)
                  <p class="text-[10px] text-[#787774]">{{ $item->nama_dospem_2 }}</p>
                @endif
              </td>
              <td class="py-3 px-4">
                <div class="flex flex-wrap gap-1">
                  @foreach (explode(',', $item->kata_kunci) as $kw)
                    <span class="notion-pill-gray text-[9px]">{{ trim($kw) }}</span>
                  @endforeach
                </div>
              </td>
              <td class="py-3 px-4 text-right whitespace-nowrap">
                <a href="{{ asset('storage/' . $item->file_dokumen) }}" target="_blank" class="notion-btn-secondary text-xs py-1 px-2.5">
                  <span>📄</span> Unduh PDF
                </a>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="6" class="py-8 text-center text-[#787774]">
                Tidak ada arsip yang cocok dengan kriteria pencarian.
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    @if ($arsipList->hasPages())
      <div class="p-3 border-t border-[#E9E9E7] bg-[#F7F7F5]">
        {{ $arsipList->links() }}
      </div>
    @endif
  </div>

  @if (auth()->user()->isTA())
  <!-- MODAL: UNGGAH ARSIP TUGAS AKHIR ALUMNI (Teaching Assistant) -->
  <div x-show="uploadModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
    <div class="fixed inset-0 bg-[#0A0A0B]/40 backdrop-blur-xs" @click="uploadModal = false"></div>
    <div class="flex min-h-full items-center justify-center p-4">
      <div class="relative w-full max-w-lg bg-[#FFFFFF] border border-[#E9E9E7] rounded-xl shadow-xl p-6 z-10" @click.away="uploadModal = false">
        
        <div class="flex items-center justify-between pb-3 border-b border-[#E9E9E7] mb-4">
          <div>
            <h3 class="text-sm font-bold text-[#37352F]">Unggah Naskah Soft-Copy Arsip Tugas Akhir</h3>
            <p class="text-xs text-[#787774]">Teaching Assistant mengarsipkan naskah akhir alumni FITE</p>
          </div>
          <button @click="uploadModal = false" class="text-gray-400 hover:text-black">&times;</button>
        </div>

        <form action="{{ route('arsip.store') }}" method="POST" enctype="multipart/form-data" class="space-y-3.5 text-xs">
          @csrf

          <div>
            <label class="block font-semibold uppercase tracking-wider text-[#787774] mb-1">Judul Lengkap Tugas Akhir</label>
            <input type="text" name="judul" required placeholder="Judul naskah tugas akhir alumni..." class="w-full px-3 py-2 bg-[#FFFFFF] border border-[#E9E9E7] rounded text-[#37352F]">
          </div>

          <div class="grid grid-cols-2 gap-3">
            <div>
              <label class="block font-semibold uppercase tracking-wider text-[#787774] mb-1">Nama Penulis Alumni</label>
              <input type="text" name="penulis" required placeholder="Contoh: Grace Hutapea" class="w-full px-3 py-2 bg-[#FFFFFF] border border-[#E9E9E7] rounded text-[#37352F]">
            </div>
            <div>
              <label class="block font-semibold uppercase tracking-wider text-[#787774] mb-1">NIM</label>
              <input type="text" name="nim" required placeholder="Contoh: 11S20011" class="w-full px-3 py-2 bg-[#FFFFFF] border border-[#E9E9E7] rounded text-[#37352F]">
            </div>
          </div>

          <div class="grid grid-cols-2 gap-3">
            <div>
              <label class="block font-semibold uppercase tracking-wider text-[#787774] mb-1">Program Studi</label>
              <select name="prodi" required class="w-full px-3 py-2 bg-[#FFFFFF] border border-[#E9E9E7] rounded text-[#37352F]">
                <option value="S1 Informatika">S1 Informatika</option>
                <option value="S1 Sistem Informasi">S1 Sistem Informasi</option>
                <option value="S1 Teknik Komputer">S1 Teknik Komputer</option>
              </select>
            </div>
            <div>
              <label class="block font-semibold uppercase tracking-wider text-[#787774] mb-1">Tahun Lulus</label>
              <input type="number" name="tahun_lulus" value="2025" min="2010" max="2030" required class="w-full px-3 py-2 bg-[#FFFFFF] border border-[#E9E9E7] rounded text-[#37352F]">
            </div>
          </div>

          <div class="grid grid-cols-2 gap-3">
            <div>
              <label class="block font-semibold uppercase tracking-wider text-[#787774] mb-1">Nama Dospem 1</label>
              <input type="text" name="nama_dospem_1" required placeholder="Dr. Johannes Harungguan" class="w-full px-3 py-2 bg-[#FFFFFF] border border-[#E9E9E7] rounded text-[#37352F]">
            </div>
            <div>
              <label class="block font-semibold uppercase tracking-wider text-[#787774] mb-1">Nama Dospem 2 (Opsional)</label>
              <input type="text" name="nama_dospem_2" placeholder="Marlina Valentina, M.Sc." class="w-full px-3 py-2 bg-[#FFFFFF] border border-[#E9E9E7] rounded text-[#37352F]">
            </div>
          </div>

          <div>
            <label class="block font-semibold uppercase tracking-wider text-[#787774] mb-1">Kata Kunci (Pisahkan dengan koma)</label>
            <input type="text" name="kata_kunci" required placeholder="IoT, Deep Learning, Danau Toba" class="w-full px-3 py-2 bg-[#FFFFFF] border border-[#E9E9E7] rounded text-[#37352F]">
          </div>

          <div>
            <label class="block font-semibold uppercase tracking-wider text-[#787774] mb-1">Abstrak Ringkas</label>
            <textarea name="abstrak" rows="3" required placeholder="Tuliskan ringkasan tujuan, metodologi, dan hasil utama..." class="w-full px-3 py-2 bg-[#FFFFFF] border border-[#E9E9E7] rounded text-[#37352F]"></textarea>
          </div>

          <div>
            <label class="block font-semibold uppercase tracking-wider text-[#787774] mb-1">File Naskah Lengkap (PDF Maks 50MB)</label>
            <input type="file" name="file_dokumen" required accept=".pdf" class="w-full px-3 py-2 bg-[#F7F7F5] border border-[#E9E9E7] rounded text-[#37352F]">
          </div>

          <div class="flex items-center justify-end gap-2 pt-3 border-t border-[#E9E9E7]">
            <button type="button" @click="uploadModal = false" class="notion-btn-secondary text-xs">Batal</button>
            <button type="submit" class="notion-btn-primary text-xs">Simpan ke Pustaka</button>
          </div>
        </form>

      </div>
    </div>
  </div>
  @endif

</div>
@endsection

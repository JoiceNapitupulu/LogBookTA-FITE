@extends('layouts.notion')

@section('title', 'Manajemen Ujian TA')
@section('breadcrumb', 'Ujian TA')

@section('content')
<div class="space-y-6" x-data="{ 
  activeTab: '{{ auth()->user()->isBaak() ? 'baak' : (auth()->user()->isTA() ? 'ta' : (auth()->user()->isKoordinator() ? 'koordinator' : 'mahasiswa')) }}',
  selectedUjian: null,
  verifyModalOpen: false,
  scheduleModalOpen: false,
  facilityModalOpen: false,
  openVerify(item) {
    this.selectedUjian = item;
    this.verifyModalOpen = true;
  },
  openSchedule(item) {
    this.selectedUjian = item;
    this.scheduleModalOpen = true;
  },
  openFacility(item) {
    this.selectedUjian = item;
    this.facilityModalOpen = true;
  }
}">

  <!-- Header Notion -->
  <div class="border-b border-[#E9E9E7] pb-5 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
      <div class="flex items-center gap-2 mb-1">
        <span class="text-2xl">🏛️</span>
        <h1 class="text-2xl font-bold tracking-tight text-[#37352F]">Alur Pendaftaran & Manajemen Ujian TA</h1>
      </div>
      <p class="text-xs text-[#787774]">Kolaborasi 4 pilar: Mahasiswa TA &rarr; Teaching Assistant (Verifikator) &rarr; Koordinator TA (Jadwal & Penguji) &rarr; BAAK (Ruang & Zoom).</p>
    </div>

    <!-- Tab Switcher ala Notion Pills -->
    <div class="flex items-center bg-[#F7F7F5] p-1 rounded border border-[#E9E9E7] text-xs">
      <button @click="activeTab = 'mahasiswa'" :class="activeTab === 'mahasiswa' ? 'bg-[#FFFFFF] text-[#37352F] shadow-xs font-semibold' : 'text-[#787774] hover:text-[#37352F]'" class="px-3 py-1 rounded transition-colors">
        1. Mahasiswa TA
      </button>
      @if (!auth()->user()->isMahasiswa())
      <button @click="activeTab = 'ta'" :class="activeTab === 'ta' ? 'bg-[#FFFFFF] text-[#37352F] shadow-xs font-semibold' : 'text-[#787774] hover:text-[#37352F]'" class="px-3 py-1 rounded transition-colors">
        2. Teaching Assistant
      </button>
      <button @click="activeTab = 'koordinator'" :class="activeTab === 'koordinator' ? 'bg-[#FFFFFF] text-[#37352F] shadow-xs font-semibold' : 'text-[#787774] hover:text-[#37352F]'" class="px-3 py-1 rounded transition-colors">
        3. Koordinator TA
      </button>
      <button @click="activeTab = 'baak'" :class="activeTab === 'baak' ? 'bg-[#FFFFFF] text-[#37352F] shadow-xs font-semibold' : 'text-[#787774] hover:text-[#37352F]'" class="px-3 py-1 rounded transition-colors">
        4. BAAK
      </button>
      @endif
    </div>
  </div>

  <!-- ================================================================= -->
  <!-- TAB 1: MAHASISWA TA -->
  <!-- ================================================================= -->
  <div x-show="activeTab === 'mahasiswa'" class="space-y-6">
    
    <!-- Status Alur Verifikasi Terkini -->
    @if ($myUjian)
      <div class="border border-[#E9E9E7] rounded-xl p-6 bg-[#FFFFFF] shadow-xs">
        <h3 class="text-sm font-bold text-[#37352F] mb-1">Status Pendaftaran Ujian Terkini</h3>
        <p class="text-xs text-[#787774] mb-4">Jenis Ujian: <strong class="text-[#37352F] uppercase">{{ str_replace('_', ' ', $myUjian->jenis_ujian) }}</strong></p>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-3 text-xs">
          <!-- Step 1 -->
          <div class="p-3 rounded border border-[#C4E5D1] bg-[#EBF5EE]">
            <span class="text-[10px] font-bold text-[#2A7E4B] block uppercase">Langkah 1</span>
            <p class="font-bold text-[#37352F] mt-1">Pengajuan Berkas</p>
            <p class="text-[10px] text-[#2A7E4B]">Diajukan ✓</p>
          </div>

          <!-- Step 2 -->
          <div class="p-3 rounded {{ $myUjian->status_dokumen === 'berkas_lengkap' ? 'border-[#C4E5D1] bg-[#EBF5EE]' : 'border-amber-200 bg-amber-50' }}">
            <span class="text-[10px] font-bold {{ $myUjian->status_dokumen === 'berkas_lengkap' ? 'text-[#2A7E4B]' : 'text-amber-800' }} block uppercase">Langkah 2</span>
            <p class="font-bold text-[#37352F] mt-1">Verifikasi Artefak TA</p>
            <p class="text-[10px] {{ $myUjian->status_dokumen === 'berkas_lengkap' ? 'text-[#2A7E4B]' : 'text-amber-800' }}">
              {{ $myUjian->status_dokumen === 'berkas_lengkap' ? 'Lengkap & Valid ✓' : 'Menunggu / Kurang' }}
            </p>
          </div>

          <!-- Step 3 -->
          <div class="p-3 rounded {{ $myUjian->tanggal_ujian ? 'border-[#C4E5D1] bg-[#EBF5EE]' : 'border-[#E9E9E7] bg-[#F7F7F5]' }}">
            <span class="text-[10px] font-bold block uppercase {{ $myUjian->tanggal_ujian ? 'text-[#2A7E4B]' : 'text-[#787774]' }}">Langkah 3</span>
            <p class="font-bold text-[#37352F] mt-1">Tanggal & Penguji</p>
            <p class="text-[10px] {{ $myUjian->tanggal_ujian ? 'text-[#2A7E4B]' : 'text-[#787774]' }}">
              {{ $myUjian->tanggal_ujian ? $myUjian->tanggal_ujian->format('d M Y') . ' ✓' : 'Menunggu Koordinator' }}
            </p>
          </div>

          <!-- Step 4 -->
          <div class="p-3 rounded {{ $myUjian->ruangan ? 'border-[#C4E5D1] bg-[#EBF5EE]' : 'border-[#E9E9E7] bg-[#F7F7F5]' }}">
            <span class="text-[10px] font-bold block uppercase {{ $myUjian->ruangan ? 'text-[#2A7E4B]' : 'text-[#787774]' }}">Langkah 4</span>
            <p class="font-bold text-[#37352F] mt-1">Ruang & Zoom BAAK</p>
            <p class="text-[10px] {{ $myUjian->ruangan ? 'text-[#2A7E4B]' : 'text-[#787774]' }}">
              {{ $myUjian->ruangan ?? 'Menunggu BAAK' }}
            </p>
          </div>
        </div>
      </div>
    @endif

    <!-- Formulir Pendaftaran Ujian Baru -->
    @if ($myTugasAkhir)
      <div class="border border-[#E9E9E7] rounded-xl p-6 bg-[#FFFFFF] shadow-xs">
        <div class="pb-3 border-b border-[#E9E9E7] mb-5">
          <h3 class="text-sm font-bold text-[#37352F]">Formulir Pendaftaran Ujian Tugas Akhir Baru</h3>
          <p class="text-xs text-[#787774]">Unggah bundel naskah dan dokumen syarat pendaftaran</p>
        </div>

        <form action="{{ route('ujian.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4 text-xs">
          @csrf
          <input type="hidden" name="tugas_akhir_id" value="{{ $myTugasAkhir->id }}">

          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="block font-semibold uppercase tracking-wider text-[#787774] mb-1">Pilih Tahap Ujian</label>
              <select name="jenis_ujian" required class="w-full px-3 py-2 bg-[#FFFFFF] border border-[#E9E9E7] rounded text-[#37352F]">
                <option value="sempro">1. Seminar Proposal (Sempro)</option>
                <option value="seminar_ta" selected>2. Seminar Tugas Akhir (Progres)</option>
                <option value="sidang_akhir">3. Sidang Akhir / Yudisium TA</option>
              </select>
            </div>

            <div>
              <label class="block font-semibold uppercase tracking-wider text-[#787774] mb-1">Judul Tugas Akhir Terdaftar</label>
              <input type="text" readonly value="{{ $myTugasAkhir->judul }}" class="w-full px-3 py-2 bg-[#F7F7F5] border border-[#E9E9E7] rounded text-[#787774]">
            </div>
          </div>

          <div>
            <label class="block font-semibold uppercase tracking-wider text-[#787774] mb-1">Unggah Bundel Persyaratan (Naskah, Form Pengesahan, Bukti Logbook & Transkrip - PDF/ZIP, maks 35MB)</label>
            <input type="file" name="file_persyaratan" required accept=".pdf,.zip,.rar" class="w-full px-3 py-2 bg-[#F7F7F5] border border-[#E9E9E7] rounded text-[#37352F]">
          </div>

          <div class="flex justify-end pt-2">
            <button type="submit" class="notion-btn-primary text-xs">
              Kirim Pendaftaran Ujian
            </button>
          </div>
        </form>
      </div>
    @endif

  </div>

  <!-- ================================================================= -->
  <!-- TAB 2: TEACHING ASSISTANT (VERIFIKATOR ARTEFAK) -->
  <!-- ================================================================= -->
  <div x-show="activeTab === 'ta'" class="space-y-6">
    <div class="border border-[#E9E9E7] rounded-xl overflow-hidden bg-[#FFFFFF] shadow-xs">
      <div class="p-4 border-b border-[#E9E9E7] flex items-center justify-between">
        <div>
          <h3 class="text-sm font-bold text-[#37352F]">Antrean Pengajuan Berkas Ujian Mahasiswa</h3>
          <p class="text-xs text-[#787774]">Teaching Assistant memeriksa kelengkapan administrasi artefak fisik dan naskah digital</p>
        </div>
      </div>

      <div class="overflow-x-auto">
        <table class="w-full text-left text-xs">
          <thead class="bg-[#F7F7F5] text-[#787774] uppercase text-[10px] tracking-wider border-b border-[#E9E9E7]">
            <tr>
              <th class="py-2.5 px-4 font-semibold">Mahasiswa & NIM</th>
              <th class="py-2.5 px-4 font-semibold">Jenis Ujian</th>
              <th class="py-2.5 px-4 font-semibold">Bundel Berkas</th>
              <th class="py-2.5 px-4 font-semibold">Status Dokumen</th>
              <th class="py-2.5 px-4 text-right font-semibold">Aksi Verifikasi</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-[#E9E9E7]">
            @forelse ($allUjian as $item)
              <tr class="hover:bg-[#F7F7F5] transition-colors">
                <td class="py-3 px-4">
                  <p class="font-bold text-[#37352F]">{{ $item->tugasAkhir->mahasiswa->user->name ?? '-' }}</p>
                  <p class="text-[10px] text-[#787774]">{{ $item->tugasAkhir->mahasiswa->nim ?? '-' }} • {{ $item->tugasAkhir->prodi ?? '-' }}</p>
                </td>
                <td class="py-3 px-4">
                  <span class="notion-pill-gray uppercase text-[10px]">{{ str_replace('_', ' ', $item->jenis_ujian) }}</span>
                </td>
                <td class="py-3 px-4">
                  <a href="{{ asset('storage/' . $item->file_persyaratan) }}" target="_blank" class="inline-flex items-center gap-1 font-semibold text-[#37352F] underline">
                    <span>📦</span> Unduh Berkas
                  </a>
                </td>
                <td class="py-3 px-4">
                  @if ($item->status_dokumen === 'berkas_lengkap')
                    <span class="notion-pill-green">Lengkap & Valid</span>
                  @elseif ($item->status_dokumen === 'berkas_kurang')
                    <span class="notion-pill-red">Kurang Lengkap</span>
                  @else
                    <span class="notion-pill-orange">Menunggu Verifikasi</span>
                  @endif
                </td>
                <td class="py-3 px-4 text-right">
                  <button @click="openVerify({{ $item->toJson() }})" class="notion-btn-primary text-xs py-1 px-2.5">
                    Verifikasi Dokumen
                  </button>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="5" class="py-8 text-center text-[#787774]">Belum ada antrean berkas ujian.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- ================================================================= -->
  <!-- TAB 3: KOORDINATOR TA -->
  <!-- ================================================================= -->
  <div x-show="activeTab === 'koordinator'" class="space-y-6">
    <div class="border border-[#E9E9E7] rounded-xl overflow-hidden bg-[#FFFFFF] shadow-xs">
      <div class="p-4 border-b border-[#E9E9E7] flex items-center justify-between">
        <div>
          <h3 class="text-sm font-bold text-[#37352F]">Penetapan Tanggal Ujian & Penguji (Koordinator TA)</h3>
          <p class="text-xs text-[#787774]">Tetapkan tanggal pelaksanaan ujian untuk berkas yang telah diverifikasi lengkap oleh TA</p>
        </div>
      </div>

      <div class="overflow-x-auto">
        <table class="w-full text-left text-xs">
          <thead class="bg-[#F7F7F5] text-[#787774] uppercase text-[10px] tracking-wider border-b border-[#E9E9E7]">
            <tr>
              <th class="py-2.5 px-4 font-semibold">Mahasiswa & Judul</th>
              <th class="py-2.5 px-4 font-semibold">Jenis Ujian</th>
              <th class="py-2.5 px-4 font-semibold">Tanggal Pelaksanaan</th>
              <th class="py-2.5 px-4 font-semibold">Status Ujian</th>
              <th class="py-2.5 px-4 text-right font-semibold">Aksi Koordinator</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-[#E9E9E7]">
            @forelse ($allUjian->where('status_dokumen', 'berkas_lengkap') as $item)
              <tr class="hover:bg-[#F7F7F5] transition-colors">
                <td class="py-3 px-4 max-w-xs">
                  <p class="font-bold text-[#37352F]">{{ $item->tugasAkhir->mahasiswa->user->name ?? '-' }}</p>
                  <p class="text-[10px] text-[#787774] truncate">{{ $item->tugasAkhir->judul ?? '-' }}</p>
                </td>
                <td class="py-3 px-4">
                  <span class="notion-pill-gray uppercase text-[10px]">{{ str_replace('_', ' ', $item->jenis_ujian) }}</span>
                </td>
                <td class="py-3 px-4">
                  <span class="font-semibold text-[#37352F]">{{ $item->tanggal_ujian ? $item->tanggal_ujian->format('d M Y') : 'Belum Ditetapkan' }}</span>
                </td>
                <td class="py-3 px-4">
                  <span class="notion-pill-green">{{ ucfirst($item->status_ujian) }}</span>
                </td>
                <td class="py-3 px-4 text-right">
                  <button @click="openSchedule({{ $item->toJson() }})" class="notion-btn-primary text-xs py-1 px-2.5">
                    Atur Tanggal & Penguji
                  </button>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="5" class="py-8 text-center text-[#787774]">Tidak ada berkas yang menunggu tanggal ujian dari Koordinator.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- ================================================================= -->
  <!-- TAB 4: BAAK (FASILITAS RUANGAN, JAM & LINK ZOOM) -->
  <!-- ================================================================= -->
  <div x-show="activeTab === 'baak'" class="space-y-6">
    <div class="border border-[#E9E9E7] rounded-xl overflow-hidden bg-[#FFFFFF] shadow-xs">
      <div class="p-4 border-b border-[#E9E9E7] flex items-center justify-between">
        <div>
          <h3 class="text-sm font-bold text-[#37352F]">Alokasi Fasilitas Ruangan, Jam & Zoom (BAAK IT Del)</h3>
          <p class="text-xs text-[#787774]">Plotting ruang sidang, jam pelaksanaan, dan tautan online meeting</p>
        </div>
      </div>

      <div class="overflow-x-auto">
        <table class="w-full text-left text-xs">
          <thead class="bg-[#F7F7F5] text-[#787774] uppercase text-[10px] tracking-wider border-b border-[#E9E9E7]">
            <tr>
              <th class="py-2.5 px-4 font-semibold">Tgl Ujian</th>
              <th class="py-2.5 px-4 font-semibold">Mahasiswa</th>
              <th class="py-2.5 px-4 font-semibold">Ruangan</th>
              <th class="py-2.5 px-4 font-semibold">Jam Mulai - Selesai</th>
              <th class="py-2.5 px-4 font-semibold">Link Zoom</th>
              <th class="py-2.5 px-4 text-right font-semibold">Aksi BAAK</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-[#E9E9E7]">
            @forelse ($allUjian->whereNotNull('tanggal_ujian') as $item)
              <tr class="hover:bg-[#F7F7F5] transition-colors">
                <td class="py-3 px-4 font-bold text-[#37352F]">
                  {{ $item->tanggal_ujian->format('d M Y') }}
                </td>
                <td class="py-3 px-4">
                  {{ $item->tugasAkhir->mahasiswa->user->name ?? '-' }}
                </td>
                <td class="py-3 px-4">
                  <span class="notion-pill-gray">{{ $item->ruangan ?? 'Belum Diatur' }}</span>
                </td>
                <td class="py-3 px-4">
                  {{ $item->jam_mulai ? substr($item->jam_mulai, 0, 5) . ' - ' . substr($item->jam_selesai, 0, 5) . ' WIB' : '-' }}
                </td>
                <td class="py-3 px-4">
                  @if ($item->link_zoom)
                    <a href="{{ $item->link_zoom }}" target="_blank" class="text-blue-600 underline">Buka Zoom</a>
                  @else
                    -
                  @endif
                </td>
                <td class="py-3 px-4 text-right">
                  <button @click="openFacility({{ $item->toJson() }})" class="notion-btn-primary text-xs py-1 px-2.5">
                    Plot Ruangan & Jam
                  </button>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="6" class="py-8 text-center text-[#787774]">Belum ada jadwal ujian dengan tanggal yang ditetapkan Koordinator.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>

  @if (auth()->user()->isTA() || auth()->user()->isKoordinator())
  <!-- MODAL: VERIFIKASI DOKUMEN TA -->
  <div x-show="verifyModalOpen" x-cloak class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
    <div class="fixed inset-0 bg-[#0A0A0B]/40 backdrop-blur-xs" @click="verifyModalOpen = false"></div>
    <div class="flex min-h-full items-center justify-center p-4">
      <div class="relative w-full max-w-md bg-[#FFFFFF] border border-[#E9E9E7] rounded-xl shadow-xl p-6 z-10" @click.away="verifyModalOpen = false">
        
        <div class="flex items-center justify-between pb-3 border-b border-[#E9E9E7] mb-4">
          <h3 class="text-sm font-bold text-[#37352F]">Verifikasi Dokumen Ujian (TA)</h3>
          <button @click="verifyModalOpen = false" class="text-gray-400 hover:text-black">&times;</button>
        </div>

        <template x-if="selectedUjian">
          <form :action="'/ujian/' + selectedUjian.id + '/verifikasi'" method="POST" class="space-y-4 text-xs">
            @csrf
            @method('PATCH')

            <div>
              <label class="block font-semibold uppercase tracking-wider text-[#787774] mb-1">Keputusan Kelengkapan</label>
              <div class="grid grid-cols-2 gap-2">
                <label class="p-2 border border-[#C4E5D1] bg-[#EBF5EE] rounded text-center cursor-pointer flex items-center justify-center gap-1.5">
                  <input type="radio" name="status_dokumen" value="berkas_lengkap" checked>
                  <span class="font-bold text-[#2A7E4B]">Lengkap & Valid</span>
                </label>
                <label class="p-2 border border-red-200 bg-red-50 rounded text-center cursor-pointer flex items-center justify-center gap-1.5">
                  <input type="radio" name="status_dokumen" value="berkas_kurang">
                  <span class="font-bold text-red-800">Kurang Lengkap</span>
                </label>
              </div>
            </div>

            <div>
              <label class="block font-semibold uppercase tracking-wider text-[#787774] mb-1">Catatan Tambahan Verifikator TA</label>
              <textarea name="catatan_verifikasi_ta" rows="3" placeholder="Semua berkas fisik dan soft-copy telah diverifikasi lengkap..." class="w-full px-3 py-2 bg-[#FFFFFF] border border-[#E9E9E7] rounded text-[#37352F]"></textarea>
            </div>

            <div class="flex items-center justify-end gap-2 pt-3 border-t border-[#E9E9E7]">
              <button type="button" @click="verifyModalOpen = false" class="notion-btn-secondary text-xs">Batal</button>
              <button type="submit" class="notion-btn-primary text-xs">Simpan Verifikasi</button>
            </div>
          </form>
        </template>

      </div>
    </div>
  </div>
  @endif

  @if (auth()->user()->isKoordinator())
  <!-- MODAL: ATUR TANGGAL & PENGUJI (KOORDINATOR TA) -->
  <div x-show="scheduleModalOpen" x-cloak class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
    <div class="fixed inset-0 bg-[#0A0A0B]/40 backdrop-blur-xs" @click="scheduleModalOpen = false"></div>
    <div class="flex min-h-full items-center justify-center p-4">
      <div class="relative w-full max-w-md bg-[#FFFFFF] border border-[#E9E9E7] rounded-xl shadow-xl p-6 z-10" @click.away="scheduleModalOpen = false">
        
        <div class="flex items-center justify-between pb-3 border-b border-[#E9E9E7] mb-4">
          <h3 class="text-sm font-bold text-[#37352F]">Penetapan Tanggal & Penguji Ujian</h3>
          <button @click="scheduleModalOpen = false" class="text-gray-400 hover:text-black">&times;</button>
        </div>

        <template x-if="selectedUjian">
          <form :action="'/ujian/' + selectedUjian.id + '/jadwal'" method="POST" class="space-y-4 text-xs">
            @csrf
            @method('PATCH')

            <div>
              <label class="block font-semibold uppercase tracking-wider text-[#787774] mb-1">Tanggal Pelaksanaan Ujian</label>
              <input type="date" name="tanggal_ujian" required class="w-full px-3 py-2 bg-[#FFFFFF] border border-[#E9E9E7] rounded text-[#37352F]">
            </div>

            <div>
              <label class="block font-semibold uppercase tracking-wider text-[#787774] mb-1">Dosen Penguji 1</label>
              <select name="dosen_penguji_1" class="w-full px-3 py-2 bg-[#FFFFFF] border border-[#E9E9E7] rounded text-[#37352F]">
                @foreach ($dosenList as $d)
                  <option value="{{ $d->user->name }}">{{ $d->user->name }}</option>
                @endforeach
              </select>
            </div>

            <div>
              <label class="block font-semibold uppercase tracking-wider text-[#787774] mb-1">Dosen Penguji 2</label>
              <select name="dosen_penguji_2" class="w-full px-3 py-2 bg-[#FFFFFF] border border-[#E9E9E7] rounded text-[#37352F]">
                @foreach ($dosenList as $d)
                  <option value="{{ $d->user->name }}">{{ $d->user->name }}</option>
                @endforeach
              </select>
            </div>

            <div class="flex items-center justify-end gap-2 pt-3 border-t border-[#E9E9E7]">
              <button type="button" @click="scheduleModalOpen = false" class="notion-btn-secondary text-xs">Batal</button>
              <button type="submit" class="notion-btn-primary text-xs">Simpan Tanggal & Teruskan ke BAAK</button>
            </div>
          </form>
        </template>

      </div>
    </div>
  </div>
  @endif

  @if (auth()->user()->isBaak())
  <!-- MODAL: ALOKASI FASILITAS BAAK -->
  <div x-show="facilityModalOpen" x-cloak class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
    <div class="fixed inset-0 bg-[#0A0A0B]/40 backdrop-blur-xs" @click="facilityModalOpen = false"></div>
    <div class="flex min-h-full items-center justify-center p-4">
      <div class="relative w-full max-w-md bg-[#FFFFFF] border border-[#E9E9E7] rounded-xl shadow-xl p-6 z-10" @click.away="facilityModalOpen = false">
        
        <div class="flex items-center justify-between pb-3 border-b border-[#E9E9E7] mb-4">
          <h3 class="text-sm font-bold text-[#37352F]">Plotting Fasilitas Ujian (BAAK)</h3>
          <button @click="facilityModalOpen = false" class="text-gray-400 hover:text-black">&times;</button>
        </div>

        <template x-if="selectedUjian">
          <form :action="'/ujian/' + selectedUjian.id + '/fasilitas'" method="POST" class="space-y-4 text-xs">
            @csrf
            @method('PATCH')

            <div>
              <label class="block font-semibold uppercase tracking-wider text-[#787774] mb-1">Ruangan Ujian FITE</label>
              <select name="ruangan" required class="w-full px-3 py-2 bg-[#FFFFFF] border border-[#E9E9E7] rounded text-[#37352F]">
                <option value="Ruang Sidang FITE GD 514">Ruang Sidang FITE GD 514</option>
                <option value="Lab Komputer GD 512">Lab Komputer GD 512</option>
                <option value="Ruang Rapat FITE GD 510">Ruang Rapat FITE GD 510</option>
              </select>
            </div>

            <div class="grid grid-cols-2 gap-3">
              <div>
                <label class="block font-semibold uppercase tracking-wider text-[#787774] mb-1">Jam Mulai</label>
                <input type="time" name="jam_mulai" value="09:00" required class="w-full px-3 py-2 bg-[#FFFFFF] border border-[#E9E9E7] rounded text-[#37352F]">
              </div>
              <div>
                <label class="block font-semibold uppercase tracking-wider text-[#787774] mb-1">Jam Selesai</label>
                <input type="time" name="jam_selesai" value="11:00" required class="w-full px-3 py-2 bg-[#FFFFFF] border border-[#E9E9E7] rounded text-[#37352F]">
              </div>
            </div>

            <div>
              <label class="block font-semibold uppercase tracking-wider text-[#787774] mb-1">Link Zoom Meeting (Daring / Hybrid)</label>
              <input type="url" name="link_zoom" value="https://zoom.us/j/itdel-sidang-fite1" class="w-full px-3 py-2 bg-[#FFFFFF] border border-[#E9E9E7] rounded text-[#37352F]">
            </div>

            <div class="flex items-center justify-end gap-2 pt-3 border-t border-[#E9E9E7]">
              <button type="button" @click="facilityModalOpen = false" class="notion-btn-secondary text-xs">Batal</button>
              <button type="submit" class="notion-btn-primary text-xs">Terbitkan Fasilitas</button>
            </div>
          </form>
        </template>

      </div>
    </div>
  </div>
  @endif

</div>
@endsection

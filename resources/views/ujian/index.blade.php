@extends('layouts.notion')

@section('title', 'Manajemen Ujian TA')
@section('breadcrumb', 'Ujian TA')

@section('content')
<div class="space-y-6" x-data="{ 
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

  <!-- Header Notion -->
  <div class="border-b border-[#E9E9E7] pb-5 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
      <div class="flex items-center gap-2 mb-1">
        <span class="text-2xl">🏛️</span>
        <h1 class="text-2xl font-bold tracking-tight text-[#37352F]">Alur Pendaftaran & Manajemen Ujian TA</h1>
      </div>
      <p class="text-xs text-[#787774]">Kolaborasi terintegrasi: Mahasiswa TA (Pendaftaran) &rarr; Koordinator TA (Verifikasi Berkas) &rarr; BAAK (Jadwal, Penguji & Fasilitas Ruangan).</p>
    </div>

    <!-- Tab Switcher ala Notion Pills -->
    <div class="flex items-center bg-[#F7F7F5] p-1 rounded border border-[#E9E9E7] text-xs">
      <button @click="activeTab = 'mahasiswa'" :class="activeTab === 'mahasiswa' ? 'bg-[#FFFFFF] text-[#37352F] shadow-xs font-semibold' : 'text-[#787774] hover:text-[#37352F]'" class="px-3 py-1 rounded transition-colors">
        1. Mahasiswa TA
      </button>
      @if (!auth()->user()->isMahasiswa())
      <button @click="activeTab = 'koordinator'" :class="activeTab === 'koordinator' ? 'bg-[#FFFFFF] text-[#37352F] shadow-xs font-semibold' : 'text-[#787774] hover:text-[#37352F]'" class="px-3 py-1 rounded transition-colors">
        2. Koordinator TA
      </button>
      <button @click="activeTab = 'baak'" :class="activeTab === 'baak' ? 'bg-[#FFFFFF] text-[#37352F] shadow-xs font-semibold' : 'text-[#787774] hover:text-[#37352F]'" class="px-3 py-1 rounded transition-colors">
        3. BAAK
      </button>
      <button @click="activeTab = 'ta'" :class="activeTab === 'ta' ? 'bg-[#FFFFFF] text-[#37352F] shadow-xs font-semibold' : 'text-[#787774] hover:text-[#37352F]'" class="px-3 py-1 rounded transition-colors">
        4. Pantauan Staf / TA
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
        <p class="text-xs text-[#787774] mb-4">Jenis Ujian: <strong class="text-[#37352F]">{{ $myUjian->jenis_ujian_label }}</strong></p>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-3 text-xs">
          <!-- Step 1 -->
          <div class="p-3 rounded border border-[#C4E5D1] bg-[#EBF5EE]">
            <span class="text-[10px] font-bold text-[#2A7E4B] block uppercase">Langkah 1</span>
            <p class="font-bold text-[#37352F] mt-1">Pengajuan Berkas</p>
            <p class="text-[10px] text-[#2A7E4B]">Diajukan Mahasiswa ✓</p>
          </div>

          <!-- Step 2 -->
          <div class="p-3 rounded {{ $myUjian->status_dokumen === 'berkas_lengkap' ? 'border-[#C4E5D1] bg-[#EBF5EE]' : 'border-amber-200 bg-amber-50' }}">
            <span class="text-[10px] font-bold {{ $myUjian->status_dokumen === 'berkas_lengkap' ? 'text-[#2A7E4B]' : 'text-amber-800' }} block uppercase">Langkah 2</span>
            <p class="font-bold text-[#37352F] mt-1">Verifikasi Berkas</p>
            <p class="text-[10px] {{ $myUjian->status_dokumen === 'berkas_lengkap' ? 'text-[#2A7E4B]' : 'text-amber-800' }}">
              {{ $myUjian->status_dokumen === 'berkas_lengkap' ? 'Lengkap & Valid (Koordinator) ✓' : 'Menunggu Koordinator TA' }}
            </p>
          </div>

          <!-- Step 3 -->
          <div class="p-3 rounded {{ $myUjian->tanggal_ujian ? 'border-[#C4E5D1] bg-[#EBF5EE]' : 'border-[#E9E9E7] bg-[#F7F7F5]' }}">
            <span class="text-[10px] font-bold block uppercase {{ $myUjian->tanggal_ujian ? 'text-[#2A7E4B]' : 'text-[#787774]' }}">Langkah 3</span>
            <p class="font-bold text-[#37352F] mt-1">Tanggal & Penguji</p>
            <p class="text-[10px] {{ $myUjian->tanggal_ujian ? 'text-[#2A7E4B]' : 'text-[#787774]' }}">
              {{ $myUjian->tanggal_ujian ? $myUjian->tanggal_ujian->format('d M Y') . ' (BAAK) ✓' : 'Menunggu BAAK' }}
            </p>
            @if ($myUjian->penguji1 || $myUjian->penguji2)
              <div class="mt-2 pt-2 border-t border-[#C4E5D1] text-[10px] text-[#2A7E4B] space-y-0.5">
                <p><span class="font-semibold">P1:</span> {{ $myUjian->penguji1->user->name ?? '-' }}</p>
                <p><span class="font-semibold">P2:</span> {{ $myUjian->penguji2->user->name ?? '-' }}</p>
              </div>
            @endif
          </div>

          <!-- Step 4 -->
          <div class="p-3 rounded {{ $myUjian->ruangan ? 'border-[#C4E5D1] bg-[#EBF5EE]' : 'border-[#E9E9E7] bg-[#F7F7F5]' }}">
            <span class="text-[10px] font-bold block uppercase {{ $myUjian->ruangan ? 'text-[#2A7E4B]' : 'text-[#787774]' }}">Langkah 4</span>
            <p class="font-bold text-[#37352F] mt-1">Ruang & Zoom BAAK</p>
            <p class="text-[10px] {{ $myUjian->ruangan ? 'text-[#2A7E4B]' : 'text-[#787774]' }}">
              {{ $myUjian->ruangan ?? 'Menunggu BAAK' }}
            </p>
            @if ($myUjian->jam_mulai && $myUjian->jam_selesai)
              <p class="text-[10px] text-[#2A7E4B] mt-1">{{ substr($myUjian->jam_mulai, 0, 5) }} - {{ substr($myUjian->jam_selesai, 0, 5) }} WIB</p>
            @endif
            @if ($myUjian->link_zoom)
              <div class="mt-1">
                <a href="{{ $myUjian->link_zoom }}" target="_blank" class="text-[10px] text-blue-600 underline font-semibold">Tautan Zoom</a>
              </div>
            @endif
          </div>
        </div>
      </div>
    @endif

    <!-- Formulir Pendaftaran Ujian Baru -->
    @if ($myTugasAkhir)
      <div class="border border-[#E9E9E7] rounded-xl p-6 bg-[#FFFFFF] shadow-xs">
        <div class="pb-3 border-b border-[#E9E9E7] mb-5">
          <h3 class="text-sm font-bold text-[#37352F]">Formulir Pendaftaran Ujian Tugas Akhir FITE</h3>
          <p class="text-xs text-[#787774]">Pilih tahapan ujian resmi dan unggah bundel naskah serta berkas persyaratan</p>
        </div>

        <form action="{{ route('ujian.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4 text-xs">
          @csrf
          <input type="hidden" name="tugas_akhir_id" value="{{ $myTugasAkhir->id }}">

          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="block font-semibold uppercase tracking-wider text-[#787774] mb-1">Pilih Tahap Ujian Resmi</label>
              <select name="jenis_ujian" required class="w-full px-3 py-2 bg-[#FFFFFF] border border-[#E9E9E7] rounded text-[#37352F]">
                <option value="seminar_proposal">1. Seminar Proposal (BAB 1–3)</option>
                <option value="seminar_ta_1" selected>2. Seminar TA 1</option>
                <option value="seminar_hasil">3. Seminar Hasil</option>
                <option value="seminar_skripsi">4. Seminar Tugas Akhir / Skripsi</option>
              </select>
            </div>

            <div>
              <label class="block font-semibold uppercase tracking-wider text-[#787774] mb-1">Judul Tugas Akhir Terdaftar</label>
              <input type="text" readonly value="{{ $myTugasAkhir->judul }}" class="w-full px-3 py-2 bg-[#F7F7F5] border border-[#E9E9E7] rounded text-[#787774]">
            </div>
          </div>

          <div>
            <label class="block font-semibold uppercase tracking-wider text-[#787774] mb-1">Unggah Bundel Persyaratan (Naskah, Form Persetujuan Pembimbing, Bukti Logbook - PDF/ZIP, maks 35MB)</label>
            <input type="file" name="file_persyaratan" required accept=".pdf,.zip,.rar" class="w-full px-3 py-2 bg-[#F7F7F5] border border-[#E9E9E7] rounded text-[#37352F]">
          </div>

          <div class="flex justify-end pt-2">
            <button type="submit" class="notion-btn-primary text-xs">
              Kirim Pendaftaran Ujian ke Koordinator TA
            </button>
          </div>
        </form>
      </div>
    @endif

  </div>

  @if (!auth()->user()->isMahasiswa() && isset($allUjian))
  <!-- ================================================================= -->
  <!-- TAB 2: KOORDINATOR TA (VERIFIKASI BERKAS PENDAFTARAN) -->
  <!-- ================================================================= -->
  <div x-show="activeTab === 'koordinator'" class="space-y-6">
    <div class="border border-[#E9E9E7] rounded-xl overflow-hidden bg-[#FFFFFF] shadow-xs">
      <div class="p-4 border-b border-[#E9E9E7] flex items-center justify-between">
        <div>
          <h3 class="text-sm font-bold text-[#37352F]">Verifikasi Berkas Pendaftaran Ujian (Wewenang Koordinator TA)</h3>
          <p class="text-xs text-[#787774]">Koordinator TA memeriksa kelengkapan administrasi berkas dan naskah sebelum diteruskan ke BAAK</p>
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
              <th class="py-2.5 px-4 text-right font-semibold">Aksi Koordinator</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-[#E9E9E7]">
            @forelse ($allUjian as $item)
              <tr class="hover:bg-[#F7F7F5] transition-colors {{ $item->status_dokumen === 'menunggu_verifikasi' ? 'bg-amber-50/40' : '' }}">
                <td class="py-3 px-4">
                  <div class="flex items-center gap-1.5">
                    @if ($item->status_dokumen === 'menunggu_verifikasi')
                      <span class="inline-block w-2 h-2 rounded-full bg-amber-500 shrink-0" title="Menunggu verifikasi berkas"></span>
                    @endif
                    <p class="font-bold text-[#37352F]">{{ $item->tugasAkhir->mahasiswa->user->name ?? '-' }}</p>
                  </div>
                  <p class="text-[10px] text-[#787774]">{{ $item->tugasAkhir->mahasiswa->nim ?? '-' }} • {{ $item->tugasAkhir->prodi ?? '-' }}</p>
                </td>
                <td class="py-3 px-4">
                  <span class="notion-pill-gray text-[10px]">{{ $item->jenis_ujian_label }}</span>
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
                    <span class="notion-pill-orange font-semibold">Menunggu Verifikasi ⏳</span>
                  @endif
                </td>
                <td class="py-3 px-4 text-right">
                  @if (auth()->user()->isKoordinator())
                    <button @click="openVerify({{ $item->toJson() }})" class="notion-btn-primary text-xs py-1 px-2.5">
                      Verifikasi Dokumen
                    </button>
                  @else
                    <span class="text-[11px] text-[#787774] italic">Khusus Koordinator TA</span>
                  @endif
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
  <!-- TAB 3: BAAK (JADWAL, 2 PENGUJI & FASILITAS RUANGAN) -->
  <!-- ================================================================= -->
  <div x-show="activeTab === 'baak'" class="space-y-6">
    <div class="border border-[#E9E9E7] rounded-xl overflow-hidden bg-[#FFFFFF] shadow-xs">
      <div class="p-4 border-b border-[#E9E9E7] flex items-center justify-between">
        <div>
          <h3 class="text-sm font-bold text-[#37352F]">Penetapan Tanggal, Tim Penguji & Fasilitas Ruangan (BAAK IT Del)</h3>
          <p class="text-xs text-[#787774]">BAAK menetapkan tanggal pelaksanaan ujian, menunjuk 2 Dosen Penguji, alokasi ruang sidang, dan link Zoom</p>
        </div>
      </div>

      <div class="overflow-x-auto">
        <table class="w-full text-left text-xs">
          <thead class="bg-[#F7F7F5] text-[#787774] uppercase text-[10px] tracking-wider border-b border-[#E9E9E7]">
            <tr>
              <th class="py-2.5 px-4 font-semibold">Mahasiswa & Jenis Ujian</th>
              <th class="py-2.5 px-4 font-semibold">Tgl Ujian</th>
              <th class="py-2.5 px-4 font-semibold">Tim Dosen Penguji</th>
              <th class="py-2.5 px-4 font-semibold">Ruangan & Jam</th>
              <th class="py-2.5 px-4 font-semibold">Link Zoom</th>
              <th class="py-2.5 px-4 text-right font-semibold">Aksi BAAK</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-[#E9E9E7]">
            @forelse ($allUjian->where('status_dokumen', 'berkas_lengkap') as $item)
              @php
                $needsBaakSetup = ! $item->tanggal_ujian || ! $item->dosen_penguji_1_id || ! $item->dosen_penguji_2_id || ! $item->ruangan;
              @endphp
              <tr class="hover:bg-[#F7F7F5] transition-colors {{ $needsBaakSetup ? 'bg-amber-50/40' : '' }}">
                <td class="py-3 px-4">
                  <div class="flex items-center gap-1.5">
                    @if ($needsBaakSetup)
                      <span class="inline-block w-2 h-2 rounded-full bg-amber-500 shrink-0" title="Menunggu alokasi BAAK"></span>
                    @endif
                    <p class="font-bold text-[#37352F]">{{ $item->tugasAkhir->mahasiswa->user->name ?? '-' }}</p>
                  </div>
                  <span class="notion-pill-gray text-[10px] mt-0.5 inline-block">{{ $item->jenis_ujian_label }}</span>
                </td>
                <td class="py-3 px-4 font-bold text-[#37352F]">
                  @if ($item->tanggal_ujian)
                    {{ $item->tanggal_ujian->format('d M Y') }}
                  @else
                    <span class="notion-pill-orange text-[10px]">Belum Ditetapkan</span>
                  @endif
                </td>
                <td class="py-3 px-4">
                  @if ($item->penguji1 && $item->penguji2)
                    <div class="text-[11px] text-[#37352F] space-y-0.5">
                      <p><span class="font-semibold text-[#787774]">P1:</span> {{ $item->penguji1->user->name }}</p>
                      <p><span class="font-semibold text-[#787774]">P2:</span> {{ $item->penguji2->user->name }}</p>
                    </div>
                  @else
                    <span class="notion-pill-orange text-[10px]">Wajib 2 Penguji ⚠️</span>
                  @endif
                </td>
                <td class="py-3 px-4">
                  @if ($item->ruangan)
                    <p class="font-semibold text-[#37352F]">{{ $item->ruangan }}</p>
                    <p class="text-[10px] text-[#787774]">{{ $item->jam_mulai ? substr($item->jam_mulai, 0, 5) . ' - ' . substr($item->jam_selesai, 0, 5) . ' WIB' : '-' }}</p>
                  @else
                    <span class="notion-pill-orange font-bold">Siap Dijadwalkan BAAK ⚠️</span>
                  @endif
                </td>
                <td class="py-3 px-4">
                  @if ($item->link_zoom)
                    <a href="{{ $item->link_zoom }}" target="_blank" class="text-blue-600 underline">Buka Zoom</a>
                  @else
                    -
                  @endif
                </td>
                <td class="py-3 px-4 text-right">
                  @if (auth()->user()->isBaak())
                    <button @click="openSchedule({{ $item->toJson() }})" class="notion-btn-primary text-xs py-1 px-2.5">
                      {{ $needsBaakSetup ? 'Atur Jadwal, Penguji & Fasilitas' : 'Ubah Jadwal & Fasilitas' }}
                    </button>
                  @else
                    <span class="text-[11px] text-[#787774] italic">Khusus BAAK</span>
                  @endif
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="6" class="py-8 text-center text-[#787774]">Belum ada berkas berstatus lengkap yang siap dialokasikan oleh BAAK.</td>
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
  <div x-show="activeTab === 'ta'" class="space-y-6">
    <div class="border border-[#E9E9E7] rounded-xl overflow-hidden bg-[#FFFFFF] shadow-xs">
      <div class="p-4 border-b border-[#E9E9E7] flex items-center justify-between">
        <div>
          <h3 class="text-sm font-bold text-[#37352F]">Pantauan Administrasi & Jadwal Ujian TA (Monitoring)</h3>
          <p class="text-xs text-[#787774]">Pemantauan menyeluruh alur pendaftaran ujian seluruh mahasiswa FITE (Read-only)</p>
        </div>
      </div>

      <div class="overflow-x-auto">
        <table class="w-full text-left text-xs">
          <thead class="bg-[#F7F7F5] text-[#787774] uppercase text-[10px] tracking-wider border-b border-[#E9E9E7]">
            <tr>
              <th class="py-2.5 px-4 font-semibold">Mahasiswa & NIM</th>
              <th class="py-2.5 px-4 font-semibold">Jenis Ujian</th>
              <th class="py-2.5 px-4 font-semibold">Status Berkas</th>
              <th class="py-2.5 px-4 font-semibold">Tanggal Pelaksanaan</th>
              <th class="py-2.5 px-4 font-semibold">Ruangan Ujian</th>
              <th class="py-2.5 px-4 text-right font-semibold">Status Ujian</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-[#E9E9E7]">
            @forelse ($allUjian as $item)
              <tr class="hover:bg-[#F7F7F5] transition-colors">
                <td class="py-3 px-4">
                  <p class="font-bold text-[#37352F]">{{ $item->tugasAkhir->mahasiswa->user->name ?? '-' }}</p>
                  <p class="text-[10px] text-[#787774]">{{ $item->tugasAkhir->mahasiswa->nim ?? '-' }}</p>
                </td>
                <td class="py-3 px-4">
                  <span class="notion-pill-gray text-[10px]">{{ $item->jenis_ujian_label }}</span>
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
                <td class="py-3 px-4">
                  {{ $item->tanggal_ujian ? $item->tanggal_ujian->format('d M Y') : '-' }}
                </td>
                <td class="py-3 px-4">
                  {{ $item->ruangan ?? '-' }}
                </td>
                <td class="py-3 px-4 text-right">
                  <span class="notion-pill-gray capitalize">{{ $item->status_ujian }}</span>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="6" class="py-8 text-center text-[#787774]">Belum ada data pendaftaran ujian.</td>
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
  <div x-show="verifyModalOpen" x-cloak class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
    <div class="fixed inset-0 bg-[#0A0A0B]/40 backdrop-blur-xs" @click="verifyModalOpen = false"></div>
    <div class="flex min-h-full items-center justify-center p-4">
      <div class="relative w-full max-w-md bg-[#FFFFFF] border border-[#E9E9E7] rounded-xl shadow-xl p-6 z-10" @click.away="verifyModalOpen = false">
        
        <div class="flex items-center justify-between pb-3 border-b border-[#E9E9E7] mb-4">
          <div>
            <h3 class="text-sm font-bold text-[#37352F]">Verifikasi Berkas Ujian (Koordinator TA)</h3>
            <p class="text-[11px] text-[#787774] mt-0.5">Hanya Koordinator TA yang berwenang menyatakan kelengkapan berkas</p>
          </div>
          <button @click="verifyModalOpen = false" class="text-gray-400 hover:text-black">&times;</button>
        </div>

        <template x-if="selectedUjian">
          <form :action="'/ujian/' + selectedUjian.id + '/verifikasi'" method="POST" class="space-y-4 text-xs">
            @csrf
            @method('PATCH')

            <div>
              <label class="block font-semibold uppercase tracking-wider text-[#787774] mb-1">Keputusan Kelengkapan Berkas</label>
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
              <label class="block font-semibold uppercase tracking-wider text-[#787774] mb-1">Catatan Tambahan Koordinator TA</label>
              <textarea name="catatan_verifikasi_ta" rows="3" placeholder="Semua berkas fisik dan digital telah diperiksa lengkap & valid..." class="w-full px-3 py-2 bg-[#FFFFFF] border border-[#E9E9E7] rounded text-[#37352F]"></textarea>
            </div>

            <div class="flex items-center justify-end gap-2 pt-3 border-t border-[#E9E9E7]">
              <button type="button" @click="verifyModalOpen = false" class="notion-btn-secondary text-xs">Batal</button>
              <button type="submit" class="notion-btn-primary text-xs">Simpan Verifikasi & Teruskan ke BAAK</button>
            </div>
          </form>
        </template>

      </div>
    </div>
  </div>
  @endif

  @if (auth()->user()->isBaak())
  <!-- MODAL: ATUR TANGGAL, 2 PENGUJI & FASILITAS (BAAK) -->
  <div x-show="scheduleModalOpen" x-cloak class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
    <div class="fixed inset-0 bg-[#0A0A0B]/40 backdrop-blur-xs" @click="scheduleModalOpen = false"></div>
    <div class="flex min-h-full items-center justify-center p-4">
      <div class="relative w-full max-w-lg bg-[#FFFFFF] border border-[#E9E9E7] rounded-xl shadow-xl p-6 z-10" @click.away="scheduleModalOpen = false">
        
        <div class="flex items-center justify-between pb-3 border-b border-[#E9E9E7] mb-4">
          <div>
            <h3 class="text-sm font-bold text-[#37352F]">Penetapan Jadwal, Penguji & Fasilitas (BAAK IT Del)</h3>
            <p class="text-[11px] text-[#787774] mt-0.5">BAAK menetapkan tanggal pelaksanaan, 2 dosen penguji berbeda, ruang sidang, dan Zoom</p>
          </div>
          <button @click="scheduleModalOpen = false" class="text-gray-400 hover:text-black">&times;</button>
        </div>

        <template x-if="selectedUjian">
          <form :action="'/ujian/' + selectedUjian.id + '/jadwal'" method="POST" class="space-y-4 text-xs">
            @csrf
            @method('PATCH')

            <div>
              <label class="block font-semibold uppercase tracking-wider text-[#787774] mb-1">Tanggal Pelaksanaan Ujian <span class="text-red-500">*</span></label>
              <input type="date" name="tanggal_ujian" :value="selectedUjian?.tanggal_ujian ? selectedUjian.tanggal_ujian.substring(0, 10) : ''" required class="w-full px-3 py-2 bg-[#FFFFFF] border border-[#E9E9E7] rounded text-[#37352F]">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
              <div>
                <label class="block font-semibold uppercase tracking-wider text-[#787774] mb-1">Dosen Penguji 1 <span class="text-red-500">*</span></label>
                <select name="dosen_penguji_1_id" required class="w-full px-3 py-2 bg-[#FFFFFF] border border-[#E9E9E7] rounded text-[#37352F]">
                  <option value="" disabled :selected="!selectedUjian?.dosen_penguji_1_id">-- Pilih Dosen Penguji 1 --</option>
                  @foreach (($dosenList ?? []) as $d)
                    <option value="{{ $d->id }}" :selected="selectedUjian?.dosen_penguji_1_id == {{ $d->id }}">{{ $d->user->name }} ({{ $d->kode_dosen ?? $d->nidn }})</option>
                  @endforeach
                </select>
              </div>

              <div>
                <label class="block font-semibold uppercase tracking-wider text-[#787774] mb-1">Dosen Penguji 2 <span class="text-red-500">* (Berbeda)</span></label>
                <select name="dosen_penguji_2_id" required class="w-full px-3 py-2 bg-[#FFFFFF] border border-[#E9E9E7] rounded text-[#37352F]">
                  <option value="" disabled :selected="!selectedUjian?.dosen_penguji_2_id">-- Pilih Dosen Penguji 2 --</option>
                  @foreach (($dosenList ?? []) as $d)
                    <option value="{{ $d->id }}" :selected="selectedUjian?.dosen_penguji_2_id == {{ $d->id }}">{{ $d->user->name }} ({{ $d->kode_dosen ?? $d->nidn }})</option>
                  @endforeach
                </select>
              </div>
            </div>

            <div>
              <label class="block font-semibold uppercase tracking-wider text-[#787774] mb-1">Ruangan Ujian / Sidang FITE <span class="text-red-500">*</span></label>
              <select name="ruangan" required class="w-full px-3 py-2 bg-[#FFFFFF] border border-[#E9E9E7] rounded text-[#37352F]">
                <option value="Ruang Sidang FITE GD 514" :selected="selectedUjian?.ruangan === 'Ruang Sidang FITE GD 514'">Ruang Sidang FITE GD 514</option>
                <option value="Lab Komputer GD 512" :selected="selectedUjian?.ruangan === 'Lab Komputer GD 512'">Lab Komputer GD 512</option>
                <option value="Ruang Rapat FITE GD 510" :selected="selectedUjian?.ruangan === 'Ruang Rapat FITE GD 510'">Ruang Rapat FITE GD 510</option>
              </select>
            </div>

            <div class="grid grid-cols-2 gap-3">
              <div>
                <label class="block font-semibold uppercase tracking-wider text-[#787774] mb-1">Jam Mulai <span class="text-red-500">*</span></label>
                <input type="time" name="jam_mulai" :value="selectedUjian?.jam_mulai ? selectedUjian.jam_mulai.substring(0, 5) : '09:00'" required class="w-full px-3 py-2 bg-[#FFFFFF] border border-[#E9E9E7] rounded text-[#37352F]">
              </div>
              <div>
                <label class="block font-semibold uppercase tracking-wider text-[#787774] mb-1">Jam Selesai <span class="text-red-500">*</span></label>
                <input type="time" name="jam_selesai" :value="selectedUjian?.jam_selesai ? selectedUjian.jam_selesai.substring(0, 5) : '11:00'" required class="w-full px-3 py-2 bg-[#FFFFFF] border border-[#E9E9E7] rounded text-[#37352F]">
              </div>
            </div>

            <div>
              <label class="block font-semibold uppercase tracking-wider text-[#787774] mb-1">Link Zoom Meeting (Daring / Hybrid)</label>
              <input type="url" name="link_zoom" :value="selectedUjian?.link_zoom ?? 'https://zoom.us/j/itdel-sidang-fite1'" class="w-full px-3 py-2 bg-[#FFFFFF] border border-[#E9E9E7] rounded text-[#37352F]">
            </div>

            <div class="flex items-center justify-end gap-2 pt-3 border-t border-[#E9E9E7]">
              <button type="button" @click="scheduleModalOpen = false" class="notion-btn-secondary text-xs">Batal</button>
              <button type="submit" class="notion-btn-primary text-xs">Simpan Jadwal, Penguji & Fasilitas</button>
            </div>
          </form>
        </template>

      </div>
    </div>
  </div>
  @endif

</div>
@endsection

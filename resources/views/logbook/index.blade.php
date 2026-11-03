@extends('layouts.notion')

@section('title', 'Logbook Aktivitas')
@section('breadcrumb', 'Logbook Aktivitas')

@section('content')
<div class="space-y-6" x-data="{ 
  viewMode: 'table',
  openCreateModal: false,
  selectedLogbook: null,
  reviewDrawerOpen: false,
  openReview(log) {
    this.selectedLogbook = log;
    this.reviewDrawerOpen = true;
  }
}">

  <!-- Page Header Ala Notion -->
  <div class="border-b border-[#E9E9E7] pb-5 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
      <div class="flex items-center gap-2 mb-1">
        <span class="text-2xl">📝</span>
        <h1 class="text-2xl font-bold tracking-tight text-[#37352F]">Logbook Aktivitas & Progres TA</h1>
      </div>
      <p class="text-xs text-[#787774]">Dokumentasi berkala pelaksanaan riset tugas akhir dan riwayat evaluasi pembimbing.</p>
    </div>

    @if (auth()->user()->isMahasiswa())
      <button @click="openCreateModal = true" class="notion-btn-primary text-xs">
        <span>+</span> Isi Logbook Baru
      </button>
    @endif
  </div>

  <!-- Notion Database View Bar (Controls & Filters) -->
  <div class="flex flex-wrap items-center justify-between gap-3 text-xs">
    
    <!-- View Switcher -->
    <div class="flex items-center bg-[#F7F7F5] p-0.5 rounded border border-[#E9E9E7]">
      <button @click="viewMode = 'table'" 
              :class="viewMode === 'table' ? 'bg-[#FFFFFF] text-[#37352F] shadow-xs font-semibold' : 'text-[#787774] hover:text-[#37352F]'"
              class="px-2.5 py-1 rounded transition-colors flex items-center gap-1.5">
        <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <line x1="8" y1="6" x2="21" y2="6"></line>
          <line x1="8" y1="12" x2="21" y2="12"></line>
          <line x1="8" y1="18" x2="21" y2="18"></line>
          <line x1="3" y1="6" x2="3.01" y2="6"></line>
          <line x1="3" y1="12" x2="3.01" y2="12"></line>
          <line x1="3" y1="18" x2="3.01" y2="18"></line>
        </svg>
        <span>Table</span>
      </button>
      <button @click="viewMode = 'kanban'" 
              :class="viewMode === 'kanban' ? 'bg-[#FFFFFF] text-[#37352F] shadow-xs font-semibold' : 'text-[#787774] hover:text-[#37352F]'"
              class="px-2.5 py-1 rounded transition-colors flex items-center gap-1.5">
        <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <rect x="3" y="3" width="7" height="18" rx="1"></rect>
          <rect x="14" y="3" width="7" height="11" rx="1"></rect>
        </svg>
        <span>Board</span>
      </button>
      <button @click="viewMode = 'list'" 
              :class="viewMode === 'list' ? 'bg-[#FFFFFF] text-[#37352F] shadow-xs font-semibold' : 'text-[#787774] hover:text-[#37352F]'"
              class="px-2.5 py-1 rounded transition-colors flex items-center gap-1.5">
        <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <line x1="3" y1="6" x2="21" y2="6"></line>
          <line x1="3" y1="12" x2="21" y2="12"></line>
          <line x1="3" y1="18" x2="21" y2="18"></line>
        </svg>
        <span>List</span>
      </button>
    </div>

    <!-- Quick Filters Form -->
    <form method="GET" action="{{ route('logbook.index') }}" class="flex items-center gap-2">
      <select name="status" onchange="this.form.submit()" class="px-2.5 py-1 bg-[#FFFFFF] border border-[#E9E9E7] rounded text-xs text-[#37352F] focus:outline-none">
        <option value="">Semua Status</option>
        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
        <option value="revisi" {{ request('status') === 'revisi' ? 'selected' : '' }}>Revisi</option>
        <option value="disetujui" {{ request('status') === 'disetujui' ? 'selected' : '' }}>Disetujui</option>
      </select>

      <select name="bab" onchange="this.form.submit()" class="px-2.5 py-1 bg-[#FFFFFF] border border-[#E9E9E7] rounded text-xs text-[#37352F] focus:outline-none">
        <option value="">Semua Bab</option>
        <option value="BAB 1" {{ request('bab') === 'BAB 1' ? 'selected' : '' }}>BAB 1</option>
        <option value="BAB 2" {{ request('bab') === 'BAB 2' ? 'selected' : '' }}>BAB 2</option>
        <option value="BAB 3" {{ request('bab') === 'BAB 3' ? 'selected' : '' }}>BAB 3</option>
        <option value="BAB 4" {{ request('bab') === 'BAB 4' ? 'selected' : '' }}>BAB 4</option>
        <option value="BAB 5" {{ request('bab') === 'BAB 5' ? 'selected' : '' }}>BAB 5</option>
      </select>
    </form>

  </div>

  <!-- VIEW 1: TABLE VIEW -->
  <div x-show="viewMode === 'table'" class="border border-[#E9E9E7] rounded-xl overflow-hidden bg-[#FFFFFF] shadow-xs">
    <div class="overflow-x-auto">
      <table class="w-full text-left text-xs">
        <thead class="bg-[#F7F7F5] text-[#787774] uppercase text-[10px] tracking-wider border-b border-[#E9E9E7]">
          <tr>
            <th class="py-2.5 px-4 font-semibold">Tgl / Bab</th>
            @if (!auth()->user()->isMahasiswa())
              <th class="py-2.5 px-4 font-semibold">Mahasiswa</th>
            @endif
            <th class="py-2.5 px-4 font-semibold">Aktivitas Progres</th>
            <th class="py-2.5 px-4 font-semibold">Kendala / Rencana</th>
            <th class="py-2.5 px-4 font-semibold">Dokumen Draf</th>
            <th class="py-2.5 px-4 font-semibold">Catatan Evaluasi Dosen</th>
            <th class="py-2.5 px-4 font-semibold">Status</th>
            <th class="py-2.5 px-4 text-right font-semibold">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-[#E9E9E7]">
          @forelse ($logbooks as $log)
            <tr class="hover:bg-[#F7F7F5] transition-colors">
              <td class="py-3 px-4 whitespace-nowrap">
                <span class="font-bold text-[#37352F] block">{{ $log->tanggal->format('d M Y') }}</span>
                <span class="notion-pill-gray text-[10px] mt-0.5">{{ $log->bab_progress }}</span>
              </td>

              @if (!auth()->user()->isMahasiswa())
                <td class="py-3 px-4">
                  @if ($log->tugasAkhir->anggota && $log->tugasAkhir->anggota->isNotEmpty())
                    @foreach ($log->tugasAkhir->anggota as $member)
                      <p class="font-semibold text-[#37352F] text-xs leading-tight">{{ $member->user->name ?? '-' }}</p>
                      <p class="text-[10px] text-[#787774] mb-1">NIM: {{ $member->nim }}</p>
                    @endforeach
                  @else
                    <p class="font-semibold text-[#37352F]">{{ $log->tugasAkhir->mahasiswa->user->name ?? '-' }}</p>
                    <p class="text-[10px] text-[#787774]">{{ $log->tugasAkhir->mahasiswa->nim ?? '-' }}</p>
                  @endif
                </td>
              @endif

              <td class="py-3 px-4 max-w-xs">
                <p class="font-medium text-[#37352F] line-clamp-2 leading-relaxed">{{ $log->deskripsi_kegiatan }}</p>
              </td>

              <td class="py-3 px-4 max-w-xs text-[#787774]">
                @if ($log->kendala)
                  <p class="text-[11px] text-[#B86200]"><strong class="font-semibold">Kendala:</strong> {{ $log->kendala }}</p>
                @endif
                @if ($log->rencana_berikutnya)
                  <p class="text-[11px] text-[#787774] mt-0.5"><strong class="font-semibold">Rencana:</strong> {{ $log->rencana_berikutnya }}</p>
                @endif
                @if (!$log->kendala && !$log->rencana_berikutnya)
                  <span class="text-[11px]">-</span>
                @endif
              </td>

              <td class="py-3 px-4 whitespace-nowrap">
                @if ($log->file_dokumen_mhs)
                  <a href="{{ asset('storage/' . $log->file_dokumen_mhs) }}" target="_blank" class="inline-flex items-center gap-1 text-[11px] font-semibold text-[#37352F] bg-[#F7F7F5] px-2 py-1 rounded border border-[#E9E9E7] hover:bg-[#EBEBE9]">
                    <span>📄</span> Draf Mhs
                  </a>
                @else
                  <span class="text-[11px] text-[#787774]">-</span>
                @endif

                @if ($log->file_revisi_dosen)
                  <a href="{{ asset('storage/' . $log->file_revisi_dosen) }}" target="_blank" class="inline-flex items-center gap-1 text-[11px] font-semibold text-red-700 bg-red-50 px-2 py-1 rounded border border-red-200 hover:bg-red-100 ml-1">
                    <span>✏️</span> Coretan Dosen
                  </a>
                @endif
              </td>

              <td class="py-3 px-4 max-w-xs text-[#37352F] italic">
                {{ $log->catatan_dosen ?? 'Belum ada catatan evaluasi' }}
                @if ($log->verifiedBy)
                  <span class="block not-italic text-[10px] text-[#787774] mt-0.5">— {{ $log->verifiedBy->user->name }}</span>
                @endif
              </td>

              <td class="py-3 px-4 whitespace-nowrap">
                @if ($log->status_verifikasi === 'disetujui')
                  <span class="notion-pill-green">Disetujui</span>
                @elseif ($log->status_verifikasi === 'revisi')
                  <span class="notion-pill-red">Perlu Revisi</span>
                @else
                  <span class="notion-pill-orange">Pending Review</span>
                @endif
              </td>

              <td class="py-3 px-4 text-right whitespace-nowrap">
                @if (auth()->user()->isDosen() && auth()->user()->isPembimbingOf($log->tugasAkhir))
                  <button @click="openReview({{ $log->toJson() }})" class="notion-btn-primary text-xs py-1 px-2.5">
                    Review & Revisi
                  </button>
                @elseif (auth()->user()->isDosen())
                  <span class="text-[11px] text-[#787774]">Bukan Pembimbing</span>
                @else
                  <span class="text-[11px] text-[#787774]">Terdata</span>
                @endif
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="8" class="py-8 text-center text-[#787774]">
                Tidak ada data logbook yang ditemukan.
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    @if ($logbooks->hasPages())
      <div class="p-3 border-t border-[#E9E9E7] bg-[#F7F7F5]">
        {{ $logbooks->links() }}
      </div>
    @endif
  </div>

  <!-- VIEW 2: KANBAN BOARD VIEW -->
  <div x-show="viewMode === 'kanban'" class="grid grid-cols-1 md:grid-cols-3 gap-4">
    <!-- Col 1: Pending -->
    <div class="bg-[#F7F7F5] rounded-xl p-3 border border-[#E9E9E7]">
      <div class="flex items-center justify-between pb-2 border-b border-[#E9E9E7] mb-3">
        <span class="font-bold text-xs text-[#37352F]">Pending Review</span>
        <span class="notion-pill-orange text-[10px]">{{ $logbooks->where('status_verifikasi', 'pending')->count() }}</span>
      </div>
      <div class="space-y-2">
        @foreach ($logbooks->where('status_verifikasi', 'pending') as $item)
          <div class="bg-[#FFFFFF] p-3 rounded border border-[#E9E9E7] shadow-xs">
            <div class="flex items-center justify-between text-[10px] text-[#787774] mb-1">
              <span>{{ $item->bab_progress }}</span>
              <span>{{ $item->tanggal->format('d M Y') }}</span>
            </div>
            <p class="text-xs font-semibold text-[#37352F] line-clamp-2">{{ $item->deskripsi_kegiatan }}</p>
          </div>
        @endforeach
      </div>
    </div>

    <!-- Col 2: Revisi -->
    <div class="bg-[#F7F7F5] rounded-xl p-3 border border-[#E9E9E7]">
      <div class="flex items-center justify-between pb-2 border-b border-[#E9E9E7] mb-3">
        <span class="font-bold text-xs text-[#37352F]">Perlu Revisi</span>
        <span class="notion-pill-red text-[10px]">{{ $logbooks->where('status_verifikasi', 'revisi')->count() }}</span>
      </div>
      <div class="space-y-2">
        @foreach ($logbooks->where('status_verifikasi', 'revisi') as $item)
          <div class="bg-[#FFFFFF] p-3 rounded border border-[#E9E9E7] shadow-xs">
            <div class="flex items-center justify-between text-[10px] text-[#787774] mb-1">
              <span>{{ $item->bab_progress }}</span>
              <span>{{ $item->tanggal->format('d M Y') }}</span>
            </div>
            <p class="text-xs font-semibold text-[#37352F] line-clamp-2">{{ $item->deskripsi_kegiatan }}</p>
            <p class="text-[11px] text-red-700 italic mt-1">{{ $item->catatan_dosen }}</p>
          </div>
        @endforeach
      </div>
    </div>

    <!-- Col 3: Disetujui -->
    <div class="bg-[#F7F7F5] rounded-xl p-3 border border-[#E9E9E7]">
      <div class="flex items-center justify-between pb-2 border-b border-[#E9E9E7] mb-3">
        <span class="font-bold text-xs text-[#37352F]">Disetujui</span>
        <span class="notion-pill-green text-[10px]">{{ $logbooks->where('status_verifikasi', 'disetujui')->count() }}</span>
      </div>
      <div class="space-y-2">
        @foreach ($logbooks->where('status_verifikasi', 'disetujui') as $item)
          <div class="bg-[#FFFFFF] p-3 rounded border border-[#E9E9E7] shadow-xs">
            <div class="flex items-center justify-between text-[10px] text-[#787774] mb-1">
              <span>{{ $item->bab_progress }}</span>
              <span>{{ $item->tanggal->format('d M Y') }}</span>
            </div>
            <p class="text-xs font-semibold text-[#37352F] line-clamp-2">{{ $item->deskripsi_kegiatan }}</p>
          </div>
        @endforeach
      </div>
    </div>
  </div>

  <!-- VIEW 3: LIST VIEW -->
  <div x-show="viewMode === 'list'" class="space-y-2">
    @foreach ($logbooks as $item)
      <div class="p-3 bg-[#FFFFFF] border border-[#E9E9E7] rounded-lg flex items-center justify-between text-xs">
        <div class="flex items-center gap-3">
          <span class="notion-pill-gray text-[10px]">{{ $item->bab_progress }}</span>
          <p class="font-semibold text-[#37352F]">{{ $item->tanggal->format('d M Y') }} — {{ Str::limit($item->deskripsi_kegiatan, 80) }}</p>
        </div>
        <div>
          @if ($item->status_verifikasi === 'disetujui')
            <span class="notion-pill-green">Disetujui</span>
          @elseif ($item->status_verifikasi === 'revisi')
            <span class="notion-pill-red">Perlu Revisi</span>
          @else
            <span class="notion-pill-orange">Pending</span>
          @endif
        </div>
      </div>
    @endforeach
  </div>

  <!-- ================================================================= -->
  <!-- MODAL: ISI LOGBOOK BARU (Untuk Mahasiswa) -->
  <!-- ================================================================= -->
  @if (auth()->user()->isMahasiswa())
    @php
      $myTa = auth()->user()->tugasAkhir();
    @endphp

    <div x-show="openCreateModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
      <div class="fixed inset-0 bg-[#0A0A0B]/40 backdrop-blur-xs" @click="openCreateModal = false"></div>
      <div class="flex min-h-full items-center justify-center p-4">
        <div class="relative w-full max-w-lg bg-[#FFFFFF] border border-[#E9E9E7] rounded-xl shadow-xl p-6 z-10" @click.away="openCreateModal = false">
          
          <div class="flex items-center justify-between pb-3 border-b border-[#E9E9E7] mb-4">
            <div>
              <h3 class="text-base font-bold text-[#37352F]">Catat Entri Logbook Aktivitas Baru</h3>
              <p class="text-xs text-[#787774]">Laporkan capaian pengerjaan bab tugas akhir Anda</p>
            </div>
            <button @click="openCreateModal = false" class="text-gray-400 hover:text-black">&times;</button>
          </div>

          @if ($myTa)
            <form action="{{ route('logbook.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4 text-xs">
              @csrf
              <input type="hidden" name="tugas_akhir_id" value="{{ $myTa->id }}">

              <div class="grid grid-cols-2 gap-3">
                <div>
                  <label class="block font-semibold uppercase tracking-wider text-[#787774] mb-1">Tanggal Kegiatan</label>
                  <input type="date" name="tanggal" value="{{ date('Y-m-d') }}" required class="w-full px-3 py-2 bg-[#FFFFFF] border border-[#E9E9E7] rounded text-[#37352F] focus:outline-none focus:border-[#2C2C2F]">
                </div>
                <div>
                  <label class="block font-semibold uppercase tracking-wider text-[#787774] mb-1">Fokus Bab Progres</label>
                  <select name="bab_progress" required class="w-full px-3 py-2 bg-[#FFFFFF] border border-[#E9E9E7] rounded text-[#37352F] focus:outline-none focus:border-[#2C2C2F]">
                    <option value="BAB 1">BAB 1 - Pendahuluan</option>
                    <option value="BAB 2">BAB 2 - Tinjauan Pustaka</option>
                    <option value="BAB 3">BAB 3 - Metodologi</option>
                    <option value="BAB 4" selected>BAB 4 - Implementasi & Hasil</option>
                    <option value="BAB 5">BAB 5 - Kesimpulan</option>
                    <option value="Pengujian">Pengujian Sistem / Model</option>
                    <option value="Lainnya">Lainnya</option>
                  </select>
                </div>
              </div>

              <div>
                <label class="block font-semibold uppercase tracking-wider text-[#787774] mb-1">Deskripsi Kegiatan & Progres Naskah</label>
                <textarea name="deskripsi_kegiatan" rows="3" required placeholder="Jelaskan bagian subbab yang disusun atau eksperimen yang dilakukan..." class="w-full px-3 py-2 bg-[#FFFFFF] border border-[#E9E9E7] rounded text-[#37352F] focus:outline-none focus:border-[#2C2C2F]"></textarea>
              </div>

              <div>
                <label class="block font-semibold uppercase tracking-wider text-[#787774] mb-1">Kendala / Masalah yang Dihadapi (Opsional)</label>
                <input type="text" name="kendala" placeholder="Contoh: Overfitting model pada kelas DoS..." class="w-full px-3 py-2 bg-[#FFFFFF] border border-[#E9E9E7] rounded text-[#37352F] focus:outline-none focus:border-[#2C2C2F]">
              </div>

              <div>
                <label class="block font-semibold uppercase tracking-wider text-[#787774] mb-1">Rencana Kerja Berikutnya</label>
                <input type="text" name="rencana_berikutnya" placeholder="Contoh: Menyusun confusion matrix dan evaluasi ROC-AUC..." class="w-full px-3 py-2 bg-[#FFFFFF] border border-[#E9E9E7] rounded text-[#37352F] focus:outline-none focus:border-[#2C2C2F]">
              </div>

              <div>
                <label class="block font-semibold uppercase tracking-wider text-[#787774] mb-1">Unggah Dokumen Progres (PDF / DOCX, maks 25MB)</label>
                <input type="file" name="file_dokumen_mhs" accept=".pdf,.docx,.zip" class="w-full px-3 py-2 bg-[#F7F7F5] border border-[#E9E9E7] rounded text-[#37352F]">
              </div>

              <div class="flex items-center justify-end gap-2 pt-3 border-t border-[#E9E9E7]">
                <button type="button" @click="openCreateModal = false" class="notion-btn-secondary text-xs">Batal</button>
                <button type="submit" class="notion-btn-primary text-xs">Simpan Logbook</button>
              </div>
            </form>
          @else
            <p class="text-xs text-red-600">Anda belum memiliki Tugas Akhir yang terdaftar. Hubungi Koordinator TA.</p>
          @endif

        </div>
      </div>
    </div>
  @endif

  @if (auth()->user()->isDosen())
  <!-- ================================================================= -->
  <!-- SLIDE-OVER DRAWER: REVIEW LOGBOOK OLEH DOSEN PEMBIMBING -->
  <!-- ================================================================= -->
  <div x-show="reviewDrawerOpen" x-cloak class="fixed inset-0 z-50 overflow-hidden" style="display: none;">
    <div class="fixed inset-0 bg-[#0A0A0B]/40 backdrop-blur-xs" @click="reviewDrawerOpen = false"></div>
    <div class="fixed inset-y-0 right-0 max-w-full flex pl-10">
      <div class="w-screen max-w-xl bg-[#FFFFFF] border-l border-[#E9E9E7] shadow-2xl flex flex-col p-6 overflow-y-auto">
        
        <div class="flex items-center justify-between pb-3 border-b border-[#E9E9E7] mb-4">
          <div>
            <h3 class="text-base font-bold text-[#37352F]">Evaluasi & Verifikasi Logbook Mahasiswa</h3>
            <p class="text-xs text-[#787774]">Berikan umpan balik catatan dan unggah coretan revisi dokumen</p>
          </div>
          <button @click="reviewDrawerOpen = false" class="text-gray-400 hover:text-black">&times;</button>
        </div>

        <template x-if="selectedLogbook">
          <form :action="'/logbook/' + selectedLogbook.id + '/review'" method="POST" enctype="multipart/form-data" class="space-y-4 text-xs">
            @csrf
            @method('PATCH')

            <!-- Info Mahasiswa & Logbook -->
            <div class="p-3 bg-[#F7F6F3] rounded border border-[#E9E9E7] space-y-1">
              <p class="text-xs font-bold text-[#37352F]" x-text="'Bab: ' + selectedLogbook.bab_progress + ' (' + selectedLogbook.tanggal + ')'"></p>
              <p class="text-[#37352F] leading-relaxed" x-text="selectedLogbook.deskripsi_kegiatan"></p>
              <template x-if="selectedLogbook.kendala">
                <p class="text-amber-800 text-[11px]" x-text="'Kendala: ' + selectedLogbook.kendala"></p>
              </template>
            </div>

            <!-- Form Catatan Revisi Dosen -->
            <div>
              <label class="block font-semibold uppercase tracking-wider text-[#787774] mb-1">Catatan Evaluasi / Arahan Revisi Dosen</label>
              <textarea name="catatan_dosen" rows="4" required x-model="selectedLogbook.catatan_dosen"
                        placeholder="Tuliskan arahan perbaikan penulisan naskah atau pengujian model..."
                        class="w-full px-3 py-2 bg-[#FFFFFF] border border-[#E9E9E7] rounded text-[#37352F] focus:outline-none focus:border-[#2C2C2F]"></textarea>
            </div>

            <!-- Upload File Revisi Coretan -->
            <div>
              <label class="block font-semibold uppercase tracking-wider text-[#787774] mb-1">Unggah Dokumen Hasil Coretan Revisi (PDF / DOCX)</label>
              <input type="file" name="file_revisi_dosen" accept=".pdf,.docx,.zip" class="w-full px-3 py-2 bg-[#F7F7F5] border border-[#E9E9E7] rounded text-[#37352F]">
            </div>

            <!-- Pilihan Status Verifikasi -->
            <div>
              <label class="block font-semibold uppercase tracking-wider text-[#787774] mb-1.5">Keputusan Status Verifikasi</label>
              <div class="grid grid-cols-3 gap-2">
                <label class="p-2 border border-[#C4E5D1] bg-[#EBF5EE] rounded text-center cursor-pointer flex flex-col items-center">
                  <input type="radio" name="status_verifikasi" value="disetujui" :checked="selectedLogbook.status_verifikasi === 'disetujui'">
                  <span class="text-xs font-bold text-[#2A7E4B] mt-1">Disetujui</span>
                </label>
                <label class="p-2 border border-red-200 bg-red-50 rounded text-center cursor-pointer flex flex-col items-center">
                  <input type="radio" name="status_verifikasi" value="revisi" :checked="selectedLogbook.status_verifikasi === 'revisi'">
                  <span class="text-xs font-bold text-red-800 mt-1">Perlu Revisi</span>
                </label>
                <label class="p-2 border border-[#E9E9E7] bg-[#F7F7F5] rounded text-center cursor-pointer flex flex-col items-center">
                  <input type="radio" name="status_verifikasi" value="pending" :checked="selectedLogbook.status_verifikasi === 'pending'">
                  <span class="text-xs font-bold text-[#787774] mt-1">Pending</span>
                </label>
              </div>
            </div>

            <div class="flex items-center justify-end gap-2 pt-4 border-t border-[#E9E9E7]">
              <button type="button" @click="reviewDrawerOpen = false" class="notion-btn-secondary text-xs">Tutup</button>
              <button type="submit" class="notion-btn-primary text-xs">Simpan Evaluasi Dosen</button>
            </div>
          </form>
        </template>

      </div>
    </div>
  </div>
  @endif

</div>
@endsection

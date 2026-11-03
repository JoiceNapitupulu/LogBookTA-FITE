{{-- Screen 3: Workspace Dosen Pembimbing --}}
<div x-show="$store.app.currentScreen === 'dosen_workspace'" class="p-6 max-w-7xl mx-auto space-y-6">

  <!-- Header Banner Dosen Pembimbing -->
  <div class="bg-[#FFFFFF] border border-[#E0E0E0] rounded-xl p-6 shadow-sm">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
      <div>
        <div class="flex items-center gap-2 mb-2">
          <span class="pill-neutral text-xs">Fakultas Informatika & Teknik Elektro</span>
          <span class="pill-approved text-xs">Dosen Pembimbing Aktif</span>
        </div>
        <h1 class="text-xl md:text-2xl font-bold text-[#0A0A0B]">Workspace Review Progres & Bimbingan TA</h1>
        <p class="text-xs text-[#5D5D5F] mt-1">Dosen: <strong class="text-[#0A0A0B]">Dr. Johannes Harungguan, S.T., M.T.</strong> • NIDN: 0118048201</p>
      </div>

      <div class="flex items-center gap-3">
        <button @click="$store.app.openModal('appointment')" class="btn-primary text-sm">
          <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
            <line x1="16" y1="2" x2="16" y2="6"></line>
            <line x1="8" y1="2" x2="8" y2="6"></line>
            <line x1="3" y1="10" x2="21" y2="10"></line>
          </svg>
          + Jadwalkan Bimbingan Mhs
        </button>
      </div>
    </div>

    <!-- Quick Stats Metric ala Notion Cards -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mt-6 pt-6 border-t border-[#E0E0E0]">
      <div class="p-3 bg-[#F5F5F5] rounded-lg border border-[#E0E0E0]">
        <p class="text-xs text-[#5D5D5F]">Total Mhs Bimbingan</p>
        <p class="text-xl font-bold text-[#0A0A0B] mt-0.5">6 Mahasiswa</p>
      </div>
      <div class="p-3 bg-[#FFFBEB] rounded-lg border border-[#FDE68A]">
        <p class="text-xs text-amber-800">Menunggu Review</p>
        <p class="text-xl font-bold text-amber-900 mt-0.5">3 Logbook</p>
      </div>
      <div class="p-3 bg-[#EBF5EE] rounded-lg border border-[#C4E5D1]">
        <p class="text-xs text-emerald-800">Siap Daftar Sidang</p>
        <p class="text-xl font-bold text-emerald-900 mt-0.5">2 Mahasiswa</p>
      </div>
      <div class="p-3 bg-[#F5F5F5] rounded-lg border border-[#E0E0E0]">
        <p class="text-xs text-[#5D5D5F]">Jadwal Minggu Ini</p>
        <p class="text-xl font-bold text-[#0A0A0B] mt-0.5">4 Sesi Temu</p>
      </div>
    </div>
  </div>

  <!-- Tabel Mahasiswa Bimbingan -->
  <div class="bg-[#FFFFFF] border border-[#E0E0E0] rounded-xl overflow-hidden shadow-sm">
    <div class="p-5 border-b border-[#E0E0E0] flex flex-col md:flex-row md:items-center justify-between gap-3">
      <div>
        <h2 class="text-base font-semibold text-[#0A0A0B]">Daftar Mahasiswa Asistensi & Progres Bab</h2>
        <p class="text-xs text-[#5D5D5F]">Pilih salah satu mahasiswa untuk membuka panel Split-View review dokumen dan pemberian umpan balik</p>
      </div>
      <div class="flex items-center gap-2">
        <input type="text" placeholder="Filter nama / NIM..." class="px-3 py-1.5 rounded-md border border-[#E0E0E0] text-xs focus:outline-none focus:border-[#0A0A0B]">
        <span class="text-xs text-[#5D5D5F]">Tampilkan: <strong>6 Data</strong></span>
      </div>
    </div>

    <div class="overflow-x-auto">
      <table class="w-full text-left text-sm">
        <thead class="bg-[#F5F5F5] text-[11px] uppercase tracking-wider text-[#4D4D4F] border-b border-[#E0E0E0]">
          <tr>
            <th class="py-3 px-4 font-semibold">Mahasiswa & NIM</th>
            <th class="py-3 px-4 font-semibold">Judul Tugas Akhir</th>
            <th class="py-3 px-4 font-semibold">Capaian Bab</th>
            <th class="py-3 px-4 font-semibold">Entri Terakhir</th>
            <th class="py-3 px-4 font-semibold">Status Logbook</th>
            <th class="py-3 px-4 text-right font-semibold">Aksi Review</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-[#E0E0E0]">

          <!-- Mhs 1: Samuel Siahaan -->
          <tr class="hover-item transition-colors bg-amber-50/20">
            <td class="py-3.5 px-4">
              <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-full bg-[#2C2C2F] text-white flex items-center justify-center font-bold text-xs">
                  SS
                </div>
                <div>
                  <p class="font-semibold text-[#0A0A0B]">Samuel Siahaan</p>
                  <p class="text-xs text-[#5D5D5F]">11S21004 • S1 Informatika</p>
                </div>
              </div>
            </td>
            <td class="py-3.5 px-4 max-w-xs">
              <p class="text-xs text-[#0A0A0B] font-medium line-clamp-2">Sistem Deteksi Intrusi Jaringan Berbasis Deep Learning pada Infrastruktur IT Del</p>
              <span class="text-[11px] text-[#5D5D5F]">Pembimbing 1: Dr. Johannes</span>
            </td>
            <td class="py-3.5 px-4">
              <span class="inline-block px-2.5 py-1 text-xs font-semibold rounded bg-[#FFFFFF] border-2 border-[#0A0A0B] text-[#0A0A0B]">
                BAB 4 (Eksperimen)
              </span>
            </td>
            <td class="py-3.5 px-4 text-xs text-[#3D3D3F] whitespace-nowrap">
              <p class="font-medium text-[#0A0A0B]">28 Okt 2026</p>
              <span class="text-[11px] text-[#5D5D5F]">Draf_Bab4_Model_v2.pdf</span>
            </td>
            <td class="py-3.5 px-4">
              <span class="pill-revision">Perlu Revisi</span>
            </td>
            <td class="py-3.5 px-4 text-right whitespace-nowrap">
              <button @click="$store.app.openModal('dosenReviewDrawer')" class="btn-primary text-xs py-1.5 px-3">
                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                  <circle cx="12" cy="12" r="3"></circle>
                </svg>
                Tinjau Split-View
              </button>
            </td>
          </tr>

          <!-- Mhs 2: Yohana Sinaga -->
          <tr class="hover-item transition-colors">
            <td class="py-3.5 px-4">
              <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-full bg-[#3D3D3F] text-white flex items-center justify-center font-bold text-xs">
                  YS
                </div>
                <div>
                  <p class="font-semibold text-[#0A0A0B]">Yohana Sinaga</p>
                  <p class="text-xs text-[#5D5D5F]">11S21033 • S1 Informatika</p>
                </div>
              </div>
            </td>
            <td class="py-3.5 px-4 max-w-xs">
              <p class="text-xs text-[#0A0A0B] font-medium line-clamp-2">Klasifikasi Citra Motif Tenun Ulos Batak Menggunakan Convolutional Neural Network</p>
            </td>
            <td class="py-3.5 px-4">
              <span class="inline-block px-2.5 py-1 text-xs font-semibold rounded bg-[#EBF5EE] text-[#1F7A4C] border border-[#C4E5D1]">
                BAB 5 (Kesimpulan)
              </span>
            </td>
            <td class="py-3.5 px-4 text-xs text-[#3D3D3F] whitespace-nowrap">
              <p class="font-medium text-[#0A0A0B]">26 Okt 2026</p>
              <span class="text-[11px] text-[#5D5D5F]">Draf_Bab5_Final.docx</span>
            </td>
            <td class="py-3.5 px-4">
              <span class="pill-pending">Menunggu Review</span>
            </td>
            <td class="py-3.5 px-4 text-right whitespace-nowrap">
              <button @click="$store.app.openModal('dosenReviewDrawer')" class="btn-secondary text-xs py-1.5 px-3">
                Review Berkas
              </button>
            </td>
          </tr>

          <!-- Mhs 3: Daniel Situmorang -->
          <tr class="hover-item transition-colors">
            <td class="py-3.5 px-4">
              <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-full bg-[#5D5D5F] text-white flex items-center justify-center font-bold text-xs">
                  DS
                </div>
                <div>
                  <p class="font-semibold text-[#0A0A0B]">Daniel Situmorang</p>
                  <p class="text-xs text-[#5D5D5F]">12S21012 • S1 Sistem Informasi</p>
                </div>
              </div>
            </td>
            <td class="py-3.5 px-4 max-w-xs">
              <p class="text-xs text-[#0A0A0B] font-medium line-clamp-2">Perancangan Enterprise Architecture Sistem Logistik Danau Toba Menggunakan TOGAF ADM</p>
            </td>
            <td class="py-3.5 px-4">
              <span class="inline-block px-2.5 py-1 text-xs font-semibold rounded bg-[#EBF5EE] text-[#1F7A4C] border border-[#C4E5D1]">
                Sidang Akhir
              </span>
            </td>
            <td class="py-3.5 px-4 text-xs text-[#3D3D3F] whitespace-nowrap">
              <p class="font-medium text-[#0A0A0B]">21 Okt 2026</p>
              <span class="text-[11px] text-[#5D5D5F]">Naskah_Lengkap_TA.pdf</span>
            </td>
            <td class="py-3.5 px-4">
              <span class="pill-approved">Disetujui Ujian</span>
            </td>
            <td class="py-3.5 px-4 text-right whitespace-nowrap">
              <span class="text-xs text-[#5D5D5F] py-1 px-2">Siap Sidang</span>
            </td>
          </tr>

        </tbody>
      </table>
    </div>
  </div>

  <!-- Quick Action & Pedoman Bimbingan Dosen -->
  <div class="bg-[#F5F5F5] border border-[#E0E0E0] rounded-xl p-5 text-xs text-[#3D3D3F] flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div class="flex items-center gap-3">
      <div class="w-8 h-8 rounded-md bg-[#FFFFFF] border border-[#E0E0E0] flex items-center justify-center text-[#0A0A0B]">
        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <circle cx="12" cy="12" r="10"></circle>
          <line x1="12" y1="16" x2="12" y2="12"></line>
          <line x1="12" y1="8" x2="12.01" y2="8"></line>
        </svg>
      </div>
      <div>
        <p class="font-semibold text-[#0A0A0B]">Ketentuan Bimbingan FITE IT Del</p>
        <p class="text-[#5D5D5F]">Mahasiswa wajib memenuhi minimal 8 kali catatan asistensi logbook terverifikasi untuk mengajukan Seminar TA & Sidang Akhir.</p>
      </div>
    </div>
    <div class="flex items-center gap-2 shrink-0">
      <button @click="$store.app.showToast('Format lembar pengesahan FITE berhasil diunduh')" class="btn-secondary text-xs">
        Template Lembar Pengesahan TA
      </button>
    </div>
  </div>

</div>

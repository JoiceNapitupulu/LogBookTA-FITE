{{-- Screen 2: Dashboard & Progress Tracker Mahasiswa TA --}}
<div x-show="$store.app.currentScreen === 'mahasiswa_dashboard'" class="p-6 max-w-7xl mx-auto space-y-6">

  <!-- Header Banner: Judul TA & Data Pembimbing -->
  <div class="bg-[#FFFFFF] border border-[#E0E0E0] rounded-xl p-6 shadow-sm">
    <div class="flex flex-col md:flex-row md:items-start justify-between gap-4">
      <div>
        <div class="flex items-center gap-2 mb-2">
          <span class="pill-neutral text-xs">S1 Informatika • Angkatan 2021</span>
          <span class="pill-approved text-xs">TA Aktif • Semester Genap 2025/2026</span>
          <span class="text-xs text-[#5D5D5F]">NIM: 11S21004</span>
        </div>
        <h1 class="text-xl md:text-2xl font-bold text-[#0A0A0B] leading-snug">
          Pengembangan Sistem Deteksi Intrusi Jaringan Berbasis Deep Learning pada Infrastruktur Jaringan Kampus IT Del
        </h1>
        <div class="mt-3 flex flex-wrap items-center gap-x-6 gap-y-2 text-xs text-[#3D3D3F]">
          <div class="flex items-center gap-1.5">
            <span class="text-[#5D5D5F]">Pembimbing 1:</span>
            <span class="font-semibold text-[#0A0A0B]">Dr. Johannes Harungguan, S.T., M.T.</span>
          </div>
          <span class="text-[#CCCCCC]">•</span>
          <div class="flex items-center gap-1.5">
            <span class="text-[#5D5D5F]">Pembimbing 2:</span>
            <span class="font-semibold text-[#0A0A0B]">Marlina Valentina, S.Kom., M.Sc.</span>
          </div>
          <span class="text-[#CCCCCC]">•</span>
          <div class="flex items-center gap-1.5">
            <span class="text-[#5D5D5F]">Total Bimbingan Terverifikasi:</span>
            <span class="font-semibold text-[#1F7A4C] bg-[#EBF5EE] px-2 py-0.5 rounded">9 Sesi (Memenuhi Syarat Ujian)</span>
          </div>
        </div>
      </div>

      <div class="flex items-center gap-2 self-start shrink-0">
        <button @click="$store.app.openModal('chapterProgress')" class="btn-secondary text-sm">
          <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M12 20h9"></path>
            <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path>
          </svg>
          Perbarui Capaian Bab TA
        </button>
        <button @click="$store.app.openModal('logbook')" class="btn-primary text-sm">
          <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <line x1="12" y1="5" x2="12" y2="19"></line>
            <line x1="5" y1="12" x2="19" y2="12"></line>
          </svg>
          + Isi Logbook Baru
        </button>
      </div>
    </div>
  </div>

  <!-- BAGIAN ATAS: Stepper Horizontal Pencapaian BAB TA -->
  <div class="bg-[#FFFFFF] border border-[#E0E0E0] rounded-xl p-6 shadow-sm">
    <div class="flex items-center justify-between mb-4">
      <div>
        <h2 class="text-base font-semibold text-[#0A0A0B]">Stepper Pencapaian Bab Tugas Akhir</h2>
        <p class="text-xs text-[#5D5D5F]">Pelacakan bertahap dari tahap proposal hingga persiapan sidang akhir kelulusan</p>
      </div>
      <div class="flex items-center gap-2">
        <span class="text-xs font-semibold text-[#2C2C2F]">Total Capaian: 68%</span>
        <div class="w-24 bg-[#E0E0E0] rounded-full h-2 overflow-hidden">
          <div class="bg-[#0A0A0B] h-2 rounded-full" style="width: 68%"></div>
        </div>
      </div>
    </div>

    <!-- Stepper Blocks ala Notion -->
    <div class="grid grid-cols-2 md:grid-cols-7 gap-3">
      <!-- Step 1: Proposal -->
      <div class="p-3.5 rounded-lg border border-[#C4E5D1] bg-[#EBF5EE] transition-all">
        <div class="flex items-center justify-between mb-1.5">
          <span class="text-[11px] font-bold text-[#1F7A4C] uppercase tracking-wider">Tahap 1</span>
          <svg class="w-4 h-4 text-[#1F7A4C]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
            <polyline points="20 6 9 17 4 12"></polyline>
          </svg>
        </div>
        <p class="text-sm font-semibold text-[#0A0A0B]">Proposal TA</p>
        <p class="text-[11px] text-[#1F7A4C] mt-0.5">Lulus Sempro</p>
      </div>

      <!-- Step 2: BAB 1 -->
      <div class="p-3.5 rounded-lg border border-[#C4E5D1] bg-[#EBF5EE] transition-all">
        <div class="flex items-center justify-between mb-1.5">
          <span class="text-[11px] font-bold text-[#1F7A4C] uppercase tracking-wider">Tahap 2</span>
          <svg class="w-4 h-4 text-[#1F7A4C]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
            <polyline points="20 6 9 17 4 12"></polyline>
          </svg>
        </div>
        <p class="text-sm font-semibold text-[#0A0A0B]">BAB 1</p>
        <p class="text-[11px] text-[#1F7A4C] mt-0.5">Disetujui Dosen</p>
      </div>

      <!-- Step 3: BAB 2 -->
      <div class="p-3.5 rounded-lg border border-[#C4E5D1] bg-[#EBF5EE] transition-all">
        <div class="flex items-center justify-between mb-1.5">
          <span class="text-[11px] font-bold text-[#1F7A4C] uppercase tracking-wider">Tahap 3</span>
          <svg class="w-4 h-4 text-[#1F7A4C]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
            <polyline points="20 6 9 17 4 12"></polyline>
          </svg>
        </div>
        <p class="text-sm font-semibold text-[#0A0A0B]">BAB 2</p>
        <p class="text-[11px] text-[#1F7A4C] mt-0.5">Tinjauan Pustaka</p>
      </div>

      <!-- Step 4: BAB 3 -->
      <div class="p-3.5 rounded-lg border border-[#C4E5D1] bg-[#EBF5EE] transition-all">
        <div class="flex items-center justify-between mb-1.5">
          <span class="text-[11px] font-bold text-[#1F7A4C] uppercase tracking-wider">Tahap 4</span>
          <svg class="w-4 h-4 text-[#1F7A4C]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
            <polyline points="20 6 9 17 4 12"></polyline>
          </svg>
        </div>
        <p class="text-sm font-semibold text-[#0A0A0B]">BAB 3</p>
        <p class="text-[11px] text-[#1F7A4C] mt-0.5">Metodologi & Desain</p>
      </div>

      <!-- Step 5: BAB 4 (In Progress / Revisi) -->
      <div class="p-3.5 rounded-lg border-2 border-[#0A0A0B] bg-[#FFFFFF] shadow-sm relative">
        <div class="flex items-center justify-between mb-1.5">
          <span class="text-[11px] font-bold text-[#0A0A0B] uppercase tracking-wider">Tahap 5</span>
          <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
        </div>
        <p class="text-sm font-bold text-[#0A0A0B]">BAB 4</p>
        <p class="text-[11px] font-medium text-amber-700 mt-0.5">Revisi Eksperimen</p>
      </div>

      <!-- Step 6: BAB 5 -->
      <div class="p-3.5 rounded-lg border border-[#E0E0E0] bg-[#F5F5F5] opacity-75">
        <div class="flex items-center justify-between mb-1.5">
          <span class="text-[11px] font-medium text-[#5D5D5F] uppercase tracking-wider">Tahap 6</span>
          <svg class="w-3.5 h-3.5 text-[#5D5D5F]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
            <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
          </svg>
        </div>
        <p class="text-sm font-medium text-[#4D4D4F]">BAB 5</p>
        <p class="text-[11px] text-[#5D5D5F] mt-0.5">Kesimpulan & Saran</p>
      </div>

      <!-- Step 7: Sidang Akhir -->
      <div class="p-3.5 rounded-lg border border-[#E0E0E0] bg-[#F5F5F5] opacity-60">
        <div class="flex items-center justify-between mb-1.5">
          <span class="text-[11px] font-medium text-[#5D5D5F] uppercase tracking-wider">Final</span>
          <svg class="w-3.5 h-3.5 text-[#5D5D5F]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="12" cy="8" r="7"></circle>
            <polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"></polyline>
          </svg>
        </div>
        <p class="text-sm font-medium text-[#4D4D4F]">Sidang Akhir</p>
        <p class="text-[11px] text-[#5D5D5F] mt-0.5">Yudisium & Kelulusan</p>
      </div>
    </div>
  </div>

  <!-- BAGIAN TENGAH: Logbook Aktivitas & Progres (Notion Database Table View) -->
  <div class="bg-[#FFFFFF] border border-[#E0E0E0] rounded-xl overflow-hidden shadow-sm">
    <div class="p-5 border-b border-[#E0E0E0] flex flex-col md:flex-row md:items-center justify-between gap-3">
      <div>
        <div class="flex items-center gap-2">
          <h2 class="text-base font-semibold text-[#0A0A0B]">Logbook Aktivitas & Progres</h2>
          <span class="px-2 py-0.5 text-xs font-semibold rounded bg-[#F5F5F5] text-[#3D3D3F] border border-[#E0E0E0]">8 Catatan Terdata</span>
        </div>
        <p class="text-xs text-[#5D5D5F] mt-0.5">Semua entri aktivitas asistensi, pengerjaan dokumen bab, dan tanggapan revisi dosen pembimbing</p>
      </div>

      <div class="flex items-center gap-2">
        <div class="relative text-xs">
          <input type="text" placeholder="Cari logbook..." class="pl-8 pr-3 py-1.5 rounded-md border border-[#E0E0E0] text-xs focus:outline-none focus:border-[#0A0A0B] w-48">
          <svg class="w-3.5 h-3.5 text-[#5D5D5F] absolute left-2.5 top-2.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="11" cy="11" r="8"></circle>
            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
          </svg>
        </div>
        <select class="text-xs rounded-md border border-[#E0E0E0] px-3 py-1.5 bg-[#FFFFFF] text-[#2C2C2F] focus:outline-none focus:border-[#0A0A0B]">
          <option>Semua Bab</option>
          <option>BAB 4: Implementasi</option>
          <option>BAB 3: Metodologi</option>
          <option>BAB 2: Tinjauan Pustaka</option>
          <option>BAB 1: Pendahuluan</option>
        </select>
      </div>
    </div>

    <!-- Tabel Data Logbook ala Notion -->
    <div class="overflow-x-auto">
      <table class="w-full text-left text-sm">
        <thead class="bg-[#F5F5F5] text-[11px] uppercase tracking-wider text-[#4D4D4F] border-b border-[#E0E0E0]">
          <tr>
            <th class="py-3 px-4 font-semibold">Tgl / Bab</th>
            <th class="py-3 px-4 font-semibold">Deskripsi Aktivitas</th>
            <th class="py-3 px-4 font-semibold">Kendala / Masalah</th>
            <th class="py-3 px-4 font-semibold">Dokumen Progres</th>
            <th class="py-3 px-4 font-semibold">Status & Catatan Dosen</th>
            <th class="py-3 px-4 text-right font-semibold">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-[#E0E0E0]">
          
          <!-- Row 1: Menunggu Review / Revisi -->
          <tr class="hover-item transition-colors">
            <td class="py-3.5 px-4 align-top whitespace-nowrap">
              <span class="font-semibold text-[#0A0A0B] block">28 Okt 2026</span>
              <span class="inline-block mt-1 px-2 py-0.5 text-[11px] font-bold rounded bg-[#F5F5F5] text-[#0A0A0B] border border-[#E0E0E0]">BAB 4</span>
            </td>
            <td class="py-3.5 px-4 align-top max-w-xs">
              <p class="font-medium text-[#0A0A0B] line-clamp-2">Pengujian arsitektur Convolutional 1D dan LSTM pada dataset lalu lintas packet IT Del (testbed GD 512).</p>
              <span class="text-[11px] text-[#5D5D5F] mt-1 block">Durasi: 4.5 Jam riset lab</span>
            </td>
            <td class="py-3.5 px-4 align-top max-w-xs text-xs text-[#3D3D3F]">
              <span class="text-amber-800 bg-amber-50 px-1.5 py-0.5 rounded border border-amber-200">Overfitting pada epoch ke-35 akibat ketidakseimbangan kelas serangan DoS.</span>
            </td>
            <td class="py-3.5 px-4 align-top whitespace-nowrap">
              <a href="javascript:void(0)" class="inline-flex items-center gap-1.5 text-xs text-[#0A0A0B] font-medium hover:underline bg-[#F5F5F5] px-2.5 py-1.5 rounded border border-[#E0E0E0]">
                <svg class="w-3.5 h-3.5 text-red-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                  <polyline points="14 2 14 8 20 8"></polyline>
                </svg>
                Draf_Bab4_Model_v2.pdf
              </a>
            </td>
            <td class="py-3.5 px-4 align-top">
              <span class="pill-revision mb-1.5">Revisi Pembimbing</span>
              <p class="text-xs text-[#3D3D3F] mt-1 italic">"Perjelas perbandingan akurasi confusion matrix dan lakukan oversampling SMOTE..."</p>
              <p class="text-[10px] text-[#5D5D5F] mt-0.5">— Dr. Johannes Harungguan</p>
            </td>
            <td class="py-3.5 px-4 align-top text-right whitespace-nowrap">
              <button @click="$store.app.openModal('dosenReviewDrawer')" class="btn-secondary text-xs py-1 px-2.5">
                Detail Review
              </button>
            </td>
          </tr>

          <!-- Row 2: Disetujui -->
          <tr class="hover-item transition-colors">
            <td class="py-3.5 px-4 align-top whitespace-nowrap">
              <span class="font-semibold text-[#0A0A0B] block">19 Okt 2026</span>
              <span class="inline-block mt-1 px-2 py-0.5 text-[11px] font-bold rounded bg-[#F5F5F5] text-[#0A0A0B] border border-[#E0E0E0]">BAB 3</span>
            </td>
            <td class="py-3.5 px-4 align-top max-w-xs">
              <p class="font-medium text-[#0A0A0B] line-clamp-2">Finalisasi diagram alur sistem deteksi anomali jaringan dan skema data pipeline.</p>
              <span class="text-[11px] text-[#5D5D5F] mt-1 block">Bimbingan luring di GD 514</span>
            </td>
            <td class="py-3.5 px-4 align-top max-w-xs text-xs text-[#5D5D5F]">
              <span>Tidak ada kendala, metodologi disetujui penuh oleh kedua pembimbing.</span>
            </td>
            <td class="py-3.5 px-4 align-top whitespace-nowrap">
              <a href="javascript:void(0)" class="inline-flex items-center gap-1.5 text-xs text-[#0A0A0B] font-medium hover:underline bg-[#F5F5F5] px-2.5 py-1.5 rounded border border-[#E0E0E0]">
                <svg class="w-3.5 h-3.5 text-red-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                </svg>
                Bab3_Metodologi_Final.pdf
              </a>
            </td>
            <td class="py-3.5 px-4 align-top">
              <span class="pill-approved mb-1">Disetujui Penuh</span>
              <p class="text-xs text-[#1F7A4C] mt-1">"Bagus. Sudah siap dilanjutkan ke implementasi kode pengujian Bab 4."</p>
            </td>
            <td class="py-3.5 px-4 align-top text-right whitespace-nowrap">
              <span class="text-xs text-[#5D5D5F]">Terverifikasi</span>
            </td>
          </tr>

          <!-- Row 3: Disetujui -->
          <tr class="hover-item transition-colors">
            <td class="py-3.5 px-4 align-top whitespace-nowrap">
              <span class="font-semibold text-[#0A0A0B] block">05 Okt 2026</span>
              <span class="inline-block mt-1 px-2 py-0.5 text-[11px] font-bold rounded bg-[#F5F5F5] text-[#0A0A0B] border border-[#E0E0E0]">BAB 2</span>
            </td>
            <td class="py-3.5 px-4 align-top max-w-xs">
              <p class="font-medium text-[#0A0A0B] line-clamp-2">Penyusunan kajian pustaka: perbandingan transformer vs CNN untuk packet time-series.</p>
              <span class="text-[11px] text-[#5D5D5F] mt-1 block">Asistensi daring via Zoom</span>
            </td>
            <td class="py-3.5 px-4 align-top max-w-xs text-xs text-[#5D5D5F]">
              <span>Keterbatasan akses paper IEEE terkini (telah dibantu TA FITE).</span>
            </td>
            <td class="py-3.5 px-4 align-top whitespace-nowrap">
              <a href="javascript:void(0)" class="inline-flex items-center gap-1.5 text-xs text-[#0A0A0B] font-medium hover:underline bg-[#F5F5F5] px-2.5 py-1.5 rounded border border-[#E0E0E0]">
                <svg class="w-3.5 h-3.5 text-blue-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                </svg>
                Bab2_Literature_Review.docx
              </a>
            </td>
            <td class="py-3.5 px-4 align-top">
              <span class="pill-approved mb-1">Disetujui</span>
              <p class="text-xs text-[#3D3D3F] mt-1">"Sitasi telah sesuai dengan format standar IEEE FITE."</p>
            </td>
            <td class="py-3.5 px-4 align-top text-right whitespace-nowrap">
              <span class="text-xs text-[#5D5D5F]">Terverifikasi</span>
            </td>
          </tr>

        </tbody>
      </table>
    </div>
  </div>

  <!-- BAGIAN BAWAH: Panel Detail Feedback Dosen Pembimbing Terbaru -->
  <div class="bg-[#FFFFFF] border border-[#E0E0E0] rounded-xl p-6 shadow-sm">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 pb-4 border-b border-[#E0E0E0]">
      <div class="flex items-center gap-3">
        <div class="w-10 h-10 rounded-full bg-[#1B1B1E] text-white flex items-center justify-center font-bold text-sm">
          JH
        </div>
        <div>
          <h3 class="text-sm font-semibold text-[#0A0A0B]">Catatan Evaluasi Dosen Pembimbing Terbaru</h3>
          <p class="text-xs text-[#5D5D5F]">Dr. Johannes Harungguan, S.T., M.T. • Tanggal 28 Okt 2026, 17:15 WIB</p>
        </div>
      </div>
      <div class="flex items-center gap-2">
        <span class="pill-revision">Status: Perlu Revisi Bab 4</span>
        <button @click="$store.app.showToast('Mengunduh Coretan_Revisi_Bab4_Dr_Johannes.pdf...')" class="btn-secondary text-xs">
          <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
            <polyline points="7 10 12 15 17 10"></polyline>
            <line x1="12" y1="15" x2="12" y2="3"></line>
          </svg>
          Unduh File Coretan Dosen (PDF)
        </button>
      </div>
    </div>

    <div class="mt-4 p-4 rounded-lg bg-[#F5F5F5] border border-[#E0E0E0] text-sm text-[#2C2C2F] space-y-2">
      <p class="font-medium text-[#0A0A0B]">Ulasan Evaluasi & Rekomendasi Perbaikan:</p>
      <ul class="list-disc list-inside text-xs space-y-1.5 text-[#3D3D3F]">
        <li>Pada subbab 4.2.1, jelaskan alasan pemilihan rasio pembagian data 80:20 versus 70:30.</li>
        <li>Bagan kurva ROC-AUC harus dilengkapi dengan perbandingan baseline model SVM klasik kampus.</li>
        <li>Periksa kembali sitasi standar IT Del FITE pada halaman 47. Coretan tinta merah telah diunggah di file revisi.</li>
      </ul>
      <p class="text-[11px] text-[#5D5D5F] pt-2 border-t border-[#E0E0E0]">
        Silakan perbaiki dokumen draf Bab 4 Anda dan unggah kembali melalui tombol <span class="font-semibold text-[#0A0A0B]">+ Isi Logbook Baru</span>.
      </p>
    </div>
  </div>

</div>

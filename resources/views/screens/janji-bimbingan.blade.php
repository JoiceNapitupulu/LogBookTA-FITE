{{-- Screen 4: Modul Janji Bimbingan Dua Arah & Alert Notifikasi --}}
<div x-show="$store.app.currentScreen === 'janji_bimbingan'" class="p-6 max-w-7xl mx-auto space-y-6">

  <!-- Header Modul Janji Bimbingan -->
  <div class="bg-[#FFFFFF] border border-[#E0E0E0] rounded-xl p-6 shadow-sm">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
      <div>
        <div class="flex items-center gap-2 mb-2">
          <span class="pill-neutral text-xs">Asistensi & Pertemuan Ilmiah</span>
          <span class="pill-approved text-xs">Dua Arah (Mahasiswa & Dosen)</span>
        </div>
        <h1 class="text-xl md:text-2xl font-bold text-[#0A0A0B]">Jadwal & Janji Bimbingan Tugas Akhir</h1>
        <p class="text-xs text-[#5D5D5F] mt-1">Koordinasikan sesi asistensi luring (Ruang Dosen/Lab FITE) maupun daring (Zoom/Google Meet) secara terintegrasi.</p>
      </div>

      <div class="flex items-center gap-3">
        <button @click="$store.app.openModal('appointment')" class="btn-primary text-sm">
          <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <line x1="12" y1="5" x2="12" y2="19"></line>
            <line x1="5" y1="12" x2="19" y2="12"></line>
          </svg>
          + Ajukan Janji Bimbingan
        </button>
      </div>
    </div>
  </div>

  <!-- Sesi Menunggu Persetujuan (Action Required) -->
  <div class="bg-[#FFFFFF] border border-[#E0E0E0] rounded-xl p-6 shadow-sm">
    <div class="flex items-center justify-between mb-4">
      <div class="flex items-center gap-2">
        <span class="w-2.5 h-2.5 rounded-full bg-amber-500 animate-pulse"></span>
        <h2 class="text-base font-semibold text-[#0A0A0B]">Menunggu Persetujuan (Pending Approval)</h2>
      </div>
      <span class="text-xs text-[#5D5D5F]">Aksi cepat persetujuan dua arah</span>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
      
      <!-- Card Request 1 -->
      <div class="p-4 rounded-lg border border-[#FDE68A] bg-[#FFFBEB]/50 flex flex-col justify-between">
        <div>
          <div class="flex items-center justify-between mb-2">
            <span class="pill-pending text-[11px]">Menunggu Konfirmasi Anda</span>
            <span class="text-xs font-semibold text-[#8F6B12]">Jumat, 31 Okt 2026 • 14:00 WIB</span>
          </div>
          <h3 class="text-sm font-bold text-[#0A0A0B]">Konsultasi Bab 4: Arsitektur Model Deep Learning & Uji Coba Dataset Kampus</h3>
          <p class="text-xs text-[#4D4D4F] mt-1.5 leading-relaxed">
            Pengaju: <strong class="text-[#0A0A0B]">Samuel Siahaan (11S21004)</strong> kepada <strong class="text-[#0A0A0B]">Dr. Johannes Harungguan</strong>
          </p>
          <div class="mt-2.5 flex items-center gap-2 text-xs text-[#5D5D5F]">
            <span class="inline-flex items-center gap-1 bg-[#FFFFFF] px-2 py-0.5 rounded border border-[#E0E0E0]">
              <svg class="w-3.5 h-3.5 text-[#2C2C2F]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                <circle cx="12" cy="10" r="3"></circle>
              </svg>
              Tatap Muka • Ruang Dosen GD 512
            </span>
          </div>
        </div>

        <div class="mt-4 pt-3 border-t border-[#FDE68A] flex items-center justify-end gap-2">
          <button @click="$store.app.showToast('Janji bimbingan ditolak dengan catatan pemberitahuan ke mahasiswa', 'error')" class="btn-secondary text-xs py-1.5 px-3">
            Tolak
          </button>
          <button @click="$store.app.showToast('Janji bimbingan disetujui! Notifikasi otomatis terkirim.')" class="btn-primary text-xs py-1.5 px-3">
            <svg class="w-3.5 h-3.5 text-emerald-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
              <polyline points="20 6 9 17 4 12"></polyline>
            </svg>
            Setujui Jadwal
          </button>
        </div>
      </div>

      <!-- Card Request 2 -->
      <div class="p-4 rounded-lg border border-[#E0E0E0] bg-[#FFFFFF] flex flex-col justify-between">
        <div>
          <div class="flex items-center justify-between mb-2">
            <span class="pill-neutral text-[11px]">Diinisiasi oleh Dosen</span>
            <span class="text-xs font-semibold text-[#2C2C2F]">Senin, 03 Nov 2026 • 10:00 WIB</span>
          </div>
          <h3 class="text-sm font-bold text-[#0A0A0B]">Evaluasi Progress Bab 5 & Kesiapan Kelengkapan Naskah Seminar</h3>
          <p class="text-xs text-[#4D4D4F] mt-1.5 leading-relaxed">
            Pengundang: <strong class="text-[#0A0A0B]">Marlina Valentina, M.Sc.</strong> kepada <strong class="text-[#0A0A0B]">Yohana Sinaga (11S21033)</strong>
          </p>
          <div class="mt-2.5 flex items-center gap-2 text-xs text-[#5D5D5F]">
            <span class="inline-flex items-center gap-1 bg-[#F5F5F5] px-2 py-0.5 rounded border border-[#E0E0E0]">
              <svg class="w-3.5 h-3.5 text-blue-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polygon points="23 7 16 12 23 17 23 7"></polygon>
                <rect x="1" y="5" width="15" height="14" rx="2" ry="2"></rect>
              </svg>
              Daring • Zoom Meeting IT Del Room 2
            </span>
          </div>
        </div>

        <div class="mt-4 pt-3 border-t border-[#E0E0E0] flex items-center justify-end gap-2">
          <span class="text-xs text-[#5D5D5F]">Menunggu tanggapan mahasiswa</span>
        </div>
      </div>

    </div>
  </div>

  <!-- Kalender & Riwayat Sesi Terjadwal -->
  <div class="bg-[#FFFFFF] border border-[#E0E0E0] rounded-xl overflow-hidden shadow-sm">
    <div class="p-5 border-b border-[#E0E0E0] flex flex-col md:flex-row md:items-center justify-between gap-3">
      <div>
        <h2 class="text-base font-semibold text-[#0A0A0B]">Daftar Jadwal Bimbingan Terkonfirmasi</h2>
        <p class="text-xs text-[#5D5D5F]">Sesi yang telah disetujui bersama dan siap dilaksanakan</p>
      </div>

      <div class="flex items-center gap-2">
        <button class="px-3 py-1 rounded bg-[#0A0A0B] text-white text-xs font-medium">Bulan Ini</button>
        <button class="px-3 py-1 rounded bg-[#F5F5F5] hover:bg-[#EBEBEB] text-[#4D4D4F] text-xs font-medium border border-[#E0E0E0]">Mendatang</button>
        <button class="px-3 py-1 rounded bg-[#F5F5F5] hover:bg-[#EBEBEB] text-[#4D4D4F] text-xs font-medium border border-[#E0E0E0]">Riwayat Selesai</button>
      </div>
    </div>

    <div class="overflow-x-auto">
      <table class="w-full text-left text-sm">
        <thead class="bg-[#F5F5F5] text-[11px] uppercase tracking-wider text-[#4D4D4F] border-b border-[#E0E0E0]">
          <tr>
            <th class="py-3 px-4 font-semibold">Waktu Pelaksanaan</th>
            <th class="py-3 px-4 font-semibold">Peserta Bimbingan</th>
            <th class="py-3 px-4 font-semibold">Topik Konsultasi</th>
            <th class="py-3 px-4 font-semibold">Metode & Lokasi</th>
            <th class="py-3 px-4 font-semibold">Status Sesi</th>
            <th class="py-3 px-4 text-right font-semibold">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-[#E0E0E0]">
          <tr class="hover-item transition-colors">
            <td class="py-3.5 px-4 whitespace-nowrap">
              <span class="font-semibold text-[#0A0A0B] block">Kamis, 06 Nov 2026</span>
              <span class="text-xs text-[#5D5D5F]">09:30 - 11:00 WIB</span>
            </td>
            <td class="py-3.5 px-4">
              <p class="font-medium text-[#0A0A0B]">Samuel Siahaan</p>
              <p class="text-xs text-[#5D5D5F]">Pembimbing: Dr. Johannes Harungguan</p>
            </td>
            <td class="py-3.5 px-4 max-w-xs">
              <p class="text-xs font-medium text-[#0A0A0B]">Review Bab 4 Pasca-Revisi SMOTE & Pembahasan Bab 5</p>
            </td>
            <td class="py-3.5 px-4 text-xs">
              <span class="inline-flex items-center gap-1 text-[#0A0A0B] font-medium">
                <svg class="w-3.5 h-3.5 text-blue-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <polygon points="23 7 16 12 23 17 23 7"></polygon>
                  <rect x="1" y="5" width="15" height="14" rx="2" ry="2"></rect>
                </svg>
                Zoom FITE Room 1
              </span>
              <a href="https://zoom.us/j/itdel-fite" target="_blank" class="block text-[11px] text-blue-600 hover:underline">zoom.us/j/itdel-fite</a>
            </td>
            <td class="py-3.5 px-4">
              <span class="pill-approved">Terkonfirmasi</span>
            </td>
            <td class="py-3.5 px-4 text-right whitespace-nowrap">
              <button @click="$store.app.showToast('Tautan pengingat Google Calendar dikirim')" class="btn-icon">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                  <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                </svg>
              </button>
            </td>
          </tr>

          <tr class="hover-item transition-colors">
            <td class="py-3.5 px-4 whitespace-nowrap">
              <span class="font-semibold text-[#0A0A0B] block">Jumat, 24 Okt 2026</span>
              <span class="text-xs text-[#5D5D5F]">14:00 - 15:30 WIB</span>
            </td>
            <td class="py-3.5 px-4">
              <p class="font-medium text-[#0A0A0B]">Samuel Siahaan</p>
              <p class="text-xs text-[#5D5D5F]">Pembimbing: Marlina Valentina, M.Sc.</p>
            </td>
            <td class="py-3.5 px-4 max-w-xs">
              <p class="text-xs font-medium text-[#0A0A0B]">Verifikasi Format Penulisan Naskah Ilmiah & Sitasi Bab 3</p>
            </td>
            <td class="py-3.5 px-4 text-xs">
              <span class="inline-flex items-center gap-1 text-[#0A0A0B] font-medium">
                <svg class="w-3.5 h-3.5 text-[#2C2C2F]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                  <circle cx="12" cy="10" r="3"></circle>
                </svg>
                Ruang Dosen GD 514
              </span>
            </td>
            <td class="py-3.5 px-4">
              <span class="pill-neutral">Selesai Terlaksana</span>
            </td>
            <td class="py-3.5 px-4 text-right whitespace-nowrap">
              <span class="text-xs text-[#5D5D5F]">Logbook Terbit</span>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>

</div>

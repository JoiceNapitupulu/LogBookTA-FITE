{{-- Screen 7: Panel Eksekutif Kaprodi & Koordinator TA --}}
<div x-show="$store.app.currentScreen === 'panel_eksekutif'" 
     x-data="{ 
       activeProdiTab: 'all',
       koordinatorList: [
         { id: 1, name: 'Marlina Valentina, S.Kom., M.Sc.', nidn: '0124088701', prodi: 'S1 Informatika', isActive: true, mhsCount: 48 },
         { id: 2, name: 'Samuel P. Pandiangan, M.Kom.', nidn: '0115028902', prodi: 'S1 Sistem Informasi', isActive: true, mhsCount: 42 },
         { id: 3, name: 'Dr. Arnaldo Marulitua Sinaga', nidn: '0102067801', prodi: 'S1 Teknik Elektro', isActive: false, mhsCount: 36 },
         { id: 4, name: 'Dr. Johannes Harungguan', nidn: '0118048201', prodi: 'S1 Informatika', isActive: false, mhsCount: 45 }
       ]
     }"
     class="p-6 max-w-7xl mx-auto space-y-6">

  <!-- Header Banner Eksekutif Kaprodi & Koordinator -->
  <div class="bg-[#FFFFFF] border border-[#E0E0E0] rounded-xl p-6 shadow-sm">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
      <div>
        <div class="flex items-center gap-2 mb-2">
          <span class="pill-neutral text-xs">Pimpinan FITE IT Del</span>
          <span class="pill-approved text-xs">Monitoring Mutu Akademik</span>
        </div>
        <h1 class="text-xl md:text-2xl font-bold text-[#0A0A0B]">Panel Eksekutif & Statistik Kelulusan TA</h1>
        <p class="text-xs text-[#5D5D5F] mt-1">Laporan analitik, sebaran progres bab mahasiswa per angkatan, dan pengelolaan penetapan Koordinator TA.</p>
      </div>

      <div class="flex items-center gap-2">
        <button @click="$store.app.showToast('Mengekspor Laporan_Statistik_TA_FITE_2026.xlsx...')" class="btn-secondary text-sm">
          <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
            <polyline points="7 10 12 15 17 10"></polyline>
            <line x1="12" y1="15" x2="12" y2="3"></line>
          </svg>
          Ekspor Excel
        </button>
      </div>
    </div>

    <!-- Kartu Metrik Monokrom Minimalis ala Notion -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-6 pt-6 border-t border-[#E0E0E0]">
      
      <div class="p-4 bg-[#F5F5F5] rounded-xl border border-[#E0E0E0]">
        <div class="flex items-center justify-between text-xs text-[#5D5D5F]">
          <span>Total Mhs TA Aktif</span>
          <span class="pill-neutral text-[10px]">Genap 25/26</span>
        </div>
        <p class="text-3xl font-extrabold text-[#0A0A0B] mt-2">148</p>
        <p class="text-[11px] text-[#5D5D5F] mt-1">Terbagi di 3 Program Studi</p>
      </div>

      <div class="p-4 bg-[#F5F5F5] rounded-xl border border-[#E0E0E0]">
        <div class="flex items-center justify-between text-xs text-[#5D5D5F]">
          <span>Proyeksi Lulus Tepat Waktu</span>
          <span class="pill-approved text-[10px]">+4.2% YoY</span>
        </div>
        <p class="text-3xl font-extrabold text-[#0A0A0B] mt-2">89.4%</p>
        <p class="text-[11px] text-[#1F7A4C] mt-1">132 dari 148 on-track</p>
      </div>

      <div class="p-4 bg-[#F5F5F5] rounded-xl border border-[#E0E0E0]">
        <div class="flex items-center justify-between text-xs text-[#5D5D5F]">
          <span>Rasio Beban Bimbingan</span>
          <span class="pill-neutral text-[10px]">Standar Dikti</span>
        </div>
        <p class="text-3xl font-extrabold text-[#0A0A0B] mt-2">1 : 5.8</p>
        <p class="text-[11px] text-[#5D5D5F] mt-1">Batas ideal maks 1:8 mhs/dosen</p>
      </div>

      <div class="p-4 bg-[#0A0A0B] text-white rounded-xl border border-[#0A0A0B]">
        <div class="flex items-center justify-between text-xs text-[#D6D6D6]">
          <span>Siap Sidang Akhir</span>
          <span class="bg-[#2C2C2F] text-white text-[10px] px-2 py-0.5 rounded-full">Gelombang 1</span>
        </div>
        <p class="text-3xl font-extrabold text-[#FFFFFF] mt-2">38</p>
        <p class="text-[11px] text-[#CCCCCC] mt-1">Pendaftaran ditutup 15 Nov</p>
      </div>

    </div>
  </div>

  <!-- BAGIAN KHUSUS KAPRODI: Penetapan Koordinator TA -->
  <div class="bg-[#FFFFFF] border border-[#E0E0E0] rounded-xl overflow-hidden shadow-sm">
    <div class="p-5 border-b border-[#E0E0E0] flex flex-col md:flex-row md:items-center justify-between gap-3">
      <div>
        <div class="flex items-center gap-2">
          <h2 class="text-base font-semibold text-[#0A0A0B]">Pengelolaan & Penetapan Koordinator Tugas Akhir</h2>
          <span class="pill-neutral text-xs">Otoritas Ketua Program Studi (Kaprodi)</span>
        </div>
        <p class="text-xs text-[#5D5D5F] mt-0.5">Kaprodi menetapkan dosen aktif sebagai Koordinator TA untuk mengelola penunjukan pembimbing dan penjadwalan ujian program studi</p>
      </div>
      <button @click="$store.app.openModal('assignCoordinator')" class="btn-primary text-xs py-2 px-3">
        + Angkat Koordinator Baru
      </button>
    </div>

    <div class="overflow-x-auto">
      <table class="w-full text-left text-sm">
        <thead class="bg-[#F5F5F5] text-[11px] uppercase tracking-wider text-[#4D4D4F] border-b border-[#E0E0E0]">
          <tr>
            <th class="py-3 px-4 font-semibold">Nama Dosen & NIDN</th>
            <th class="py-3 px-4 font-semibold">Program Studi</th>
            <th class="py-3 px-4 font-semibold">Mahasiswa Terkoordinasi</th>
            <th class="py-3 px-4 font-semibold">Status Penugasan</th>
            <th class="py-3 px-4 text-right font-semibold">Toggle Status / Opsi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-[#E0E0E0]">
          <template x-for="item in koordinatorList" :key="item.id">
            <tr class="hover-item transition-colors">
              <td class="py-3.5 px-4">
                <p class="font-semibold text-[#0A0A0B]" x-text="item.name"></p>
                <p class="text-xs text-[#5D5D5F]" x-text="'NIDN: ' + item.nidn"></p>
              </td>
              <td class="py-3.5 px-4 text-xs font-medium text-[#2C2C2F]" x-text="item.prodi"></td>
              <td class="py-3.5 px-4 text-xs">
                <span class="font-bold text-[#0A0A0B]" x-text="item.mhsCount"></span> Mahasiswa
              </td>
              <td class="py-3.5 px-4">
                <span :class="item.isActive ? 'pill-approved' : 'pill-neutral'" x-text="item.isActive ? 'Koordinator Aktif' : 'Non-Aktif'"></span>
              </td>
              <td class="py-3.5 px-4 text-right">
                <button @click="item.isActive = !item.isActive; $store.app.showToast('Status penugasan ' + item.name + ' diperbarui!')" 
                  :class="item.isActive ? 'bg-[#0A0A0B] text-white' : 'bg-[#E0E0E0] text-[#3D3D3F]'"
                  class="text-xs px-3 py-1.5 rounded-full transition-colors font-medium">
                  <span x-text="item.isActive ? 'Nonaktifkan' : 'Aktifkan'"></span>
                </button>
              </td>
            </tr>
          </template>
        </tbody>
      </table>
    </div>
  </div>

  <!-- BAGIAN GRAFIK & ANALITIK KELULUSAN TA (Notion Style Clean Data Visualizations) -->
  <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

    <!-- Grafik 1: Statistik Kelulusan TA per Angkatan -->
    <div class="bg-[#FFFFFF] border border-[#E0E0E0] rounded-xl p-6 shadow-sm">
      <div class="flex items-center justify-between mb-4">
        <div>
          <h3 class="text-sm font-semibold text-[#0A0A0B]">Statistik Kelulusan Tepat Waktu (4 Tahun)</h3>
          <p class="text-xs text-[#5D5D5F]">Tren 4 angkatan terakhir mahasiswa FITE IT Del</p>
        </div>
        <span class="text-xs font-semibold text-[#1F7A4C] bg-[#EBF5EE] px-2 py-0.5 rounded">Target: &gt; 85%</span>
      </div>

      <!-- Minimal Bar Chart SVG -->
      <div class="space-y-4 pt-2">
        <div>
          <div class="flex justify-between text-xs font-medium mb-1">
            <span class="text-[#0A0A0B]">Angkatan 2021 (Tahun Ajaran Aktif 2025/2026)</span>
            <span class="font-bold text-[#0A0A0B]">89.4% (Proyeksi)</span>
          </div>
          <div class="w-full bg-[#EBEBEB] rounded-full h-3 overflow-hidden">
            <div class="bg-[#0A0A0B] h-3 rounded-full transition-all duration-500" style="width: 89.4%"></div>
          </div>
        </div>

        <div>
          <div class="flex justify-between text-xs font-medium mb-1">
            <span class="text-[#0A0A0B]">Angkatan 2020 (Lulus 2024/2025)</span>
            <span class="font-bold text-[#0A0A0B]">87.2%</span>
          </div>
          <div class="w-full bg-[#EBEBEB] rounded-full h-3 overflow-hidden">
            <div class="bg-[#2C2C2F] h-3 rounded-full transition-all duration-500" style="width: 87.2%"></div>
          </div>
        </div>

        <div>
          <div class="flex justify-between text-xs font-medium mb-1">
            <span class="text-[#0A0A0B]">Angkatan 2019 (Lulus 2023/2024)</span>
            <span class="font-bold text-[#0A0A0B]">82.5%</span>
          </div>
          <div class="w-full bg-[#EBEBEB] rounded-full h-3 overflow-hidden">
            <div class="bg-[#4D4D4F] h-3 rounded-full transition-all duration-500" style="width: 82.5%"></div>
          </div>
        </div>

        <div>
          <div class="flex justify-between text-xs font-medium mb-1">
            <span class="text-[#0A0A0B]">Angkatan 2018 (Lulus 2022/2023)</span>
            <span class="font-bold text-[#0A0A0B]">78.9%</span>
          </div>
          <div class="w-full bg-[#EBEBEB] rounded-full h-3 overflow-hidden">
            <div class="bg-[#5D5D5F] h-3 rounded-full transition-all duration-500" style="width: 78.9%"></div>
          </div>
        </div>
      </div>
    </div>

    <!-- Grafik 2: Rekapitulasi Sebaran Progres Bab Mahasiswa TA Saat Ini -->
    <div class="bg-[#FFFFFF] border border-[#E0E0E0] rounded-xl p-6 shadow-sm">
      <div class="flex items-center justify-between mb-4">
        <div>
          <h3 class="text-sm font-semibold text-[#0A0A0B]">Rekapitulasi Sebaran Progres Bab TA</h3>
          <p class="text-xs text-[#5D5D5F]">Distribusi 148 mahasiswa pada semester berjalan</p>
        </div>
        <span class="text-xs text-[#5D5D5F]">Semester Genap 25/26</span>
      </div>

      <div class="grid grid-cols-2 gap-3 pt-2">
        <div class="p-3 bg-[#F5F5F5] rounded-lg border border-[#E0E0E0]">
          <p class="text-xs text-[#5D5D5F]">Proposal & Sempro</p>
          <p class="text-xl font-bold text-[#0A0A0B] mt-1">12 Mhs</p>
          <div class="w-full bg-[#E0E0E0] h-1.5 rounded-full mt-2">
            <div class="bg-[#5D5D5F] h-1.5 rounded-full" style="width: 8%"></div>
          </div>
        </div>

        <div class="p-3 bg-[#F5F5F5] rounded-lg border border-[#E0E0E0]">
          <p class="text-xs text-[#5D5D5F]">BAB 1 - 2 (Pustaka)</p>
          <p class="text-xl font-bold text-[#0A0A0B] mt-1">28 Mhs</p>
          <div class="w-full bg-[#E0E0E0] h-1.5 rounded-full mt-2">
            <div class="bg-[#4D4D4F] h-1.5 rounded-full" style="width: 19%"></div>
          </div>
        </div>

        <div class="p-3 bg-[#F5F5F5] rounded-lg border border-[#E0E0E0]">
          <p class="text-xs text-[#5D5D5F]">BAB 3 (Metodologi)</p>
          <p class="text-xl font-bold text-[#0A0A0B] mt-1">35 Mhs</p>
          <div class="w-full bg-[#E0E0E0] h-1.5 rounded-full mt-2">
            <div class="bg-[#2C2C2F] h-1.5 rounded-full" style="width: 24%"></div>
          </div>
        </div>

        <div class="p-3 bg-[#F5F5F5] rounded-lg border border-[#E0E0E0]">
          <p class="text-xs text-[#5D5D5F]">BAB 4 - 5 (Hasil)</p>
          <p class="text-xl font-bold text-[#0A0A0B] mt-1">45 Mhs</p>
          <div class="w-full bg-[#E0E0E0] h-1.5 rounded-full mt-2">
            <div class="bg-[#0A0A0B] h-1.5 rounded-full" style="width: 30%"></div>
          </div>
        </div>

        <div class="col-span-2 p-3 bg-[#EBF5EE] rounded-lg border border-[#C4E5D1] flex items-center justify-between">
          <div>
            <p class="text-xs font-bold text-[#1F7A4C]">Siap Sidang Akhir / Yudisium</p>
            <p class="text-xs text-[#1F7A4C]">Lengkap 8x asistensi & draf final</p>
          </div>
          <p class="text-2xl font-extrabold text-[#1F7A4C]">28 Mhs</p>
        </div>
      </div>
    </div>

  </div>

</div>

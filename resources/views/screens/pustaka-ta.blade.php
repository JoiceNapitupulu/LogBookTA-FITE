{{-- Screen 6: Pustaka Arsip Tugas Akhir Terdahulu --}}
<div x-show="$store.app.currentScreen === 'pustaka_ta'" 
     x-data="{ 
       searchQuery: '', 
       selectedProdi: 'all', 
       selectedYear: 'all', 
       selectedTag: 'all',
       viewMode: 'table'
     }" 
     class="p-6 max-w-7xl mx-auto space-y-6">

  <!-- Header Banner Repositori Pustaka -->
  <div class="bg-[#FFFFFF] border border-[#E0E0E0] rounded-xl p-6 shadow-sm">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
      <div>
        <div class="flex items-center gap-2 mb-2">
          <span class="pill-neutral text-xs">Repositori Digital FITE IT Del</span>
          <span class="pill-approved text-xs">Akses Terbuka Sivitas Akademika</span>
        </div>
        <h1 class="text-xl md:text-2xl font-bold text-[#0A0A0B]">Pustaka Arsip Tugas Akhir Terdahulu</h1>
        <p class="text-xs text-[#5D5D5F] mt-1">Basis referensi riset, metodologi, dan karya ilmiah alumni Fakultas Informatika dan Teknik Elektro - Institut Teknologi Del.</p>
      </div>

      <!-- Tombol Khusus Teaching Assistant untuk Unggah Arsip -->
      <div class="flex items-center gap-3">
        <template x-if="$store.app.currentRole === 'ta'">
          <button @click="$store.app.openModal('uploadArchive')" class="btn-primary text-sm">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
              <polyline points="17 8 12 3 7 8"></polyline>
              <line x1="12" y1="3" x2="12" y2="15"></line>
            </svg>
            + Unggah Arsip Tugas Akhir
          </button>
        </template>
        <template x-if="$store.app.currentRole !== 'ta'">
          <div class="text-xs text-[#5D5D5F] bg-[#F5F5F5] px-3 py-1.5 rounded-md border border-[#E0E0E0]">
            Mode: <strong>Read-Only Repository</strong>
          </div>
        </template>
      </div>
    </div>

    <!-- Search Bar Besar ala Notion -->
    <div class="mt-6">
      <div class="relative">
        <input type="text" x-model="searchQuery" placeholder="Cari berdasarkan judul naskah, nama penulis, NIM, atau dosen pembimbing..." 
          class="w-full pl-11 pr-4 py-3 bg-[#F5F5F5] border border-[#E0E0E0] rounded-lg text-sm text-[#0A0A0B] placeholder-[#5D5D5F] focus:outline-none focus:bg-[#FFFFFF] focus:ring-1 focus:ring-[#0A0A0B] focus:border-[#0A0A0B] transition-all">
        <svg class="w-5 h-5 text-[#4D4D4F] absolute left-3.5 top-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <circle cx="11" cy="11" r="8"></circle>
          <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
        </svg>
      </div>
    </div>

    <!-- Filter Pills Minimalis -->
    <div class="mt-4 pt-4 border-t border-[#EBEBEB] flex flex-wrap items-center justify-between gap-3 text-xs">
      
      <!-- Filter Prodi -->
      <div class="flex flex-wrap items-center gap-1.5">
        <span class="text-[#5D5D5F] font-semibold mr-1">Program Studi:</span>
        <button @click="selectedProdi = 'all'" :class="selectedProdi === 'all' ? 'bg-[#0A0A0B] text-white' : 'bg-[#F5F5F5] text-[#3D3D3F] hover:bg-[#EBEBEB]'" class="px-2.5 py-1 rounded-full border border-[#E0E0E0] transition-colors">
          Semua Prodi
        </button>
        <button @click="selectedProdi = 'IF'" :class="selectedProdi === 'IF' ? 'bg-[#0A0A0B] text-white' : 'bg-[#F5F5F5] text-[#3D3D3F] hover:bg-[#EBEBEB]'" class="px-2.5 py-1 rounded-full border border-[#E0E0E0] transition-colors">
          S1 Informatika
        </button>
        <button @click="selectedProdi = 'SI'" :class="selectedProdi === 'SI' ? 'bg-[#0A0A0B] text-white' : 'bg-[#F5F5F5] text-[#3D3D3F] hover:bg-[#EBEBEB]'" class="px-2.5 py-1 rounded-full border border-[#E0E0E0] transition-colors">
          S1 Sistem Informasi
        </button>
        <button @click="selectedProdi = 'TE'" :class="selectedProdi === 'TE' ? 'bg-[#0A0A0B] text-white' : 'bg-[#F5F5F5] text-[#3D3D3F] hover:bg-[#EBEBEB]'" class="px-2.5 py-1 rounded-full border border-[#E0E0E0] transition-colors">
          S1 Teknik Komputer
        </button>
      </div>

      <!-- Filter Tahun & Tampilan -->
      <div class="flex items-center gap-2">
        <select x-model="selectedYear" class="px-2.5 py-1 bg-[#FFFFFF] border border-[#E0E0E0] rounded-md text-xs text-[#2C2C2F] focus:outline-none">
          <option value="all">Semua Tahun Lulus</option>
          <option value="2025">Tahun 2025</option>
          <option value="2024">Tahun 2024</option>
          <option value="2023">Tahun 2023</option>
        </select>

        <div class="flex items-center border border-[#E0E0E0] rounded-md overflow-hidden bg-[#F5F5F5]">
          <button @click="viewMode = 'table'" :class="viewMode === 'table' ? 'bg-[#FFFFFF] text-[#0A0A0B]' : 'text-[#5D5D5F]'" class="p-1.5 hover:text-[#0A0A0B]">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <line x1="8" y1="6" x2="21" y2="6"></line>
              <line x1="8" y1="12" x2="21" y2="12"></line>
              <line x1="8" y1="18" x2="21" y2="18"></line>
              <line x1="3" y1="6" x2="3.01" y2="6"></line>
              <line x1="3" y1="12" x2="3.01" y2="12"></line>
              <line x1="3" y1="18" x2="3.01" y2="18"></line>
            </svg>
          </button>
          <button @click="viewMode = 'grid'" :class="viewMode === 'grid' ? 'bg-[#FFFFFF] text-[#0A0A0B]' : 'text-[#5D5D5F]'" class="p-1.5 hover:text-[#0A0A0B]">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <rect x="3" y="3" width="7" height="7"></rect>
              <rect x="14" y="3" width="7" height="7"></rect>
              <rect x="14" y="14" width="7" height="7"></rect>
              <rect x="3" y="14" width="7" height="7"></rect>
            </svg>
          </button>
        </div>
      </div>

    </div>
  </div>

  <!-- Notion Database View: Tabel Naskah Arsip TA -->
  <div x-show="viewMode === 'table'" class="bg-[#FFFFFF] border border-[#E0E0E0] rounded-xl overflow-hidden shadow-sm">
    <div class="overflow-x-auto">
      <table class="w-full text-left text-sm">
        <thead class="bg-[#F5F5F5] text-[11px] uppercase tracking-wider text-[#4D4D4F] border-b border-[#E0E0E0]">
          <tr>
            <th class="py-3 px-4 font-semibold">Judul Tugas Akhir</th>
            <th class="py-3 px-4 font-semibold">Penulis & NIM</th>
            <th class="py-3 px-4 font-semibold">Prodi & Tahun</th>
            <th class="py-3 px-4 font-semibold">Dosen Pembimbing</th>
            <th class="py-3 px-4 font-semibold">Kata Kunci</th>
            <th class="py-3 px-4 text-right font-semibold">Aksi Dokumen</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-[#E0E0E0]">

          <!-- Item 1 -->
          <tr class="hover-item transition-colors">
            <td class="py-3.5 px-4 max-w-sm">
              <div class="flex items-start gap-2">
                <svg class="w-4 h-4 text-red-600 mt-0.5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                  <polyline points="14 2 14 8 20 8"></polyline>
                </svg>
                <div>
                  <p class="font-semibold text-[#0A0A0B] hover:underline cursor-pointer">Sistem Pemantauan Kualitas Air Danau Toba Berbasis Internet of Things dan Dashboard Interaktif</p>
                  <p class="text-[11px] text-[#5D5D5F] mt-1 line-clamp-1">Menggunakan sensor pH, kekeruhan, dan dissolved oxygen dengan protokol MQTT.</p>
                </div>
              </div>
            </td>
            <td class="py-3.5 px-4 text-xs">
              <p class="font-medium text-[#0A0A0B]">Grace Hutapea</p>
              <p class="text-[#5D5D5F]">11S20011</p>
            </td>
            <td class="py-3.5 px-4 whitespace-nowrap text-xs">
              <span class="inline-block px-2 py-0.5 rounded bg-[#F5F5F5] font-semibold text-[#0A0A0B] border border-[#E0E0E0]">S1 Informatika</span>
              <p class="text-[11px] text-[#5D5D5F] mt-0.5">Lulus: 2025</p>
            </td>
            <td class="py-3.5 px-4 text-xs text-[#3D3D3F]">
              <p>Dr. Johannes Harungguan</p>
              <p class="text-[11px] text-[#5D5D5F]">Marlina Valentina, M.Sc.</p>
            </td>
            <td class="py-3.5 px-4">
              <div class="flex flex-wrap gap-1">
                <span class="pill-neutral text-[10px]">IoT</span>
                <span class="pill-neutral text-[10px]">MQTT</span>
                <span class="pill-neutral text-[10px]">Danau Toba</span>
              </div>
            </td>
            <td class="py-3.5 px-4 text-right whitespace-nowrap">
              <button @click="$store.app.showToast('Mengunduh Naskah_TA_11S20011_Grace_Hutapea.pdf')" class="btn-secondary text-xs py-1.5 px-3">
                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                  <polyline points="7 10 12 15 17 10"></polyline>
                  <line x1="12" y1="15" x2="12" y2="3"></line>
                </svg>
                Unduh PDF
              </button>
            </td>
          </tr>

          <!-- Item 2 -->
          <tr class="hover-item transition-colors">
            <td class="py-3.5 px-4 max-w-sm">
              <div class="flex items-start gap-2">
                <svg class="w-4 h-4 text-red-600 mt-0.5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                  <polyline points="14 2 14 8 20 8"></polyline>
                </svg>
                <div>
                  <p class="font-semibold text-[#0A0A0B] hover:underline cursor-pointer">Implementasi Microservices Architecture pada Platform E-Commerce Produk Pertanian Toba</p>
                  <p class="text-[11px] text-[#5D5D5F] mt-1 line-clamp-1">Perancangan backend terdistribusi dengan Docker, Kubernetes, dan Apache Kafka.</p>
                </div>
              </div>
            </td>
            <td class="py-3.5 px-4 text-xs">
              <p class="font-medium text-[#0A0A0B]">Bona Manurung</p>
              <p class="text-[#5D5D5F]">12S20042</p>
            </td>
            <td class="py-3.5 px-4 whitespace-nowrap text-xs">
              <span class="inline-block px-2 py-0.5 rounded bg-[#F5F5F5] font-semibold text-[#0A0A0B] border border-[#E0E0E0]">S1 Sistem Informasi</span>
              <p class="text-[11px] text-[#5D5D5F] mt-0.5">Lulus: 2024</p>
            </td>
            <td class="py-3.5 px-4 text-xs text-[#3D3D3F]">
              <p>Marlina Valentina, M.Sc.</p>
              <p class="text-[11px] text-[#5D5D5F]">Samuel P. Pandiangan, M.Kom.</p>
            </td>
            <td class="py-3.5 px-4">
              <div class="flex flex-wrap gap-1">
                <span class="pill-neutral text-[10px]">Microservices</span>
                <span class="pill-neutral text-[10px]">Kafka</span>
              </div>
            </td>
            <td class="py-3.5 px-4 text-right whitespace-nowrap">
              <button @click="$store.app.showToast('Mengunduh Naskah_TA_12S20042_Bona_Manurung.pdf')" class="btn-secondary text-xs py-1.5 px-3">
                Unduh PDF
              </button>
            </td>
          </tr>

          <!-- Item 3 -->
          <tr class="hover-item transition-colors">
            <td class="py-3.5 px-4 max-w-sm">
              <div class="flex items-start gap-2">
                <svg class="w-4 h-4 text-red-600 mt-0.5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                  <polyline points="14 2 14 8 20 8"></polyline>
                </svg>
                <div>
                  <p class="font-semibold text-[#0A0A0B] hover:underline cursor-pointer">Rancang Bangun Node Sensor Nirkabel Berdaya Rendah untuk Deteksi Tanah Longsor di Kawasan Sigumpar</p>
                  <p class="text-[11px] text-[#5D5D5F] mt-1 line-clamp-1">Integrasi sensor akselerometer MEMS dan modul komunikasi LoRaWAN jarak jauh.</p>
                </div>
              </div>
            </td>
            <td class="py-3.5 px-4 text-xs">
              <p class="font-medium text-[#0A0A0B]">Tulus Simanjuntak</p>
              <p class="text-[#5D5D5F]">14S20005</p>
            </td>
            <td class="py-3.5 px-4 whitespace-nowrap text-xs">
              <span class="inline-block px-2 py-0.5 rounded bg-[#F5F5F5] font-semibold text-[#0A0A0B] border border-[#E0E0E0]">S1 Teknik Komputer</span>
              <p class="text-[11px] text-[#5D5D5F] mt-0.5">Lulus: 2024</p>
            </td>
            <td class="py-3.5 px-4 text-xs text-[#3D3D3F]">
              <p>Dr. Arnaldo Sinaga</p>
              <p class="text-[11px] text-[#5D5D5F]">Dr. Johannes Harungguan</p>
            </td>
            <td class="py-3.5 px-4">
              <div class="flex flex-wrap gap-1">
                <span class="pill-neutral text-[10px]">LoRaWAN</span>
                <span class="pill-neutral text-[10px]">Embedded System</span>
              </div>
            </td>
            <td class="py-3.5 px-4 text-right whitespace-nowrap">
              <button @click="$store.app.showToast('Mengunduh Naskah_TA_14S20005_Tulus.pdf')" class="btn-secondary text-xs py-1.5 px-3">
                Unduh PDF
              </button>
            </td>
          </tr>

        </tbody>
      </table>
    </div>
  </div>

  <!-- Notion Gallery/Grid View -->
  <div x-show="viewMode === 'grid'" class="grid grid-cols-1 md:grid-cols-3 gap-4">
    <div class="bg-[#FFFFFF] border border-[#E0E0E0] rounded-xl p-5 shadow-sm hover:border-[#0A0A0B] transition-all flex flex-col justify-between">
      <div>
        <div class="flex items-center justify-between text-xs text-[#5D5D5F] mb-2">
          <span>S1 Informatika • 2025</span>
          <span class="pill-approved text-[10px]">Lulus Cum Laude</span>
        </div>
        <h3 class="font-bold text-[#0A0A0B] text-sm leading-snug">Sistem Pemantauan Kualitas Air Danau Toba Berbasis Internet of Things</h3>
        <p class="text-xs text-[#4D4D4F] mt-2 line-clamp-3">Mengintegrasikan telemetri sensor lingkungan perairan Toba dengan peringatan dini anomali.</p>
        <p class="text-xs text-[#5D5D5F] mt-3">Penulis: <strong class="text-[#0A0A0B]">Grace Hutapea (11S20011)</strong></p>
      </div>
      <div class="mt-4 pt-3 border-t border-[#EBEBEB] flex items-center justify-between">
        <span class="text-[11px] text-[#5D5D5F]">PDF • 8.2 MB</span>
        <button @click="$store.app.showToast('Mengunduh Naskah...')" class="btn-secondary text-xs py-1 px-2.5">Unduh PDF</button>
      </div>
    </div>

    <div class="bg-[#FFFFFF] border border-[#E0E0E0] rounded-xl p-5 shadow-sm hover:border-[#0A0A0B] transition-all flex flex-col justify-between">
      <div>
        <div class="flex items-center justify-between text-xs text-[#5D5D5F] mb-2">
          <span>S1 Sistem Informasi • 2024</span>
          <span class="pill-approved text-[10px]">Lulus</span>
        </div>
        <h3 class="font-bold text-[#0A0A0B] text-sm leading-snug">Implementasi Microservices Architecture pada Platform E-Commerce Pertanian Toba</h3>
        <p class="text-xs text-[#4D4D4F] mt-2 line-clamp-3">Meningkatkan skalabilitas transaksi digital produk kopi dan andaliman binaan Pemkab Toba.</p>
        <p class="text-xs text-[#5D5D5F] mt-3">Penulis: <strong class="text-[#0A0A0B]">Bona Manurung (12S20042)</strong></p>
      </div>
      <div class="mt-4 pt-3 border-t border-[#EBEBEB] flex items-center justify-between">
        <span class="text-[11px] text-[#5D5D5F]">PDF • 6.4 MB</span>
        <button @click="$store.app.showToast('Mengunduh Naskah...')" class="btn-secondary text-xs py-1 px-2.5">Unduh PDF</button>
      </div>
    </div>

    <div class="bg-[#FFFFFF] border border-[#E0E0E0] rounded-xl p-5 shadow-sm hover:border-[#0A0A0B] transition-all flex flex-col justify-between">
      <div>
        <div class="flex items-center justify-between text-xs text-[#5D5D5F] mb-2">
          <span>S1 Teknik Komputer • 2024</span>
          <span class="pill-approved text-[10px]">Lulus</span>
        </div>
        <h3 class="font-bold text-[#0A0A0B] text-sm leading-snug">Rancang Bangun Node Sensor Nirkabel LoRaWAN Deteksi Tanah Longsor</h3>
        <p class="text-xs text-[#4D4D4F] mt-2 line-clamp-3">Implementasi sensor berdaya baterai surya untuk mitigasi bencana di lereng bukit Sitoluama.</p>
        <p class="text-xs text-[#5D5D5F] mt-3">Penulis: <strong class="text-[#0A0A0B]">Tulus Simanjuntak (14S20005)</strong></p>
      </div>
      <div class="mt-4 pt-3 border-t border-[#EBEBEB] flex items-center justify-between">
        <span class="text-[11px] text-[#5D5D5F]">PDF • 11.5 MB</span>
        <button @click="$store.app.showToast('Mengunduh Naskah...')" class="btn-secondary text-xs py-1 px-2.5">Unduh PDF</button>
      </div>
    </div>
  </div>

</div>

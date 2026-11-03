{{-- Screen 5: Alur Pendaftaran & Manajemen Ujian TA (Sempro, Seminar TA, Sidang Akhir) --}}
<div x-show="$store.app.currentScreen === 'ujian_ta'" 
     x-data="{ 
       activeExamTab: $store.app.currentRole === 'baak' ? 'baak' : 
                      ($store.app.currentRole === 'ta' ? 'ta' : 
                      ($store.app.currentRole === 'koordinator' ? 'koordinator' : 'mahasiswa')) 
     }" 
     class="p-6 max-w-7xl mx-auto space-y-6">

  <!-- Header Modul Ujian TA -->
  <div class="bg-[#FFFFFF] border border-[#E0E0E0] rounded-xl p-6 shadow-sm">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
      <div>
        <div class="flex items-center gap-2 mb-2">
          <span class="pill-neutral text-xs">Evaluasi Akademik FITE</span>
          <span class="pill-approved text-xs">Sempro • Seminar TA • Sidang Akhir</span>
        </div>
        <h1 class="text-xl md:text-2xl font-bold text-[#0A0A0B]">Alur Pendaftaran & Manajemen Ujian Tugas Akhir</h1>
        <p class="text-xs text-[#5D5D5F] mt-1">Alur terintegrasi 4 pilar: Mahasiswa TA (Pendaftaran) &rarr; Teaching Assistant (Verifikasi Berkas) &rarr; Koordinator TA (Tanggal & Penguji) &rarr; BAAK (Ruang & Jam).</p>
      </div>

      <!-- Tab Switcher ala Notion Pills -->
      <div class="flex flex-wrap items-center bg-[#F5F5F5] p-1 rounded-lg border border-[#E0E0E0] text-xs font-medium">
        <button @click="activeExamTab = 'mahasiswa'" :class="activeExamTab === 'mahasiswa' ? 'bg-[#FFFFFF] text-[#0A0A0B] shadow-sm font-semibold' : 'text-[#5D5D5F] hover:text-[#0A0A0B]'" class="px-3 py-1.5 rounded-md transition-all">
          1. Mahasiswa TA
        </button>
        <button @click="activeExamTab = 'ta'" :class="activeExamTab === 'ta' ? 'bg-[#FFFFFF] text-[#0A0A0B] shadow-sm font-semibold' : 'text-[#5D5D5F] hover:text-[#0A0A0B]'" class="px-3 py-1.5 rounded-md transition-all">
          2. Teaching Assistant (Verifikator)
        </button>
        <button @click="activeExamTab = 'koordinator'" :class="activeExamTab === 'koordinator' ? 'bg-[#FFFFFF] text-[#0A0A0B] shadow-sm font-semibold' : 'text-[#5D5D5F] hover:text-[#0A0A0B]'" class="px-3 py-1.5 rounded-md transition-all">
          3. Koordinator TA
        </button>
        <button @click="activeExamTab = 'baak'" :class="activeExamTab === 'baak' ? 'bg-[#FFFFFF] text-[#0A0A0B] shadow-sm font-semibold' : 'text-[#5D5D5F] hover:text-[#0A0A0B]'" class="px-3 py-1.5 rounded-md transition-all">
          4. BAAK (Jadwal & Ruang)
        </button>
      </div>
    </div>
  </div>

  <!-- TAB 1: MAHASISWA TA (Pendaftaran & Status Tracking) -->
  <div x-show="activeExamTab === 'mahasiswa'" class="space-y-6">
    
    <!-- Status Alur Verifikasi Pendaftaran Saat Ini -->
    <div class="bg-[#FFFFFF] border border-[#E0E0E0] rounded-xl p-6 shadow-sm">
      <h2 class="text-base font-semibold text-[#0A0A0B] mb-1">Status Progres Pendaftaran Ujian Terkini</h2>
      <p class="text-xs text-[#5D5D5F] mb-6">Seminar TA Samuel Siahaan (11S21004) - Periode Semester Genap 2025/2026</p>

      <!-- Step Flowchart Cards -->
      <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <!-- Step 1 -->
        <div class="p-4 rounded-lg border border-[#C4E5D1] bg-[#EBF5EE]">
          <div class="flex items-center justify-between mb-2">
            <span class="text-[11px] font-bold text-[#1F7A4C] uppercase">Langkah 1</span>
            <svg class="w-4 h-4 text-[#1F7A4C]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
              <polyline points="20 6 9 17 4 12"></polyline>
            </svg>
          </div>
          <h3 class="text-xs font-bold text-[#0A0A0B]">Pengajuan Berkas Mandiri</h3>
          <p class="text-[11px] text-[#1F7A4C] mt-1">Selesai diunggah (26 Okt 2026)</p>
        </div>

        <!-- Step 2 -->
        <div class="p-4 rounded-lg border border-[#C4E5D1] bg-[#EBF5EE]">
          <div class="flex items-center justify-between mb-2">
            <span class="text-[11px] font-bold text-[#1F7A4C] uppercase">Langkah 2</span>
            <svg class="w-4 h-4 text-[#1F7A4C]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
              <polyline points="20 6 9 17 4 12"></polyline>
            </svg>
          </div>
          <h3 class="text-xs font-bold text-[#0A0A0B]">Verifikasi Berkas (TA)</h3>
          <p class="text-[11px] text-[#1F7A4C] mt-1">Dinyatakan LENGKAP & VALID</p>
        </div>

        <!-- Step 3 (Active) -->
        <div class="p-4 rounded-lg border-2 border-[#0A0A0B] bg-[#FFFFFF] shadow-sm">
          <div class="flex items-center justify-between mb-2">
            <span class="text-[11px] font-bold text-[#0A0A0B] uppercase">Langkah 3</span>
            <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
          </div>
          <h3 class="text-xs font-bold text-[#0A0A0B]">Penetapan Tanggal & Penguji</h3>
          <p class="text-[11px] text-amber-700 font-medium mt-1">Sedang diproses Koordinator TA</p>
        </div>

        <!-- Step 4 -->
        <div class="p-4 rounded-lg border border-[#E0E0E0] bg-[#F5F5F5] opacity-60">
          <div class="flex items-center justify-between mb-2">
            <span class="text-[11px] font-medium text-[#5D5D5F] uppercase">Langkah 4</span>
            <svg class="w-3.5 h-3.5 text-[#5D5D5F]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
              <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
            </svg>
          </div>
          <h3 class="text-xs font-semibold text-[#4D4D4F]">Alokasi Ruang & Zoom BAAK</h3>
          <p class="text-[11px] text-[#5D5D5F] mt-1">Menunggu disposisi tanggal</p>
        </div>
      </div>
    </div>

    <!-- Form Pendaftaran Ujian Baru -->
    <div class="bg-[#FFFFFF] border border-[#E0E0E0] rounded-xl p-6 shadow-sm">
      <div class="flex items-center justify-between pb-4 border-b border-[#E0E0E0] mb-6">
        <div>
          <h2 class="text-base font-semibold text-[#0A0A0B]">Formulir Pendaftaran Ujian Tugas Akhir</h2>
          <p class="text-xs text-[#5D5D5F]">Pilih tahapan ujian dan unggah bundel dokumen persyaratan akademik</p>
        </div>
        <span class="text-xs text-[#5D5D5F]">Format Dokumen: PDF / ZIP (Maks 30MB)</span>
      </div>

      <form @submit.prevent="$store.app.showToast('Pendaftaran ujian berhasil dikirimkan ke Teaching Assistant FITE!')" class="space-y-5">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
          <div>
            <label class="block text-xs font-semibold uppercase tracking-wider text-[#3D3D3F] mb-1.5">Pilih Jenis Ujian</label>
            <select class="w-full text-sm px-3.5 py-2.5 bg-[#FFFFFF] border border-[#E0E0E0] rounded-md text-[#0A0A0B] focus:outline-none focus:border-[#0A0A0B]">
              <option value="sempro">1. Seminar Proposal (Sempro)</option>
              <option value="seminar_ta" selected>2. Seminar Tugas Akhir (Kemajuan)</option>
              <option value="sidang_akhir">3. Sidang Akhir / Yudisium TA</option>
            </select>
          </div>

          <div>
            <label class="block text-xs font-semibold uppercase tracking-wider text-[#3D3D3F] mb-1.5">Rekomendasi Dosen Pembimbing</label>
            <input type="text" readonly value="Dr. Johannes Harungguan (Disetujui di Logbook #8)" class="w-full text-sm px-3.5 py-2.5 bg-[#F5F5F5] border border-[#E0E0E0] rounded-md text-[#2C2C2F]">
          </div>
        </div>

        <div>
          <label class="block text-xs font-semibold uppercase tracking-wider text-[#3D3D3F] mb-1.5">Unggah Bundel Persyaratan (Naskah TA, Bukti Logbook & Form Pengesahan)</label>
          <div class="border-2 border-dashed border-[#CCCCCC] hover:border-[#0A0A0B] rounded-lg p-6 text-center bg-[#F5F5F5] cursor-pointer transition-colors">
            <svg class="mx-auto h-9 w-9 text-[#5D5D5F]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
              <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
              <polyline points="17 8 12 3 7 8"></polyline>
              <line x1="12" y1="3" x2="12" y2="15"></line>
            </svg>
            <p class="mt-2 text-xs font-medium text-[#0A0A0B]">Klik atau seret berkas persyaratan ujian ke area ini</p>
            <p class="text-[11px] text-[#5D5D5F] mt-0.5">Persyaratan mencakup: Draf Naskah TA, Form Bimbingan &gt;= 8x, Transkrip Sementara, Form Bebas Tanggungan Perpustakaan Del</p>
          </div>
        </div>

        <div class="flex justify-end pt-2">
          <button type="submit" class="btn-primary text-sm">
            Kirimkan Pendaftaran Ujian TA
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- TAB 2: TEACHING ASSISTANT (Verifikator Kelengkapan Artefak) -->
  <div x-show="activeExamTab === 'ta'" class="space-y-6">
    <div class="bg-[#FFFFFF] border border-[#E0E0E0] rounded-xl overflow-hidden shadow-sm">
      <div class="p-5 border-b border-[#E0E0E0] flex flex-col md:flex-row md:items-center justify-between gap-3">
        <div>
          <h2 class="text-base font-semibold text-[#0A0A0B]">Antrean Verifikasi Kelengkapan Dokumen Fisik/Artefak Mahasiswa</h2>
          <p class="text-xs text-[#5D5D5F]">Teaching Assistant bertanggung jawab memeriksa kelengkapan administrasi sebelum diteruskan ke Koordinator TA</p>
        </div>
        <span class="pill-pending">4 Antrean Perlu Ditinjau</span>
      </div>

      <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
          <thead class="bg-[#F5F5F5] text-[11px] uppercase tracking-wider text-[#4D4D4F] border-b border-[#E0E0E0]">
            <tr>
              <th class="py-3 px-4 font-semibold">Mahasiswa & NIM</th>
              <th class="py-3 px-4 font-semibold">Jenis Ujian</th>
              <th class="py-3 px-4 font-semibold">Kelengkapan Artefak</th>
              <th class="py-3 px-4 font-semibold">Status Saat Ini</th>
              <th class="py-3 px-4 text-right font-semibold">Tindakan Verifikasi</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-[#E0E0E0]">
            
            <tr class="hover-item transition-colors">
              <td class="py-3.5 px-4">
                <p class="font-semibold text-[#0A0A0B]">Samuel Siahaan</p>
                <p class="text-xs text-[#5D5D5F]">11S21004 • S1 Informatika</p>
              </td>
              <td class="py-3.5 px-4">
                <span class="inline-block px-2.5 py-1 text-xs font-semibold rounded bg-[#F5F5F5] border border-[#E0E0E0] text-[#0A0A0B]">
                  Seminar Tugas Akhir
                </span>
              </td>
              <td class="py-3.5 px-4 text-xs text-[#3D3D3F]">
                <ul class="list-disc list-inside space-y-0.5 text-[11px]">
                  <li>Naskah TA: <span class="text-emerald-700 font-semibold">Ada (v2.pdf)</span></li>
                  <li>Logbook asistensi: <span class="text-emerald-700 font-semibold">9 sesi terverifikasi</span></li>
                  <li>Lembar Persetujuan Dosen: <span class="text-emerald-700 font-semibold">Lengkap TTD</span></li>
                </ul>
              </td>
              <td class="py-3.5 px-4">
                <span class="pill-approved">Lengkap & Valid</span>
              </td>
              <td class="py-3.5 px-4 text-right whitespace-nowrap">
                <button @click="$store.app.openModal('verifyArtifact')" class="btn-secondary text-xs py-1.5 px-3">
                  Perbarui Status Dokumen
                </button>
              </td>
            </tr>

            <tr class="hover-item transition-colors">
              <td class="py-3.5 px-4">
                <p class="font-semibold text-[#0A0A0B]">Rian Marpaung</p>
                <p class="text-xs text-[#5D5D5F]">14S21002 • S1 Teknik Elektro</p>
              </td>
              <td class="py-3.5 px-4">
                <span class="inline-block px-2.5 py-1 text-xs font-semibold rounded bg-[#F5F5F5] border border-[#E0E0E0] text-[#0A0A0B]">
                  Seminar Proposal (Sempro)
                </span>
              </td>
              <td class="py-3.5 px-4 text-xs text-[#3D3D3F]">
                <ul class="list-disc list-inside space-y-0.5 text-[11px]">
                  <li>Naskah Proposal: <span class="text-emerald-700 font-semibold">Ada</span></li>
                  <li>Transkrip Nilai: <span class="text-red-700 font-semibold">Belum dicap BAAK</span></li>
                </ul>
              </td>
              <td class="py-3.5 px-4">
                <span class="pill-revision">Kurang Lengkap</span>
              </td>
              <td class="py-3.5 px-4 text-right whitespace-nowrap">
                <button @click="$store.app.openModal('verifyArtifact')" class="btn-primary text-xs py-1.5 px-3">
                  Verifikasi Berkas
                </button>
              </td>
            </tr>

          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- TAB 3: KOORDINATOR TA (Penetapan Tanggal Ujian & Tim Penguji 1 & 2) -->
  <div x-show="activeExamTab === 'koordinator'" class="space-y-6">
    
    <!-- Sub-Panel: Penetapan Dosen Pembimbing untuk Mahasiswa Baru/Aktif -->
    <div class="bg-[#FFFFFF] border border-[#E0E0E0] rounded-xl p-6 shadow-sm">
      <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 pb-4 border-b border-[#E0E0E0] mb-4">
        <div>
          <h2 class="text-base font-semibold text-[#0A0A0B]">Penetapan (Assign) Dosen Pembimbing 1 & 2</h2>
          <p class="text-xs text-[#5D5D5F]">Koordinator TA berwenang mengalokasikan pembimbing utama dan pendamping sesuai keahlian bidang riset</p>
        </div>
        <button @click="$store.app.openModal('assignSupervisor')" class="btn-primary text-xs">
          + Tetapkan Pembimbing Baru
        </button>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-3 gap-3 text-xs">
        <div class="p-3 bg-[#F5F5F5] rounded-lg border border-[#E0E0E0]">
          <p class="font-semibold text-[#0A0A0B]">Samuel Siahaan (11S21004)</p>
          <p class="text-[#5D5D5F] mt-0.5">Topik: Deep Learning IDS Jaringan</p>
          <div class="mt-2 pt-2 border-t border-[#E0E0E0] text-[11px] text-[#3D3D3F]">
            <p>P1: Dr. Johannes Harungguan</p>
            <p>P2: Marlina Valentina, M.Sc.</p>
          </div>
        </div>

        <div class="p-3 bg-[#F5F5F5] rounded-lg border border-[#E0E0E0]">
          <p class="font-semibold text-[#0A0A0B]">Yohana Sinaga (11S21033)</p>
          <p class="text-[#5D5D5F] mt-0.5">Topik: Klasifikasi Citra Ulos CNN</p>
          <div class="mt-2 pt-2 border-t border-[#E0E0E0] text-[11px] text-[#3D3D3F]">
            <p>P1: Dr. Arnaldo Sinaga</p>
            <p>P2: Dr. Johannes Harungguan</p>
          </div>
        </div>

        <div class="p-3 bg-[#F5F5F5] rounded-lg border border-[#E0E0E0]">
          <p class="font-semibold text-[#0A0A0B]">Daniel Situmorang (12S21012)</p>
          <p class="text-[#5D5D5F] mt-0.5">Topik: Enterprise Architecture TOGAF</p>
          <div class="mt-2 pt-2 border-t border-[#E0E0E0] text-[11px] text-[#3D3D3F]">
            <p>P1: Marlina Valentina, M.Sc.</p>
            <p>P2: Samuel P. Pandiangan, M.Kom.</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Tabel Penetapan Tanggal & Penguji Ujian yang sudah diverifikasi TA -->
    <div class="bg-[#FFFFFF] border border-[#E0E0E0] rounded-xl overflow-hidden shadow-sm">
      <div class="p-5 border-b border-[#E0E0E0] flex flex-col md:flex-row md:items-center justify-between gap-3">
        <div>
          <h2 class="text-base font-semibold text-[#0A0A0B]">Penetapan Tanggal Ujian & Penunjukan Tim Penguji</h2>
          <p class="text-xs text-[#5D5D5F]">Berkas yang telah divalidasi Teaching Assistant siap untuk ditetapkan tanggal dan tim penguji 1 & 2</p>
        </div>
      </div>

      <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
          <thead class="bg-[#F5F5F5] text-[11px] uppercase tracking-wider text-[#4D4D4F] border-b border-[#E0E0E0]">
            <tr>
              <th class="py-3 px-4 font-semibold">Mahasiswa & Judul</th>
              <th class="py-3 px-4 font-semibold">Jenis Ujian</th>
              <th class="py-3 px-4 font-semibold">Tanggal Pelaksanaan</th>
              <th class="py-3 px-4 font-semibold">Dosen Penguji 1 & 2</th>
              <th class="py-3 px-4 text-right font-semibold">Aksi Koordinator</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-[#E0E0E0]">
            
            <tr class="hover-item transition-colors">
              <td class="py-3.5 px-4 max-w-xs">
                <p class="font-semibold text-[#0A0A0B]">Samuel Siahaan (11S21004)</p>
                <p class="text-xs text-[#5D5D5F] truncate">Deep Learning IDS Jaringan Kampus</p>
              </td>
              <td class="py-3.5 px-4">
                <span class="pill-neutral text-xs">Seminar TA</span>
              </td>
              <td class="py-3.5 px-4 whitespace-nowrap">
                <span class="font-semibold text-[#0A0A0B] block">Senin, 10 Nov 2026</span>
                <span class="text-xs text-[#5D5D5F]">Ditetapkan Koordinator</span>
              </td>
              <td class="py-3.5 px-4 text-xs">
                <p class="text-[#0A0A0B] font-medium">Penguji 1: Dr. Arnaldo Sinaga</p>
                <p class="text-[#5D5D5F]">Penguji 2: Samuel P. Pandiangan, M.Kom.</p>
              </td>
              <td class="py-3.5 px-4 text-right whitespace-nowrap">
                <button @click="$store.app.openModal('assignExaminer')" class="btn-primary text-xs py-1.5 px-3">
                  Atur Tanggal & Penguji
                </button>
              </td>
            </tr>

            <tr class="hover-item transition-colors">
              <td class="py-3.5 px-4 max-w-xs">
                <p class="font-semibold text-[#0A0A0B]">Daniel Situmorang (12S21012)</p>
                <p class="text-xs text-[#5D5D5F] truncate">Enterprise Architecture TOGAF Danau Toba</p>
              </td>
              <td class="py-3.5 px-4">
                <span class="pill-approved text-xs">Sidang Akhir TA</span>
              </td>
              <td class="py-3.5 px-4 whitespace-nowrap">
                <span class="font-semibold text-[#0A0A0B] block">Rabu, 12 Nov 2026</span>
                <span class="text-xs text-[#5D5D5F]">Terjadwal</span>
              </td>
              <td class="py-3.5 px-4 text-xs">
                <p class="text-[#0A0A0B] font-medium">Penguji 1: Dr. Johannes Harungguan</p>
                <p class="text-[#5D5D5F]">Penguji 2: Dr. Arnaldo Sinaga</p>
              </td>
              <td class="py-3.5 px-4 text-right whitespace-nowrap">
                <button @click="$store.app.openModal('assignExaminer')" class="btn-secondary text-xs py-1.5 px-3">
                  Ubah Penguji
                </button>
              </td>
            </tr>

          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- TAB 4: BAAK (Fasilitas Ruangan, Jam & Link Zoom) -->
  <div x-show="activeExamTab === 'baak'" class="space-y-6">
    <div class="bg-[#FFFFFF] border border-[#E0E0E0] rounded-xl overflow-hidden shadow-sm">
      <div class="p-5 border-b border-[#E0E0E0] flex flex-col md:flex-row md:items-center justify-between gap-3">
        <div>
          <h2 class="text-base font-semibold text-[#0A0A0B]">Pengaturan Fasilitas Ruang Ujian, Jam & Link Zoom (BAAK)</h2>
          <p class="text-xs text-[#5D5D5F]">Mengelola ketersediaan ruang ujian FITE (GD 512, GD 514, Ruang Sidang FITE) dan jam mulai-selesai</p>
        </div>
        <span class="text-xs text-[#5D5D5F]">Integrasi Layanan Akademik IT Del</span>
      </div>

      <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
          <thead class="bg-[#F5F5F5] text-[11px] uppercase tracking-wider text-[#4D4D4F] border-b border-[#E0E0E0]">
            <tr>
              <th class="py-3 px-4 font-semibold">Tgl Ujian (Koordinator)</th>
              <th class="py-3 px-4 font-semibold">Mahasiswa & Penguji</th>
              <th class="py-3 px-4 font-semibold">Alokasi Ruangan</th>
              <th class="py-3 px-4 font-semibold">Jam Mulai - Selesai</th>
              <th class="py-3 px-4 font-semibold">Link Zoom (Daring/Hybrid)</th>
              <th class="py-3 px-4 text-right font-semibold">Aksi BAAK</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-[#E0E0E0]">
            
            <tr class="hover-item transition-colors">
              <td class="py-3.5 px-4 whitespace-nowrap">
                <span class="font-semibold text-[#0A0A0B] block">Senin, 10 Nov 2026</span>
                <span class="pill-neutral text-[10px]">Seminar TA</span>
              </td>
              <td class="py-3.5 px-4">
                <p class="font-semibold text-[#0A0A0B]">Samuel Siahaan (11S21004)</p>
                <p class="text-[11px] text-[#5D5D5F]">Penguji: Dr. Arnaldo & Samuel Pandiangan</p>
              </td>
              <td class="py-3.5 px-4">
                <span class="inline-flex items-center gap-1 font-semibold text-xs text-[#0A0A0B] bg-[#F5F5F5] px-2.5 py-1 rounded border border-[#E0E0E0]">
                  Ruang Sidang FITE GD 514
                </span>
              </td>
              <td class="py-3.5 px-4 text-xs font-semibold text-[#0A0A0B]">
                09:00 - 11:00 WIB
              </td>
              <td class="py-3.5 px-4 text-xs">
                <a href="https://zoom.us/j/itdel-sidang-room1" target="_blank" class="text-blue-600 hover:underline flex items-center gap-1">
                  <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polygon points="23 7 16 12 23 17 23 7"></polygon>
                    <rect x="1" y="5" width="15" height="14" rx="2" ry="2"></rect>
                  </svg>
                  zoom.us/j/itdel-sidang-room1
                </a>
              </td>
              <td class="py-3.5 px-4 text-right whitespace-nowrap">
                <button @click="$store.app.openModal('baakSchedule')" class="btn-primary text-xs py-1.5 px-3">
                  Kelola Jadwal BAAK
                </button>
              </td>
            </tr>

            <tr class="hover-item transition-colors">
              <td class="py-3.5 px-4 whitespace-nowrap">
                <span class="font-semibold text-[#0A0A0B] block">Rabu, 12 Nov 2026</span>
                <span class="pill-approved text-[10px]">Sidang Akhir TA</span>
              </td>
              <td class="py-3.5 px-4">
                <p class="font-semibold text-[#0A0A0B]">Daniel Situmorang (12S21012)</p>
                <p class="text-[11px] text-[#5D5D5F]">Penguji: Dr. Johannes & Dr. Arnaldo</p>
              </td>
              <td class="py-3.5 px-4">
                <span class="inline-flex items-center gap-1 font-semibold text-xs text-[#0A0A0B] bg-[#F5F5F5] px-2.5 py-1 rounded border border-[#E0E0E0]">
                  Lab Komputer GD 512
                </span>
              </td>
              <td class="py-3.5 px-4 text-xs font-semibold text-[#0A0A0B]">
                13:30 - 15:30 WIB
              </td>
              <td class="py-3.5 px-4 text-xs">
                <a href="https://zoom.us/j/itdel-sidang-room2" target="_blank" class="text-blue-600 hover:underline flex items-center gap-1">
                  <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polygon points="23 7 16 12 23 17 23 7"></polygon>
                    <rect x="1" y="5" width="15" height="14" rx="2" ry="2"></rect>
                  </svg>
                  zoom.us/j/itdel-sidang-room2
                </a>
              </td>
              <td class="py-3.5 px-4 text-right whitespace-nowrap">
                <button @click="$store.app.openModal('baakSchedule')" class="btn-secondary text-xs py-1.5 px-3">
                  Edit Ruangan
                </button>
              </td>
            </tr>

          </tbody>
        </table>
      </div>
    </div>
  </div>

</div>

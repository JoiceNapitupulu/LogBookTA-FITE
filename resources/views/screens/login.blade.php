{{-- Screen 1: Halaman Login Mandiri FITE --}}
<div x-show="$store.app.currentScreen === 'login'" class="min-h-[calc(100vh-70px)] bg-[#F5F5F5] flex flex-col justify-center items-center py-12 px-4 sm:px-6 lg:px-8">
  <div class="w-full max-w-md">
    
    <!-- Header Branding ala Notion -->
    <div class="text-center mb-8">
      <div class="inline-flex items-center justify-center w-14 h-14 rounded-xl bg-[#0A0A0B] text-white shadow-sm mb-4">
        <svg class="w-7 h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
          <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
          <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
          <path d="M9 7h6"></path>
          <path d="M9 11h6"></path>
        </svg>
      </div>
      <h1 class="text-2xl font-bold tracking-tight text-[#0A0A0B]">Logbook TA FITE</h1>
      <p class="text-sm text-[#4D4D4F] mt-1">Sistem Manajemen & Logbook Tugas Akhir</p>
      <div class="inline-flex items-center gap-1.5 mt-2 px-2.5 py-1 rounded-full text-xs font-medium bg-[#EBEBEB] text-[#2C2C2F] border border-[#E0E0E0]">
        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
        Fakultas Informatika & Teknik Elektro • IT Del
      </div>
    </div>

    <!-- Login Card Minimalis -->
    <div class="bg-[#FFFFFF] border border-[#E0E0E0] rounded-xl p-8 shadow-sm">
      <div class="border-b border-[#EBEBEB] pb-4 mb-6">
        <h2 class="text-lg font-semibold text-[#0A0A0B]">Masuk ke Workspace</h2>
        <p class="text-xs text-[#5D5D5F] mt-0.5">Gunakan akun kredensial mandiri FITE Anda untuk melanjutkan.</p>
      </div>

      <form @submit.prevent="$store.app.setRole('mahasiswa_ta')" class="space-y-4">
        <div>
          <label class="block text-xs font-semibold uppercase tracking-wider text-[#3D3D3F] mb-1.5">Email Institusi Del</label>
          <div class="relative">
            <input type="email" value="samuel.siahaan@del.ac.id" placeholder="nama.nim@del.ac.id" required
              class="w-full text-sm px-3.5 py-2.5 bg-[#FFFFFF] border border-[#E0E0E0] rounded-md text-[#0A0A0B] placeholder-[#CCCCCC] focus:outline-none focus:ring-1 focus:ring-[#0A0A0B] focus:border-[#0A0A0B] transition-colors">
          </div>
        </div>

        <div>
          <div class="flex items-center justify-between mb-1.5">
            <label class="block text-xs font-semibold uppercase tracking-wider text-[#3D3D3F]">Kata Sandi</label>
            <a href="javascript:void(0)" @click="$store.app.showToast('Silakan hubungi TA FITE atau Admin BAAK untuk reset sandi')" class="text-xs text-[#4D4D4F] hover:text-[#0A0A0B] underline">Lupa sandi?</a>
          </div>
          <input type="password" value="••••••••••••" required
            class="w-full text-sm px-3.5 py-2.5 bg-[#FFFFFF] border border-[#E0E0E0] rounded-md text-[#0A0A0B] placeholder-[#CCCCCC] focus:outline-none focus:ring-1 focus:ring-[#0A0A0B] focus:border-[#0A0A0B] transition-colors">
        </div>

        <div class="flex items-center justify-between text-xs text-[#4D4D4F] pt-1">
          <label class="flex items-center gap-2 cursor-pointer">
            <input type="checkbox" checked class="rounded border-[#CCCCCC] text-[#0A0A0B] focus:ring-0">
            <span>Ingat sesi masuk ini</span>
          </label>
          <span class="text-[11px] text-[#5D5D5F]">Auth Mandiri FITE</span>
        </div>

        <button type="submit" class="w-full btn-primary py-2.5 mt-2 text-sm justify-center">
          <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"></path>
            <polyline points="10 17 15 12 10 7"></polyline>
            <line x1="15" y1="12" x2="3" y2="12"></line>
          </svg>
          Masuk ke Workspace
        </button>
      </form>

      <!-- Demo Quick Access Role Switcher -->
      <div class="mt-8 pt-6 border-t border-[#EBEBEB]">
        <div class="flex items-center justify-between mb-3">
          <span class="text-xs font-semibold uppercase tracking-wider text-[#5D5D5F]">Demo Quick-Login (7 Roles)</span>
          <span class="text-[11px] text-emerald-600 font-medium">Klik untuk simulasi</span>
        </div>
        <div class="grid grid-cols-2 gap-2 text-left">
          <button @click="$store.app.setRole('mahasiswa_ta')" class="p-2 rounded border border-[#E0E0E0] hover:bg-[#F5F5F5] transition-colors text-left">
            <p class="text-xs font-semibold text-[#0A0A0B]">1. Mahasiswa TA</p>
            <p class="text-[10px] text-[#5D5D5F] truncate">Samuel Siahaan (11S21004)</p>
          </button>
          <button @click="$store.app.setRole('mahasiswa_non_ta')" class="p-2 rounded border border-[#E0E0E0] hover:bg-[#F5F5F5] transition-colors text-left">
            <p class="text-xs font-semibold text-[#0A0A0B]">2. Mhs Non-TA</p>
            <p class="text-[10px] text-[#5D5D5F] truncate">Yosua Tambunan (11S22014)</p>
          </button>
          <button @click="$store.app.setRole('dosen')" class="p-2 rounded border border-[#E0E0E0] hover:bg-[#F5F5F5] transition-colors text-left">
            <p class="text-xs font-semibold text-[#0A0A0B]">3. Dosen Pembimbing</p>
            <p class="text-[10px] text-[#5D5D5F] truncate">Dr. Johannes Harungguan</p>
          </button>
          <button @click="$store.app.setRole('koordinator')" class="p-2 rounded border border-[#E0E0E0] hover:bg-[#F5F5F5] transition-colors text-left">
            <p class="text-xs font-semibold text-[#0A0A0B]">4. Koordinator TA</p>
            <p class="text-[10px] text-[#5D5D5F] truncate">Marlina Valentina, M.Sc.</p>
          </button>
          <button @click="$store.app.setRole('kaprodi')" class="p-2 rounded border border-[#E0E0E0] hover:bg-[#F5F5F5] transition-colors text-left">
            <p class="text-xs font-semibold text-[#0A0A0B]">5. Kaprodi FITE</p>
            <p class="text-[10px] text-[#5D5D5F] truncate">Dr. Arnaldo Sinaga</p>
          </button>
          <button @click="$store.app.setRole('ta')" class="p-2 rounded border border-[#E0E0E0] hover:bg-[#F5F5F5] transition-colors text-left">
            <p class="text-xs font-semibold text-[#0A0A0B]">6. Teaching Assistant</p>
            <p class="text-[10px] text-[#5D5D5F] truncate">Grace Panjaitan, S.Kom.</p>
          </button>
          <button @click="$store.app.setRole('baak')" class="col-span-2 p-2 rounded border border-[#E0E0E0] hover:bg-[#F5F5F5] transition-colors text-left">
            <p class="text-xs font-semibold text-[#0A0A0B]">7. BAAK Institut Teknologi Del</p>
            <p class="text-[10px] text-[#5D5D5F] truncate">Pengaturan Ruang Ujian, Jam & Tautan Zoom Meeting</p>
          </button>
        </div>
      </div>
    </div>

    <!-- Institutional Footer -->
    <div class="text-center mt-6 text-xs text-[#5D5D5F]">
      <p>Institut Teknologi Del • Jl. Sisingamangaraja, Sitoluama, Laguboti, Toba</p>
      <p class="mt-1 font-serif italic text-[#3D3D3F]">"MarTuhan, Marroha, Marbisuk"</p>
    </div>
  </div>
</div>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sistem Manajemen & Logbook Tugas Akhir FITE - Institut Teknologi Del</title>
  
  <!-- Fonts: Google Fonts Plus Jakarta Sans & Inter with Gilroy fallbacks -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  <!-- Bootstrap 5.3 CSS & Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  
  @vite(['resources/css/app.css', 'resources/js/app.js'])

  <style>
    [x-cloak] { display: none !important; }
    p { margin-bottom: 0; }
    a { text-decoration: none; color: inherit; }
  </style>
</head>
<body class="bg-[#FFFFFF] text-[#0A0A0B] antialiased overflow-x-hidden" x-data>

  <!-- Global Toast Notification Container -->
  <div x-show="$store.app.toast.show" x-cloak
       x-transition:enter="transition ease-out duration-300"
       x-transition:enter-start="opacity-0 transform translate-y-2"
       x-transition:enter-end="opacity-100 transform translate-y-0"
       x-transition:leave="transition ease-in duration-200"
       x-transition:leave-start="opacity-100 transform translate-y-0"
       x-transition:leave-end="opacity-0 transform translate-y-2"
       class="fixed bottom-6 right-6 z-50 max-w-md bg-[#0A0A0B] text-white px-4 py-3 rounded-lg shadow-xl border border-[#2C2C2F] flex items-center gap-3">
    <div class="w-2 h-2 rounded-full" :class="$store.app.toast.type === 'error' ? 'bg-red-500' : 'bg-emerald-400'"></div>
    <p class="text-xs font-medium text-white" x-text="$store.app.toast.message"></p>
    <button @click="$store.app.toast.show = false" class="text-[#D6D6D6] hover:text-white ml-auto">
      <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
    </button>
  </div>

  <div class="flex h-screen overflow-hidden">
    
    <!-- ========================================= -->
    <!-- SIDEBAR KIRI (COLLAPSIBLE ALA NOTION #F5F5F5) -->
    <!-- ========================================= -->
    <aside :class="$store.app.sidebarCollapsed ? 'w-16' : 'w-64'" 
           class="h-screen bg-[#F5F5F5] border-r border-[#E0E0E0] flex flex-col justify-between transition-all duration-200 shrink-0 z-30 select-none">
      
      <!-- Top Section of Sidebar -->
      <div>
        <!-- Workspace Header -->
        <div class="h-[70px] border-b border-[#E0E0E0] px-4 flex items-center justify-between">
          <div class="flex items-center gap-2.5 overflow-hidden">
            <div class="w-8 h-8 rounded-md bg-[#0A0A0B] text-white flex items-center justify-center font-bold text-xs shrink-0 shadow-xs">
              TA
            </div>
            <div x-show="!$store.app.sidebarCollapsed" class="truncate">
              <h2 class="text-xs font-bold text-[#0A0A0B] tracking-tight truncate">FITE IT Del</h2>
              <p class="text-[10px] text-[#5D5D5F] truncate">Logbook & Manajemen TA</p>
            </div>
          </div>
          
          <button @click="$store.app.sidebarCollapsed = !$store.app.sidebarCollapsed" 
                  class="btn-icon p-1.5 text-[#5D5D5F] hover:text-[#0A0A0B]" 
                  title="Toggle Sidebar">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <line x1="3" y1="12" x2="21" y2="12"></line>
              <line x1="3" y1="6" x2="21" y2="6"></line>
              <line x1="3" y1="18" x2="21" y2="18"></line>
            </svg>
          </button>
        </div>

        <!-- Quick Jump / Search Placeholder ala Notion -->
        <div x-show="!$store.app.sidebarCollapsed" class="px-3 pt-3">
          <button @click="$store.app.currentScreen = 'pustaka_ta'" 
                  class="w-full flex items-center justify-between px-2.5 py-1.5 text-xs text-[#5D5D5F] bg-[#FFFFFF] hover:bg-[#EBEBEB] border border-[#E0E0E0] rounded-md transition-colors">
            <div class="flex items-center gap-2">
              <svg class="w-3.5 h-3.5 text-[#5D5D5F]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="11" cy="11" r="8"></circle>
                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
              </svg>
              <span>Cari naskah / arsip...</span>
            </div>
            <span class="text-[10px] font-mono text-[#CCCCCC] bg-[#F5F5F5] px-1 rounded">⌘K</span>
          </button>
        </div>

        <!-- Navigation Links Menu -->
        <nav class="p-3 space-y-5 overflow-y-auto max-h-[calc(100vh-220px)]">
          
          <!-- Grup 1: Workspace Utama -->
          <div>
            <div x-show="!$store.app.sidebarCollapsed" class="px-2 mb-1.5 text-[10px] font-bold uppercase tracking-wider text-[#5D5D5F]">
              Workspace Utama
            </div>
            <div class="space-y-0.5">
              
              <!-- Screen 2: Dashboard Mahasiswa TA -->
              <button @click="$store.app.currentScreen = 'mahasiswa_dashboard'"
                      :class="$store.app.currentScreen === 'mahasiswa_dashboard' ? 'bg-[#EBEBEB] text-[#0A0A0B] font-semibold' : 'text-[#3D3D3F] hover:bg-[#EBEBEB]'"
                      class="w-full flex items-center gap-2.5 px-2.5 py-1.5 rounded-md text-xs transition-colors text-left"
                      title="Dashboard & Progress Tracker (Mahasiswa TA)">
                <svg class="w-4 h-4 shrink-0 text-[#2C2C2F]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path d="M3 3h7v7H3z"></path>
                  <path d="M14 3h7v4h-7z"></path>
                  <path d="M14 11h7v10h-7z"></path>
                  <path d="M3 14h7v7H3z"></path>
                </svg>
                <span x-show="!$store.app.sidebarCollapsed" class="truncate">Dashboard & Logbook TA</span>
              </button>

              <!-- Screen 3: Workspace Dosen Pembimbing -->
              <button @click="$store.app.currentScreen = 'dosen_workspace'"
                      :class="$store.app.currentScreen === 'dosen_workspace' ? 'bg-[#EBEBEB] text-[#0A0A0B] font-semibold' : 'text-[#3D3D3F] hover:bg-[#EBEBEB]'"
                      class="w-full flex items-center gap-2.5 px-2.5 py-1.5 rounded-md text-xs transition-colors text-left"
                      title="Workspace Dosen Pembimbing">
                <svg class="w-4 h-4 shrink-0 text-[#2C2C2F]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                  <circle cx="9" cy="7" r="4"></circle>
                  <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                  <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                </svg>
                <span x-show="!$store.app.sidebarCollapsed" class="truncate">Workspace Dosen</span>
              </button>

              <!-- Screen 4: Janji Bimbingan Dua Arah -->
              <button @click="$store.app.currentScreen = 'janji_bimbingan'"
                      :class="$store.app.currentScreen === 'janji_bimbingan' ? 'bg-[#EBEBEB] text-[#0A0A0B] font-semibold' : 'text-[#3D3D3F] hover:bg-[#EBEBEB]'"
                      class="w-full flex items-center gap-2.5 px-2.5 py-1.5 rounded-md text-xs transition-colors text-left"
                      title="Janji Bimbingan Dua Arah">
                <svg class="w-4 h-4 shrink-0 text-[#2C2C2F]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                  <line x1="16" y1="2" x2="16" y2="6"></line>
                  <line x1="8" y1="2" x2="8" y2="6"></line>
                  <line x1="3" y1="10" x2="21" y2="10"></line>
                </svg>
                <span x-show="!$store.app.sidebarCollapsed" class="truncate">Janji Bimbingan</span>
              </button>

              <!-- Screen 5: Alur & Manajemen Ujian TA -->
              <button @click="$store.app.currentScreen = 'ujian_ta'"
                      :class="$store.app.currentScreen === 'ujian_ta' ? 'bg-[#EBEBEB] text-[#0A0A0B] font-semibold' : 'text-[#3D3D3F] hover:bg-[#EBEBEB]'"
                      class="w-full flex items-center gap-2.5 px-2.5 py-1.5 rounded-md text-xs transition-colors text-left"
                      title="Alur Pendaftaran & Manajemen Ujian TA">
                <svg class="w-4 h-4 shrink-0 text-[#2C2C2F]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <circle cx="12" cy="8" r="7"></circle>
                  <polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"></polyline>
                </svg>
                <span x-show="!$store.app.sidebarCollapsed" class="truncate">Manajemen Ujian TA</span>
              </button>

            </div>
          </div>

          <!-- Grup 2: Repositori & Pimpinan -->
          <div>
            <div x-show="!$store.app.sidebarCollapsed" class="px-2 mb-1.5 text-[10px] font-bold uppercase tracking-wider text-[#5D5D5F]">
              Koleksi & Analitik
            </div>
            <div class="space-y-0.5">
              
              <!-- Screen 6: Pustaka Arsip Tugas Akhir -->
              <button @click="$store.app.currentScreen = 'pustaka_ta'"
                      :class="$store.app.currentScreen === 'pustaka_ta' ? 'bg-[#EBEBEB] text-[#0A0A0B] font-semibold' : 'text-[#3D3D3F] hover:bg-[#EBEBEB]'"
                      class="w-full flex items-center gap-2.5 px-2.5 py-1.5 rounded-md text-xs transition-colors text-left"
                      title="Pustaka Arsip Tugas Akhir Terdahulu">
                <svg class="w-4 h-4 shrink-0 text-[#2C2C2F]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                  <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                </svg>
                <span x-show="!$store.app.sidebarCollapsed" class="truncate">Pustaka Arsip TA</span>
              </button>

              <!-- Screen 7: Panel Eksekutif Kaprodi & Koordinator -->
              <button @click="$store.app.currentScreen = 'panel_eksekutif'"
                      :class="$store.app.currentScreen === 'panel_eksekutif' ? 'bg-[#EBEBEB] text-[#0A0A0B] font-semibold' : 'text-[#3D3D3F] hover:bg-[#EBEBEB]'"
                      class="w-full flex items-center gap-2.5 px-2.5 py-1.5 rounded-md text-xs transition-colors text-left"
                      title="Panel Eksekutif Kaprodi & Koordinator TA">
                <svg class="w-4 h-4 shrink-0 text-[#2C2C2F]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <line x1="18" y1="20" x2="18" y2="10"></line>
                  <line x1="12" y1="20" x2="12" y2="4"></line>
                  <line x1="6" y1="20" x2="6" y2="14"></line>
                </svg>
                <span x-show="!$store.app.sidebarCollapsed" class="truncate">Panel Eksekutif</span>
              </button>

            </div>
          </div>

          <!-- Grup 3: Autentikasi -->
          <div>
            <div x-show="!$store.app.sidebarCollapsed" class="px-2 mb-1.5 text-[10px] font-bold uppercase tracking-wider text-[#5D5D5F]">
              Autentikasi
            </div>
            <button @click="$store.app.currentScreen = 'login'"
                    :class="$store.app.currentScreen === 'login' ? 'bg-[#EBEBEB] text-[#0A0A0B] font-semibold' : 'text-[#3D3D3F] hover:bg-[#EBEBEB]'"
                    class="w-full flex items-center gap-2.5 px-2.5 py-1.5 rounded-md text-xs transition-colors text-left"
                    title="Halaman Login Mandiri FITE">
              <svg class="w-4 h-4 shrink-0 text-[#2C2C2F]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"></path>
                <polyline points="10 17 15 12 10 7"></polyline>
                <line x1="15" y1="12" x2="3" y2="12"></line>
              </svg>
              <span x-show="!$store.app.sidebarCollapsed" class="truncate">Login Mandiri FITE</span>
            </button>
          </div>

        </nav>
      </div>

      <!-- Bottom User Section & Info -->
      <div class="p-3 border-t border-[#E0E0E0] bg-[#F5F5F5]">
        <div x-show="!$store.app.sidebarCollapsed" class="mb-2">
          <div class="px-2 py-1.5 rounded bg-[#FFFFFF] border border-[#E0E0E0]">
            <p class="text-[10px] text-[#5D5D5F]">Sesi Role Aktif:</p>
            <p class="text-xs font-bold text-[#0A0A0B] truncate" x-text="$store.app.getRoleLabel()"></p>
          </div>
        </div>

        <div class="flex items-center gap-2.5 px-1 py-1">
          <div class="w-7 h-7 rounded-full bg-[#2C2C2F] text-white flex items-center justify-center font-bold text-[11px] shrink-0">
            DEL
          </div>
          <div x-show="!$store.app.sidebarCollapsed" class="truncate text-left">
            <p class="text-xs font-semibold text-[#0A0A0B] truncate" x-text="$store.app.getUserName()"></p>
            <p class="text-[10px] text-[#5D5D5F] truncate">IT Del • FITE</p>
          </div>
        </div>
      </div>

    </aside>

    <!-- ========================================= -->
    <!-- AREA KONTEN UTAMA & TOPBAR (70px NOTION STYLE) -->
    <!-- ========================================= -->
    <div class="flex-1 flex flex-col h-screen overflow-hidden">
      
      <!-- TOPBAR: 70px Height, Border-bottom 1px #E0E0E0 -->
      <header class="h-[70px] bg-[#FFFFFF] border-b border-[#E0E0E0] px-6 flex items-center justify-between z-20 shrink-0">
        
        <!-- Left: Notion Breadcrumbs -->
        <div class="flex items-center gap-2 text-xs">
          <span class="text-[#5D5D5F] flex items-center gap-1.5">
            <svg class="w-4 h-4 text-[#0A0A0B]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
              <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
            </svg>
            <span>FITE IT Del</span>
          </span>
          <span class="text-[#CCCCCC]">/</span>
          <span class="text-[#5D5D5F]">Tugas Akhir</span>
          <span class="text-[#CCCCCC]">/</span>
          <span class="font-semibold text-[#0A0A0B] truncate max-w-xs md:max-w-md" 
                x-text="$store.app.currentScreen === 'mahasiswa_dashboard' ? 'Dashboard & Logbook TA' : 
                       ($store.app.currentScreen === 'dosen_workspace' ? 'Workspace Dosen Pembimbing' : 
                       ($store.app.currentScreen === 'janji_bimbingan' ? 'Modul Janji Bimbingan' : 
                       ($store.app.currentScreen === 'ujian_ta' ? 'Pendaftaran & Manajemen Ujian' : 
                       ($store.app.currentScreen === 'pustaka_ta' ? 'Pustaka Arsip Tugas Akhir' : 
                       ($store.app.currentScreen === 'panel_eksekutif' ? 'Panel Eksekutif & Statistik' : 'Halaman Login Mandiri')))))">
          </span>
        </div>

        <!-- Center: Quick Screens Pills for Presentation Navigation -->
        <div class="hidden xl:flex items-center gap-1 bg-[#F5F5F5] p-1 rounded-lg border border-[#E0E0E0] text-xs font-medium">
          <button @click="$store.app.currentScreen = 'mahasiswa_dashboard'" :class="$store.app.currentScreen === 'mahasiswa_dashboard' ? 'bg-[#FFFFFF] text-[#0A0A0B] shadow-xs font-semibold' : 'text-[#5D5D5F] hover:text-[#0A0A0B]'" class="px-2.5 py-1 rounded transition-colors">
            Mhs TA
          </button>
          <button @click="$store.app.currentScreen = 'dosen_workspace'" :class="$store.app.currentScreen === 'dosen_workspace' ? 'bg-[#FFFFFF] text-[#0A0A0B] shadow-xs font-semibold' : 'text-[#5D5D5F] hover:text-[#0A0A0B]'" class="px-2.5 py-1 rounded transition-colors">
            Dosen
          </button>
          <button @click="$store.app.currentScreen = 'janji_bimbingan'" :class="$store.app.currentScreen === 'janji_bimbingan' ? 'bg-[#FFFFFF] text-[#0A0A0B] shadow-xs font-semibold' : 'text-[#5D5D5F] hover:text-[#0A0A0B]'" class="px-2.5 py-1 rounded transition-colors">
            Bimbingan
          </button>
          <button @click="$store.app.currentScreen = 'ujian_ta'" :class="$store.app.currentScreen === 'ujian_ta' ? 'bg-[#FFFFFF] text-[#0A0A0B] shadow-xs font-semibold' : 'text-[#5D5D5F] hover:text-[#0A0A0B]'" class="px-2.5 py-1 rounded transition-colors">
            Ujian TA
          </button>
          <button @click="$store.app.currentScreen = 'pustaka_ta'" :class="$store.app.currentScreen === 'pustaka_ta' ? 'bg-[#FFFFFF] text-[#0A0A0B] shadow-xs font-semibold' : 'text-[#5D5D5F] hover:text-[#0A0A0B]'" class="px-2.5 py-1 rounded transition-colors">
            Pustaka
          </button>
          <button @click="$store.app.currentScreen = 'panel_eksekutif'" :class="$store.app.currentScreen === 'panel_eksekutif' ? 'bg-[#FFFFFF] text-[#0A0A0B] shadow-xs font-semibold' : 'text-[#5D5D5F] hover:text-[#0A0A0B]'" class="px-2.5 py-1 rounded transition-colors">
            Eksekutif
          </button>
        </div>

        <!-- Right: Role Switcher Dropdown, Notifications, User Profile -->
        <div class="flex items-center gap-3">
          
          <!-- ROLE SWITCHER INTERAKTIF (7 ROLES) -->
          <div class="relative" @click.away="$store.app.roleDropdownOpen = false">
            <button @click="$store.app.roleDropdownOpen = !$store.app.roleDropdownOpen" 
                    class="flex items-center gap-1.5 px-3 py-1.5 bg-[#FFFFFF] border border-[#0A0A0B] rounded-md text-xs font-semibold text-[#0A0A0B] hover:bg-[#0A0A0B] hover:text-white transition-all shadow-xs">
              <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
              <span class="hidden sm:inline">Role:</span>
              <span x-text="$store.app.getRoleLabel()"></span>
              <svg class="w-3.5 h-3.5 ml-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polyline points="6 9 12 15 18 9"></polyline>
              </svg>
            </button>

            <!-- Role Dropdown Menu ala Notion -->
            <div x-show="$store.app.roleDropdownOpen" x-cloak
                 class="absolute right-0 mt-2 w-72 bg-[#FFFFFF] border border-[#E0E0E0] rounded-xl shadow-xl py-2 z-50 text-xs">
              <div class="px-3 py-2 border-b border-[#EBEBEB]">
                <p class="font-bold text-[#0A0A0B]">Ganti Hak Akses (7 Roles FITE)</p>
                <p class="text-[11px] text-[#5D5D5F]">Simulasikan tampilan antarmuka tiap peran</p>
              </div>

              <div class="max-h-72 overflow-y-auto p-1 space-y-0.5">
                <button @click="$store.app.setRole('mahasiswa_ta')" class="w-full text-left p-2 rounded hover:bg-[#F5F5F5] transition-colors flex items-start gap-2">
                  <span class="w-2 h-2 rounded-full mt-1 shrink-0" :class="$store.app.currentRole === 'mahasiswa_ta' ? 'bg-[#0A0A0B]' : 'bg-[#CCCCCC]'"></span>
                  <div>
                    <p class="font-semibold text-[#0A0A0B]">1. Mahasiswa TA</p>
                    <p class="text-[10px] text-[#5D5D5F]">Akses logbook, progres bab, janji temu, daftar ujian</p>
                  </div>
                </button>

                <button @click="$store.app.setRole('mahasiswa_non_ta')" class="w-full text-left p-2 rounded hover:bg-[#F5F5F5] transition-colors flex items-start gap-2">
                  <span class="w-2 h-2 rounded-full mt-1 shrink-0" :class="$store.app.currentRole === 'mahasiswa_non_ta' ? 'bg-[#0A0A0B]' : 'bg-[#CCCCCC]'"></span>
                  <div>
                    <p class="font-semibold text-[#0A0A0B]">2. Mahasiswa Non-TA</p>
                    <p class="text-[10px] text-[#5D5D5F]">Read-only pustaka arsip TA terdahulu & info umum</p>
                  </div>
                </button>

                <button @click="$store.app.setRole('dosen')" class="w-full text-left p-2 rounded hover:bg-[#F5F5F5] transition-colors flex items-start gap-2">
                  <span class="w-2 h-2 rounded-full mt-1 shrink-0" :class="$store.app.currentRole === 'dosen' ? 'bg-[#0A0A0B]' : 'bg-[#CCCCCC]'"></span>
                  <div>
                    <p class="font-semibold text-[#0A0A0B]">3. Dosen Pembimbing</p>
                    <p class="text-[10px] text-[#5D5D5F]">Review mahasiswa, coretan revisi & setujui bimbingan</p>
                  </div>
                </button>

                <button @click="$store.app.setRole('koordinator')" class="w-full text-left p-2 rounded hover:bg-[#F5F5F5] transition-colors flex items-start gap-2">
                  <span class="w-2 h-2 rounded-full mt-1 shrink-0" :class="$store.app.currentRole === 'koordinator' ? 'bg-[#0A0A0B]' : 'bg-[#CCCCCC]'"></span>
                  <div>
                    <p class="font-semibold text-[#0A0A0B]">4. Koordinator TA</p>
                    <p class="text-[10px] text-[#5D5D5F]">Tetapkan pembimbing, tanggal ujian & penguji 1 & 2</p>
                  </div>
                </button>

                <button @click="$store.app.setRole('kaprodi')" class="w-full text-left p-2 rounded hover:bg-[#F5F5F5] transition-colors flex items-start gap-2">
                  <span class="w-2 h-2 rounded-full mt-1 shrink-0" :class="$store.app.currentRole === 'kaprodi' ? 'bg-[#0A0A0B]' : 'bg-[#CCCCCC]'"></span>
                  <div>
                    <p class="font-semibold text-[#0A0A0B]">5. Ketua Program Studi (Kaprodi)</p>
                    <p class="text-[10px] text-[#5D5D5F]">Tetapkan Koordinator TA & pantau statistik kelulusan</p>
                  </div>
                </button>

                <button @click="$store.app.setRole('ta')" class="w-full text-left p-2 rounded hover:bg-[#F5F5F5] transition-colors flex items-start gap-2">
                  <span class="w-2 h-2 rounded-full mt-1 shrink-0" :class="$store.app.currentRole === 'ta' ? 'bg-[#0A0A0B]' : 'bg-[#CCCCCC]'"></span>
                  <div>
                    <p class="font-semibold text-[#0A0A0B]">6. Teaching Assistant (TA)</p>
                    <p class="text-[10px] text-[#5D5D5F]">Verifikasi artefak ujian & unggah arsip naskah alumni</p>
                  </div>
                </button>

                <button @click="$store.app.setRole('baak')" class="w-full text-left p-2 rounded hover:bg-[#F5F5F5] transition-colors flex items-start gap-2">
                  <span class="w-2 h-2 rounded-full mt-1 shrink-0" :class="$store.app.currentRole === 'baak' ? 'bg-[#0A0A0B]' : 'bg-[#CCCCCC]'"></span>
                  <div>
                    <p class="font-semibold text-[#0A0A0B]">7. BAAK IT Del</p>
                    <p class="text-[10px] text-[#5D5D5F]">Atur ruangan ujian (GD 514, dll), jam & link Zoom</p>
                  </div>
                </button>
              </div>
            </div>
          </div>

          <!-- NOTIFICATION BELL & POP-UP ALERT DROPDOWN -->
          <div class="relative" @click.away="$store.app.notificationOpen = false">
            <button @click="$store.app.notificationOpen = !$store.app.notificationOpen" 
                    class="btn-icon relative" title="Notifikasi In-App">
              <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
              </svg>
              <template x-if="$store.app.unreadNotificationCount > 0">
                <span class="absolute -top-1 -right-1 w-4 h-4 bg-red-600 text-white text-[10px] font-bold rounded-full flex items-center justify-center">
                  <span x-text="$store.app.unreadNotificationCount"></span>
                </span>
              </template>
            </button>

            <!-- Dropdown Notifikasi Notion Style -->
            <div x-show="$store.app.notificationOpen" x-cloak
                 class="absolute right-0 mt-2 w-80 sm:w-96 bg-[#FFFFFF] border border-[#E0E0E0] rounded-xl shadow-xl z-50 overflow-hidden text-xs">
              <div class="p-3.5 border-b border-[#E0E0E0] flex items-center justify-between bg-[#F5F5F5]">
                <div class="flex items-center gap-2">
                  <span class="font-bold text-[#0A0A0B]">Pemberitahuan Sistem</span>
                  <span class="pill-neutral text-[10px]" x-text="$store.app.unreadNotificationCount + ' Baru'"></span>
                </div>
                <button @click="$store.app.markAllNotificationsRead()" class="text-[11px] text-[#4D4D4F] hover:text-[#0A0A0B] underline">
                  Tandai semua dibaca
                </button>
              </div>

              <div class="divide-y divide-[#EBEBEB] max-h-80 overflow-y-auto">
                <template x-for="item in $store.app.notifications" :key="item.id">
                  <div class="p-3.5 hover:bg-[#F5F5F5] transition-colors" :class="item.read ? 'opacity-70' : 'bg-amber-50/20'">
                    <div class="flex items-start justify-between gap-2">
                      <div class="flex items-center gap-1.5">
                        <span class="font-bold text-[#0A0A0B]" x-text="item.sender"></span>
                        <span class="text-[10px] text-[#5D5D5F]" x-text="'(' + item.role + ')'"></span>
                      </div>
                      <span class="text-[10px] text-[#5D5D5F] whitespace-nowrap" x-text="item.time"></span>
                    </div>
                    <p class="text-xs text-[#2C2C2F] mt-1 font-medium" x-text="item.title"></p>
                    <div class="mt-2 flex items-center justify-between">
                      <button @click="$store.app.notificationOpen = false; $store.app.currentScreen = (item.type === 'review' ? 'mahasiswa_dashboard' : (item.type === 'appointment' ? 'janji_bimbingan' : 'ujian_ta'))" 
                              class="text-[11px] font-semibold text-[#0A0A0B] underline hover:text-[#4D4D4F]" x-text="item.linkText">
                      </button>
                      <span class="w-1.5 h-1.5 rounded-full" :class="item.read ? 'bg-transparent' : 'bg-red-500'"></span>
                    </div>
                  </div>
                </template>
              </div>

              <div class="p-2 border-t border-[#E0E0E0] text-center bg-[#F5F5F5]">
                <span class="text-[11px] text-[#5D5D5F]">Notifikasi in-app real-time FITE IT Del</span>
              </div>
            </div>
          </div>

          <!-- USER PROFILE BADGE -->
          <div class="flex items-center gap-2 pl-2 border-l border-[#E0E0E0]">
            <div class="w-8 h-8 rounded-full bg-[#1B1B1E] text-white flex items-center justify-center font-bold text-xs">
              <span x-text="$store.app.currentRole.substring(0, 2).toUpperCase()"></span>
            </div>
            <div class="hidden md:block text-left">
              <p class="text-xs font-bold text-[#0A0A0B] leading-none" x-text="$store.app.getUserName()"></p>
              <p class="text-[10px] text-[#5D5D5F] mt-0.5" x-text="$store.app.getRoleLabel()"></p>
            </div>
          </div>

        </div>

      </header>

      <!-- CANVAS UTAMA: Menampilkan Screen Aktif -->
      <main class="flex-1 overflow-y-auto bg-[#FFFFFF]">
        @include('screens.login')
        @include('screens.mahasiswa-dashboard')
        @include('screens.dosen-workspace')
        @include('screens.janji-bimbingan')
        @include('screens.ujian-ta')
        @include('screens.pustaka-ta')
        @include('screens.panel-eksekutif')
      </main>

    </div>

  </div>

  <!-- Include Modals & Drawers Partial -->
  @include('partials.modals')

  <!-- Bootstrap 5.3 JS Bundle -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'Logbook & Manajemen Tugas Akhir') - FITE Institut Teknologi Del</title>

  <!-- Google Fonts: Plus Jakarta Sans & Inter (Gilroy fallback) -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  @vite(['resources/css/app.css', 'resources/js/app.js'])

  <style>
    [x-cloak] { display: none !important; }
    
    /* Notion Aesthetic Specific Tokens */
    :root {
      --notion-sidebar-bg: #F7F7F5;
      --notion-sidebar-border: #E9E9E7;
      --notion-sidebar-hover: #EBEBE9;
      --notion-text-main: #37352F;
      --notion-text-secondary: #787774;
      --notion-callout-bg: #F7F6F3;
      --notion-border-subtle: #E0E0E0;
    }

    body {
      color: var(--notion-text-main);
      background-color: #FFFFFF;
      font-family: 'Gilroy', 'Plus Jakarta Sans', 'Inter', system-ui, -apple-system, sans-serif;
    }

    /* Notion Database Status Tag Pills */
    .notion-pill-green {
      background-color: #EBF5EE;
      color: #2A7E4B;
      border: 1px solid #C4E5D1;
      border-radius: 4px;
      padding: 2px 8px;
      font-size: 11px;
      font-weight: 500;
      display: inline-flex;
      align-items: center;
      gap: 4px;
    }

    .notion-pill-orange {
      background-color: #FDF5E6;
      color: #B86200;
      border: 1px solid #F3E0A2;
      border-radius: 4px;
      padding: 2px 8px;
      font-size: 11px;
      font-weight: 500;
      display: inline-flex;
      align-items: center;
      gap: 4px;
    }

    .notion-pill-red {
      background-color: #FDEBEC;
      color: #C4383D;
      border: 1px solid #F6C8C6;
      border-radius: 4px;
      padding: 2px 8px;
      font-size: 11px;
      font-weight: 500;
      display: inline-flex;
      align-items: center;
      gap: 4px;
    }

    .notion-pill-gray {
      background-color: #F1F1EF;
      color: #5A5A58;
      border: 1px solid #E0E0E0;
      border-radius: 4px;
      padding: 2px 8px;
      font-size: 11px;
      font-weight: 500;
      display: inline-flex;
      align-items: center;
      gap: 4px;
    }

    /* Notion Callout Block */
    .notion-callout {
      background-color: #F7F6F3;
      border: 1px solid #E9E9E7;
      border-radius: 6px;
      padding: 14px 16px;
      display: flex;
      align-items: flex-start;
      gap: 12px;
    }

    /* Notion Buttons */
    .notion-btn-primary {
      background-color: #2C2C2F;
      color: #FFFFFF;
      font-size: 14px;
      font-weight: 500;
      border-radius: 6px;
      padding: 6px 14px;
      display: inline-flex;
      align-items: center;
      gap: 6px;
      transition: background-color 0.15s ease-in-out;
      border: 1px solid transparent;
      cursor: pointer;
    }
    .notion-btn-primary:hover {
      background-color: #0A0A0B;
    }

    .notion-btn-secondary {
      background-color: #FFFFFF;
      color: #0A0A0B;
      font-size: 14px;
      font-weight: 500;
      border-radius: 6px;
      padding: 6px 14px;
      display: inline-flex;
      align-items: center;
      gap: 6px;
      transition: all 0.15s ease-in-out;
      border: 1px solid #0A0A0B;
      cursor: pointer;
    }
    .notion-btn-secondary:hover {
      background-color: #0A0A0B;
      color: #FFFFFF;
    }
  </style>
</head>
<body class="bg-[#FFFFFF] antialiased overflow-x-hidden" x-data="{ sidebarOpen: true, roleTesterOpen: false, notifDropdownOpen: false }">

  <!-- Flash Toast Notifications -->
  @if (session('success'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
         class="fixed bottom-6 right-6 z-50 bg-[#0A0A0B] text-white px-4 py-3 rounded-lg shadow-xl border border-[#2C2C2F] flex items-center gap-3 text-xs">
      <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
      <span>{{ session('success') }}</span>
      <button @click="show = false" class="text-gray-400 hover:text-white ml-2">&times;</button>
    </div>
  @endif

  @if (session('error'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
         class="fixed bottom-6 right-6 z-50 bg-[#C4383D] text-white px-4 py-3 rounded-lg shadow-xl flex items-center gap-3 text-xs">
      <span class="w-2 h-2 rounded-full bg-white"></span>
      <span>{{ session('error') }}</span>
      <button @click="show = false" class="text-white hover:opacity-75 ml-2">&times;</button>
    </div>
  @endif

  @if (session('info'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
         class="fixed bottom-6 right-6 z-50 bg-[#1B1B1E] text-white px-4 py-3 rounded-lg shadow-xl border border-[#3D3D3F] flex items-center gap-3 text-xs">
      <span class="w-2 h-2 rounded-full bg-blue-400"></span>
      <span>{{ session('info') }}</span>
      <button @click="show = false" class="text-gray-400 hover:text-white ml-2">&times;</button>
    </div>
  @endif

  @if (isset($errors) && $errors->any())
    <div x-data="{ show: true }" x-show="show"
         class="fixed bottom-6 right-6 z-50 bg-[#C4383D] text-white px-4 py-3 rounded-lg shadow-xl flex items-center gap-3 text-xs">
      <span class="w-2 h-2 rounded-full bg-white"></span>
      <span>{{ $errors->first() }}</span>
      <button @click="show = false" class="text-white hover:opacity-75 ml-2">&times;</button>
    </div>
  @endif

  <div class="flex h-screen overflow-hidden">
    
    <!-- ================================================================= -->
    <!-- LEFT SIDEBAR (Lebar 240px, bg #F7F7F5, border-r 1px #E9E9E7) -->
    <!-- ================================================================= -->
    <aside :class="sidebarOpen ? 'w-60' : 'w-14'"
           class="h-screen bg-[#F7F7F5] border-r border-[#E9E9E7] flex flex-col justify-between transition-all duration-200 shrink-0 z-30 select-none">
      
      <!-- Top Section -->
      <div class="overflow-y-auto">
        <!-- Workspace Switcher IT Del FITE -->
        <div class="h-[52px] border-b border-[#E9E9E7] px-3 flex items-center justify-between">
          <a href="{{ route('dashboard') }}" class="flex items-center gap-2 overflow-hidden hover:opacity-90">
            <div class="w-7 h-7 rounded bg-[#2C2C2F] text-white flex items-center justify-center font-bold text-xs shrink-0 shadow-xs">
              🎓
            </div>
            <div x-show="sidebarOpen" class="truncate">
              <h2 class="text-xs font-bold text-[#37352F] tracking-tight leading-none truncate">TA FITE IT Del</h2>
              <p class="text-[10px] text-[#787774] truncate mt-0.5">Workspace Akademik</p>
            </div>
          </a>

          <button @click="sidebarOpen = !sidebarOpen" class="p-1 rounded text-[#787774] hover:bg-[#EBEBE9] hover:text-[#37352F]">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <line x1="3" y1="12" x2="21" y2="12"></line>
              <line x1="3" y1="6" x2="21" y2="6"></line>
              <line x1="3" y1="18" x2="21" y2="18"></line>
            </svg>
          </button>
        </div>

        <!-- Quick Jump / Search Placeholder -->
        <div x-show="sidebarOpen" class="px-3 pt-3">
          <a href="{{ route('arsip.index') }}" class="w-full flex items-center justify-between px-2.5 py-1.5 text-xs text-[#787774] bg-[#FFFFFF] hover:bg-[#EBEBE9] border border-[#E9E9E7] rounded transition-colors">
            <div class="flex items-center gap-1.5">
              <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="11" cy="11" r="8"></circle>
                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
              </svg>
              <span>Cari arsip & naskah...</span>
            </div>
            <span class="text-[10px] font-mono text-[#CCCCCC] bg-[#F7F7F5] px-1 rounded">⌘K</span>
          </a>
        </div>

        <!-- Sidebar Navigation Items with Notion indentation -->
        <nav class="p-2.5 space-y-4 text-xs">
          
          <!-- Section 1: Halaman Utama -->
          <div>
            <div x-show="sidebarOpen" class="px-2 mb-1 text-[10px] font-semibold uppercase tracking-wider text-[#787774]">
              Workspace
            </div>
            <div class="space-y-0.5">
              <!-- Dashboard -->
              <a href="{{ route('dashboard') }}" 
                 class="flex items-center gap-2 px-2 py-1.5 rounded text-xs transition-colors {{ request()->routeIs('dashboard') || request()->routeIs('workspace') ? 'bg-[#EBEBE9] text-[#37352F] font-semibold' : 'text-[#37352F] hover:bg-[#EBEBE9]' }}"
                 title="Dashboard Workspace">
                <span class="text-sm">🏠</span>
                <span x-show="sidebarOpen" class="truncate">Dashboard Utama</span>
              </a>

              @if (auth()->check() && !auth()->user()->isMahasiswaNonTa())
              <!-- Logbook Aktivitas -->
              <a href="{{ route('logbook.index') }}" 
                 class="flex items-center gap-2 px-2 py-1.5 rounded text-xs transition-colors {{ request()->routeIs('logbook.*') ? 'bg-[#EBEBE9] text-[#37352F] font-semibold' : 'text-[#37352F] hover:bg-[#EBEBE9]' }}"
                 title="Logbook Aktivitas & Progres">
                <span class="text-sm">📝</span>
                <span x-show="sidebarOpen" class="truncate">Logbook Aktivitas</span>
              </a>

              <!-- Janji Bimbingan -->
              <a href="{{ route('bimbingan.index') }}" 
                 class="flex items-center gap-2 px-2 py-1.5 rounded text-xs transition-colors {{ request()->routeIs('bimbingan.*') ? 'bg-[#EBEBE9] text-[#37352F] font-semibold' : 'text-[#37352F] hover:bg-[#EBEBE9]' }}"
                 title="Janji Bimbingan Dua Arah">
                <span class="text-sm">📅</span>
                <span x-show="sidebarOpen" class="truncate">Janji Bimbingan</span>
              </a>

              <!-- Alur & Jadwal Ujian TA -->
              <a href="{{ route('ujian.index') }}" 
                 class="flex items-center gap-2 px-2 py-1.5 rounded text-xs transition-colors {{ request()->routeIs('ujian.*') ? 'bg-[#EBEBE9] text-[#37352F] font-semibold' : 'text-[#37352F] hover:bg-[#EBEBE9]' }}"
                 title="Pendaftaran & Jadwal Ujian">
                <span class="text-sm">🏛️</span>
                <span x-show="sidebarOpen" class="truncate">Manajemen Ujian TA</span>
              </a>
              @endif
            </div>
          </div>

          <!-- Section 2: Pustaka & Repositori -->
          <div>
            <div x-show="sidebarOpen" class="px-2 mb-1 text-[10px] font-semibold uppercase tracking-wider text-[#787774]">
              Koleksi & Database
            </div>
            <div class="space-y-0.5">
              <!-- Pustaka Arsip TA -->
              <a href="{{ route('arsip.index') }}" 
                 class="flex items-center gap-2 px-2 py-1.5 rounded text-xs transition-colors {{ request()->routeIs('arsip.*') ? 'bg-[#EBEBE9] text-[#37352F] font-semibold' : 'text-[#37352F] hover:bg-[#EBEBE9]' }}"
                 title="Pustaka Arsip Tugas Akhir Alumni">
                <span class="text-sm">📚</span>
                <span x-show="sidebarOpen" class="truncate">Pustaka Arsip TA</span>
              </a>
            </div>
          </div>

          <!-- Section 3: Manajemen Program Studi (Koordinator & Kaprodi) -->
          @if (auth()->check() && (auth()->user()->isKoordinator() || auth()->user()->isKaprodi()))
          <div>
            <div x-show="sidebarOpen" class="px-2 mb-1 text-[10px] font-semibold uppercase tracking-wider text-[#787774]">
              Manajemen Program Studi
            </div>
            <div class="space-y-0.5">
              @if (auth()->user()->isKoordinator())
              <a href="{{ route('koordinator.index') }}" 
                 class="flex items-center gap-2 px-2 py-1.5 rounded text-xs transition-colors {{ request()->routeIs('koordinator.*') ? 'bg-[#EBEBE9] text-[#37352F] font-semibold' : 'text-[#37352F] hover:bg-[#EBEBE9]' }}"
                 title="Panel Koordinator TA">
                <span class="text-sm">⚙️</span>
                <span x-show="sidebarOpen" class="truncate">Koordinator TA</span>
              </a>
              @endif

              @if (auth()->user()->isKaprodi())
              <a href="{{ route('kaprodi.index') }}" 
                 class="flex items-center gap-2 px-2 py-1.5 rounded text-xs transition-colors {{ request()->routeIs('kaprodi.*') ? 'bg-[#EBEBE9] text-[#37352F] font-semibold' : 'text-[#37352F] hover:bg-[#EBEBE9]' }}"
                 title="Panel Eksekutif Kaprodi">
                <span class="text-sm">📊</span>
                <span x-show="sidebarOpen" class="truncate">Panel Kaprodi</span>
              </a>
              @endif
            </div>
          </div>
          @endif

        </nav>
      </div>

      <!-- Bottom User Section & Logout -->
      <div class="p-2.5 border-t border-[#E9E9E7] bg-[#F7F7F5]">
        @auth
          <div x-show="sidebarOpen" class="mb-2">
            <div class="px-2 py-1 rounded bg-[#FFFFFF] border border-[#E9E9E7] text-[11px]">
              <span class="text-[#787774] block text-[9px] uppercase font-semibold">Peran Aktif:</span>
              <span class="font-bold text-[#37352F] truncate block">
                {{ strtoupper(str_replace('_', ' ', auth()->user()->role)) }}
                @if (auth()->user()->isKoordinator())
                  <span class="text-emerald-700 font-semibold">• Koordinator TA</span>
                @endif
              </span>
            </div>
          </div>

          <div class="flex items-center justify-between px-1">
            <div class="flex items-center gap-2 overflow-hidden">
              <div class="w-6 h-6 rounded-full bg-[#37352F] text-white flex items-center justify-center font-bold text-[10px] shrink-0">
                {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
              </div>
              <div x-show="sidebarOpen" class="truncate text-left">
                <p class="text-xs font-semibold text-[#37352F] leading-tight truncate">{{ auth()->user()->name }}</p>
                <p class="text-[10px] text-[#787774] truncate">{{ auth()->user()->email }}</p>
              </div>
            </div>

            <form action="{{ route('logout') }}" method="POST" x-show="sidebarOpen">
              @csrf
              <button type="submit" class="p-1 rounded text-[#787774] hover:text-[#C4383D] hover:bg-[#FDEBEC]" title="Keluar">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                  <polyline points="16 17 21 12 16 7"></polyline>
                  <line x1="21" y1="12" x2="9" y2="12"></line>
                </svg>
              </button>
            </form>
          </div>
        @else
          <a href="{{ route('login') }}" class="notion-btn-primary w-full text-xs justify-center py-1.5">
            Masuk Akun
          </a>
        @endauth
      </div>

    </aside>

    <!-- ================================================================= -->
    <!-- MAIN CONTENT & TOP HEADER (Tinggi 52px, border-b 1px #E9E9E7) -->
    <!-- ================================================================= -->
    <div class="flex-1 flex flex-col h-screen overflow-hidden">
      
      <!-- Top Header 52px -->
      <header class="h-[52px] bg-[#FFFFFF] border-b border-[#E9E9E7] px-6 flex items-center justify-between z-20 shrink-0">
        
        <!-- Breadcrumb Ala Notion -->
        <div class="flex items-center gap-1.5 text-xs text-[#787774]">
          <a href="{{ route('dashboard') }}" class="hover:text-[#37352F] flex items-center gap-1">
            <span>FITE IT Del</span>
          </a>
          <span>/</span>
          <span class="text-[#787774]">Tugas Akhir</span>
          <span>/</span>
          <span class="font-semibold text-[#37352F]">@yield('breadcrumb', 'Workspace')</span>
        </div>

        <!-- Right Quick Actions & Role Switcher Tester -->
        <div class="flex items-center gap-2.5">
          
          <!-- Role Switcher Tester Dropdown -->
          <div class="relative" @click.away="roleTesterOpen = false">
            <button @click="roleTesterOpen = !roleTesterOpen" 
                    class="flex items-center gap-1.5 px-2.5 py-1 bg-[#F7F7F5] border border-[#E9E9E7] rounded text-xs font-medium text-[#37352F] hover:bg-[#EBEBE9] transition-colors">
              <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
              <span class="hidden sm:inline">Ganti Akun Demo:</span>
              <span class="font-semibold">{{ auth()->check() ? auth()->user()->name : 'Guest' }}</span>
              <svg class="w-3.5 h-3.5 ml-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polyline points="6 9 12 15 18 9"></polyline>
              </svg>
            </button>

            <!-- Dropdown Menu Akun Demo -->
            <div x-show="roleTesterOpen" x-cloak
                 class="absolute right-0 mt-2 w-72 bg-[#FFFFFF] border border-[#E9E9E7] rounded-lg shadow-xl py-2 z-50 text-xs">
              <div class="px-3 py-1.5 border-b border-[#E9E9E7]">
                <p class="font-bold text-[#37352F]">Pilih Akun Demo Pengujian FITE</p>
                <p class="text-[10px] text-[#787774]">Simulasi login tanpa input password</p>
              </div>

              <div class="max-h-64 overflow-y-auto p-1 space-y-0.5">
                <a href="{{ route('demo.login', 'mahasiswa_ta') }}" class="block p-2 rounded hover:bg-[#F7F7F5] transition-colors text-left">
                  <p class="font-semibold text-[#37352F]">1. Mahasiswa TA Aktif</p>
                  <p class="text-[10px] text-[#787774]">Samuel Siahaan (11S21004) - Ada TA & Logbook</p>
                </a>
                <a href="{{ route('demo.login', 'mahasiswa_non_ta') }}" class="block p-2 rounded hover:bg-[#F7F7F5] transition-colors text-left">
                  <p class="font-semibold text-[#37352F]">2. Mahasiswa Non-TA</p>
                  <p class="text-[10px] text-[#787774]">Yosua Tambunan (11S22014) - Pustaka Read-Only</p>
                </a>
                <a href="{{ route('demo.login', 'dosen') }}" class="block p-2 rounded hover:bg-[#F7F7F5] transition-colors text-left">
                  <p class="font-semibold text-[#37352F]">3. Dosen 1 (Koordinator TA)</p>
                  <p class="text-[10px] text-[#787774]">Dr. Johannes Harungguan (JHH)</p>
                </a>
                <a href="{{ route('demo.login', 'dosen2') }}" class="block p-2 rounded hover:bg-[#F7F7F5] transition-colors text-left">
                  <p class="font-semibold text-[#37352F]">4. Dosen 2 (Pembimbing)</p>
                  <p class="text-[10px] text-[#787774]">Marlina Valentina, M.Sc. (MVN)</p>
                </a>
                <a href="{{ route('demo.login', 'kaprodi') }}" class="block p-2 rounded hover:bg-[#F7F7F5] transition-colors text-left">
                  <p class="font-semibold text-[#37352F]">5. Kaprodi FITE</p>
                  <p class="text-[10px] text-[#787774]">Dr. Arnaldo Marulitua Sinaga</p>
                </a>
                <a href="{{ route('demo.login', 'ta') }}" class="block p-2 rounded hover:bg-[#F7F7F5] transition-colors text-left">
                  <p class="font-semibold text-[#37352F]">6. Teaching Assistant (TA)</p>
                  <p class="text-[10px] text-[#787774]">Grace Panjaitan (Verifikator Berkas & Arsip)</p>
                </a>
                <a href="{{ route('demo.login', 'baak') }}" class="block p-2 rounded hover:bg-[#F7F7F5] transition-colors text-left">
                  <p class="font-semibold text-[#37352F]">7. BAAK IT Del</p>
                  <p class="text-[10px] text-[#787774]">Pengatur Ruang Ujian, Jam & Link Zoom</p>
                </a>
              </div>
            </div>
          </div>

          <!-- Lonceng Notifikasi In-App -->
          <div class="relative" @click.away="notifDropdownOpen = false">
            <button @click="notifDropdownOpen = !notifDropdownOpen" class="p-1.5 rounded hover:bg-[#F7F7F5] text-[#787774] hover:text-[#37352F] relative">
              <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
              </svg>
              <span class="absolute top-1 right-1 w-2 h-2 rounded-full bg-red-500"></span>
            </button>

            <!-- Dropdown Notifikasi -->
            <div x-show="notifDropdownOpen" x-cloak
                 class="absolute right-0 mt-2 w-80 bg-[#FFFFFF] border border-[#E9E9E7] rounded-lg shadow-xl p-3 z-50 text-xs">
              <div class="flex items-center justify-between pb-2 border-b border-[#E9E9E7] mb-2">
                <span class="font-bold text-[#37352F]">Pemberitahuan Sistem</span>
                <span class="notion-pill-green text-[10px]">3 Baru</span>
              </div>
              <div class="space-y-2 max-h-60 overflow-y-auto">
                <div class="p-2 rounded bg-[#F7F6F3] border border-[#E9E9E7]">
                  <p class="font-semibold text-[#37352F]">Catatan Revisi Logbook Bab 4</p>
                  <p class="text-[10px] text-[#787774] mt-0.5">Dr. Johannes Harungguan telah menyematkan evaluasi & coretan.</p>
                </div>
                <div class="p-2 rounded bg-[#F7F6F3] border border-[#E9E9E7]">
                  <p class="font-semibold text-[#37352F]">Verifikasi Berkas Seminar TA: Lengkap</p>
                  <p class="text-[10px] text-[#787774] mt-0.5">Teaching Assistant telah memvalidasi seluruh artefak ujian.</p>
                </div>
                <div class="p-2 rounded bg-[#F7F6F3] border border-[#E9E9E7]">
                  <p class="font-semibold text-[#37352F]">Jadwal Ruangan Seminar Ditetapkan</p>
                  <p class="text-[10px] text-[#787774] mt-0.5">BAAK: Ruang Sidang FITE GD 514 & Zoom Room 1.</p>
                </div>
              </div>
            </div>
          </div>

        </div>

      </header>

      <!-- Main Canvas View -->
      <main class="flex-1 overflow-y-auto p-8 max-w-7xl w-full mx-auto">
        @yield('content')
      </main>

    </div>

  </div>

  @stack('scripts')
</body>
</html>

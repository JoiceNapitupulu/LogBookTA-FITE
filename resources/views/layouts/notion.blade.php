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

  <!-- Bootstrap 5.3 CSS & Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

  @vite(['resources/css/app.css', 'resources/js/app.js'])

  <style>
    [x-cloak] { display: none !important; }
    
    p {
      margin-bottom: 0;
    }
    h1, h2, h3, h4, h5, h6 {
      margin-bottom: 0;
      color: inherit;
    }
    a {
      text-decoration: none;
      color: inherit;
    }
    a:hover {
      color: inherit;
    }

    /* Normalize Bootstrap 5 oversized 48px spacing to compact 16px-24px */
    .p-5 { padding: 1.25rem !important; }
    .pb-5 { padding-bottom: 1.25rem !important; }
    .pt-5 { padding-top: 1.25rem !important; }
    .py-5 { padding-top: 1.25rem !important; padding-bottom: 1.25rem !important; }
    .px-5 { padding-left: 1.25rem !important; padding-right: 1.25rem !important; }
    .mb-5 { margin-bottom: 1.25rem !important; }

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
         class="fixed bottom-6 right-6 z-50 bg-[#1B1B1E] text-white px-4 py-3 rounded-lg shadow-xl border border-[#3D3D3F] flex items-center gap-3 text-xs">
      <span class="w-2 h-2 rounded-full bg-rose-400"></span>
      <span class="text-[#FCA5A5]">{{ session('error') }}</span>
      <button @click="show = false" class="text-gray-400 hover:text-white ml-2">&times;</button>
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
          <a href="{{ route('dashboard') }}" class="flex items-center gap-2 overflow-hidden hover:opacity-90 min-w-0">
            <img src="{{ asset('images/logo-del.png') }}" alt="Logo IT Del" class="shrink-0 object-contain" style="width: 28px; height: 28px;">
            <div x-show="sidebarOpen" class="truncate min-w-0">
              <span class="block font-bold text-[#37352F] tracking-tight leading-tight truncate" style="font-size: 13px; font-weight: 700; margin: 0; line-height: 1.2;">TA FITE IT Del</span>
              <span class="block text-[#787774] truncate" style="font-size: 10px; margin-top: 2px; line-height: 1.2;">Workspace Akademik</span>
            </div>
          </a>

          <button @click="sidebarOpen = !sidebarOpen" class="p-1.5 rounded text-[#787774] hover:bg-[#EBEBE9] hover:text-[#37352F] shrink-0" title="Toggle Sidebar">
            <i class="bi bi-layout-sidebar text-sm"></i>
          </button>
        </div>

        <!-- Quick Jump / Search Placeholder -->
        <div x-show="sidebarOpen" class="px-3 pt-3">
          <a href="{{ route('arsip.index') }}" class="w-full flex items-center justify-between px-2.5 py-1.5 text-xs text-[#787774] bg-[#FFFFFF] hover:bg-[#EBEBE9] border border-[#E9E9E7] rounded transition-colors">
            <div class="flex items-center gap-1.5">
              <i class="bi bi-search text-xs text-[#787774]"></i>
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
                 class="flex items-center gap-2.5 px-2.5 py-1.5 rounded text-xs transition-colors {{ request()->routeIs('dashboard') || request()->routeIs('workspace') ? 'bg-[#EBEBE9] text-[#37352F] font-semibold' : 'text-[#37352F] hover:bg-[#EBEBE9]' }}"
                 title="Dashboard Workspace">
                <i class="bi bi-grid-1x2 text-sm text-[#787774] w-4 text-center shrink-0"></i>
                <span x-show="sidebarOpen" class="truncate">Dashboard Utama</span>
              </a>

              @if (auth()->check() && (auth()->user()->isMahasiswaTa() || auth()->user()->isDosen()))
              <!-- Logbook Aktivitas -->
              <a href="{{ route('logbook.index') }}" 
                 class="flex items-center gap-2.5 px-2.5 py-1.5 rounded text-xs transition-colors {{ request()->routeIs('logbook.*') ? 'bg-[#EBEBE9] text-[#37352F] font-semibold' : 'text-[#37352F] hover:bg-[#EBEBE9]' }}"
                 title="Logbook Aktivitas & Progres">
                <i class="bi bi-journal-text text-sm text-[#787774] w-4 text-center shrink-0"></i>
                <span x-show="sidebarOpen" class="truncate">Logbook Aktivitas</span>
              </a>

              <!-- Janji Bimbingan -->
              <a href="{{ route('bimbingan.index') }}" 
                 class="flex items-center gap-2.5 px-2.5 py-1.5 rounded text-xs transition-colors {{ request()->routeIs('bimbingan.*') ? 'bg-[#EBEBE9] text-[#37352F] font-semibold' : 'text-[#37352F] hover:bg-[#EBEBE9]' }}"
                 title="Janji Bimbingan">
                <i class="bi bi-calendar-event text-sm text-[#787774] w-4 text-center shrink-0"></i>
                <span x-show="sidebarOpen" class="truncate">Janji Bimbingan</span>
              </a>
              @endif

              @if (auth()->check() && !auth()->user()->isMahasiswaNonTa())
              <!-- Alur & Jadwal Ujian TA -->
              <a href="{{ route('ujian.index') }}" 
                 class="flex items-center gap-2.5 px-2.5 py-1.5 rounded text-xs transition-colors {{ request()->routeIs('ujian.*') ? 'bg-[#EBEBE9] text-[#37352F] font-semibold' : 'text-[#37352F] hover:bg-[#EBEBE9]' }}"
                 title="Pendaftaran & Jadwal Ujian">
                <i class="bi bi-mortarboard text-sm text-[#787774] w-4 text-center shrink-0"></i>
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
                 class="flex items-center gap-2.5 px-2.5 py-1.5 rounded text-xs transition-colors {{ request()->routeIs('arsip.*') ? 'bg-[#EBEBE9] text-[#37352F] font-semibold' : 'text-[#37352F] hover:bg-[#EBEBE9]' }}"
                 title="Pustaka Arsip Tugas Akhir Alumni">
                <i class="bi bi-collection text-sm text-[#787774] w-4 text-center shrink-0"></i>
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
                 class="flex items-center gap-2.5 px-2.5 py-1.5 rounded text-xs transition-colors {{ request()->routeIs('koordinator.*') ? 'bg-[#EBEBE9] text-[#37352F] font-semibold' : 'text-[#37352F] hover:bg-[#EBEBE9]' }}"
                 title="Panel Koordinator TA">
                <i class="bi bi-sliders text-sm text-[#787774] w-4 text-center shrink-0"></i>
                <span x-show="sidebarOpen" class="truncate">Koordinator TA</span>
              </a>
              @endif

              @if (auth()->user()->isKaprodi())
              <a href="{{ route('kaprodi.index') }}" 
                 class="flex items-center gap-2.5 px-2.5 py-1.5 rounded text-xs transition-colors {{ request()->routeIs('kaprodi.*') ? 'bg-[#EBEBE9] text-[#37352F] font-semibold' : 'text-[#37352F] hover:bg-[#EBEBE9]' }}"
                 title="Panel Eksekutif Kaprodi">
                <i class="bi bi-bar-chart-line text-sm text-[#787774] w-4 text-center shrink-0"></i>
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
          <div class="flex items-center justify-between px-1">
            <div class="flex items-center gap-2 overflow-hidden">
              <div class="w-7 h-7 rounded-full bg-[#37352F] text-white flex items-center justify-center font-bold text-[11px] shrink-0">
                {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
              </div>
              <div x-show="sidebarOpen" class="truncate text-left">
                <p class="text-xs font-semibold text-[#37352F] leading-tight truncate">{{ auth()->user()->name }}</p>
                <p class="text-[10px] text-[#787774] truncate">{{ auth()->user()->email }}</p>
              </div>
            </div>

            <form action="{{ route('logout') }}" method="POST" x-show="sidebarOpen">
              @csrf
              <button type="submit" class="p-1.5 rounded text-[#787774] hover:text-[#C4383D] hover:bg-[#FDEBEC] transition-colors" title="Keluar">
                <i class="bi bi-box-arrow-right text-sm"></i>
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

        <!-- Right Quick Actions -->
        <div class="flex items-center gap-3">
          
          <!-- Lonceng Notifikasi In-App -->
          <div class="relative" @click.away="notifDropdownOpen = false">
            <button @click="notifDropdownOpen = !notifDropdownOpen" class="p-1.5 rounded hover:bg-[#F7F7F5] text-[#787774] hover:text-[#37352F] relative" title="Notifikasi">
              <i class="bi bi-bell text-base"></i>
              <span class="position-absolute top-1 end-1 p-1 bg-danger border border-light rounded-circle">
                <span class="visually-hidden">Notifikasi Baru</span>
              </span>
            </button>

            <!-- Dropdown Notifikasi -->
            <div x-show="notifDropdownOpen" x-cloak
                 class="absolute right-0 mt-2 w-80 bg-[#FFFFFF] border border-[#E9E9E7] rounded-lg shadow-xl p-3 z-50 text-xs">
              <div class="flex items-center justify-between pb-2 border-b border-[#E9E9E7] mb-2">
                <span class="font-bold text-[#37352F]">Pemberitahuan Sistem</span>
                <span class="badge bg-success-subtle text-success border border-success-subtle text-[10px]">3 Baru</span>
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

          @auth
            <div class="d-flex align-items-center gap-2 border-start ps-3 border-[#E9E9E7]">
              <div class="text-end d-none d-sm-block">
                <p class="text-xs font-semibold text-[#37352F] leading-tight mb-0">{{ auth()->user()->name }}</p>
                <p class="text-[10px] text-[#787774] mb-0">{{ strtoupper(str_replace('_', ' ', auth()->user()->role)) }}</p>
              </div>
              <div class="w-7 h-7 rounded-full bg-[#2C2C2F] text-white flex items-center justify-center font-bold text-[11px] shrink-0">
                {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
              </div>
            </div>
          @endauth

        </div>

      </header>

      <!-- Main Canvas View -->
      <main class="flex-1 overflow-y-auto p-4 md:p-6 max-w-7xl w-full mx-auto">
        @yield('content')
      </main>

    </div>

  </div>

  <!-- Bootstrap 5.3 JS Bundle -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  @stack('scripts')
</body>
</html>

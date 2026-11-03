<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'Logbook & Manajemen Tugas Akhir') - FITE Institut Teknologi Del</title>

  <!-- Google Fonts: Plus Jakarta Sans & Inter -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

  <!-- Bootstrap 5.3 CSS & Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

  @vite(['resources/css/app.css', 'resources/js/app.js'])

  <style>
    [x-cloak] { display: none !important; }
    
    html, body {
      height: 100%;
      min-height: 100vh;
      margin: 0;
      padding: 0;
    }

    body {
      font-family: 'Plus Jakarta Sans', 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
      background-color: #f8f9fa;
      color: #212529;
    }

    /* Academic sidebar styling */
    .sidebar-academic {
      background-color: #ffffff;
      border-right: 1px solid #e9ecef;
    }
    
    .nav-link-academic {
      color: #495057;
      border-radius: 6px;
      padding: 8px 12px;
      font-size: 0.8125rem;
      font-weight: 500;
      display: flex;
      align-items: center;
      gap: 10px;
      transition: all 0.15s ease-in-out;
      text-decoration: none;
    }
    .nav-link-academic:hover {
      background-color: #f1f3f5;
      color: #0d6efd;
    }
    .nav-link-academic.active {
      background-color: #e7f1ff;
      color: #0d6efd;
      font-weight: 600;
    }
    .nav-link-academic.active i {
      color: #0d6efd;
    }

    /* Modal Backdrop for Alpine.js without Bootstrap !important display conflict */
    .modal-backdrop-custom {
      position: fixed;
      top: 0;
      left: 0;
      width: 100vw;
      height: 100vh;
      background-color: rgba(15, 23, 42, 0.65);
      backdrop-filter: blur(2px);
      display: flex;
      align-items: center;
      justify-content: center;
      z-index: 1055;
    }

    /* Normalize legacy utilities for clean academic spacing */
    .p-5 { padding: 1.25rem !important; }
    .pb-5 { padding-bottom: 1.25rem !important; }
    .pt-5 { padding-top: 1.25rem !important; }
    .py-5 { padding-top: 1.25rem !important; padding-bottom: 1.25rem !important; }
    .px-5 { padding-left: 1.25rem !important; padding-right: 1.25rem !important; }
    .mb-5 { margin-bottom: 1.25rem !important; }

    /* Compatible Pills mapping to clean Bootstrap Badges */
    .notion-pill-green {
      background-color: #d1e7dd;
      color: #0f5132;
      border: 1px solid #badbcc;
      border-radius: 4px;
      padding: 3px 8px;
      font-size: 11px;
      font-weight: 600;
      display: inline-flex;
      align-items: center;
      gap: 4px;
    }

    .notion-pill-orange {
      background-color: #fff3cd;
      color: #664d03;
      border: 1px solid #ffecb5;
      border-radius: 4px;
      padding: 3px 8px;
      font-size: 11px;
      font-weight: 600;
      display: inline-flex;
      align-items: center;
      gap: 4px;
    }

    .notion-pill-red {
      background-color: #f8d7da;
      color: #842029;
      border: 1px solid #f5c2c7;
      border-radius: 4px;
      padding: 3px 8px;
      font-size: 11px;
      font-weight: 600;
      display: inline-flex;
      align-items: center;
      gap: 4px;
    }

    .notion-pill-gray {
      background-color: #e9ecef;
      color: #495057;
      border: 1px solid #dee2e6;
      border-radius: 4px;
      padding: 3px 8px;
      font-size: 11px;
      font-weight: 600;
      display: inline-flex;
      align-items: center;
      gap: 4px;
    }

    .notion-callout {
      background-color: #ffffff;
      border: 1px solid #e9ecef;
      border-left: 4px solid #0d6efd;
      border-radius: 6px;
      padding: 14px 16px;
      display: flex;
      align-items: flex-start;
      gap: 12px;
      box-shadow: 0 1px 3px rgba(0,0,0,0.04);
    }

    .notion-btn-primary {
      background-color: #212529;
      color: #ffffff;
      font-size: 13px;
      font-weight: 600;
      border-radius: 6px;
      padding: 6px 14px;
      display: inline-flex;
      align-items: center;
      gap: 6px;
      transition: background-color 0.15s ease-in-out;
      border: 1px solid transparent;
      text-decoration: none;
    }
    .notion-btn-primary:hover {
      background-color: #000000;
      color: #ffffff;
    }

    .notion-btn-secondary {
      background-color: #ffffff;
      color: #212529;
      font-size: 13px;
      font-weight: 600;
      border-radius: 6px;
      padding: 6px 14px;
      display: inline-flex;
      align-items: center;
      gap: 6px;
      transition: all 0.15s ease-in-out;
      border: 1px solid #dee2e6;
      text-decoration: none;
    }
    .notion-btn-secondary:hover {
      background-color: #f8f9fa;
      color: #000000;
      border-color: #ced4da;
    }
  </style>
</head>
<body class="antialiased overflow-x-hidden" x-data="{ sidebarOpen: true, notifDropdownOpen: false }">

  <!-- Flash Toast Notifications (Bootstrap Style) -->
  @if (session('success'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
         class="toast show align-items-center text-bg-dark border-0 position-fixed bottom-0 end-0 m-3 z-3" role="alert" aria-live="assertive" aria-atomic="true">
      <div class="d-flex">
        <div class="toast-body d-flex align-items-center gap-2">
          <i class="bi bi-check-circle-fill text-success"></i>
          <span>{{ session('success') }}</span>
        </div>
        <button type="button" class="btn-close btn-close-white me-2 m-auto" @click="show = false"></button>
      </div>
    </div>
  @endif

  @if (session('error'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
         class="toast show align-items-center text-bg-danger border-0 position-fixed bottom-0 end-0 m-3 z-3" role="alert">
      <div class="d-flex">
        <div class="toast-body d-flex align-items-center gap-2">
          <i class="bi bi-exclamation-triangle-fill"></i>
          <span>{{ session('error') }}</span>
        </div>
        <button type="button" class="btn-close btn-close-white me-2 m-auto" @click="show = false"></button>
      </div>
    </div>
  @endif

  @if (session('info'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
         class="toast show align-items-center text-bg-primary border-0 position-fixed bottom-0 end-0 m-3 z-3" role="alert">
      <div class="d-flex">
        <div class="toast-body d-flex align-items-center gap-2">
          <i class="bi bi-info-circle-fill"></i>
          <span>{{ session('info') }}</span>
        </div>
        <button type="button" class="btn-close btn-close-white me-2 m-auto" @click="show = false"></button>
      </div>
    </div>
  @endif

  @if (isset($errors) && $errors->any())
    <div x-data="{ show: true }" x-show="show"
         class="toast show align-items-center text-bg-danger border-0 position-fixed bottom-0 end-0 m-3 z-3" role="alert">
      <div class="d-flex">
        <div class="toast-body d-flex align-items-center gap-2">
          <i class="bi bi-exclamation-circle-fill"></i>
          <span>{{ $errors->first() }}</span>
        </div>
        <button type="button" class="btn-close btn-close-white me-2 m-auto" @click="show = false"></button>
      </div>
    </div>
  @endif

  <div class="d-flex h-100" style="height: 100vh; overflow: hidden;">
    
    <!-- ================================================================= -->
    <!-- LEFT SIDEBAR ACADEMIC (Width 240px, bg #ffffff, border-end #e9ecef) -->
    <!-- ================================================================= -->
    <aside :class="sidebarOpen ? 'w-60' : 'w-14'"
           class="sidebar-academic d-flex flex-column justify-content-between transition-all shrink-0 z-3 select-none"
           style="width: 240px; height: 100vh; min-height: 100vh; max-height: 100vh; transition: width 0.2s ease;">
      
      <!-- Top Section (flex-grow-1 pushes user profile strictly to the bottom) -->
      <div class="overflow-y-auto flex-grow-1">
        
        <!-- Logo Del & Brand Title -->
        <div class="border-bottom px-3 py-2 d-flex align-items-center justify-content-between" style="height: 56px; border-color: #e9ecef !important;">
          <a href="{{ route('dashboard') }}" class="d-flex align-items-center gap-2 text-decoration-none min-w-0">
            <img src="{{ asset('images/logo-del.png') }}" alt="Logo Institut Teknologi Del" class="shrink-0 object-contain" style="width: 28px; height: 28px;">
            <div x-show="sidebarOpen" style="min-width: 0;">
              <span class="d-block fw-bold text-dark lh-1 text-nowrap" style="font-size: 13px; letter-spacing: -0.2px;">TA FITE IT Del</span>
              <span class="d-block text-secondary lh-1 mt-1 text-nowrap" style="font-size: 10px;">Workspace Akademik</span>
            </div>
          </a>

          <button @click="sidebarOpen = !sidebarOpen" class="btn btn-sm btn-light p-1 text-secondary shrink-0" title="Sembunyikan / Tampilkan Sidebar">
            <i class="bi bi-layout-sidebar"></i>
          </button>
        </div>

        <!-- Pencarian Cepat Arsip -->
        <div x-show="sidebarOpen" class="px-3 pt-3">
          <a href="{{ route('arsip.index') }}" class="d-flex align-items-center justify-content-between px-2.5 py-1.5 text-secondary bg-light border rounded text-decoration-none" style="font-size: 11.5px;">
            <div class="d-flex align-items-center gap-1.5">
              <i class="bi bi-search text-secondary" style="font-size: 11px;"></i>
              <span>Cari naskah arsip...</span>
            </div>
            <span class="badge bg-white text-secondary border font-monospace text-[9px] px-1 py-0.5">Katalog</span>
          </a>
        </div>

        <!-- Sidebar Navigation Items with Bootstrap Icons -->
        <nav class="p-2 space-y-3" style="font-size: 12.5px;">
          
          <!-- Menu Utama -->
          <div class="mb-3">
            <div x-show="sidebarOpen" class="px-2 mb-1 text-secondary text-uppercase fw-bold" style="font-size: 10px; letter-spacing: 0.5px;">
              Menu Utama
            </div>
            <div class="d-flex flex-column gap-1">
              <!-- Dashboard -->
              <a href="{{ route('dashboard') }}" 
                 class="nav-link-academic {{ request()->routeIs('dashboard') || request()->routeIs('workspace') ? 'active' : '' }}"
                 title="Dashboard Utama">
                <i class="bi bi-grid-1x2 text-center" style="width: 18px;"></i>
                <span x-show="sidebarOpen" class="text-truncate">Dashboard Utama</span>
              </a>

              @if (auth()->check() && (auth()->user()->isMahasiswaTa() || auth()->user()->isDosen()))
              <!-- Logbook Aktivitas -->
              <a href="{{ route('logbook.index') }}" 
                 class="nav-link-academic {{ request()->routeIs('logbook.*') ? 'active' : '' }}"
                 title="Logbook Aktivitas">
                <i class="bi bi-journal-text text-center" style="width: 18px;"></i>
                <span x-show="sidebarOpen" class="text-truncate">Logbook Aktivitas</span>
              </a>

              <!-- Janji Bimbingan -->
              <a href="{{ route('bimbingan.index') }}" 
                 class="nav-link-academic {{ request()->routeIs('bimbingan.*') ? 'active' : '' }}"
                 title="Janji Bimbingan">
                <i class="bi bi-calendar-check text-center" style="width: 18px;"></i>
                <span x-show="sidebarOpen" class="text-truncate">Janji Bimbingan</span>
              </a>
              @endif

              @if (auth()->check() && !auth()->user()->isMahasiswaNonTa())
              <!-- Manajemen Ujian TA -->
              <a href="{{ route('ujian.index') }}" 
                 class="nav-link-academic {{ request()->routeIs('ujian.*') ? 'active' : '' }}"
                 title="Manajemen Ujian TA">
                <i class="bi bi-mortarboard text-center" style="width: 18px;"></i>
                <span x-show="sidebarOpen" class="text-truncate">Manajemen Ujian TA</span>
              </a>
              @endif
            </div>
          </div>

          <!-- Pustaka & Repositori -->
          <div class="mb-3">
            <div x-show="sidebarOpen" class="px-2 mb-1 text-secondary text-uppercase fw-bold" style="font-size: 10px; letter-spacing: 0.5px;">
              Pustaka & Naskah
            </div>
            <div class="d-flex flex-column gap-1">
              <!-- Pustaka Arsip TA -->
              <a href="{{ route('arsip.index') }}" 
                 class="nav-link-academic {{ request()->routeIs('arsip.*') ? 'active' : '' }}"
                 title="Pustaka Arsip TA">
                <i class="bi bi-archive text-center" style="width: 18px;"></i>
                <span x-show="sidebarOpen" class="text-truncate">Pustaka Arsip TA</span>
              </a>
            </div>
          </div>

          <!-- Otoritas Program Studi (Koordinator & Kaprodi) -->
          @if (auth()->check() && (auth()->user()->isKoordinator() || auth()->user()->isKaprodi()))
          <div>
            <div x-show="sidebarOpen" class="px-2 mb-1 text-secondary text-uppercase fw-bold" style="font-size: 10px; letter-spacing: 0.5px;">
              Program Studi
            </div>
            <div class="d-flex flex-column gap-1">
              @if (auth()->user()->isKoordinator())
              <a href="{{ route('koordinator.index') }}" 
                 class="nav-link-academic {{ request()->routeIs('koordinator.*') ? 'active' : '' }}"
                 title="Panel Koordinator TA">
                <i class="bi bi-diagram-3 text-center" style="width: 18px;"></i>
                <span x-show="sidebarOpen" class="text-truncate">Koordinator TA</span>
              </a>
              @endif

              @if (auth()->user()->isKaprodi())
              <a href="{{ route('kaprodi.index') }}" 
                 class="nav-link-academic {{ request()->routeIs('kaprodi.*') ? 'active' : '' }}"
                 title="Panel Kaprodi">
                <i class="bi bi-bar-chart text-center" style="width: 18px;"></i>
                <span x-show="sidebarOpen" class="text-truncate">Panel Kaprodi</span>
              </a>
              @endif
            </div>
          </div>
          @endif

        </nav>
      </div>

      <!-- Sidebar Footer (System Version / Info) -->
      <div class="p-2.5 border-top text-center mt-auto shrink-0 bg-light" style="border-color: #e9ecef !important;">
        <span x-show="sidebarOpen" class="text-secondary small d-block" style="font-size: 11px;">
          LogBookTA · FITE IT Del
        </span>
        <span x-show="!sidebarOpen" class="text-secondary small d-block" style="font-size: 9px;">
          TA
        </span>
      </div>

    </aside>

    <!-- ================================================================= -->
    <!-- MAIN CONTENT & TOP HEADER -->
    <!-- ================================================================= -->
    <div class="flex-grow-1 d-flex flex-column h-100 overflow-hidden" style="background-color: #f8f9fa;">
      
      <!-- Top Header 56px -->
      <header class="bg-white border-bottom px-4 d-flex align-items-center justify-content-between shrink-0" style="height: 56px; border-color: #e9ecef !important;">
        
        <!-- Breadcrumb Bootstrap Sederhana -->
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb mb-0 text-xs" style="font-size: 12px;">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-secondary text-decoration-none">TA FITE</a></li>
            <li class="breadcrumb-item active text-dark fw-semibold" aria-current="page">@yield('breadcrumb', 'Workspace')</li>
          </ol>
        </nav>

        <!-- Right Quick Actions -->
        <div class="d-flex align-items-center gap-3">
          
          <!-- Lonceng Notifikasi In-App -->
          <div class="position-relative" @click.away="notifDropdownOpen = false">
            <button @click="notifDropdownOpen = !notifDropdownOpen" class="btn btn-sm btn-light text-secondary position-relative p-1.5" title="Pemberitahuan">
              <i class="bi bi-bell fs-6"></i>
              <span class="position-absolute top-1 start-100 translate-middle p-1 bg-primary border border-light rounded-circle">
                <span class="visually-hidden">Notifikasi</span>
              </span>
            </button>

            <!-- Dropdown Notifikasi -->
            <div x-show="notifDropdownOpen" x-cloak
                 class="position-absolute end-0 mt-2 bg-white border rounded shadow p-3 z-3 text-xs" style="width: 320px; font-size: 12px;">
              <div class="d-flex align-items-center justify-content-between pb-2 border-bottom mb-2">
                <span class="fw-bold text-dark">Pemberitahuan Sistem</span>
                <span class="badge bg-primary-subtle text-primary border border-primary-subtle text-[10px]">Aktif</span>
              </div>
              <div class="d-flex flex-column gap-2" style="max-height: 240px; overflow-y: auto;">
                <div class="p-2 rounded bg-light border">
                  <p class="fw-semibold text-dark mb-0.5">Catatan Evaluasi Logbook</p>
                  <p class="text-secondary mb-0" style="font-size: 11px;">Dosen pembimbing telah memperbarui catatan asistensi.</p>
                </div>
                <div class="p-2 rounded bg-light border">
                  <p class="fw-semibold text-dark mb-0.5">Verifikasi Berkas Ujian TA</p>
                  <p class="text-secondary mb-0" style="font-size: 11px;">Koordinator TA melakukan validasi persyaratan naskah.</p>
                </div>
                <div class="p-2 rounded bg-light border">
                  <p class="fw-semibold text-dark mb-0.5">Jadwal & Ruangan Ujian</p>
                  <p class="text-secondary mb-0" style="font-size: 11px;">BAAK mengalokasikan ruang sidang dan tautan daring.</p>
                </div>
              </div>
            </div>
          </div>

          <!-- User Chip & Logout Kanan Atas -->
          @auth
            <div class="d-flex align-items-center gap-2 border-start ps-3" style="border-color: #e9ecef !important;">
              <div class="text-end d-none d-sm-block">
                <p class="fw-semibold text-dark mb-0 lh-1" style="font-size: 12.5px;">{{ auth()->user()->name }}</p>
                <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-1.5 py-0.5 mt-1" style="font-size: 9.5px;">
                  {{ strtoupper(str_replace('_', ' ', auth()->user()->role)) }}
                </span>
              </div>
              <div class="rounded-circle bg-dark text-white d-flex align-items-center justify-content-center fw-bold shrink-0 lh-1" style="width: 32px; height: 32px; font-size: 11px;">
                {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
              </div>

              <!-- Tombol Logout Topbar -->
              <form action="{{ route('logout') }}" method="POST" class="mb-0 ms-1">
                @csrf
                <button type="submit" class="btn btn-sm btn-outline-danger d-flex align-items-center justify-content-center p-0" title="Keluar dari akun" style="width: 32px; height: 32px;">
                  <i class="bi bi-box-arrow-right"></i>
                </button>
              </form>
            </div>
          @else
            <a href="{{ route('login') }}" class="btn btn-dark btn-sm">
              Masuk Akun
            </a>
          @endauth

        </div>

      </header>

      <!-- Main Canvas View (Consistent 16px / 24px padding) -->
      <main class="flex-grow-1 overflow-y-auto p-3 p-md-4 w-100 mx-auto" style="max-width: 1240px;">
        @yield('content')
      </main>

    </div>

  </div>

  <!-- Bootstrap 5.3 JS Bundle -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  @stack('scripts')
</body>
</html>

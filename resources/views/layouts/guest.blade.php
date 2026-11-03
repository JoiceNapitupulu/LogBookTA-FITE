<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'Sign In') - Sistem Manajemen & Logbook TA FITE IT Del</title>

  <!-- Google Fonts: Plus Jakarta Sans & Inter with Gilroy fallbacks -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Inter:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">

  <!-- Bootstrap 5.3 CSS & Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

  @vite(['resources/css/app.css', 'resources/js/app.js'])

  <style>
    [x-cloak] { display: none !important; }
    
    p {
      margin-bottom: 0;
    }
    a {
      text-decoration: none;
      color: inherit;
    }

    body {
      font-family: 'Gilroy', 'Plus Jakarta Sans', 'Inter', system-ui, -apple-system, sans-serif;
    }

    .font-mono-code {
      font-family: 'JetBrains Mono', monospace;
    }
  </style>
</head>
<body class="antialiased min-h-screen bg-[#111315] text-[#1E1E24] overflow-x-hidden selection:bg-black selection:text-white">

  @yield('content')

  <!-- Bootstrap 5.3 JS Bundle -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

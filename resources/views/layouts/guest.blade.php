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

  @vite(['resources/css/app.css', 'resources/js/app.js'])

  <style>
    [x-cloak] { display: none !important; }
    
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

</body>
</html>

@extends('layouts.guest')

@section('title', 'Sign In - FITE IT Del')

@section('content')
<div class="relative min-h-screen w-full flex items-center justify-center p-4 sm:p-6 lg:p-8 bg-neutral-900 overflow-hidden"
     style="background-image: linear-gradient(rgba(17, 19, 21, 0.75), rgba(17, 19, 21, 0.85)), url('https://images.unsplash.com/photo-1511497584788-87676104235f?q=80&w=2000&auto=format&fit=crop'); background-size: cover; background-position: center;">

  <!-- Ambient Fog Overlay -->
  <div class="absolute inset-0 bg-radial-at-c from-transparent via-black/40 to-black/80 pointer-events-none"></div>

  <!-- Central Floating Clean Card Container -->
  <div class="relative w-full max-w-4xl bg-white rounded-3xl shadow-2xl border border-white/50 backdrop-blur-xs overflow-hidden z-10 transition-all duration-300">
    <div class="grid grid-cols-1 lg:grid-cols-2">
      
      <!-- ========================================================= -->
      <!-- LEFT COLUMN: AUTHENTICATION FORM (USERNAME & PASSWORD)    -->
      <!-- ========================================================= -->
      <div class="p-8 sm:p-10 lg:p-12 flex flex-col justify-between">
        <div>
          <!-- Logo Institut Teknologi Del Resmi -->
          <div class="flex items-center gap-3 mb-6">
            <img src="https://cis.del.ac.id/assets/d7fb01ed/img/logo.jpg" 
                 alt="Logo Institut Teknologi Del" 
                 class="h-12 w-auto object-contain rounded shadow-xs border border-gray-100">
            <div>
              <span class="text-xs font-bold tracking-wider uppercase text-[#0A0A0B] block leading-tight">Institut Teknologi Del</span>
              <span class="text-[10px] text-[#787774] font-mono-code block">Fakultas Informatika & Teknik Elektro</span>
            </div>
          </div>

          <!-- Headline & Subtitle -->
          <div class="mb-7">
            <h1 class="text-3xl font-extrabold tracking-tight text-[#0A0A0B] leading-tight">
              Sign In
            </h1>
            <p class="text-xs text-[#8A8A8E] font-mono-code mt-1.5">
              Continue to access your dashboard
            </p>
          </div>

          <!-- Credential Form (Username & Password Saja) -->
          <form action="{{ route('login.post') }}" method="POST" class="space-y-4">
            @csrf

            <!-- Username / NIM / Email Field -->
            <div>
              <label class="block text-xs font-bold text-[#111827] mb-1.5 font-sans">Username</label>
              <input type="text" name="username" value="{{ old('username', 'mhs.ta@students.del.ac.id') }}" required autofocus
                     placeholder="Enter your username or email"
                     class="w-full px-4 py-2.5 text-xs rounded-xl border border-[#E5E7EB] bg-white text-[#111827] placeholder-[#9CA3AF] focus:outline-none focus:border-black focus:ring-1 focus:ring-black transition-all">
            </div>

            <!-- Password Field -->
            <div>
              <div class="flex items-center justify-between mb-1.5">
                <label class="block text-xs font-bold text-[#111827] font-sans">Password</label>
                <a href="javascript:void(0)" onclick="alert('Untuk reset kata sandi, silakan hubungi tim Teaching Assistant (TA FITE) atau Admin BAAK IT Del.')" 
                   class="text-xs text-[#6B7280] hover:text-black transition-colors">
                  Forgot Password?
                </a>
              </div>
              <input type="password" name="password" value="password123" required
                     placeholder="Enter your password"
                     class="w-full px-4 py-2.5 text-xs rounded-xl border border-[#E5E7EB] bg-white text-[#111827] placeholder-[#9CA3AF] focus:outline-none focus:border-black focus:ring-1 focus:ring-black transition-all">
            </div>

            <!-- Submit Button (Solid Black Rounded Pill) -->
            <button type="submit" 
                    class="w-full mt-3 py-3 px-4 rounded-full bg-[#0A0A0B] hover:bg-[#2C2C2F] text-white text-xs font-semibold tracking-wide transition-all shadow-md active:scale-[0.99] cursor-pointer">
              Sign In
            </button>
          </form>

          <!-- Footer Link -->
          <div class="text-center mt-6 text-xs text-[#6B7280]">
            Don't have an account? 
            <a href="javascript:void(0)" onclick="alert('Pendaftaran akun mahasiswa dilakukan otomatis oleh Koordinator TA FITE.')" class="font-bold text-[#0A0A0B] underline hover:opacity-80">
              Create an Account
            </a>
          </div>
        </div>

        <!-- Demo Accounts Collapsible Drawer for Testing (7 Roles) -->
        <div x-data="{ openDemo: false }" class="mt-8 pt-4 border-t border-[#F3F4F6]">
          <button @click="openDemo = !openDemo" type="button" class="w-full flex items-center justify-between text-[11px] font-mono-code text-gray-500 hover:text-black py-1">
            <span class="flex items-center gap-1.5 font-bold">
              <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
              ⚡ Quick Demo Login (7 Roles FITE)
            </span>
            <span x-text="openDemo ? '▲ Hide' : '▼ Show'"></span>
          </button>

          <div x-show="openDemo" x-cloak class="mt-2.5 grid grid-cols-2 gap-1.5 text-left text-[11px]">
            <a href="{{ route('demo.login', 'mahasiswa_ta') }}" class="p-2 rounded-lg bg-gray-50 hover:bg-gray-100 border border-gray-200 transition-colors">
              <span class="font-bold text-black block">1. Mahasiswa TA</span>
              <span class="text-gray-500 text-[10px] truncate block">Samuel Siahaan</span>
            </a>
            <a href="{{ route('demo.login', 'mahasiswa_non_ta') }}" class="p-2 rounded-lg bg-gray-50 hover:bg-gray-100 border border-gray-200 transition-colors">
              <span class="font-bold text-black block">2. Mhs Non-TA</span>
              <span class="text-gray-500 text-[10px] truncate block">Yosua Tambunan</span>
            </a>
            <a href="{{ route('demo.login', 'dosen') }}" class="p-2 rounded-lg bg-gray-50 hover:bg-gray-100 border border-gray-200 transition-colors">
              <span class="font-bold text-black block">3. Dosen (Koord)</span>
              <span class="text-gray-500 text-[10px] truncate block">Dr. Johannes H.</span>
            </a>
            <a href="{{ route('demo.login', 'dosen2') }}" class="p-2 rounded-lg bg-gray-50 hover:bg-gray-100 border border-gray-200 transition-colors">
              <span class="font-bold text-black block">4. Dosen 2</span>
              <span class="text-gray-500 text-[10px] truncate block">Marlina V., M.Sc.</span>
            </a>
            <a href="{{ route('demo.login', 'kaprodi') }}" class="p-2 rounded-lg bg-gray-50 hover:bg-gray-100 border border-gray-200 transition-colors">
              <span class="font-bold text-black block">5. Kaprodi</span>
              <span class="text-gray-500 text-[10px] truncate block">Dr. Arnaldo Sinaga</span>
            </a>
            <a href="{{ route('demo.login', 'ta') }}" class="p-2 rounded-lg bg-gray-50 hover:bg-gray-100 border border-gray-200 transition-colors">
              <span class="font-bold text-black block">6. Teaching Asst</span>
              <span class="text-gray-500 text-[10px] truncate block">Grace Panjaitan</span>
            </a>
            <a href="{{ route('demo.login', 'baak') }}" class="col-span-2 p-2 rounded-lg bg-gray-50 hover:bg-gray-100 border border-gray-200 transition-colors text-center">
              <span class="font-bold text-black">7. BAAK Institut Teknologi Del</span>
            </a>
          </div>
        </div>

      </div>

      <!-- ========================================================= -->
      <!-- RIGHT COLUMN: HIGH-CONTRAST HALFTONE ARTWORK              -->
      <!-- ========================================================= -->
      <div class="relative hidden lg:flex items-center justify-center bg-white overflow-hidden select-none border-l border-[#F3F4F6]">
        
        <!-- Generative Halftone Dither Matrix Artwork SVG -->
        <svg class="w-full h-full object-cover" viewBox="0 0 500 650" preserveAspectRatio="xMidYMid slice" xmlns="http://www.w3.org/2000/svg">
          <defs>
            <pattern id="dotPatternDense" width="10" height="10" patternUnits="userSpaceOnUse">
              <circle cx="5" cy="5" r="4.2" fill="#000000"/>
            </pattern>
            <pattern id="dotPatternMedium" width="10" height="10" patternUnits="userSpaceOnUse">
              <circle cx="5" cy="5" r="3.0" fill="#000000"/>
            </pattern>
            <pattern id="dotPatternLight" width="10" height="10" patternUnits="userSpaceOnUse">
              <circle cx="5" cy="5" r="1.8" fill="#000000"/>
            </pattern>
            <pattern id="dotPatternFine" width="10" height="10" patternUnits="userSpaceOnUse">
              <circle cx="5" cy="5" r="0.9" fill="#000000"/>
            </pattern>
          </defs>

          <!-- Crisp White Background Base -->
          <rect width="100%" height="100%" fill="#FFFFFF"/>

          <!-- Organic Contour Layer 1 (Finest Scattered Outer Dots) -->
          <path d="M120 0 C 180 120, 240 220, 160 320 C 100 400, 140 540, 280 650 L 500 650 L 500 0 Z" fill="url(#dotPatternFine)"/>

          <!-- Organic Contour Layer 2 (Light Dots) -->
          <path d="M160 0 C 220 110, 280 200, 200 300 C 140 380, 190 510, 330 650 L 500 650 L 500 0 Z" fill="url(#dotPatternLight)"/>

          <!-- Organic Contour Layer 3 (Medium Density Dots) -->
          <path d="M210 0 C 260 100, 320 180, 240 280 C 180 360, 240 480, 380 650 L 500 650 L 500 0 Z" fill="url(#dotPatternMedium)"/>

          <!-- Organic Contour Layer 4 (Dense Core Halftone Dots) -->
          <path d="M270 0 C 310 90, 370 160, 300 250 C 240 330, 300 450, 430 650 L 500 650 L 500 0 Z" fill="url(#dotPatternDense)"/>

          <!-- Halftone Pixel Matrix Elements scattering along boundary -->
          <g fill="#000000">
            <rect x="135" y="45" width="4" height="4"/>
            <rect x="148" y="70" width="3" height="3"/>
            <rect x="165" y="95" width="5" height="5"/>
            <rect x="180" y="130" width="6" height="6"/>
            <rect x="195" y="165" width="4" height="4"/>
            <rect x="210" y="190" width="7" height="7"/>
            <rect x="185" y="225" width="5" height="5"/>
            <rect x="160" y="260" width="6" height="6"/>
            <rect x="140" y="295" width="4" height="4"/>
            <rect x="125" y="330" width="3" height="3"/>
            <rect x="135" y="365" width="5" height="5"/>
            <rect x="155" y="405" width="6" height="6"/>
            <rect x="175" y="445" width="7" height="7"/>
            <rect x="205" y="490" width="5" height="5"/>
            <rect x="235" y="530" width="8" height="8"/>
            <rect x="270" y="570" width="6" height="6"/>
            <rect x="305" y="610" width="4" height="4"/>

            <rect x="150" y="240" width="2" height="2"/>
            <rect x="130" y="270" width="3" height="3"/>
            <rect x="115" y="310" width="2" height="2"/>
            <rect x="110" y="350" width="3" height="3"/>
            <rect x="120" y="380" width="4" height="4"/>
            <rect x="145" y="420" width="3" height="3"/>
            <rect x="160" y="460" width="4" height="4"/>
            <rect x="190" y="510" width="3" height="3"/>
            <rect x="220" y="550" width="5" height="5"/>
            <rect x="250" y="590" width="4" height="4"/>
            <rect x="285" y="630" width="3" height="3"/>
          </g>

          <!-- Top-right corner dense black cluster -->
          <path d="M360 0 C 390 60, 440 100, 410 180 C 380 250, 430 350, 500 480 L 500 0 Z" fill="#000000" opacity="0.9"/>
        </svg>

        <!-- Subtle Gradient Vignette -->
        <div class="absolute inset-0 bg-gradient-to-l from-transparent via-transparent to-white/40 pointer-events-none"></div>
      </div>

    </div>
  </div>

</div>
@endsection

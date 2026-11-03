<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureIsMahasiswaTa
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user()) {
            return redirect()->route('login');
        }

        // Jika user adalah Mahasiswa Non-TA, tolak akses dan arahkan ke dashboard
        if ($request->user()->isMahasiswaNonTa()) {
            return redirect()->route('dashboard')
                ->with('error', 'Akses ditolak. Fitur ini hanya tersedia untuk Mahasiswa Tugas Akhir.');
        }

        return $next($request);
    }
}

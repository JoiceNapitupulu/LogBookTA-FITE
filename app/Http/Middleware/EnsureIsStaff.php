<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureIsStaff
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

        // Tolak semua mahasiswa (baik TA maupun Non-TA) dari area staf/administratif
        if ($request->user()->isMahasiswa()) {
            abort(403, 'Akses ditolak: Area ini khusus untuk staf pengajar dan administrator FITE.');
        }

        return $next($request);
    }
}

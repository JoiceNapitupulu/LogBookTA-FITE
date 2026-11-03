<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (! $request->user()) {
            return redirect()->route('login');
        }

        $userRole = $request->user()->role;

        // Support check for koordinator (role = dosen and is_koordinator_ta)
        if (in_array('koordinator', $roles)) {
            if ($request->user()->isKoordinator()) {
                return $next($request);
            }
        }

        // Support check for kaprodi (role = kaprodi or dosen with is_kaprodi)
        if (in_array('kaprodi', $roles)) {
            if ($request->user()->isKaprodi()) {
                return $next($request);
            }
        }

        if (in_array($userRole, $roles)) {
            return $next($request);
        }

        abort(403, 'Akses ditolak: Anda tidak memiliki hak akses untuk halaman ini.');
    }
}

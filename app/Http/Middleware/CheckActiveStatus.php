<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth; // <--- PERBAIKAN: Gunakan Facade Auth yang benar

class CheckActiveStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Cek jika user sedang login DAN status is_active nya 0 (false)
        if (Auth::check() && Auth::user()->is_active == 0) {

            // 1. Paksa Logout
            Auth::logout();

            // 2. Invalidate Session (Penting untuk keamanan agar sesi benar-benar mati)
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            // 3. Redirect ke halaman login dengan pesan error
            return redirect()->route('login')->with('error', 'Akun Anda sedang menunggu persetujuan Admin.');
        }

        return $next($request);
    }
}

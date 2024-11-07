<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Mengecek apakah pengguna sudah login
        if (Session::get('role') && Session::get('pengguna_id')) {
            return $next($request);
        }

        // Jika belum login, redirect ke halaman login
        return redirect()->route('login')->with('error', 'Kamu harus login terlebih dahulu');
    }
}

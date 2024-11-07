<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $role
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $role)
    {
        // Memeriksa apakah role pengguna yang tersimpan di session sesuai dengan role yang diminta
        if (Session::get('role') !== $role) {
            // Jika tidak sesuai, kembalikan pengguna ke halaman sebelumnya dengan pesan error
            return back()->with('error', 'Anda tidak memiliki akses ke menu ini');
        }

        // Jika role sesuai, lanjutkan request
        return $next($request);
    }
}

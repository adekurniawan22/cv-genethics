<?php

namespace App\Http\Controllers;

use App\Models\Pengguna;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    public function showLogin()
    {
        // Jika pengguna sudah terautentikasi, arahkan berdasarkan peran
        if (Session::has('pengguna_id')) {
            $user = Pengguna::find(Session::get('pengguna_id'));

            if ($user) {
                return $this->redirectBasedOnRole($user->role);
            }
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $customMessages = [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Password wajib diisi.',
        ];

        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ], $customMessages);

        $user = Pengguna::where('email', $request->email)->first();

        if (!$user) {
            return redirect()->back()
                ->withInput($request->only('email', 'password'))
                ->with('error', 'Akun anda tidak ditemukan');
        }

        if (!Hash::check($request->password, $user->password)) {
            return redirect()->back()
                ->withInput($request->only('email', 'password'))
                ->with('error', 'Password anda salah');
        }

        if ($user->status_akun != "aktif") {
            return redirect()->back()
                ->withInput($request->only('email', 'password'))
                ->with('error', 'Akun anda tidak aktif');
        }

        // Set session untuk pengguna dan role
        Session::put('pengguna_id', $user->pengguna_id);
        Session::put('role', $user->role);

        return $this->redirectBasedOnRole($user->role);
    }


    public function logout()
    {
        // Hapus sesi yang dimasukkan saat login saja
        Session::forget('pengguna_id');
        Session::forget('role');
        return redirect()->route('login')->with('success', 'Anda berhasil logout');
    }

    // Arahkan berdasarkan role pengguna
    protected function redirectBasedOnRole($role)
    {
        if ($role == 'owner') {
            return redirect()->route(session()->get('role') . '.dashboard')->with('success', 'Selamat datang di menu Owner');
        } elseif ($role == 'manajer') {
            return redirect()->route('manajer.penjadwalan.index')->with('success', 'Selamat datang di menu Manajer');
        } elseif ($role == 'admin') {
            return redirect()->route('admin.pesanan.index')->with('success', 'Selamat datang di menu Admin');
        } elseif ($role == 'super') {
            return redirect()->route('super.dashboard')->with('success', 'Selamat datang di menu Super Admin');
        } else {
            return redirect()->back()->withErrors(['error' => 'Role tidak dikenali']);
        }
    }
}

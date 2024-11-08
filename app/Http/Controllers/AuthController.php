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
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = Pengguna::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return redirect()->back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'Email atau password salah']);
        }

        if (!$user->status_akun == "aktif") {
            return redirect()->back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'Akun tidak aktif']);
        }

        // Set session untuk pengguna dan role
        Session::put('pengguna_id', $user->pengguna_id);
        Session::put('role', $user->role);

        return $this->redirectBasedOnRole($user->role);
    }

    public function logout()
    {
        Session::flush();
        return redirect()->route('login');
    }

    // Arahkan berdasarkan role pengguna
    protected function redirectBasedOnRole($role)
    {
        if ($role == 'owner') {
            return redirect()->route(session()->get('role') . '.dashboard')->with('success', 'Selamat datang di menu Owner');
        } elseif ($role == 'manajer') {
            return redirect()->route('manajer.dashboard')->with('success', 'Selamat datang di menu Manajer');
        } elseif ($role == 'admin') {
            return redirect()->route('admin.dashboard')->with('success', 'Selamat datang di menu Admin');
        } else {
            return redirect()->back()->withErrors(['error' => 'Role tidak dikenali']);
        }
    }
}

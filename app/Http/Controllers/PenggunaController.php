<?php

namespace App\Http\Controllers;

use App\Models\Pengguna;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PenggunaController extends Controller
{
    // Constants for view titles
    private const TITLE_INDEX = 'Daftar Pengguna';
    private const TITLE_CREATE = 'Tambah Pengguna';
    private const TITLE_EDIT = 'Edit Pengguna';

    // Constructor to apply middleware for owner role (if necessary)
    public function __construct()
    {
        // Contoh jika middleware dibutuhkan untuk role "owner"

    }

    // Index method (show all users)
    public function index()
    {
        $data = Pengguna::all();
        return view('menu.pengguna.index', [
            'data' => $data,
            'title' => self::TITLE_INDEX
        ]);
    }

    // Create method (show form for creating new user)
    public function create()
    {
        return view('menu.pengguna.create', [
            'title' => self::TITLE_CREATE
        ]);
    }

    // Store method (store new user to the database)
    public function store(Request $request)
    {
        $this->validateStoreOrUpdate($request);

        Pengguna::create([
            'role' => $request->role,
            'nama' => $request->nama,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'foto' => 'profil.png', // You can adjust the default photo logic if needed
        ]);

        return redirect()->route(session()->get('role') . '.pengguna.index')->with('success', 'Pengguna berhasil ditambahkan.');
    }

    // Edit method (show form for editing user data)
    public function edit($id)
    {
        $pengguna = Pengguna::findOrFail($id);

        return view('menu.pengguna.edit', [
            'pengguna' => $pengguna,
            'title' => self::TITLE_EDIT
        ]);
    }

    // Update method (update user data in the database)
    public function update(Request $request, $id)
    {
        $this->validateStoreOrUpdate($request, $id);

        $user = Pengguna::findOrFail($id);

        // Set nilai baru dari request
        $user->role = $request->role;
        $user->nama = $request->nama;
        $user->email = $request->email;
        $user->status_akun = $request->status_akun;
        $user->password = $request->password ? Hash::make($request->password) : $user->password;

        // Cek apakah ada perubahan
        if ($user->isDirty()) {
            $user->save();
            return redirect()->route(session()->get('role') . '.pengguna.index')->with('success', 'Pengguna berhasil diedit.');
        }

        return redirect()->route(session()->get('role') . '.pengguna.index')->with('info', 'Tidak ada perubahan yang dilakukan.');
    }


    // Destroy method (delete user)
    public function destroy($id)
    {
        Pengguna::findOrFail($id)->delete();
        return redirect()->route(session()->get('role') . '.pengguna.index')->with('success', 'Pengguna berhasil dihapus.');
    }

    // Private method for validation (to avoid duplication of logic)
    private function validateStoreOrUpdate(Request $request, $id = null)
    {
        $rules = [
            'role' => 'required',
            'nama' => 'required|string|max:255',
            'email' => 'required|string|max:255|unique:pengguna,email' . ($id ? ",$id,pengguna_id" : ''),
            'password' => 'nullable|string|min:8',
        ];

        $customAttributes = [
            'role' => 'Role',
            'nama' => 'Nama Lengkap',
            'email' => 'Email',
            'password' => 'Password',
        ];

        // Validate input with custom attributes
        return $request->validate($rules, [], $customAttributes);
    }
}

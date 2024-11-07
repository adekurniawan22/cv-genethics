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

    // Index method
    public function index()
    {
        $data = Pengguna::all();
        return view('menu.pengguna.index', [
            'data' => $data,
            'title' => self::TITLE_INDEX
        ]);
    }

    // Create method
    public function create()
    {
        return view('menu.pengguna.create', [
            'title' => self::TITLE_CREATE
        ]);
    }

    // Store method
    public function store(Request $request)
    {
        $this->validateStoreOrUpdate($request);

        Pengguna::create([
            'role' => $request->role,
            'nama' => $request->nama,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'foto' => 'profil.png',
        ]);

        return redirect()->route('owner.pengguna.index')->with('success', 'Pengguna berhasil ditambahkan.');
    }

    // Edit method
    public function edit($id)
    {
        return view('menu.pengguna.edit', [
            'pengguna' => Pengguna::findOrFail($id),
            'title' => self::TITLE_EDIT
        ]);
    }

    // Update method
    public function update(Request $request, $id)
    {
        $this->validateStoreOrUpdate($request, $id);

        $user = Pengguna::findOrFail($id);

        dd($request);
        // die();
        $user->update([
            'role' => $request->role,
            'nama' => $request->nama,
            'email' => $request->email,
            'status_akun' => $request->status_akun,
            'password' => $request->password ? Hash::make($request->password) : $user->password,
        ]);

        return redirect()->route('owner.pengguna.index')->with('success', 'Pengguna berhasil diedit.');
    }

    // Destroy method
    public function destroy($id)
    {
        Pengguna::findOrFail($id)->delete();
        return redirect()->route('owner.pengguna.index')->with('success', 'Pengguna berhasil dihapus.');
    }

    // Private method for validation
    private function validateStoreOrUpdate(Request $request, $id = null)
    {
        $rules = [
            'role' => 'required',
            'nama' => 'required|string|max:255',
            'email' => 'required|string|max:255|unique:pengguna,email' . ($id ? ",$id,pengguna_id" : ''),
            'password' => 'nullable|string|min:8',
        ];

        // Menentukan nama khusus untuk atribut
        $customAttributes = [
            'role' => 'Role',
            'nama' => 'Nama Lengkap',
            'email' => 'Email',
            'password' => 'Password',
        ];

        // Melakukan validasi dengan nama atribut khusus
        $validatedData = $request->validate($rules, [], $customAttributes);

        return $validatedData;
    }
}

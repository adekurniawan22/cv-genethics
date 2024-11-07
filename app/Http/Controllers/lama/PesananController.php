<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use App\Models\Pengguna;
use Illuminate\Http\Request;

class PesananController extends Controller
{
    private const TITLE_INDEX = 'Daftar Pesanan';
    private const TITLE_CREATE = 'Tambah Pesanan';
    private const TITLE_EDIT = 'Edit Pesanan';

    public function index()
    {
        $data = Pesanan::with('pengguna')->get();
        return view('menu.pesanan.index', [
            'data' => $data,
            'title' => self::TITLE_INDEX
        ]);
    }

    public function create()
    {
        $pengguna = Pengguna::all();
        return view('menu.pesanan.create', [
            'pengguna' => $pengguna,
            'title' => self::TITLE_CREATE
        ]);
    }

    public function store(Request $request)
    {
        $this->validateStoreOrUpdate($request);

        Pesanan::create([
            'pengguna_id' => $request->pengguna_id,
            'detail_pesanan' => $request->detail_pesanan,
            'status' => $request->status
        ]);

        return redirect()->route('owner.pesanan.index')
            ->with('success', 'Pesanan berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $pesanan = Pesanan::findOrFail($id);
        $pengguna = Pengguna::all();

        return view('menu.pesanan.edit', [
            'pesanan' => $pesanan,
            'pengguna' => $pengguna,
            'title' => self::TITLE_EDIT
        ]);
    }

    public function update(Request $request, $id)
    {
        $this->validateStoreOrUpdate($request);

        $pesanan = Pesanan::findOrFail($id);
        $pesanan->update([
            'pengguna_id' => $request->pengguna_id,
            'detail_pesanan' => $request->detail_pesanan,
            'status' => $request->status
        ]);

        return redirect()->route('owner.pesanan.index')
            ->with('success', 'Pesanan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        Pesanan::findOrFail($id)->delete();
        return redirect()->route('owner.pesanan.index')
            ->with('success', 'Pesanan berhasil dihapus.');
    }

    private function validateStoreOrUpdate(Request $request)
    {
        $rules = [
            'pengguna_id' => 'required|exists:pengguna,pengguna_id',
            'detail_pesanan' => 'required|string',
            'status' => 'required|in:pending,completed'
        ];

        $customAttributes = [
            'pengguna_id' => 'Pengguna',
            'detail_pesanan' => 'Detail Pesanan',
            'status' => 'Status'
        ];

        return $request->validate($rules, [], $customAttributes);
    }
}

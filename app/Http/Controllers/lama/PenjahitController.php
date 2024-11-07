<?php

namespace App\Http\Controllers;

use App\Models\Penjahit;
use Illuminate\Http\Request;

class PenjahitController extends Controller
{
    private const TITLE_INDEX = 'Daftar Penjahit';
    private const TITLE_CREATE = 'Tambah Penjahit';
    private const TITLE_EDIT = 'Edit Penjahit';

    public function index()
    {
        $data = Penjahit::all();
        return view('menu.penjahit.index', [
            'data' => $data,
            'title' => self::TITLE_INDEX,
        ]);
    }

    public function create()
    {
        return view('menu.penjahit.create', [
            'title' => self::TITLE_CREATE,
        ]);
    }

    public function store(Request $request)
    {
        $this->validateStoreOrUpdate($request);

        Penjahit::create([
            'nama' => $request->nama,
            'kontak' => $request->kontak,
        ]);

        return redirect()->route('owner.penjahit.index')->with('success', 'Penjahit berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $penjahit = Penjahit::findOrFail($id);
        return view('menu.penjahit.edit', [
            'penjahit' => $penjahit,
            'title' => self::TITLE_EDIT,
        ]);
    }

    public function update(Request $request, $id)
    {
        $this->validateStoreOrUpdate($request, $id);

        $penjahit = Penjahit::findOrFail($id);
        $penjahit->update([
            'nama' => $request->nama,
            'kontak' => $request->kontak,
        ]);

        return redirect()->route('owner.penjahit.index')->with('success', 'Penjahit berhasil diperbarui.');
    }

    public function destroy($id)
    {
        Penjahit::findOrFail($id)->delete();
        return redirect()->route('owner.penjahit.index')->with('success', 'Penjahit berhasil dihapus.');
    }

    private function validateStoreOrUpdate(Request $request, $id = null)
    {
        $rules = [
            'nama' => 'required|string|max:255',
            'kontak' => 'required|string|max:255',
        ];

        $customAttributes = [
            'nama' => 'Nama',
            'kontak' => 'Kontak',
        ];

        $validatedData = $request->validate($rules, [], $customAttributes);

        return $validatedData;
    }
}

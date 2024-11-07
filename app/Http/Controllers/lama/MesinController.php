<?php

namespace App\Http\Controllers;

use App\Models\Mesin;
use Illuminate\Http\Request;

class MesinController extends Controller
{
    // Constants for view titles
    private const TITLE_INDEX = 'Daftar Mesin';
    private const TITLE_CREATE = 'Tambah Mesin';
    private const TITLE_EDIT = 'Edit Mesin';

    // Index method
    public function index()
    {
        $data = Mesin::all();
        return view('menu.mesin.index', [
            'data' => $data,
            'title' => self::TITLE_INDEX
        ]);
    }

    // Create method
    public function create()
    {
        return view('menu.mesin.create', [
            'title' => self::TITLE_CREATE
        ]);
    }

    // Store method
    public function store(Request $request)
    {
        $this->validateStoreOrUpdate($request);

        Mesin::create([
            'nama_mesin' => $request->nama_mesin,
            'status' => $request->status,
        ]);

        return redirect()->route('owner.mesin.index')->with('success', 'Mesin berhasil ditambahkan.');
    }

    // Edit method
    public function edit($id)
    {
        return view('menu.mesin.edit', [
            'mesin' => Mesin::findOrFail($id),
            'title' => self::TITLE_EDIT
        ]);
    }

    // Update method
    public function update(Request $request, $id)
    {
        $this->validateStoreOrUpdate($request, $id);

        $mesin = Mesin::findOrFail($id);
        $mesin->update([
            'nama_mesin' => $request->nama_mesin,
            'status' => $request->status,
        ]);

        return redirect()->route('owner.mesin.index')->with('success', 'Mesin berhasil diedit.');
    }

    // Destroy method
    public function destroy($id)
    {
        Mesin::findOrFail($id)->delete();
        return redirect()->route('owner.mesin.index')->with('success', 'Mesin berhasil dihapus.');
    }

    // Private method for validation
    private function validateStoreOrUpdate(Request $request, $id = null)
    {
        $rules = [
            'nama_mesin' => 'required|string|max:255',
            'status' => 'required|integer|in:0,1',
        ];

        // Menentukan nama khusus untuk atribut
        $customAttributes = [
            'nama_mesin' => 'Nama Mesin',
            'status' => 'Status',
        ];

        // Melakukan validasi dengan nama atribut khusus
        $validatedData = $request->validate($rules, [], $customAttributes);

        return $validatedData;
    }
}

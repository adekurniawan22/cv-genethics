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

    // Index method (show all machines)
    public function index()
    {
        $data = Mesin::all();
        return view('menu.mesin.index', [
            'data' => $data,
            'title' => self::TITLE_INDEX
        ]);
    }

    // Create method (show form for creating a new machine)
    public function create()
    {
        return view('menu.mesin.create', [
            'title' => self::TITLE_CREATE
        ]);
    }

    // Store method (store new machine to the database)
    public function store(Request $request)
    {
        $this->validateStoreOrUpdate($request);

        Mesin::create([
            'nama_mesin' => $request->nama_mesin,
            'status' => $request->status,
            'keterangan_mesin' => $request->keterangan_mesin,
            'kapasitas_per_hari' => $request->kapasitas_per_hari,
        ]);

        return redirect()->route(session()->get('role') . '.mesin.index')->with('success', 'Mesin berhasil ditambahkan.');
    }

    // Edit method (show form for editing machine data)
    public function edit($id)
    {
        $mesin = Mesin::findOrFail($id);
        return view('menu.mesin.edit', [
            'mesin' => $mesin,
            'title' => self::TITLE_EDIT
        ]);
    }

    // Update method (update machine data in the database)
    public function update(Request $request, $id)
    {
        $this->validateStoreOrUpdate($request, $id);
        $mesin = Mesin::findOrFail($id);

        $mesin->update([
            'nama_mesin' => $request->nama_mesin,
            'status' => $request->status,
            'keterangan_mesin' => $request->keterangan_mesin,
            'kapasitas_per_hari' => $request->kapasitas_per_hari,
        ]);

        return redirect()->route(session()->get('role') . '.mesin.index')->with('success', 'Mesin berhasil diedit.');
    }

    // Destroy method (delete machine)
    public function destroy($id)
    {
        Mesin::findOrFail($id)->delete();
        return redirect()->route(session()->get('role') . '.mesin.index')->with('success', 'Mesin berhasil dihapus.');
    }

    // Private method for validation
    private function validateStoreOrUpdate(Request $request, $id = null)
    {
        $rules = [
            'nama_mesin' => 'required|string|max:100',
            'status' => 'required|in:aktif,tidak aktif',
            'keterangan_mesin' => 'nullable|string|max:255',
            'kapasitas_per_hari' => 'nullable|integer',
        ];

        $customAttributes = [
            'nama_mesin' => 'Nama Mesin',
            'status' => 'Status',
            'keterangan_mesin' => 'Keterangan Mesin',
            'kapasitas_per_hari' => 'Kapasitas Per Hari',
        ];

        return $request->validate($rules, [], $customAttributes);
    }
}

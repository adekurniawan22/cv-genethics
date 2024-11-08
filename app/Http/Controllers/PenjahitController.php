<?php

namespace App\Http\Controllers;

use App\Models\Penjahit;
use Illuminate\Http\Request;

class PenjahitController extends Controller
{
    // Constants for view titles
    private const TITLE_INDEX = 'Daftar Penjahit';
    private const TITLE_CREATE = 'Tambah Penjahit';
    private const TITLE_EDIT = 'Edit Penjahit';

    // Index method (show all penjahit)
    public function index()
    {
        $data = Penjahit::all();
        return view('menu.penjahit.index', [
            'data' => $data,
            'title' => self::TITLE_INDEX
        ]);
    }

    // Create method (show form for creating new penjahit)
    public function create()
    {
        return view('menu.penjahit.create', [
            'title' => self::TITLE_CREATE
        ]);
    }

    // Store method (store new penjahit to the database)
    public function store(Request $request)
    {
        $this->validateStoreOrUpdate($request);

        Penjahit::create([
            'nama_penjahit' => $request->nama_penjahit,
            'alamat' => $request->alamat,
            'kontak' => $request->kontak,
        ]);

        return redirect()->route(session()->get('role') . '.penjahit.index')->with('success', 'Penjahit berhasil ditambahkan.');
    }

    // Edit method (show form for editing penjahit data)
    public function edit($id)
    {
        $penjahit = Penjahit::findOrFail($id);

        return view('menu.penjahit.edit', [
            'penjahit' => $penjahit,
            'title' => self::TITLE_EDIT
        ]);
    }

    // Update method (update penjahit data in the database)
    public function update(Request $request, $id)
    {
        $this->validateStoreOrUpdate($request, $id);

        $penjahit = Penjahit::findOrFail($id);

        // Set nilai baru dari request
        $penjahit->nama_penjahit = $request->nama_penjahit;
        $penjahit->alamat = $request->alamat;
        $penjahit->kontak = $request->kontak;

        // Cek apakah ada perubahan
        if ($penjahit->isDirty()) {
            $penjahit->save();
            return redirect()->route(session()->get('role') . '.penjahit.index')->with('success', 'Penjahit berhasil diedit.');
        }

        return redirect()->route(session()->get('role') . '.penjahit.index')->with('info', 'Tidak ada perubahan yang dilakukan.');
    }


    // Destroy method (delete penjahit)
    public function destroy($id)
    {
        Penjahit::findOrFail($id)->delete();
        return redirect()->route(session()->get('role') . '.penjahit.index')->with('success', 'Penjahit berhasil dihapus.');
    }

    // Private method for validation (to avoid duplication of logic)
    private function validateStoreOrUpdate(Request $request, $id = null)
    {
        $rules = [
            'nama_penjahit' => 'required|string|max:100',
            'alamat' => 'nullable|string|max:255',
            'kontak' => 'nullable|string|max:50',
        ];

        $customAttributes = [
            'nama_penjahit' => 'Nama Penjahit',
            'alamat' => 'Alamat',
            'kontak' => 'Kontak',
        ];

        // Validate input with custom attributes
        return $request->validate($rules, [], $customAttributes);
    }
}

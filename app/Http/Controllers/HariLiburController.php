<?php

namespace App\Http\Controllers;

use App\Models\HariLibur;
use Illuminate\Http\Request;

class HariLiburController extends Controller
{
    // Constants for view titles
    private const TITLE_INDEX = 'Daftar Hari Libur';
    private const TITLE_CREATE = 'Tambah Hari Libur';
    private const TITLE_EDIT = 'Edit Hari Libur';

    public function index()
    {
        $data = HariLibur::all();
        return view('menu.hari_libur.index', [
            'data' => $data,
            'title' => self::TITLE_INDEX
        ]);
    }

    public function create()
    {
        return view('menu.hari_libur.create', [
            'title' => self::TITLE_CREATE
        ]);
    }

    public function store(Request $request)
    {
        $this->validateStoreOrUpdate($request);

        HariLibur::create([
            'tanggal' => $request->tanggal,
            'keterangan' => $request->keterangan,
        ]);

        return redirect()->route(session()->get('role') . '.hari_libur.index')->with('success', 'Hari libur berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $hariLibur = HariLibur::findOrFail($id);

        return view('menu.hari_libur.edit', [
            'hariLibur' => $hariLibur,
            'title' => self::TITLE_EDIT
        ]);
    }

    public function update(Request $request, $id)
    {
        $this->validateStoreOrUpdate($request, $id);

        $hariLibur = HariLibur::findOrFail($id);

        // Set nilai baru dari request
        $hariLibur->tanggal = $request->tanggal;
        $hariLibur->keterangan = $request->keterangan;

        // Cek apakah ada perubahan
        if ($hariLibur->isDirty()) {
            $hariLibur->save();
            return redirect()->route(session()->get('role') . '.hari_libur.index')->with('success', 'Hari libur berhasil diedit.');
        }

        return redirect()->route(session()->get('role') . '.hari_libur.index')->with('info', 'Tidak ada perubahan yang dilakukan.');
    }

    public function destroy($id)
    {
        HariLibur::findOrFail($id)->delete();
        return redirect()->route(session()->get('role') . '.hari_libur.index')->with('success', 'Hari libur berhasil dihapus.');
    }

    private function validateStoreOrUpdate(Request $request, $id = null)
    {
        $rules = [
            'tanggal' => 'required|date',
            'keterangan' => 'required|string|max:255',
        ];

        $customAttributes = [
            'tanggal' => 'Tanggal',
            'keterangan' => 'Keterangan',
        ];

        return $request->validate($rules, [], $customAttributes);
    }
}

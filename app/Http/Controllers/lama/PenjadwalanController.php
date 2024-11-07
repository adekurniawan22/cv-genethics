<?php

namespace App\Http\Controllers;

use App\Models\Penjadwalan;
use App\Models\Pengguna;
use Illuminate\Http\Request;

class PenjadwalanController extends Controller
{
    // Constants for view titles
    private const TITLE_INDEX = 'Daftar Penjadwalan';
    private const TITLE_CREATE = 'Tambah Penjadwalan';
    private const TITLE_EDIT = 'Edit Penjadwalan';

    public function index()
    {
        $penjadwalans = Penjadwalan::with('manajer')->get();
        return view('menu.penjadwalan.index', [
            'penjadwalans' => $penjadwalans,
            'title' => self::TITLE_INDEX,
            'jumlahBulanIni' => Penjadwalan::jumlahBulanIni()
        ]);
    }

    public function create()
    {
        $managers = Pengguna::where('role', 'manajer')->get();
        return view('menu.penjadwalan.create', [
            'managers' => $managers,
            'title' => self::TITLE_CREATE,
        ]);
    }

    public function store(Request $request)
    {
        $validatedData = $this->validateStoreOrUpdate($request);

        Penjadwalan::create($validatedData);

        return redirect()->route('owner.penjadwalan.index')->with('success', 'Penjadwalan berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $penjadwalan = Penjadwalan::findOrFail($id);
        $managers = Pengguna::where('role', 'manajer')->get();
        return view('menu.penjadwalan.edit', [
            'penjadwalan' => $penjadwalan,
            'managers' => $managers,
            'title' => self::TITLE_EDIT,
        ]);
    }

    public function update(Request $request, $id)
    {
        $validatedData = $this->validateStoreOrUpdate($request, $id);

        $penjadwalan = Penjadwalan::findOrFail($id);
        $penjadwalan->update($validatedData);

        return redirect()->route('owner.penjadwalan.index')->with('success', 'Penjadwalan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        Penjadwalan::findOrFail($id)->delete();
        return redirect()->route('owner.penjadwalan.index')->with('success', 'Penjadwalan berhasil dihapus.');
    }

    private function validateStoreOrUpdate(Request $request, $id = null)
    {
        $rules = [
            'manajer_id' => 'nullable|exists:pengguna,pengguna_id',
            'detail_penjadwalan' => 'required|string',
            'tanggal' => 'required|date',
        ];

        $customAttributes = [
            'manajer_id' => 'Manajer',
            'detail_penjadwalan' => 'Detail Penjadwalan',
            'tanggal' => 'Tanggal',
        ];

        return $request->validate($rules, [], $customAttributes);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use Illuminate\Http\Request;

class ProdukController extends Controller
{
    // Constants for view titles
    private const TITLE_INDEX = 'Daftar Produk';
    private const TITLE_CREATE = 'Tambah Produk';
    private const TITLE_EDIT = 'Edit Produk';

    // Index method (show all products)
    public function index()
    {
        $data = Produk::all();
        return view('menu.produk.index', [
            'data' => $data,
            'title' => self::TITLE_INDEX
        ]);
    }

    // Create method (show form for creating new product)
    public function create()
    {
        return view('menu.produk.create', [
            'title' => self::TITLE_CREATE
        ]);
    }

    // Store method (store new product to the database)
    public function store(Request $request)
    {
        $this->validateStoreOrUpdate($request);

        Produk::create([
            'nama_produk' => $request->nama_produk,
            'keterangan_produk' => $request->keterangan_produk,
            'harga' => $request->harga,
        ]);

        return redirect()->route('owner.produk.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    // Edit method (show form for editing product data)
    public function edit($id)
    {
        $produk = Produk::findOrFail($id);

        return view('menu.produk.edit', [
            'produk' => $produk,
            'title' => self::TITLE_EDIT
        ]);
    }

    // Update method (update product data in the database)
    public function update(Request $request, $id)
    {
        $this->validateStoreOrUpdate($request, $id);

        $produk = Produk::findOrFail($id);

        // Set nilai baru dari request
        $produk->nama_produk = $request->nama_produk;
        $produk->keterangan_produk = $request->keterangan_produk;
        $produk->harga = $request->harga;

        // Cek apakah ada perubahan
        if ($produk->isDirty()) {
            $produk->save();
            return redirect()->route('owner.produk.index')->with('success', 'Produk berhasil diedit.');
        }

        return redirect()->route('owner.produk.index')->with('info', 'Tidak ada perubahan yang dilakukan.');
    }

    // Destroy method (delete product)
    public function destroy($id)
    {
        Produk::findOrFail($id)->delete();
        return redirect()->route('owner.produk.index')->with('success', 'Produk berhasil dihapus.');
    }

    // Private method for validation (to avoid duplication of logic)
    private function validateStoreOrUpdate(Request $request, $id = null)
    {
        $rules = [
            'nama_produk' => 'required|string|max:100',
            'keterangan_produk' => 'nullable|string|max:255',
            'harga' => 'required|integer',
        ];

        $customAttributes = [
            'nama_produk' => 'Nama Produk',
            'keterangan_produk' => 'Keterangan Produk',
            'harga' => 'Harga',
        ];

        // Validate input with custom attributes
        return $request->validate($rules, [], $customAttributes);
    }
}

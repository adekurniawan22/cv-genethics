<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use App\Models\Pengguna;
use App\Models\Produk;
use App\Models\PesananDetail;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;

class PesananController extends Controller
{
    // Constants for view titles
    private const TITLE_INDEX = 'Daftar Pesanan';
    private const TITLE_CREATE = 'Tambah Pesanan';
    private const TITLE_EDIT = 'Edit Pesanan';

    // Constructor to apply middleware for owner role (if necessary)
    public function __construct()
    {
        // Contoh jika middleware dibutuhkan untuk role "owner"

    }

    // Index method (show all users)
    public function index()
    {
        $data = Pesanan::all();
        return view('menu.pesanan.index', [
            'data' => $data,
            'title' => self::TITLE_INDEX
        ]);
    }

    // Create method (show form for creating new user)
    public function create()
    {
        $users = Pengguna::all();
        $products = Produk::all();

        return view('menu.pesanan.create', [
            'title' => self::TITLE_CREATE,
            'users' => $users,
            'products' => $products
        ]);
    }

    // Store method (store new user to the database)
    public function store(Request $request)
    {
        // Validasi data utama pesanan
        $request->validate([
            'tanggalPengiriman' => 'nullable|date',
            'status' => 'required|string|in:pending,selesai',
            'channel' => 'required|string|in:Online,Offline',
            'tanggalPesanan' => 'required|date',
            'produkData' => 'required|array|min:1',
            'produkData.*.produk_id' => 'required|exists:produk,produk_id',
            'produkData.*.jumlah' => 'required|integer|min:1',
        ]);

        // Simpan data utama pesanan
        $pesanan = Pesanan::create([
            'tanggal_pengiriman' => $request->input('tanggalPengiriman') ?? null,
            'status' => $request->input('status'),
            'channel' => $request->input('channel'),
            'tanggal_pesanan' => $request->input('tanggalPesanan'),
            'dibuat_oleh' => session('pengguna_id')
        ]);

        // Simpan setiap item dari produkData ke tabel PesananDetail melalui relasi
        $pesanan->pesananDetails()->createMany(
            collect($request->input('produkData'))->map(function ($item) {
                return [
                    'produk_id' => $item['produk_id'],
                    'jumlah' => $item['jumlah']
                ];
            })->toArray()
        );

        session()->flash('success', 'Pesanan berhasil ditambahkan.');
        return response()->json(['success' => true]);
    }

    public function detail(Pesanan $pesanan)
    {
        $pesananDetail = PesananDetail::with(['produk' => function ($query) {
            $query->select('produk_id', 'nama_produk', 'harga');
        }, 'pesanan' => function ($query) {
            $query->with('pengguna:pengguna_id,nama');
        }])
            ->where('pesanan_id', $pesanan->pesanan_id)
            ->get();
        return response()->json($pesananDetail);
    }

    // Edit method (show form for editing user data)
    // public function edit($id)
    // {
    //     $pengguna = Pesanan::findOrFail($id);

    //     return view('menu.pesanan.edit', [
    //         'pengguna' => $pengguna,
    //         'title' => self::TITLE_EDIT
    //     ]);
    // }

    // // Update method (update user data in the database)
    // public function update(Request $request, $id)
    // {
    //     $this->validateStoreOrUpdate($request, $id);

    //     $user = Pesanan::findOrFail($id);

    //     // Set nilai baru dari request
    //     $user->role = $request->role;
    //     $user->nama = $request->nama;
    //     $user->email = $request->email;
    //     $user->status_akun = $request->status_akun;

    //     // Cek apakah ada perubahan
    //     if ($user->isDirty()) {
    //         $user->save();
    //         return redirect()->route(session()->get('role') . '.pesanan.index')->with('success', 'Pesanan berhasil diedit.');
    //     }

    //     return redirect()->route(session()->get('role') . '.pesanan.index')->with('info', 'Tidak ada perubahan yang dilakukan.');
    // }


    // Destroy method (delete user)
    public function destroy($id)
    {
        Pesanan::findOrFail($id)->delete();
        return redirect()->route(session()->get('role') . '.pesanan.index')->with('success', 'Pesanan berhasil dihapus.');
    }
}

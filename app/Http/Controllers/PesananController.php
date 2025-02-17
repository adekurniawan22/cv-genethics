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

    public function listProduksi()
    {
        // Mengambil pesanan dengan status "selesai"
        $data = Pesanan::where('status', 'proses')->get();

        return view('menu.list_produksi.index', [
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
            'tanggalPengiriman' => 'required|date',
            'nama_pemesan' => 'required|string',
            'status' => 'required|string|in:proses,selesai',
            'channel' => 'required|string',
            'tanggalPesanan' => 'required|date',
            'produkData' => 'required|array|min:1',
            'produkData.*.produk_id' => 'required|exists:produk,produk_id',
            'produkData.*.jumlah' => 'required|integer|min:1',
        ]);

        $lastPesanan = Pesanan::latest('pesanan_id')->first();
        $nextPesananId = $lastPesanan ? $lastPesanan->pesanan_id + 1 : 1;

        $formattedTanggalPengiriman = date('dmY', strtotime($request->input('tanggalPesanan')));

        // Buat kode pesanan
        $kodePesanan = $request->input('channel') . '-' . $formattedTanggalPengiriman . '-' . $nextPesananId;

        // Simpan data utama pesanan
        $pesanan = Pesanan::create([
            'nama_pemesan' => $request->input('nama_pemesan'),
            'kode_pesanan' => $kodePesanan,
            'tanggal_pengiriman' => $request->input('tanggalPengiriman'),
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
        return response()->json(['success' => true, 'kode_pesanan' => $kodePesanan]);
    }


    public function edit($id)
    {
        $pesanan = Pesanan::with('pesananDetails')->findOrFail($id);
        $products = Produk::all();

        return view('menu.pesanan.edit', [
            'pesanan' => $pesanan,
            'title' => self::TITLE_EDIT,
            'products' => $products,
        ]);
    }

    public function update(Request $request, $id)
    {
        // Validasi data utama pesanan
        $request->validate([
            'tanggalPengiriman' => 'nullable|date',
            'status' => 'required|string|in:proses,selesai',
            'nama_pemesan' => 'required|string',
            'channel' => 'required|string',
            'tanggalPesanan' => 'required|date',
            'produkData' => 'required|array|min:1',
            'produkData.*.produk_id' => 'required|exists:produk,produk_id',
            'produkData.*.jumlah' => 'required|integer|min:1',
        ]);

        // Temukan pesanan berdasarkan ID
        $pesanan = Pesanan::findOrFail($id);

        // Update data utama pesanan
        $pesanan->nama_pemesan = $request->input('nama_pemesan');
        $pesanan->tanggal_pengiriman = $request->input('tanggalPengiriman');
        $pesanan->status = $request->input('status');
        $pesanan->channel = $request->input('channel');
        $pesanan->tanggal_pesanan = $request->input('tanggalPesanan');
        $pesanan->save();

        // Hapus detail pesanan yang ada
        $pesanan->pesananDetails()->delete();

        // Simpan setiap item dari produkData ke tabel PesananDetail melalui relasi
        $pesanan->pesananDetails()->createMany(
            collect($request->input('produkData'))->map(function ($item) {
                return [
                    'produk_id' => $item['produk_id'],
                    'jumlah' => $item['jumlah']
                ];
            })->toArray()
        );

        session()->flash('success', 'Pesanan berhasil diperbarui.');
        return response()->json(['success' => true]);
    }

    public function tandaiPesananSelesai($id)
    {
        try {
            $pesanan = Pesanan::findOrFail($id);
            $pesanan->status = 'selesai';
            $pesanan->save();

            return response()->json([
                'success' => true,
                'message' => 'Pesanan berhasil ditandai selesai'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengubah status pesanan'
            ], 500);
        }
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

    // Destroy method (delete user)
    public function destroy($id)
    {
        Pesanan::findOrFail($id)->delete();
        return redirect()->route(session()->get('role') . '.pesanan.index')->with('success', 'Pesanan berhasil dihapus.');
    }

    public function searchNamaPemesan(Request $request)
    {
        $query = $request->input('search');

        if (!$query) {
            return response()->json(['pemesan' => []]);
        }

        $pemesanList = Pesanan::where('nama_pemesan', 'like', '%' . $query . '%')
            ->pluck('nama_pemesan')
            ->toArray();

        return response()->json(['pemesan' => $pemesanList]);
    }
}

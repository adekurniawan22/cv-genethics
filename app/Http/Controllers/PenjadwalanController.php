<?php

namespace App\Http\Controllers;

use App\Models\Penjadwalan;
use App\Models\Pesanan;
use Illuminate\Http\Request;

class PenjadwalanController extends Controller
{
    // Constants for view titles
    private const TITLE_INDEX = 'Daftar Penjadwalan';
    private const TITLE_CREATE = 'Tambah Penjadwalan';
    private const TITLE_EDIT = 'Edit Penjadwalan';

    // Constructor to apply middleware for owner role (if necessary)
    public function __construct()
    {
        // Contoh jika middleware dibutuhkan untuk role "owner"
        $this->middleware('role:owner')->except(['index']);
    }

    // Index method (show all schedules)
    public function index()
    {
        $data = Penjadwalan::with('pesanan')->get();
        return view('menu.penjadwalan.index', [
            'data' => $data,
            'title' => self::TITLE_INDEX
        ]);
    }

    // Create method (show form for creating new schedule)
    public function create()
    {
        $orders = Pesanan::all();
        return view('menu.penjadwalan.create', [
            'orders' => $orders,
            'title' => self::TITLE_CREATE
        ]);
    }

    // Store method (store new schedule to the database)
    public function store(Request $request)
    {
        $this->validateStoreOrUpdate($request);

        Penjadwalan::create([
            'pesanan_id' => $request->pesanan_id,
            'urutan_prioritas' => $request->urutan_prioritas,
            'estimasi_selesai' => $request->estimasi_selesai,
        ]);

        return redirect()->route('owner.penjadwalan.index')->with('success', 'Penjadwalan berhasil ditambahkan.');
    }

    // Edit method (show form for editing schedule data)
    public function edit($id)
    {
        $schedule = Penjadwalan::findOrFail($id);
        $orders = Pesanan::all();

        return view('menu.penjadwalan.edit', [
            'schedule' => $schedule,
            'orders' => $orders,
            'title' => self::TITLE_EDIT
        ]);
    }

    // Update method (update schedule data in the database)
    public function update(Request $request, $id)
    {
        $this->validateStoreOrUpdate($request, $id);

        $schedule = Penjadwalan::findOrFail($id);

        // Set nilai baru dari request
        $schedule->pesanan_id = $request->pesanan_id;
        $schedule->urutan_prioritas = $request->urutan_prioritas;
        $schedule->estimasi_selesai = $request->estimasi_selesai;

        // Cek apakah ada perubahan
        if ($schedule->isDirty()) {
            $schedule->save();
            return redirect()->route('owner.penjadwalan.index')->with('success', 'Penjadwalan berhasil diedit.');
        }

        return redirect()->route('owner.penjadwalan.index')->with('info', 'Tidak ada perubahan yang dilakukan.');
    }


    // Destroy method (delete schedule)
    public function destroy($id)
    {
        Penjadwalan::findOrFail($id)->delete();
        return redirect()->route('owner.penjadwalan.index')->with('success', 'Penjadwalan berhasil dihapus.');
    }

    // Private method for validation (to avoid duplication of logic)
    private function validateStoreOrUpdate(Request $request, $id = null)
    {
        $rules = [
            'pesanan_id' => 'required|exists:pesanan,pesanan_id',
            'urutan_prioritas' => 'required|integer|min:1',
            'estimasi_selesai' => 'required|date',
        ];

        $customAttributes = [
            'pesanan_id' => 'Pesanan',
            'urutan_prioritas' => 'Urutan Prioritas',
            'estimasi_selesai' => 'Estimasi Selesai',
        ];

        // Validate input with custom attributes
        return $request->validate($rules, [], $customAttributes);
    }
}

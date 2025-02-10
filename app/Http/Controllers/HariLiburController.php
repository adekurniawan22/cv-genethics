<?php

namespace App\Http\Controllers;

use App\Models\HariLibur;
use Illuminate\Http\Request;
use Carbon\Carbon;

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

    public function getHariLibur(Request $request)
    {
        if ($request->has('penjadwalan') && $request->boolean('penjadwalan')) {
            $holidays = HariLibur::select('tanggal', 'keterangan')
                ->get()
                ->mapWithKeys(function ($item) {
                    return [Carbon::parse($item->tanggal)->format('Y-m-d') => $item->keterangan];
                });

            return response()->json($holidays);
        }

        $hariLibur = HariLibur::all()->map(function ($item) {
            return [
                'id' => $item->hari_libur_id,
                'title' => $item->keterangan,
                'start' => $item->tanggal,
                'allDay' => true
            ];
        });

        return response()->json($hariLibur);
    }

    public function storeHariLibur(Request $request)
    {
        try {
            $hariLibur = HariLibur::create([
                'tanggal' => $request->tanggal,
                'keterangan' => $request->keterangan
            ]);

            return response()->json([
                'success' => true,
                'id' => $hariLibur->hari_libur_id,
                'title' => $hariLibur->keterangan, // Untuk kompatibilitas dengan FullCalendar
                'start' => $hariLibur->tanggal // Untuk kompatibilitas dengan FullCalendar
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambah hari libur: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateHariLibur(Request $request, $id)
    {
        $hariLibur = HariLibur::where('hari_libur_id', $id)->firstOrFail();
        $hariLibur->update([
            'tanggal' => $request->tanggal,
            'keterangan' => $request->keterangan
        ]);

        return response()->json(['success' => true]);
    }

    public function deleteHariLibur($id)
    {
        $hariLibur = HariLibur::where('hari_libur_id', $id)->firstOrFail();
        $hariLibur->delete();

        return response()->json(['success' => true]);
    }
}

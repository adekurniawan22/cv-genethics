<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use App\Models\HariLibur;
use App\Models\Penjadwalan;
use App\Models\PesananDetail;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class PenjadwalanController extends Controller
{
    const TITLE_INDEX = 'Penjadwalan Produksi';
    const TITLE_INDEX_PDF = 'PENJADWALAN PRODUKSI';

    // public function index(Request $request)
    // {
    //     $dateMulai = $request->input('date', Carbon::now('Asia/Jakarta')->format('Y-m-d'));
    //     $limit = $request->input('limit', 50);

    //     $prosesOrders = Pesanan::with(['pesananDetails.produk'])
    //         ->where('status', 'proses')
    //         ->take($limit)
    //         ->get()
    //         ->map(function ($pesanan) {
    //             $totalQuantity = $pesanan->pesananDetails->sum('jumlah');

    //             $products = $pesanan->pesananDetails->map(function ($detail) {
    //                 return [
    //                     'produk_id' => $detail->produk_id,
    //                     'nama_produk' => $detail->produk->nama_produk,
    //                     'jumlah' => $detail->jumlah
    //                 ];
    //             });

    //             return [
    //                 'pesanan_id' => $pesanan->pesanan_id,
    //                 'channel' => $pesanan->channel,
    //                 'kode_pesanan' => $pesanan->kode_pesanan,
    //                 'products' => $products,
    //                 'total_quantity' => $totalQuantity,
    //                 'tanggal_pengiriman' => $pesanan->tanggal_pengiriman
    //             ];
    //         })
    //         ->sortBy('tanggal_pengiriman')
    //         ->values()
    //         ->all();

    //     $machines = Mesin::where('status', 'aktif')
    //         ->select('mesin_id', 'nama_mesin', 'kapasitas_per_hari')
    //         ->get()
    //         ->map(function ($mesin) {
    //             return [
    //                 'mesin_id' => $mesin->mesin_id,
    //                 'nama_mesin' => $mesin->nama_mesin,
    //                 'kapasitas_per_hari' => $mesin->kapasitas_per_hari
    //             ];
    //         })
    //         ->all();

    //     $schedule = $this->calculateProductionSchedule($limit, $dateMulai, $machines);
    //     $schedule2 = $this->calculateProductionSchedule2($prosesOrders, $dateMulai, $machines);

    //     $uniqueDates = [];
    //     $uniqueProducts = [];
    //     foreach ($schedule as $mesinData) {
    //         foreach ($mesinData['produk'] as $produkData) {
    //             $uniqueDates = array_merge(
    //                 $uniqueDates,
    //                 array_keys($produkData['tanggal_produksi']),
    //             );

    //             if (!isset($uniqueProducts[$produkData['produk_id']])) {
    //                 $uniqueProducts[$produkData['produk_id']] = [
    //                     'nama_produk' => $produkData['nama_produk'],
    //                     'total_item' => 0
    //                 ];
    //             }

    //             $uniqueProducts[$produkData['produk_id']]['total_item'] = $produkData['total_item'];
    //         }
    //     }

    //     $uniqueDates = array_unique($uniqueDates);
    //     $uniqueDatesFormatted = array_map(function ($date) {
    //         return Carbon::createFromFormat('d F Y', $date)->format('Y-m-d');
    //     }, $uniqueDates);

    //     sort($uniqueDatesFormatted);

    //     $uniqueDates = array_map(function ($date) {
    //         return Carbon::parse($date)->format('d F Y');
    //     }, $uniqueDatesFormatted);

    //     $startDate = Carbon::parse(min($uniqueDatesFormatted));
    //     $endDate = Carbon::parse(max($uniqueDatesFormatted));

    //     $allDates = [];
    //     while ($startDate <= $endDate) {
    //         $allDates[] = $startDate->format('d F Y');
    //         $startDate->addDay();
    //     }

    //     $hariLibur = HariLibur::select('tanggal', 'keterangan')
    //         ->get()
    //         ->pluck('keterangan', 'tanggal')
    //         ->toArray();

    //     // return view('menu.penjadwalan.index2', [
    //     return view('menu.penjadwalan.index1', [
    //         'schedule' => $schedule,
    //         'schedule2' => $schedule2,
    //         'uniqueDates' => $uniqueDates,
    //         'uniqueProducts' => $uniqueProducts,
    //         'allDates' => $allDates,
    //         'hariLibur' => $hariLibur,
    //         'title' => self::TITLE_INDEX,
    //         'limit' => $limit
    //     ]);
    // }

    public function index(Request $request)
    {
        return view('menu.penjadwalan.tes', [
            'title' => self::TITLE_INDEX,
        ]);
    }

    public function getOrders()
    {
        $orders = Pesanan::with(['pesananDetails.produk'])
            ->orderBy('tanggal_pesanan', 'desc')
            ->get()
            ->map(function ($pesanan) {
                return [
                    'kode_pesanan' => $pesanan->kode_pesanan,
                    'name' => $pesanan->nama_pemesan,
                    'products' => $pesanan->pesananDetails->map(function ($detail) {
                        return [
                            'name' => $detail->produk->nama_produk,
                            'quantity' => $detail->jumlah
                        ];
                    }),
                    'orderDate' => $pesanan->tanggal_pesanan,
                    'dueDate' => $pesanan->tanggal_pengiriman,
                ];
            });

        return response()->json($orders);
    }

    // public function downloadPDF(Request $request)
    // {
    //     $dateMulai = $request->input('date', Carbon::now('Asia/Jakarta')->format('Y-m-d'));
    //     $limit = $request->input('limit', 50);
    //     $prosesOrders = Pesanan::with(['pesananDetails.produk'])
    //         ->where('status', 'proses')
    //         ->take($limit)
    //         ->get()
    //         ->map(function ($pesanan) {
    //             $totalQuantity = $pesanan->pesananDetails->sum('jumlah');

    //             $products = $pesanan->pesananDetails->map(function ($detail) {
    //                 return [
    //                     'produk_id' => $detail->produk_id,
    //                     'nama_produk' => $detail->produk->nama_produk,
    //                     'jumlah' => $detail->jumlah
    //                 ];
    //             });

    //             return [
    //                 'pesanan_id' => $pesanan->pesanan_id,
    //                 'channel' => $pesanan->channel,
    //                 'kode_pesanan' => $pesanan->kode_pesanan,
    //                 'products' => $products,
    //                 'total_quantity' => $totalQuantity,
    //                 'tanggal_pengiriman' => $pesanan->tanggal_pengiriman
    //             ];
    //         })
    //         ->sortBy('tanggal_pengiriman')
    //         ->values()
    //         ->all();

    //     $machines = Mesin::where('status', 'aktif')
    //         ->select('mesin_id', 'nama_mesin', 'kapasitas_per_hari')
    //         ->get()
    //         ->map(function ($mesin) {
    //             return [
    //                 'mesin_id' => $mesin->mesin_id,
    //                 'nama_mesin' => $mesin->nama_mesin,
    //                 'kapasitas_per_hari' => $mesin->kapasitas_per_hari
    //             ];
    //         })
    //         ->all();

    //     $schedule = $this->calculateProductionSchedule($prosesOrders, $dateMulai, $machines);
    //     $jumlahSchedule = count($prosesOrders);

    //     if (count($schedule) > 0) {
    //         $html = view('menu.penjadwalan.pdf', [
    //             'schedule' => $schedule,
    //             'title' => self::TITLE_INDEX_PDF,
    //             'tanggal' => $this->formatDateInIndonesian(now()),
    //             'limit' => $limit,
    //             'jumlahSchedule' => $jumlahSchedule,
    //             'dateMulai' => Carbon::parse($request->input('date', Carbon::now('Asia/Jakarta')))->locale('id')->isoFormat('D MMMM YYYY'),
    //         ])->render();

    //         $pdf = PDF::loadHTML($html);

    //         $pdf->setPaper('A4', 'landscape');
    //     } else {
    //         $html = view('menu.penjadwalan.pdfError')->render();
    //         $pdf = PDF::loadHTML($html);

    //         $pdf->setPaper('A4', 'potrait');
    //     }

    //     return $pdf->stream('penjadwalan_produksi_' . $limit . '_data_' . now()->format('Y-m-d') . '.pdf');
    // }

    private function translateMonthToIndonesian($month)
    {
        $months = [
            'January' => 'Januari',
            'February' => 'Februari',
            'March' => 'Maret',
            'April' => 'April',
            'May' => 'Mei',
            'June' => 'Juni',
            'July' => 'Juli',
            'August' => 'Agustus',
            'September' => 'September',
            'October' => 'Oktober',
            'November' => 'November',
            'December' => 'Desember'
        ];

        return $months[$month] ?? $month;
    }

    private function formatDateInIndonesian($date)
    {
        $formattedDate = $date->format('d/F/Y');
        $parts = explode('/', $formattedDate);
        $parts[1] = $this->translateMonthToIndonesian($parts[1]);
        return implode(' ', $parts);
    }

    // private function calculateProductionSchedule($orders, $dateMulai, $machines)
    // {
    //     Penjadwalan::truncate();
    //     $schedule = [];
    //     $globalCurrentDate = Carbon::parse($dateMulai)->startOfDay();
    //     $machineCapacityTracker = [];
    //     $hariLibur = HariLibur::pluck('tanggal')->toArray();

    //     foreach ($orders as $order) {
    //         $remainingItems = $order['total_quantity'];
    //         $startProdDate = null;
    //         $machineUsage = [];
    //         $completionTime = 0;

    //         while ($remainingItems > 0) {
    //             if ($globalCurrentDate->dayOfWeek !== 0 && !in_array($globalCurrentDate->format('Y-m-d'), $hariLibur)) {
    //                 $dateKey = $this->formatDateInIndonesian($globalCurrentDate);


    //                 if (!isset($machineCapacityTracker[$dateKey])) {
    //                     $machineCapacityTracker[$dateKey] = array_fill_keys(
    //                         array_column($machines, 'mesin_id'),
    //                         0
    //                     );
    //                 }

    //                 foreach ($machines as $machine) {
    //                     if ($machineCapacityTracker[$dateKey][$machine['mesin_id']] >= $machine['kapasitas_per_hari']) {
    //                         continue;
    //                     }

    //                     if (!$startProdDate) {
    //                         $startProdDate = clone $globalCurrentDate;
    //                     }

    //                     $availableCapacity = $machine['kapasitas_per_hari'] - $machineCapacityTracker[$dateKey][$machine['mesin_id']];
    //                     $itemsToProcess = min($remainingItems, $availableCapacity);

    //                     if ($itemsToProcess > 0) {
    //                         $remainingItems -= $itemsToProcess;
    //                         $machineCapacityTracker[$dateKey][$machine['mesin_id']] += $itemsToProcess;

    //                         if (!isset($machineUsage[$dateKey])) {
    //                             $machineUsage[$dateKey] = [];
    //                         }

    //                         $machineUsage[$dateKey][] = [
    //                             'mesin_id' => $machine['mesin_id'],
    //                             'nama_mesin' => $machine['nama_mesin'],
    //                             'kapasitas_terpakai' => $itemsToProcess
    //                         ];

    //                         if ($remainingItems <= 0) {
    //                             $endProdDate = clone $globalCurrentDate;
    //                             break;
    //                         }
    //                     }
    //                 }

    //                 if (isset($machineUsage[$dateKey])) {
    //                     $completionTime++;
    //                 }
    //             }

    //             if ($remainingItems > 0) {
    //                 $globalCurrentDate->addDay();
    //             }
    //         }
    //         $startDateFormatted = $this->formatDateInIndonesian($startProdDate);
    //         $endDateFormatted = $this->formatDateInIndonesian($endProdDate);
    //         $formattedCompletionTime = $startDateFormatted;

    //         if ($completionTime > 1) {
    //             $formattedCompletionTime .= " - " . $endDateFormatted;
    //         }

    //         $dueDate = Carbon::parse($order['tanggal_pengiriman']);
    //         $completionDateCarbon = clone $endProdDate;

    //         $latenessDate = null;
    //         $latenessDays = 0;
    //         if ($completionDateCarbon->gt($dueDate)) {
    //             $latenessDate = $this->formatDateInIndonesian($completionDateCarbon);
    //             $latenessDays = $completionDateCarbon->diffInDays($dueDate);
    //         }

    //         $penjadwalan = new Penjadwalan([
    //             'pesanan_id' => $order['pesanan_id'],
    //             'due_date' => Carbon::now()->diffInDays(Carbon::parse($order['tanggal_pengiriman'])),
    //             'completion_time' => $completionTime,
    //             'lateness' => $latenessDays,
    //             'mesin' => json_encode(array_reduce(array_keys($machineUsage), function ($result, $date) use ($machineUsage) {
    //                 foreach ($machineUsage[$date] as $usage) {
    //                     $result[] = [
    //                         'date' => $date,
    //                         'mesin_id' => $usage['mesin_id'],
    //                         'usage' => $usage['kapasitas_terpakai'],
    //                     ];
    //                 }
    //                 return $result;
    //             }, []))

    //         ]);
    //         $penjadwalan->save();

    //         $schedule[] = [
    //             'dateMulai' => Carbon::parse($dateMulai)->format('d F Y'),
    //             'pesanan_id' => $order['pesanan_id'],
    //             'kode_pesanan' => $order['kode_pesanan'],
    //             'products' => $order['products'],
    //             'channel' => $order['channel'],
    //             'tanggal_pengiriman_asli' => $this->formatDateInIndonesian(Carbon::parse($order['tanggal_pengiriman'])),
    //             'batas_hari_pengiriman' => Carbon::parse($dateMulai)->diffInDays(Carbon::parse($order['tanggal_pengiriman']), false),
    //             'completion_time' => [
    //                 'tanggal' => $formattedCompletionTime,
    //                 'hari' => $completionTime,
    //             ],
    //             'keterlambatan' => [
    //                 'tanggal' => $latenessDate,
    //                 'hari' => $latenessDays,
    //             ],
    //             'penggunaan_mesin' => $machineUsage,
    //         ];
    //     }
    //     return $schedule;
    // }
}

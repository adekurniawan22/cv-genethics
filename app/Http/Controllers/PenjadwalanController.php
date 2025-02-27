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

    public function index(Request $request)
    {
        $selectedDate = $request->has('date')
            ? Carbon::parse($request->date)
            : Carbon::now('Asia/Jakarta');

        $selectedAverage = $request->has('average')
            ? intval($request->average)
            : 100;

        // Ambil data pesanan
        // Modify the orders query and mapping
        $orders = Pesanan::with(['pesananDetails.produk'])
            ->orderBy('tanggal_pesanan', 'desc')
            ->get()
            ->map(function ($pesanan) use ($selectedDate) {
                return [
                    'kode_pesanan' => $pesanan->kode_pesanan,
                    'name' => $pesanan->nama_pemesan,
                    'products' => $pesanan->pesananDetails->map(function ($detail) {
                        return [
                            'name' => $detail->produk->nama_produk,
                            'quantity' => $detail->jumlah
                        ];
                    }),
                    'countDueDate' => $selectedDate->diffInDays($pesanan->tanggal_pengiriman, false),
                    'totalQuantity' => $pesanan->pesananDetails->sum('jumlah'),
                    'orderDate' => $pesanan->tanggal_pesanan,
                    'dueDate' => $pesanan->tanggal_pengiriman,
                    'pesanan_id' => $pesanan->pesanan_id
                ];
            })
            ->sortBy(function ($order) {
                return [
                    Carbon::parse($order['dueDate'])->timestamp,
                    $order['totalQuantity']
                ];
            })
            ->values()
            ->all();

        // Ambil semua tanggal libur dari database
        $holidays = HariLibur::pluck('tanggal')->map(function ($date) {
            return Carbon::parse($date)->format('Y-m-d');
        })->toArray();

        // Inisialisasi variabel untuk penjadwalan
        $schedule = [];
        $currentDate = $selectedDate;
        $maxItemsPerDay = $selectedAverage;
        $processedOrders = [];
        $scheduleDates = [];

        // Variables for employee and machine assignment
        $employeeCount = 4; // Number of employees
        $maxItemsPerEmployee = $maxItemsPerDay / $employeeCount; // Max items per employee per day
        $employeeAssignments = []; // Track employee assignments

        DB::statement('TRUNCATE TABLE penjadwalan');

        foreach ($orders as $order) {
            if ($currentDate === null) {
                $currentDate = Carbon::parse($order['orderDate']);
            }

            $orderProducts = [];
            $totalOrderQuantity = 0;

            foreach ($order['products'] as $product) {
                $remainingItems = $product['quantity'];
                $tempCurrentDate = clone $currentDate;
                $productStartDate = null;

                $totalOrderQuantity += $product['quantity'];

                $productData = [
                    'product_name' => $product['name'],
                    'total_quantity' => $product['quantity'],
                    'scheduled_items' => [],
                    'completion_time' => [],
                    'lateness' => [],
                    'assignments' => [], // Add assignments array to track employee-machine assignments
                ];

                while ($remainingItems > 0) {
                    $dateKey = $tempCurrentDate->format('Y-m-d');
                    $isHoliday = in_array($dateKey, $holidays);
                    $isSunday = $tempCurrentDate->isSunday();

                    // Always add the date to scheduled_items, but set to 0 for holidays/sundays
                    if ($isHoliday || $isSunday) {
                        $productData['scheduled_items'][$dateKey] = 0;
                        $productData['completion_time'][$dateKey] = 0;
                        $productData['lateness'][$dateKey] = 0;
                    } else {
                        if (!isset($schedule[$dateKey])) {
                            $schedule[$dateKey] = 0;
                        }

                        // Initialize employee assignments for this date if needed
                        if (!isset($employeeAssignments[$dateKey])) {
                            $employeeAssignments[$dateKey] = [];
                            for ($i = 1; $i <= $employeeCount; $i++) {
                                $employeeAssignments[$dateKey][$i] = 0; // Initialize capacity for each employee
                            }
                        }

                        $availableCapacity = $maxItemsPerDay - $schedule[$dateKey];
                        $itemsProcessedThisDate = 0;

                        // Process items based on employee availability
                        for ($employeeId = 1; $employeeId <= $employeeCount && $remainingItems > 0; $employeeId++) {
                            // Skip if this employee has reached their capacity
                            if ($employeeAssignments[$dateKey][$employeeId] >= $maxItemsPerEmployee) {
                                continue;
                            }

                            // Calculate how many items this employee can process
                            $employeeAvailableCapacity = $maxItemsPerEmployee - $employeeAssignments[$dateKey][$employeeId];
                            $itemsToProcessByEmployee = min($remainingItems, $employeeAvailableCapacity);

                            if ($itemsToProcessByEmployee > 0) {
                                // Store the assignment details
                                if (!isset($productData['assignments'][$dateKey])) {
                                    $productData['assignments'][$dateKey] = [];
                                }

                                $productData['assignments'][$dateKey][] = [
                                    'employee_id' => $employeeId,
                                    'machine_id' => $employeeId, // Assuming machine ID equals employee ID
                                    'quantity' => $itemsToProcessByEmployee,
                                    'order_id' => $order['pesanan_id'],
                                    'product_name' => $product['name']
                                ];

                                // Update tracking variables
                                $employeeAssignments[$dateKey][$employeeId] += $itemsToProcessByEmployee;
                                $itemsProcessedThisDate += $itemsToProcessByEmployee;
                                $remainingItems -= $itemsToProcessByEmployee;

                                // Break if all remaining items are processed
                                if ($remainingItems <= 0) {
                                    break;
                                }
                            }
                        }

                        // Update scheduled items for this date
                        $productData['scheduled_items'][$dateKey] = $itemsProcessedThisDate;

                        if ($itemsProcessedThisDate > 0) {
                            $productData['completion_time'][$dateKey] = floor($itemsProcessedThisDate / $maxItemsPerDay * 100) / 100;
                            $productData['lateness'][$dateKey] = intval(floor((($itemsProcessedThisDate / $maxItemsPerDay) * 100) / 1000) - $order['countDueDate']);
                            $schedule[$dateKey] += $itemsProcessedThisDate;

                            if ($productStartDate === null) {
                                $productStartDate = $dateKey;
                            }
                        } else {
                            $productData['scheduled_items'][$dateKey] = 0;
                            $productData['completion_time'][$dateKey] = 0;
                            $productData['lateness'][$dateKey] = 0;
                        }
                    }

                    // Record in scheduleDates for all days
                    if (!isset($scheduleDates[$dateKey])) {
                        $scheduleDates[$dateKey] = [
                            'date' => $dateKey,
                            'total_items' => 0,
                            'is_holiday' => $isHoliday,
                            'is_sunday' => $isSunday,
                            'holiday_desc' => $isHoliday ? HariLibur::where('tanggal', $dateKey)->first()['keterangan'] : null
                        ];
                    }

                    // Only add to total_items if it's a working day
                    if (!$isHoliday && !$isSunday) {
                        $scheduleDates[$dateKey]['total_items'] += isset($productData['scheduled_items'][$dateKey]) ?
                            $productData['scheduled_items'][$dateKey] : 0;
                    }

                    if (!$isHoliday && !$isSunday && $remainingItems <= 0) {
                        break;
                    }

                    $tempCurrentDate->addDay();
                }

                $productData['start_date'] = $productStartDate; // tambahkan tanggal awal produk ke dalam array $productData
                $orderProducts[] = $productData;
            }

            $processedOrders[$order['kode_pesanan']] = [
                'kode_pesanan' => $order['kode_pesanan'],
                'customer' => $order['name'],
                'products' => $orderProducts,
                'total_quantity' => $totalOrderQuantity,
                'completion_time' => $totalOrderQuantity / 77,
                'start_date' => min(array_map(function ($product) { // ubah penentuan start_date
                    return $product['start_date'];
                }, $orderProducts)),
                'end_date' => max(array_map(function ($product) {
                    return max(array_keys($product['scheduled_items']));
                }, $orderProducts)),
                'dueDate' => $order['countDueDate'],
                'assignments' => array_reduce($orderProducts, function ($carry, $product) {
                    foreach ($product['assignments'] ?? [] as $date => $dateAssignments) {
                        if (!isset($carry[$date])) {
                            $carry[$date] = [];
                        }
                        $carry[$date] = array_merge($carry[$date], $dateAssignments);
                    }
                    return $carry;
                }, [])
            ];

            DB::table('penjadwalan')->insert([
                'pesanan_id' => $order['pesanan_id'],
                'detail_perhitungan' => json_encode($orderProducts), // Insert orderProducts as JSON into detail column
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }

        // Temukan tanggal minimal dan maksimal dari semua pesanan
        $minDate = null;
        $maxDate = null;
        foreach ($processedOrders as $order) {
            if ($minDate === null || $order['start_date'] < $minDate) {
                $minDate = $order['start_date'];
            }
            if ($maxDate === null || $order['end_date'] > $maxDate) {
                $maxDate = $order['end_date'];
            }
        }

        // Buat array tanggal lengkap dari min ke max
        $completeDates = [];
        $currentDate = Carbon::parse($minDate);
        $endDate = Carbon::parse($maxDate);

        while ($currentDate <= $endDate) {
            $dateKey = $currentDate->format('Y-m-d');

            // Jika tanggal sudah ada di scheduleDates, gunakan data yang ada
            if (isset($scheduleDates[$dateKey])) {
                $completeDates[$dateKey] = $scheduleDates[$dateKey];
            } else {
                // Jika tidak, buat data baru dengan total_items = 0
                $isHoliday = in_array($dateKey, $holidays);

                $completeDates[$dateKey] = [
                    'date' => $dateKey,
                    'total_items' => 0,
                    'is_holiday' => $isHoliday,
                    'holiday_desc' => $isHoliday ? HariLibur::where('tanggal', $dateKey)->first()['keterangan'] : null,
                    'is_sunday' => $currentDate->isSunday(),
                ];
            }

            $currentDate->addDay();
        }

        // Gunakan complete dates sebagai scheduleDates
        $scheduleDates = $completeDates;
        ksort($scheduleDates);

        $penjadwalan = DB::table('penjadwalan')
            ->join('pesanan', 'penjadwalan.pesanan_id', '=', 'pesanan.pesanan_id')
            ->select('penjadwalan.*', 'pesanan.*') // Pilih kolom yang diinginkan
            ->get();

        // dd($processedOrders, $employeeAssignments, $maxItemsPerEmployee);

        return view('menu.penjadwalan.index', [
            'title' => 'Hasil Penjadwalan',
            'processedOrders' => $processedOrders,
            'scheduleDates' => $scheduleDates,
            'penjadwalan' => $penjadwalan,
            'employeeAssignments' => $employeeAssignments,
            'maxItemsPerEmployee' => $maxItemsPerEmployee
        ]);
    }

    public function downloadPDF(Request $request)
    {
        // $dateMulai = $request->input('date', Carbon::now('Asia/Jakarta')->format('Y-m-d'));
        // if (count($schedule) > 0) {
        //     $html = view('menu.penjadwalan.pdf', [
        //         'schedule' => $schedule,
        //         'title' => self::TITLE_INDEX_PDF,
        //         'tanggal' => $this->formatDateInIndonesian(now()),
        //         'limit' => $limit,
        //         'jumlahSchedule' => $jumlahSchedule,
        //         'dateMulai' => Carbon::parse($request->input('date', Carbon::now('Asia/Jakarta')))->locale('id')->isoFormat('D MMMM YYYY'),
        //     ])->render();

        //     $pdf = PDF::loadHTML($html);

        //     $pdf->setPaper('A4', 'landscape');
        // } else {
        //     $html = view('menu.penjadwalan.pdfError')->render();
        //     $pdf = PDF::loadHTML($html);

        //     $pdf->setPaper('A4', 'potrait');
        // }

        // return $pdf->stream('penjadwalan_produksi_' . $limit . '_data_' . now()->format('Y-m-d') . '.pdf');
    }
}

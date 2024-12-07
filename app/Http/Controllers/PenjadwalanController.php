<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use App\Models\Mesin;
use App\Models\Penjadwalan;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class PenjadwalanController extends Controller
{
    const TITLE_INDEX = 'Penjadwalan Produksi';
    const TITLE_INDEX_PDF = 'PENJADWALAN PRODUKSI';

    // Di Controller
    public function index($limit = null)
    {
        // Ambil limit dari parameter URL atau request, default 5
        $limit = $limit ?? request('limit', 20);

        $prosesOrders = Pesanan::with(['pesananDetails.produk'])
            ->where('status', 'proses')
            ->take($limit) // Tambahkan limit
            ->get()
            ->map(function ($pesanan) {
                $totalQuantity = $pesanan->pesananDetails->sum('jumlah');

                $products = $pesanan->pesananDetails->map(function ($detail) {
                    return [
                        'produk_id' => $detail->produk_id,
                        'nama_produk' => $detail->produk->nama_produk,
                        'jumlah' => $detail->jumlah
                    ];
                });

                return [
                    'pesanan_id' => $pesanan->pesanan_id,
                    'channel' => $pesanan->channel,
                    'kode_pesanan' => $pesanan->kode_pesanan,
                    'products' => $products,
                    'total_quantity' => $totalQuantity,
                    'tanggal_pengiriman' => $pesanan->tanggal_pengiriman
                ];
            })
            ->sortBy('tanggal_pengiriman')
            ->values()
            ->all();

        $machines = Mesin::where('status', 'aktif')
            ->select('mesin_id', 'nama_mesin', 'kapasitas_per_hari')
            ->get()
            ->map(function ($mesin) {
                return [
                    'mesin_id' => $mesin->mesin_id,
                    'nama_mesin' => $mesin->nama_mesin,
                    'kapasitas_per_hari' => $mesin->kapasitas_per_hari
                ];
            })
            ->all();

        $schedule = $this->calculateProductionSchedule($prosesOrders, $machines);

        return view('menu.penjadwalan.index', [
            'schedule' => $schedule,
            'title' => self::TITLE_INDEX,
            'limit' => $limit
        ]);
    }

    public function downloadPDF($limit = null)
    {
        // Ambil limit dari parameter URL atau request, default 5
        $limit = $limit ?? request('limit', 20);

        // Ambil data schedule seperti di method index
        $prosesOrders = Pesanan::with(['pesananDetails.produk'])
            ->where('status', 'proses')
            ->take($limit) // Tambahkan limit
            ->get()
            ->map(function ($pesanan) {
                $totalQuantity = $pesanan->pesananDetails->sum('jumlah');

                $products = $pesanan->pesananDetails->map(function ($detail) {
                    return [
                        'produk_id' => $detail->produk_id,
                        'nama_produk' => $detail->produk->nama_produk,
                        'jumlah' => $detail->jumlah
                    ];
                });

                return [
                    'pesanan_id' => $pesanan->pesanan_id,
                    'channel' => $pesanan->channel,
                    'kode_pesanan' => $pesanan->kode_pesanan,
                    'products' => $products,
                    'total_quantity' => $totalQuantity,
                    'tanggal_pengiriman' => $pesanan->tanggal_pengiriman
                ];
            })
            ->sortBy('tanggal_pengiriman')
            ->values()
            ->all();

        $machines = Mesin::where('status', 'aktif')
            ->select('mesin_id', 'nama_mesin', 'kapasitas_per_hari')
            ->get()
            ->map(function ($mesin) {
                return [
                    'mesin_id' => $mesin->mesin_id,
                    'nama_mesin' => $mesin->nama_mesin,
                    'kapasitas_per_hari' => $mesin->kapasitas_per_hari
                ];
            })
            ->all();

        $schedule = $this->calculateProductionSchedule($prosesOrders, $machines);
        $jumlahSchedule = count($prosesOrders); // Gantilah 'property' dengan properti yang sesuai


        // Generate PDF
        $pdf = PDF::loadView('menu.penjadwalan.pdf', [
            'schedule' => $schedule,
            'title' => self::TITLE_INDEX_PDF,
            'tanggal' => $this->formatDateInIndonesian(now()),
            'limit' => $limit,
            'jumlahSchedule' => $jumlahSchedule,
        ]);

        return $pdf->stream('penjadwalan_produksi_' . $limit . '_data_' . now()->format('Y-m-d') . '.pdf');
    }

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

    private function calculateProductionSchedule($orders, $machines)
    {
        $schedule = [];
        $globalCurrentDate = now()->startOfDay();
        $machineCapacityTracker = [];

        foreach ($orders as $order) {
            $remainingItems = $order['total_quantity'];
            $startProdDate = null;
            $machineUsage = [];
            $completionTime = 0;

            while ($remainingItems > 0) {
                if ($globalCurrentDate->dayOfWeek !== 0) {
                    $dateKey = $this->formatDateInIndonesian($globalCurrentDate);

                    if (!isset($machineCapacityTracker[$dateKey])) {
                        $machineCapacityTracker[$dateKey] = array_fill_keys(
                            array_column($machines, 'mesin_id'),
                            0
                        );
                    }

                    foreach ($machines as $machine) {
                        if ($machineCapacityTracker[$dateKey][$machine['mesin_id']] >= $machine['kapasitas_per_hari']) {
                            continue;
                        }

                        if (!$startProdDate) {
                            $startProdDate = clone $globalCurrentDate;
                        }

                        $availableCapacity = $machine['kapasitas_per_hari'] - $machineCapacityTracker[$dateKey][$machine['mesin_id']];
                        $itemsToProcess = min($remainingItems, $availableCapacity);

                        if ($itemsToProcess > 0) {
                            $remainingItems -= $itemsToProcess;
                            $machineCapacityTracker[$dateKey][$machine['mesin_id']] += $itemsToProcess;

                            if (!isset($machineUsage[$dateKey])) {
                                $machineUsage[$dateKey] = [];
                            }

                            $machineUsage[$dateKey][] = [
                                'mesin_id' => $machine['mesin_id'],
                                'nama_mesin' => $machine['nama_mesin'],
                                'kapasitas_terpakai' => $itemsToProcess
                            ];

                            if ($remainingItems <= 0) {
                                $endProdDate = clone $globalCurrentDate;
                                break;
                            }
                        }
                    }

                    if (isset($machineUsage[$dateKey])) {
                        $completionTime++;
                    }
                }

                if ($remainingItems > 0) {
                    $globalCurrentDate->addDay();
                }
            }

            // Format tanggal mulai dan selesai dalam bahasa Indonesia
            $startDateFormatted = $this->formatDateInIndonesian($startProdDate);
            $endDateFormatted = $this->formatDateInIndonesian($endProdDate);

            // Format completion time dengan jumlah hari yang dibutuhkan
            $formattedCompletionTime = $startDateFormatted;
            if ($completionTime > 1) {
                $formattedCompletionTime .= " - " . $endDateFormatted;
            }

            // Hitung keterlambatan
            $dueDate = Carbon::parse($order['tanggal_pengiriman']);
            $completionDateCarbon = clone $endProdDate;

            $latenessDate = null;
            $latenessDays = 0; // Default to 0 if there's no lateness

            if ($completionDateCarbon->gt($dueDate)) {
                // Format lateness date and calculate lateness days in Indonesian
                $latenessDate = $this->formatDateInIndonesian($completionDateCarbon);
                $latenessDays = $completionDateCarbon->diffInDays($dueDate); // Calculate days of lateness
            }

            $schedule[] = [
                'pesanan_id' => $order['pesanan_id'],
                'kode_pesanan' => $order['kode_pesanan'],
                'products' => $order['products'],
                'channel' => $order['channel'],
                'tanggal_pengiriman_asli' => $this->formatDateInIndonesian(Carbon::parse($order['tanggal_pengiriman'])),
                'batas_hari_pengiriman' => Carbon::now()->diffInDays(Carbon::parse($order['tanggal_pengiriman'])),
                'completion_time' => [
                    'tanggal' => $formattedCompletionTime,
                    'hari' => $completionTime,
                ],  // Number of days it took to complete all items
                'keterlambatan' => [
                    'tanggal' => $latenessDate,    // The lateness date (if any)
                    'hari' => $latenessDays,      // Number of lateness days (if any)
                ],
                'penggunaan_mesin' => $machineUsage
            ];
        }

        return $schedule;
    }
}

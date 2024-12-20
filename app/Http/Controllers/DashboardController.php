<?php

namespace App\Http\Controllers;

use App\Models\{Mesin, Pengguna, Pesanan, Produk};
use Carbon\Carbon;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\PesananDetail;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function owner()
    {
        $currentMonth = date('n');
        $currentMonthName = $this->indonesianMonths[$currentMonth];

        $totalAdmin = Pengguna::where('role', 'admin')->count();

        $totalPesananSelesaiBulanIni = Pesanan::whereMonth('tanggal_pesanan', $currentMonth)
            ->where('status', 'selesai')
            ->count();

        $totalPesananProsesBulanIni = Pesanan::whereMonth('tanggal_pesanan', $currentMonth)
            ->where('status', 'proses')
            ->count();

        $totalMesin = Mesin::all()->count();
        $totalProduk = Produk::all()->count();

        return view('menu.dashboard.owner', [
            'title' => 'Dashboard Owner',
            'totalPesananSelesaiBulanIni' => $totalPesananSelesaiBulanIni,
            'totalPesananProsesBulanIni' => $totalPesananProsesBulanIni,
            'totalAdmin' => $totalAdmin,
            'totalMesin' => $totalMesin,
            'totalProduk' => $totalProduk,
            'currentMonthName' => $currentMonthName,
        ]);
    }

    public function manajer()
    {
        $currentMonth = date('n');
        $currentMonthName = $this->indonesianMonths[$currentMonth];

        $totalAdmin = Pengguna::where('role', 'admin')->count();

        $totalPesananSelesaiBulanIni = Pesanan::whereMonth('tanggal_pesanan', $currentMonth)
            ->where('status', 'selesai')
            ->count();

        $totalPesananProsesBulanIni = Pesanan::whereMonth('tanggal_pesanan', $currentMonth)
            ->where('status', 'proses')
            ->count();

        $totalMesin = Mesin::all()->count();
        $totalProduk = Produk::all()->count();

        return view('menu.dashboard.manajer', [
            'title' => 'Dashboard Manajer',
            'totalPesananSelesaiBulanIni' => $totalPesananSelesaiBulanIni,
            'totalPesananProsesBulanIni' => $totalPesananProsesBulanIni,
            'totalAdmin' => $totalAdmin,
            'totalMesin' => $totalMesin,
            'totalProduk' => $totalProduk,
            'currentMonthName' => $currentMonthName
        ]);
    }

    public function admin()
    {
        $currentMonth = date('n');
        $currentMonthName = $this->indonesianMonths[$currentMonth];

        $totalPesananSelesaiBulanIni = Pesanan::whereMonth('tanggal_pesanan', $currentMonth)
            ->where('status', 'selesai')
            ->count();

        $totalPesananProsesBulanIni = Pesanan::whereMonth('tanggal_pesanan', $currentMonth)
            ->where('status', 'proses')
            ->count();

        return view('menu.dashboard.admin', [
            'title' => 'Dashboard Admin',
            'totalPesananSelesaiBulanIni' => $totalPesananSelesaiBulanIni,
            'totalPesananProsesBulanIni' => $totalPesananProsesBulanIni,
            'currentMonthName' => $currentMonthName,
        ]);
    }

    public function super()
    {
        $currentMonth = date('n');
        $currentMonthName = $this->indonesianMonths[$currentMonth];

        $totalAdmin = Pengguna::where('role', 'admin')->count();
        $totalManajer = Pengguna::where('role', 'manajer')->count();
        $totalOwner = Pengguna::where('role', 'owner')->count();

        $totalPesananSelesaiBulanIni = Pesanan::whereMonth('tanggal_pesanan', $currentMonth)
            ->where('status', 'selesai')
            ->count();

        $totalPesananProsesBulanIni = Pesanan::whereMonth('tanggal_pesanan', $currentMonth)
            ->where('status', 'proses')
            ->count();

        $totalMesin = Mesin::all()->count();
        $totalProduk = Produk::all()->count();

        $topPemesan = Pesanan::select('nama_pemesan', DB::raw('SUM(pesanan_detail.jumlah) as total_item_pesanan'))
            ->join('pesanan_detail', 'pesanan.pesanan_id', '=', 'pesanan_detail.pesanan_id')
            ->join('produk', 'pesanan_detail.produk_id', '=', 'produk.produk_id')
            ->where('pesanan.status', 'selesai')
            ->groupBy('nama_pemesan')
            ->orderByDesc('total_item_pesanan')
            ->limit(3)
            ->get()
            ->map(function ($pesanan) {
                $detailProduk = PesananDetail::select('produk.nama_produk', DB::raw('SUM(pesanan_detail.jumlah) as jumlah'))
                    ->join('produk', 'pesanan_detail.produk_id', '=', 'produk.produk_id')
                    ->whereHas('pesanan', function ($query) use ($pesanan) {
                        $query->where('nama_pemesan', $pesanan->nama_pemesan);
                    })
                    ->groupBy('produk.nama_produk')
                    ->pluck('jumlah', 'produk.nama_produk');

                return [
                    'nama_pemesan' => $pesanan->nama_pemesan,
                    'total_item_pesanan' => $pesanan->total_item_pesanan,
                    'detail' => $detailProduk,
                ];
            });

        return view('menu.dashboard.super', [
            'title' => 'Dashboard Super Admin',
            'totalPesananSelesaiBulanIni' => $totalPesananSelesaiBulanIni,
            'totalPesananProsesBulanIni' => $totalPesananProsesBulanIni,
            'totalAdmin' => $totalAdmin,
            'totalManajer' => $totalManajer,
            'totalOwner' => $totalOwner,
            'totalMesin' => $totalMesin,
            'totalProduk' => $totalProduk,
            'currentMonthName' => $currentMonthName,
            'topPemesan' => $topPemesan,
        ]);
    }

    public function getChartData(Request $request)
    {
        $year = $request->input('year', Carbon::now()->year);
        $orders = Pesanan::with('pesananDetails.produk')
            ->where('status', 'selesai')
            ->whereYear('tanggal_pesanan', $year)
            ->get();

        $monthlyRevenues = collect(range(1, 12))->map(function ($month) use ($orders, $year) {
            $monthOrders = $orders->filter(function ($order) use ($month) {
                return Carbon::parse($order->tanggal_pesanan)->month === $month;
            });

            $totalRevenue = $monthOrders->flatMap(function ($order) {
                return $order->pesananDetails->map(function ($detail) {
                    return $detail->jumlah * $detail->produk->harga;
                });
            })->sum();

            return [
                'month' => $month,
                'month_name' => $this->indonesianMonths[$month],
                'total_revenue' => $totalRevenue
            ];
        });

        $topProducts = $orders->flatMap(function ($order) {
            return $order->pesananDetails;
        })
            ->groupBy('produk_id')
            ->map(function ($details) {
                $product = $details->first()->produk;
                return [
                    'name' => $product->nama_produk,
                    'total_sold' => $details->sum('jumlah')
                ];
            })
            ->sortByDesc('total_sold')
            ->take(5)
            ->values();

        $topPemesan = Pesanan::select('nama_pemesan', DB::raw('SUM(pesanan_detail.jumlah) as total_item_pesanan'))
            ->join('pesanan_detail', 'pesanan.pesanan_id', '=', 'pesanan_detail.pesanan_id')
            ->join('produk', 'pesanan_detail.produk_id', '=', 'produk.produk_id')
            ->where('pesanan.status', 'selesai')
            ->whereYear('tanggal_pesanan', $year)
            ->groupBy('nama_pemesan')
            ->orderByDesc('total_item_pesanan')
            ->limit(3)
            ->get()
            ->map(function ($pesanan) {
                $detailProduk = PesananDetail::select('produk.nama_produk', DB::raw('SUM(pesanan_detail.jumlah) as jumlah'))
                    ->join('produk', 'pesanan_detail.produk_id', '=', 'produk.produk_id')
                    ->whereHas('pesanan', function ($query) use ($pesanan) {
                        $query->where('nama_pemesan', $pesanan->nama_pemesan);
                    })
                    ->groupBy('produk.nama_produk')
                    ->pluck('jumlah', 'produk.nama_produk');

                return [
                    'nama_pemesan' => $pesanan->nama_pemesan,
                    'total_item_pesanan' => $pesanan->total_item_pesanan,
                    'detail' => $detailProduk,
                ];
            });

        $responseData = [
            'year' => $year,
            'monthly_revenues' => $monthlyRevenues,
            'total_annual_revenue' => $monthlyRevenues->sum('total_revenue'),
            'top_products' => $topProducts,
            'top_pemesan' => $topPemesan,
        ];

        return response()->json($responseData);
    }

    public function generatePdfReport(Request $request)
    {
        $year = (int) $request->input('year', Carbon::now()->year);

        $orders = Pesanan::with('pesananDetails.produk')
            ->where('status', 'selesai')
            ->whereYear('tanggal_pesanan', $year)
            ->get();

        $monthlyRevenues = collect(range(1, 12))->map(function ($month) use ($orders, $year) {
            $monthOrders = $orders->filter(function ($order) use ($month) {
                return Carbon::parse($order->tanggal_pesanan)->month === $month;
            });

            $totalRevenue = $monthOrders->flatMap(function ($order) {
                return $order->pesananDetails->map(function ($detail) {
                    return $detail->jumlah * $detail->produk->harga;
                });
            })->sum();

            return [
                'month' => $month,
                'month_name' => $this->indonesianMonths[$month],
                'total_revenue' => $totalRevenue
            ];
        });

        $topProducts = $orders->flatMap(function ($order) {
            return $order->pesananDetails;
        })
            ->groupBy('produk_id')
            ->map(function ($details) {
                $product = $details->first()->produk;
                return [
                    'name' => $product->nama_produk,
                    'total_sold' => $details->sum('jumlah')
                ];
            })
            ->sortByDesc('total_sold')
            ->take(5)
            ->values();

        $topPemesan = Pesanan::select('nama_pemesan', DB::raw('SUM(pesanan_detail.jumlah) as total_item_pesanan'))
            ->join('pesanan_detail', 'pesanan.pesanan_id', '=', 'pesanan_detail.pesanan_id')
            ->join('produk', 'pesanan_detail.produk_id', '=', 'produk.produk_id')
            ->where('pesanan.status', 'selesai')
            ->whereYear('tanggal_pesanan', $year)
            ->groupBy('nama_pemesan')
            ->orderByDesc('total_item_pesanan')
            ->limit(3)
            ->get()
            ->map(function ($pesanan) {
                $detailProduk = PesananDetail::select('produk.nama_produk', DB::raw('SUM(pesanan_detail.jumlah) as jumlah'))
                    ->join('produk', 'pesanan_detail.produk_id', '=', 'produk.produk_id')
                    ->whereHas('pesanan', function ($query) use ($pesanan) {
                        $query->where('nama_pemesan', $pesanan->nama_pemesan);
                    })
                    ->groupBy('produk.nama_produk')
                    ->pluck('jumlah', 'produk.nama_produk');

                return [
                    'nama_pemesan' => $pesanan->nama_pemesan,
                    'total_item_pesanan' => $pesanan->total_item_pesanan,
                    'detail' => $detailProduk,
                ];
            });

        $reportData = [
            'year' => $year,
            'monthly_revenues' => $monthlyRevenues,
            'total_annual_revenue' => $monthlyRevenues->sum('total_revenue'),
            'top_products' => $topProducts,
            'top_pemesan' => $topPemesan,
        ];

        $fileName = 'laporan_penjualan_' . $year . '_' . now()->format('Y-m-d') . '.pdf';

        $pdf = PDF::loadView('menu.dashboard.keuangan_pdf', [
            'year' => $year,
            'reportData' => $reportData,
        ]);

        return $pdf->stream($fileName);
    }

    private $indonesianMonths = [
        1 => 'Januari',
        2 => 'Februari',
        3 => 'Maret',
        4 => 'April',
        5 => 'Mei',
        6 => 'Juni',
        7 => 'Juli',
        8 => 'Agustus',
        9 => 'September',
        10 => 'Oktober',
        11 => 'November',
        12 => 'Desember'
    ];
}

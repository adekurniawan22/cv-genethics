Karena ada jumlah pegawai sebanyak 4 orang dan jumlah mesin sebanyak pegawai, maka 1 pegawai mengerjakan hasil dari pembagian $maxItemsPerDay/jumlah pegawai, jadi saya ingin di sebalah Kode Produksi di buat kolom baru yaitu Pengerjaan, nah ini berisi pegawai dan mesin mana yg digunakan

contoh kasus :
pesanan 1 dikerjakan tanggal 01 Januari 2024 hingga 01 Januari 2024 dengan total quantity 3
pesanan 2 dikerjakan tanggal 01 Januari 2024 hingga 01 Januari 2024 dengan total quantity 10
pesanan 3 dikerjakan tanggal 01 Januari 2024 hingga 02 Januari 2024 dengan total quantity 120

maka :
pegawai 1 mengerjakan 3 item dari pesanan 1 di mesin 1 pada 01 Januari 2024
pegawai 1 mengerjakan 10 item dari pesanan 2 di mesin 1 pada 01 Januari 2024
pegawai 1 mengerjakan 12 item dari pesanan 3 di mesin 1 pada 01 Januari 2024 (sudah mencapai batas (anggap kapasitas perhari 100) dibagi 4 = 25)
pegawai 2 mengerjakan 25 item dari pesanan 3 di mesin 2 pada 01 Januari 2024 (sudah mencapai batas (anggap kapasitas perhari 100) dibagi 4 = 25)
pegawai 3 mengerjakan 25 item dari pesanan 3 di mesin 3 pada 01 Januari 2024 (sudah mencapai batas (anggap kapasitas perhari 100) dibagi 4 = 25)
pegawai 4 mengerjakan 25 item dari pesanan 3 di mesin 4 pada 01 Januari 2024 (sudah mencapai batas (anggap kapasitas perhari 100) dibagi 4 = 25)
pegawai 1 mengerjakan 25 item dari pesanan 3 di mesin 1 pada 02 Januari 2024 (sudah mencapai batas (anggap kapasitas perhari 100) dibagi 4 = 25)
pegawai 2 mengerjakan 8 item dari pesanan 3 di mesin 2 pada 02 Januari 2024

Ikuti dengan cara menghitung scheduled_items, tetapi bedanya setiap pegawai memiliki batas kapasitas yaitu $maxItemsPerDay/ jumlah_pegawai

// Controller
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
];

while ($remainingItems > 0) {
$dateKey = $tempCurrentDate->format('Y-m-d');
$isHoliday = in_array($dateKey, $holidays);
$isSunday = $tempCurrentDate->isSunday();

// Always add the date to scheduled_items, but set to 0 for holidays/sundays
if ($isHoliday || $isSunday) {
$productData['scheduled_items'][$dateKey] = 0;
} else {
if (!isset($schedule[$dateKey])) {
$schedule[$dateKey] = 0;
}

$availableCapacity = $maxItemsPerDay - $schedule[$dateKey];
$itemsToProcess = min($remainingItems, $availableCapacity);

if ($itemsToProcess > 0) {
$productData['scheduled_items'][$dateKey] = $itemsToProcess;
$productData['completion_time'][$dateKey] = floor($itemsToProcess / $maxItemsPerDay * 100) / 100;
$productData['lateness'][$dateKey] = intval(floor((($itemsToProcess / $maxItemsPerDay) * 100) / 1000) - $order['countDueDate']);
$schedule[$dateKey] += $itemsToProcess;
$remainingItems -= $itemsToProcess;

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
    'dueDate' => $order['countDueDate']
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
        $minDate=$order['start_date'];
        }
        if ($maxDate===null || $order['end_date']> $maxDate) {
        $maxDate = $order['end_date'];
        }
        }

        // Buat array tanggal lengkap dari min ke max
        $completeDates = [];
        $currentDate = Carbon::parse($minDate);
        $endDate = Carbon::parse($maxDate);

        while ($currentDate <= $endDate) {
            $dateKey=$currentDate->format('Y-m-d');

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

            // dd($processedOrders);

            return view('menu.penjadwalan.index', [
            'title' => 'Hasil Penjadwalan',
            'processedOrders' => $processedOrders,
            'scheduleDates' => $scheduleDates,
            'penjadwalan' => $penjadwalan
            ]);
            }

            //Views
            @extends('layout.main')
            @section('content')
            <style>
                .small-label {
                    font-size: 0.875rem;
                }

                #productionTable th,
                #productionTable td {
                    text-align: center;
                    vertical-align: middle;
                    min-width: 200px;
                    padding: 8px;
                    border: 1px solid #dee2e6;
                }

                .progress {
                    position: relative;
                    overflow: visible;
                }

                .progress-bar {
                    transition: width 0.6s ease;
                    border-radius: 0.1rem;
                }
            </style>

            <main class="page-content">
                <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3"
                    style="height: 37px; overflow: hidden; display: flex; align-items: center;">
                    <div>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0 p-0">
                                <li class="breadcrumb-item">
                                    <a href="{{ route(session()->get('role') . '.dashboard') }}"><i class="bx bx-home-alt"></i></a>
                                </li>
                                <li class="breadcrumb-item" aria-current="page">
                                    <span class="text-dark">Penjadwalan</span>
                                </li>
                            </ol>
                        </nav>
                    </div>
                </div>

                <div class="row ms-0 me-1">
                    <div class="ps-0 pe-1 col-2">
                        <div class="card radius-10 mb-3">
                            <div class="card-body">
                                <div class="align-items-center gap-2 ms-0" style="max-width:500px">
                                    <form action="{{ route(session()->get('role') . '.penjadwalan.index') }}" method="GET">
                                        <div class="row g-1">
                                            <div class="col-12">
                                                <label for="date" class="col-form-label small-label">Tanggal Mulai:</label>
                                                <input type="date" name="date" id="date"
                                                    class="form-control form-control-sm" placeholder="Pilih Tanggal"
                                                    value="{{ request('date', \Carbon\Carbon::now('Asia/Jakarta')->format('Y-m-d')) }}">
                                            </div>
                                            <div class="col-12">
                                                <label for="average" class="col-form-label small-label">Rata-rata perhari:</label>
                                                <input type="number" name="average" id="average"
                                                    class="form-control form-control-sm" placeholder="Masukkan rata-rata perhari"
                                                    value="{{ request('average', 100) }}">
                                            </div>
                                        </div>

                                        <div class="row g-3 mb-2">
                                            <div class="col-12">
                                                <div class="row mt-2 g-1">
                                                    <div class="col-12">
                                                        <a href="javascript:void(0);" id="submitBtn"
                                                            class="btn btn-primary w-100">Jadwalkan
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="card radius-10">
                            <div class="card-body">
                                <div class="d-flex flex-column gap-2" style="max-width:500px">
                                    <div class="d-flex align-items-center">
                                        <span class="badge" style="background-color: #007bff;">Print & Press</span>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <span class="badge" style="background-color: #28a745;">Cutting</span>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <span class="badge" style="background-color: #ffc107; color: black;">Jahit & Obras</span>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <span class="badge" style="background-color: #e95d1c;">Finishing</span>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <span class="badge" style="background-color: #6c757d;">QC & Packing</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-10 pe-0">
                        <div class="card radius-10">
                            <div class="card-body">

                                <!-- Nav tabs -->
                                <ul class="nav nav-tabs" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link active" data-bs-toggle="tab" href="#penjadwalanTable" role="tab"
                                            aria-selected="true">Penjadwalan</a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link" data-bs-toggle="tab" href="#tab2" role="tab"
                                            aria-selected="false">Detail Perhitungan</a>
                                    </li>
                                </ul>

                                <!-- Tab panes -->
                                <div class="tab-content">
                                    <div class="tab-pane active" id="penjadwalanTable" role="tabpanel">
                                        <div class="table-responsive mt-3" style="max-height: 600px !important;">
                                            <table id="productionTable" class="table">
                                                <thead>
                                                    <tr>
                                                        <th>Kode Produksi</th>
                                                        <th>Pegawai & Mesin</th>
                                                        <th>Total Jumlah</th>
                                                        <th>Detail Produk</th>
                                                        @foreach ($scheduleDates as $date)
                                                        <th style="{{ $date['is_holiday'] || $date['is_sunday'] ? 'background-color: #f8d7da; color: #721c24;' : '' }}">
                                                            {{ Carbon\Carbon::parse($date['date'])->format('d/m/Y') }}
                                                            @if ($date['is_holiday'])
                                                            <br>
                                                            <small>({{ $date['holiday_desc'] }})</small>
                                                            @endif
                                                            @if ($date['is_sunday'])
                                                            <br>
                                                            <small>(Minggu)</small>
                                                            @endif
                                                        </th>
                                                        @endforeach
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($processedOrders as $order)
                                                    @php
                                                    $orderTotalQuantity = array_sum(array_column($order['products'], 'total_quantity'));
                                                    $startDate = $order['start_date'];
                                                    $endDate = $order['end_date'];

                                                    // Create product list HTML
                                                    $productDetails = '<ul class="list-unstyled m-0">';
                                                        foreach ($order['products'] as $product) {
                                                        $productDetails .= '<li>' . $product['product_name'] . ' (' . $product['total_quantity'] . ' pcs)</li>';
                                                        }
                                                        $productDetails .= '</ul>';

                                                    // Initialize variables for progress segments
                                                    $progressSegments = [];
                                                    $currentSegment = null;
                                                    $beforeCells = [];
                                                    $afterCells = [];
                                                    $foundStart = false;
                                                    $foundEnd = false;
                                                    $totalWorkDays = 0;
                                                    $currentStartPercentage = 100;

                                                    // First pass: count total working days and identify date range
                                                    foreach ($scheduleDates as $date) {
                                                    $dateStr = $date['date'];
                                                    if ($dateStr >= $startDate && $dateStr <= $endDate) {
                                                        if (!$date['is_holiday'] && !$date['is_sunday']) {
                                                        $totalWorkDays++;
                                                        }
                                                        }
                                                        }

                                                        $progressPerDay=$totalWorkDays> 0 ? 100 / $totalWorkDays : 0;
                                                        $workDaysProcessed = 0;

                                                        // Second pass: build segments
                                                        foreach ($scheduleDates as $date) {
                                                        $dateStr = $date['date'];

                                                        if (!$foundStart) {
                                                        if ($dateStr == $startDate) {
                                                        $foundStart = true;
                                                        } else {
                                                        $beforeCells[] = $date;
                                                        continue;
                                                        }
                                                        }

                                                        if ($foundStart && !$foundEnd) {
                                                        if ($dateStr > $endDate) {
                                                        $foundEnd = true;
                                                        $afterCells[] = $date;
                                                        continue;
                                                        }
                                                        }

                                                        if ($foundEnd) {
                                                        $afterCells[] = $date;
                                                        continue;
                                                        }

                                                        if ($foundStart && !$foundEnd) {
                                                        if (!$date['is_holiday'] && !$date['is_sunday']) {
                                                        $workDaysProcessed++;
                                                        }

                                                        if ($currentSegment === null) {
                                                        $currentSegment = [
                                                        'type' => 'progress',
                                                        'count' => 1,
                                                        'startPercentage' => 100 - ($workDaysProcessed - 1) * $progressPerDay,
                                                        'dates' => [$date],
                                                        ];
                                                        } else {
                                                        $currentSegment['count']++;
                                                        $currentSegment['dates'][] = $date;
                                                        }
                                                        }
                                                        }

                                                        if ($currentSegment !== null) {
                                                        $progressSegments[] = $currentSegment;
                                                        }
                                                        @endphp
                                                        <tr>
                                                            <td>{{ $order['kode_pesanan'] }}</td>
                                                            <td>{{ $order['employee'] }} - {{ $order['machine'] }}</td>
                                                            <td>{{ $orderTotalQuantity }} pcs</td>
                                                            <td>{!! $productDetails !!}</td>

                                                            @foreach ($beforeCells as $date)
                                                            <td style="{{ $date['is_holiday'] || $date['is_sunday'] ? 'background-color: #f8d7da; color: #721c24;' : 'background-color: #f8f9fa;' }}">
                                                                -
                                                            </td>
                                                            @endforeach

                                                            @foreach ($progressSegments as $index => $segment)
                                                            <td colspan="{{ $segment['count'] }}">
                                                                <div style="{{ $date['is_holiday'] || $date['is_sunday'] ? 'background-color: #f8d7da;' : '' }} padding-top: 0.25rem; padding-bottom: 0.25rem;">
                                                                    <div class="d-flex flex-column" style="height: 100px;">
                                                                        @php
                                                                        $currentProgress = $segment['startPercentage'];
                                                                        $thresholds = [100, 80, 60, 40, 20];
                                                                        $colors = ['#007bff', '#28a745', '#ffc107', '#e95d1c', '#6c757d'];
                                                                        @endphp

                                                                        @foreach ($thresholds as $i => $threshold)
                                                                        @if ($currentProgress > $threshold - 20)
                                                                        <div class="progress" style="height: 20px; background: none; width: 100%; margin-left: {{ $i * 10 }}%;">
                                                                            <div class="progress-bar {{ $i > 0 ? 'mt-1' : '' }}" role="progressbar" style="width: 60%; background-color: {{ $colors[$i] }};{{ $i > 0 ? 'margin-top:2px !important' : '' }}">
                                                                                {{ $orderTotalQuantity }} pcs
                                                                            </div>
                                                                        </div>
                                                                        @else
                                                                        <div class="progress" style="height: 20px; background: none; width: 100%; margin-left: {{ $i * 10 }}%;">
                                                                        </div>
                                                                        @endif
                                                                        @endforeach
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            @endforeach

                                                            @foreach ($afterCells as $date)
                                                            <td style="{{ $date['is_holiday'] || $date['is_sunday'] ? 'background-color: #f8d7da; color: #721c24;' : 'background-color: #f8f9fa;' }}">
                                                                -
                                                            </td>
                                                            @endforeach
                                                        </tr>
                                                        @endforeach
                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <td colspan="3" style="font-weight:bold">Jumlah di produksi</td>
                                                        @foreach ($scheduleDates as $date)
                                                        <td style="{{ $date['is_holiday'] || $date['is_sunday'] ? 'background-color: #f8d7da; color: #721c24;' : '' }}font-weight:bold">
                                                            {{ $date['total_items'] }}
                                                        </td>
                                                        @endforeach
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
            @endsection
            @section('script')
            <script>
                $(document).ready(function() {
                    document.getElementById('submitBtn').addEventListener('click', function() {
                        const form = this.closest('form');
                        form.submit();
                    });
                });
            </script>
            @endsection
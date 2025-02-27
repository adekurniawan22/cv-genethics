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
                                                <th>Pengerjaan</th>
                                                <th>Total Jumlah</th>
                                                <th>Detail Produk</th>
                                                @foreach ($scheduleDates as $date)
                                                    <th
                                                        style="{{ $date['is_holiday'] || $date['is_sunday'] ? 'background-color: #f8d7da; color: #721c24;' : '' }}">
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
                                                    $orderTotalQuantity = array_sum(
                                                        array_column($order['products'], 'total_quantity'),
                                                    );
                                                    $startDate = $order['start_date'];
                                                    $endDate = $order['end_date'];

                                                    // Create product list HTML
                                                    $productDetails = '<ul class="list-unstyled m-0">';
                                                    foreach ($order['products'] as $product) {
                                                        $productDetails .=
                                                            '<li>' .
                                                            $product['product_name'] .
                                                            ' (' .
                                                            $product['total_quantity'] .
                                                            ' pcs)</li>';
                                                    }
                                                    $productDetails .= '</ul>';

                                                    // Format the employee and machine assignments with collapsible sections
                                                    $assignmentsByDate = [];
                                                    if (isset($order['assignments'])) {
                                                        foreach ($order['assignments'] as $date => $assignments) {
                                                            $formattedDate = Carbon\Carbon::parse($date)->format(
                                                                'd/m/Y',
                                                            );
                                                            if (!isset($assignmentsByDate[$formattedDate])) {
                                                                $assignmentsByDate[$formattedDate] = [];
                                                            }

                                                            foreach ($assignments as $assignment) {
                                                                $assignmentsByDate[$formattedDate][] = [
                                                                    'employee_id' => $assignment['employee_id'],
                                                                    'machine_id' => $assignment['machine_id'],
                                                                    'quantity' => $assignment['quantity'],
                                                                ];
                                                            }
                                                        }
                                                    }

                                                    $assignmentDetails = '';
                                                    $collapseId = 'collapse-' . $order['kode_pesanan'] . '-';
                                                    $counter = 0;

                                                    foreach ($assignmentsByDate as $date => $dayAssignments) {
                                                        $currentCollapseId = $collapseId . $counter;
                                                        $counter++;

                                                        $totalItemsOnDay = array_sum(
                                                            array_column($dayAssignments, 'quantity'),
                                                        );

                                                        $assignmentDetails .=
                                                            '
                                                            <div class="assignment-date mb-2">
                                                                <div class="date-badge px-2 py-1 rounded d-inline-flex align-items-center gap-2" 
                                                                    style="background-color: #e9ecef; cursor: pointer; user-select: none; text-align: left;"
                                                                    data-bs-toggle="collapse" 
                                                                    data-bs-target="#' .
                                                            $currentCollapseId .
                                                            '" 
                                                                    aria-expanded="false" 
                                                                    aria-controls="' .
                                                            $currentCollapseId .
                                                            '">
                                                                    <i class="bx bx-calendar"></i>
                                                                    <strong>' .
                                                            $date .
                                                            '</strong>
                                                                    <span class="badge bg-primary">' .
                                                            $totalItemsOnDay .
                                                            ' pcs</span>
                                                                    <i class="bx bx-chevron-down ms-1"></i>
                                                                </div>
                                                                
                                                                <div class="collapse" id="' .
                                                            $currentCollapseId .
                                                            '">
                                                                    <div class="assignment-items mt-1 ps-3">';

                                                        foreach ($dayAssignments as $assignment) {
                                                            $assignmentDetails .=
                                                                '
                                                                <div class="assignment-item py-1" style="border-left: 3px solid #007bff; padding-left: 8px; margin: 5px 0;">
                                                                    <div><i class="bx bx-user"></i> <strong>Pegawai ' .
                                                                $assignment['employee_id'] .
                                                                '</strong></div>
                                                                    <div class="ps-3">Mengerjakan <span class="badge bg-success">' .
                                                                $assignment['quantity'] .
                                                                ' pcs</span> menggunakan <span class="badge bg-info">Mesin ' .
                                                                $assignment['machine_id'] .
                                                                '</span></div>
                                                                </div>';
                                                        }

                                                        $assignmentDetails .= '
                                                                    </div>
                                                                </div>
                                                            </div>';
                                                    }

                                                    // If no assignments were found
                                                    if (empty($assignmentDetails)) {
                                                        $assignmentDetails =
                                                            '<div class="text-muted"><i>Belum ada pengerjaan</i></div>';
                                                    }

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

                                                    $progressPerDay = $totalWorkDays > 0 ? 100 / $totalWorkDays : 0;
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
                                                                    'startPercentage' =>
                                                                        100 -
                                                                        ($workDaysProcessed - 1) * $progressPerDay,
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
                                                    <td class="align-middle">
                                                        <span class="badge bg-dark">{{ $order['kode_pesanan'] }}</span>
                                                    </td>
                                                    <td class="align-middle" style="min-width: 250px">
                                                        {!! $assignmentDetails !!}</td>
                                                    <td class="align-middle">
                                                        <span class="badge bg-success">{{ $orderTotalQuantity }} pcs</span>
                                                    </td>
                                                    <td class="align-middle">{!! $productDetails !!}</td>

                                                    @foreach ($beforeCells as $date)
                                                        <td
                                                            style="{{ $date['is_holiday'] || $date['is_sunday'] ? 'background-color: #f8d7da; color: #721c24;' : 'background-color: #f8f9fa;' }}">
                                                            -
                                                        </td>
                                                    @endforeach

                                                    @foreach ($progressSegments as $index => $segment)
                                                        <td colspan="{{ $segment['count'] }}">
                                                            <div
                                                                style="{{ $date['is_holiday'] || $date['is_sunday'] ? 'background-color: #f8d7da;' : '' }} padding-top: 0.25rem; padding-bottom: 0.25rem;">
                                                                <div class="d-flex flex-column" style="height: 100px;">
                                                                    @php
                                                                        $currentProgress = $segment['startPercentage'];
                                                                        $thresholds = [100, 80, 60, 40, 20];
                                                                        $colors = [
                                                                            '#007bff',
                                                                            '#28a745',
                                                                            '#ffc107',
                                                                            '#e95d1c',
                                                                            '#6c757d',
                                                                        ];
                                                                    @endphp

                                                                    @foreach ($thresholds as $i => $threshold)
                                                                        @if ($currentProgress > $threshold - 20)
                                                                            <div class="progress"
                                                                                style="height: 20px; background: none; width: 100%; margin-left: {{ $i * 10 }}%;">
                                                                                <div class="progress-bar {{ $i > 0 ? 'mt-1' : '' }}"
                                                                                    role="progressbar"
                                                                                    style="width: 60%; background-color: {{ $colors[$i] }};{{ $i > 0 ? 'margin-top:2px !important' : '' }}">
                                                                                    {{ $orderTotalQuantity }} pcs
                                                                                </div>
                                                                            </div>
                                                                        @else
                                                                            <div class="progress"
                                                                                style="height: 20px; background: none; width: 100%; margin-left: {{ $i * 10 }}%;">
                                                                            </div>
                                                                        @endif
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                        </td>
                                                    @endforeach

                                                    @foreach ($afterCells as $date)
                                                        <td
                                                            style="{{ $date['is_holiday'] || $date['is_sunday'] ? 'background-color: #f8d7da; color: #721c24;' : 'background-color: #f8f9fa;' }}">
                                                            -
                                                        </td>
                                                    @endforeach
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="4" style="font-weight:bold">Jumlah di produksi</td>
                                                @foreach ($scheduleDates as $date)
                                                    <td
                                                        style="{{ $date['is_holiday'] || $date['is_sunday'] ? 'background-color: #f8d7da; color: #721c24;' : '' }}font-weight:bold">
                                                        {{ $date['total_items'] }}
                                                    </td>
                                                @endforeach
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>

                            <div class="tab-pane" id="tab2" role="tabpanel">
                                <div class="tab-pane active" id="detailPerhitungan" role="tabpanel">
                                    <div class="table-responsive mt-3" style="max-height: 600px !important;">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Kode Pesanan</th>
                                                    <th>Nama Pemesan</th>
                                                    <th>Tanggal Pesanan</th>
                                                    <th>Tanggal Pengiriman</th>
                                                    <th>Due Date</th>
                                                    <th>Detail Perhitungan</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($penjadwalan as $jadwal)
                                                    @php
                                                        $processedOrder =
                                                            $processedOrders[$jadwal->kode_pesanan] ?? null;
                                                    @endphp
                                                    <tr>
                                                        <td>{{ $jadwal->kode_pesanan }}</td>
                                                        <td>{{ $jadwal->nama_pemesan }}</td>
                                                        <td>{{ $jadwal->tanggal_pesanan }}</td>
                                                        <td>{{ $jadwal->tanggal_pengiriman }}</td>
                                                        <td>{{ $processedOrder['dueDate'] }} hari</td>
                                                        <td>
                                                            <ul class="list-unstyled">
                                                                @php
                                                                    $detailPerhitungan = json_decode(
                                                                        $jadwal->detail_perhitungan,
                                                                        true,
                                                                    );
                                                                @endphp
                                                                @foreach ($detailPerhitungan as $detail)
                                                                    <li>
                                                                        <strong>{{ $detail['product_name'] }}</strong>
                                                                        <ul>
                                                                            <li>Total Jumlah:
                                                                                {{ $detail['total_quantity'] }} pcs
                                                                            </li>
                                                                            <li>Tanggal Mulai: {{ $detail['start_date'] }}
                                                                            </li>
                                                                            <li>Tanggal Selesai:
                                                                                {{ $processedOrder['end_date'] }}
                                                                            </li>
                                                                            <li>
                                                                                <table
                                                                                    class="table table-bordered table-sm">
                                                                                    <thead>
                                                                                        <tr>
                                                                                            <th>Date</th>
                                                                                            <th>Scheduled Items</th>
                                                                                            <th>Completion Time</th>
                                                                                            <th>Lateness</th>
                                                                                        </tr>
                                                                                    </thead>
                                                                                    <tbody>
                                                                                        @foreach ($detail['scheduled_items'] as $date => $quantity)
                                                                                            <?php if($quantity > 0) :?>
                                                                                            <tr>
                                                                                                <td>{{ $date }}
                                                                                                </td>
                                                                                                <td>{{ $quantity }}
                                                                                                    pcs
                                                                                                </td>
                                                                                                <td>{{ $detail['completion_time'][$date] ?? '-' }}
                                                                                                </td>
                                                                                                <td>{{ $detail['lateness'][$date] ?? '-' }}
                                                                                                </td>
                                                                                            </tr>
                                                                                            <?php endif; ?>
                                                                                        @endforeach
                                                                                    </tbody>
                                                                                </table>
                                                                            </li>
                                                                        </ul>
                                                                    </li>
                                                                @endforeach
                                                            </ul>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
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

            // Toggle chevron icon when collapsible items are clicked
            $('.date-badge').on('click', function() {
                const icon = $(this).find('.bx-chevron-down, .bx-chevron-up');
                if (icon.hasClass('bx-chevron-down')) {
                    icon.removeClass('bx-chevron-down').addClass('bx-chevron-up');
                } else {
                    icon.removeClass('bx-chevron-up').addClass('bx-chevron-down');
                }
            });
        });
    </script>
@endsection

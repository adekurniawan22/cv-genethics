@extends('layout.main')
@section('content')
    <style>
        .small-label {
            font-size: 0.875rem;
        }

        .print---press {
            background-color: #007bff;
        }

        .cutting {
            background-color: #28a745;
        }

        .jahit---obras {
            background-color: #ffc107;
            color: black;
        }

        .finishing {
            background-color: #e95d1c;
        }

        .qc---packing {
            background-color: #6c757d;
        }

        #productionTable th,
        #productionTable td {
            text-align: center;
            vertical-align: middle;
            min-width: 50px;
            padding: 8px;
            border: 1px solid #dee2e6;
        }

        .loader-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }

        /* HTML: <div class="loader"></div> */
        .loader {
            width: 50px;
            aspect-ratio: 1;
            display: grid;
            -webkit-mask: conic-gradient(from 15deg, #0000, #000);
            animation: l26 1s infinite steps(12);
        }

        .loader,
        .loader:before,
        .loader:after {
            background:
                radial-gradient(closest-side at 50% 12.5%,
                    #f03355 96%, #0000) 50% 0/20% 80% repeat-y,
                radial-gradient(closest-side at 12.5% 50%,
                    #f03355 96%, #0000) 0 50%/80% 20% repeat-x;
        }

        .loader:before,
        .loader:after {
            content: "";
            grid-area: 1/1;
            transform: rotate(30deg);
        }

        .loader:after {
            transform: rotate(60deg);
        }

        @keyframes l26 {
            100% {
                transform: rotate(1turn)
            }
        }

        .progress {
            height: 25px;
        }

        .progress-bar-1 {
            background-color: #007bff;
        }

        .progress-bar-2 {
            background-color: #28a745;
        }

        .progress-bar-3 {
            background-color: #ffc107;
        }

        .progress-bar-4 {
            background-color: #17a2b8;
        }

        .progress-bar-5 {
            background-color: #6f42c1;
        }
    </style>
    {{-- <div id="loader" class="loader-container">
        <div class="loader"></div>
    </div> --}}
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
                                <div class="row g-3">
                                    <div class="col-12">
                                        <label for="date" class="col-form-label small-label">Tanggal Mulai:</label>
                                        <input type="date" name="date" id="date"
                                            class="form-control form-control-sm" placeholder="Pilih Tanggal"
                                            value="{{ request('date', \Carbon\Carbon::now('Asia/Jakarta')->format('Y-m-d')) }}">
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
                {{-- <div class="card radius-10">
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
                </div> --}}
            </div>
            <div class="col-10 pe-0">
                <div class="card radius-10">
                    <div class="card-body">
                        <div class="table-responsive" style="max-height: 500px !important;">
                            <table id="productionTable" class="table">
                                <thead>
                                    <tr>
                                        <th>Kode Produksi</th>
                                        <th>Total Jumlah</th>
                                        <th>Detail Produk</th>
                                        @foreach ($scheduleDates as $date)
                                            <th
                                                class="{{ $date['is_holiday'] || $date['is_sunday'] ? 'bg-danger text-white' : '' }}">
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

                                            // Initialize variables for progress segments
                                            $progressSegments = [];
                                            $currentSegment = null;
                                            $beforeCells = [];
                                            $afterCells = [];
                                            $foundStart = false;
                                            $foundEnd = false;
                                            $totalWorkDays = 0;
                                            $currentStartPercentage = 100; // Start at 100% for first bar

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
                                                    if ($date['is_holiday'] || $date['is_sunday']) {
                                                        if ($currentSegment !== null) {
                                                            $progressSegments[] = $currentSegment;
                                                            $currentSegment = null;
                                                        }
                                                        $progressSegments[] = [
                                                            'type' => 'non_working',
                                                            'date' => $date,
                                                        ];
                                                    } else {
                                                        if ($currentSegment === null) {
                                                            $currentSegment = [
                                                                'type' => 'progress',
                                                                'count' => 1,
                                                                'startPercentage' => $currentStartPercentage,
                                                                'barCount' => min(
                                                                    5,
                                                                    floor($currentStartPercentage / 20),
                                                                ),
                                                            ];
                                                            $totalWorkDays++;
                                                        } else {
                                                            $currentSegment['count']++;
                                                            $totalWorkDays++;
                                                        }
                                                    }
                                                }
                                            }

                                            if ($currentSegment !== null) {
                                                $progressSegments[] = $currentSegment;
                                            }

                                            // Calculate next segment's starting percentage
                                            $progressPerDay = 100 / $totalWorkDays;
                                        @endphp
                                        <tr>
                                            <td>{{ $order['kode_pesanan'] }}</td>
                                            <td>{{ $orderTotalQuantity }} pcs</td>
                                            <td>{!! $productDetails !!}</td>

                                            @foreach ($beforeCells as $date)
                                                <td
                                                    class="{{ $date['is_holiday'] || $date['is_sunday'] ? 'bg-danger text-white' : '' }}">
                                                    -</td>
                                            @endforeach

                                            @foreach ($progressSegments as $index => $segment)
                                                @if ($segment['type'] === 'non_working')
                                                    <td class="bg-danger text-white">-</td>
                                                @else
                                                    <td colspan="{{ $segment['count'] }}" class="indigo">
                                                        <div class="progress mb-1">
                                                            @for ($i = 1; $i <= $segment['barCount']; $i++)
                                                                <div class="progress-bar progress-bar-{{ $i }} bg-primary "
                                                                    role="progressbar"
                                                                    style="width: {{ $segment['startPercentage'] }}%">
                                                                </div>
                                                            @endfor
                                                        </div>
                                                    </td>
                                                    @php
                                                        if (
                                                            isset($progressSegments[$index + 1]) &&
                                                            $progressSegments[$index + 1]['type'] === 'progress'
                                                        ) {
                                                            $currentStartPercentage =
                                                                $segment['startPercentage'] -
                                                                $segment['count'] * $progressPerDay;
                                                        }
                                                    @endphp
                                                @endif
                                            @endforeach

                                            @foreach ($afterCells as $date)
                                                <td
                                                    class="{{ $date['is_holiday'] || $date['is_sunday'] ? 'bg-danger text-white' : '' }}">
                                                    -</td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="3" style="font-weight:bold">Jumlah di produksi</td>
                                        @foreach ($scheduleDates as $date)
                                            <td style="font-weight:bold"
                                                class="{{ $date['is_holiday'] || $date['is_sunday'] ? 'bg-danger text-white' : '' }}">
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

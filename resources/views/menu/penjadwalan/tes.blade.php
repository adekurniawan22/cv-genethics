@extends('layout.main')
@section('content')
    <style>
        .small-label {
            font-size: 0.875rem;
            /* Smaller font size for labels */
        }

        .gantt-chart {
            width: 100%;
            padding: 5px;
            position: relative;
        }

        .gantt-bar {
            height: 20px;
            padding: 0px;
            margin-bottom: 2px;
            position: absolute;
        }

        .print---press {
            margin-top: 0px;
        }

        .cutting {
            margin-top: 22px;
        }

        .jahit---obras {
            margin-top: 44px;
        }

        .finishing {
            margin-top: 66px;
        }

        .qc---packing {
            margin-top: 88px;
        }

        .gantt-bar:hover::after {
            content: attr(data-tooltip);
            position: absolute;
            z-index: 1;
            bottom: 100%;
            left: 50%;
            transform: translateX(-50%);
            padding: 8px;
            background-color: rgba(0, 0, 0, 0.9);
            color: white;
            font-size: 12px;
            white-space: pre-line;
            border-radius: 4px;
            line-height: 1.5;
            width: max-content;
            max-width: 300px;
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

        .hour-cell {
            font-size: 10px;
            padding: 2px !important;
            height: 25px;
        }

        .gantt-cell {
            padding: 0 !important;
            padding-top: 50px;
            position: relative;
            height: 110px;
            vertical-align: top !important;
            border-right: none !important;
            border-left: none !important;
            /* border: none !important; */
        }

        .gantt-cell-bordered {
            border-left: 1px solid #dee2e6 !important;
        }

        .time-marker {
            position: absolute;
            width: 1px;
            height: 100%;
            background-color: #dee2e6;
            z-index: 1;
        }

        .holiday-cell {
            background-color: #ffebee !important;
        }

        .sunday-cell {
            background-color: #fff3e0 !important;
        }

        .holiday-label {
            font-size: 10px;
            color: #f44336;
            display: block;
            margin-top: -5px;
        }

        .sunday-label {
            font-size: 10px;
            color: #ff9800;
            display: block;
            margin-top: -5px;
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
    </style>
    <div id="loader" class="loader-container">
        <div class="loader"></div>
    </div>
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
                        <div class="table-responsive" style="max-height: 500px !important;">
                            <table id="productionTable" class="table">
                                <thead>
                                    <tr>
                                        <th>Pemesan</th>
                                        <th>Tanggal Pesanan</th>
                                        <th>Produk</th>
                                        <th>Jumlah</th>
                                        <th>Batas Waktu</th>
                                        <th>Hari Pengerjaan</th>
                                        <th>Status</th>
                                        <th>Gantt Chart</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
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
        let HOLIDAYS = {};

        const role = '{{ session()->get('role') }}';
        const base_url = '{{ url('/') }}';

        const TIME_PER_PRODUCT = {
            "Print & Press": 2,
            Cutting: 2,
            "Jahit & Obras": 3,
            Finishing: 1,
            "QC & Packing": 1,
        };

        const BREAK_TIME = 5;
        const START_HOUR = 8;
        const END_HOUR = 17;

        let orderData = [];


        document.addEventListener("DOMContentLoaded", async function() {
            const loader = document.getElementById('loader');
            loader.style.display = 'flex';
            try {
                const holidaysResponse = await fetch(`${base_url}/${role}/get-hari-libur?penjadwalan=true`);
                if (!holidaysResponse.ok) {
                    throw new Error(`HTTP error! Status: ${holidaysResponse.status}`);
                }
                HOLIDAYS = await holidaysResponse.json();

                // HOLIDAYS['2025-02-10'] = 'ULANG TAHUN';

                const getUrlParameter = (name) => {
                    const urlParams = new URLSearchParams(window.location.search);
                    return urlParams.get(name);
                };

                // Ambil tanggal dari URL atau gunakan tanggal hari ini
                const dateParam = getUrlParameter('date');
                const today = new Date();
                let selectedDate;

                if (dateParam) {
                    selectedDate = new Date(dateParam);
                    if (isNaN(selectedDate.getTime())) {
                        selectedDate = today;
                    }
                } else {
                    selectedDate = today;
                }

                // Set value input date sesuai dengan tanggal yang dipilih
                const dateInput = document.getElementById('date');
                dateInput.value = selectedDate.toISOString().split('T')[0];

                const response = await fetch(`${base_url}/${role}/orders-edd`);

                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }

                orderData = await response.json();

                if (!Array.isArray(orderData) || orderData.length === 0) {
                    console.error("Data pesanan kosong atau bukan array.");
                    return;
                }

                const sortedOrders = [...orderData].sort(
                    (a, b) => new Date(a.dueDate) - new Date(b.dueDate)
                );

                // Fungsi untuk menginisialisasi schedule dengan tanggal tertentu
                const initializeWithDate = (date) => {
                    const scheduleStartTime = new Date(
                        date.getFullYear(),
                        date.getMonth(),
                        date.getDate(),
                        START_HOUR,
                        0,
                        0
                    );
                    initializeSchedule(scheduleStartTime);
                };

                // Inisialisasi awal
                initializeWithDate(selectedDate);

                // Event listener untuk tombol Jadwalkan
                const scheduleButton = document.getElementById('submitBtn');
                if (scheduleButton) {
                    scheduleButton.addEventListener('click', function(e) {
                        e.preventDefault(); // Mencegah form submit default
                        const dateInput = document.getElementById('date');
                        const newDate = new Date(dateInput.value);
                        if (!isNaN(newDate.getTime())) {
                            initializeWithDate(newDate);
                        }
                    });
                }

            } catch (error) {
                console.error("Error fetching orders:", error);
            } finally {
                loader.style.display = 'none';
            }
        });

        function isHoliday(date) {
            const dateString = date.toISOString().split('T')[0];
            return dateString in HOLIDAYS;
        }

        // Helper function untuk mendapatkan judul hari libur
        function getHolidayTitle(date) {
            const dateString = date.toISOString().split('T')[0];
            return HOLIDAYS[dateString] || 'LIBUR';
        }

        function isSunday(date) {
            return date.getDay() === 0;
        }

        function moveToNextWorkDay(date) {
            const nextDay = new Date(date);
            nextDay.setDate(nextDay.getDate() + 1);
            nextDay.setHours(START_HOUR, 0, 0, 0);

            // Loop sampai menemukan hari kerja
            while (isSunday(nextDay) || isHoliday(nextDay)) {
                nextDay.setDate(nextDay.getDate() + 1);
            }

            return nextDay;
        }

        function formatDateTime(date) {
            return `${date.getFullYear()}-${String(date.getMonth() + 1).padStart(2, "0")}-${String(date.getDate()).padStart(
                    2,
                    "0"
                )} ${String(date.getHours()).padStart(2, "0")}:${String(date.getMinutes()).padStart(2, "0")}`;
        }

        function calculateStageTimes(startTime, duration) {
            let currentTime = new Date(startTime);
            let timeSegments = [];
            let remainingDuration = duration;

            while (remainingDuration > 0) {
                if (isSunday(currentTime) || isHoliday(currentTime)) {
                    currentTime = moveToNextWorkDay(currentTime);
                    continue;
                }

                const dayEndMinutes = END_HOUR * 60 - (currentTime.getHours() * 60 + currentTime.getMinutes());

                if (dayEndMinutes >= remainingDuration) {
                    const segmentEnd = new Date(currentTime.getTime() + remainingDuration * 60000);
                    timeSegments.push({
                        start: new Date(currentTime),
                        end: segmentEnd,
                    });
                    remainingDuration = 0;
                } else {
                    const dayEnd = new Date(currentTime);
                    dayEnd.setHours(END_HOUR, 0, 0, 0);

                    timeSegments.push({
                        start: new Date(currentTime),
                        end: dayEnd,
                    });

                    remainingDuration -= dayEndMinutes;
                    currentTime = moveToNextWorkDay(currentTime);
                }
            }

            return timeSegments;
        }

        function generateTooltip(order, stage, timeSegments) {
            if (timeSegments.length === 1) {
                return `${order.name} - ${stage}:\n${formatDateTime(timeSegments[0].start)} - ${formatDateTime(
                        timeSegments[0].end
                    )}`;
            }

            let tooltip = `${order.name} - ${stage}:\n${formatDateTime(timeSegments[0].start)} - ${formatDateTime(
                    timeSegments[0].end
                )}`;
            for (let i = 1; i < timeSegments.length; i++) {
                tooltip += `\nlanjut: ${formatDateTime(timeSegments[i].start)} - ${formatDateTime(timeSegments[i].end)}`;
            }
            return tooltip;
        }

        function processOrders() {
            return [...orderData].sort((a, b) => new Date(a.dueDate) - new Date(b.dueDate));
        }

        function calculateMidPoint(timeSegments) {
            if (!timeSegments || timeSegments.length === 0) return null;

            // Hitung total durasi dalam milidetik
            let totalDuration = 0;
            timeSegments.forEach((segment) => {
                totalDuration += segment.end.getTime() - segment.start.getTime();
            });

            // Hitung titik tengah dalam milidetik
            const midPointTime = timeSegments[0].start.getTime() + totalDuration / 2;
            const midPoint = new Date(midPointTime);

            // Jika midpoint jatuh di luar jam kerja, sesuaikan ke hari kerja berikutnya
            if (midPoint.getHours() >= END_HOUR || midPoint.getHours() < START_HOUR) {
                return moveToNextWorkDay(midPoint);
            }

            return midPoint;
        }

        function generateGanttChart(order) {
            const stages = ["Print & Press", "Cutting", "Jahit & Obras", "Finishing", "QC & Packing"];
            const ganttData = [];
            let currentTime = new Date(order.startTime);
            let firstDay = null;
            let lastDay = null;
            let previousStageSegments = null;

            stages.forEach((stage, index) => {
                const totalQuantity = order.products.reduce((sum, prod) => sum + prod.quantity, 0);
                const duration = totalQuantity * TIME_PER_PRODUCT[stage];

                let stageStart;
                if (index === 0) {
                    // Stage pertama mulai sesuai waktu awal
                    stageStart = new Date(currentTime);
                    if (stageStart.getHours() >= END_HOUR || stageStart.getHours() < START_HOUR) {
                        stageStart = moveToNextWorkDay(stageStart);
                    }
                } else if (stage === "Finishing") {
                    // Khusus untuk Finishing, mulai dari akhir stage Jahit & Obras
                    stageStart = new Date(previousStageSegments[previousStageSegments.length - 1].end);
                    if (stageStart.getHours() >= END_HOUR || stageStart.getHours() < START_HOUR) {
                        stageStart = moveToNextWorkDay(stageStart);
                    }
                } else {
                    // Stage lainnya mulai dari tengah stage sebelumnya
                    stageStart = calculateMidPoint(previousStageSegments);
                    if (!stageStart) {
                        stageStart = new Date(currentTime);
                        if (stageStart.getHours() >= END_HOUR || stageStart.getHours() < START_HOUR) {
                            stageStart = moveToNextWorkDay(stageStart);
                        }
                    }
                }

                currentTime = new Date(stageStart);

                // Hitung timeSegments untuk stage ini
                const timeSegments = calculateStageTimes(stageStart, duration);
                previousStageSegments = timeSegments;

                const tooltip = generateTooltip(order, stage, timeSegments);
                const stageEnd = timeSegments[timeSegments.length - 1].end;

                if (!firstDay) firstDay = stageStart;
                lastDay = stageEnd;

                ganttData.push({
                    name: order.name,
                    stage,
                    timeSegments,
                    tooltip: tooltip,
                });

                // Set waktu saat ini ke akhir stage untuk referensi
                currentTime = new Date(stageEnd.getTime() + BREAK_TIME * 60 * 1000);
            });

            const workingDays = Math.ceil((lastDay - firstDay) / (1000 * 60 * 60 * 24)) + 1;
            const isLate = lastDay > new Date(order.dueDate);

            return {
                ganttData,
                workingDays,
                isLate,
                endTime: lastDay,
            };
        }

        function displayProductionTable(orders) {
            const tableBody = document.querySelector("#productionTable tbody");
            const tableHeader = document.querySelector("#productionTable thead");
            tableBody.innerHTML = "";
            tableHeader.innerHTML = "";

            // Find first and last day from all orders
            let firstDay = new Date(orders[0].startTime);
            let lastDay = new Date(orders[0].ganttInfo.endTime);
            orders.forEach((order) => {
                if (new Date(order.startTime) < firstDay) firstDay = new Date(order.startTime);
                if (new Date(order.ganttInfo.endTime) > lastDay) lastDay = new Date(order.ganttInfo.endTime);
            });

            // Create array of all days
            const days = [];
            let currentDay = new Date(firstDay);
            while (currentDay <= lastDay) {
                days.push(new Date(currentDay));
                currentDay.setDate(currentDay.getDate() + 1);
            }

            // Create header rows
            const headerRow1 = document.createElement("tr");
            const headerRow2 = document.createElement("tr");

            // Add basic columns
            const basicColumns = [
                "Kode Produksi",
                // "Tanggal Pesanan",
                "Jumlah",
                "Detail Produk",
                // "Batas Waktu",
                // "Hari Pengerjaan",
                // "Status",
            ];
            basicColumns.forEach((col) => {
                const th = document.createElement("th");
                th.textContent = col;
                th.rowSpan = 2;
                headerRow1.appendChild(th);
            });

            // Add date columns
            days.forEach((day) => {
                const dateHeader = document.createElement("th");
                dateHeader.colSpan = END_HOUR - START_HOUR + 1;

                // Tambahkan class untuk styling
                if (isSunday(day)) {
                    dateHeader.classList.add("sunday-cell");
                    const label = document.createElement("span");
                    label.classList.add("sunday-label");
                    label.textContent = "MINGGU";
                    dateHeader.appendChild(label);
                } else if (isHoliday(day)) {
                    dateHeader.classList.add("holiday-cell");
                    const label = document.createElement("span");
                    label.classList.add("holiday-label");
                    label.textContent = getHolidayTitle(day);
                    dateHeader.appendChild(label);
                }

                dateHeader.insertAdjacentHTML('afterbegin',
                    day.toLocaleDateString("id-ID", {
                        day: "2-digit",
                        month: "long",
                        year: "numeric"
                    })
                );

                headerRow1.appendChild(dateHeader);
            });

            tableHeader.appendChild(headerRow1);
            tableHeader.appendChild(headerRow2);

            // Display orders
            let i = 1;
            orders.forEach((order) => {
                let totalQuantity = order.products.reduce((sum, product) => sum + product.quantity, 0);

                order.products.forEach((product, index) => {
                    const row = document.createElement("tr");

                    // Basic information columns
                    if (index === 0) {
                        const basicCells = [{
                                value: order.kode_pesanan + `-${i}`,
                                rowspan: true
                            },
                            // {
                            //     value: new Date(order.orderDate).toLocaleDateString("id-ID", {
                            //         day: "2-digit", // Menampilkan tanggal dengan dua digit
                            //         month: "long", // Menampilkan nama bulan dalam format panjang (misal, 'Februari')
                            //         year: "numeric" // Menampilkan tahun dengan angka penuh (misal, '2025')
                            //     }),
                            //     rowspan: true
                            // },
                        ];

                        basicCells.forEach((cell) => {
                            const td = document.createElement("td");
                            td.textContent = cell.value;
                            if (cell.rowspan) td.setAttribute("rowspan", order.products.length);
                            row.appendChild(td);
                        });
                    }

                    // Product details
                    const productCells = [{
                        value: product.name + ` (${product.quantity} pcs)`
                    }];

                    productCells.forEach((cell) => {
                        const td = document.createElement("td");
                        td.textContent = cell.value;
                        row.appendChild(td);
                    });

                    if (index === 0) {
                        const totalQuantityCell = document.createElement("td");
                        totalQuantityCell.textContent = totalQuantity;
                        totalQuantityCell.setAttribute("rowspan", order.products.length);
                        row.insertBefore(totalQuantityCell, row.lastChild);
                    }

                    if (index === 0) {
                        // Status cells
                        const statusCells = [
                            // {
                            //     value: new Date(order.dueDate).toLocaleDateString("id-ID", {
                            //         day: "2-digit",
                            //         month: "long",   
                            //         year: "numeric"
                            //     }),
                            //     rowspan: true
                            // },
                            // {
                            //     value: order.ganttInfo.workingDays - 1,
                            //     rowspan: true  
                            // },
                            // {
                            //     value: order.ganttInfo.isLate ? "Telat" : "Tepat Waktu",
                            //     rowspan: true
                            // },
                        ];

                        statusCells.forEach((cell) => {
                            const td = document.createElement("td");
                            td.textContent = cell.value;
                            if (cell.rowspan) td.setAttribute("rowspan", order.products.length);
                            row.appendChild(td);
                        });

                        // Gantt chart cells
                        days.forEach((day, dayIndex) => {
                            const dayStart = new Date(day);
                            dayStart.setHours(START_HOUR, 0, 0, 0);

                            for (let hour = START_HOUR; hour <= END_HOUR; hour++) {
                                const td = document.createElement("td");
                                td.classList.add("gantt-cell");
                                td.setAttribute("rowspan", order.products.length);

                                // Tambahkan class untuk hari Minggu dan hari libur
                                if (isSunday(day)) {
                                    td.classList.add("sunday-cell");
                                } else if (isHoliday(day)) {
                                    td.classList.add("holiday-cell");
                                }

                                if (hour === START_HOUR && dayIndex > 0) {
                                    td.classList.add("gantt-cell-bordered");
                                }

                                // Add gantt bars for this hour
                                order.ganttInfo.ganttData.forEach((item) => {
                                    item.timeSegments.forEach((segment) => {
                                        const segmentStart = new Date(segment
                                            .start);
                                        const segmentEnd = new Date(segment.end);

                                        const currentHourStart = new Date(day);
                                        currentHourStart.setHours(hour, 0, 0, 0);
                                        const currentHourEnd = new Date(
                                            currentHourStart);
                                        currentHourEnd.setHours(hour, 59, 59, 999);

                                        // Cek apakah segment berada dalam jam ini
                                        if (
                                            segmentStart.getTime() <= currentHourEnd
                                            .getTime() &&
                                            segmentEnd.getTime() >= currentHourStart
                                            .getTime()
                                        ) {
                                            const bar = document.createElement(
                                                "div");
                                            bar.classList.add(
                                                "gantt-bar",
                                                item.stage.toLowerCase()
                                                .replace(/[^a-z0-9]/g, "-")
                                            );
                                            bar.setAttribute("data-tooltip", item
                                                .tooltip);
                                            // bar.textContent = item.stage;

                                            // Calculate position and width
                                            let startPos = 0;
                                            if (segmentStart > currentHourStart) {
                                                startPos = (segmentStart
                                                    .getMinutes() / 60) * 100;
                                            }

                                            let endPos = 100;
                                            if (segmentEnd <= currentHourEnd) {
                                                endPos = (segmentEnd.getMinutes() /
                                                    60) * 100;
                                                // Jika berakhir tepat di jam 00 menit, set ke 100%
                                                if (segmentEnd.getMinutes() === 0 &&
                                                    segmentEnd.getHours() === 17) {
                                                    endPos = 100;
                                                }
                                            }

                                            bar.style.left = startPos + "%";
                                            bar.style.width = endPos - startPos +
                                                "%";

                                            td.appendChild(bar);
                                        }
                                    });
                                });

                                row.appendChild(td);
                            }
                        });
                    }

                    tableBody.appendChild(row);
                });
                i++;
            });
        }

        function initializeSchedule(initialStartTime) {
            const processedOrders = processOrders().reduce((acc, order, index) => {
                let startTime;

                if (index === 0) {
                    // First order starts at the selected time
                    startTime = new Date(initialStartTime);
                    if (startTime.getHours() >= END_HOUR || startTime.getHours() < START_HOUR) {
                        startTime = moveToNextWorkDay(startTime);
                    }
                } else {
                    // Get the end time of previous order and add break time
                    const prevOrder = acc[acc.length - 1];
                    startTime = new Date(prevOrder.ganttInfo.endTime);
                    startTime = new Date(startTime.getTime() + BREAK_TIME * 60 * 1000);

                    // Check working hours after adding break
                    if (startTime.getHours() >= END_HOUR || startTime.getHours() < START_HOUR) {
                        startTime = moveToNextWorkDay(startTime);
                    }
                }

                const processedOrder = {
                    ...order,
                    startTime,
                    ganttInfo: generateGanttChart({
                        ...order,
                        startTime
                    }),
                };

                return [...acc, processedOrder];
            }, []);

            displayProductionTable(processedOrders);
        }
    </script>
@endsection

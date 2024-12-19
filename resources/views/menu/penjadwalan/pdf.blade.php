<!DOCTYPE html>
<html>

<head>
    <title>PDF {{ isset($title) ? $title : 'Laporan' }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table,
        th,
        td {
            border: 1px solid #ddd;
        }

        th,
        td {
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f8f9fa;
        }

        .badge {
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 11px;
            color: white;
        }

        .bg-info {
            background-color: #17a2b8;
        }

        .bg-warning {
            background-color: #ffc107;
            color: black;
        }

        .bg-danger {
            background-color: #dc3545;
        }

        .bg-success {
            background-color: #28a745;
        }

        .no-data {
            text-align: center;
            color: #6c757d;
            padding: 20px;
            background-color: #f8f9fa;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>{{ isset($title) ? $title : 'Laporan' }}</h1>
        <p style="margin-top: -10px">
            @if (isset($jumlahSchedule) && isset($tanggal))
                Telah dijadwalkan {{ $jumlahSchedule }} pesanan pada tanggal {{ $tanggal }}
                <br>
                Dari rentang waktu <strong>{{ $startDate }}</strong> hingga <strong>{{ $endDate }}</strong>
            @else
                Tidak ada informasi jadwal yang tersedia.
            @endif
        </p>
    </div>

    @if (isset($schedule) && count($schedule) > 0)
        <h2>1. JADWAL PRODUKSI PER PESANAN</h2>
        <table>
            <thead>
                <tr>
                    <th style="width: 5%">No.</th>
                    <th>Pesanan</th>
                    <th>Batas Pengiriman</th>
                    <th>Waktu Produksi</th>
                    <th>Prediksi Keterlambatan</th>
                    <th>Penggunaan Mesin</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($schedule as $index => $item)
                    <tr>
                        <td style="vertical-align: top;">{{ $index + 1 }}.</td>
                        <td style="vertical-align: top;">
                            <strong>{{ $item['kode_pesanan'] ?? 'Kode Pesanan Tidak Tersedia' }}</strong><br>
                            <small>{{ $item['channel'] ?? 'Saluran Tidak Tersedia' }}</small><br>
                            <ul style="margin: 0; padding-left: 15px;">
                                @if (isset($item['products']) && count($item['products']) > 0)
                                    @foreach ($item['products'] as $product)
                                        <li><small>{{ $product['nama_produk'] ?? 'Nama Produk Tidak Tersedia' }}
                                                ({{ $product['jumlah'] ?? '0' }})
                                            </small></li>
                                    @endforeach
                                @else
                                    <li><small>Tidak ada produk yang terdaftar</small></li>
                                @endif
                            </ul>
                        </td>
                        <td style="vertical-align: top;">
                            {{ $item['tanggal_pengiriman_asli'] ?? 'Tanggal Tidak Tersedia' }}<br>
                            <small>({{ $item['batas_hari_pengiriman'] ?? '0' }} hari lagi)</small>
                        </td>
                        <td style="vertical-align: top;">
                            @if (isset($item['completion_time']))
                                {{ $item['completion_time']['tanggal'] ?? 'Tanggal Tidak Tersedia' }}<br>
                                <small>({{ $item['completion_time']['hari'] ?? '0' }} hari pengerjaan)</small>
                            @else
                                Informasi Waktu Produksi Tidak Tersedia
                            @endif
                        </td>
                        <td style="vertical-align: top;">
                            @if (isset($item['keterlambatan']))
                                @if (($item['keterlambatan']['hari'] ?? 0) > 0)
                                    <span class="badge bg-danger"
                                        style="display: inline-block; text-align: center; width: auto; padding: 8px 12px; border-radius: 3px;">
                                        <div>{{ $item['keterlambatan']['tanggal'] ?? 'Tanggal Tidak Tersedia' }}
                                        </div>
                                        <div>(telat {{ $item['keterlambatan']['hari'] ?? '0' }} hari)</div>
                                    </span>
                                @else
                                    <span class="badge bg-success"
                                        style="display: inline-block; text-align: center; width: auto; padding: 8px 12px; border-radius: 3px;">
                                        Tepat Waktu
                                    </span>
                                @endif
                            @else
                                Informasi Keterlambatan Tidak Tersedia
                            @endif
                        </td>
                        <td style="vertical-align: top;">
                            @if (isset($item['penggunaan_mesin']) && count($item['penggunaan_mesin']) > 0)
                                @foreach ($item['penggunaan_mesin'] as $date => $usage)
                                    <strong>{{ $date }}</strong>
                                    <ul style="list-style-type: none; margin: 0; padding-left: 15px;">
                                        @foreach ($usage as $machine)
                                            <li>- {{ $machine['nama_mesin'] ?? 'Nama Mesin Tidak Tersedia' }}:
                                                {{ $machine['kapasitas_terpakai'] ?? '0' }} unit</li>
                                        @endforeach
                                    </ul>
                                @endforeach
                            @else
                                Tidak ada informasi penggunaan mesin
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="no-data">
            <p>Tidak ada jadwal yang tersedia untuk ditampilkan.</p>
        </div>
    @endif
</body>

</html>

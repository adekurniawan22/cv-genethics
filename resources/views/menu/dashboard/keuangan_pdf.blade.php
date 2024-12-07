<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PDF LAPORAN TAHUNAN {{ $year }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        .header {
            text-align: center;
            margin-bottom: 50px;
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
        <h1>Laporan Penjualan Tahun {{ $year }}</h1>
    </div>

    <h2>Pendapatan Bulanan</h2>
    @if (isset($reportData['monthly_revenues']) && count($reportData['monthly_revenues']) > 0)
        <table>
            <thead>
                <tr>
                    <th>Bulan</th>
                    <th>Total Pendapatan (Rp)</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($reportData['monthly_revenues'] as $revenue)
                    <tr>
                        <td>{{ $revenue['month_name'] }}</td>
                        <td>Rp. {{ number_format($revenue['total_revenue'], 0, ',', '.') }}</td>
                    </tr>
                @endforeach
                <tr>
                    <td style="font-weight: bold">Total</td>
                    <td style="font-weight: bold">Rp.
                        {{ number_format($reportData['total_annual_revenue'], 0, ',', '.') }}
                    </td>
                </tr>
            </tbody>
        </table>
    @else
        <div class="no-data">
            <p>Tidak ada data pendapatan bulanan yang tersedia.</p>
        </div>
    @endif

    <h2>Produk Terlaris</h2>
    @if (isset($reportData['top_products']) && count($reportData['top_products']) > 0)
        <table>
            <thead>
                <tr>
                    <th>Nama Produk</th>
                    <th>Total Terjual</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($reportData['top_products'] as $product)
                    <tr>
                        <td>{{ $product['name'] }}</td>
                        <td>{{ $product['total_sold'] }} pcs</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="no-data">
            <p>Tidak ada data produk terlaris yang tersedia.</p>
        </div>
    @endif
</body>

</html>

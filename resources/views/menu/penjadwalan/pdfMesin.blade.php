<!DOCTYPE html>
<html>

<body>
    @if (isset($schedule) && count($schedule) > 0)
        @php

            $dateChunks = array_chunk($allDates, 10);

        @endphp
        <h2>2. JADWAL PRODUKSI PER MESIN DAN PER PRODUK </h2>

        @foreach ($dateChunks as $chunkIndex => $chunk)
            @if (count($allDates) > 9)
                <h3> 2.{{ $chunkIndex + 1 }}. Tabel dimulai dari
                    <em>{{ Carbon\Carbon::parse($chunk[0])->locale('id')->translatedFormat('d F Y') }}</em>
                    hingga <em>{{ Carbon\Carbon::parse(end($chunk))->locale('id')->translatedFormat('d F Y') }}</em>
                </h3>
            @endif


            @php
                $lastMesin = null;
            @endphp

            <table style="width: {{ count($dateChunks) > 10 ? '100%' : 'auto' }}">
                <thead>
                    <tr>
                        <th>Mesin</th>
                        <th>Produk</th>
                        <th style="text-align: center">Total</th>
                        @foreach ($chunk as $date)
                            <th style="text-align: center">
                                {{ Carbon\Carbon::parse($date)->locale('id')->translatedFormat('d M Y') }}
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach ($schedule as $mesinId => $mesinData)
                        @foreach ($uniqueProducts as $produkId => $produkData)
                            <tr>
                                <td
                                    style=" width: 80px;
                                @if ($lastMesin != $mesinData['nama_mesin']) border-top: 1px solid #ddd; 
                                    border-bottom: 0px !important;
                                @else
                                    border-top: 0px !important;
                                    border-bottom: 0px !important; @endif
                            ">
                                    @if ($lastMesin != $mesinData['nama_mesin'])
                                        {{ $mesinData['nama_mesin'] }}
                                        @php
                                            $lastMesin = $mesinData['nama_mesin'];
                                        @endphp
                                    @else
                                        <!-- Kosong jika mesin sama -->
                                    @endif
                                </td>
                                <td style=" width: 100px;">{{ $produkData['nama_produk'] }}</td>
                                <td style=" width: 70px; text-align:center">
                                    @if (isset($mesinData['produk'][$produkId]))
                                        {{ $mesinData['produk'][$produkId]['total_item'] }} item
                                    @endif
                                </td>
                                @foreach ($chunk as $date)
                                    <td
                                        style="text-align: center; 
                                    @if (isset($mesinData['produk'][$produkId]['tanggal_produksi'][$date])) background-color: green; color: white;
                                    @elseif (array_key_exists(Carbon\Carbon::parse($date)->format('Y-m-d'), $hariLibur)) 
                                        background-color: red; color: white; 
                                    @elseif (Carbon\Carbon::parse($date)->isSunday()) 
                                        background-color: red; color: white;
                                    @else 
                                        background-color: #f6f6f6; @endif">
                                        @if (isset($mesinData['produk'][$produkId]['tanggal_produksi'][$date]))
                                            @if (count($mesinData['produk'][$produkId]['tanggal_produksi'][$date]['pesanan_details']) > 1)
                                                @php
                                                    $jumlahCountDua = 0;
                                                @endphp
                                                @foreach ($mesinData['produk'][$produkId]['tanggal_produksi'][$date]['pesanan_details'] as $pesananDetail)
                                                    @php
                                                        $jumlahCountDua += $pesananDetail['jumlah'];
                                                    @endphp
                                                @endforeach
                                                {{ $jumlahCountDua }} item
                                            @else
                                                @foreach ($mesinData['produk'][$produkId]['tanggal_produksi'][$date]['pesanan_details'] as $pesananDetail)
                                                    <span>
                                                        {{ $pesananDetail['jumlah'] }} item
                                                    </span>
                                                @endforeach
                                            @endif
                                        @elseif (array_key_exists(Carbon\Carbon::parse($date)->format('Y-m-d'), $hariLibur))
                                            {{ $hariLibur[Carbon\Carbon::parse($date)->format('Y-m-d')] }}
                                        @elseif (Carbon\Carbon::parse($date)->isSunday())
                                            Hari Minggu
                                        @else
                                            -
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    @endforeach
                </tbody>
            </table>

            @if (!$loop->last)
                <div style="page-break-before: always;"></div>
            @endif
        @endforeach
    @else
        <div class="no-data">
            <p>Tidak ada jadwal yang tersedia untuk ditampilkan.</p>
        </div>
    @endif

</body>

</html>

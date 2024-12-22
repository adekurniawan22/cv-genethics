@extends('layout.main')
@section('content')
    <style>
        .small-label {
            font-size: 0.875rem;
            /* Smaller font size for labels */
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
                <div class="card radius-10">
                    <div class="card-body">
                        <div class="align-items-center gap-2 ms-0" style="max-width:500px">
                            <form action="{{ route(session()->get('role') . '.penjadwalan.index') }}" method="GET">
                                <div class="row g-3">
                                    <!-- Tanggal Mulai -->
                                    <div class="col-12">
                                        <label for="date" class="col-form-label small-label">Tanggal Mulai:</label>

                                        <input type="date" name="date" id="date"
                                            class="form-control form-control-sm" placeholder="Pilih Tanggal"
                                            value="{{ request('date', \Carbon\Carbon::now('Asia/Jakarta')->format('Y-m-d')) }}">
                                    </div>
                                </div>

                                <div class="row g-3 mb-2">
                                    <!-- Jumlah Pesanan -->
                                    <div class="col-12">
                                        <label for="limit" class="col-form-label small-label">Jumlah Pesanan:</label>
                                        <input type="number" name="limit" id="limit"
                                            class="form-control form-control-sm" placeholder="Jumlah data"
                                            value="{{ request('limit', 50) }}" min="1">
                                        <div class="row mt-2 g-1">
                                            <div class="col-12">
                                                <a href="javascript:void(0);" id="submitBtn"
                                                    class="btn btn-primary w-100">Jadwalkan</a>

                                            </div>
                                            <div class="col-12">
                                                <a id="downloadPDF"
                                                    href="{{ route(session()->get('role') . '.penjadwalan.pdf', ['limit' => request('limit', 50), 'date' => request('date', \Carbon\Carbon::now('Asia/Jakarta')->format('Y-m-d'))]) }}"
                                                    target="_blank" class="btn btn-danger mr-2 w-100">
                                                    <i class="lni lni-download"></i> PDF
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-10 pe-0">
                <div class="card radius-10">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-sm">
                                <thead>
                                    <tr>
                                        <th class="py-3 px-4 bg-dark text-white border-light">Mesin</th>
                                        <th class="py-3 px-4 bg-dark text-white border-light">Produk</th>
                                        <th class="py-3 px-4 bg-dark text-white border-light text-center">Total
                                        </th>
                                        @foreach ($allDates as $date)
                                            <th class="p-3 bg-dark text-center text-white border-light">
                                                {{ Carbon\Carbon::parse($date)->locale('id')->translatedFormat('d M Y') }}
                                            </th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($schedule as $mesinId => $mesinData)
                                        <tr>
                                            <td class="py-3 px-4" rowspan="{{ count($uniqueProducts) + 1 }}">
                                                {{ $mesinData['nama_mesin'] }}
                                            </td>
                                        </tr>
                                        @foreach ($uniqueProducts as $produkId => $produkData)
                                            <tr>
                                                <td class="py-3 px-4">{{ $produkData['nama_produk'] }}</td>
                                                <td class="text-center py-3 px-4">
                                                    @if (isset($mesinData['produk'][$produkId]))
                                                        {{ $mesinData['produk'][$produkId]['total_item'] }} item
                                                    @endif
                                                </td>
                                                @foreach ($allDates as $date)
                                                    <td class="text-center px-3"
                                                        style="
                                                        vertical-align: middle;
                                                            @if (isset($mesinData['produk'][$produkId]['tanggal_produksi'][$date])) background-color: green; color: white;
                                                            @elseif (array_key_exists(Carbon\Carbon::parse($date)->format('Y-m-d'), $hariLibur)) 
                                                                background-color: red; color: white; 
                                                            @elseif (Carbon\Carbon::parse($date)->isSunday()) 
                                                                background-color: red; color: white;
                                                            @else 
                                                                background-color: #f6f6f6; @endif">

                                                        @if (isset($mesinData['produk'][$produkId]['tanggal_produksi'][$date]))
                                                            @if (count($mesinData['produk'][$produkId]['tanggal_produksi'][$date]['pesanan_details']) > 1)
                                                                <ul class="list-unstyled mb-0">
                                                                    @foreach ($mesinData['produk'][$produkId]['tanggal_produksi'][$date]['pesanan_details'] as $pesananDetail)
                                                                        <li class="mb-2">
                                                                            <div
                                                                                class="d-flex justify-content-center gap-2 align-items-center">
                                                                                <span>{{ $pesananDetail['jumlah'] }}
                                                                                    item</span>
                                                                                <button
                                                                                    class="btn btn-sm btn-primary jadwal-produksi-mesin"
                                                                                    data-pesanan-detail-id="{{ $pesananDetail['pesanan_detail_id'] }}">
                                                                                    Detail
                                                                                </button>
                                                                            </div>
                                                                        </li>
                                                                    @endforeach
                                                                </ul>
                                                            @else
                                                                <div class="d-flex flex-column">
                                                                    @foreach ($mesinData['produk'][$produkId]['tanggal_produksi'][$date]['pesanan_details'] as $pesananDetail)
                                                                        <div
                                                                            class="d-flex justify-content-center gap-2 align-items-center">
                                                                            <span>{{ $pesananDetail['jumlah'] }}
                                                                                item</span>
                                                                            <button
                                                                                class="btn btn-sm btn-primary jadwal-produksi-mesin"
                                                                                data-pesanan-detail-id="{{ $pesananDetail['pesanan_detail_id'] }}">
                                                                                Detail
                                                                            </button>
                                                                        </div>
                                                                    @endforeach
                                                                </div>
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
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </main>

    {{-- Modal Detail Pesanan --}}
    <div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailModalLabel">Detail Pesanan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="pesananModal" tabindex="-1" aria-labelledby="pesananModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="pesananModalLabel">Detail Pesanan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        const role = '{{ session()->get('role') }}';
        const base_url = '{{ url('/') }}';

        function fetchPesananDetail(pesananId) {
            fetch(`${base_url}/${role}/pesanan/${pesananId}/detail`)
                .then(response => response.json())
                .then(data => {
                    var detailHtml = '';
                    if (data.length > 0) {
                        var pesanan = data[0].pesanan;
                        var total = 0;
                        const status = pesanan.status.charAt(0).toUpperCase() + pesanan.status.slice(1)
                            .toLowerCase();

                        detailHtml += `
                                <div class="row">
                                    <div class="col-md-6">
                                        <p><strong>Kode Pesanan:</strong> ${pesanan.kode_pesanan}</p>
                                        <p><strong>Channel:</strong> ${pesanan.channel}</p>
                                        <p><strong>Nama Pemesan:</strong> ${pesanan.nama_pemesan}</p>
                                        <p><strong>Status:</strong> <span class="badge bg-${pesanan.status == 'selesai' ? 'success' : 'warning'}">${status}</span></p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>Dibuat Oleh:</strong> ${pesanan.pengguna.nama}</p>
                                        <p><strong>Tanggal Pesanan:</strong> ${formatDateIndonesia(pesanan.tanggal_pesanan)}</p>
                                        <p><strong>Tanggal Pengiriman:</strong> ${formatDateIndonesia(pesanan.tanggal_pengiriman)}</p>
                                    </div>
                                </div>
                                <hr class="mt-0">
                                <h5>Detail Pesanan</h5>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Produk</th>
                                            <th>Jumlah</th>
                                            <th>Harga</th>
                                            <th>Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                            `;

                        data.forEach(item => {
                            var subtotal = item.jumlah * item.produk.harga;
                            total += subtotal;

                            detailHtml += `
                                    <tr>
                                        <td>${item.produk.nama_produk}</td>
                                        <td>x ${item.jumlah}</td>
                                        <td>Rp ${item.produk.harga.toLocaleString('id-ID')}</td>
                                        <td>Rp ${subtotal.toLocaleString('id-ID')}</td>
                                    </tr>
                                `;
                        });

                        detailHtml += `
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="3" class="text-end"><strong>Total:</strong></td>
                                            <td>Rp ${total.toLocaleString('id-ID')}</td>
                                        </tr>
                                    </tfoot>
                                </table>
                            `;
                    } else {
                        detailHtml = '<p>Tidak ada detail pesanan.</p>';
                    }

                    var detailModalBody = document.querySelector('#detailModal .modal-body');
                    detailModalBody.innerHTML = detailHtml;
                })
                .catch(error => {
                    console.error('Terjadi kesalahan:', error);
                    const pesananModalBody = document.querySelector('#pesananModal .modal-body');
                    pesananModalBody.innerHTML = '<p>Terjadi kesalahan saat mengambil data.</p>';
                });
        }

        function fetchPesananDetailSatu(pesananDetailId) {
            fetch(`${base_url}/${role}/pesanan/detail/${pesananDetailId}/detail`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Gagal mengambil data');
                    }
                    return response.json();
                })
                .then(data => {
                    let detailHtml = '';

                    if (data) {
                        const pesanan = data.pesanan || {};
                        const produk = data.produk || {};
                        const pengguna = pesanan.pengguna || {};
                        const jumlah = data.jumlah || 0;
                        const harga = produk.harga || 0;
                        const subtotal = jumlah * harga;
                        const status = pesanan.status.charAt(0).toUpperCase() + pesanan.status.slice(1)
                            .toLowerCase() || 'Tidak diketahui';
                        const channel = pesanan.channel || 'Tidak tersedia';

                        detailHtml += `
                                <div class="row">
                                    <div class="col-md-6">
                                        <p><strong>Kode Pesanan:</strong> ${pesanan.kode_pesanan || '-'}</p>
                                        <p><strong>Nama Pemesan:</strong> ${pesanan.nama_pemesan}</p>
                                        <p><strong>Channel:</strong> ${channel}</p>
                                        <p><strong>Status:</strong> 
                                            <span class="badge bg-${status === 'selesai' ? 'success' : 'warning'}">
                                                ${status}
                                            </span>
                                        </p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>Dibuat Oleh:</strong> ${pengguna.nama || 'Tidak diketahui'}</p>
                                        <p><strong>Tanggal Pesanan:</strong> ${formatDateIndonesia(pesanan.tanggal_pesanan || 'N/A')}</p>
                                        <p><strong>Tanggal Pengiriman:</strong> ${formatDateIndonesia(pesanan.tanggal_pengiriman || 'N/A')}</p>
                                    </div>
                                </div>
                                <hr class="mt-0">
                                <h5>Detail Pesanan</h5>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Produk</th>
                                            <th>Jumlah</th>
                                            <th>Harga</th>
                                            <th>Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>${produk.nama_produk || 'Tidak tersedia'}</td>
                                            <td>x ${jumlah}</td>
                                            <td>Rp ${harga.toLocaleString('id-ID')}</td>
                                            <td>Rp ${subtotal.toLocaleString('id-ID')}</td>
                                        </tr>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="3" class="text-end"><strong>Total:</strong></td>
                                            <td>Rp ${subtotal.toLocaleString('id-ID')}</td>
                                        </tr>
                                    </tfoot>
                                </table>
                            `;
                    } else {
                        detailHtml = '<p>Tidak ada detail pesanan.</p>';
                    }

                    const pesananModalBody = document.querySelector('#pesananModal .modal-body');
                    pesananModalBody.innerHTML = detailHtml;
                })
                .catch(error => {
                    console.error('Terjadi kesalahan:', error);
                    const pesananModalBody = document.querySelector('#pesananModal .modal-body');
                    pesananModalBody.innerHTML = '<p>Terjadi kesalahan saat mengambil data.</p>';
                });

        }

        // Fungsi untuk memformat tanggal ke format Indonesia
        function formatDateIndonesia(dateString) {
            const months = {
                'January': 'Januari',
                'February': 'Februari',
                'March': 'Maret',
                'April': 'April',
                'May': 'Mei',
                'June': 'Juni',
                'July': 'Juli',
                'August': 'Agustus',
                'September': 'September',
                'October': 'Oktober',
                'November': 'November',
                'December': 'Desember'
            };

            const date = new Date(dateString);
            const day = date.getDate();
            const monthIndex = date.toLocaleString('en-US', {
                month: 'long'
            });
            const year = date.getFullYear();

            return `${day} ${months[monthIndex]} ${year}`;
        }

        document.addEventListener('DOMContentLoaded', function() {
            const jadwalProduksi = document.querySelectorAll('.jadwal-produksi');
            jadwalProduksi.forEach(row => {
                row.addEventListener('click', function() {
                    const pesananId = this.getAttribute('data-pesanan-id');

                    if (pesananId) {
                        fetchPesananDetail(pesananId);
                        const modal = new bootstrap.Modal(document.getElementById('detailModal'));
                        modal.show();
                    } else {
                        console.error('data-pesanan-id tidak ditemukan pada elemen ini.');
                    }
                });
            });

            const jadwalProduksiMesin = document.querySelectorAll('.jadwal-produksi-mesin');
            jadwalProduksiMesin.forEach(row => {
                row.addEventListener('click', function() {
                    const pesananDetailId = this.getAttribute('data-pesanan-detail-id');

                    if (pesananDetailId) {
                        fetchPesananDetailSatu(pesananDetailId);
                        const modal = new bootstrap.Modal(document.getElementById('pesananModal'));
                        modal.show();
                    } else {
                        console.error('data-pesanan-id tidak ditemukan pada elemen ini.');
                    }
                });
            });

            document.querySelector('#date').addEventListener('change', updateLink);
            document.querySelector('#limit').addEventListener('change', updateLink);

            function updateLink() {
                const date = document.querySelector('#date').value;
                const limit = document.querySelector('#limit').value;

                const url = new URL(`{{ url('${role}/penjadwalan') }}`);
                const urlPDF = new URL(`{{ url('${role}/penjadwalan/pdf') }}`);

                // Set query parameters
                url.searchParams.set('date', date);
                url.searchParams.set('limit', limit);
                urlPDF.searchParams.set('date', date);
                urlPDF.searchParams.set('limit', limit);

                // Update the link
                document.querySelector('#submitBtn').href = url.toString();
                document.querySelector('#downloadPDF').href = urlPDF.toString();
            }

            // Trigger the function initially to set the correct URL
            updateLink();
        });
    </script>
@endsection
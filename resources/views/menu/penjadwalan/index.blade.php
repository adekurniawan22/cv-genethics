@extends('layout.main')
@section('content')
    <main class="page-content">
        <!-- Breadcrumb section remains the same -->
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
            <div class="ms-auto d-flex align-items-center gap-2">
                <form action="" method="GET" class="d-flex align-items-center">
                    <div class="input-group" style="width: 180px;">
                        <input type="number" name="limit" class="form-control" placeholder="Jumlah data"
                            value="{{ request('limit', 20) }}" min="1">
                        <button class="btn btn-primary" type="submit">Generate</button>
                    </div>
                </form>
                <a id="downloadPDF" href="{{ route('manajer.penjadwalan.pdf', ['limit' => request('limit', 5)]) }}"
                    target="_blank" class="btn btn-danger mr-2">
                    <i class="lni lni-download"></i> Download PDF
                </a>
            </div>
        </div>

        <div class="row ms-0 me-1">
            <div class="card radius-10 w-100">
                <div class="card-body">
                    <div class="table-responsive">
                        <div class="mt-1"></div>
                        <style>
                            #penjadwalan.table-bordered {
                                border: 1px solid #dee2e6;
                            }

                            #penjadwalan.table-bordered th,
                            #penjadwalan.table-bordered td {
                                border: 1px solid #dee2e6;
                            }
                        </style>
                        <table id="penjadwalan" class="table align-middle table-hover table-bordered">
                            <thead class="table-dark">
                                <tr>
                                    <th class="text-center" style="width: 5%">No.</th>
                                    <th>Pesanan</th>
                                    <th class="text-center">Batas Hari Pengiriman</th>
                                    <th class="text-center">Waktu Produksi</th>
                                    <th class="text-center">Prediksi Keterlambatan</th>
                                    <th>Penggunaan Mesin</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($schedule as $index => $item)
                                    <tr class="clickable-row" data-pesanan-id="{{ $item['pesanan_id'] }}"
                                        style="cursor: pointer;">
                                        <td class="text-center" style="vertical-align: top">{{ $index + 1 }}.</td>
                                        <td style="vertical-align: top">
                                            <div class="d-flex align-items-center gap-3">
                                                <div class="fw-bold">{{ $item['kode_pesanan'] }}</div>
                                            </div>
                                            <div style="max-width: 200px; word-wrap: break-word;">
                                                <span class="text-muted">{{ $item['channel'] }}</span>
                                                <div class="d-flex flex-wrap gap-1">
                                                    @foreach ($item['products'] as $product)
                                                        <span class="badge bg-light text-dark">
                                                            {{ $product['nama_produk'] }} ({{ $product['jumlah'] }})
                                                        </span>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </td>
                                        <!-- Rest of the row content remains the same -->
                                        <td class="text-center" style="vertical-align: top">
                                            <span class="badge bg-info  p-2">
                                                {{ $item['tanggal_pengiriman_asli'] }}
                                                <br>
                                                <em class="d-block mt-2">({{ $item['batas_hari_pengiriman'] }} hari
                                                    lagi)</em>
                                            </span>
                                        </td>
                                        <td class="text-center" style="vertical-align: top">
                                            <span class="badge bg-warning p-2">
                                                {{ $item['completion_time']['tanggal'] }}
                                                <br>
                                                <em class="d-block mt-2">({{ $item['completion_time']['hari'] }} hari
                                                    pengerjaan)</em>
                                            </span>
                                        </td>
                                        <td class="text-center" style="vertical-align: top">
                                            @if ($item['keterlambatan']['hari'] > 0)
                                                <span class="badge bg-danger p-2">
                                                    {{ $item['keterlambatan']['tanggal'] }}
                                                    <br>
                                                    <em class="d-block mt-2">(telat {{ $item['keterlambatan']['hari'] }}
                                                        hari)</em>
                                                </span>
                                            @else
                                                <span class="badge bg-success p-2">Tepat Waktu</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div>
                                                @foreach ($item['penggunaan_mesin'] as $date => $usage)
                                                    <div class="mb-2">
                                                        <div class="fw-bold text-primary">
                                                            <i class="bi bi-calendar3"></i>
                                                            {{ $date }}
                                                        </div>
                                                        @foreach ($usage as $machine)
                                                            <div class="ms-3">
                                                                <i class="bi bi-gear"></i>
                                                                {{ $machine['nama_mesin'] }}
                                                                <span class="badge bg-info">
                                                                    {{ $machine['kapasitas_terpakai'] }} unit
                                                                </span>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @endforeach
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
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
                    <!-- Konten detail pesanan akan dimuat di sini -->
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
        document.addEventListener('DOMContentLoaded', function() {
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

            // Select all rows with the clickable-row class
            const rows = document.querySelectorAll('.clickable-row');

            // Add click event listener to each row
            rows.forEach(row => {
                row.addEventListener('click', function() {
                    const pesananId = this.getAttribute('data-pesanan-id');
                    fetchPesananDetail(pesananId);

                    // Show the modal
                    const modal = new bootstrap.Modal(document.getElementById('detailModal'));
                    modal.show();
                });
            });

            function fetchPesananDetail(pesananId) {
                // Ambil role dari session
                const role = '{{ session()->get('role') }}';
                const base_url = '{{ url('/') }}';

                // Kirim permintaan Ajax ke server untuk mengambil detail pesanan
                fetch(`${base_url}/${role}/pesanan/${pesananId}/detail`)
                    .then(response => response.json())
                    .then(data => {
                        // Tampilkan data detail pesanan dalam modal
                        var detailHtml = '';

                        if (data.length > 0) {
                            var pesanan = data[0].pesanan;
                            var total = 0;
                            // Ambil status pesanan
                            const status = pesanan.status.charAt(0).toUpperCase() + pesanan.status.slice(1)
                                .toLowerCase();

                            detailHtml += `
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Kode Pesanan:</strong> PESANAN#${pesanan.pesanan_id}</p>
                                <p><strong>Channel:</strong> ${pesanan.channel}</p>
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
                    });
            }

            const limitInput = document.querySelector('input[name="limit"]');
            const downloadBtn = document.getElementById('downloadPDF');

            function updateDownloadUrl() {
                const limit = limitInput.value || 5;
                downloadBtn.href = `{{ route('manajer.penjadwalan.pdf') }}/${limit}`;
            }

            // Update saat halaman dimuat
            updateDownloadUrl();

            // Update saat nilai input berubah
            limitInput.addEventListener('change', updateDownloadUrl);
            limitInput.addEventListener('keyup', updateDownloadUrl);
        });
    </script>
@endsection

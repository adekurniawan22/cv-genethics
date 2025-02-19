@extends('layout.main')
@section('content')
    <main class="page-content">
        <!-- Breadcrumb -->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route(session()->get('role') . '.dashboard') }}"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">
                            <span class="text-dark">Pesanan</span>
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
        <!-- End Breadcrumb -->

        <div class="row ms-0 me-1">
            <div class="card radius-10 w-100">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="id-sembunyi-table" class="table align-middle table-hover" style="width: 99%">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Kode Pesanan</th>
                                    <th>Status</th>
                                    <th>Tanggal Pesanan</th>
                                    <th>Tanggal Pengiriman</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $pesanan)
                                    <tr class="clickable-row" style="cursor: pointer"
                                        data-pesanan-id="{{ $pesanan->pesanan_id }}">
                                        <td>{{ $pesanan->pesanan_id }}</td>
                                        <td>
                                            {{ $pesanan->kode_pesanan }}
                                            <br>
                                            <small class="text-muted">{{ $pesanan->channel }}</small>
                                        </td>
                                        <td>
                                            <span
                                                class="badge bg-{{ $pesanan->status == 'selesai' ? 'success' : 'warning' }}">
                                                {{ ucfirst($pesanan->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            {{ $pesanan->tanggal_pesanan
                                                ? \Carbon\Carbon::parse($pesanan->tanggal_pesanan)->locale('id_ID')->isoFormat('D MMMM YYYY')
                                                : 'Tanggal tidak tersedia' }}
                                        </td>
                                        <td>
                                            {{ $pesanan->tanggal_pengiriman
                                                ? \Carbon\Carbon::parse($pesanan->tanggal_pengiriman)->locale('id_ID')->isoFormat('D MMMM YYYY')
                                                : 'Tanggal tidak tersedia' }}
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
            var viewButtons = document.querySelectorAll('[data-bs-target="#detailModal"]');

            viewButtons.forEach(function(button) {
                button.addEventListener('click', function() {
                    var pesananId = button.getAttribute('data-pesanan-id');
                    fetchPesananDetail(pesananId);
                });
            });

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

            function fetchPesananDetail(pesananId) {
                // Ambil role dari session
                const role = '{{ session()->get('role') }}';
                const base_url = '{{ url('/') }}';

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

            var clickableRows = document.querySelectorAll('.clickable-row');

            clickableRows.forEach(function(row) {
                row.addEventListener('click', function() {
                    var pesananId = row.getAttribute('data-pesanan-id');
                    fetchPesananDetail(pesananId);
                    var detailModal = new bootstrap.Modal(document.getElementById('detailModal'));
                    detailModal.show();
                });
            });
        });
    </script>
@endsection

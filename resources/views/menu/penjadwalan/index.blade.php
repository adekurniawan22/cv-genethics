@extends('layout.main')
@section('content')
    <main class="page-content">

        <!-- Breadcrumb -->
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
            <div class="ms-auto">
                <a id="downloadPDF" href="#" class="btn btn-primary">
                    <i class="lni lni-download"></i> Download PDF
                </a>
            </div>
        </div>
        <!-- End Breadcrumb -->

        <div class="row ms-0 me-1">
            <div class="card radius-10 w-100">
                <div class="card-body">
                    <div class="table-responsive">
                        <div class="mt-1"></div>
                        <table id="penjadwalan" class="table align-middle table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Pesanan</th>
                                    <th>Estimasi Selesai</th>
                                    <th>Urutan Prioritas</th>
                                    <th data-sortable="false">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $schedule)
                                    <tr>
                                        <td>
                                            Pesanan#{{ $schedule->pesanan_id }}
                                            <br>
                                            <small class="text-muted">{{ $schedule->pesanan->channel }}</small>
                                        </td>
                                        <td>{{ $schedule->estimasi_selesai }}</td>
                                        <td>{{ $schedule->urutan_prioritas }}</td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal"
                                                data-bs-target="#detailModal" data-pesanan-id="{{ $schedule->pesanan_id }}">
                                                <i class="bi bi-eye-fill"></i> View
                                            </button>
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

            function fetchPesananDetail(pesananId) {
                // Ambil role dari session
                const role = '{{ session()->get('role') }}';

                // Kirim permintaan Ajax ke server untuk mengambil detail pesanan
                fetch(`/${role}/pesanan/${pesananId}/detail`)
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
                            <p><strong>Tanggal Pesanan:</strong> ${pesanan.tanggal_pesanan}</p>
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

            document.getElementById('downloadPDF').addEventListener('click', function() {
                alert('COMING SOON HEHE')
            })
        });
    </script>
@endsection

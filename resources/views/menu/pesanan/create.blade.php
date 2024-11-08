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
                            <a href="{{ route(session()->get('role') . '.pesanan.index') }}">Pesanan</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Tambah Pesanan</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!-- End Breadcrumb -->

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6 ">
                                <form id="pesananForm" action="{{ route(session()->get('role') . '.pesanan.store') }}"
                                    method="POST">
                                    @csrf
                                    <div class="card-body rounded bg-secondary text-white">
                                        <div class="mb-3">
                                            <label for="channel" class="form-label">Channel</label>
                                            <select name="channel" id="channel" class="form-select" required>
                                                <option value="">Pilih Channel</option>
                                                <option value="Online">Online</option>
                                                <option value="Offline">Offline</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="tanggal_pesanan" class="form-label">Tanggal Pesanan</label>
                                            <input type="date" name="tanggal_pesanan" id="tanggal_pesanan"
                                                class="form-control" value="{{ date('Y-m-d') }}">
                                        </div>
                                        <!-- Checklist untuk Tanggal Pengiriman -->
                                        <div class="mb-3">
                                            <input type="checkbox" id="toggleTanggalPengiriman">
                                            <label for="toggleTanggalPengiriman" class="form-label">Apakah ada tanggal
                                                pengiriman?</label>
                                        </div>
                                        <!-- Input Tanggal Pengiriman -->
                                        <div class="mb-3" id="tanggalPengirimanWrapper" style="display: none;">
                                            <label for="tanggal_pengiriman" class="form-label">Tanggal Pengiriman</label>
                                            <input type="date" name="tanggal_pengiriman" id="tanggal_pengiriman"
                                                class="form-control">
                                        </div>
                                        <div class="mb-3">
                                            <label for="status" class="form-label">Status</label>
                                            <select name="status" id="status" class="form-select" required>
                                                <option value="pending" selected>Pending</option>
                                                <option value="selesai">Selesai</option>
                                            </select>
                                        </div>
                                    </div>
                            </div>

                            <div class="col-6">
                                <div class="card-body rounded bg-secondary text-white">
                                    <div class="mb-3">
                                        <label class="form-label">Produk</label>
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Produk</th>
                                                    <th>Jumlah</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody id="detailPesanan">
                                                <tr>
                                                    <td>
                                                        <select name="produk_id[]" class="form-select produk-select"
                                                            required>
                                                            <option value="">Pilih Produk</option>
                                                            @foreach ($products as $product)
                                                                <option value="{{ $product->id }}">
                                                                    {{ $product->nama_produk }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <input type="number" name="jumlah[]" class="form-control"
                                                            min="1" required>
                                                    </td>
                                                    <td>
                                                        <button type="button" class="btn btn-sm btn-danger removeRow"
                                                            style="display: none;">
                                                            <i class="bi bi-trash-fill"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <button type="button" class="btn btn-sm btn-primary" id="addRow">
                                            <i class="bi bi-plus-circle"></i> Tambah Produk
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="text-end mb-2 mt-4">
                            <a href="{{ route(session()->get('role') . '.pesanan.index') }}"
                                class="btn btn-dark">Kembali</a>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </main>
@endsection

@section('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const addRowButton = document.getElementById('addRow');
            const detailPesananBody = document.getElementById('detailPesanan');
            const toggleTanggalPengiriman = document.getElementById('toggleTanggalPengiriman');
            const tanggalPengirimanWrapper = document.getElementById('tanggalPengirimanWrapper');

            // Tampilkan atau sembunyikan input tanggal pengiriman
            toggleTanggalPengiriman.addEventListener('change', function() {
                tanggalPengirimanWrapper.style.display = this.checked ? 'block' : 'none';
            });

            addRowButton.addEventListener('click', function() {
                addNewProductRow();
                updateProdukOptions();
                updateRemoveButtons();
                updateAddRowButton();
            });

            detailPesananBody.addEventListener('click', function(event) {
                const clickedElement = event.target;
                if (clickedElement.classList.contains('removeRow') || clickedElement.tagName
                    .toLowerCase() === 'i') {
                    event.stopPropagation();
                    clickedElement.closest('tr').remove();
                    updateProdukOptions();
                    updateRemoveButtons();
                    updateAddRowButton();
                }
            });

            detailPesananBody.addEventListener('change', function(event) {
                if (event.target.classList.contains('produk-select')) {
                    updateProdukOptions();
                    updateAddRowButton();
                }
            });

            function addNewProductRow() {
                const newRow = document.createElement('tr');
                newRow.innerHTML = `
                    <td>
                        <select name="produk_id[]" class="form-select produk-select" required>
                            <option value="">Pilih Produk</option>
                            @foreach ($products as $product)
                                <option value="{{ $product->id }}">{{ $product->nama_produk }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <input type="number" name="jumlah[]" class="form-control" min="1" required>
                    </td>
                    <td>
                        <button type="button" class="btn btn-sm btn-danger removeRow">
                            <i class="bi bi-trash-fill"></i>
                        </button>
                    </td>
                `;
                detailPesananBody.appendChild(newRow);
            }


            function updateProdukOptions() {
                const selectedProducts = Array.from(detailPesananBody.querySelectorAll('.produk-select'))
                    .map(select => select.value);

                const produkSelects = detailPesananBody.querySelectorAll('.produk-select');
                produkSelects.forEach(function(select) {
                    const selectedValue = select.value;
                    select.innerHTML = '<option value="">Pilih Produk</option>';

                    @foreach ($products as $product)
                        if (!selectedProducts.includes('{{ $product->produk_id }}') || selectedValue ===
                            '{{ $product->produk_id }}') {
                            const option = document.createElement('option');
                            option.value = '{{ $product->produk_id }}';
                            option.textContent = '{{ $product->nama_produk }}';
                            select.appendChild(option);
                        }
                    @endforeach

                    select.value = selectedValue;
                });
            }

            function updateRemoveButtons() {
                const removeButtons = detailPesananBody.querySelectorAll('.removeRow');
                removeButtons.forEach(function(button) {
                    button.style.display = removeButtons.length > 1 ? 'inline-block' : 'none';
                });
            }

            function updateAddRowButton() {
                const selectedProducts = Array.from(detailPesananBody.querySelectorAll('.produk-select'))
                    .map(select => select.value);

                const allProductsSelected = selectedProducts.length === @json($products->count());
                addRowButton.disabled = allProductsSelected;
            }

            updateProdukOptions();
            updateRemoveButtons();
            updateAddRowButton();
        });

        document.getElementById('pesananForm').addEventListener('submit', function(event) {
            event.preventDefault(); // Mencegah pengiriman form default

            const csrfToken = document.querySelector('input[name="_token"]').value;
            // Mendapatkan data produk dari tabel detail pesanan
            const detailPesananBody = document.getElementById('detailPesanan');
            const rows = detailPesananBody.querySelectorAll('tr');
            const produkData = [];

            // Mengiterasi setiap baris di dalam tabel untuk mengumpulkan produk_id dan jumlah
            rows.forEach(row => {
                const produkId = row.querySelector('select[name="produk_id[]"]').value;
                const jumlah = row.querySelector('input[name="jumlah[]"]').value;

                // Debugging per baris
                console.log(`Produk ID: ${produkId}, Jumlah: ${jumlah}`);

                // Menambahkan data produk hanya jika valid
                if (produkId && jumlah) {
                    produkData.push({
                        produk_id: produkId,
                        jumlah: jumlah
                    });
                }
            });

            // Mengumpulkan data lainnya dari form
            const formData = new FormData(this);
            const tanggalPengiriman = formData.get('tanggal_pengiriman');
            const status = formData.get('status');
            const channel = formData.get('channel');
            const tanggalPesanan = formData.get('tanggal_pesanan');

            // Gabungkan semua data yang akan dikirim
            const formDataObj = {
                produkData: produkData,
                tanggalPengiriman: tanggalPengiriman,
                status: status,
                channel: channel,
                tanggalPesanan: tanggalPesanan
            };

            // Mengirim data ke server melalui AJAX menggunakan fetch
            fetch("{{ route(session()->get('role') . '.pesanan.store') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": csrfToken
                    },
                    body: JSON.stringify(formDataObj)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.href =
                            "{{ route(session()->get('role') . '.pesanan.index') }}"; // Session 'success_message' akan tersedia di halaman tujuan
                    } else {
                        alert("Gagal menyimpan data pesanan.");
                    }
                })

                .catch(error => {
                    console.error("Error:", error);
                    alert("Terjadi kesalahan dalam menyimpan data.");
                });
        });
    </script>
@endsection

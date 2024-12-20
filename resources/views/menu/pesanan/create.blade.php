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
                                    <div class="card-body rounded " style="background-color: #e9e9e9">
                                        <div class="mb-3">
                                            <label for="nama_pemesan" class="form-label">Nama Pemesan</label>
                                            <input type="text" name="nama_pemesan" id="nama_pemesan" class="form-control"
                                                placeholder="Masukkan nama pemesan" autocomplete="off">
                                            <style>
                                                .suggestions-list {
                                                    position: relative;
                                                    max-height: 200px;
                                                    overflow-y: auto;
                                                    overflow-x: hidden;
                                                    border: 1px solid #ddd;
                                                    background-color: #fff;
                                                    z-index: 999;
                                                    padding: 5px;
                                                    box-sizing: border-box;
                                                    width: 100%;
                                                    /* Lebar default 100% */
                                                }


                                                .suggestions-list div {
                                                    padding: 8px;
                                                    cursor: pointer;
                                                }

                                                .suggestions-list div:hover {
                                                    background-color: #f1f1f1;
                                                }
                                            </style>
                                            <div id="pemesanSuggestions" class="suggestions-list" style="display: none;">
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="channel" class="form-label">Channel</label>
                                            <select name="channel" id="channel" class="form-select">
                                                <option value="">Pilih Channel</option>
                                                <option value="WA">WA</option>
                                                <option value="SHOPEE">SHOPEE</option>
                                                <option value="TOKOPEDIA">TOKOPEDIA</option>
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
                                            <select name="status" id="status" class="form-select">
                                                <option value="proses" selected>Proses</option>
                                                <option value="selesai">Selesai</option>
                                            </select>
                                        </div>
                                    </div>
                            </div>

                            <div class="col-6">
                                <div class="card-body rounded " style="background-color: #e9e9e9">
                                    <div class="mb-3">
                                        <label class="form-label">Produk</label>
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Produk</th>
                                                    <th>Jumlah</th>
                                                    <th id="thAksi" style="display: none">Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody id="detailPesanan">
                                                <tr>
                                                    <td>
                                                        <select name="produk_id[]"
                                                            class="form-select produk-select single-select">
                                                            <option value="">Pilih Produk</option>
                                                            @foreach ($products as $product)
                                                                <option value="{{ $product->id }}">
                                                                    {{ $product->nama_produk }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <input type="number" name="jumlah[]"
                                                            class="form-control jumlah-input" min="1">
                                                    </td>
                                                    <td class="thTdAksi" style="display: none">
                                                        <button type="button" class="btn btn-sm btn-danger removeRow"
                                                            style="display: none;">
                                                            <i class="bi bi-trash-fill"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <button type="button" class="btn btn-sm btn-danger" id="addRow">
                                            <i class="bi bi-plus-circle"></i> Tambah Produk
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="text-end mb-2 mt-4">
                            <a href="{{ route(session()->get('role') . '.pesanan.index') }}"
                                class="btn btn-dark">Kembali</a>
                            <button type="submit" class="btn btn-danger">Simpan</button>
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
        const role = '{{ session()->get('role') }}';
        const base_url = '{{ url('/') }}';
        const namaPemesanInput = document.getElementById('nama_pemesan');
        const suggestionsContainer = document.getElementById('pemesanSuggestions');

        async function fetchPemesan(query) {
            try {
                const response = await fetch(base_url + '/' + role + '/cari-nama-pemesan?search=' + query);
                const data = await response.json();
                return data.pemesan || [];
            } catch (error) {
                console.error('Error fetching pemesan data:', error);
                return [];
            }
        }

        function showSuggestions(suggestions) {
            if (suggestions.length > 0) {
                suggestionsContainer.innerHTML = suggestions.map(suggestion => `<div>${suggestion}</div>`).join('');
                suggestionsContainer.style.display = 'block';

                const inputWidth = namaPemesanInput.offsetWidth;
                suggestionsContainer.style.width = `${inputWidth}px`;

                const suggestionItems = suggestionsContainer.querySelectorAll('div');
                suggestionItems.forEach(item => {
                    item.addEventListener('click', function() {
                        namaPemesanInput.value = item
                            .textContent;
                        suggestionsContainer.style.display = 'none';
                    });
                });
            } else {
                suggestionsContainer.style.display = 'none';
            }
        }

        function addErrorMessage(element, message) {
            let errorElement = element.parentNode.querySelector('.invalid-feedback');
            if (!errorElement) {
                errorElement = document.createElement('div');
                errorElement.className = 'invalid-feedback';
                element.parentNode.insertBefore(errorElement, element.nextSibling);
            }
            errorElement.textContent = message;
            errorElement.style.display = 'block';
        }

        function removeErrorMessage(element) {
            const errorElement = element.parentNode.querySelector('.invalid-feedback');
            if (errorElement) {
                errorElement.style.display = 'none';
                errorElement.remove();
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            namaPemesanInput.addEventListener('input', async function() {
                const query = namaPemesanInput.value.trim().toLowerCase();
                if (query.length > 0) {
                    const pemesanList = await fetchPemesan(query);
                    showSuggestions(pemesanList);
                } else {
                    suggestionsContainer.style.display = 'none';
                }
            });

            namaPemesanInput.addEventListener('blur', function() {
                setTimeout(() => {
                    suggestionsContainer.style.display = 'none';
                }, 200);
            });

            const addRowButton = document.getElementById('addRow');
            const detailPesananBody = document.getElementById('detailPesanan');

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
                        <select name="produk_id[]" class="form-select produk-select single-select" >
                            <option value="">Pilih Produk</option>
                            @foreach ($products as $product)
                                <option value="{{ $product->id }}">{{ $product->nama_produk }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <input type="number" name="jumlah[]" class="form-control jumlah-input" min="1" >
                    </td>
                    <td class="thTdAksi" style="display:none">
                        <button type="button" class="btn btn-sm btn-danger removeRow">
                            <i class="bi bi-trash-fill"></i>
                        </button>
                    </td>
                `;
                detailPesananBody.appendChild(newRow);
                $('.single-select').select2({
                    theme: 'bootstrap4',
                    width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ?
                        '100%' : 'style',
                    placeholder: $(this).data('placeholder'),
                    allowClear: Boolean($(this).data('allow-clear')),
                });
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
                const detailPesananBody = document.getElementById('detailPesanan');
                const removeButtons = detailPesananBody.querySelectorAll('.removeRow');
                const rows = detailPesananBody.querySelectorAll('tr');
                const thAksi = document.getElementById('thAksi');

                removeButtons.forEach(function(button) {
                    button.style.display = rows.length > 1 ? 'inline-block' : 'none';
                });

                if (rows.length > 1) {
                    thAksi.style.display = 'table-cell';
                    document.querySelectorAll('.thTdAksi').forEach(function(td) {
                        td.style.display = 'table-cell';
                    });
                } else {
                    thAksi.style.display = 'none';
                    document.querySelectorAll('.thTdAksi').forEach(function(td) {
                        td.style.display = 'none';
                    });
                }
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
            event.preventDefault();

            const nama_pemesan = document.getElementById('nama_pemesan');
            const channel = document.getElementById('channel');
            const tanggalPesanan = document.getElementById('tanggal_pesanan');
            const tanggalPengiriman = document.getElementById('tanggal_pengiriman');
            const status = document.getElementById('status');
            console.log(tanggalPesanan)

            let isValid = true;

            if (!nama_pemesan.value) {
                addErrorMessage(nama_pemesan, 'Harap masukkan nama pemesan.');
                isValid = false;
            } else {
                removeErrorMessage(nama_pemesan);
            }

            if (!channel.value) {
                addErrorMessage(channel, 'Harap pilih channel.');
                isValid = false;
            } else {
                removeErrorMessage(channel);
            }

            if (!tanggalPesanan.value) {
                addErrorMessage(tanggalPesanan, 'Harap isi tanggal pesanan.');
                isValid = false;
            } else {
                removeErrorMessage(tanggalPesanan);
            }

            if (toggleTanggalPengiriman.checked && !tanggalPengiriman.value) {
                addErrorMessage(tanggalPengiriman, 'Harap isi tanggal pengiriman jika checkbox dicentang.');
                isValid = false;
            } else {
                removeErrorMessage(tanggalPengiriman);
            }

            if (!status.value) {
                addErrorMessage(status, 'Harap pilih status.');
                isValid = false;
            } else {
                removeErrorMessage(status);
            }

            const detailPesananBody = document.getElementById('detailPesanan');
            const detailPesananRows = detailPesananBody.querySelectorAll('tr');
            detailPesananRows.forEach(function(row) {
                const produkSelect = row.querySelector('.produk-select');
                const jumlahInput = row.querySelector('.jumlah-input');

                if (!produkSelect.value) {
                    addErrorMessage(produkSelect, 'Harap pilih produk.');
                    isValid = false;
                } else {
                    removeErrorMessage(produkSelect);
                }

                if (!jumlahInput.value || parseInt(jumlahInput.value) < 1) {
                    addErrorMessage(jumlahInput, 'Harap masukkan jumlah yang valid.');
                    isValid = false;
                } else {
                    removeErrorMessage(jumlahInput);
                }
            });

            if (isValid) {

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
                const status = formData.get('status');
                const nama_pemesan = formData.get('nama_pemesan');
                const channel = formData.get('channel');
                const tanggalPesanan = formData.get('tanggal_pesanan');
                const toggleTanggalPengirimanChecked = document.getElementById('toggleTanggalPengiriman').checked;
                let tanggalPengiriman;

                if (toggleTanggalPengirimanChecked) {
                    tanggalPengiriman = formData.get('tanggal_pengiriman');
                } else {
                    const tanggalPesanan = new Date(formData.get('tanggal_pesanan'));
                    const calculatedDate = new Date(tanggalPesanan);
                    calculatedDate.setDate(calculatedDate.getDate() + 9);
                    tanggalPengiriman = calculatedDate.toISOString().split('T')[0];
                }

                const formDataObj = {
                    produkData: produkData,
                    tanggalPengiriman: tanggalPengiriman,
                    nama_pemesan: nama_pemesan,
                    channel: channel,
                    status: status,
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
                                "{{ route(session()->get('role') . '.pesanan.index') }}";
                        } else {
                            alert("Gagal menyimpan data pesanan.");
                        }
                    })

                    .catch(error => {
                        console.error("Error:", error);
                        alert("Terjadi kesalahan dalam menyimpan data.");
                    });
            }
        });
    </script>
@endsection

@extends('layout.main')
@section('content')
    <main class="page-content">
        <!-- Breadcrumb -->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('owner.dashboard') }}"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">
                            <span class="text-dark">Pesanan</span>
                        </li>
                    </ol>
                </nav>
            </div>
            <div class="ms-auto">
                <a href="{{ route('owner.pesanan.create') }}" class="btn btn-primary">
                    <i class="fadeIn animated bx bx-plus"></i>Tambah
                </a>
            </div>
        </div>
        <!-- End Breadcrumb -->

        <div class="row ms-0 me-1">
            <div class="card radius-10 w-100">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="example" class="table align-middle table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Pengguna</th>
                                    <th>Detail Pesanan</th>
                                    <th>Status</th>
                                    <th>Tanggal</th>
                                    <th data-sortable="false">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $pesanan)
                                    <tr>
                                        <td>{{ $pesanan->pengguna->nama ?? 'Pengguna Dihapus' }}</td>
                                        <td>{{ $pesanan->detail_pesanan }}</td>
                                        <td>
                                            <span
                                                class="badge bg-{{ $pesanan->status == 'completed' ? 'success' : 'warning' }}">
                                                {{ ucfirst($pesanan->status) }}
                                            </span>
                                        </td>
                                        <td>{{ $pesanan->created_at->format('d/m/Y H:i') }}</td>
                                        <td>
                                            <div class="d-flex gap-3">
                                                <a href="{{ route('owner.pesanan.edit', $pesanan->pesanan_id) }}"
                                                    class="btn btn-sm btn-warning">
                                                    <i class="bi bi-pencil-fill"></i> Edit
                                                </a>
                                                <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                                    data-bs-target="#confirmDeleteModal"
                                                    data-form-id="delete-form-{{ $pesanan->pesanan_id }}">
                                                    <i class="bi bi-trash-fill"></i> Hapus
                                                </button>
                                                <form id="delete-form-{{ $pesanan->pesanan_id }}"
                                                    action="{{ route('owner.pesanan.destroy', $pesanan->pesanan_id) }}"
                                                    method="POST" style="display: none;">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>
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
@endsection

@section('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var deleteButtons = document.querySelectorAll('[data-bs-target="#confirmDeleteModal"]');
            var confirmDeleteButton = document.getElementById('confirm-delete');

            deleteButtons.forEach(function(button) {
                button.addEventListener('click', function() {
                    var formId = button.getAttribute('data-form-id');
                    confirmDeleteButton.setAttribute('data-form-id', formId);
                });
            });

            confirmDeleteButton.addEventListener('click', function() {
                var formId = confirmDeleteButton.getAttribute('data-form-id');
                document.getElementById(formId).submit();
            });
        });
    </script>
@endsection

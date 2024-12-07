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
                            <span class="text-dark">Mesin</span>
                        </li>
                    </ol>
                </nav>
            </div>
            <div class="ms-auto">
                <a href="{{ route(session()->get('role') . '.mesin.create') }}" class="btn btn-danger">
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
                                    <th>Nama Mesin</th>
                                    <th>Status</th>
                                    <th>Keterangan</th>
                                    <th>Kapasitas/Hari</th>
                                    <th data-sortable="false">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $mesin)
                                    <tr>
                                        <td>{{ $mesin->nama_mesin }}</td>
                                        <td>
                                            <span
                                                class="badge {{ $mesin->status === 'aktif' ? 'bg-success' : 'bg-danger' }}">
                                                {{ ucwords($mesin->status) }}
                                            </span>
                                        </td>
                                        <td>{{ $mesin->keterangan_mesin }}</td>
                                        <td>{{ $mesin->kapasitas_per_hari ?? '-' }}</td>
                                        <td>
                                            <div class="d-flex align-items-start justify-content-start gap-3 fs-6">
                                                <a href="{{ route(session()->get('role') . '.mesin.edit', $mesin->mesin_id) }}"
                                                    class="btn btn-sm btn-warning text-white d-flex align-items-center">
                                                    <i class="bi bi-pencil-fill me-1"></i> Edit
                                                </a>

                                                <button type="button"
                                                    class="btn btn-sm btn-danger d-flex align-items-center"
                                                    data-bs-toggle="modal" data-bs-target="#confirmDeleteModal"
                                                    data-form-id="delete-form-{{ $mesin->mesin_id }}">
                                                    <i class="bi bi-trash-fill me-1"></i> Hapus
                                                </button>

                                                <form id="delete-form-{{ $mesin->mesin_id }}"
                                                    action="{{ route(session()->get('role') . '.mesin.destroy', $mesin->mesin_id) }}"
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

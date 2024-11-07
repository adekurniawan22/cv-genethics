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
                            <a href="{{ route('owner.dashboard') }}"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">
                            <span class="text-dark">Pengguna</span>
                        </li>
                    </ol>
                </nav>
            </div>
            <div class="ms-auto">
                <a href="{{ route('owner.pengguna.create') }}" class="btn btn-primary">
                    <i class="fadeIn animated bx bx-plus"></i>Tambah
                </a>
            </div>
        </div>
        <!-- End Breadcrumb -->

        <div class="row ms-0 me-1">
            <div class="card radius-10 w-100">
                <div class="card-body">
                    <div class="table-responsive">
                        <div class="mt-1"></div>
                        <table id="example" class="table align-middle table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Status Akun</th>
                                    <th data-sortable="false">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $pengguna)
                                    <tr>
                                        <td>
                                            <div class="d-flex">
                                                <div class="me-3">
                                                    <img src="{{ url('assets/onedash/images/avatars/user_profil.png') }}"
                                                        class="rounded-circle" width="50">
                                                </div>
                                                <div class="d-flex flex-column justify-content-center">
                                                    <h6 class="mb-0">{{ $pengguna->nama }}</h6>
                                                    <p class="text-secondary mb-0" style="font-size: 12px">
                                                        {{ $pengguna->role === 'manajer' ? 'Manajer Produksi' : ucwords(strtolower($pengguna->role)) }}
                                                    </p>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $pengguna->email }}</td>
                                        <td>
                                            @if ($pengguna->status_akun == 'aktif')
                                                <span class="badge bg-success px-3 py-2">Aktif</span>
                                            @else
                                                <span class="badge bg-danger px-3 py-2">Tidak Aktif</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-start justify-content-start gap-3 fs-6">
                                                <!-- Tombol Edit -->
                                                <a href="{{ route('owner.pengguna.edit', $pengguna->pengguna_id) }}"
                                                    class="btn btn-sm btn-warning d-flex align-items-center">
                                                    <i class="bi bi-pencil-fill me-1"></i> Edit
                                                </a>

                                                <!-- Tombol Hapus -->
                                                <button type="button"
                                                    class="btn btn-sm btn-danger d-flex align-items-center"
                                                    data-bs-toggle="modal" data-bs-target="#confirmDeleteModal"
                                                    data-form-id="delete-form-{{ $pengguna->pengguna_id }}">
                                                    <i class="bi bi-trash-fill me-1"></i> Hapus
                                                </button>

                                                <!-- Form Hapus -->
                                                <form id="delete-form-{{ $pengguna->pengguna_id }}"
                                                    action="{{ route('owner.pengguna.destroy', $pengguna->pengguna_id) }}"
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

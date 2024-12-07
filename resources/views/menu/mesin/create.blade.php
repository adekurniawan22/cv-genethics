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
                        <li class="breadcrumb-item">
                            <a href="{{ route(session()->get('role') . '.mesin.index') }}">Mesin</a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">
                            <span class="text-dark">Tambah Mesin</span>
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
        <!-- End Breadcrumb -->

        <div class="row ms-0 me-1">
            <div class="card radius-10 w-100">
                <div class="card-body">
                    <form action="{{ route(session()->get('role') . '.mesin.store') }}" method="POST">
                        @csrf

                        <div class="form-group mb-3">
                            <label class="form-label" for="nama_mesin">Nama Mesin</label>
                            <input type="text" id="nama_mesin" name="nama_mesin"
                                class="form-control @error('nama_mesin') is-invalid @enderror"
                                value="{{ old('nama_mesin') }}" placeholder="Masukkan nama mesin">
                            @error('nama_mesin')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label" for="status">Status</label>
                            <select id="status" name="status" class="form-select @error('status') is-invalid @enderror">
                                <option value="">Pilih Status</option>
                                <option value="aktif" {{ old('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                <option value="tidak aktif" {{ old('status') == 'tidak aktif' ? 'selected' : '' }}>Tidak
                                    Aktif</option>
                            </select>
                            @error('status')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label" for="keterangan_mesin">Keterangan Mesin</label>
                            <input type="text" id="keterangan_mesin" name="keterangan_mesin"
                                class="form-control @error('keterangan_mesin') is-invalid @enderror"
                                value="{{ old('keterangan_mesin') }}" placeholder="Masukkan keterangan mesin">
                            @error('keterangan_mesin')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label" for="kapasitas_per_hari">Kapasitas Per Hari</label>
                            <input type="number" id="kapasitas_per_hari" name="kapasitas_per_hari"
                                class="form-control @error('kapasitas_per_hari') is-invalid @enderror"
                                value="{{ old('kapasitas_per_hari') }}" placeholder="Masukkan kapasitas per hari">
                            @error('kapasitas_per_hari')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="text-end mb-3 mt-4">
                            <a href="{{ route(session()->get('role') . '.mesin.index') }}" class="btn btn-dark">Kembali</a>
                            <button type="submit" class="btn btn-danger">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
@endsection

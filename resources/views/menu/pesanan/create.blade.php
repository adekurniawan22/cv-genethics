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
                        <li class="breadcrumb-item">
                            <a href="{{ route('owner.pesanan.index') }}">Pesanan</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Tambah Pesanan
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
        <!-- End Breadcrumb -->

        <div class="row ms-0 me-1">
            <div class="card radius-10 w-100">
                <div class="card-body">
                    <form action="{{ route('owner.pesanan.store') }}" method="POST">
                        @csrf

                        <div class="form-group mb-3">
                            <label class="form-label" for="pengguna_id">Pengguna</label>
                            <select name="pengguna_id" id="pengguna_id"
                                class="form-select @error('pengguna_id') is-invalid @enderror">
                                <option value="">Pilih Pengguna</option>
                                @foreach ($pengguna as $p)
                                    <option value="{{ $p->pengguna_id }}"
                                        {{ old('pengguna_id') == $p->pengguna_id ? 'selected' : '' }}>
                                        {{ $p->nama }}
                                    </option>
                                @endforeach
                            </select>
                            @error('pengguna_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label" for="detail_pesanan">Detail Pesanan</label>
                            <textarea name="detail_pesanan" id="detail_pesanan" rows="4"
                                class="form-control @error('detail_pesanan') is-invalid @enderror" placeholder="Masukkan detail pesanan">{{ old('detail_pesanan') }}</textarea>
                            @error('detail_pesanan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label" for="status">Status</label>
                            <select name="status" id="status" class="form-select @error('status') is-invalid @enderror">
                                <option value="">Pilih Status</option>
                                <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>
                                    Pending
                                </option>
                                <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>
                                    Completed
                                </option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="text-end mt-4">
                            <a href="{{ route('owner.pesanan.index') }}" class="btn btn-dark">Kembali</a>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
@endsection

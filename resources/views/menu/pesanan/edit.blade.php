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
                        <li class="breadcrumb-item">
                            <a href="{{ route('owner.pesanan.index') }}">Pesanan</a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">
                            <span class="text-dark">Edit Pesanan</span>
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
        <!-- End Breadcrumb -->

        <div class="row ms-0 me-1">
            <div class="card radius-10 w-100">
                <div class="card-body">
                    <form action="{{ route('owner.pesanan.update', $pesanan->pesanan_id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="form-group mb-3">
                            <label class="form-label" for="pengguna_id">Pengguna</label>
                            <select id="pengguna_id" name="pengguna_id"
                                class="form-select @error('pengguna_id') is-invalid @enderror">
                                <option value="">Pilih Pengguna</option>
                                @foreach ($pengguna as $p)
                                    <option value="{{ $p->pengguna_id }}"
                                        {{ old('pengguna_id', $pesanan->pengguna_id) == $p->pengguna_id ? 'selected' : '' }}>
                                        {{ $p->nama }}
                                    </option>
                                @endforeach
                            </select>
                            @error('pengguna_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label" for="detail_pesanan">Detail Pesanan</label>
                            <textarea id="detail_pesanan" name="detail_pesanan" rows="4"
                                class="form-control @error('detail_pesanan') is-invalid @enderror" placeholder="Masukkan detail pesanan">{{ old('detail_pesanan', $pesanan->detail_pesanan) }}</textarea>
                            @error('detail_pesanan')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label" for="status">Status</label>
                            <select id="status" name="status" class="form-select @error('status') is-invalid @enderror">
                                <option value="">Pilih Status</option>
                                <option value="pending"
                                    {{ old('status', $pesanan->status) == 'pending' ? 'selected' : '' }}>
                                    Pending
                                </option>
                                <option value="completed"
                                    {{ old('status', $pesanan->status) == 'completed' ? 'selected' : '' }}>
                                    Completed
                                </option>
                            </select>
                            @error('status')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="text-end mb-3 mt-4">
                            <a href="{{ route('owner.pesanan.index') }}" class="btn btn-dark">Kembali</a>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
@endsection

@section('script')
    <script>
        // Add any additional JavaScript if needed
        document.addEventListener('DOMContentLoaded', function() {
            // You can add custom JavaScript for form handling here
            // For example, form validation, dynamic UI updates, etc.
        });
    </script>
@endsection

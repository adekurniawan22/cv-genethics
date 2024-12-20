@extends('layout.main')
@section('content')
    <main class="page-content">
        <!-- Breadcrumb -->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">
                            <span class="text-dark">Dashboard</span>
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
        <!-- End Breadcrumb -->

        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card radius-10 bg-gradient-cosmic">
                    <div class="card-body">
                        <div class="text-start">
                            <h2 class="text-dark">Selamat Datang di Panel Admin</h2>
                            <p class="text-dark mb-4">
                                Anda adalah pemantau utama dari sistem kami. Di sini, Anda dapat mengatur semua pesanan yang
                                masuk dan memastikan
                                kelancaran operasional.
                            </p>
                            <a href="{{ route('admin.pesanan.index') }}" class="btn btn-danger">
                                <i class="bx bx-package"></i> Kelola Pesanan
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-3 col-md-6">
                <div class="card radius-10 bg-purple-gradient">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <p class="mb-0 text-dark">Jumlah Pesanan Selesai ({{ $currentMonthName }})</p>
                                <h4 class="my-1 text-dark">{{ $totalPesananSelesaiBulanIni }}</h4>
                            </div>
                            <div class="text-dark ms-auto font-35"><i class="bx bx-check-circle"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card radius-10 bg-purple-gradient">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <p class="mb-0 text-dark">Jumlah Pesanan Proses ({{ $currentMonthName }})</p>
                                <h4 class="my-1 text-dark">{{ $totalPesananProsesBulanIni }}</h4>
                            </div>
                            <div class="text-dark ms-auto font-35"><i class="bx bx-time-five"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

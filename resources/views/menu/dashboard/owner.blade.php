@extends('layout.main')
@section('content')
    <main class="page-content">
        <!-- Breadcrumb -->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ route(session()->get('role') . '.dashboard') }}"><i
                                    class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">
                            <span class="text-dark">Dashboard</span>
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
        <!-- End Breadcrumb -->

        <div class="row">
            <div class="col-xl-3 col-md-6">
                <div class="card radius-10 bg-teal-gradient">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <p class="mb-0 text-dark">Jumlah Mesin</p>
                                <h4 class="my-1 text-dark">{{ $totalMesin }}</h4>
                            </div>
                            <div class="text-dark ms-auto font-35"><i class="bx bx-package"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card radius-10 bg-pink-gradient">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <p class="mb-0 text-dark">Jumlah Penjahit</p>
                                <h4 class="my-1 text-dark">{{ $totalPenjahit }}</h4>
                            </div>
                            <div class="text-dark ms-auto font-35"><i class="bx bx-user-pin"></i>
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
                                <p class="mb-0 text-dark">Jumlah Pengguna</p>
                                <h4 class="my-1 text-dark">{{ $totalPengguna }}</h4>
                            </div>
                            <div class="text-dark ms-auto font-35"><i class="bx bx-group"></i>
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
                                <p class="mb-0 text-dark">Jumlah Pesanan Selesai</p>
                                <h4 class="my-1 text-dark">{{ $totalPesananSelesai }}</h4>
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
                                <p class="mb-0 text-dark">Jumlah Pesanan Pending</p>
                                <h4 class="my-1 text-dark">{{ $totalPesananPending }}</h4>
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

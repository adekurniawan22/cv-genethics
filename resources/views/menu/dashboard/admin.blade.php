@extends('layout.main')
@section('content')
    <main class="page-content">
        <!-- Breadcrumb -->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3"
            style="height: 37px; overflow: hidden; display: flex; align-items: center;">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="<?= route('admin.dashboard') ?>"><i
                                    class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page"> <span class="text-dark">Dashboard</span></li>
                    </ol>
                </nav>
            </div>
        </div>
        <!-- End Breadcrumb -->

        <div class="row justify-content-center">
            <h1>Selamat datang di Admin Dashboard</h1>
            <p>Your User ID is: {{ session('pengguna_id') }}</p>
            <p>Your Role is: {{ session('role') }}</p>
        </div>
    </main>
@endsection

@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h2 class="font-weight-bold">
                Dashboard Admin
            </h2>

            <p class="text-muted mb-0">
                Selamat datang di Sistem Informasi BeasiswaPedia
            </p>
        </div>

        <a href="{{ route('admin.beasiswa.create') }}"
           class="btn btn-primary">

            <i class="fas fa-plus"></i>
            Tambah Beasiswa

        </a>

    </div>

    <!-- Statistik -->

    <div class="row">

        <div class="col-lg-3 col-md-6">

            <div class="card shadow border-0">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <h5>Total User</h5>

                            <h2 class="font-weight-bold text-primary">

                                {{ $totalUser }}

                            </h2>

                        </div>

                        <div>

                            <i class="fas fa-users fa-3x text-primary"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>

        <div class="col-lg-3 col-md-6">

            <div class="card shadow border-0">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <h5>Mahasiswa</h5>

                            <h2 class="font-weight-bold text-success">

                                {{ $totalMahasiswa }}

                            </h2>

                        </div>

                        <div>

                            <i class="fas fa-user-graduate fa-3x text-success"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>

        <div class="col-lg-3 col-md-6">

            <div class="card shadow border-0">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <h5>Admin</h5>

                            <h2 class="font-weight-bold text-warning">

                                {{ $totalAdmin }}

                            </h2>

                        </div>

                        <div>

                            <i class="fas fa-user-shield fa-3x text-warning"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>

        <div class="col-lg-3 col-md-6">

            <div class="card shadow border-0">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <h5>Beasiswa</h5>

                            <h2 class="font-weight-bold text-danger">

                                {{ $totalBeasiswa }}

                            </h2>

                        </div>

                        <div>

                            <i class="fas fa-graduation-cap fa-3x text-danger"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <!-- Menu Cepat -->

    <div class="row mt-4">

        <div class="col-md-6">

            <div class="card shadow">

                <div class="card-header bg-primary text-white">

                    Menu Cepat

                </div>

                <div class="card-body">

                    <a href="{{ route('admin.beasiswa.index') }}"
                       class="btn btn-outline-primary btn-block mb-2">

                        <i class="fas fa-graduation-cap"></i>

                        Kelola Beasiswa

                    </a>

                    <a href="{{ route('admin.users.index') }}"
                       class="btn btn-outline-success btn-block">

                        <i class="fas fa-users"></i>

                        Kelola Pengguna

                    </a>

                </div>

            </div>

        </div>

        <div class="col-md-6">

            <div class="card shadow">

                <div class="card-header bg-success text-white">

                    Informasi

                </div>

                <div class="card-body">

                    <h5>Selamat Datang 👋</h5>

                    <p>

                        Dashboard ini digunakan untuk mengelola seluruh data
                        BeasiswaPedia, mulai dari data beasiswa, pengguna,
                        hingga informasi sistem.

                    </p>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection
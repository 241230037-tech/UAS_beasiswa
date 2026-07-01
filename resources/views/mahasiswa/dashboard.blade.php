@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- Hero --}}
    <div class="card border-0 shadow-lg overflow-hidden mb-4">

        <div class="card-body p-5"
            style="background:linear-gradient(135deg,#2563EB,#4F46E5);color:white;border-radius:20px;">

            <div class="row align-items-center">

                <div class="col-lg-8">

                    <small class="text-white-50">
                        ☀️ Selamat Datang
                    </small>

                    <h2 class="fw-bold mt-2">
                        {{ Auth::user()->name }} 👋
                    </h2>

                    <p class="mt-3 text-white-50">

                        Temukan beasiswa impianmu dan raih masa depan yang lebih cerah bersama BeasiswaPedia.

                    </p>

                    <div class="input-group mt-4">

                        <input
                            type="text"
                            class="form-control form-control-lg"
                            placeholder="Cari beasiswa...">

                        <button class="btn btn-light">

                            <i class="fas fa-search"></i>

                        </button>

                    </div>

                </div>

                <div class="col-lg-4 text-center">

                    <i class="fas fa-user-graduate"
                       style="font-size:130px;opacity:.15;">
                    </i>

                </div>

            </div>

        </div>

    </div>

    {{-- Statistik --}}

    <div class="row mb-4">

        <div class="col-md-4">

            <div class="card border-0 shadow-sm">

                <div class="card-body d-flex align-items-center">

                    <div class="rounded-circle bg-primary text-white p-3">

                        <i class="fas fa-graduation-cap fa-lg"></i>

                    </div>

                    <div class="ms-4">

                        <h3 class="mb-0">24</h3>

                        <small class="text-muted">
                            Beasiswa Aktif
                        </small>

                    </div>

                </div>

            </div>

        </div>

        <div class="col-md-4">

            <div class="card border-0 shadow-sm">

                <div class="card-body d-flex align-items-center">

                    <div class="rounded-circle bg-success text-white p-3">

                        <i class="fas fa-heart"></i>

                    </div>

                    <div class="ms-4">

                        <h3 class="mb-0">5</h3>

                        <small class="text-muted">
                            Bookmark
                        </small>

                    </div>

                </div>

            </div>

        </div>

        <div class="col-md-4">

            <div class="card border-0 shadow-sm">

                <div class="card-body d-flex align-items-center">

                    <div class="rounded-circle bg-warning text-white p-3">

                        <i class="fas fa-clock"></i>

                    </div>

                    <div class="ms-4">

                        <h3 class="mb-0">3</h3>

                        <small class="text-muted">
                            Deadline
                        </small>

                    </div>

                </div>

            </div>

        </div>

    </div>

    {{-- Konten --}}

    <div class="row">

        <div class="col-lg-8">

            <div class="card border-0 shadow-sm">

                <div class="card-header bg-white">

                    <div class="d-flex justify-content-between">

                        <h5 class="fw-bold">

                            🔥 Beasiswa Terbaru

                        </h5>

                        <a href="#">

                            Lihat Semua

                        </a>

                    </div>

                </div>

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center py-3 border-bottom">

                        <div>

                            <h6 class="fw-bold mb-1">
                                LPDP S2 Dalam Negeri
                            </h6>

                            <small class="text-muted">

                                Fully Funded

                            </small>

                        </div>

                        <button class="btn btn-outline-primary btn-sm">

                            Detail

                        </button>

                    </div>

                    <div class="d-flex justify-content-between align-items-center py-3 border-bottom">

                        <div>

                            <h6 class="fw-bold mb-1">
                                Bank Indonesia
                            </h6>

                            <small class="text-muted">

                                Semester 4+

                            </small>

                        </div>

                        <button class="btn btn-outline-primary btn-sm">

                            Detail

                        </button>

                    </div>

                    <div class="d-flex justify-content-between align-items-center py-3">

                        <div>

                            <h6 class="fw-bold mb-1">
                                Pertamina Foundation
                            </h6>

                            <small class="text-muted">

                                S1

                            </small>

                        </div>

                        <button class="btn btn-outline-primary btn-sm">

                            Detail

                        </button>

                    </div>

                </div>

            </div>

        </div>

        <div class="col-lg-4">

            <div class="card border-0 shadow-sm">

                <div class="card-header bg-white">

                    <h5 class="fw-bold">

                        ⏰ Deadline Terdekat

                    </h5>

                </div>

                <div class="card-body">

                    <div class="mb-4">

                        <strong>LPDP</strong>

                        <br>

                        <small class="text-danger">

                            2 hari lagi

                        </small>

                    </div>

                    <div class="mb-4">

                        <strong>Bank Indonesia</strong>

                        <br>

                        <small class="text-warning">

                            5 hari lagi

                        </small>

                    </div>

                    <div>

                        <strong>Pertamina</strong>

                        <br>

                        <small class="text-success">

                            12 hari lagi

                        </small>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection
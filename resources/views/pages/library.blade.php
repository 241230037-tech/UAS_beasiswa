{{--
    View: pages/library.blade.php

    Halaman Katalog Beasiswa (Library) — menampilkan seluruh daftar beasiswa
    yang tersedia dengan fitur filter dan pencarian berbasis JavaScript (client-side).

    Data yang diterima dari PageController::library():
      - $scholarships : Array semua data beasiswa dari database
      - $filters      : Array parameter filter aktif, berisi:
          - 'q' : Kata kunci pencarian dari query string URL (?q=...)

    Arsitektur filter:
      - Semua filter (urutkan, status, level, negara) ditangani JavaScript di sisi client.
      - Tidak ada reload halaman saat filter berubah (SPA-like experience).
      - Data beasiswa disuntikkan ke atribut data-scholarships sebagai JSON.
      - JavaScript membaca atribut tersebut dan merender ulang grid secara dinamis.

    Template yang di-extend: layouts/app.blade.php
    Partial yang digunakan:
      - partials/navbar           : Navbar navigasi atas
      - partials/library-filter   : Panel filter sidebar (desktop & mobile)
      - partials/scholarship-card : Kartu satu beasiswa
--}}

@extends('layouts.app')

{{-- Judul tab browser untuk halaman katalog beasiswa --}}
@section('title', 'Katalog Beasiswa - Beasiswapedia')

@section('content')
{{-- Container utama halaman library --}}
<div class="min-h-screen bg-background">
    {{-- Navbar navigasi atas — reusable partial --}}
    @include('partials.navbar')

    {{--
        Container konten library.
        data-scholarships: menyuntikkan seluruh data beasiswa ke JavaScript sebagai JSON.
        data-initial-q: kata kunci pencarian awal dari query string (?q=...) untuk pra-isi filter.
    --}}
    <div id="library-page" class="max-w-screen-xl mx-auto px-4 py-6"
        data-scholarships='@json(collect($scholarships)->map(fn($s) => array_merge($s, ["image" => asset(ltrim($s["image"], "/"))]))->values())'
        data-initial-q="{{ $filters['q'] ?? '' }}">

        {{-- Layout dua kolom: sidebar filter (kiri) + grid beasiswa (kanan) --}}
        <div class="flex gap-5">

            {{-- Sidebar Filter — hanya tampil di layar medium ke atas (md+) --}}
            <div class="hidden md:block w-52 shrink-0">
                {{-- Sticky agar panel filter tetap terlihat saat scroll --}}
                <div class="sticky top-20">
                    @include('partials.library-filter')
                </div>
            </div>

            {{-- Area Konten Utama: header, filter mobile, grid beasiswa --}}
            <div class="flex-1 min-w-0">

                {{-- Header area konten: judul, jumlah beasiswa, dan tombol filter mobile --}}
                <div class="flex items-center justify-between mb-4">
                    <h1 class="text-foreground font-bold text-lg">Katalog Beasiswa</h1>
                    <div class="flex items-center gap-2">
                        {{-- Jumlah beasiswa yang ditampilkan (diperbarui JavaScript saat filter aktif) --}}
                        <p class="text-muted-foreground text-xs"><span id="library-count">{{ count($scholarships) }}</span> beasiswa ditemukan</p>
                        {{-- Tombol buka filter mobile — hanya tampil di layar kecil (md ke bawah) --}}
                        <button type="button" id="btn-mobile-filter" class="md:hidden flex items-center gap-1 px-3 py-1.5 text-xs font-semibold bg-muted text-foreground rounded-lg border border-border">Filter</button>
                    </div>
                </div>

                {{-- Panel Filter Mobile — tersembunyi secara default, muncul saat tombol "Filter" diklik --}}
                <div id="mobile-filter" class="md:hidden mb-4 hidden">
                    @include('partials.library-filter')
                </div>

                {{-- Pesan kosong — tampil jika tidak ada beasiswa yang cocok dengan filter aktif --}}
                <div id="library-empty" class="text-center py-16 text-muted-foreground hidden">
                    <p class="text-lg mb-2">Tidak ada beasiswa ditemukan</p>
                    <p class="text-sm">Coba ubah filter atau kata kunci pencarian</p>
                </div>

                {{--
                    Grid Kartu Beasiswa.
                    Dirender oleh server-side Blade pada load pertama.
                    JavaScript akan memanipulasi grid ini saat filter/pencarian aktif.
                --}}
                <div id="library-grid" class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3">
                    @foreach($scholarships as $scholarship)
                        {{-- Render kartu individual untuk setiap beasiswa --}}
                        @include('partials.scholarship-card', ['scholarship' => $scholarship])
                    @endforeach
                </div>

            </div>
        </div>
    </div>
</div>
@endsection

{{--
    View: pages/dashboard.blade.php

    Halaman Dashboard akun pengguna Beasiswapedia.
    Menampilkan:
      - Statistik bookmark dan beasiswa tersedia
      - Form pencarian beasiswa dalam bookmark
      - Tab Bookmark (tersimpan) dan Tab Ditandai
      - Grid kartu beasiswa yang telah di-bookmark

    Data yang diterima dari PageController::dashboard():
      - $scholarships : Array semua data beasiswa dari database

    Catatan: Data bookmark aktual (scholarship_id yang dibookmark)
    diambil secara terpisah via AJAX GET /api/bookmarks oleh JavaScript
    setelah halaman dimuat, sehingga tidak perlu login server-side.

    Template yang di-extend: layouts/app.blade.php
--}}

@extends('layouts.app')

{{-- Judul tab browser untuk halaman dashboard --}}
@section('title', 'Dashboard - Beasiswapedia')

@section('content')
{{-- Container utama halaman dashboard dengan background adaptif (dark/light) --}}
<div class="min-h-screen bg-background">
    {{-- Navbar navigasi atas — reusable partial --}}
    @include('partials.navbar')

    {{--
        Container konten dashboard.
        data-scholarships: menyuntikkan data PHP ke JavaScript sebagai atribut HTML JSON.
        JavaScript akan membaca atribut ini untuk menampilkan kartu beasiswa yang dibookmark.
        asset() + ltrim() memastikan URL gambar selalu absolut dan tidak ada slash ganda.
    --}}
    <div id="dashboard-bookmarks" class="max-w-screen-xl mx-auto px-4 py-6 max-w-4xl"
        data-scholarships='@json(collect($scholarships)->map(fn($s) => array_merge($s, ["image" => asset(ltrim($s["image"], "/"))]))->values())'>

        {{-- Header: judul halaman dan tombol aksi (refresh, tandai semua) --}}
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-foreground font-bold text-2xl">Dashboard</h1>
            <div class="flex items-center gap-2">
                {{-- Tombol Refresh: memuat ulang data bookmark dari server --}}
                <button type="button" class="p-2 text-muted-foreground hover:text-foreground transition-colors" title="Refresh">
                    <i data-lucide="refresh-cw" class="w-5 h-5"></i>
                </button>
                {{-- Tombol Tandai Semua: menandai semua beasiswa sekaligus --}}
                <button type="button" class="p-2 text-muted-foreground hover:text-foreground transition-colors" title="Tandai semua">
                    <i data-lucide="check" class="w-5 h-5"></i>
                </button>
            </div>
        </div>

        {{-- Kartu Statistik: Bookmark, Ditandai, dan Total Beasiswa Tersedia --}}
        <div class="grid grid-cols-3 gap-3 mb-5">
            {{-- Kartu jumlah beasiswa yang dibookmark (diisi dinamis oleh JavaScript) --}}
            <div class="bg-card border border-border rounded-xl p-4 text-center">
                <p id="bookmark-count" class="text-3xl font-black text-[#e53935] mb-1">0</p>
                <p class="text-foreground text-sm font-semibold">Bookmark</p>
                <p class="text-muted-foreground text-[10px]">(Maks 50)</p>
            </div>
            {{-- Kartu jumlah beasiswa yang ditandai (fitur future/placeholder) --}}
            <div class="bg-card border border-border rounded-xl p-4 text-center">
                <p class="text-3xl font-black text-[#e53935] mb-1">0</p>
                <p class="text-foreground text-sm font-semibold">Ditandai</p>
                <p class="text-muted-foreground text-[10px]">(Tidak Terbatas)</p>
            </div>
            {{-- Kartu total beasiswa yang tersedia di database --}}
            <div class="bg-card border border-border rounded-xl p-4 text-center">
                <p class="text-3xl font-black text-[#e53935] mb-1">{{ count($scholarships) }}</p>
                <p class="text-foreground text-sm font-semibold">Beasiswa Update</p>
                <p class="text-muted-foreground text-[10px]">(Terbaru)</p>
            </div>
        </div>

        {{-- Form Pencarian Dalam Bookmark (diproses sepenuhnya oleh JavaScript) --}}
        <div class="relative mb-5">
            <i data-lucide="search" class="w-4 h-4 text-muted-foreground absolute left-3 top-1/2 -translate-y-1/2"></i>
            <input type="text" id="dashboard-search" placeholder="Cari di bookmark atau beasiswa yang ditandai..."
                class="w-full bg-muted text-foreground placeholder-muted-foreground px-4 py-3 pl-10 rounded-xl border border-border focus:outline-none focus:ring-2 focus:ring-[#e53935]/50 text-sm">
        </div>

        {{-- Tab Navigasi: Bookmark dan Ditandai --}}
        <div class="flex border-b border-border mb-6">
            {{-- Tab Bookmark: aktif secara default (ditandai dengan border merah) --}}
            <button type="button" id="tab-bookmark" class="px-4 py-2.5 text-sm font-semibold transition-colors border-b-2 -mb-px border-[#e53935] text-[#e53935]">
                <span id="tab-bookmark-label">Bookmark (0)</span>
            </button>
            {{-- Tab Ditandai: tidak aktif secara default --}}
            <button type="button" id="tab-marked" class="px-4 py-2.5 text-sm font-semibold transition-colors border-b-2 -mb-px border-transparent text-muted-foreground hover:text-foreground">
                Ditandai (0)
            </button>
        </div>

        {{-- Grid kartu beasiswa yang dibookmark — diisi secara dinamis oleh JavaScript --}}
        <div id="dashboard-grid" class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3 hidden"></div>

        {{-- Tampilan kosong jika belum ada beasiswa yang dibookmark --}}
        <div id="dashboard-empty" class="text-center py-16 text-muted-foreground">
            <i data-lucide="bookmark" class="w-12 h-12 mx-auto mb-4 opacity-30"></i>
            <p id="empty-title" class="font-semibold text-foreground mb-1">Belum ada beasiswa yang disimpan</p>
            <p id="empty-desc" class="text-sm mb-5">Mulai tambahkan beasiswa ke bookmark!</p>
            {{-- Tombol ajakan untuk menjelajahi katalog beasiswa --}}
            <a href="{{ route('library') }}" class="inline-block px-6 py-2.5 bg-[#e53935] hover:bg-[#c62828] text-white rounded-full text-sm font-semibold transition-colors">Jelajahi Beasiswa</a>
        </div>

    </div>
</div>
@endsection

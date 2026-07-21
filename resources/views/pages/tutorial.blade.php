{{--
    View: pages/tutorial.blade.php

    Halaman Panduan / Tutorial Pendaftaran Beasiswa.
    Menampilkan langkah-langkah terstruktur untuk mempersiapkan
    dan mendaftarkan beasiswa secara berurutan.

    Data yang diterima dari PageController::tutorial():
      - $steps : Array langkah-langkah tutorial statis dari ScholarshipData::tutorialSteps().
                 Setiap item memiliki key: 'num', 'icon', 'title', 'desc', dan 'tips' (array).

    Template yang di-extend: layouts/app.blade.php
--}}

@extends('layouts.app')

{{-- Judul tab browser untuk halaman tutorial --}}
@section('title', 'Panduan Daftar Beasiswa - Beasiswapedia')

@section('content')
{{-- Container utama halaman tutorial --}}
<div class="min-h-screen bg-background text-foreground font-sans">
    {{-- Navbar navigasi atas — reusable partial --}}
    @include('partials.navbar')

    {{-- Konten utama tutorial dengan padding horizontal dan vertikal --}}
    <div class="max-w-screen-xl mx-auto px-4 py-8 max-w-4xl">

        {{-- Hero Section: Judul dan deskripsi singkat halaman tutorial --}}
        <div class="text-center mb-12">
            {{-- Badge label "Panduan Lengkap" di atas judul --}}
            <div class="inline-flex items-center gap-2 px-3 py-1 bg-blue-100 dark:bg-blue-950/40 text-[#0052cc] rounded-full text-xs font-bold mb-4 border border-blue-200 dark:border-blue-900/30">
                <i data-lucide="help-circle" class="w-4 h-4"></i>
                Panduan Lengkap
            </div>
            {{-- Judul utama halaman dengan efek 3D teks --}}
            <h1 class="text-3xl md:text-5xl font-black mb-4 select-none tracking-tight">
                <span class="text-3d-dark">PANDUAN DAFTAR</span> <span class="text-3d-red">BEASISWA</span>
            </h1>
            {{-- Deskripsi singkat tujuan halaman tutorial --}}
            <p class="text-muted-foreground text-sm md:text-base max-w-xl mx-auto">
                Ikuti 6 langkah terstruktur ini untuk mempersiapkan aplikasi beasiswa Anda secara matang dari awal hingga dinyatakan lulus!
            </p>
        </div>

        {{-- Daftar Langkah-Langkah Tutorial (dirender dari $steps) --}}
        <div class="space-y-8">
            @foreach($steps as $step)
                {{-- Kartu satu langkah tutorial dengan efek 3D dan layout flex --}}
                <div class="card-3d p-6 md:p-8 rounded-2xl bg-card border-2 border-border flex flex-col md:flex-row gap-6 relative">

                    {{-- Badge nomor langkah (misal: 1, 2, 3) di pojok kiri atas --}}
                    <div class="absolute -top-4 -left-4 w-10 h-10 rounded-xl bg-[#0052cc] text-white flex items-center justify-center font-black shadow-[2px_2px_0px_0px_rgba(0,0,0,0.15)] border border-blue-700">
                        {{ $step['num'] }}
                    </div>

                    {{-- Ikon langkah tutorial dari library Lucide Icons --}}
                    <div class="w-14 h-14 shrink-0 rounded-2xl bg-blue-50 dark:bg-blue-950/20 border border-blue-100 dark:border-blue-900/30 flex items-center justify-center text-[#0052cc] mt-2">
                        <i data-lucide="{{ $step['icon'] }}" class="w-7 h-7"></i>
                    </div>

                    {{-- Konten teks langkah: judul, deskripsi, dan tips sukses --}}
                    <div class="flex-1 min-w-0">
                        {{-- Judul langkah tutorial --}}
                        <h3 class="text-lg md:text-xl font-bold text-foreground mb-3">{{ $step['title'] }}</h3>
                        {{-- Deskripsi penjelasan langkah --}}
                        <p class="text-muted-foreground text-sm leading-relaxed mb-4">{{ $step['desc'] }}</p>

                        {{-- Kotak Tips Sukses dengan daftar bullet --}}
                        <div class="bg-muted/50 rounded-xl p-4 border border-border">
                            {{-- Label "Tips Sukses" dengan ikon sparkles --}}
                            <p class="text-xs font-bold text-[#0052cc] uppercase tracking-wider mb-2 flex items-center gap-1.5">
                                <i data-lucide="sparkles" class="w-3.5 h-3.5"></i>
                                Tips Sukses:
                            </p>
                            {{-- Daftar tips sukses dalam bentuk bullet list --}}
                            <ul class="space-y-1.5">
                                @foreach($step['tips'] as $tip)
                                    <li class="text-xs text-muted-foreground flex items-start gap-2">
                                        {{-- Bullet point berwarna biru brand --}}
                                        <span class="text-[#0052cc] font-bold mt-0.5">•</span>
                                        <span>{{ $tip }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- CTA Section: Ajakan Konsultasi di Bagian Bawah Halaman --}}
        <div class="card-3d bg-slate-900 dark:bg-slate-950 text-white rounded-2xl p-8 mt-12 text-center border-2 border-blue-600/30 shadow-xl">
            <h3 class="text-2xl font-black text-white mb-2 tracking-tight">Butuh Konsultasi Tambahan?</h3>
            <p class="text-slate-400 text-sm mb-6 max-w-lg mx-auto">
                Anda dapat memanfaatkan fitur Mentoring 1-on-1 atau Konsultasi Gratis bersama Awardee dengan mengklik menu atau spanduk promo di halaman Beranda.
            </p>
            {{-- Tombol kembali ke halaman Beranda --}}
            <a href="{{ url('/home') }}" class="btn-3d-red inline-flex py-3 px-8 rounded-xl font-bold text-sm">Kembali ke Beranda</a>
        </div>

    </div>
</div>
@endsection

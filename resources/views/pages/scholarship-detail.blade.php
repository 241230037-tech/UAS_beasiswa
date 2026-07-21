{{--
    View: pages/scholarship-detail.blade.php

    Halaman Detail Beasiswa — menampilkan informasi lengkap satu beasiswa.
    Diakses dari URL: /scholarship/{id}

    Data yang diterima dari PageController::scholarshipDetail():
      - $scholarship : Array data satu beasiswa dari database (title, provider, image, dll.)
      - $extra       : Array info tambahan statis dari ScholarshipData::extra($id), berisi:
          - 'requirements' : Daftar persyaratan pendaftaran
          - 'benefits'     : Daftar manfaat/fasilitas beasiswa
          - 'about'        : Deskripsi panjang tentang beasiswa

    Template yang di-extend: layouts/app.blade.php
    Partial yang digunakan: partials/navbar
--}}

@extends('layouts.app')

@section('title', $scholarship['title'].' - Beasiswapedia')

@section('content')
@php
    // Tentukan warna badge status: hijau jika dibuka, biru jika akan datang atau lainnya
    $statusColor = ($scholarship['status'] ?? '') === 'Dibuka' ? 'bg-green-500' : 'bg-blue-500';
    // Data timeline proses seleksi beasiswa (statis untuk contoh tampilan)
    $timelineSteps = [
        ['label' => 'Pembukaan Pendaftaran', 'date' => '1 Juni 2026', 'active' => true],
        ['label' => 'Deadline Pengiriman Berkas', 'date' => 'Sesuai beasiswa', 'active' => true],
        ['label' => 'Seleksi Administrasi', 'date' => 'Agustus 2026', 'active' => false],
        ['label' => 'Pengumuman Hasil', 'date' => 'September 2026', 'active' => false],
        ['label' => 'Keberangkatan / Mulai Studi', 'date' => 'Oktober 2026', 'active' => false],
    ];
@endphp
{{-- Container utama halaman detail beasiswa --}}
<div class="min-h-screen bg-background">
    @include('partials.navbar')

    <div id="scholarship-detail-page" data-id="{{ $scholarship['id'] }}" class="max-w-4xl mx-auto px-4 py-6">
        <button type="button" onclick="history.back()" class="flex items-center gap-1.5 text-muted-foreground hover:text-[#0052cc] transition-colors mb-5 text-sm font-medium group">
            <i data-lucide="arrow-left" class="w-4 h-4 group-hover:-translate-x-0.5 transition-transform"></i>
            Kembali
        </button>

        <div class="relative h-48 md:h-64 rounded-2xl overflow-hidden mb-4 border-2 border-border shadow-[4px_4px_0px_0px_rgba(0,0,0,0.08)] bg-white flex items-center justify-center p-6 md:p-10">
            <img src="{{ asset(ltrim($scholarship['image'], '/')) }}" alt="{{ $scholarship['title'] }}" class="max-w-[75%] max-h-[75%] object-contain">
            <div class="absolute top-4 right-4 flex items-center gap-2">
                <span class="text-3xl drop-shadow-md leading-none">{{ $scholarship['flag'] }}</span>
            </div>
            <div class="absolute top-4 left-4 px-3 py-1 text-xs font-bold text-white rounded-full shadow-md {{ $statusColor }}">{{ $scholarship['status'] }}</div>
            @if(!empty($scholarship['level']))
                <div class="absolute top-4 left-24 px-2.5 py-1 text-xs font-bold text-white bg-black/75 rounded-full backdrop-blur-sm flex items-center gap-1 shadow-md">
                    <i data-lucide="graduation-cap" class="w-3 h-3"></i>
                    {{ $scholarship['level'] }}
                </div>
            @endif
            <div class="absolute bottom-4 right-4 px-3 py-1 text-xs font-bold text-white bg-[#0052cc] rounded-lg shadow-md flex items-center gap-1.5">
                <i data-lucide="eye" class="w-3.5 h-3.5"></i>
                {{ $scholarship['visits'] ?? 0 }}x dikunjungi
            </div>
        </div>

        <div class="card-3d p-6 rounded-2xl bg-card border-2 border-border mb-6">
            <p class="text-muted-foreground text-xs mb-1.5 uppercase tracking-wider font-bold">{{ $scholarship['provider'] }}</p>
            <h1 class="text-foreground font-black text-2xl md:text-3xl leading-tight">{{ $scholarship['title'] }}</h1>
        </div>

        <div class="flex gap-3 mb-8 flex-wrap">
            <a href="{{ $scholarship['external_link'] }}" target="_blank" rel="noopener noreferrer"
                class="btn-3d-outline flex-1 min-w-[160px] flex items-center justify-center gap-2 py-3 rounded-xl font-bold text-sm">
                <i data-lucide="external-link" class="w-4 h-4"></i>
                Kunjungi Website Resmi
            </a>
            <a href="{{ route('scholarship.register', ['id' => $scholarship['id']]) }}" id="btn-register-link"
                class="btn-3d-red flex-1 min-w-[160px] flex items-center justify-center gap-2 py-3 rounded-xl font-bold text-sm">
                <i data-lucide="file-text" class="w-4 h-4"></i>
                Daftar Beasiswa
            </a>
            <button type="button" id="btn-bookmark" class="btn-3d-outline flex items-center gap-2 px-5 py-3 rounded-xl font-bold text-sm text-foreground">
                <i data-lucide="bookmark" class="w-4 h-4"></i> Simpan
            </button>
            <button type="button" class="btn-3d-outline p-3 rounded-xl text-foreground">
                <i data-lucide="share-2" class="w-4 h-4"></i>
            </button>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-8">
            @foreach([
                ['icon' => 'map-pin', 'label' => 'Lokasi', 'value' => $scholarship['location'], 'color' => 'text-blue-500', 'bg' => 'bg-blue-50 dark:bg-blue-950/20', 'border' => 'border-blue-200 dark:border-blue-900/30'],
                ['icon' => 'clock', 'label' => 'Deadline', 'value' => $scholarship['deadline'], 'color' => 'text-orange-500', 'bg' => 'bg-orange-50 dark:bg-orange-950/20', 'border' => 'border-orange-200 dark:border-orange-900/30'],
                ['icon' => 'dollar-sign', 'label' => 'Dana', 'value' => $scholarship['amount'], 'color' => 'text-green-500', 'bg' => 'bg-green-50 dark:bg-green-950/20', 'border' => 'border-green-200 dark:border-green-900/30'],
                ['icon' => 'users', 'label' => 'Kuota', 'value' => 'Terbatas', 'color' => 'text-purple-500', 'bg' => 'bg-purple-50 dark:bg-purple-950/20', 'border' => 'border-purple-200 dark:border-purple-900/30'],
            ] as $info)
                <div class="card-3d p-4 rounded-xl flex items-start gap-3 {{ $info['bg'] }} border {{ $info['border'] }}">
                    <div class="w-8 h-8 rounded-lg {{ $info['bg'] }} border {{ $info['border'] }} flex items-center justify-center shrink-0">
                        <i data-lucide="{{ $info['icon'] }}" class="w-4 h-4 {{ $info['color'] }}"></i>
                    </div>
                    <div class="min-w-0">
                        <p class="text-muted-foreground text-[10px] uppercase tracking-wider">{{ $info['label'] }}</p>
                        <p class="text-foreground text-sm font-bold truncate">{{ $info['value'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="card-3d p-6 rounded-2xl bg-card border-2 border-border mb-5">
            <h2 class="text-foreground font-black text-base mb-3 flex items-center gap-2">
                <span class="w-1 h-5 bg-[#0052cc] rounded-full inline-block"></span>
                Deskripsi Beasiswa
            </h2>
            <p class="text-muted-foreground text-sm leading-relaxed">{{ $extra['description'] }}</p>
        </div>

        <div class="card-3d p-6 rounded-2xl bg-card border-2 border-border mb-5">
            <h2 class="text-foreground font-black text-base mb-4 flex items-center gap-2">
                <span class="w-1 h-5 bg-[#0052cc] rounded-full inline-block"></span>
                Syarat & Ketentuan
            </h2>
            <ul class="space-y-3">
                @foreach($extra['requirements'] as $i => $req)
                    <li class="flex items-start gap-3">
                        <span class="w-6 h-6 rounded-full bg-[#0052cc] text-white text-xs flex items-center justify-center shrink-0 font-black mt-0.5 shadow-[1px_1px_0px_0px_rgba(0,59,153,0.5)]">{{ $i + 1 }}</span>
                        <span class="text-muted-foreground text-sm leading-relaxed">{{ $req }}</span>
                    </li>
                @endforeach
            </ul>
        </div>

        <div class="card-3d p-6 rounded-2xl bg-card border-2 border-border mb-5">
            <h2 class="text-foreground font-black text-base mb-4 flex items-center gap-2">
                <span class="w-1 h-5 bg-green-500 rounded-full inline-block"></span>
                Manfaat & Cakupan
            </h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                @foreach($extra['benefits'] as $benefit)
                    <div class="flex items-center gap-2.5 p-3 bg-green-50 dark:bg-green-950/20 rounded-xl border border-green-200 dark:border-green-900/30">
                        <i data-lucide="check-circle" class="w-4 h-4 text-green-500 shrink-0"></i>
                        <span class="text-foreground text-sm font-medium">{{ $benefit }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="card-3d p-6 rounded-2xl bg-card border-2 border-border mb-8">
            <h2 class="text-foreground font-black text-base mb-5 flex items-center gap-2">
                <i data-lucide="calendar" class="w-4 h-4 text-[#0052cc]"></i>
                Timeline Pendaftaran
            </h2>
            <div class="relative pl-6">
                <div class="absolute left-2 top-2 bottom-2 w-0.5 bg-border rounded-full"></div>
                <div class="space-y-5">
                    @foreach($timelineSteps as $step)
                        <div class="relative flex items-start gap-3">
                            <div class="absolute -left-6 w-4 h-4 rounded-full border-2 flex items-center justify-center top-0.5 {{ $step['active'] ? 'border-[#0052cc] bg-[#0052cc] shadow-[0_0_0_3px_rgba(0,82,204,0.15)]' : 'border-border bg-background' }}">
                                @if($step['active'])<span class="w-1.5 h-1.5 rounded-full bg-white"></span>@endif
                            </div>
                            <div>
                                <p class="text-sm font-semibold {{ $step['active'] ? 'text-foreground' : 'text-muted-foreground' }}">{{ $step['label'] }}</p>
                                <p class="text-xs text-muted-foreground">{{ $step['date'] }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

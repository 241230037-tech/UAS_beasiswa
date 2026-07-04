@extends('layouts.app')

@section('title', 'PortalBeasiswa - Wujudkan Mimpi Pendidikan Tinggimu')

@section('head')
<script>document.documentElement.classList.remove('dark');</script>
@endsection

@section('content')
<div class="min-h-screen page-landing bg-white text-slate-800 flex flex-col items-center justify-start overflow-x-hidden font-sans relative">
    <div class="absolute inset-0 opacity-10 pointer-events-none" style="background-image: linear-gradient(rgba(229,57,53,0.3) 1.5px, transparent 1.5px), linear-gradient(90deg, rgba(229,57,53,0.3) 1.5px, transparent 1.5px); background-size: 60px 60px;"></div>
    <div class="absolute top-10 left-10 w-96 h-96 bg-red-100/40 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute top-40 right-10 w-96 h-96 bg-blue-50/40 rounded-full blur-3xl pointer-events-none"></div>

    <div class="relative w-full max-w-6xl mx-auto px-6 pt-16 pb-20 z-10 flex flex-col items-center text-center">
        <div class="mb-8 p-4 bg-white border-2 border-red-500 rounded-2xl shadow-[4px_4px_0px_0px_#b71c1c] flex items-center justify-center">
            <i data-lucide="graduation-cap" class="w-16 h-16 text-[#e53935]"></i>
        </div>

        <div class="mb-6 select-none">
            <h1 class="text-5xl md:text-7xl font-black tracking-tight leading-none mb-4 flex flex-wrap justify-center gap-x-4">
                <span class="text-3d-dark">PORTAL</span>
                <span class="text-3d-red">BEASISWA</span>
            </h1>
        </div>

        <p class="text-xl md:text-2xl font-bold text-[#b71c1c] mb-6 tracking-wide">Wujudkan Mimpi Pendidikan Tinggimu Bersama Kami</p>

        <div class="flex items-start gap-4 bg-slate-50 border-2 border-slate-200 p-5 rounded-2xl max-w-2xl mb-12 shadow-[4px_4px_0px_0px_rgba(0,0,0,0.05)]">
            <span class="text-2xl mt-0.5">🚧</span>
            <div>
                <div class="flex items-center gap-2 mb-1.5">
                    <span class="px-2.5 py-0.5 bg-yellow-100 border border-yellow-300 text-yellow-800 text-[10px] font-bold rounded-full uppercase tracking-wider">Dalam Pengembangan</span>
                </div>
                <p class="text-sm text-slate-600 leading-relaxed">
                    Platform ini masih kami kembangkan secara aktif. Tujuannya satu: <strong>menyatukan informasi beasiswa Indonesia dalam satu tempat</strong> agar tidak ada lagi yang ketinggalan kesempatan. Fitur dan data terus kami perbarui.
                </p>
            </div>
        </div>

        <div class="flex flex-col sm:flex-row gap-5 justify-center w-full max-w-md mb-16">
            <a href="{{ url('/home') }}" class="btn-3d-red flex-1 py-4 px-8 rounded-xl font-bold flex items-center justify-center gap-3 text-base">
                Mulai Jelajahi
                <i data-lucide="arrow-right" class="w-5 h-5"></i>
            </a>
            <a href="{{ route('login') }}" class="btn-3d-outline flex-1 py-4 px-8 rounded-xl font-bold flex items-center justify-center gap-3 text-base">
                Login / Daftar
            </a>
        </div>

        @php
            $stats = [
                ['icon' => 'book-open', 'label' => 'Total Beasiswa', 'value' => '500+'],
                ['icon' => 'globe', 'label' => 'Negara Tujuan', 'value' => '50+'],
                ['icon' => 'star', 'label' => 'Beasiswa Aktif', 'value' => '120+'],
                ['icon' => 'users', 'label' => 'Pengguna Aktif', 'value' => '10K+'],
            ];
        @endphp
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 w-full max-w-4xl">
            @foreach($stats as $stat)
                <div class="card-3d p-6 rounded-2xl flex flex-col items-center justify-center text-center bg-white">
                    <div class="w-12 h-12 rounded-xl bg-red-50 flex items-center justify-center mb-3 border border-red-200">
                        <i data-lucide="{{ $stat['icon'] }}" class="w-6 h-6 text-[#e53935]"></i>
                    </div>
                    <h3 class="text-3xl font-black text-slate-800 leading-none mb-1">{{ $stat['value'] }}</h3>
                    <p class="text-slate-500 text-xs font-semibold uppercase tracking-wider">{{ $stat['label'] }}</p>
                </div>
            @endforeach
        </div>
    </div>

    <div class="relative w-full bg-slate-50 border-y-2 border-slate-200 py-20 z-10">
        <div class="max-w-6xl mx-auto px-6">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-black text-slate-900 mb-4 select-none">Kenapa Menggunakan <span class="text-[#e53935]">PortalBeasiswa</span>?</h2>
                <div class="w-20 h-1 bg-[#e53935] mx-auto rounded-full"></div>
                <p class="text-slate-600 mt-4 text-base md:text-lg max-w-2xl mx-auto">Kami merancang platform ini dengan fitur-fitur penting untuk menyederhanakan pencarian dan manajemen persiapan dokumen beasiswa Anda.</p>
            </div>
            @php
                $features = [
                    ['icon' => 'search', 'title' => 'Pencarian Instan & Cerdas', 'desc' => 'Cari beasiswa impian Anda dengan sistem filter terpadu berdasarkan jenjang pendidikan (S1, S2, S3, Vokasi), lokasi negara, serta status pendaftaran.'],
                    ['icon' => 'shield-check', 'title' => 'Informasi Terverifikasi', 'desc' => 'Setiap informasi beasiswa dilengkapi dengan detail cakupan dana, persyaratan lengkap, timeline resmi, serta tautan langsung ke situs resmi penyelenggara.'],
                    ['icon' => 'bookmark', 'title' => 'Dashboard Bookmark Personal', 'desc' => 'Simpan beasiswa pilihan Anda ke dalam Bookmark lokal browser Anda. Anda juga dapat melakukan backup data dengan fitur Export/Import data secara mandiri.'],
                    ['icon' => 'calendar-range', 'title' => 'Timeline & Tenggat Waktu Jelas', 'desc' => 'Pantau tanggal pembukaan dan penutupan pendaftaran dengan mudah agar Anda tidak melewatkan kesempatan emas untuk masa depan Anda.'],
                ];
            @endphp
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-5xl mx-auto">
                @foreach($features as $feat)
                    <div class="card-3d p-8 rounded-2xl bg-white flex gap-5 items-start">
                        <div class="w-12 h-12 shrink-0 rounded-xl bg-red-50 border border-red-200 flex items-center justify-center text-[#e53935]">
                            <i data-lucide="{{ $feat['icon'] }}" class="w-6 h-6"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-slate-950 mb-2">{{ $feat['title'] }}</h3>
                            <p class="text-slate-600 text-sm leading-relaxed">{{ $feat['desc'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="w-full max-w-6xl mx-auto px-6 py-20 z-10">
        <div class="text-center mb-16">
            <h2 class="text-3xl md:text-4xl font-black text-slate-900 mb-4 select-none">Langkah Mudah Menuju <span class="text-[#e53935]">Beasiswa Impian</span></h2>
            <div class="w-20 h-1 bg-[#e53935] mx-auto rounded-full"></div>
            <p class="text-slate-600 mt-4 text-base md:text-lg max-w-2xl mx-auto">Alur praktis yang membimbing Anda dari proses pencarian hingga pendaftaran resmi.</p>
        </div>
        @php
            $steps = [
                ['step' => '01', 'title' => 'Jelajahi Beasiswa', 'desc' => 'Cari beasiswa di katalog lengkap kami berdasarkan minat jenjang pendidikan Anda.'],
                ['step' => '02', 'title' => 'Baca Detail & Syarat', 'desc' => 'Periksa kelayakan akademis, nilai minimum bahasa Inggris, dan cakupan dana beasiswa.'],
                ['step' => '03', 'title' => 'Simpan & Pantau', 'desc' => 'Simpan info penting ke Dashboard agar Anda bisa memonitor timeline pendaftaran.'],
                ['step' => '04', 'title' => 'Daftar Resmi', 'desc' => 'Kunjungi tautan resmi yang kami sediakan untuk melamar langsung ke penyelenggara beasiswa.'],
            ];
        @endphp
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($steps as $st)
                <div class="relative card-3d p-6 rounded-2xl bg-white border-2 border-slate-200">
                    <div class="absolute -top-6 left-6 text-4xl font-black text-red-500/20 tracking-wider font-mono select-none">{{ $st['step'] }}</div>
                    <h3 class="text-lg font-bold text-slate-950 mt-2 mb-2 flex items-center gap-2">
                        <i data-lucide="check-circle-2" class="w-5 h-5 text-green-500 shrink-0"></i>
                        {{ $st['title'] }}
                    </h3>
                    <p class="text-slate-600 text-sm leading-relaxed">{{ $st['desc'] }}</p>
                </div>
            @endforeach
        </div>
    </div>

    <div class="w-full bg-[#e53935] text-white py-16 text-center relative z-10 border-t-4 border-[#b71c1c]">
        <div class="max-w-4xl mx-auto px-6">
            <h2 class="text-3xl md:text-5xl font-black mb-4 select-none tracking-tight">Siap Memulai Perjalanan Akademis Anda?</h2>
            <p class="text-red-100 text-lg mb-8 max-w-xl mx-auto">Daftarkan diri Anda sekarang untuk menyinkronkan bookmark Anda secara otomatis dan jelajahi ratusan program beasiswa terverifikasi.</p>
            <div class="flex gap-4 justify-center flex-wrap">
                <a href="{{ route('login') }}" class="text-[#e53935] bg-white hover:bg-red-50 py-3 px-8 rounded-xl font-bold shadow-lg shadow-black/25 active:translate-y-1 transition-all">Daftar Sekarang</a>
                <a href="{{ url('/home') }}" class="text-white bg-transparent border-2 border-white hover:bg-white/10 py-3 px-8 rounded-xl font-bold active:translate-y-1 transition-all">Lihat Katalog Beasiswa</a>
            </div>
        </div>
    </div>

    <div class="w-full bg-slate-900 text-slate-400 py-6 text-center text-xs border-t border-slate-800">
        © 2026 PortalBeasiswa. Didesain secara premium untuk masa depan pendidikan Indonesia yang gemilang.
    </div>
</div>
@endsection

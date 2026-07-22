{{--
    View: pages/landing.blade.php

    Halaman Landing Page utama yang pertama kali dilihat pengunjung baru.
    Diakses dari URL: / (root)
    Didesain untuk pengunjung yang belum login, menampilkan:
      - Sticky navbar khusus landing (berbeda dari navbar auth)
      - Slider/Carousel hero dengan slide beasiswa atau promosi
      - Section program unggulan / highlight beasiswa
      - Section statistik, mitra, dan alasan memilih Beasiswapedia
      - Section CTA (Call-To-Action) pendaftaran akun
      - Footer informasi website

    Data yang diterima dari PageController::landing():
      - $ads          : Array maks. 4 iklan dari database (fallback ke data statis jika kosong)
      - $scholarships : Array maks. 5 beasiswa dari database (fallback ke data statis)
      - $carouselItems: Array slide carousel dinamis dari database

    Catatan khusus:
      - Landing page selalu menggunakan mode terang (light mode), diatur via JavaScript
        di @section('head') untuk mencegah kedipan warna saat halaman dimuat.
      - Menggunakan navbar tersendiri (bukan partial navbar auth).

    Template yang di-extend: layouts/app.blade.php
--}}

@extends('layouts.app')

@section('title', 'Beasiswapedia - Wujudkan Mimpi Kuliah & Kerja di Luar Negeri')

@section('head')
<script>
    // Pastikan halaman landing selalu menggunakan mode terang (light mode) agar selaras dengan estetika brand Beasiswapedia
    document.documentElement.classList.remove('dark');
</script>
<style>
    /* Styling tambahan khusus untuk halaman landing */
    .partner-logo-grid img {
        max-height: 48px;
        object-fit: contain;
    }
</style>
@endsection

@section('content')
<div class="page-landing min-h-screen bg-[#fafcff] text-slate-800 flex flex-col items-center justify-start overflow-x-hidden font-sans relative">
    

    <!-- SECTION 2: STICKY PREMIUM NAVBAR (Blue Background) -->
    <nav class="w-full bg-[#0052cc] border-b border-blue-750 sticky top-0 z-40 shadow-lg text-white transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between h-16 sm:h-20 gap-4">
            
            <!-- Logo Brand Unified -->
            <a href="{{ url('/') }}" class="flex items-center gap-2 shrink-0 group">
                <div class="w-10 h-10 rounded-xl bg-white flex items-center justify-center text-[#0052cc] shadow-lg shadow-black/10 group-hover:scale-105 transition-transform duration-300">
                    <i data-lucide="graduation-cap" class="w-6 h-6 stroke-[2.5]"></i>
                </div>
                <div class="flex flex-col">
                    <span class="text-xl font-black text-white leading-none tracking-tight">
                        beasiswa<span class="text-[#ff7300]">pedia</span>
                    </span>
                </div>
            </a>

            <!-- Nav Links (Desktop - White / Light Blue links) -->
            <div class="hidden lg:flex items-center gap-7 text-sm font-semibold text-blue-100">
                <a href="#program-unggulan" class="hover:text-white transition-colors">Program Unggulan</a>
                <a href="{{ route('library') }}" class="hover:text-white transition-colors">Katalog Beasiswa</a>
                <a href="{{ route('tutorial') }}" class="hover:text-white transition-colors">Panduan Daftar</a>
                <a href="#kenapa-kami" class="hover:text-white transition-colors">Kenapa Beasiswapedia</a>
                <a href="javascript:void(0)" onclick="document.getElementById('about-modal').classList.remove('hidden')" class="hover:text-white transition-colors flex items-center gap-1">
                    Hubungi Bantuan
                </a>
            </div>

            <!-- Right Actions (Search & Dynamic Auth CTA) -->
            <div class="flex items-center gap-3 shrink-0">
                
                <!-- Search Pill Form -->
                <form class="hidden md:block relative max-w-[200px] lg:max-w-xs" action="{{ route('library') }}" method="GET">
                    <input type="text" name="q" placeholder="Cari info beasiswa..." class="w-full bg-white/10 border border-blue-400/50 text-white placeholder-blue-100/70 text-xs px-4 py-2 pr-9 rounded-full focus:outline-none focus:bg-white focus:text-slate-800 focus:placeholder-slate-400 transition-all">
                    <button type="submit" class="absolute right-3 top-1/2 -translate-y-1/2 text-blue-100/80 hover:text-white transition-colors">
                        <i data-lucide="search" class="w-4 h-4"></i>
                    </button>
                </form>

                <!-- Dynamic Auth Buttons (Menggunakan LocalStorage Sync) -->
                <div id="landing-auth-container" class="flex items-center gap-2">
                    <!-- Placeholder sebelum di-overwrite oleh JS -->
                    <a href="{{ route('login') }}" class="bg-[#ff7300] hover:bg-[#e65c00] text-white text-xs font-bold px-5 py-2.5 rounded-full shadow-md transition-all">
                        Login / Daftar
                    </a>
                </div>

                <!-- Mobile Menu Toggle -->
                <button type="button" onclick="toggleMobileMenu()" class="lg:hidden p-2 text-blue-100 hover:text-white transition-colors">
                    <i data-lucide="menu" class="w-6 h-6" id="mobile-menu-icon"></i>
                </button>
            </div>
        </div>

        <!-- Mobile Nav Menu (Slide down - Dark Blue) -->
        <div id="mobile-nav-panel" class="hidden border-t border-blue-800 bg-[#003b99] text-white px-4 py-3 space-y-2 lg:hidden shadow-lg absolute w-full left-0 z-50">
            <a href="#program-unggulan" class="block py-2 text-sm font-semibold text-blue-100 hover:text-white">Program Unggulan</a>
            <a href="{{ route('library') }}" class="block py-2 text-sm font-semibold text-blue-100 hover:text-white">Katalog Beasiswa</a>
            <a href="{{ route('tutorial') }}" class="block py-2 text-sm font-semibold text-blue-100 hover:text-white">Panduan Daftar</a>
            <a href="#kenapa-kami" class="block py-2 text-sm font-semibold text-blue-100 hover:text-white">Kenapa Beasiswapedia</a>
            <a href="javascript:void(0)" onclick="document.getElementById('about-modal').classList.remove('hidden')" class="block py-2 text-sm font-semibold text-blue-100 hover:text-white">Hubungi Bantuan</a>
            <form class="relative pt-2" action="{{ route('library') }}" method="GET">
                <input type="text" name="q" placeholder="Cari info beasiswa..." class="w-full bg-white/10 border border-blue-400/50 text-white placeholder-blue-100/70 text-xs px-4 py-2 pr-9 rounded-full focus:outline-none focus:bg-white focus:text-slate-800 focus:placeholder-slate-400">
                <button type="submit" class="absolute right-3 top-1/2 -translate-y-1/2 pt-2 text-blue-100">
                    <i data-lucide="search" class="w-4 h-4"></i>
                </button>
            </form>
        </div>
    </nav>

    <!-- SECTION 3: SKY-BLUE HERO SECTION -->
    <div class="w-full bg-gradient-to-b from-[#0052cc] to-[#003b99] text-white py-16 sm:py-24 relative overflow-hidden">
        <!-- Background Blur & Dot Grid Patterns -->
        <div class="absolute inset-0 dot-grid-bg opacity-15 pointer-events-none"></div>
        <div class="absolute -top-40 -left-40 w-96 h-96 bg-blue-400 rounded-full blur-[120px] opacity-30 pointer-events-none"></div>
        <div class="absolute -bottom-40 -right-40 w-96 h-96 bg-sky-300 rounded-full blur-[120px] opacity-20 pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
            
            <!-- Left Column: Title, Subheading, and WhatsApp Form -->
            <div class="lg:col-span-6 flex flex-col justify-center">
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black tracking-tight leading-[1.1] mb-6">
                    Bimbingan Lengkap <br class="hidden sm:inline">Kuliah & Kerja <br class="hidden sm:inline">di Luar Negeri
                </h1>
                <p class="text-base sm:text-lg text-blue-100 font-medium max-w-xl mb-10 leading-relaxed">
                    Beasiswapedia telah membantu ribuan pelajar & profesional sukses meraih impian menempuh pendidikan tinggi serta karir global yang gemilang.
                </p>

                <!-- WhatsApp & Program Consultation Form Card -->
                <div class="bg-white text-slate-800 p-6 rounded-3xl shadow-2xl relative hero-consultation-card max-w-lg border-2 border-slate-200">
                    <div class="absolute -top-3 -right-3 bg-[#ff7300] text-white text-[10px] font-black uppercase tracking-wider py-1.5 px-3 rounded-lg shadow-md animate-bounce">
                        Promo Terbatas!
                    </div>
                    <h3 class="text-lg font-extrabold text-slate-900 mb-2">Dapatkan penawaran spesial khusus!</h3>
                    <p class="text-xs text-slate-500 mb-5 leading-relaxed">Masukkan nomor WhatsApp Anda untuk konsultasi beasiswa gratis bersama mentor berpengalaman kami.</p>
                    
                    <form id="hero-consult-form" onsubmit="handleConsultationSubmit(event)" class="space-y-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Nomor WhatsApp*</label>
                            <div class="relative rounded-xl shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                    <span class="text-slate-400 font-bold text-sm">+62</span>
                                </div>
                                <input type="tel" id="consult-wa" required placeholder="8123456789" class="block w-full pl-12 pr-4 py-3 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#0052cc] focus:border-[#0052cc] bg-slate-50 text-slate-800 placeholder-slate-400 font-medium">
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Pilih Program Bimbingan*</label>
                            <select id="consult-program" required class="block w-full px-3.5 py-3 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#0052cc] focus:border-[#0052cc] bg-slate-50 text-slate-800 font-medium cursor-pointer">
                                <option value="study-abroad">Study Abroad Academy (S1/S2/S3)</option>
                                <option value="work-abroad">Work Abroad (Kerja di Luar Negeri)</option>
                                <option value="language">IELTS Academy / Test Prep</option>
                                <option value="translation">Document Translation (Penerjemah Tersumpah)</option>
                                <option value="tour">EduTrip / Study Tour Kampus Top</option>
                            </select>
                        </div>
                        <button type="submit" class="w-full bg-[#ff7300] hover:bg-[#e66700] active:scale-[0.98] text-white text-sm font-black py-3.5 px-6 rounded-xl transition-all shadow-[0_4px_12px_rgba(255,115,0,0.3)] hover:-translate-y-0.5 active:translate-y-0 flex items-center justify-center gap-2 cursor-pointer">
                            <span>Dapatkan Penawaran</span>
                            <i data-lucide="arrow-right" class="w-4 h-4"></i>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Right Column: Student Testimonial Grid & Badges -->
            <div class="lg:col-span-6 relative flex flex-col justify-center mt-10 lg:mt-0">
                
                <!-- Testimonial Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    
                    <!-- Card 1: Hanan -->
                    <div class="bg-white/10 backdrop-blur-md border border-white/20 p-5 rounded-2xl shadow-lg hover:bg-white/15 hover:-translate-y-1 transition-all duration-300">
                        <div class="flex items-center gap-3.5 mb-3">
                            <div class="w-12 h-12 rounded-full bg-gradient-to-tr from-orange-400 to-amber-500 flex items-center justify-center text-white font-black text-lg border-2 border-white/30 shrink-0 shadow-md">
                                H
                            </div>
                            <div>
                                <h4 class="font-extrabold text-sm text-white">Hanan</h4>
                                <p class="text-[10px] text-blue-100 font-medium leading-none">Alumni S2 UK</p>
                            </div>
                        </div>
                        <p class="text-xs text-blue-100 font-bold mb-1.5">Imperial College London</p>
                        <p class="text-[10px] text-orange-300 font-bold uppercase tracking-wider leading-relaxed">
                            <i data-lucide="award" class="inline w-3 h-3 mr-0.5 -mt-0.5"></i> Imperial College London Scholarship
                        </p>
                    </div>

                    <!-- Card 2: Richard -->
                    <div class="bg-white/10 backdrop-blur-md border border-white/20 p-5 rounded-2xl shadow-lg hover:bg-white/15 hover:-translate-y-1 transition-all duration-300">
                        <div class="flex items-center gap-3.5 mb-3">
                            <div class="w-12 h-12 rounded-full bg-gradient-to-tr from-sky-400 to-indigo-500 flex items-center justify-center text-white font-black text-lg border-2 border-white/30 shrink-0 shadow-md">
                                R
                            </div>
                            <div>
                                <h4 class="font-extrabold text-sm text-white">Richard</h4>
                                <p class="text-[10px] text-blue-100 font-medium leading-none">Alumni Magang Jepang</p>
                            </div>
                        </div>
                        <p class="text-xs text-blue-100 font-bold mb-1.5">Unagi Farming Industry</p>
                        <p class="text-[10px] text-orange-300 font-bold uppercase tracking-wider leading-relaxed">
                            <i data-lucide="award" class="inline w-3 h-3 mr-0.5 -mt-0.5"></i> Tokutei Ginou - Food Processing
                        </p>
                    </div>

                    <!-- Card 3: Augustine -->
                    <div class="bg-white/10 backdrop-blur-md border border-white/20 p-5 rounded-2xl shadow-lg hover:bg-white/15 hover:-translate-y-1 transition-all duration-300">
                        <div class="flex items-center gap-3.5 mb-3">
                            <div class="w-12 h-12 rounded-full bg-gradient-to-tr from-purple-400 to-pink-500 flex items-center justify-center text-white font-black text-lg border-2 border-white/30 shrink-0 shadow-md">
                                A
                            </div>
                            <div>
                                <h4 class="font-extrabold text-sm text-white">Augustine</h4>
                                <p class="text-[10px] text-blue-100 font-medium leading-none">Alumni S1 Korea</p>
                            </div>
                        </div>
                        <p class="text-xs text-blue-100 font-bold mb-1.5">Seoul National University</p>
                        <p class="text-[10px] text-orange-300 font-bold uppercase tracking-wider leading-relaxed">
                            <i data-lucide="award" class="inline w-3 h-3 mr-0.5 -mt-0.5"></i> Global Korea Scholarship
                        </p>
                    </div>

                    <!-- Card 4: Maulana -->
                    <div class="bg-white/10 backdrop-blur-md border border-white/20 p-5 rounded-2xl shadow-lg hover:bg-white/15 hover:-translate-y-1 transition-all duration-300">
                        <div class="flex items-center gap-3.5 mb-3">
                            <div class="w-12 h-12 rounded-full bg-gradient-to-tr from-emerald-400 to-teal-500 flex items-center justify-center text-white font-black text-lg border-2 border-white/30 shrink-0 shadow-md">
                                M
                            </div>
                            <div>
                                <h4 class="font-extrabold text-sm text-white">Maulana</h4>
                                <p class="text-[10px] text-blue-100 font-medium leading-none">Alumni Karir Jerman</p>
                            </div>
                        </div>
                        <p class="text-xs text-blue-100 font-bold mb-1.5">Bárdi Autó</p>
                        <p class="text-[10px] text-orange-300 font-bold uppercase tracking-wider leading-relaxed">
                            <i data-lucide="award" class="inline w-3 h-3 mr-0.5 -mt-0.5"></i> English for Working Abroad
                        </p>
                    </div>
                </div>

                <!-- Floating Promotion Badges -->
                <!-- Badge 1: Diskon 40% Buku -->
                <div class="absolute -top-12 -right-6 sm:right-6 bg-gradient-to-tr from-red-600 to-orange-500 text-white rounded-2xl p-4 shadow-xl border border-red-400/30 flex items-center gap-3 w-48 animate-landing-float hidden sm:flex pointer-events-none">
                    <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center text-white">
                        <i data-lucide="book-open" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <p class="text-[9px] font-bold uppercase tracking-wider text-red-200">Diskon 40%</p>
                        <p class="text-xs font-black leading-tight">Paket Bundling Buku Spesial!</p>
                    </div>
                </div>

                <!-- Badge 2: Kalender Beasiswa -->
                <div class="absolute -bottom-10 -left-6 bg-indigo-950 text-white rounded-2xl p-4 shadow-xl border border-indigo-800/50 flex items-center gap-3 w-52 animate-landing-float-reverse hidden sm:flex pointer-events-none">
                    <div class="w-10 h-10 rounded-xl bg-indigo-800/40 flex items-center justify-center text-[#ffcd00]">
                        <i data-lucide="calendar-range" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <p class="text-[9px] font-bold uppercase tracking-wider text-indigo-300">FREE DOWNLOAD</p>
                        <p class="text-xs font-black leading-tight">Kalender Beasiswa 2026</p>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- SECTION 4: PARTNER LOGOS -->
    <div id="mitra" class="w-full bg-white py-12 border-b border-slate-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <p class="text-center text-xs font-bold text-slate-400 uppercase tracking-widest mb-8">
                Didukung & Bekerja Sama Dengan Instansi Terkemuka
            </p>
            
            <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 lg:grid-cols-11 gap-6 items-center justify-items-center partner-logo-grid">
                @php
                    $logos = [
                        ['file' => 'lpdp.png', 'name' => 'LPDP'],
                        ['file' => 'pertamina.png', 'name' => 'Pertamina'],
                        ['file' => 'xl.png', 'name' => 'XL'],
                        ['file' => 'astra.png', 'name' => 'Astra'],
                        ['file' => 'kemendikbud.png', 'name' => 'Kemendikbud'],
                        ['file' => 'bankindonesia.png', 'name' => 'Bank Indonesia'],
                        ['file' => 'baznas.png', 'name' => 'BAZNAS'],
                        ['file' => 'bca.png', 'name' => 'BCA'],
                        ['file' => 'brilian.png', 'name' => 'Bank BRI'],
                        ['file' => 'djarum.png', 'name' => 'Djarum Foundation'],
                        ['file' => 'kemenag.png', 'name' => 'Kemenag']
                    ];
                @endphp
                @foreach($logos as $logo)
                    <div class="w-16 h-12 flex items-center justify-center" title="{{ $logo['name'] }}">
                        <img src="{{ asset('images/' . $logo['file']) }}" alt="{{ $logo['name'] }}" class="partner-logo-img cursor-pointer">
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- SECTION 5: PROGRAM UNGGULAN (Replaced with Dynamic Carousel Slider!) -->
    <div id="program-unggulan" class="w-full bg-slate-50 py-20 border-b border-slate-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Section Header -->
            <div class="text-center max-w-3xl mx-auto mb-12">
                <span class="text-xs font-extrabold text-[#0052cc] uppercase tracking-widest">
                    Pilihan Populer Beasiswa
                </span>
                <h2 class="text-3xl sm:text-4xl font-black text-slate-900 mt-2 mb-4 leading-tight">
                    Program Unggulan Beasiswapedia
                </h2>
                <p class="text-slate-500 text-sm sm:text-base leading-relaxed">
                    Daftar program beasiswa terkemuka yang terhubung langsung ke sistem database internal kami. Jelajahi detail lengkapnya secara interaktif!
                </p>
            </div>

            <!-- Dynamic Sliding Carousel -->
            @php $totalSlides = count($carouselItems); @endphp
            @if($totalSlides > 0)
            <div class="relative w-full overflow-hidden rounded-3xl border-2 border-slate-200 shadow-2xl mb-6 bg-slate-950 text-white min-h-[300px] sm:min-h-[360px]" id="home-carousel">
                {{-- Slide Wrapper --}}
                <div class="flex flex-shrink-0 transition-transform duration-500 ease-in-out h-full" id="carousel-slides" style="width: {{ $totalSlides * 100 }}%;">
                    @foreach($carouselItems as $index => $item)
                        @if($item['type'] === 'scholarship' && isset($item['scholarship']))
                            @php $s = $item['scholarship']; @endphp
                            {{-- Slide Beasiswa Terhubung (Trending) --}}
                            <div class="flex-shrink-0 relative overflow-hidden h-[300px] sm:h-[360px] flex items-center justify-between p-6 sm:p-12 text-left" style="width: {{ 100 / $totalSlides }}%; background: linear-gradient(135deg, #0052cc, #002b70)">
                                <div class="absolute inset-0 z-0 opacity-40 bg-cover bg-center" style="background-image: url('{{ $s['image'] }}'); filter: blur(4px);"></div>
                                <div class="absolute inset-0 bg-gradient-to-r from-slate-950 via-slate-950/80 to-transparent z-0"></div>
                                <div class="relative z-10 max-w-xl space-y-3 sm:space-y-4">
                                    <span class="inline-block px-2.5 py-1 text-[10px] font-black text-white bg-[#0052cc] rounded-md tracking-wider uppercase shadow-sm">Trending #{{ $index + 1 }}</span>
                                    <h3 class="text-xl sm:text-3xl font-black leading-tight text-white">{{ $s['title'] }}</h3>
                                    <p class="text-white/80 text-xs sm:text-sm font-medium">{{ $s['provider'] }}. Dapatkan kesempatan pendanaan pendidikan tinggi terbaik.</p>
                                    <div class="flex flex-wrap items-center gap-3 text-xs">
                                        <span class="bg-white/10 px-2.5 py-1 rounded-full font-semibold">📍 {{ $s['location'] }}</span>
                                        <span class="bg-white/10 px-2.5 py-1 rounded-full font-semibold">💎 {{ $s['amount'] }}</span>
                                        <span class="text-red-400 font-bold">Deadline: {{ $s['deadline'] }}</span>
                                    </div>
                                    <div class="pt-2">
                                        <a href="/scholarship/{{ $s['id'] }}" class="bg-[#ff7300] hover:bg-[#e65c00] text-white px-6 py-3 rounded-xl text-xs font-bold inline-flex items-center gap-2 transition-colors">
                                            <i data-lucide="arrow-right" class="w-4 h-4"></i> Lihat Detail & Daftar
                                        </a>
                                    </div>
                                </div>
                                <div class="hidden md:flex relative z-10 w-60 h-60 items-center justify-center bg-white/5 rounded-2xl border border-white/10 backdrop-blur-md shadow-2xl p-6">
                                    <img src="{{ $s['image'] }}" alt="{{ $s['title'] }}" class="max-h-full max-w-full object-contain">
                                </div>
                            </div>
                        @elseif($item['type'] === 'video')
                            {{-- Slide Video Promosi / Frame Video Kosong --}}
                            <div class="flex-shrink-0 relative overflow-hidden h-[300px] sm:h-[360px] flex items-center justify-between p-6 sm:p-10 text-left" style="width: {{ 100 / $totalSlides }}%; background: linear-gradient(135deg, #0f0c29, #302b63, #24243e)">
                                {{-- Background overlay dekoratif --}}
                                <div class="absolute inset-0 bg-gradient-to-r from-[#0f0c29]/90 via-[#302b63]/60 to-transparent z-0"></div>
                                <div class="absolute inset-0 z-0 opacity-10" style="background-image: radial-gradient(circle at 20% 50%, #a78bfa 0%, transparent 50%), radial-gradient(circle at 80% 20%, #38bdf8 0%, transparent 40%);"></div>

                                @if(!empty($item['video_url']))
                                    {{-- Layout 2 kolom: Kiri = Teks, Kanan = Video --}}

                                    {{-- Kolom Kiri: Badge + Judul + Deskripsi --}}
                                    <div class="relative z-10 flex-1 min-w-0 pr-4 sm:pr-8 space-y-3 sm:space-y-4">
                                        <span class="inline-block px-2.5 py-1 text-[10px] font-black text-white bg-violet-600 rounded-md tracking-wider uppercase shadow-sm">
                                            🎬 {{ $item['subtitle'] ?? 'Video Promosi' }}
                                        </span>
                                        <h3 class="text-lg sm:text-2xl font-black leading-tight text-white drop-shadow-lg">
                                            {{ $item['title'] ?? 'Video Beasiswa' }}
                                        </h3>
                                        @if(!empty($item['description']))
                                        <p class="text-white/75 text-xs sm:text-sm font-medium leading-relaxed line-clamp-3">
                                            {{ $item['description'] }}
                                        </p>
                                        @endif
                                    </div>

                                    {{-- Kolom Kanan: Video Player --}}
                                    <div class="relative z-10 flex-shrink-0 w-full max-w-[280px] sm:max-w-xs md:max-w-sm">
                                        @if(str_starts_with(trim($item['video_url']), '<'))
                                            {{-- Embed iframe (YouTube, dll) --}}
                                            <div class="rounded-2xl overflow-hidden shadow-2xl border border-white/15 aspect-video bg-black">
                                                {!! $item['video_url'] !!}
                                            </div>
                                        @else
                                            {{-- File video lokal --}}
                                            @php
                                                $rawVideoUrl = $item['video_url'];
                                                if (filter_var($rawVideoUrl, FILTER_VALIDATE_URL)) {
                                                    $videoFilename = basename(parse_url($rawVideoUrl, PHP_URL_PATH));
                                                } else {
                                                    $videoFilename = basename($rawVideoUrl);
                                                }
                                                $streamUrl = route('video.stream', ['filename' => $videoFilename]);
                                            @endphp
                                            <div class="rounded-2xl overflow-hidden shadow-2xl border border-white/15 bg-black">
                                                <video
                                                    controls
                                                    preload="metadata"
                                                    class="w-full aspect-video object-cover"
                                                    style="max-height: 240px;"
                                                >
                                                    <source src="{{ $streamUrl }}" type="video/mp4">
                                                    <source src="{{ $streamUrl }}" type="video/webm">
                                                    Browser Anda tidak mendukung pemutar video.
                                                </video>
                                            </div>
                                        @endif
                                    </div>

                                @else
                                    {{-- Frame Kosongan --}}
                                    <div class="relative z-10 flex-1 min-w-0 pr-4 sm:pr-8 space-y-3 sm:space-y-4">
                                        <span class="inline-block px-2.5 py-1 text-[10px] font-black text-white bg-white/20 rounded-md tracking-wider uppercase shadow-sm">
                                            🎬 {{ $item['subtitle'] ?? 'Video Promosi' }}
                                        </span>
                                        <h3 class="text-lg sm:text-2xl font-black leading-tight text-white">
                                            {{ $item['title'] ?? 'Frame Video (KOSONGAN)' }}
                                        </h3>
                                        <p class="text-white/60 text-xs sm:text-sm leading-relaxed">
                                            {{ $item['description'] ?? 'Silakan tempel kode embed iframe video di panel admin.' }}
                                        </p>
                                    </div>
                                    <div class="relative z-10 flex-shrink-0 w-full max-w-[280px] sm:max-w-xs md:max-w-sm border-4 border-dashed border-white/25 rounded-2xl flex flex-col items-center justify-center bg-black/20 p-6 text-center aspect-video" style="max-height: 240px;">
                                        <div class="w-14 h-14 rounded-full bg-white/10 flex items-center justify-center mb-3">
                                            <i data-lucide="video" class="w-7 h-7 text-white/70"></i>
                                        </div>
                                        <p class="text-white/50 text-xs">Belum ada video</p>
                                    </div>
                                @endif
                            </div>
                        @endif
                    @endforeach
                </div>

                {{-- Carousel Controls --}}
                <button type="button" id="btn-carousel-prev" class="absolute left-4 top-1/2 -translate-y-1/2 w-10 h-10 rounded-full bg-black/40 backdrop-blur-md border border-white/10 hover:bg-black/60 text-white flex items-center justify-center z-20 active:scale-90 transition-transform cursor-pointer">
                    <i data-lucide="chevron-left" class="w-5 h-5"></i>
                </button>
                <button type="button" id="btn-carousel-next" class="absolute right-4 top-1/2 -translate-y-1/2 w-10 h-10 rounded-full bg-black/40 backdrop-blur-md border border-white/10 hover:bg-black/60 text-white flex items-center justify-center z-20 active:scale-90 transition-transform cursor-pointer">
                    <i data-lucide="chevron-right" class="w-5 h-5"></i>
                </button>

                {{-- Carousel Dots --}}
                <div class="absolute bottom-4 left-1/2 -translate-y-1/2 -translate-x-1/2 flex gap-2 z-20" id="carousel-dots">
                    @for($i = 0; $i < $totalSlides; $i++)
                        <button type="button" class="w-2.5 h-2.5 rounded-full {{ $i === 0 ? 'bg-white' : 'bg-white/40 hover:bg-white/60' }} transition-all duration-300" data-slide="{{ $i }}"></button>
                    @endfor
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- SECTION 6: PROGRAM DAN LAYANAN LAINNYA (Tumpuk 2 Kiri, 2 Kanan) -->
    <div class="w-full bg-white py-20 border-b border-slate-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Section Header -->
            <div class="text-center max-w-3xl mx-auto mb-16">
                <span class="text-xs font-extrabold text-[#0052cc] uppercase tracking-widest">
                    Mitra Iklan Sponsor
                </span>
                <h2 class="text-3xl sm:text-4xl font-black text-slate-900 mt-2 mb-4 leading-tight">
                    Program & Layanan Beasiswapedia
                </h2>
                <p class="text-slate-500 text-sm leading-relaxed">
                    Layanan bimbingan belajar, konsultasi eksklusif, serta produk sponsor terpercaya dari basis data kami.
                </p>
            </div>

            <!-- Ads Layout Grid (Tumpuk 2 Kiri, 2 Kanan) -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Left Stack of 2 Ads -->
                <div class="flex flex-col gap-6">
                    @if(isset($ads[0]))
                        @include('partials.ad-slot', [
                            'id' => $ads[0]['id'] ?? null,
                            'title' => $ads[0]['title'],
                            'subtitle' => $ads[0]['subtitle'],
                            'description' => $ads[0]['description'],
                            'cta_text' => $ads[0]['cta_text'],
                            'bg_from' => $ads[0]['bg_from'],
                            'bg_to' => $ads[0]['bg_to'],
                            'tag' => $ads[0]['tag'],
                            'link' => $ads[0]['link'] ?? '#',
                            'image_url' => $ads[0]['image_url'] ?? null,
                        ])
                    @endif
                    @if(isset($ads[1]))
                        @include('partials.ad-slot', [
                            'id' => $ads[1]['id'] ?? null,
                            'title' => $ads[1]['title'],
                            'subtitle' => $ads[1]['subtitle'],
                            'description' => $ads[1]['description'],
                            'cta_text' => $ads[1]['cta_text'],
                            'bg_from' => $ads[1]['bg_from'],
                            'bg_to' => $ads[1]['bg_to'],
                            'tag' => $ads[1]['tag'],
                            'link' => $ads[1]['link'] ?? '#',
                            'image_url' => $ads[1]['image_url'] ?? null,
                        ])
                    @endif
                </div>

                <!-- Right Stack of 2 Ads -->
                <div class="flex flex-col gap-6">
                    @if(isset($ads[2]))
                        @include('partials.ad-slot', [
                            'id' => $ads[2]['id'] ?? null,
                            'title' => $ads[2]['title'],
                            'subtitle' => $ads[2]['subtitle'],
                            'description' => $ads[2]['description'],
                            'cta_text' => $ads[2]['cta_text'],
                            'bg_from' => $ads[2]['bg_from'],
                            'bg_to' => $ads[2]['bg_to'],
                            'tag' => $ads[2]['tag'],
                            'link' => $ads[2]['link'] ?? '#',
                            'image_url' => $ads[2]['image_url'] ?? null,
                        ])
                    @endif
                    @if(isset($ads[3]))
                        @include('partials.ad-slot', [
                            'id' => $ads[3]['id'] ?? null,
                            'title' => $ads[3]['title'],
                            'subtitle' => $ads[3]['subtitle'],
                            'description' => $ads[3]['description'],
                            'cta_text' => $ads[3]['cta_text'],
                            'bg_from' => $ads[3]['bg_from'],
                            'bg_to' => $ads[3]['bg_to'],
                            'tag' => $ads[3]['tag'],
                            'link' => $ads[3]['link'] ?? '#',
                            'image_url' => $ads[3]['image_url'] ?? null,
                        ])
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- SECTION 7: KENAPA MEMILIH KAMI -->
    <div id="kenapa-kami" class="w-full bg-gradient-to-br from-sky-50/70 to-blue-50/50 py-20 border-b border-slate-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
            
            <!-- Left Side: Image illustration -->
            <div class="lg:col-span-5 flex justify-center">
                <div class="bg-white p-4 rounded-3xl shadow-2xl border-2 border-slate-200 max-w-md relative hover:scale-103 transition-transform duration-300">
                    <div class="absolute -top-3 -left-3 bg-[#0052cc] text-white text-xs font-bold px-4 py-1.5 rounded-full shadow-sm">
                        Global Study
                    </div>
                    <!-- Menggunakan image buatan kita yang sudah dicopy ke public/images -->
                    <img src="{{ asset('images/students_landmark.png') }}" alt="Ilustrasi Kuliah Luar Negeri" class="rounded-2xl w-full object-cover">
                </div>
            </div>

            <!-- Right Side: Text & Stack of Benefit Cards -->
            <div class="lg:col-span-7 flex flex-col justify-center">
                <h2 class="text-3xl sm:text-4xl font-black text-slate-900 mb-6 leading-tight">
                    Kenapa Memilih Beasiswapedia?
                </h2>
                
                <div class="space-y-4">
                    
                    <!-- Card 1 -->
                    <div class="bg-white p-5 rounded-2xl border-2 border-slate-100 shadow-sm flex items-start gap-4 hover:shadow-md transition-shadow">
                        <div class="w-10 h-10 rounded-xl bg-blue-50 text-[#0052cc] flex items-center justify-center shrink-0">
                            <i data-lucide="globe" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <h4 class="font-extrabold text-slate-900 text-sm mb-1">
                                Puluhan Ribu Alumni Bekerja & Berkuliah di 47 Negara
                            </h4>
                            <p class="text-xs text-slate-500 leading-relaxed">
                                Dapatkan rancangan rencana studi (personalized learning plan), modul beasiswa komprehensif, dan bank soal terupdate.
                            </p>
                        </div>
                    </div>

                    <!-- Card 2 -->
                    <div class="bg-white p-5 rounded-2xl border-2 border-slate-100 shadow-sm flex items-start gap-4 hover:shadow-md transition-shadow">
                        <div class="w-10 h-10 rounded-xl bg-orange-50 text-orange-600 flex items-center justify-center shrink-0">
                            <i data-lucide="users" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <h4 class="font-extrabold text-slate-900 text-sm mb-1">
                                1.300+ Pilihan Mentor & Tutor Alumni Kampus Top Dunia
                            </h4>
                            <p class="text-xs text-slate-500 leading-relaxed">
                                Naikkan peluang lolos wawancara & esai Anda melalui sesi feedback eksklusif 1-on-1 bersama para alumni berprestasi.
                            </p>
                        </div>
                    </div>

                    <!-- Card 3 -->
                    <div class="bg-white p-5 rounded-2xl border-2 border-slate-100 shadow-sm flex items-start gap-4 hover:shadow-md transition-shadow">
                        <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                            <i data-lucide="star" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <h4 class="font-extrabold text-slate-900 text-sm mb-1">
                                Pilihan Kampus & Beasiswa Tanpa Batas
                            </h4>
                            <p class="text-xs text-slate-500 leading-relaxed">
                                Akses lengkap ke 1000+ database filter beasiswa global terlengkap serta rekomendasi beasiswa yang dipersonalisasi sesuai profil akademik Anda.
                            </p>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>

    <!-- SECTION 8: ALUMNI COLLAGE -->
    <div class="w-full bg-white py-20 border-b border-slate-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="text-center max-w-3xl mx-auto mb-16">
                <span class="text-xs font-extrabold text-[#0052cc] uppercase tracking-widest">
                    Testimoni & Kisah Sukses
                </span>
                <h2 class="text-3xl sm:text-4xl font-black text-slate-900 mt-2 mb-4 leading-tight">
                    Mewujudkan Impian Studi dan Karier Global
                </h2>
                <p class="text-slate-500 text-sm leading-relaxed">
                    Kami bangga menjadi jembatan bagi ribuan mimpi anak bangsa untuk melangkah lebih tinggi di kancah dunia.
                </p>
            </div>

            <!-- Collage Masonry Grid layout -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
                
                @php
                    $alumni = [
                        ['name' => 'Yohaness Ahmad Mathius kolose', 'uni' => 'Indonesian Catholic University', 'scholarship' => 'NTU Undergraduate Scholarship', 'bg' => 'from-rose-400 to-red-500', 'initial' => 'A'],
                        ['name' => 'Muhammad Farel Ramadhan', 'uni' => 'Massachusetts Institute of Technology (MIT)', 'scholarship' => 'MEXT Scholarship', 'bg' => 'from-blue-400 to-indigo-500', 'initial' => 'F'],
                        ['name' => 'Muhammad Fatwa Al Fiqri', 'uni' => 'University of Oxford', 'scholarship' => 'Jardine Foundation Scholarship', 'bg' => 'from-amber-400 to-orange-500', 'initial' => 'F'],
                        ['name' => 'Muhammmad Khairil Abdilla', 'uni' => 'Stanford University', 'scholarship' => 'Knight-Hennessy Scholars', 'bg' => 'from-emerald-400 to-teal-500', 'initial' => 'K'],
                        ['name' => 'Abdu Syaril', 'uni' => 'Kyoto University', 'scholarship' => 'Kyoto University iUP', 'bg' => 'from-purple-400 to-pink-500', 'initial' => 'S'],
                        ['name' => 'Ragil Pangestu', 'uni' => 'Harvard University', 'scholarship' => 'LPDP Beasiswa Utama', 'bg' => 'from-cyan-400 to-blue-500', 'initial' => 'R'],
                        ['name' => 'Arsa Maulana Syahputra Adaby', 'uni' => 'Munich University of Tech', 'scholarship' => 'DAAD Scholarship Germany', 'bg' => 'from-fuchsia-400 to-purple-600', 'initial' => 'A'],
                        ['name' => 'Dimaz Airlangga Dwiyansyah', 'uni' => 'University of Melbourne', 'scholarship' => 'AAS Scholarship Australia', 'bg' => 'from-sky-400 to-indigo-600', 'initial' => 'D']
                    ];
                @endphp
                
                @foreach($alumni as $al)
                    <div class="bg-slate-50 border-2 border-slate-200 p-6 rounded-3xl flex flex-col items-center text-center alumni-collage-card shadow-[3px_3px_0px_0px_rgba(0,0,0,0.05)] hover:shadow-[5px_5px_0px_0px_rgba(0,82,204,0.1)]">
                        <div class="w-16 h-16 rounded-full bg-gradient-to-tr {{ $al['bg'] }} text-white flex items-center justify-center font-black text-2xl mb-4 border-2 border-white shadow-md">
                            {{ $al['initial'] }}
                        </div>
                        <h4 class="font-extrabold text-slate-900 mb-1">{{ $al['name'] }}</h4>
                        <p class="text-xs font-bold text-[#0052cc] leading-tight mb-2">{{ $al['uni'] }}</p>
                        <span class="inline-block bg-white border border-slate-200 text-slate-500 text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-wider">
                            {{ $al['scholarship'] }}
                        </span>
                    </div>
                @endforeach

            </div>
        </div>
    </div>

    <!-- SECTION 9: CTA FOOTER BANNER -->
    <div class="w-full bg-[#0052cc] text-white py-16 sm:py-24 text-center relative z-10 border-t border-blue-700">
        <div class="absolute inset-0 dot-grid-bg opacity-10 pointer-events-none"></div>
        <div class="max-w-4xl mx-auto px-6 relative z-10">
            <h2 class="text-3xl sm:text-5xl font-black mb-4 select-none tracking-tight">
                Siap Memulai Perjalanan Akademis Anda?
            </h2>
            <p class="text-blue-100 text-sm sm:text-base font-medium mb-10 max-w-xl mx-auto leading-relaxed">
                Daftarkan akun gratis hari ini, buat dashboard impian, dan dapatkan sinkronisasi info bookmark beasiswa terverifikasi secara instant.
            </p>
            <div class="flex gap-4 justify-center flex-wrap">
                <a href="{{ route('login') }}" class="text-[#0052cc] bg-white hover:bg-slate-50 py-3.5 px-8 rounded-xl font-bold shadow-lg shadow-black/10 active:translate-y-0.5 transition-all text-sm uppercase tracking-wider">
                    Daftar Sekarang
                </a>
                <a href="{{ url('/home') }}" class="text-white bg-transparent border-2 border-white hover:bg-white/10 py-3.5 px-8 rounded-xl font-bold active:translate-y-0.5 transition-all text-sm uppercase tracking-wider">
                    Jelajahi Beasiswapedia
                </a>
            </div>
        </div>
    </div>

    <!-- FOOTER LINKS & COPYRIGHT (Blue Theme) -->
    <div class="w-full bg-[#001f52] text-blue-200/80 py-8 text-center text-xs border-t border-blue-800/80 relative z-10">
        <div class="max-w-7xl mx-auto px-4 flex flex-col sm:flex-row items-center justify-between gap-4">
            
            <!-- Logo Brand Unified -->
            <a href="{{ url('/') }}" class="flex items-center gap-2 shrink-0 group">
                <div class="w-8 h-8 rounded-lg bg-white flex items-center justify-center text-[#0052cc] shadow-md group-hover:scale-105 transition-transform duration-300">
                    <i data-lucide="graduation-cap" class="w-5 h-5 stroke-[2.5]"></i>
                </div>
                <div class="flex flex-col text-left">
                    <span class="text-sm font-black text-white leading-none tracking-tight">
                        beasiswa<span class="text-[#ff7300]">pedia</span>
                    </span>
                </div>
            </a>

            <p class="text-blue-300/80 font-medium">
                © 2026 Beasiswapedia. Dibuat dengan dedikasi penuh untuk Indonesia Maju.
            </p>
            <div class="flex gap-4">
                <a href="javascript:void(0)" onclick="document.getElementById('about-modal').classList.remove('hidden')" class="hover:text-white transition-colors font-semibold">Tentang Kami</a>
                <span class="text-blue-800">·</span>
                <a href="javascript:void(0)" onclick="document.getElementById('about-modal').classList.remove('hidden')" class="hover:text-white transition-colors font-semibold">Kontak Bantuan</a>
            </div>
        </div>
    </div>

</div>
@endsection

@section('scripts')
<script>
    // Inisialisasi ikon Lucide setelah halaman dirender
    document.addEventListener('DOMContentLoaded', () => {
        if (window.lucide) {
            lucide.createIcons();
        }
        
        // Memeriksa status login dan memperbarui navigasi navbar secara dinamis
        syncLandingNavbar();
    });

    // Menangani menu toggle untuk responsif mobile
    function toggleMobileMenu() {
        const panel = document.getElementById('mobile-nav-panel');
        const icon = document.getElementById('mobile-menu-icon');
        if (panel) {
            const isHidden = panel.classList.contains('hidden');
            if (isHidden) {
                panel.classList.remove('hidden');
                icon.setAttribute('data-lucide', 'x');
            } else {
                panel.classList.add('hidden');
                icon.setAttribute('data-lucide', 'menu');
            }
            if (window.lucide) lucide.createIcons();
        }
    }

    // Melakukan sinkronisasi navbar landing page dengan status login
    function syncLandingNavbar() {
        const container = document.getElementById('landing-auth-container');
        if (!container) return;

        const loggedIn = localStorage.getItem('isLoggedIn') === 'true';
        
        if (loggedIn) {
            // Jika pengguna sudah login, tampilkan tombol ke Home beasiswa (Request 3: Ganti Dashboard jadi Home)
            container.innerHTML = `
                <a href="{{ route('home') }}" class="bg-[#ff7300] hover:bg-[#e65c00] text-white text-xs font-bold px-5 py-2.5 rounded-full shadow-md transition-all flex items-center gap-1.5 shadow-md">
                    <i data-lucide="home" class="w-3.5 h-3.5"></i>
                    Home
                </a>
            `;
        } else {
            // Jika belum login, tampilkan tombol Login / Daftar
            container.innerHTML = `
                <a href="{{ route('login') }}" class="bg-[#ff7300] hover:bg-[#e65c00] text-white text-xs font-bold px-5 py-2.5 rounded-full shadow-md transition-all flex items-center gap-1.5 shadow-md">
                    <i data-lucide="log-in" class="w-3.5 h-3.5"></i>
                    Login / Daftar
                </a>
            `;
        }

        if (window.lucide) lucide.createIcons();
    }

    // Toast Notification helper khusus untuk Landing Page
    function showLandingToast(message, type = 'success') {
        const container = document.getElementById('toast-container');
        if (!container) return;

        const el = document.createElement('div');
        el.className = `portal-toast ${type}`;
        el.textContent = message;
        container.appendChild(el);

        setTimeout(() => {
            el.remove();
        }, 3500);
    }

    // Form konsultasi WhatsApp
    function handleConsultationSubmit(e) {
        e.preventDefault();
        
        const waNumberInput = document.getElementById('consult-wa');
        const programSelect = document.getElementById('consult-program');
        
        if (!waNumberInput || !programSelect) return;

        const waNumber = waNumberInput.value.trim();
        const selectedProgram = programSelect.options[programSelect.selectedIndex].text;

        if (waNumber.length < 8) {
            showLandingToast('Silakan masukkan nomor WhatsApp yang valid!', 'error');
            return;
        }

        // Tampilkan feedback sukses
        showLandingToast(`Permintaan berhasil! Konsultasi program "${selectedProgram}" akan dikirimkan ke WhatsApp +62${waNumber}.`, 'success');
        
        // Reset form
        waNumberInput.value = '';
    }
</script>
@endsection

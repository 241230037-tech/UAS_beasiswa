{{--
    View: pages/login.blade.php

    Halaman Login, Registrasi Akun Baru, dan Login Admin Beasiswapedia.
    Menggunakan desain split-screen: sisi kiri panel visual promosi, sisi kanan form interaktif.
    Tiga mode tampilan dikontrol oleh class CSS pada #login-page-container:
      - mode-login  : Form login pengguna biasa
      - mode-register : Form registrasi akun baru
      - mode-admin  : Form login khusus administrator

    Data yang diterima dari PageController::login():
      - $redirect : URL tujuan redirect setelah login berhasil (default: '/home')
      - $role     : Role awal tampilan form ('admin' = tampilkan mode admin, '' = mode login biasa)

    Autentikasi dilakukan via AJAX POST ke /api/login atau /api/register.
    Tidak menggunakan form submission tradisional (tidak ada redirect server-side).

    Template yang di-extend: layouts/app.blade.php
--}}

@extends('layouts.app')

@section('title', 'Login - Beasiswapedia')

@section('content')
@php
    // Tentukan mode awal berdasarkan parameter role dari controller
    $initialMode = 'mode-login';
    if (isset($role) && $role === 'admin') {
        $initialMode = 'mode-admin';
    } elseif (request()->query('redirect') && str_contains(request()->query('redirect'), 'admin')) {
        $initialMode = 'mode-admin';
    }
@endphp

<!-- Container Utama Halaman Login/Register Split-Screen -->
<div id="login-page-container" class="w-full min-h-screen flex flex-col md:flex-row bg-background {{ $initialMode }}" data-initial-role="{{ $role ?? '' }}">
    
    <!-- ================= SISI KIRI: PANEL PROMOSI VISUAL (HIDDEN DI MOBILE) ================= -->
    <div id="left-panel" class="hidden md:flex md:w-1/2 text-white flex-col justify-between p-12 relative overflow-hidden transition-all duration-500">
        <!-- Efek Blur Dekoratif di Belakang -->
        <div class="absolute -top-24 -left-24 w-80 h-80 bg-white/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-24 -right-24 w-90 h-90 bg-white/10 rounded-full blur-3xl pointer-events-none"></div>

        <!-- Bagian Atas: Logo & Nama Brand -->
        <div class="relative z-10">
            <a href="{{ url('/home') }}" class="flex items-center gap-2 group">
                <div class="w-10 h-10 rounded-xl bg-white flex items-center justify-center text-[#0052cc] shadow-lg shadow-black/10 group-hover:scale-105 transition-transform duration-300">
                    <i data-lucide="graduation-cap" class="w-6 h-6 stroke-[2.5]"></i>
                </div>
                <div class="flex flex-col">
                    <span class="text-xl font-black text-white leading-none tracking-tight">
                        beasiswa<span class="text-[#ff7300]">pedia</span>
                    </span>
                </div>
            </a>
        </div>

        <!-- Bagian Tengah: Teks Utama & Keunggulan -->
        <div class="my-auto relative z-10 max-w-lg space-y-8">
            <div class="space-y-4">
                <h2 id="left-title" class="text-3xl font-black leading-tight tracking-tight">
                    <!-- Dinonaktifkan/Diubah secara dinamis melalui JavaScript -->
                    Temukan Beasiswa Impian Anda
                </h2>
                <p id="left-subtitle" class="text-white/80 text-sm font-medium leading-relaxed">
                    Akses ribuan informasi beasiswa dalam negeri maupun luar negeri secara terpusat, mudah, dan gratis.
                </p>
            </div>

            <!-- Card Poin Keunggulan (Mengapa Kami?) -->
            <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl p-6 shadow-xl space-y-4">
                <h3 id="advantage-title" class="font-black text-sm tracking-wide uppercase opacity-95">Mengapa Beasiswapedia?</h3>
                <ul class="space-y-3 text-xs font-semibold">
                    <li class="flex items-center gap-2.5">
                        <span class="w-5 h-5 rounded-full bg-white/20 flex items-center justify-center text-[10px]">✓</span>
                        Informasi beasiswa terupdate secara real-time
                    </li>
                    <li class="flex items-center gap-2.5">
                        <span class="w-5 h-5 rounded-full bg-white/20 flex items-center justify-center text-[10px]">✓</span>
                        Simpan beasiswa favorit untuk dipantau kemudian
                    </li>
                    <li class="flex items-center gap-2.5">
                        <span class="w-5 h-5 rounded-full bg-white/20 flex items-center justify-center text-[10px]">✓</span>
                        Pantau deadline pendaftaran dengan notifikasi sistem
                    </li>
                    <li class="flex items-center gap-2.5">
                        <span class="w-5 h-5 rounded-full bg-white/20 flex items-center justify-center text-[10px]">✓</span>
                        Form pendaftaran yang mudah digunakan oleh pelajar
                    </li>
                </ul>
            </div>
        </div>

        <!-- Bagian Bawah: Footer Hak Cipta -->
        <div class="relative z-10 text-[10px] text-white/60 font-medium">
            &copy; {{ date('Y') }} Beasiswapedia. Hak Cipta Dilindungi.
        </div>
    </div>

    <!-- ================= SISI KANAN: FORMULIR OTENTIKASI ================= -->
    <div class="w-full md:w-1/2 flex flex-col justify-between p-8 bg-card relative z-10 min-h-screen">
        
        <!-- Header Kecil Mobile Only -->
        <div class="flex md:hidden items-center justify-between border-b border-border pb-4 mb-4">
            <a href="{{ url('/home') }}" class="flex items-center gap-2 shrink-0 group">
                <div class="w-10 h-10 rounded-xl bg-[#0052cc] flex items-center justify-center text-white shadow-lg shadow-blue-500/20 group-hover:scale-105 transition-transform duration-300">
                    <i data-lucide="graduation-cap" class="w-6 h-6 stroke-[2.5]"></i>
                </div>
                <div class="flex flex-col">
                    <span class="text-xl font-black text-slate-900 dark:text-white leading-none tracking-tight">
                        beasiswa<span class="text-[#ff7300]" id="mobile-logo-accent">pedia</span>
                    </span>
                </div>
            </a>
            <span id="mobile-header-tag" class="text-[9px] font-bold uppercase tracking-wider bg-[#0052cc]/10 text-[#0052cc] px-2.5 py-1 rounded-full">USER</span>
        </div>

        <!-- Spacer Atas untuk Menyeimbangkan Layout -->
        <div class="hidden md:block"></div>

        <!-- Konten Form Utama (Centering) -->
        <div class="w-full max-w-md mx-auto my-auto space-y-6">
            
            <!-- Informasi Judul Selamat Datang -->
            <div class="space-y-1.5 text-center md:text-left">
                <h1 id="form-welcome-title" class="text-2xl font-black text-foreground tracking-tight flex items-center justify-center md:justify-start gap-2">
                    Selamat Datang <span class="wave-hand">👋</span>
                </h1>
                <p id="form-welcome-subtitle" class="text-muted-foreground text-xs font-semibold">
                    Login untuk melanjutkan ke Beasiswapedia.
                </p>
            </div>

            <!-- Tab Switcher dihilangkan disamping login sesuai Tugas 1 -->

            <!-- Formulir Utama -->
            <form id="login-form" data-redirect="{{ $redirect }}" class="space-y-4">
                <!-- Field Nama Lengkap (Menggunakan transisi CSS halus untuk tampil/sembunyi) -->
                <div id="name-field" class="space-y-1.5 hidden">
                    <label class="block text-[10px] font-bold text-muted-foreground uppercase tracking-wider">Nama Lengkap</label>
                    <div class="relative">
                        <i data-lucide="user" class="w-4 h-4 text-muted-foreground absolute left-3 top-1/2 -translate-y-1/2"></i>
                        <input type="text" id="name-input" name="name" placeholder="Masukkan nama lengkap Anda"
                            class="w-full bg-muted text-foreground placeholder-muted-foreground/60 px-4 py-3 pl-10 rounded-xl border border-border focus:outline-none focus:ring-2 focus:ring-accent-primary/40 focus:border-accent-primary transition-all text-xs font-semibold">
                    </div>
                </div>

                <!-- Field Email -->
                <div class="space-y-1.5">
                    <label id="email-label" class="block text-[10px] font-bold text-muted-foreground uppercase tracking-wider">Alamat Email</label>
                    <div class="relative">
                        <i data-lucide="mail" class="w-4 h-4 text-muted-foreground absolute left-3 top-1/2 -translate-y-1/2"></i>
                        <input type="text" id="email-input" name="email" placeholder="nama@email.com" required
                            class="w-full bg-muted text-foreground placeholder-muted-foreground/60 px-4 py-3 pl-10 rounded-xl border border-border focus:outline-none focus:ring-2 focus:ring-accent-primary/40 focus:border-accent-primary transition-all text-xs font-semibold">
                    </div>
                </div>

                <!-- Field Password -->
                <div class="space-y-1.5">
                    <label class="block text-[10px] font-bold text-muted-foreground uppercase tracking-wider">Password</label>
                    <div class="relative">
                        <i data-lucide="lock" class="w-4 h-4 text-muted-foreground absolute left-3 top-1/2 -translate-y-1/2"></i>
                        <input type="password" id="password-input" name="password" placeholder="••••••••" required
                            class="w-full bg-muted text-foreground placeholder-muted-foreground/60 px-4 py-3 pl-10 pr-11 rounded-xl border border-border focus:outline-none focus:ring-2 focus:ring-accent-primary/40 focus:border-accent-primary transition-all text-xs font-semibold">
                        <button type="button" id="btn-toggle-password" class="absolute right-3 top-1/2 -translate-y-1/2 text-muted-foreground hover:text-foreground transition-colors p-1" title="Lihat password">
                            <i data-lucide="eye" class="w-4 h-4"></i>
                        </button>
                    </div>
                </div>

                <!-- Row Opsi Tambahan (Ingat Saya & Lupa Sandi - Tersembunyi saat Register) -->
                <div id="remember-row" class="flex items-center justify-between text-[11px] font-semibold">
                    <label class="flex items-center gap-2 text-muted-foreground cursor-pointer select-none">
                        <input type="checkbox" class="rounded border-border text-accent-primary focus:ring-accent-primary/40"> Ingat saya
                    </label>
                    <a href="#" class="text-accent-primary hover:opacity-85 transition-opacity" id="btn-forgot-password">Lupa password?</a>
                </div>

                <!-- Tombol Submit Form -->
                <button type="submit" id="login-submit" class="w-full text-white py-3 rounded-xl font-bold transition-all shadow-lg active:scale-[0.98] text-xs">
                    Masuk
                </button>
            </form>

            <!-- Switcher Footer Cepat (Belum punya akun? Daftar) -->
            <div id="switch-footer" class="text-center text-xs font-semibold text-muted-foreground">
                <span id="switch-footer-text">Belum punya akun?</span> 
                <a href="#" id="btn-switch-auth" class="text-accent-primary hover:opacity-85 transition-opacity">Daftar Sekarang</a>
            </div>
        </div>

        <!-- Tombol Kembali ke Beranda -->
        <div class="text-center pt-6">
            <a href="{{ url('/home') }}" class="text-muted-foreground hover:text-foreground text-xs font-semibold transition-colors flex items-center justify-center gap-1.5">
                <i data-lucide="arrow-left" class="w-4 h-4"></i> Kembali ke Beranda
            </a>
        </div>
    </div>
</div>

<style>
    /* Styling Aksen Warna Dinamis Menggunakan CSS Variables */
    .mode-login {
        --accent-primary: #0052cc; /* Warna biru premium untuk mode masuk */
        --gradient-from: #0052cc;
        --gradient-to: #003b99;
    }
    
    .mode-register {
        --accent-primary: #6366f1; /* Warna indigo premium untuk mode daftar */
        --gradient-from: #6366f1;
        --gradient-to: #4338ca;
    }

    .mode-admin {
        --accent-primary: #475569; /* Warna slate grey premium untuk administrator */
        --gradient-from: #334155;
        --gradient-to: #1e293b;
    }

    /* Memberikan efek transisi background linear gradient */
    #left-panel {
        background: linear-gradient(135deg, var(--gradient-from), var(--gradient-to));
        transition: all 0.6s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .text-accent-primary {
        color: var(--accent-primary) !important;
        transition: color 0.4s ease;
    }
    
    .bg-accent-primary {
        background-color: var(--accent-primary) !important;
        transition: background-color 0.4s ease;
    }

    #login-submit {
        background-color: var(--accent-primary);
        box-shadow: 0 4px 14px 0 rgba(0, 0, 0, 0.1), 0 4px 20px 0 rgba(var(--accent-primary), 0.15);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    #login-submit:hover {
        opacity: 0.95;
    }

    /* Melambaikan Tangan Animasi */
    @keyframes wave {
        0%, 100% { transform: rotate(0deg); }
        50% { transform: rotate(15deg); }
    }
    .wave-hand {
        display: inline-block;
        animation: wave 1.5s infinite ease-in-out;
        transform-origin: 70% 70%;
    }

    /* CSS Animasi / Transisi Smooth untuk Name Field (Daftar Akun) */
    #name-field {
        transition: max-height 0.5s cubic-bezier(0.4, 0, 0.2, 1), opacity 0.4s ease, margin 0.5s ease;
        max-height: 0;
        opacity: 0;
        overflow: hidden;
        margin-top: 0;
        margin-bottom: 0;
    }

    /* Saat berada di mode registrasi, beri tinggi maksimal dan munculkan */
    #login-page-container.mode-register #name-field {
        max-height: 100px;
        opacity: 1;
        margin-top: 1rem;
        margin-bottom: 1rem;
    }

    /* Paksa input form tetap block agar transisi tinggi berjalan lancar */
    #name-field.hidden {
        display: block !important;
    }
</style>
@endsection

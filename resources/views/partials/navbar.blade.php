{{--
    Partial: partials/navbar.blade.php

    Navbar navigasi utama yang digunakan di semua halaman setelah login (home, library, dashboard, dll.).
    Berbeda dengan navbar landing page yang hard-coded di pages/landing.blade.php.

    Fitur yang tersedia:
      - Logo brand Beasiswapedia dengan link ke /home
      - Form pencarian beasiswa (redirect ke /library?q=...)
      - Tombol toggle chatbot, dark/light mode
      - Avatar dan nama pengguna yang sedang login (diisi JavaScript dari localStorage)
      - Tombol Login (untuk pengguna yang belum login)
      - Tombol hamburger menu untuk membuka slide-out navigation drawer

    State autentikasi (logged in / logged out) dikelola sepenuhnya oleh JavaScript
    yang membaca localStorage — bukan dari session Laravel server-side.

    Cara penggunaan:
      @include('partials.navbar')
--}}

<nav class="bg-[#0052cc] border-b border-blue-750 sticky top-0 z-50 shadow-lg text-white">
    <div class="max-w-screen-xl mx-auto px-4 flex items-center h-16 gap-3">
        
        <!-- Logo Brand Unified -->
        <a href="{{ url('/home') }}" class="flex items-center gap-2 shrink-0 group">
            <div class="w-10 h-10 rounded-xl bg-white flex items-center justify-center text-[#0052cc] shadow-lg shadow-black/10 group-hover:scale-105 transition-transform duration-300">
                <i data-lucide="graduation-cap" class="w-6 h-6 stroke-[2.5]"></i>
            </div>
            <div class="flex flex-col">
                <span class="text-xl font-black text-white leading-none tracking-tight">
                    beasiswa<span class="text-[#ff7300]">pedia</span>
                </span>
            </div>
        </a>

        <form class="flex-1 flex items-center justify-center" id="navbar-search-form" action="{{ route('library') }}" method="GET">
            <div class="relative w-full max-w-md">
                <input type="text" name="q" id="navbar-search-input" placeholder="Cari beasiswa..."
                    value="{{ request('q') }}"
                    class="navbar-search-input w-full px-4 py-2 pr-10 rounded-full text-sm bg-white/10 border border-blue-400/50 text-white placeholder-blue-100/70 focus:bg-white focus:text-slate-800 focus:placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-300/30 transition-all">
                <button type="submit" class="absolute right-3 top-1/2 -translate-y-1/2 text-blue-100/80 hover:text-white transition-colors">
                    <i data-lucide="search" class="w-4 h-4"></i>
                </button>
            </div>
        </form>

        <div class="flex items-center gap-0.5 ml-auto shrink-0">
            <button type="button" id="btn-chatbot" class="p-2 text-blue-100 hover:text-white transition-colors" title="Chatbot Pemandu">
                <i data-lucide="message-square" class="w-5 h-5"></i>
            </button>
            <button type="button" id="btn-theme" class="p-2 text-blue-100 hover:text-white transition-colors" title="Mode Terang">
                <i data-lucide="sun" class="w-5 h-5" id="theme-icon"></i>
            </button>

            <div id="navbar-logged-in" class="hidden sm:flex items-center gap-2 ml-2">
                <div id="navbar-avatar" class="w-8 h-8 rounded-full border-2 border-white overflow-hidden bg-white/10 cursor-pointer flex items-center justify-center hover:scale-105 active:scale-95 transition-transform" title="Pengaturan Akun">
                    <span id="navbar-avatar-letter" class="text-xs font-bold text-white">U</span>
                    <img id="navbar-avatar-img" src="" alt="avatar" class="w-full h-full object-cover hidden">
                </div>
                <span id="navbar-username" class="text-xs text-blue-100 font-bold truncate max-w-[100px]">User</span>
            </div>

            <a href="{{ route('login') }}" id="navbar-login-btn" class="hidden sm:flex items-center gap-1.5 px-4 py-1.5 text-sm font-bold text-white bg-[#ff7300] hover:bg-[#e65c00] rounded-full transition-colors ml-1 shadow-md">
                <i data-lucide="log-in" class="w-4 h-4"></i>
                Login
            </a>

            <button type="button" id="btn-menu" class="p-2 text-blue-100 hover:text-white transition-colors ml-1" title="Menu">
                <i data-lucide="menu" class="w-5 h-5" id="menu-icon-open"></i>
                <i data-lucide="x" class="w-5 h-5 hidden" id="menu-icon-close"></i>
            </button>
        </div>
    </div>
</nav>

<div id="panel-overlay" class="fixed inset-0 z-40 bg-black/50 hidden"></div>

<div id="side-menu" class="fixed top-0 right-0 h-full w-72 bg-background border-l border-border z-50 panel-slide panel-hidden shadow-2xl flex flex-col">
    <div class="flex items-center justify-between px-4 h-14 border-b border-border shrink-0">
        <span class="text-foreground font-bold text-sm">Menu Navigasi</span>
        <button type="button" id="btn-close-menu" class="text-muted-foreground hover:text-foreground transition-colors">
            <i data-lucide="x" class="w-5 h-5"></i>
        </button>
    </div>
    <nav class="py-2 shrink-0">
        @php
            $menuItems = [
                ['icon' => 'home', 'label' => 'Beranda', 'path' => '/home'],
                ['icon' => 'book-open', 'label' => 'Katalog', 'path' => '/library'],
                ['icon' => 'layout-dashboard', 'label' => 'Dashboard', 'path' => '/dashboard'],
                ['icon' => 'help-circle', 'label' => 'Tutorial Daftar', 'path' => '/tutorial'],
                ['icon' => 'user-check', 'label' => 'Admin Panel', 'path' => '/admin'],
            ];
            $currentPath = request()->path();
        @endphp
        @foreach($menuItems as $item)
            @php $active = $currentPath === ltrim($item['path'], '/'); @endphp
            <a href="{{ url($item['path']) }}"
                @if($item['path'] === '/admin') id="sidebar-admin-link" @endif
                class="w-full flex items-center gap-3 px-4 py-3 text-sm transition-colors {{ $active ? 'bg-[#0052cc]/10 text-[#0052cc] border-r-2 border-[#0052cc] font-semibold' : 'text-muted-foreground hover:bg-muted hover:text-foreground' }}">
                <i data-lucide="{{ $item['icon'] }}" class="w-5 h-5 shrink-0"></i>
                {{ $item['label'] }}
            </a>
        @endforeach
    </nav>
    <div class="flex-1 overflow-y-auto">
        <div id="sidebar-account-logged" class="hidden p-4 border-t border-border bg-muted/10 flex flex-col gap-4">
            <p class="text-[10px] font-bold text-muted-foreground uppercase tracking-wider">Status Akun</p>
            <div class="flex items-center gap-3">
                <div id="sidebar-avatar" class="w-12 h-12 rounded-full border-2 border-[#0052cc] overflow-hidden bg-muted flex items-center justify-center shrink-0 shadow-[2px_2px_0px_0px_rgba(0,82,204,0.2)]">
                    <span id="sidebar-avatar-letter" class="text-sm font-bold text-[#0052cc]">U</span>
                    <img id="sidebar-avatar-img" src="" alt="avatar" class="w-full h-full object-cover hidden">
                </div>
                <div class="flex-1 min-w-0">
                    <p id="sidebar-username" class="text-foreground font-bold text-sm truncate">User</p>
                    <p id="sidebar-email" class="text-muted-foreground text-xs truncate">user@email.com</p>
                </div>
            </div>
            <div class="flex flex-col gap-2">
                <button type="button" id="btn-open-account-modal" class="w-full flex items-center justify-center gap-2 py-2 text-xs font-semibold bg-muted hover:bg-accent text-foreground rounded-lg border border-border transition-colors cursor-pointer">
                    <i data-lucide="settings" class="w-3.5 h-3.5"></i>
                    Pengaturan Akun
                </button>
                <button type="button" id="btn-logout" class="w-full flex items-center justify-center gap-2 py-2 text-xs font-semibold text-muted-foreground hover:text-foreground border border-border rounded-lg transition-colors cursor-pointer">
                    <i data-lucide="log-out" class="w-3.5 h-3.5"></i>
                    Keluar
                </button>
            </div>
        </div>
        <div id="sidebar-account-guest" class="p-4 border-t border-border">
            <a href="{{ route('login') }}" class="w-full btn-3d-red flex items-center justify-center gap-2 py-3 text-sm font-semibold rounded-xl">
                <i data-lucide="log-in" class="w-4 h-4"></i>
                Login / Daftar
            </a>
        </div>
    </div>
</div>

<div id="chatbot-panel" class="fixed top-14 right-0 w-80 sm:w-96 bg-card border-l border-border z-50 panel-slide panel-hidden shadow-2xl max-h-[calc(100vh-3.5rem)] h-[550px] flex flex-col overflow-hidden">
    {{-- Header --}}
    <div class="p-4 border-b border-border bg-muted/20 flex items-center justify-between shrink-0">
        <div class="flex items-center gap-2">
            <div class="w-8 h-8 rounded-full bg-[#0052cc]/15 border border-[#0052cc]/30 flex items-center justify-center text-[#0052cc]">
                <i data-lucide="bot" class="w-4.5 h-4.5"></i>
            </div>
            <div>
                <h3 class="text-foreground font-black text-xs leading-none">Asisten Chatbot</h3>
                <span class="text-[9px] text-green-500 font-bold">Online · Pemandu Anda</span>
            </div>
        </div>
        <div class="flex items-center gap-1">
            <a href="https://wa.me/6285751052374?text=Halo%20Admin%20Beasiswapedia,%20saya%20butuh%20bantuan%20terkait%20portal%20beasiswa." target="_blank" class="text-green-500 hover:text-green-600 transition-colors p-1.5 rounded-full hover:bg-muted flex items-center justify-center" title="Chat WhatsApp Admin">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-whatsapp" viewBox="0 0 16 16">
                    <path d="M13.601 2.326A7.854 7.854 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.366 2.76 1.057 3.965L0 16l4.204-1.102a7.933 7.933 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.898 7.898 0 0 0 13.6 2.326zM7.994 14.521a6.573 6.573 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.557 6.557 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592zm3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.729.729 0 0 0-.529.247c-.182.198-.691.677-.691 1.654 0 .977.71 1.916.81 2.049.098.133 1.394 2.132 3.383 2.992.47.205.84.326 1.129.418.475.152.904.129 1.246.08.38-.058 1.171-.48 1.338-.943.164-.464.164-.86.114-.943-.049-.084-.182-.133-.38-.232z"/>
                </svg>
            </a>
            <button type="button" id="btn-close-chatbot" class="text-muted-foreground hover:text-foreground transition-colors p-1.5 rounded-full hover:bg-muted flex items-center justify-center">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>
    </div>

    {{-- Messages area --}}
    <div id="chatbot-messages" class="flex-1 p-4 overflow-y-auto space-y-3 bg-background/50 text-xs">
        {{-- Welcome Message --}}
        <div class="flex gap-2.5 max-w-[92%]">
            <div class="w-7 h-7 rounded-full bg-[#0052cc]/10 border border-[#0052cc]/20 flex items-center justify-center text-[#0052cc] shrink-0">
                <i data-lucide="bot" class="w-3.5 h-3.5"></i>
            </div>
            <div class="bg-muted px-3 py-2 rounded-2xl rounded-tl-none text-foreground leading-relaxed shadow-sm">
                Halo! 👋 Saya <strong>Asisten Beasiswapedia</strong>.<br>Ada yang ingin Anda tanyakan seputar beasiswa, pendaftaran, atau fitur website? Silakan tanya atau klik topik di bawah!
            </div>
        </div>
    </div>

    {{-- Quick chips --}}
    <div class="p-3 border-t border-border bg-muted/10 shrink-0">
        <p class="text-[9px] font-bold text-muted-foreground uppercase tracking-wider mb-2">Pertanyaan Populer</p>
        <div class="flex flex-wrap gap-1.5" id="chatbot-chips">
            <button type="button" class="chatbot-chip px-2.5 py-1 text-[10px] bg-muted hover:bg-accent text-foreground border border-border rounded-full transition-colors font-medium">Cara daftar beasiswa?</button>
            <button type="button" class="chatbot-chip px-2.5 py-1 text-[10px] bg-muted hover:bg-accent text-foreground border border-border rounded-full transition-colors font-medium">Syarat berkas & dokumen</button>
            <button type="button" class="chatbot-chip px-2.5 py-1 text-[10px] bg-muted hover:bg-accent text-foreground border border-border rounded-full transition-colors font-medium">Cara bookmark beasiswa</button>
            <button type="button" class="chatbot-chip px-2.5 py-1 text-[10px] bg-muted hover:bg-accent text-foreground border border-border rounded-full transition-colors font-medium">Beasiswa luar negeri</button>
            <button type="button" class="chatbot-chip px-2.5 py-1 text-[10px] bg-muted hover:bg-accent text-foreground border border-border rounded-full transition-colors font-medium">Info deadline</button>
            <button type="button" class="chatbot-chip px-2.5 py-1 text-[10px] bg-[#25D366]/10 hover:bg-[#25D366]/20 text-[#25D366] border border-[#25D366]/30 rounded-full transition-colors font-bold flex items-center gap-1">
                <svg xmlns="http://www.w3.org/2000/svg" width="9" height="9" fill="currentColor" viewBox="0 0 16 16"><path d="M13.601 2.326A7.854 7.854 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.366 2.76 1.057 3.965L0 16l4.204-1.102a7.933 7.933 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.898 7.898 0 0 0 13.6 2.326z"/></svg>
                Hubungi Admin
            </button>
        </div>
    </div>

    {{-- Input --}}
    <div class="p-3 border-t border-border bg-card shrink-0 flex gap-2">
        <input type="text" id="chatbot-input" placeholder="Ketik pesan Anda di sini..." class="flex-1 bg-muted text-foreground placeholder-muted-foreground px-4 py-2.5 rounded-xl border border-border text-xs focus:outline-none focus:ring-1 focus:ring-[#0052cc]/50">
        <button type="button" id="btn-send-chatbot" class="btn-3d-red px-3 rounded-xl text-white flex items-center justify-center shadow-md">
            <i data-lucide="send" class="w-4 h-4"></i>
        </button>
    </div>
</div>

<div id="account-modal" class="hidden">
    <div class="modal-backdrop" id="account-modal-backdrop"></div>
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="bg-card border-2 border-border rounded-2xl shadow-2xl w-full max-w-md relative overflow-hidden z-10 modal-box">
            <div class="h-1.5 bg-[#0052cc] w-full"></div>
            <button type="button" id="btn-close-account-modal" class="absolute top-4 right-4 p-1.5 rounded-full hover:bg-muted text-muted-foreground hover:text-foreground transition-colors cursor-pointer">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
            <div class="p-6">
                <div class="flex items-center gap-2 mb-6">
                    <i data-lucide="settings" class="w-5 h-5 text-[#0052cc]"></i>
                    <h2 class="text-foreground font-black text-lg">Pengaturan Akun</h2>
                </div>
                <div class="flex flex-col items-center gap-3 mb-6">
                    <div class="relative">
                        <div id="modal-avatar" class="w-20 h-20 rounded-full border-4 border-[#0052cc] shadow-[4px_4px_0px_0px_rgba(0,82,204,0.3)] overflow-hidden bg-muted flex items-center justify-center cursor-pointer">
                            <i data-lucide="user" class="w-10 h-10 text-muted-foreground" id="modal-avatar-icon"></i>
                            <img id="modal-avatar-img" src="" alt="avatar" class="w-full h-full object-cover hidden">
                        </div>
                        <button type="button" id="btn-upload-avatar" class="absolute bottom-0 right-0 w-8 h-8 rounded-full bg-[#0052cc] hover:bg-blue-700 text-white flex items-center justify-center shadow-lg border border-blue-700 active:scale-95 transition-transform">
                            <i data-lucide="camera" class="w-4 h-4"></i>
                        </button>
                        <input type="file" id="avatar-input" accept="image/*" class="hidden">
                    </div>
                    <p class="text-muted-foreground text-xs">Ketuk tombol kamera untuk mengganti foto</p>
                </div>
                <div class="space-y-4 mb-6">
                    <div>
                        <label class="text-xs font-bold text-muted-foreground uppercase tracking-wide block mb-1.5">Nama Tampil</label>
                        <input type="text" id="modal-edit-name" class="w-full bg-muted text-foreground text-sm px-4 py-2.5 rounded-xl border border-border focus:outline-none focus:ring-2 focus:ring-[#0052cc]/50 transition-shadow" placeholder="Nama lengkap Anda">
                    </div>
                    <div>
                        <label class="text-xs font-bold text-muted-foreground uppercase tracking-wide block mb-1.5">Email</label>
                        <input type="email" id="modal-email" disabled class="w-full bg-muted/40 text-muted-foreground text-sm px-4 py-2.5 rounded-xl border border-border cursor-not-allowed opacity-75">
                    </div>

                    {{-- Form Ganti Password Terintegrasi Database (Tugas 10 / Hanya untuk Pengguna Biasa, Admin lewat CRUD Admin - Request 3) --}}
                    @if(Auth::check() && Auth::user()->role === 'user')
                    <div class="border-t border-border pt-4 mt-4">
                        <button type="button" id="btn-toggle-password-section" class="w-full flex items-center justify-between text-xs font-bold text-muted-foreground hover:text-foreground transition-colors py-1 cursor-pointer">
                            <span>GANTI KATA SANDI (OPSIONAL)</span>
                            <i data-lucide="chevron-down" class="w-4 h-4 transition-transform duration-300" id="icon-password-chevron"></i>
                        </button>
                        <div id="password-section" class="hidden mt-3 space-y-3">
                            <div>
                                <label class="text-[10px] font-bold text-muted-foreground uppercase tracking-wider block mb-1">Kata Sandi Saat Ini</label>
                                <div class="relative">
                                    <input type="password" id="modal-current-password" class="w-full bg-muted text-foreground text-xs px-4 py-2 pr-10 rounded-xl border border-border focus:outline-none focus:ring-1 focus:ring-[#0052cc]/50" placeholder="••••••••">
                                    <button type="button" class="absolute right-3 top-1/2 -translate-y-1/2 text-muted-foreground hover:text-foreground focus:outline-none cursor-pointer btn-toggle-modal-password" data-target="modal-current-password">
                                        <i data-lucide="eye" class="w-4 h-4"></i>
                                    </button>
                                </div>
                            </div>
                            <div>
                                <label class="text-[10px] font-bold text-muted-foreground uppercase tracking-wider block mb-1">Kata Sandi Baru</label>
                                <div class="relative">
                                    <input type="password" id="modal-new-password" class="w-full bg-muted text-foreground text-xs px-4 py-2 pr-10 rounded-xl border border-border focus:outline-none focus:ring-1 focus:ring-[#0052cc]/50" placeholder="••••••••">
                                    <button type="button" class="absolute right-3 top-1/2 -translate-y-1/2 text-muted-foreground hover:text-foreground focus:outline-none cursor-pointer btn-toggle-modal-password" data-target="modal-new-password">
                                        <i data-lucide="eye" class="w-4 h-4"></i>
                                    </button>
                                </div>
                            </div>
                            <div>
                                <label class="text-[10px] font-bold text-muted-foreground uppercase tracking-wider block mb-1">Konfirmasi Kata Sandi Baru</label>
                                <div class="relative">
                                    <input type="password" id="modal-confirm-password" class="w-full bg-muted text-foreground text-xs px-4 py-2 pr-10 rounded-xl border border-border focus:outline-none focus:ring-1 focus:ring-[#0052cc]/50" placeholder="••••••••">
                                    <button type="button" class="absolute right-3 top-1/2 -translate-y-1/2 text-muted-foreground hover:text-foreground focus:outline-none cursor-pointer btn-toggle-modal-password" data-target="modal-confirm-password">
                                        <i data-lucide="eye" class="w-4 h-4"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
                <div class="flex flex-col gap-2">
                    <button type="button" id="btn-save-profile" class="w-full btn-3d-red py-3 rounded-xl text-sm font-bold shadow-lg shadow-red-500/10 cursor-pointer">Simpan Perubahan</button>
                    <button type="button" id="btn-logout-modal" class="w-full flex items-center justify-center gap-2 py-3 text-sm font-semibold text-muted-foreground hover:text-red-500 border border-border hover:border-red-500/30 rounded-xl hover:bg-red-500/5 transition-all cursor-pointer">
                        <i data-lucide="log-out" class="w-4 h-4"></i>
                        Keluar Akun
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Judul Halaman dinamis diambil dari section('title') di masing-masing page -->
    <title>@yield('title', 'Beasiswapedia')</title>
    <!-- CSRF Token Laravel untuk keamanan request POST AJAX / Form -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Terapkan preferensi tema (dark/light) dari LocalStorage sebelum render HTML agar tidak ada kedipan warna --}}
    <script>
        (function () {
            var saved = localStorage.getItem('portal-theme') || 'dark';
            if (saved === 'dark') {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        })();
    </script>

    <!-- Memanggil bundler aset Vite untuk memuat file CSS dan JS utama -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
    @yield('head')
</head>
<body class="min-h-screen bg-background text-foreground antialiased page-entrance">
    <!-- Container untuk notifikasi Toast melayang -->
    <div id="toast-container"></div>

    {{-- Modal Tentang Kami & Kontak Bantuan Hubungi Kami --}}
    <div id="about-modal" class="hidden">
        <div class="modal-backdrop" id="about-modal-backdrop"></div>
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="bg-card border-2 border-border rounded-2xl shadow-2xl w-full max-w-md relative overflow-hidden z-10 modal-box">
                <div class="h-1.5 bg-[#0052cc] w-full"></div>
                <button type="button" id="btn-close-about-modal" class="absolute top-4 right-4 p-1.5 rounded-full hover:bg-muted text-muted-foreground hover:text-foreground transition-colors cursor-pointer">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
                <div class="p-6">
                    <div class="flex items-center gap-2 mb-4">
                        <i data-lucide="info" class="w-5 h-5 text-[#0052cc]"></i>
                        <h2 class="text-foreground font-black text-lg">Tentang Kami</h2>
                    </div>
                    <div class="space-y-3 text-xs leading-relaxed text-muted-foreground mb-6">
                        <p><strong>Beasiswapedia</strong> adalah platform informasi beasiswa terpercaya yang dirancang khusus untuk mempermudah pelajar Indonesia dalam mencari dan melamar berbagai program beasiswa secara real-time.</p>
                        <p>Visi kami adalah mewujudkan pemerataan pendidikan dengan memberikan akses informasi beasiswa yang akurat, transparan, dan mudah diakses oleh siapa saja, di mana saja.</p>
                        
                        <div class="border-t border-border pt-3 mt-3">
                            <p class="font-bold text-foreground mb-1.5 flex items-center gap-1.5 text-xs">
                                <i data-lucide="alert-triangle" class="w-4 h-4 text-[#0052cc]"></i>
                                Laporkan Masalah / Error
                            </p>
                            <p>Jika Anda mengalami kendala teknis, error saat mengunggah berkas, atau kesulitan lain saat menggunakan website kami, hubungi pusat bantuan kami:</p>
                            <p class="mt-2 text-foreground font-black flex items-center gap-1.5 bg-muted p-2.5 rounded-xl border border-border">
                                <i data-lucide="phone" class="w-4 h-4 text-green-500"></i>
                                WhatsApp / Call Center: +62 812-3456-7890
                            </p>
                        </div>
                    </div>
                    <button type="button" id="btn-close-about-modal-btn" class="w-full btn-3d-red py-3 rounded-xl text-sm font-bold shadow-lg shadow-blue-500/10 cursor-pointer">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Menampilkan konten halaman utama yang di-yield oleh page yang meng-extend file layouts ini -->
    @yield('content')

    <!-- Tombol Scroll ke Atas (Back to Top) Melayang - Tugas 7 -->
    <button type="button" id="btn-back-to-top" class="fixed bottom-6 right-6 z-50 w-11 h-11 bg-[#e53935] hover:bg-[#c62828] text-white rounded-full flex items-center justify-center shadow-2xl hover:scale-110 active:scale-90 transition-all duration-300 translate-y-20 opacity-0 pointer-events-none cursor-pointer" title="Kembali ke atas">
        <i data-lucide="arrow-up" class="w-5 h-5 stroke-[3px]"></i>
    </button>

    <!-- Memuat Lucide Icons library untuk render icon SVG secara dinamis -->
    <script src="https://unpkg.com/lucide@latest"></script>
    @stack('scripts')
    @yield('scripts')
</body>
</html>

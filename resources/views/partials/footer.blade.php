{{-- Footer Website Premium & Aesthetic (Tugas 11 & Request 5 & Theme Unification) --}}
<footer class="relative mt-20 border-t-2 border-blue-800 bg-gradient-to-b from-[#003b99] via-[#002b70] to-[#001f52] text-blue-100 overflow-hidden">
    {{-- Hiasan Lingkaran Blur Latar Belakang (Aesthetic glow effects) --}}
    <div class="absolute -top-24 -left-20 w-80 h-80 rounded-full bg-white/5 blur-[80px] pointer-events-none"></div>
    <div class="absolute -bottom-20 -right-20 w-80 h-80 rounded-full bg-blue-400/5 blur-[80px] pointer-events-none"></div>

    <div class="max-w-screen-xl mx-auto px-6 py-12 relative z-10">
        <div class="grid grid-cols-1 md:grid-cols-5 gap-8 mb-10">
            {{-- Info Brand & Deskripsi Singkat --}}
            <div class="md:col-span-2 space-y-4">
                
                <!-- Logo Brand Unified -->
                <a href="{{ url('/') }}" class="flex items-center gap-2 shrink-0 group inline-block mb-2">
                    <div class="w-10 h-10 rounded-xl bg-white flex items-center justify-center text-[#0052cc] shadow-lg shadow-black/10 group-hover:scale-105 transition-transform duration-300">
                        <i data-lucide="graduation-cap" class="w-6 h-6 stroke-[2.5]"></i>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-xl font-black text-white leading-none tracking-tight">
                            beasiswa<span class="text-[#ff7300]">pedia</span>
                        </span>
                    </div>
                </a>

                <p class="text-blue-200/80 text-xs leading-relaxed max-w-sm">
                    Platform navigasi beasiswa terpercaya dan terintegrasi untuk putra-putri berprestasi Indonesia. Menjangkau impian akademis Anda secara nyata.
                </p>
                {{-- Tombol Sosial Media Animatif 3D --}}
                <div class="flex items-center gap-3 pt-2">
                    <a href="#" class="w-9 h-9 rounded-xl bg-white/10 border border-white/10 flex items-center justify-center text-blue-100 hover:text-white hover:bg-gradient-to-tr hover:from-pink-500 hover:to-orange-500 hover:scale-110 active:scale-90 hover:-translate-y-1 transition-all duration-300 shadow-sm" title="Instagram">
                        <i data-lucide="instagram" class="w-4 h-4"></i>
                    </a>
                    <a href="#" class="w-9 h-9 rounded-xl bg-white/10 border border-white/10 flex items-center justify-center text-blue-100 hover:text-white hover:bg-gradient-to-tr hover:from-blue-600 hover:to-blue-400 hover:scale-110 active:scale-90 hover:-translate-y-1 transition-all duration-300 shadow-sm" title="Facebook">
                        <i data-lucide="facebook" class="w-4 h-4"></i>
                    </a>
                    <a href="#" class="w-9 h-9 rounded-xl bg-white/10 border border-white/10 flex items-center justify-center text-blue-100 hover:text-white hover:bg-gradient-to-tr hover:from-sky-500 hover:to-sky-400 hover:scale-110 active:scale-90 hover:-translate-y-1 transition-all duration-300 shadow-sm" title="Telegram">
                        <i data-lucide="send" class="w-4 h-4"></i>
                    </a>
                </div>
            </div>

            {{-- Tautan Navigasi --}}
            <div>
                <p class="text-white font-black text-xs uppercase tracking-widest mb-4 flex items-center gap-1.5">
                    <span class="w-1 h-3 bg-white rounded-full inline-block"></span> Navigasi
                </p>
                <ul class="space-y-2.5 text-xs font-semibold text-blue-200/80">
                    <li>
                        <a href="{{ url('/home') }}" class="hover:text-white transition-all duration-300 flex items-center gap-1 group">
                            <span class="w-0 group-hover:w-1.5 h-0.5 bg-white rounded-full transition-all duration-300"></span>
                            <span class="group-hover:translate-x-1 transition-transform">Beranda</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('library') }}" class="hover:text-white transition-all duration-300 flex items-center gap-1 group">
                            <span class="w-0 group-hover:w-1.5 h-0.5 bg-white rounded-full transition-all duration-300"></span>
                            <span class="group-hover:translate-x-1 transition-transform">Katalog</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('dashboard') }}" class="hover:text-white transition-all duration-300 flex items-center gap-1 group">
                            <span class="w-0 group-hover:w-1.5 h-0.5 bg-white rounded-full transition-all duration-300"></span>
                            <span class="group-hover:translate-x-1 transition-transform">Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <button type="button" id="btn-about" class="hover:text-white transition-all duration-300 flex items-center gap-1 group cursor-pointer text-left w-full">
                            <span class="w-0 group-hover:w-1.5 h-0.5 bg-white rounded-full transition-all duration-300"></span>
                            <span class="group-hover:translate-x-1 transition-transform">Tentang Kami</span>
                        </button>
                    </li>
                </ul>
            </div>

            {{-- Tautan Kategori Pendidikan --}}
            <div>
                <p class="text-white font-black text-xs uppercase tracking-widest mb-4 flex items-center gap-1.5">
                    <span class="w-1 h-3 bg-white rounded-full inline-block"></span> Kategori
                </p>
                <ul class="space-y-2.5 text-xs font-semibold text-blue-200/80">
                    <li>
                        <a href="{{ route('library', ['level' => 's1']) }}" class="hover:text-white transition-all duration-300 flex items-center gap-1 group">
                            <span class="w-0 group-hover:w-1.5 h-0.5 bg-white rounded-full transition-all duration-300"></span>
                            <span class="group-hover:translate-x-1 transition-transform">S1 / Sarjana</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('library', ['level' => 's2']) }}" class="hover:text-white transition-all duration-300 flex items-center gap-1 group">
                            <span class="w-0 group-hover:w-1.5 h-0.5 bg-white rounded-full transition-all duration-300"></span>
                            <span class="group-hover:translate-x-1 transition-transform">S2 / Master</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('library', ['level' => 's3']) }}" class="hover:text-white transition-all duration-300 flex items-center gap-1 group">
                            <span class="w-0 group-hover:w-1.5 h-0.5 bg-white rounded-full transition-all duration-300"></span>
                            <span class="group-hover:translate-x-1 transition-transform">S3 / Doktor</span>
                        </a>
                    </li>
                </ul>
            </div>

            {{-- Kontak & Bantuan --}}
            <div class="space-y-4">
                <p class="text-white font-black text-xs uppercase tracking-widest flex items-center gap-1.5">
                    <span class="w-1 h-3 bg-white rounded-full inline-block"></span> Kontak
                </p>
                <ul class="space-y-3 text-xs font-semibold text-blue-200/80">
                    <li class="flex items-center gap-2">
                        <i data-lucide="mail" class="w-3.5 h-3.5 text-white shrink-0"></i>
                        <span class="truncate hover:text-white transition-colors">support@beasiswapedia.id</span>
                    </li>
                    <li class="flex items-center gap-2">
                        <i data-lucide="phone" class="w-3.5 h-3.5 text-white shrink-0"></i>
                        <span class="hover:text-white transition-colors">+62 (21) 4567-8910</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <i data-lucide="map-pin" class="w-3.5 h-3.5 text-white shrink-0 mt-0.5"></i>
                        <span class="leading-relaxed">Gedung Pendidikan Tinggi Utama, Jakarta, Indonesia</span>
                    </li>
                </ul>
            </div>
        </div>

        {{-- Hak Cipta & Ketentuan Legal --}}
        <div class="border-t border-blue-800/80 pt-6 flex flex-col sm:flex-row items-center justify-between gap-4 text-blue-300/80 text-[11px] font-medium">
            <p>© 2026 Beasiswapedia. Dibuat dengan dedikasi penuh untuk Indonesia Maju.</p>
            <div class="flex items-center gap-4">
                <a href="#" class="hover:text-white transition-colors">Syarat & Ketentuan</a>
                <span class="text-blue-800">|</span>
                <a href="#" class="hover:text-white transition-colors">Kebijakan Privasi</a>
            </div>
        </div>
    </div>
</footer>

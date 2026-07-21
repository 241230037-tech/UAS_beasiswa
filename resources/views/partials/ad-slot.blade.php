{{--
    Partial: partials/ad-slot.blade.php

    Komponen reusable untuk menampilkan satu Spanduk Iklan (Ad Banner).
    Mendukung media gambar maupun video singkat sebagai latar iklan.

    Parameter yang wajib dikirim via @include:
      - $title       : Judul utama iklan
      - $subtitle    : Sub-judul iklan (biasanya teks promosi menarik)
      - $description : Deskripsi singkat iklan
      - $cta_text    : Teks tombol Call-To-Action (misal: "Daftar Sekarang")
      - $bg_from     : Warna awal gradient background (hex/Tailwind class)
      - $bg_to       : Warna akhir gradient background
      - $tag         : Label/kategori iklan (misal: "PROMO", "BEASISWA")
      - $link        : URL tujuan saat iklan diklik

    Parameter opsional:
      - $image_url   : URL gambar atau video latar iklan (nullable)

    Cara penggunaan:
      @include('partials.ad-slot', [
          'title'       => $ad['title'],
          'subtitle'    => $ad['subtitle'],
          ...
      ])
--}}

{{-- Wrapper iklan sebagai anchor link yang dapat diklik, dengan gradient background dinamis --}}
<a href="{{ isset($id) ? route('ad.click', $id) : ($link ?? '#') }}" target="_blank" rel="noopener noreferrer"
   class="block rounded-xl overflow-hidden relative group cursor-pointer select-none border border-border/20 shadow-[2px_2px_8px_rgba(0,0,0,0.1)] transition-transform duration-300 hover:-translate-y-0.5"
   style="background: linear-gradient(135deg, {{ $bg_from ?? '#0052cc' }}, {{ $bg_to ?? '#003b99' }})">

    @php
        // Deteksi apakah media iklan berupa video berdasarkan ekstensi file
        $isVideo = false;
        if (!empty($image_url)) {
            $ext = strtolower(pathinfo($image_url, PATHINFO_EXTENSION));
            // Daftar ekstensi video yang didukung
            if (in_array($ext, ['mp4', 'webm', 'ogg', 'mov'])) {
                $isVideo = true;
            }
        }
    @endphp

    {{-- Tampilkan media latar (gambar/video) jika URL tersedia --}}
    @if(!empty($image_url))
        <div class="absolute inset-0 z-0">
            @if($isVideo)
                {{-- Video iklan: diputar otomatis, berulang, tanpa suara (muted), dan responsif --}}
                <video autoplay loop muted playsinline class="w-full h-full object-cover">
                    <source src="{{ $image_url }}">
                </video>
            @else
                {{-- Gambar iklan: zoom halus saat hover untuk efek interaktif --}}
                <img src="{{ $image_url }}" alt="{{ $title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
            @endif
            {{-- Overlay gelap transparan agar teks konten iklan lebih mudah dibaca --}}
            <div class="absolute inset-0 bg-black/50 group-hover:bg-black/60 transition-colors duration-300"></div>
        </div>
    @endif

    {{-- Konten Iklan: Tag, Judul, Sub-judul, Deskripsi, dan Tombol CTA --}}
    <div class="relative z-10 flex flex-col justify-between p-4 min-h-[120px] h-full text-white">
        <div>
            {{-- Baris atas: tag kategori (kiri) dan label "Sponsored" (kanan) --}}
            <div class="flex items-center justify-between mb-2">
                {{-- Badge kategori iklan dengan warna oranye brand --}}
                <span class="inline-block px-2 py-0.5 text-[8px] font-black text-white bg-[#ff7300] rounded-md tracking-wider uppercase shadow-sm">{{ $tag ?? 'PROMO' }}</span>
                {{-- Label sponsored di pojok kanan atas --}}
                <span class="text-[8px] text-white/50 font-semibold tracking-wider uppercase">Sponsored</span>
            </div>
            {{-- Judul utama iklan — dipotong satu baris jika terlalu panjang --}}
            <h4 class="text-white font-black text-xs leading-snug line-clamp-1 tracking-tight">{{ $title ?? 'KONSULTASI BEASISWA' }}</h4>
            {{-- Sub-judul iklan — biasanya kata promosi yang menarik perhatian --}}
            <p class="text-yellow-300 font-extrabold text-sm leading-tight mt-0.5 tracking-tight">{{ $subtitle ?? 'GRATIS!' }}</p>
            {{-- Deskripsi singkat iklan — dipotong dua baris jika terlalu panjang --}}
            <p class="text-white/80 text-[10px] leading-snug line-clamp-2 mt-1.5 font-medium">{{ $description ?? '' }}</p>
        </div>
        {{-- Tombol Call-To-Action di bagian bawah kanan iklan --}}
        <div class="mt-4 flex justify-end">
            <span class="inline-block px-3 py-1 text-[9px] font-bold text-white bg-[#ff7300] hover:bg-[#e65c00] rounded-full transition-all duration-300 group-hover:scale-105 shadow-md">{{ $cta_text ?? 'Daftar Sekarang' }}</span>
        </div>
    </div>
</a>

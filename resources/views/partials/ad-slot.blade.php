<a href="{{ $link ?? '#' }}" target="_blank" rel="noopener noreferrer"
   class="block rounded-xl overflow-hidden relative group cursor-pointer select-none border border-border/20 shadow-[2px_2px_8px_rgba(0,0,0,0.1)] transition-transform duration-300 hover:-translate-y-0.5"
   style="background: linear-gradient(135deg, {{ $bg_from ?? '#1a237e' }}, {{ $bg_to ?? '#283593' }})">
    
    @php
        // Cek ekstensi media untuk menentukan jenis tampilan (gambar atau video singkat)
        $isVideo = false;
        if (!empty($image_url)) {
            $ext = strtolower(pathinfo($image_url, PATHINFO_EXTENSION));
            if (in_array($ext, ['mp4', 'webm', 'ogg', 'mov'])) {
                $isVideo = true;
            }
        }
    @endphp

    {{-- Latar media iklan jika tersedia --}}
    @if(!empty($image_url))
        <div class="absolute inset-0 z-0">
            @if($isVideo)
                {{-- Putar video singkat secara otomatis, loop, senyap (muted) --}}
                <video autoplay loop muted playsinline class="w-full h-full object-cover">
                    <source src="{{ $image_url }}">
                </video>
            @else
                {{-- Gambar background iklan --}}
                <img src="{{ $image_url }}" alt="{{ $title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
            @endif
            {{-- Overlay gelap transparan agar teks di atasnya mudah dibaca --}}
            <div class="absolute inset-0 bg-black/50 group-hover:bg-black/60 transition-colors duration-300"></div>
        </div>
    @endif

    {{-- Konten Iklan --}}
    <div class="relative z-10 flex flex-col justify-between p-4 min-h-[120px] h-full">
        <div>
            <div class="flex items-center justify-between mb-2">
                <span class="inline-block px-2 py-0.5 text-[8px] font-black text-white bg-[#e53935] rounded-md tracking-wider uppercase shadow-sm">{{ $tag ?? 'PROMO' }}</span>
                <span class="text-[8px] text-white/50 font-semibold tracking-wider uppercase">Sponsored</span>
            </div>
            <h4 class="text-white font-black text-xs leading-snug line-clamp-1 tracking-tight">{{ $title ?? 'KONSULTASI BEASISWA' }}</h4>
            <p class="text-yellow-300 font-extrabold text-sm leading-tight mt-0.5 tracking-tight">{{ $subtitle ?? 'GRATIS!' }}</p>
            <p class="text-white/80 text-[10px] leading-snug line-clamp-2 mt-1.5 font-medium">{{ $description ?? '' }}</p>
        </div>
        <div class="mt-4 flex justify-end">
            <span class="inline-block px-3 py-1 text-[9px] font-bold text-white bg-[#e53935] hover:bg-[#c62828] rounded-full transition-all duration-300 group-hover:scale-105 shadow-md">{{ $cta_text ?? 'Daftar Sekarang' }}</span>
        </div>
    </div>
</a>

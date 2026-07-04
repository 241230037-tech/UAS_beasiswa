<a href="{{ $link ?? '#' }}" target="_blank" rel="noopener noreferrer"
   class="block rounded-lg overflow-hidden relative group cursor-pointer select-none"
   style="background: linear-gradient(135deg, {{ $bg_from ?? '#1a237e' }}, {{ $bg_to ?? '#283593' }})">
    <div class="flex items-center justify-between px-4 py-3 min-h-[56px]">
        <div class="flex items-center gap-3">
            <div class="shrink-0">
                <span class="inline-block px-2 py-0.5 text-[10px] font-black text-white bg-[#e53935] rounded mb-1">{{ $tag ?? 'PROMO' }}</span>
                <p class="text-white font-black text-base leading-tight">{{ $title ?? 'KONSULTASI BEASISWA' }}</p>
                <p class="text-yellow-300 font-black text-lg leading-tight -mt-0.5">{{ $subtitle ?? 'GRATIS!' }}</p>
                <p class="text-white/70 text-[10px] leading-tight">{{ $description ?? '' }}</p>
            </div>
        </div>
        <div class="shrink-0 ml-4">
            <span class="block px-3 py-1.5 text-xs font-bold text-white bg-[#e53935] hover:bg-[#c62828] rounded-full transition-colors group-hover:scale-105 transform transition-transform whitespace-nowrap">{{ $cta_text ?? 'Daftar Sekarang' }}</span>
        </div>
    </div>
    <div class="absolute right-20 top-1/2 -translate-y-1/2 w-12 h-12 rounded-full bg-white/5 pointer-events-none"></div>
    <div class="absolute right-28 top-0 w-8 h-8 rounded-full bg-white/5 pointer-events-none"></div>
</a>

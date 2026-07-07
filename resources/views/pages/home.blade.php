@extends('layouts.app')

@section('title', 'Beranda - PortalBeasiswa')

@section('content')
<div class="min-h-screen bg-background">
    @include('partials.navbar')

    <div class="max-w-screen-xl mx-auto px-4 py-6">
        <div class="w-full">
            
            {{-- Carousel / Slider Beranda Premium Dinamis (Tugas 3 / Request 1 & 2) --}}
            @php $totalSlides = count($carouselItems); @endphp
            @if($totalSlides > 0)
            <div class="relative w-full overflow-hidden rounded-2xl border-2 border-border/20 shadow-xl mb-6 bg-slate-950 text-white min-h-[300px] sm:min-h-[360px]" id="home-carousel">
                {{-- Slide Wrapper --}}
                <div class="flex flex-shrink-0 transition-transform duration-500 ease-in-out h-full" id="carousel-slides" style="width: {{ $totalSlides * 100 }}%;">
                    @foreach($carouselItems as $index => $item)
                        @if($item['type'] === 'scholarship' && isset($item['scholarship']))
                            @php $s = $item['scholarship']; @endphp
                            {{-- Slide Beasiswa Terhubung (Trending) --}}
                            <div class="flex-shrink-0 relative overflow-hidden h-[300px] sm:h-[360px] flex items-center justify-between p-6 sm:p-12" style="width: {{ 100 / $totalSlides }}%; background: linear-gradient(135deg, #1e3a8a, #0d1b2a)">
                                <div class="absolute inset-0 z-0 opacity-40 bg-cover bg-center" style="background-image: url('{{ $s['image'] }}'); filter: blur(4px);"></div>
                                <div class="absolute inset-0 bg-gradient-to-r from-slate-950 via-slate-950/80 to-transparent z-0"></div>
                                <div class="relative z-10 max-w-xl space-y-3 sm:space-y-4">
                                    <span class="inline-block px-2.5 py-1 text-[10px] font-black text-white bg-[#e53935] rounded-md tracking-wider uppercase shadow-sm">Trending #{{ $index + 1 }}</span>
                                    <h3 class="text-xl sm:text-3xl font-black leading-tight text-white">{{ $s['title'] }}</h3>
                                    <p class="text-white/80 text-xs sm:text-sm font-medium">{{ $s['provider'] }}. Dapatkan kesempatan pendanaan pendidikan tinggi terbaik.</p>
                                    <div class="flex flex-wrap items-center gap-3 text-xs">
                                        <span class="bg-white/10 px-2.5 py-1 rounded-full font-semibold">📍 {{ $s['location'] }}</span>
                                        <span class="bg-white/10 px-2.5 py-1 rounded-full font-semibold">💎 {{ $s['amount'] }}</span>
                                        <span class="text-red-400 font-bold">Deadline: {{ $s['deadline'] }}</span>
                                    </div>
                                    <div class="pt-2">
                                        <a href="/scholarship/{{ $s['id'] }}" class="btn-3d-red px-6 py-3 rounded-xl text-xs font-bold inline-flex items-center gap-2">
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
                            <div class="flex-shrink-0 relative overflow-hidden h-[300px] sm:h-[360px] flex items-center justify-between p-6 sm:p-10" style="width: {{ 100 / $totalSlides }}%; background: linear-gradient(135deg, #0f0c29, #302b63, #24243e)">
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
                                            {{-- File video lokal — gunakan streaming route untuk support seek/skip --}}
                                            @php
                                                // Ekstrak nama file dari berbagai format video_url yang tersimpan
                                                $rawVideoUrl = $item['video_url'];
                                                // Jika berupa full URL (http://...), ambil path-nya
                                                if (filter_var($rawVideoUrl, FILTER_VALIDATE_URL)) {
                                                    $videoFilename = basename(parse_url($rawVideoUrl, PHP_URL_PATH));
                                                } else {
                                                    // Jika path relatif seperti "carousel-videos/xxx.mp4"
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
                                                    {{-- Streaming route: mengirim Accept-Ranges + 206 Partial Content untuk seek --}}
                                                    <source src="{{ $streamUrl }}" type="video/mp4">
                                                    <source src="{{ $streamUrl }}" type="video/webm">
                                                    Browser Anda tidak mendukung pemutar video.
                                                </video>
                                            </div>
                                        @endif
                                    </div>

                                @else
                                    {{-- Frame Kosongan — layout 2 kolom --}}
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

            {{-- Kolom Iklan Bagian Atas (Request 5) --}}
            @if(count($topAds) > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-6">
                @foreach($topAds as $ad)
                    @include('partials.ad-slot', [
                        'title' => $ad['title'],
                        'subtitle' => $ad['subtitle'],
                        'description' => $ad['description'],
                        'cta_text' => $ad['cta_text'],
                        'bg_from' => $ad['bg_from'],
                        'bg_to' => $ad['bg_to'],
                        'tag' => $ad['tag'],
                        'link' => $ad['link'] ?? '#',
                        'image_url' => $ad['image_url'] ?? null,
                    ])
                @endforeach
            </div>
            @endif


            {{-- Bagian Daftar Beasiswa Update --}}
            <div class="mb-6">
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center gap-2">
                        <span class="w-1 h-5 bg-[#e53935] rounded-sm inline-block"></span>
                        <h2 class="text-foreground font-bold text-sm">Beasiswa Update</h2>
                    </div>
                    <a href="{{ route('library') }}" class="text-[#e53935] hover:text-[#ef5350] text-xs transition-colors">Lihat Semua</a>
                </div>
                <div id="home-updates-grid" class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3" data-raw-scholarships='@json($scholarships)'>
                    @foreach(array_slice($scholarships, 0, 4) as $scholarship)
                        @include('partials.scholarship-card', ['scholarship' => $scholarship])
                    @endforeach
                </div>
            </div>

            {{-- Kolom Iklan Bagian Bawah Diatas Beasiswa Terbaru (Request 5) --}}
            @if(count($bottomAds) > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-6">
                @foreach($bottomAds as $ad)
                    @include('partials.ad-slot', [
                        'title' => $ad['title'],
                        'subtitle' => $ad['subtitle'],
                        'description' => $ad['description'],
                        'cta_text' => $ad['cta_text'],
                        'bg_from' => $ad['bg_from'],
                        'bg_to' => $ad['bg_to'],
                        'tag' => $ad['tag'],
                        'link' => $ad['link'] ?? '#',
                        'image_url' => $ad['image_url'] ?? null,
                    ])
                @endforeach
            </div>
            @endif

            {{-- Bagian Daftar Beasiswa Terbaru --}}
            <div class="mb-6">
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center gap-2">
                        <span class="w-1 h-5 bg-[#e53935] rounded-sm inline-block"></span>
                        <h2 class="text-foreground font-bold text-sm">Beasiswa Terbaru</h2>
                    </div>
                    <a href="{{ route('library') }}" class="text-[#e53935] hover:text-[#ef5350] text-xs transition-colors">Lihat Semua</a>
                </div>
                <div id="home-latest-grid" class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3">
                    @foreach(array_slice($scholarships, 4, 8) as $scholarship)
                        @include('partials.scholarship-card', ['scholarship' => $scholarship])
                    @endforeach
                </div>
            </div>

        </div>
    </div>

    @include('partials.footer')
</div>
@endsection

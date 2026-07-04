@extends('layouts.app')

@section('title', 'Beranda - PortalBeasiswa')

@section('content')
<div class="min-h-screen bg-background">
    @include('partials.navbar')

    <div class="max-w-screen-xl mx-auto px-4 py-4">
        <div class="flex gap-5">
            <div class="flex-1 min-w-0">
                <div class="flex flex-col gap-1.5 mb-5">
                    @foreach($adBanners as $ad)
                        @include('partials.ad-slot', [
                            'title' => $ad['title'],
                            'subtitle' => $ad['subtitle'],
                            'description' => $ad['description'],
                            'cta_text' => $ad['cta_text'],
                            'bg_from' => $ad['bg_from'],
                            'bg_to' => $ad['bg_to'],
                            'tag' => $ad['tag'],
                            'link' => $ad['link'] ?? '#',
                        ])
                    @endforeach
                </div>

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

                <div class="mb-5">
                    @include('partials.ad-slot', [
                        'title' => 'MENTORING BEASISWA',
                        'subtitle' => '1-ON-1!',
                        'description' => 'Konsultasi langsung dengan awardee beasiswa luar negeri',
                        'cta_text' => 'Booking Sekarang',
                        'bg_from' => '#b71c1c',
                        'bg_to' => '#c62828',
                        'tag' => 'EKSKLUSIF',
                        'link' => '/tutorial',
                    ])
                </div>

                <div>
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

            <div class="hidden lg:block w-56 shrink-0">
                <div class="sticky top-20">
                    {{-- 2 Kotak Video Placeholder --}}
                    <div class="flex flex-col gap-2 mb-4">
                        <div class="aspect-video w-full rounded-xl bg-card border-2 border-border flex items-center justify-center relative overflow-hidden group hover:border-[#e53935]/40 transition-colors shadow-[2px_2px_0px_0px_rgba(0,0,0,0.05)] bg-slate-900 dark:bg-slate-950">
                            <div class="absolute inset-0 bg-black/35 flex flex-col items-center justify-center p-2 text-center">
                                <i data-lucide="play" class="w-5 h-5 text-white mb-1 opacity-75 group-hover:scale-110 transition-transform"></i>
                                <span class="text-[9px] font-black text-white/90 uppercase tracking-widest">Video 1</span>
                            </div>
                        </div>
                        <div class="aspect-video w-full rounded-xl bg-card border-2 border-border flex items-center justify-center relative overflow-hidden group hover:border-[#e53935]/40 transition-colors shadow-[2px_2px_0px_0px_rgba(0,0,0,0.05)] bg-slate-900 dark:bg-slate-950">
                            <div class="absolute inset-0 bg-black/35 flex flex-col items-center justify-center p-2 text-center">
                                <i data-lucide="play" class="w-5 h-5 text-white mb-1 opacity-75 group-hover:scale-110 transition-transform"></i>
                                <span class="text-[9px] font-black text-white/90 uppercase tracking-widest">Video 2</span>
                            </div>
                        </div>
                    </div>

                    {{-- Title Centered --}}
                    <div class="flex flex-col items-center justify-center text-center mb-3">
                        <h3 class="text-foreground font-bold text-sm">Beasiswa Trending</h3>
                        <a href="{{ route('library', ['sort' => 'popular']) }}" class="text-[#e53935] hover:text-[#ef5350] text-[11px] font-semibold transition-colors mt-0.5">Lihat Semua</a>
                    </div>
                    <div id="home-trending-list" class="flex flex-col gap-0.5">
                        @foreach(array_slice($scholarships, 0, 3) as $s)
                            <a href="{{ $s['external_link'] }}" target="_blank" rel="noopener noreferrer"
                                class="flex gap-2 p-2 rounded-lg hover:bg-muted transition-colors group cursor-pointer">
                                <div class="relative shrink-0 w-10 h-10 bg-white flex items-center justify-center p-1.5 border border-border rounded overflow-hidden">
                                    <img src="{{ asset(ltrim($s['image'], '/')) }}" alt="{{ $s['title'] }}" class="max-w-full max-h-full object-contain">
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-foreground text-[11px] font-semibold line-clamp-2 leading-tight group-hover:text-[#e53935] transition-colors">{{ $s['title'] }}</p>
                                    <p class="text-muted-foreground text-[10px] mt-0.5">{{ $s['level'] }}</p>
                                    <p class="text-muted-foreground text-[10px]">{{ $s['updated_ago'] }}</p>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('partials.footer')
</div>
@endsection

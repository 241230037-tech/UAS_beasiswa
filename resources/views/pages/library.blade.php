@extends('layouts.app')

@section('title', 'Katalog Beasiswa - Beasiswapedia')

@section('content')
<div class="min-h-screen bg-background">
    @include('partials.navbar')

    <div id="library-page" class="max-w-screen-xl mx-auto px-4 py-6"
        data-scholarships='@json(collect($scholarships)->map(fn($s) => array_merge($s, ["image" => asset(ltrim($s["image"], "/"))]))->values())'
        data-initial-q="{{ $filters['q'] ?? '' }}">

        <div class="flex gap-5">
            <div class="hidden md:block w-52 shrink-0">
                <div class="sticky top-20">
                    @include('partials.library-filter')
                </div>
            </div>

            <div class="flex-1 min-w-0">
                <div class="flex items-center justify-between mb-4">
                    <h1 class="text-foreground font-bold text-lg">Katalog Beasiswa</h1>
                    <div class="flex items-center gap-2">
                        <p class="text-muted-foreground text-xs"><span id="library-count">{{ count($scholarships) }}</span> beasiswa ditemukan</p>
                        <button type="button" id="btn-mobile-filter" class="md:hidden flex items-center gap-1 px-3 py-1.5 text-xs font-semibold bg-muted text-foreground rounded-lg border border-border">Filter</button>
                    </div>
                </div>

                <div id="mobile-filter" class="md:hidden mb-4 hidden">
                    @include('partials.library-filter')
                </div>

                <div id="library-empty" class="text-center py-16 text-muted-foreground hidden">
                    <p class="text-lg mb-2">Tidak ada beasiswa ditemukan</p>
                    <p class="text-sm">Coba ubah filter atau kata kunci pencarian</p>
                </div>

                <div id="library-grid" class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3">
                    @foreach($scholarships as $scholarship)
                        @include('partials.scholarship-card', ['scholarship' => $scholarship])
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('title', 'Dashboard - PortalBeasiswa')

@section('content')
<div class="min-h-screen bg-background">
    @include('partials.navbar')

    <div id="dashboard-bookmarks" class="max-w-screen-xl mx-auto px-4 py-6 max-w-4xl"
        data-scholarships='@json(collect($scholarships)->map(fn($s) => array_merge($s, ["image" => asset(ltrim($s["image"], "/"))]))->values())'>

        <div class="flex items-center justify-between mb-6">
            <h1 class="text-foreground font-bold text-2xl">Dashboard</h1>
            <div class="flex items-center gap-2">
                <button type="button" class="p-2 text-muted-foreground hover:text-foreground transition-colors" title="Refresh">
                    <i data-lucide="refresh-cw" class="w-5 h-5"></i>
                </button>
                <button type="button" class="p-2 text-muted-foreground hover:text-foreground transition-colors" title="Tandai semua">
                    <i data-lucide="check" class="w-5 h-5"></i>
                </button>
            </div>
        </div>

        <div class="grid grid-cols-3 gap-3 mb-5">
            <div class="bg-card border border-border rounded-xl p-4 text-center">
                <p id="bookmark-count" class="text-3xl font-black text-[#e53935] mb-1">0</p>
                <p class="text-foreground text-sm font-semibold">Bookmark</p>
                <p class="text-muted-foreground text-[10px]">(Maks 50)</p>
            </div>
            <div class="bg-card border border-border rounded-xl p-4 text-center">
                <p class="text-3xl font-black text-[#e53935] mb-1">0</p>
                <p class="text-foreground text-sm font-semibold">Ditandai</p>
                <p class="text-muted-foreground text-[10px]">(Tidak Terbatas)</p>
            </div>
            <div class="bg-card border border-border rounded-xl p-4 text-center">
                <p class="text-3xl font-black text-[#e53935] mb-1">{{ count($scholarships) }}</p>
                <p class="text-foreground text-sm font-semibold">Beasiswa Update</p>
                <p class="text-muted-foreground text-[10px]">(Terbaru)</p>
            </div>
        </div>

        <div class="relative mb-5">
            <i data-lucide="search" class="w-4 h-4 text-muted-foreground absolute left-3 top-1/2 -translate-y-1/2"></i>
            <input type="text" id="dashboard-search" placeholder="Cari di bookmark atau beasiswa yang ditandai..."
                class="w-full bg-muted text-foreground placeholder-muted-foreground px-4 py-3 pl-10 rounded-xl border border-border focus:outline-none focus:ring-2 focus:ring-[#e53935]/50 text-sm">
        </div>

        <div class="flex border-b border-border mb-6">
            <button type="button" id="tab-bookmark" class="px-4 py-2.5 text-sm font-semibold transition-colors border-b-2 -mb-px border-[#e53935] text-[#e53935]">
                <span id="tab-bookmark-label">Bookmark (0)</span>
            </button>
            <button type="button" id="tab-marked" class="px-4 py-2.5 text-sm font-semibold transition-colors border-b-2 -mb-px border-transparent text-muted-foreground hover:text-foreground">
                Ditandai (0)
            </button>
        </div>

        <div id="dashboard-grid" class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3 hidden"></div>

        <div id="dashboard-empty" class="text-center py-16 text-muted-foreground">
            <i data-lucide="bookmark" class="w-12 h-12 mx-auto mb-4 opacity-30"></i>
            <p id="empty-title" class="font-semibold text-foreground mb-1">Belum ada beasiswa yang disimpan</p>
            <p id="empty-desc" class="text-sm mb-5">Mulai tambahkan beasiswa ke bookmark!</p>
            <a href="{{ route('library') }}" class="inline-block px-6 py-2.5 bg-[#e53935] hover:bg-[#c62828] text-white rounded-full text-sm font-semibold transition-colors">Jelajahi Beasiswa</a>
        </div>
    </div>
</div>
@endsection

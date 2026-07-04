@extends('layouts.app')

@section('title', 'Panduan Daftar Beasiswa - PortalBeasiswa')

@section('content')
<div class="min-h-screen bg-background text-foreground font-sans">
    @include('partials.navbar')

    <div class="max-w-screen-xl mx-auto px-4 py-8 max-w-4xl">
        <div class="text-center mb-12">
            <div class="inline-flex items-center gap-2 px-3 py-1 bg-red-100 dark:bg-red-950/40 text-red-500 rounded-full text-xs font-bold mb-4 border border-red-200 dark:border-red-900/30">
                <i data-lucide="help-circle" class="w-4 h-4"></i>
                Panduan Lengkap
            </div>
            <h1 class="text-3xl md:text-5xl font-black mb-4 select-none tracking-tight">
                <span class="text-3d-dark">PANDUAN DAFTAR</span> <span class="text-3d-red">BEASISWA</span>
            </h1>
            <p class="text-muted-foreground text-sm md:text-base max-w-xl mx-auto">
                Ikuti 6 langkah terstruktur ini untuk mempersiapkan aplikasi beasiswa Anda secara matang dari awal hingga dinyatakan lulus!
            </p>
        </div>

        <div class="space-y-8">
            @foreach($steps as $step)
                <div class="card-3d p-6 md:p-8 rounded-2xl bg-card border-2 border-border flex flex-col md:flex-row gap-6 relative">
                    <div class="absolute -top-4 -left-4 w-10 h-10 rounded-xl bg-[#e53935] text-white flex items-center justify-center font-black shadow-[2px_2px_0px_0px_rgba(0,0,0,0.15)] border border-red-700">
                        {{ $step['num'] }}
                    </div>
                    <div class="w-14 h-14 shrink-0 rounded-2xl bg-red-50 dark:bg-red-950/20 border border-red-100 dark:border-red-900/30 flex items-center justify-center text-[#e53935] mt-2">
                        <i data-lucide="{{ $step['icon'] }}" class="w-7 h-7"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h3 class="text-lg md:text-xl font-bold text-foreground mb-3">{{ $step['title'] }}</h3>
                        <p class="text-muted-foreground text-sm leading-relaxed mb-4">{{ $step['desc'] }}</p>
                        <div class="bg-muted/50 rounded-xl p-4 border border-border">
                            <p class="text-xs font-bold text-[#e53935] uppercase tracking-wider mb-2 flex items-center gap-1.5">
                                <i data-lucide="sparkles" class="w-3.5 h-3.5"></i>
                                Tips Sukses:
                            </p>
                            <ul class="space-y-1.5">
                                @foreach($step['tips'] as $tip)
                                    <li class="text-xs text-muted-foreground flex items-start gap-2">
                                        <span class="text-[#e53935] font-bold mt-0.5">•</span>
                                        <span>{{ $tip }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="card-3d bg-slate-900 dark:bg-slate-950 text-white rounded-2xl p-8 mt-12 text-center border-2 border-red-600/30 shadow-xl">
            <h3 class="text-2xl font-black text-white mb-2 tracking-tight">Butuh Konsultasi Tambahan?</h3>
            <p class="text-slate-400 text-sm mb-6 max-w-lg mx-auto">
                Anda dapat memanfaatkan fitur Mentoring 1-on-1 atau Konsultasi Gratis bersama Awardee dengan mengklik menu atau spanduk promo di halaman Beranda.
            </p>
            <a href="{{ url('/home') }}" class="btn-3d-red inline-flex py-3 px-8 rounded-xl font-bold text-sm">Kembali ke Beranda</a>
        </div>
    </div>
</div>
@endsection

@php
    $statusColor = match($scholarship['status'] ?? 'Dibuka') {
        'Dibuka' => 'bg-green-600',
        'Akan Datang' => 'bg-blue-600',
        default => 'bg-gray-600',
    };
@endphp
<a href="{{ route('scholarship.detail', $scholarship['id']) }}"
   class="scholarship-card group block rounded-xl overflow-hidden"
   data-scholarship-id="{{ $scholarship['id'] }}">
    <div class="scholarship-card-image relative overflow-hidden flex items-center justify-center p-6" style="aspect-ratio: 4/3">
        <img src="{{ asset(ltrim($scholarship['image'], '/')) }}" alt="{{ $scholarship['title'] }}"
            class="max-w-[85%] max-h-[85%] object-contain group-hover:scale-105 transition-transform duration-300"
            onerror="this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%22100%22 height=%2260%22%3E%3Crect fill=%22%23f3f4f6%22 width=%22100%22 height=%2260%22/%3E%3Ctext x=%2250%25%22 y=%2250%25%22 dominant-baseline=%22middle%22 text-anchor=%22middle%22 fill=%22%23999%22 font-size=%2212%22%3ELogo%3C/text%3E%3C/svg%3E'">
        <div class="absolute top-2 right-2 text-xl drop-shadow-md leading-none">{{ $scholarship['flag'] ?? '🌐' }}</div>
        @if(!empty($scholarship['level']))
            <div class="absolute top-2 left-2 px-1.5 py-0.5 text-[10px] font-bold text-white bg-black/70 rounded backdrop-blur-sm">{{ $scholarship['level'] }}</div>
        @endif
        <div class="absolute bottom-2 left-2 px-2 py-0.5 text-[10px] font-bold text-white rounded-full shadow {{ $statusColor }}">{{ $scholarship['status'] ?? 'Dibuka' }}</div>
        <div class="absolute inset-0 bg-black/10 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none">
            <span class="bg-[#e53935] text-white text-[11px] font-bold px-3 py-1 rounded-full shadow-lg">Lihat Detail →</span>
        </div>
    </div>
    <div class="p-2.5 bg-card">
        <h3 class="text-xs font-semibold text-foreground line-clamp-2 leading-tight mb-1.5 group-hover:text-[#e53935] transition-colors min-h-[32px]">{{ $scholarship['title'] }}</h3>
        <div class="flex items-center justify-between text-[10px] text-muted-foreground">
            <span class="truncate max-w-[70%]">{{ $scholarship['location'] }}</span>
            <span>{{ $scholarship['updated_ago'] ?? 'Baru' }}</span>
        </div>
        <div class="flex items-center justify-between text-[10px] text-muted-foreground mt-0.5">
            <span class="truncate max-w-[70%]">{{ $scholarship['amount'] }}</span>
            <span class="text-[#e53935] shrink-0 font-bold">↗ Detail</span>
        </div>
    </div>
</a>

{{--
    Partial: partials/scholarship-card.blade.php

    Komponen reusable untuk menampilkan satu kartu Beasiswa dalam katalog atau grid.
    Menampilkan gambar/logo beasiswa, badge level, status, dan info ringkas.
    Kartu dapat diklik untuk menuju halaman detail beasiswa.

    Parameter yang wajib dikirim via @include atau @foreach:
      - $scholarship : Array data satu beasiswa dengan setidaknya key berikut:
          - 'id'          : ID unik beasiswa (string)
          - 'title'       : Judul/nama beasiswa
          - 'image'       : URL gambar/logo beasiswa
          - 'location'    : Negara/kota tujuan beasiswa
          - 'amount'      : Nilai tunjangan beasiswa
          - 'status'      : Status beasiswa ('Dibuka', 'Akan Datang', atau lainnya)
          - 'flag'        : Emoji bendera negara (opsional)
          - 'level'       : Jenjang studi (opsional)
          - 'updated_ago' : Keterangan kapan terakhir diperbarui (opsional)

    Cara penggunaan:
      @include('partials.scholarship-card', ['scholarship' => $scholarshipArray])
--}}

@php
    // Tentukan warna badge status berdasarkan nilai kolom 'status'
    $statusColor = match($scholarship['status'] ?? 'Dibuka') {
        'Dibuka'      => 'bg-green-600', // Hijau = sedang dibuka
        'Akan Datang' => 'bg-blue-600',  // Biru = akan segera dibuka
        default       => 'bg-gray-600',  // Abu-abu = tutup atau status lainnya
    };
@endphp

{{-- Wrapper kartu sebagai anchor link menuju halaman detail beasiswa --}}
<a href="{{ route('scholarship.detail', $scholarship['id']) }}"
   class="scholarship-card group block rounded-xl overflow-hidden"
   data-scholarship-id="{{ $scholarship['id'] }}">

    {{-- Area Gambar/Logo Beasiswa --}}
    <div class="scholarship-card-image relative overflow-hidden flex items-center justify-center p-6" style="aspect-ratio: 4/3">
        {{--
            Gambar logo beasiswa; jika gagal dimuat (onerror), tampilkan placeholder SVG berwarna abu-abu.
            ltrim() digunakan untuk memastikan tidak ada duplikasi slash di awal path.
        --}}
        <img src="{{ asset(ltrim($scholarship['image'], '/')) }}" alt="{{ $scholarship['title'] }}"
             class="max-w-[85%] max-h-[85%] object-contain group-hover:scale-105 transition-transform duration-300"
             onerror="this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%22100%22 height=%2260%22%3E%3Crect fill=%22%23f3f4f6%22 width=%22100%22 height=%2260%22/%3E%3Ctext x=%2250%25%22 y=%2250%25%22 dominant-baseline=%22middle%22 text-anchor=%22middle%22 fill=%22%23999%22 font-size=%2212%22%3ELogo%3C/text%3E%3C/svg%3E'">

        {{-- Badge bendera negara di pojok kanan atas --}}
        <div class="absolute top-2 right-2 text-xl drop-shadow-md leading-none">{{ $scholarship['flag'] ?? '🌐' }}</div>

        {{-- Badge jenjang studi (S1/S2/S3) di pojok kiri atas — ditampilkan hanya jika ada --}}
        @if(!empty($scholarship['level']))
            <div class="absolute top-2 left-2 px-1.5 py-0.5 text-[10px] font-bold text-white bg-black/70 rounded backdrop-blur-sm">{{ $scholarship['level'] }}</div>
        @endif

        {{-- Badge status beasiswa di pojok kiri bawah dengan warna dinamis berdasarkan status --}}
        <div class="absolute bottom-2 left-2 px-2 py-0.5 text-[10px] font-bold text-white rounded-full shadow {{ $statusColor }}">{{ $scholarship['status'] ?? 'Dibuka' }}</div>

        {{-- Overlay "Lihat Detail" yang muncul saat kartu di-hover --}}
        <div class="absolute inset-0 bg-black/10 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none">
            <span class="bg-[#0052cc] text-white text-[11px] font-bold px-3 py-1 rounded-full shadow-lg">Lihat Detail →</span>
        </div>
    </div>

    {{-- Area Informasi Beasiswa (di bawah gambar) --}}
    <div class="p-2.5 bg-card">
        {{-- Judul beasiswa — dipotong maksimal 2 baris, berubah warna saat hover --}}
        <h3 class="text-xs font-semibold text-foreground line-clamp-2 leading-tight mb-1.5 group-hover:text-[#0052cc] transition-colors min-h-[32px]">{{ $scholarship['title'] }}</h3>

        {{-- Baris pertama: lokasi (kiri) dan waktu update (kanan) --}}
        <div class="flex items-center justify-between text-[10px] text-muted-foreground">
            <span class="truncate max-w-[70%]">{{ $scholarship['location'] }}</span>
            <span>{{ $scholarship['updated_ago'] ?? 'Baru' }}</span>
        </div>

        {{-- Baris kedua: nilai tunjangan (kiri) dan link detail (kanan) --}}
        <div class="flex items-center justify-between text-[10px] text-muted-foreground mt-0.5">
            <span class="truncate max-w-[70%]">{{ $scholarship['amount'] }}</span>
            <span class="text-[#0052cc] shrink-0 font-bold">↗ Detail</span>
        </div>
    </div>
</a>

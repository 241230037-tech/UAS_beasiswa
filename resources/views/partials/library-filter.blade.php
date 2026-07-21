{{--
    Partial: partials/library-filter.blade.php

    Panel Filter Katalog Beasiswa yang ditampilkan di sidebar kiri halaman Library.
    Semua interaksi filter (sort, status, level, negara) ditangani sepenuhnya oleh JavaScript
    di sisi client — tidak ada reload halaman (SPA-like filtering).

    Filter yang tersedia:
      1. Urutkan    : Relevansi, Terbaru, A-Z, Terbanyak Dilihat, Baru
      2. Cocokan    : Match ANY (minimal satu filter cocok) atau Match ALL (semua filter harus cocok)
      3. Status     : Semua Status, Dibuka, Akan Datang, Ditutup
      4. Tingkat    : S1/Sarjana, S2/Master, S3/Doktor, Vokasi/D4
      5. Negara     : Dropdown daftar negara yang tersedia

    Cara penggunaan:
      @include('partials.library-filter')
--}}

{{-- Container panel filter dengan background kartu dan border --}}
<div class="library-filter-panel bg-card border border-border rounded-xl p-4 w-full">

    {{-- Header panel filter dengan judul dan tombol reset --}}
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-[#0052cc] font-bold text-sm">Filter</h3>
        {{-- Tombol Reset: menghapus semua filter aktif dan menampilkan ulang semua beasiswa --}}
        <button type="button" class="library-filter-reset text-muted-foreground hover:text-foreground text-xs transition-colors">Reset</button>
    </div>

    {{-- FILTER 1: Pilihan Pengurutan Beasiswa --}}
    <div class="mb-5">
        <p class="text-foreground text-xs font-semibold mb-2">Urutkan</p>
        <div class="flex flex-wrap gap-1.5" id="library-sort-btns">
            {{-- Render tombol sort secara dinamis dari array opsi --}}
            @foreach(['Relevansi', 'Terbaru', 'A-Z', 'Terbanyak Dilihat', 'Baru'] as $sort)
                {{--
                    Tombol sort aktif (default: 'Relevansi') diberi background biru dan teks putih.
                    JavaScript akan toggle class active saat tombol diklik.
                --}}
                <button type="button" data-sort="{{ $sort }}"
                    class="library-sort-btn px-2.5 py-1 rounded-full text-[11px] font-semibold transition-colors {{ $sort === 'Relevansi' ? 'bg-[#0052cc] text-white' : 'bg-muted text-muted-foreground hover:bg-accent hover:text-foreground' }}">
                    {{ $sort }}
                </button>
            @endforeach
        </div>
    </div>

    {{-- FILTER 2: Mode Pencocokan Filter (ANY = OR, ALL = AND) --}}
    <div class="mb-5">
        <p class="text-foreground text-xs font-semibold mb-2">Cocokan</p>
        <div class="flex gap-3">
            {{-- Match ANY: menampilkan beasiswa yang cocok dengan setidaknya satu filter --}}
            <label class="flex items-center gap-1.5 cursor-pointer text-xs text-muted-foreground library-match-label">
                <span class="filter-radio active" data-match="ANY"></span> Match ANY
            </label>
            {{-- Match ALL: menampilkan beasiswa yang cocok dengan semua filter yang dipilih --}}
            <label class="flex items-center gap-1.5 cursor-pointer text-xs text-muted-foreground library-match-label">
                <span class="filter-radio" data-match="ALL"></span> Match ALL
            </label>
        </div>
    </div>

    {{-- FILTER 3: Filter Berdasarkan Status Beasiswa --}}
    <div class="mb-5">
        <p class="text-foreground text-xs font-semibold mb-2">Status</p>
        <div class="flex flex-col gap-1.5" id="library-status-filters">
            {{-- Render opsi status secara dinamis; 'Semua Status' aktif secara default --}}
            @foreach(['Semua Status', 'Dibuka', 'Akan Datang', 'Ditutup'] as $status)
                <label class="flex items-center gap-2 cursor-pointer text-xs text-muted-foreground hover:text-foreground library-status-label">
                    {{-- Radio button kustom; 'Semua Status' aktif secara default --}}
                    <span class="filter-radio {{ $status === 'Semua Status' ? 'active' : '' }}" data-status="{{ $status }}"></span>
                    {{ $status }}
                </label>
            @endforeach
        </div>
    </div>

    {{-- FILTER 4: Filter Berdasarkan Jenjang Studi (Checkbox — bisa pilih lebih dari satu) --}}
    <div class="mb-5">
        <p class="text-foreground text-xs font-semibold mb-2">Tingkat</p>
        <div class="flex flex-col gap-1.5" id="library-level-filters">
            {{-- Render opsi jenjang studi; menggunakan checkbox agar bisa pilih lebih dari satu --}}
            @foreach(['S1 / Sarjana', 'S2 / Master', 'S3 / Doktor', 'Vokasi / D4'] as $level)
                <label class="flex items-center gap-2 cursor-pointer text-xs text-muted-foreground hover:text-foreground library-level-label">
                    {{-- Checkbox kustom yang dapat di-toggle JavaScript --}}
                    <span class="filter-checkbox" data-level="{{ $level }}"></span>
                    {{ $level }}
                </label>
            @endforeach
        </div>
    </div>

    {{-- FILTER 5: Filter Berdasarkan Negara Tujuan Beasiswa (Dropdown) --}}
    <div>
        <p class="text-foreground text-xs font-semibold mb-2">Negara</p>
        {{-- Dropdown pemilihan negara; perubahan value dipantau oleh JavaScript --}}
        <select class="library-country-select w-full bg-muted text-foreground text-xs px-3 py-2 rounded-lg border border-border focus:outline-none focus:ring-1 focus:ring-[#0052cc]/50">
            @foreach(['Semua Negara', 'Indonesia', 'United Kingdom', 'Australia', 'United States', 'Germany', 'Netherlands', 'Japan', 'Europe'] as $country)
                <option value="{{ $country }}">{{ $country }}</option>
            @endforeach
        </select>
    </div>

</div>

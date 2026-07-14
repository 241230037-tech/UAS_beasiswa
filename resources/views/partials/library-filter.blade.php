<div class="library-filter-panel bg-card border border-border rounded-xl p-4 w-full">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-[#0052cc] font-bold text-sm">Filter</h3>
        <button type="button" class="library-filter-reset text-muted-foreground hover:text-foreground text-xs transition-colors">Reset</button>
    </div>

    <div class="mb-5">
        <p class="text-foreground text-xs font-semibold mb-2">Urutkan</p>
        <div class="flex flex-wrap gap-1.5" id="library-sort-btns">
            @foreach(['Relevansi', 'Terbaru', 'A-Z', 'Terbanyak Dilihat', 'Baru'] as $sort)
                <button type="button" data-sort="{{ $sort }}"
                    class="library-sort-btn px-2.5 py-1 rounded-full text-[11px] font-semibold transition-colors {{ $sort === 'Relevansi' ? 'bg-[#0052cc] text-white' : 'bg-muted text-muted-foreground hover:bg-accent hover:text-foreground' }}">
                    {{ $sort }}
                </button>
            @endforeach
        </div>
    </div>

    <div class="mb-5">
        <p class="text-foreground text-xs font-semibold mb-2">Cocokan</p>
        <div class="flex gap-3">
            <label class="flex items-center gap-1.5 cursor-pointer text-xs text-muted-foreground library-match-label">
                <span class="filter-radio active" data-match="ANY"></span> Match ANY
            </label>
            <label class="flex items-center gap-1.5 cursor-pointer text-xs text-muted-foreground library-match-label">
                <span class="filter-radio" data-match="ALL"></span> Match ALL
            </label>
        </div>
    </div>

    <div class="mb-5">
        <p class="text-foreground text-xs font-semibold mb-2">Status</p>
        <div class="flex flex-col gap-1.5" id="library-status-filters">
            @foreach(['Semua Status', 'Dibuka', 'Akan Datang', 'Ditutup'] as $status)
                <label class="flex items-center gap-2 cursor-pointer text-xs text-muted-foreground hover:text-foreground library-status-label">
                    <span class="filter-radio {{ $status === 'Semua Status' ? 'active' : '' }}" data-status="{{ $status }}"></span>
                    {{ $status }}
                </label>
            @endforeach
        </div>
    </div>

    <div class="mb-5">
        <p class="text-foreground text-xs font-semibold mb-2">Tingkat</p>
        <div class="flex flex-col gap-1.5" id="library-level-filters">
            @foreach(['S1 / Sarjana', 'S2 / Master', 'S3 / Doktor', 'Vokasi / D4'] as $level)
                <label class="flex items-center gap-2 cursor-pointer text-xs text-muted-foreground hover:text-foreground library-level-label">
                    <span class="filter-checkbox" data-level="{{ $level }}"></span>
                    {{ $level }}
                </label>
            @endforeach
        </div>
    </div>

    <div>
        <p class="text-foreground text-xs font-semibold mb-2">Negara</p>
        <select class="library-country-select w-full bg-muted text-foreground text-xs px-3 py-2 rounded-lg border border-border focus:outline-none focus:ring-1 focus:ring-[#0052cc]/50">
            @foreach(['Semua Negara', 'Indonesia', 'United Kingdom', 'Australia', 'United States', 'Germany', 'Netherlands', 'Japan', 'Europe'] as $country)
                <option value="{{ $country }}">{{ $country }}</option>
            @endforeach
        </select>
    </div>
</div>

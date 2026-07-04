@extends('layouts.app')

@section('title', 'Admin Dashboard - PortalBeasiswa')

@section('content')
<div class="min-h-screen bg-background">
    @include('partials.navbar')

    <div id="admin-page" class="max-w-screen-xl mx-auto px-4 py-8"
        data-initial-scholarships='@json($scholarships)'
        data-initial-ads='@json($adBanners)'>

        <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4 mb-6">
            <div>
                <h1 class="text-foreground font-black text-2xl md:text-3xl tracking-tight">Admin Dashboard</h1>
                <p class="text-muted-foreground text-sm">Kelola data beasiswa dan spanduk iklan secara real-time.</p>
            </div>
            <div class="flex gap-2">
                <button type="button" id="btn-add-scholarship" class="btn-3d-red px-5 py-3 rounded-xl text-sm font-bold flex items-center gap-2">
                    <i data-lucide="plus" class="w-4 h-4"></i> Tambah Beasiswa
                </button>
                <button type="button" id="btn-add-ad" class="btn-3d-outline px-5 py-3 rounded-xl text-sm font-bold flex items-center gap-2 hidden">
                    <i data-lucide="plus" class="w-4 h-4"></i> Tambah Iklan
                </button>
            </div>
        </div>

        {{-- Statistics Row --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
            <div class="bg-card border border-border rounded-xl p-5 shadow-sm">
                <p class="text-muted-foreground text-[10px] uppercase font-bold tracking-wider mb-1">Total Beasiswa</p>
                <h3 id="stat-total-scholarships" class="text-3xl font-black text-[#e53935]">{{ count($scholarships) }}</h3>
            </div>
            <div class="bg-card border border-border rounded-xl p-5 shadow-sm">
                <p class="text-muted-foreground text-[10px] uppercase font-bold tracking-wider mb-1">Total Spanduk Iklan</p>
                <h3 id="stat-total-ads" class="text-3xl font-black text-[#e53935]">{{ count($adBanners) }}</h3>
            </div>
            <div class="bg-card border border-border rounded-xl p-5 shadow-sm">
                <p class="text-muted-foreground text-[10px] uppercase font-bold tracking-wider mb-1">Role Akun</p>
                <h3 class="text-lg font-black text-foreground flex items-center gap-2 mt-1">
                    <span class="inline-block w-2.5 h-2.5 rounded-full bg-green-500 animate-pulse"></span> Administrator
                </h3>
            </div>
        </div>

        {{-- Tab Navigation --}}
        <div class="flex gap-2 border-b border-border mb-6">
            <button type="button" id="tab-manage-scholarships" class="px-5 py-3 border-b-2 border-[#e53935] font-black text-xs text-[#e53935] tracking-wide uppercase transition-all">Kelola Beasiswa</button>
            <button type="button" id="tab-manage-ads" class="px-5 py-3 border-b-2 border-transparent font-bold text-xs text-muted-foreground hover:text-foreground tracking-wide uppercase transition-all">Kelola Iklan</button>
        </div>

        {{-- SECTION KELOLA BEASISWA --}}
        <div id="section-scholarships" class="space-y-4">
            <div class="bg-card border-2 border-border rounded-2xl shadow-[4px_4px_0_0_rgba(0,0,0,0.06)] overflow-hidden">
                <div class="p-5 border-b border-border bg-muted/10 flex items-center justify-between">
                    <h3 class="text-foreground font-black text-base">Daftar Beasiswa</h3>
                    <div class="relative w-64">
                        <i data-lucide="search" class="w-4 h-4 text-muted-foreground absolute left-3 top-1/2 -translate-y-1/2"></i>
                        <input type="text" id="admin-search-scholarships" placeholder="Cari beasiswa..." class="w-full bg-muted text-foreground placeholder-muted-foreground px-4 py-2 pl-9 rounded-xl border border-border text-xs focus:outline-none focus:ring-1 focus:ring-[#e53935]/50">
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="border-b border-border bg-muted/20 text-muted-foreground font-bold uppercase tracking-wider">
                                <th class="p-4 w-12">Logo</th>
                                <th class="p-4">Nama Beasiswa</th>
                                <th class="p-4">Penyelenggara</th>
                                <th class="p-4 w-24">Tingkat</th>
                                <th class="p-4 w-28">Lokasi</th>
                                <th class="p-4 w-24">Deadline</th>
                                <th class="p-4 w-24">Status</th>
                                <th class="p-4 w-28 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="admin-scholarships-table-body" class="divide-y divide-border">
                            {{-- Rendered by JS --}}
                        </tbody>
                    </table>
                </div>
                <div id="admin-empty-scholarships" class="text-center py-12 text-muted-foreground hidden">
                    <p class="text-sm">Tidak ada data beasiswa ditemukan.</p>
                </div>
            </div>
        </div>

        {{-- SECTION KELOLA IKLAN --}}
        <div id="section-ads" class="space-y-4 hidden">
            <div class="bg-card border-2 border-border rounded-2xl shadow-[4px_4px_0_0_rgba(0,0,0,0.06)] overflow-hidden">
                <div class="p-5 border-b border-border bg-muted/10 flex items-center justify-between">
                    <h3 class="text-foreground font-black text-base">Daftar Spanduk Iklan</h3>
                    <div class="relative w-64">
                        <i data-lucide="search" class="w-4 h-4 text-muted-foreground absolute left-3 top-1/2 -translate-y-1/2"></i>
                        <input type="text" id="admin-search-ads" placeholder="Cari iklan..." class="w-full bg-muted text-foreground placeholder-muted-foreground px-4 py-2 pl-9 rounded-xl border border-border text-xs focus:outline-none focus:ring-1 focus:ring-[#e53935]/50">
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="border-b border-border bg-muted/20 text-muted-foreground font-bold uppercase tracking-wider">
                                <th class="p-4 w-32">Visual / Warna</th>
                                <th class="p-4">Judul Iklan</th>
                                <th class="p-4">Sub-judul</th>
                                <th class="p-4">Deskripsi</th>
                                <th class="p-4 w-20">Tag</th>
                                <th class="p-4 w-32">Link Tujuan</th>
                                <th class="p-4 w-28 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="admin-ads-table-body" class="divide-y divide-border">
                            {{-- Rendered by JS --}}
                        </tbody>
                    </table>
                </div>
                <div id="admin-empty-ads" class="text-center py-12 text-muted-foreground hidden">
                    <p class="text-sm">Tidak ada data spanduk iklan ditemukan.</p>
                </div>
            </div>
        </div>

    </div>
</div>

{{-- MODAL CRUD BEASISWA --}}
<div id="crud-modal" class="hidden">
    <div class="modal-backdrop" id="crud-modal-backdrop"></div>
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="bg-card border-2 border-border rounded-2xl shadow-2xl w-full max-w-lg relative overflow-hidden z-10 modal-box max-h-[90vh] flex flex-col">
            <div class="h-1.5 bg-[#e53935] w-full shrink-0"></div>
            <button type="button" id="btn-close-crud-modal" class="absolute top-4 right-4 p-1.5 rounded-full hover:bg-muted text-muted-foreground hover:text-foreground transition-colors cursor-pointer shrink-0">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
            <div class="p-6 overflow-y-auto flex-1">
                <div class="flex items-center gap-2 mb-6 shrink-0">
                    <i data-lucide="graduation-cap" class="w-5 h-5 text-[#e53935]"></i>
                    <h2 class="text-foreground font-black text-lg" id="crud-modal-title">Tambah Beasiswa</h2>
                </div>
                <form id="crud-form" class="space-y-4">
                    <input type="hidden" id="field-id">
                    <div>
                        <label class="block text-[10px] font-bold text-muted-foreground uppercase tracking-wider mb-1.5">Nama Beasiswa *</label>
                        <input type="text" id="field-title" required class="portal-input" placeholder="Contoh: Beasiswa S1 Bakti BCA">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-muted-foreground uppercase tracking-wider mb-1.5">Penyelenggara *</label>
                        <input type="text" id="field-provider" required class="portal-input" placeholder="Contoh: PT Bank Central Asia Tbk">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-bold text-muted-foreground uppercase tracking-wider mb-1.5">Lokasi *</label>
                            <input type="text" id="field-location" required class="portal-input" placeholder="Contoh: Indonesia / UK">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-muted-foreground uppercase tracking-wider mb-1.5">Bendera Emoji *</label>
                            <input type="text" id="field-flag" required class="portal-input" placeholder="Contoh: 🇮🇩 atau 🇬🇧">
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-bold text-muted-foreground uppercase tracking-wider mb-1.5">Jenjang / Tingkat *</label>
                            <input type="text" id="field-level" required class="portal-input" placeholder="Contoh: S1 atau S2/S3">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-muted-foreground uppercase tracking-wider mb-1.5">Dana Beasiswa *</label>
                            <input type="text" id="field-amount" required class="portal-input" placeholder="Contoh: Fully Funded / UKT">
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-bold text-muted-foreground uppercase tracking-wider mb-1.5">Deadline Pendaftaran *</label>
                            <input type="text" id="field-deadline" required class="portal-input" placeholder="Contoh: 30 Jul 2026">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-muted-foreground uppercase tracking-wider mb-1.5">Status *</label>
                            <select id="field-status" required class="portal-input">
                                <option value="Dibuka">Dibuka</option>
                                <option value="Akan Datang">Akan Datang</option>
                                <option value="Ditutup">Ditutup</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-muted-foreground uppercase tracking-wider mb-1.5">Pilih Logo Beasiswa *</label>
                        <select id="field-logo-select" class="portal-input">
                            <option value="/images/logos/lpdp.png">LPDP</option>
                            <option value="/images/logos/kampusmerdeka.png">Kemendikbud (Kampus Merdeka)</option>
                            <option value="/images/logos/bankindonesia.png">Bank Indonesia</option>
                            <option value="/images/logos/baznas.png">BAZNAS</option>
                            <option value="/images/logos/djarum.png">Djarum</option>
                            <option value="/images/logos/bca.png">BCA</option>
                            <option value="/images/logos/pertamina.png">Pertamina</option>
                            <option value="/images/logos/astra.png">Astra</option>
                            <option value="/images/logos/brilian.png">BRI (BRILiaN)</option>
                            <option value="/images/logos/xl.png">XL Axiata</option>
                            <option value="/images/logos/kemenag.png">Kementerian Agama</option>
                        </select>
                        <input type="hidden" id="field-image">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-muted-foreground uppercase tracking-wider mb-1.5">Link Resmi Beasiswa *</label>
                        <input type="url" id="field-external-link" required class="portal-input" placeholder="https://example.com">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-muted-foreground uppercase tracking-wider mb-1.5">Updated Keterangan *</label>
                        <input type="text" id="field-updated-ago" required class="portal-input" placeholder="Contoh: 2 jam lalu atau 1 hari lalu">
                    </div>
                    <button type="submit" class="w-full btn-3d-red py-3 rounded-xl text-sm font-bold shadow-lg shadow-red-500/10 cursor-pointer">Simpan Data Beasiswa</button>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- MODAL CRUD IKLAN (AD BANNER) --}}
<div id="ad-modal" class="hidden">
    <div class="modal-backdrop" id="ad-modal-backdrop"></div>
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="bg-card border-2 border-border rounded-2xl shadow-2xl w-full max-w-lg relative overflow-hidden z-10 modal-box max-h-[90vh] flex flex-col">
            <div class="h-1.5 bg-[#e53935] w-full shrink-0"></div>
            <button type="button" id="btn-close-ad-modal" class="absolute top-4 right-4 p-1.5 rounded-full hover:bg-muted text-muted-foreground hover:text-foreground transition-colors cursor-pointer shrink-0">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
            <div class="p-6 overflow-y-auto flex-1">
                <div class="flex items-center gap-2 mb-6 shrink-0">
                    <i data-lucide="megaphone" class="w-5 h-5 text-[#e53935]"></i>
                    <h2 class="text-foreground font-black text-lg" id="ad-modal-title">Tambah Spanduk Iklan</h2>
                </div>
                <form id="ad-form" class="space-y-4">
                    <input type="hidden" id="ad-field-id">
                    <div>
                        <label class="block text-[10px] font-bold text-muted-foreground uppercase tracking-wider mb-1.5">Judul Iklan *</label>
                        <input type="text" id="ad-field-title" required class="portal-input" placeholder="Contoh: MENTORING BEASISWA">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-bold text-muted-foreground uppercase tracking-wider mb-1.5">Sub-judul *</label>
                            <input type="text" id="ad-field-subtitle" required class="portal-input" placeholder="Contoh: 1-ON-1! atau GRATIS!">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-muted-foreground uppercase tracking-wider mb-1.5">Teks Tag *</label>
                            <input type="text" id="ad-field-tag" required class="portal-input" placeholder="Contoh: PROMO / HOT / NEW">
                        </div>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-muted-foreground uppercase tracking-wider mb-1.5">Deskripsi Singkat *</label>
                        <input type="text" id="ad-field-description" required class="portal-input" placeholder="Contoh: Konsultasi langsung dengan mentor beasiswa">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-bold text-muted-foreground uppercase tracking-wider mb-1.5">Warna Gradien Mulai (Hex) *</label>
                            <input type="color" id="ad-field-bg-from" required class="w-full h-10 border border-border rounded-xl cursor-pointer" value="#1a237e">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-muted-foreground uppercase tracking-wider mb-1.5">Warna Gradien Selesai (Hex) *</label>
                            <input type="color" id="ad-field-bg-to" required class="w-full h-10 border border-border rounded-xl cursor-pointer" value="#283593">
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-bold text-muted-foreground uppercase tracking-wider mb-1.5">Teks Tombol CTA *</label>
                            <input type="text" id="ad-field-cta-text" required class="portal-input" placeholder="Contoh: Daftar Sekarang / Coba Gratis">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-muted-foreground uppercase tracking-wider mb-1.5">Link Spanduk Iklan *</label>
                            <input type="text" id="ad-field-link" required class="portal-input" placeholder="Contoh: /tutorial atau /library">
                        </div>
                    </div>
                    {{-- Bagian Upload Gambar Iklan --}}
                    <div>
                        <label class="block text-[10px] font-bold text-muted-foreground uppercase tracking-wider mb-1.5">Upload Gambar Iklan (Opsional)</label>
                        {{-- Input file gambar tersembunyi, dipicu oleh tombol klik di bawah --}}
                        <input type="file" id="ad-field-image-file" accept="image/*" class="hidden">
                        {{-- Input tersembunyi untuk menyimpan URL gambar yang sudah diupload --}}
                        <input type="hidden" id="ad-field-image-url">
                        <div class="flex items-center gap-3">
                            {{-- Tombol untuk memilih file --}}
                            <button type="button" id="btn-ad-upload-image"
                                class="px-4 py-2 text-xs font-bold border-2 border-dashed border-border rounded-xl text-muted-foreground hover:border-[#e53935] hover:text-[#e53935] transition-colors flex items-center gap-2">
                                <i data-lucide="image-plus" class="w-4 h-4"></i>
                                Pilih Gambar
                            </button>
                            {{-- Area preview gambar yang dipilih --}}
                            <div id="ad-image-preview-container" class="hidden">
                                <img id="ad-image-preview" src="" alt="Preview" class="h-12 w-auto rounded-lg border border-border object-cover">
                            </div>
                            {{-- Status loading saat upload sedang berjalan --}}
                            <span id="ad-upload-status" class="text-xs text-muted-foreground hidden">Mengupload...</span>
                        </div>
                        <p class="text-[10px] text-muted-foreground mt-1.5">Format: JPG, PNG, GIF, WebP. Maks: 2MB. URL gambar tersimpan otomatis ke database.</p>
                    </div>
                    <button type="submit" class="w-full btn-3d-red py-3 rounded-xl text-sm font-bold shadow-lg shadow-red-500/10 cursor-pointer">Simpan Spanduk Iklan</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

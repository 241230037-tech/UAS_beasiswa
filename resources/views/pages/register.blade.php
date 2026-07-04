@extends('layouts.app')

@section('title', 'Daftar '.$scholarship['title'].' - PortalBeasiswa')

@section('content')
<div class="min-h-screen bg-background">
    @include('partials.navbar')

    {{-- Container pendaftaran --}}
    <div id="scholarship-register-page" data-id="{{ $scholarship['id'] }}" class="max-w-4xl mx-auto px-4 py-6">
        <a href="{{ route('scholarship.detail', ['id' => $scholarship['id']]) }}" class="flex items-center gap-1.5 text-muted-foreground hover:text-[#e53935] transition-colors mb-5 text-sm font-medium group">
            <i data-lucide="arrow-left" class="w-4 h-4 group-hover:-translate-x-0.5 transition-transform"></i>
            Kembali ke Detail Beasiswa
        </a>

        {{-- Banner Mini --}}
        <div class="relative h-28 md:h-36 rounded-2xl overflow-hidden mb-4 border-2 border-border shadow-[4px_4px_0px_0px_rgba(0,0,0,0.08)] bg-white flex items-center justify-center p-4">
            <img src="{{ asset(ltrim($scholarship['image'], '/')) }}" alt="{{ $scholarship['title'] }}" class="max-w-[65%] max-h-[65%] object-contain">
            <div class="absolute top-3 right-3 flex items-center gap-2">
                <span class="text-2xl drop-shadow-md leading-none">{{ $scholarship['flag'] }}</span>
            </div>
            <div class="absolute top-3 left-3 px-2.5 py-0.5 text-[10px] font-bold text-white rounded-full bg-green-500 shadow-sm">{{ $scholarship['status'] }}</div>
        </div>

        {{-- Header Judul --}}
        <div class="card-3d p-5 rounded-2xl bg-card border-2 border-border mb-6">
            <p class="text-muted-foreground text-xs mb-1 uppercase tracking-wider font-bold">{{ $scholarship['provider'] }}</p>
            <h1 class="text-foreground font-black text-xl md:text-2xl leading-tight">Formulir Pendaftaran: <span class="text-[#e53935]">{{ $scholarship['title'] }}</span></h1>
        </div>

        {{-- Form Pendaftaran Beasiswa --}}
        <div id="registration-form-section" class="card-3d p-6 md:p-8 rounded-2xl bg-card border-2 border-border mb-8">
            <p class="text-muted-foreground text-sm mb-6">Silakan lengkapi formulir pendaftaran di bawah ini dengan data asli Anda.</p>

            <form id="scholarship-register-form" class="space-y-6" data-scholarship-id="{{ $scholarship['id'] }}" data-scholarship-title="{{ $scholarship['title'] }}">
                {{-- Data Pribadi --}}
                <div>
                    <h3 class="text-foreground font-bold text-sm mb-3 flex items-center gap-2">
                        <span class="w-1 h-4 bg-[#e53935] rounded-full"></span> Data Pribadi
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-muted-foreground mb-1.5 uppercase tracking-wider">Nama Lengkap *</label>
                            <input type="text" name="full_name" required class="portal-input" placeholder="Sesuai KTP">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-muted-foreground mb-1.5 uppercase tracking-wider">NIK *</label>
                            <input type="text" name="nik" required maxlength="16" class="portal-input" placeholder="16 digit NIK">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-muted-foreground mb-1.5 uppercase tracking-wider">Email *</label>
                            <input type="email" name="email" required class="portal-input" placeholder="nama@email.com">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-muted-foreground mb-1.5 uppercase tracking-wider">No. Telepon / WhatsApp *</label>
                            <input type="tel" name="phone" required class="portal-input" placeholder="08xxxxxxxxxx">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-muted-foreground mb-1.5 uppercase tracking-wider">Tanggal Lahir *</label>
                            <input type="date" name="birth_date" required class="portal-input">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-muted-foreground mb-1.5 uppercase tracking-wider">Jenis Kelamin *</label>
                            <select name="gender" required class="portal-input">
                                <option value="">Pilih</option>
                                <option value="Laki-laki">Laki-laki</option>
                                <option value="Perempuan">Perempuan</option>
                            </select>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-xs font-semibold text-muted-foreground mb-1.5 uppercase tracking-wider">Alamat Lengkap *</label>
                            <textarea name="address" required rows="2" class="portal-input resize-none" placeholder="Alamat domisili saat ini"></textarea>
                        </div>
                    </div>
                </div>

                {{-- Data Akademik --}}
                <div>
                    <h3 class="text-foreground font-bold text-sm mb-3 flex items-center gap-2">
                        <span class="w-1 h-4 bg-[#e53935] rounded-full"></span> Data Akademik
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-muted-foreground mb-1.5 uppercase tracking-wider">Jenjang yang Diajukan *</label>
                            <select name="applied_level" required class="portal-input">
                                <option value="">Pilih jenjang</option>
                                <option value="S1">S1 / Sarjana</option>
                                <option value="S2">S2 / Magister</option>
                                <option value="S3">S3 / Doktor</option>
                                <option value="D4">D4 / Vokasi</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-muted-foreground mb-1.5 uppercase tracking-wider">Universitas / Institusi Asal *</label>
                            <input type="text" name="university" required class="portal-input" placeholder="Nama universitas">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-muted-foreground mb-1.5 uppercase tracking-wider">Program Studi *</label>
                            <input type="text" name="major" required class="portal-input" placeholder="Jurusan / prodi">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-muted-foreground mb-1.5 uppercase tracking-wider">IPK / Nilai Terakhir *</label>
                            <input type="text" name="gpa" required class="portal-input" placeholder="Contoh: 3.75 / 4.00">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-muted-foreground mb-1.5 uppercase tracking-wider">Skor Bahasa Inggris</label>
                            <input type="text" name="english_score" class="portal-input" placeholder="IELTS / TOEFL (opsional)">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-muted-foreground mb-1.5 uppercase tracking-wider">Universitas Tujuan</label>
                            <input type="text" name="target_university" class="portal-input" placeholder="Jika sudah ada LoA">
                        </div>
                    </div>
                </div>

                {{-- Dokumen & Esai --}}
                <div>
                    <h3 class="text-foreground font-bold text-sm mb-3 flex items-center gap-2">
                        <span class="w-1 h-4 bg-[#e53935] rounded-full"></span> Dokumen & Esai
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        {{-- KTP --}}
                        <div class="flex flex-col">
                            <label class="block text-xs font-semibold text-muted-foreground mb-1.5 uppercase tracking-wider">Upload KTP *</label>
                            <input type="file" name="ktp" data-preview-id="preview-ktp" accept=".pdf,.jpg,.jpeg,.png" required class="portal-input file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:bg-[#e53935] file:text-white file:text-xs">
                            <div id="preview-ktp" class="mt-2.5 hidden p-3 border border-border rounded-xl bg-muted/40 flex items-center gap-3"></div>
                        </div>

                        {{-- Ijazah --}}
                        <div class="flex flex-col">
                            <label class="block text-xs font-semibold text-muted-foreground mb-1.5 uppercase tracking-wider">Upload Ijazah Terakhir *</label>
                            <input type="file" name="ijazah" data-preview-id="preview-ijazah" accept=".pdf,.jpg,.jpeg,.png" required class="portal-input file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:bg-[#e53935] file:text-white file:text-xs">
                            <div id="preview-ijazah" class="mt-2.5 hidden p-3 border border-border rounded-xl bg-muted/40 flex items-center gap-3"></div>
                        </div>

                        {{-- Transkrip --}}
                        <div class="flex flex-col">
                            <label class="block text-xs font-semibold text-muted-foreground mb-1.5 uppercase tracking-wider">Upload Transkrip Nilai *</label>
                            <input type="file" name="transcript" data-preview-id="preview-transcript" accept=".pdf,.jpg,.jpeg,.png" required class="portal-input file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:bg-[#e53935] file:text-white file:text-xs">
                            <div id="preview-transcript" class="mt-2.5 hidden p-3 border border-border rounded-xl bg-muted/40 flex items-center gap-3"></div>
                        </div>

                        {{-- CV --}}
                        <div class="flex flex-col">
                            <label class="block text-xs font-semibold text-muted-foreground mb-1.5 uppercase tracking-wider">Upload CV / Resume</label>
                            <input type="file" name="cv" data-preview-id="preview-cv" accept=".pdf,.doc,.docx" class="portal-input file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:bg-[#e53935] file:text-white file:text-xs">
                            <div id="preview-cv" class="mt-2.5 hidden p-3 border border-border rounded-xl bg-muted/40 flex items-center gap-3"></div>
                        </div>

                        {{-- Motivation --}}
                        <div class="md:col-span-2">
                            <label class="block text-xs font-semibold text-muted-foreground mb-1.5 uppercase tracking-wider">Motivation Letter / Esai *</label>
                            <textarea name="motivation" required rows="5" class="portal-input resize-none" placeholder="Ceritakan alasan Anda layak mendapatkan beasiswa ini, rencana studi, dan kontribusi pasca studi..."></textarea>
                        </div>
                    </div>
                </div>

                {{-- Persetujuan --}}
                <div class="bg-muted/50 rounded-xl p-4 border border-border">
                    <label class="flex items-start gap-3 cursor-pointer text-sm text-muted-foreground">
                        <input type="checkbox" name="agreement" required class="mt-1 rounded accent-[#e53935]">
                        <span>Saya menyatakan bahwa seluruh data dan dokumen yang saya unggah adalah benar dan dapat dipertanggungjawabkan. Saya setuju dengan syarat & ketentuan program beasiswa ini.</span>
                    </label>
                </div>

                <button type="submit" class="w-full btn-3d-red py-4 rounded-xl font-bold text-sm flex items-center justify-center gap-2">
                    <i data-lucide="send" class="w-4 h-4"></i>
                    Kirim Pendaftaran Beasiswa
                </button>
            </form>
            <div class="text-center mt-6 text-xs text-muted-foreground border-t border-border pt-4">
                Mengalami kendala teknis atau error saat mendaftar? 
                <button type="button" id="btn-register-contact" class="text-[#e53935] hover:underline font-bold focus:outline-none cursor-pointer">Hubungi Kami / Lapor Error</button>
            </div>
        </div>
    </div>
</div>
@endsection

<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * Class AppServiceProvider
 * Provider utama untuk mendaftarkan dan melakukan bootstrap berbagai service/layanan 
 * yang digunakan di dalam aplikasi Laravel.
 */
class AppServiceProvider extends ServiceProvider
{
    /**
     * Mendaftarkan service provider atau konfigurasi khusus (dijalankan sebelum boot).
     */
    public function register(): void
    {
        //
    }

    /**
     * Melakukan bootstrap untuk layanan aplikasi (diambil saat Laravel pertama kali jalan).
     * Di sini kita menambahkan validasi agar server development frontend (npm run dev)
     * harus berjalan di environment lokal sebelum aplikasi PHP dapat diakses.
     */
    public function boot(): void
    {
        // Pastikan hanya memeriksa di environment 'local' dan bukan saat menjalankan command artisan (CLI)
        if (app()->environment('local') && !app()->runningInConsole()) {
            // Ketika 'npm run dev' dijalankan, Vite otomatis membuat file 'hot' di folder public.
            // Jika file ini tidak ada, berarti server development frontend belum aktif.
            if (!file_exists(public_path('hot'))) {
                abort(503, 'PENGEMBANGAN ERROR: Silakan jalankan perintah "npm run dev" terlebih dahulu di terminal Anda untuk mengakses project ini.');
            }
        }
    }
}

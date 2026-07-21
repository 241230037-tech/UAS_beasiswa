<?php

/**
 * File: database/seeders/DatabaseSeeder.php
 *
 * Seeder utama aplikasi Beasiswapedia.
 * Bertanggung jawab untuk mengisi database dengan data awal (data benih)
 * yang diperlukan agar aplikasi dapat langsung digunakan setelah instalasi.
 *
 * Data yang diisi:
 *   1. Beasiswa      : Data beasiswa dari ScholarshipData::all()
 *   2. Iklan         : Data spanduk iklan dari ScholarshipData::adBanners()
 *   3. Slide Carousel: Data slide dari ScholarshipData::carouselItems()
 *   4. Akun Admin    : Dua akun administrator tetap (admin1 & admin2)
 *
 * Cara menjalankan seeder:
 *   php artisan db:seed
 *   php artisan migrate:fresh --seed
 */

namespace Database\Seeders;

use App\Models\User;          // Model akun pengguna
use App\Models\Scholarship;   // Model data beasiswa
use App\Models\AdBanner;      // Model spanduk iklan
use App\Data\ScholarshipData; // Sumber data statis beasiswa, iklan, dan carousel
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Mengisi database dengan data awal aplikasi.
     */
    public function run(): void
    {
        // ============================================================
        // 1. Isi tabel scholarships dengan data beasiswa statis
        // ============================================================
        $scholarships = ScholarshipData::all();
        foreach ($scholarships as $s) {
            Scholarship::create($s); // Buat satu record beasiswa ke database
        }

        // ============================================================
        // 2. Isi tabel ad_banners dengan data iklan statis
        // ============================================================
        $adBanners = ScholarshipData::adBanners();
        foreach ($adBanners as $ad) {
            AdBanner::create($ad); // Buat satu record iklan ke database
        }

        // ============================================================
        // 3. Isi tabel carousel_items dengan data slide carousel statis
        // ============================================================
        $carouselItems = ScholarshipData::carouselItems();
        foreach ($carouselItems as $item) {
            \App\Models\CarouselItem::create($item); // Buat satu record slide carousel ke database
        }

        // ============================================================
        // 4. Buat akun administrator tetap untuk login awal
        // ============================================================

        // Akun Admin 1 — Untuk login: email = admin1@email.com, password = admin1
        User::create([
            'name'     => 'Administrator 1',
            'email'    => 'admin1@email.com',
            'password' => bcrypt('admin1'), // Password di-hash sebelum disimpan ke database
            'role'     => 'admin',
        ]);

        // Akun Admin 2 — Untuk login: email = admin2@email.com, password = admin2
        User::create([
            'name'     => 'Administrator 2',
            'email'    => 'admin2@email.com',
            'password' => bcrypt('admin2'), // Password di-hash sebelum disimpan ke database
            'role'     => 'admin',
        ]);
    }
}

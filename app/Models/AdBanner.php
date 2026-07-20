<?php

/**
 * File: app/Models/AdBanner.php
 *
 * Model Eloquent untuk tabel 'ad_banners' di database.
 * Merepresentasikan satu entri Spanduk Iklan yang tampil di halaman home atau landing.
 *
 * Kolom tabel ad_banners:
 *   - id          : Auto-increment primary key
 *   - title       : Judul utama iklan
 *   - subtitle    : Sub-judul iklan
 *   - description : Deskripsi singkat iklan
 *   - cta_text    : Teks tombol Call-To-Action (misal: "Daftar Sekarang")
 *   - bg_from     : Warna awal gradient background iklan
 *   - bg_to       : Warna akhir gradient background iklan
 *   - tag         : Label/kategori iklan (misal: "PROMO", "BEASISWA")
 *   - link        : URL tujuan saat iklan diklik
 *   - image_url   : URL gambar/media latar iklan (nullable)
 *   - position    : Posisi tampil iklan — 'top' (atas) atau 'bottom' (bawah)
 *   - created_at  : Timestamp otomatis saat data dibuat
 *   - updated_at  : Timestamp otomatis saat data terakhir diperbarui
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model; // Base class Eloquent ORM dari Laravel

class AdBanner extends Model
{
    /**
     * Mengizinkan semua kolom tabel untuk diisi secara massal (mass-assignment).
     * Dengan $guarded = [], semua field dapat digunakan pada AdBanner::create() dan update().
     *
     * Catatan: Gunakan pendekatan ini dengan hati-hati; pastikan validasi dilakukan di Controller.
     *
     * @var array<string> Array kolom yang dikecualikan dari mass-assignment (kosong = semua diizinkan)
     */
    protected $guarded = [];
}

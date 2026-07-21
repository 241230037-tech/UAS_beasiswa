<?php

/**
 * File: app/Models/CarouselItem.php
 *
 * Model Eloquent untuk tabel 'carousel_items' di database.
 * Merepresentasikan satu slide dalam Carousel/Slider dinamis yang ditampilkan
 * di halaman landing dan home sebagai media promosi atau highlight beasiswa.
 *
 * Tipe slide yang didukung:
 *   - 'scholarship' : Slide yang menampilkan data beasiswa dari tabel scholarships
 *   - 'video'       : Slide yang menampilkan video lokal atau URL video eksternal
 *
 * Kolom tabel carousel_items:
 *   - id             : Auto-increment primary key
 *   - type           : Jenis slide ('scholarship' atau 'video')
 *   - scholarship_id : Foreign key ke tabel scholarships (nullable, hanya untuk type 'scholarship')
 *   - title          : Judul slide (nullable — bisa diambil dari beasiswa jika type 'scholarship')
 *   - subtitle       : Sub-judul slide (nullable)
 *   - description    : Deskripsi singkat slide (nullable)
 *   - image_url      : URL gambar latar slide (nullable)
 *   - video_url      : URL video slide (nullable, diisi dari chunked upload atau URL eksternal)
 *   - link           : URL tujuan saat slide diklik (nullable)
 *   - order_index    : Urutan tampil slide (0 = pertama, nilai lebih kecil tampil lebih awal)
 *   - created_at     : Timestamp otomatis saat data dibuat
 *   - updated_at     : Timestamp otomatis saat data terakhir diperbarui
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;            // Base class Eloquent ORM dari Laravel
use Illuminate\Database\Eloquent\Relations\BelongsTo; // Tipe relasi Many-to-One di Eloquent

class CarouselItem extends Model
{
    /**
     * Kolom yang diizinkan untuk diisi secara massal (mass-assignment).
     * Mendefinisikan semua kolom yang dapat diisi saat CarouselItem::create() atau update().
     *
     * @var array<string> Nama-nama kolom yang dapat diisi secara massal
     */
    protected $fillable = [
        'type',           // Jenis slide: 'scholarship' atau 'video'
        'scholarship_id', // ID beasiswa yang ditampilkan (nullable)
        'title',          // Judul slide (nullable)
        'subtitle',       // Sub-judul slide (nullable)
        'description',    // Deskripsi slide (nullable)
        'image_url',      // URL gambar latar slide (nullable)
        'video_url',      // URL file video slide (nullable)
        'link',           // URL tujuan klik slide (nullable)
        'order_index',    // Urutan tampil (integer, nullable)
    ];

    /**
     * Relasi many-to-one ke model Scholarship.
     *
     * Hanya relevan untuk slide bertipe 'scholarship'.
     * Memuat data beasiswa terkait sehingga slide dapat menampilkan info beasiswa secara dinamis.
     * Contoh penggunaan: $carouselItem->scholarship->title
     *
     * @return BelongsTo Relasi ke model Scholarship menggunakan foreign key 'scholarship_id'
     */
    public function scholarship(): BelongsTo
    {
        return $this->belongsTo(Scholarship::class); // Relasi menggunakan 'scholarship_id' secara default
    }
}

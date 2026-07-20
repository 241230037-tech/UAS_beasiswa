<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Menjalankan migrasi untuk membuat tabel spanduk iklan (ad_banners).
     */
    public function up(): void
    {
        Schema::create('ad_banners', function (Blueprint $table) {
            $table->id();
            $table->string('title');        // Judul utama iklan
            $table->string('subtitle');     // Sub-judul / tagline pendek
            $table->string('description');  // Deskripsi singkat iklan
            $table->string('cta_text');     // Teks tombol aksi (misal: "Daftar Sekarang")
            $table->string('bg_from');      // Warna gradien awal (hex, misal: #1a237e)
            $table->string('bg_to');        // Warna gradien akhir (hex, misal: #283593)
            $table->string('tag');          // Label tag singkat (misal: PROMO, HOT)
            $table->string('link');         // Tautan tujuan ketika iklan diklik
            $table->string('image_url')->nullable(); // URL/path gambar iklan (opsional), disimpan ke storage
            $table->string('position')->default('bottom'); // Posisi penempatan iklan ('top' = atas, 'bottom' = bawah)
            $table->timestamps();           // Kolom created_at dan updated_at otomatis
        });
    }

    /**
     * Membatalkan migrasi (menghapus tabel ad_banners jika rollback).
     */
    public function down(): void
    {
        Schema::dropIfExists('ad_banners');
    }
};

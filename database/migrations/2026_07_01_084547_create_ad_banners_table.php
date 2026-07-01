<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ad_banners', function (Blueprint $table) {
            $table->id();
            $table->string('title');        // Judul utama iklan
            $table->string('subtitle');     // Sub-judul / tagline pendek
            $table->string('description');  // Deskripsi singkat iklan
            $table->string('cta_text');     // Teks tombol Call-to-Action
            $table->string('bg_from');      // Warna gradien awal (hex, misal: #1a237e)
            $table->string('bg_to');        // Warna gradien akhir (hex, misal: #283593)
            $table->string('tag');          // Label tag singkat (misal: PROMO, HOT)
            $table->string('link');         // Tautan tujuan ketika iklan diklik
            $table->string('image_url')->nullable(); // URL/path gambar iklan (opsional), disimpan ke storage
            $table->timestamps();           // created_at & updated_at otomatis
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ad_banners');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Menjalankan migrasi untuk membuat tabel slide carousel (carousel_items).
     */
    public function up(): void
    {
        Schema::create('carousel_items', function (Blueprint $table) {
            $table->id();
            $table->string('type');  // Jenis slide: 'scholarship' (beasiswa) atau 'video'
            $table->foreignId('scholarship_id')->nullable()->constrained('scholarships')->onDelete('cascade');
            // scholarship_id: ID beasiswa terkait (opsional, hanya untuk tipe 'scholarship')
            // onDelete('cascade'): jika beasiswa dihapus, slide carousel ikut terhapus otomatis
            $table->string('title')->nullable();          // Judul slide (opsional)
            $table->string('subtitle')->nullable();       // Sub-judul slide (opsional)
            $table->text('description')->nullable();      // Deskripsi slide (opsional)
            $table->string('image_url')->nullable();      // URL gambar latar slide (opsional)
            $table->text('video_url')->nullable();        // URL video slide — bisa berupa path lokal atau URL eksternal
            $table->string('link')->nullable();           // URL tujuan saat slide diklik (opsional)
            $table->integer('order_index')->default(0);  // Urutan tampil slide (0 = pertama)
            $table->timestamps();                         // Kolom created_at dan updated_at otomatis
        });
    }

    /**
     * Membatalkan migrasi (menghapus tabel carousel_items jika rollback).
     */
    public function down(): void
    {
        Schema::dropIfExists('carousel_items');
    }
};

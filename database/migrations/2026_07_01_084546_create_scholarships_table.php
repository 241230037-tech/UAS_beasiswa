<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Menjalankan migrasi untuk membuat tabel data beasiswa.
     */
    public function up(): void
    {
        Schema::create('scholarships', function (Blueprint $table) {
            $table->string('id')->primary();     // Primary key berupa string (diatur manual oleh admin)
            $table->string('title');             // Judul/nama lengkap beasiswa
            $table->string('provider');          // Penyelenggara beasiswa (misal: LPDP, Chevening)
            $table->string('location');          // Negara atau kota tujuan beasiswa
            $table->string('flag')->nullable();  // Emoji bendera negara (opsional)
            $table->string('level');             // Jenjang studi (S1, S2, S3, atau kombinasi)
            $table->string('amount');            // Nilai tunjangan (misal: "Fully Funded")
            $table->string('deadline');          // Batas waktu pendaftaran
            $table->string('status');            // Status beasiswa (Dibuka, Tutup, Segera Buka)
            $table->string('image');             // URL gambar/logo resmi beasiswa
            $table->string('external_link');     // URL website resmi penyelenggara
            $table->string('updated_ago');       // Keterangan kapan data terakhir diperbarui
            $table->timestamps();                // Kolom created_at dan updated_at otomatis
        });
    }

    /**
     * Membatalkan migrasi (menghapus tabel scholarships jika rollback).
     */
    public function down(): void
    {
        Schema::dropIfExists('scholarships');
    }
};

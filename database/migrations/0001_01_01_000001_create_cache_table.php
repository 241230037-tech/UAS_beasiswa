<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Menjalankan migrasi untuk membuat tabel cache dan cache_locks.
     * Tabel ini digunakan Laravel untuk menyimpan data cache sementara di database.
     */
    public function up(): void
    {
        // Tabel cache: menyimpan data yang di-cache sementara oleh aplikasi
        Schema::create('cache', function (Blueprint $table) {
            $table->string('key')->primary(); // Kunci unik data cache
            $table->mediumText('value');      // Nilai data cache yang disimpan
            $table->bigInteger('expiration')->index(); // Waktu kedaluwarsa cache (Unix timestamp)
        });

        // Tabel cache_locks: mencegah race condition saat beberapa proses mengakses cache bersamaan
        Schema::create('cache_locks', function (Blueprint $table) {
            $table->string('key')->primary(); // Kunci unik lock
            $table->string('owner');          // Pemilik lock (identifier proses yang mengunci)
            $table->bigInteger('expiration')->index(); // Waktu kedaluwarsa lock
        });
    }

    /**
     * Membatalkan migrasi (menghapus tabel cache dan cache_locks jika rollback).
     */
    public function down(): void
    {
        Schema::dropIfExists('cache');
        Schema::dropIfExists('cache_locks');
    }
};

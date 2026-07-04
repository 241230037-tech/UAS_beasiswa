<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Menjalankan migrasi untuk membuat tabel-tabel database.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id(); // Primary Key auto-increment
            $table->string('name'); // Nama lengkap
            $table->string('email')->unique(); // Email unik untuk login
            $table->timestamp('email_verified_at')->nullable(); // Waktu verifikasi email
            $table->string('password'); // Password terenkripsi
            $table->enum('role', ['admin', 'mahasiswa'])
      ->default('mahasiswa');

$table->boolean('status')
      ->default(true);
            $table->rememberToken(); // Token remember-me untuk login jangka panjang
            $table->timestamps(); // Kolom created_at dan updated_at otomatis
        });

        // Membuat tabel 'password_reset_tokens' untuk token pengaturan ulang password
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        // Membuat tabel 'sessions' untuk mencatat session aktif pengguna (khusus jika menggunakan database session driver)
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Membatalkan migrasi (menghapus tabel-tabel jika ada proses rollback).
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};

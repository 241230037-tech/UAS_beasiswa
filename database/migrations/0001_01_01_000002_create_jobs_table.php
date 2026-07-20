<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Menjalankan migrasi untuk membuat tabel antrian pekerjaan (jobs, job_batches, failed_jobs).
     * Tabel ini digunakan Laravel Queue untuk mengelola pekerjaan latar belakang (background jobs).
     */
    public function up(): void
    {
        // Tabel jobs: menyimpan antrian pekerjaan yang akan diproses
        Schema::create('jobs', function (Blueprint $table) {
            $table->id();
            $table->string('queue')->index();         // Nama antrian (queue) tempat pekerjaan dimasukkan
            $table->longText('payload');              // Data pekerjaan yang terserialisasi
            $table->unsignedSmallInteger('attempts'); // Jumlah percobaan eksekusi yang sudah dilakukan
            $table->unsignedInteger('reserved_at')->nullable(); // Waktu pekerjaan mulai diproses (Unix timestamp)
            $table->unsignedInteger('available_at'); // Waktu pekerjaan siap diproses (Unix timestamp)
            $table->unsignedInteger('created_at');   // Waktu pekerjaan dibuat (Unix timestamp)
        });

        // Tabel job_batches: mengelompokkan beberapa pekerjaan dalam satu batch
        Schema::create('job_batches', function (Blueprint $table) {
            $table->string('id')->primary();          // ID unik batch
            $table->string('name');                   // Nama batch pekerjaan
            $table->integer('total_jobs');            // Total jumlah pekerjaan dalam batch
            $table->integer('pending_jobs');          // Jumlah pekerjaan yang belum diproses
            $table->integer('failed_jobs');           // Jumlah pekerjaan yang gagal
            $table->longText('failed_job_ids');       // Daftar ID pekerjaan yang gagal
            $table->mediumText('options')->nullable(); // Opsi tambahan batch (serial JSON)
            $table->integer('cancelled_at')->nullable(); // Waktu batch dibatalkan (null jika belum)
            $table->integer('created_at');            // Waktu batch dibuat
            $table->integer('finished_at')->nullable(); // Waktu batch selesai (null jika belum)
        });

        // Tabel failed_jobs: menyimpan pekerjaan yang gagal diproses setelah batas percobaan
        Schema::create('failed_jobs', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique();         // ID unik pekerjaan yang gagal
            $table->string('connection');             // Nama koneksi antrian yang digunakan
            $table->string('queue');                  // Nama antrian asal pekerjaan
            $table->longText('payload');              // Data pekerjaan yang terserialisasi
            $table->longText('exception');            // Pesan kesalahan/exception yang terjadi
            $table->timestamp('failed_at')->useCurrent(); // Waktu pekerjaan dinyatakan gagal

            $table->index(['connection', 'queue', 'failed_at']); // Indeks gabungan untuk pencarian cepat
        });
    }

    /**
     * Membatalkan migrasi (menghapus tabel-tabel antrian jika rollback).
     */
    public function down(): void
    {
        Schema::dropIfExists('jobs');
        Schema::dropIfExists('job_batches');
        Schema::dropIfExists('failed_jobs');
    }
};

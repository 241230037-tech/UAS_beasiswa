<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Menjalankan migrasi untuk membuat tabel lamaran beasiswa (scholarship_applications).
     */
    public function up(): void
    {
        Schema::create('scholarship_applications', function (Blueprint $table) {
            $table->id();
            $table->string('scholarship_id');              // ID beasiswa yang dilamar
            $table->string('scholarship_title');           // Nama beasiswa yang dilamar
            $table->string('full_name');                   // Nama lengkap pendaftar sesuai KTP
            $table->string('nik');                         // Nomor Induk Kependudukan (16 digit)
            $table->string('email');                       // Alamat email aktif pendaftar
            $table->string('phone');                       // Nomor telepon pendaftar
            $table->string('birth_date');                  // Tanggal lahir pendaftar
            $table->string('gender');                      // Jenis kelamin pendaftar
            $table->text('address');                       // Alamat domisili lengkap pendaftar
            $table->string('applied_level');               // Jenjang studi yang dilamar (S1/S2/S3)
            $table->string('university');                  // Nama universitas asal pendaftar
            $table->string('major');                       // Jurusan/program studi pendaftar
            $table->string('gpa');                         // Indeks Prestasi Kumulatif (IPK)
            $table->string('english_score')->nullable();   // Skor bahasa Inggris (TOEFL/IELTS) — opsional
            $table->string('target_university')->nullable(); // Universitas tujuan — opsional
            $table->string('ktp_path');                    // Path file KTP di storage
            $table->string('ijazah_path');                 // Path file Ijazah di storage
            $table->string('transcript_path');             // Path file Transkrip di storage
            $table->string('cv_path')->nullable();         // Path file CV di storage (opsional)
            $table->text('motivation');                    // Isi motivation letter (minimal 50 karakter)
            $table->string('status')->default('pending');  // Status lamaran (pending/dikirim/ditolak)
            $table->timestamps();                          // Kolom created_at dan updated_at otomatis
        });
    }

    /**
     * Membatalkan migrasi (menghapus tabel scholarship_applications jika rollback).
     */
    public function down(): void
    {
        Schema::dropIfExists('scholarship_applications');
    }
};

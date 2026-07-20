<?php

/**
 * File: app/Models/ScholarshipApplication.php
 *
 * Model Eloquent untuk tabel 'scholarship_applications' di database.
 * Merepresentasikan satu record lamaran pendaftaran beasiswa yang disubmit oleh calon pendaftar
 * melalui formulir pendaftaran di halaman /scholarship/{id}/register.
 *
 * Kolom tabel scholarship_applications:
 *   - id                : Auto-increment primary key
 *   - scholarship_id    : ID beasiswa yang dilamar (foreign key ke tabel scholarships)
 *   - scholarship_title : Nama beasiswa yang dilamar (disimpan langsung untuk kejelasan)
 *   - full_name         : Nama lengkap pendaftar sesuai KTP
 *   - nik               : Nomor Induk Kependudukan (16 digit)
 *   - email             : Alamat email aktif pendaftar
 *   - phone             : Nomor telepon pendaftar
 *   - birth_date        : Tanggal lahir pendaftar
 *   - gender            : Jenis kelamin pendaftar (Laki-laki / Perempuan)
 *   - address           : Alamat domisili lengkap pendaftar
 *   - applied_level     : Jenjang studi yang dilamar (S1, S2, S3)
 *   - university        : Nama universitas asal pendaftar
 *   - major             : Jurusan/program studi pendaftar
 *   - gpa               : Indeks Prestasi Kumulatif (IPK) pendaftar
 *   - english_score     : Skor bahasa Inggris (TOEFL/IELTS) — nullable
 *   - target_university : Universitas tujuan yang diminati — nullable
 *   - ktp_path          : Path relatif file KTP di storage/app/public/uploads/
 *   - ijazah_path       : Path relatif file Ijazah di storage/app/public/uploads/
 *   - transcript_path   : Path relatif file Transkrip di storage/app/public/uploads/
 *   - cv_path           : Path relatif file CV di storage/app/public/uploads/ (nullable)
 *   - motivation        : Isi motivation letter (minimal 50 karakter)
 *   - created_at        : Timestamp otomatis saat lamaran dikirim
 *   - updated_at        : Timestamp otomatis saat data terakhir diperbarui
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model; // Base class Eloquent ORM dari Laravel

class ScholarshipApplication extends Model
{
    /**
     * Mengizinkan semua kolom tabel untuk diisi secara massal (mass-assignment).
     * Dengan $guarded = [], semua field dapat digunakan pada ScholarshipApplication::create().
     *
     * Catatan: Validasi ketat wajib dilakukan di PageController::submitRegistration()
     * sebelum memanggil create() untuk memastikan keamanan data.
     *
     * @var array<string> Array kolom yang dikecualikan dari mass-assignment (kosong = semua diizinkan)
     */
    protected $guarded = [];
}

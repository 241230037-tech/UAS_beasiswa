<?php

/**
 * File: app/Models/Scholarship.php
 *
 * Model Eloquent untuk tabel 'scholarships' di database.
 * Merepresentasikan satu entri data Beasiswa yang dapat dikelola admin
 * dan ditampilkan di katalog, halaman detail, maupun slide carousel.
 *
 * Catatan penting tentang Primary Key:
 *   Model ini menggunakan string sebagai primary key (bukan auto-increment integer).
 *   ID beasiswa didefinisikan secara manual oleh admin saat menambahkan data baru.
 *
 * Kolom tabel scholarships:
 *   - id            : String primary key (diatur manual, misal: "1", "lpdp-2026")
 *   - title         : Judul/nama lengkap beasiswa
 *   - provider      : Penyelenggara beasiswa (misal: LPDP, Chevening, Fulbright)
 *   - location      : Negara atau kota tujuan beasiswa
 *   - flag          : Emoji bendera negara tujuan (nullable)
 *   - level         : Jenjang studi yang dicakup (S1, S2, S3, atau kombinasi)
 *   - amount        : Nilai tunjangan beasiswa (misal: "Fully Funded", "IDR 5 juta/bulan")
 *   - deadline      : Tanggal batas waktu pendaftaran
 *   - status        : Status beasiswa saat ini ('Dibuka', 'Tutup', 'Segera Buka')
 *   - image         : URL gambar/logo resmi beasiswa
 *   - external_link : URL website resmi penyelenggara beasiswa
 *   - updated_ago   : Keterangan kapan data terakhir diperbarui (misal: "2 jam lalu")
 *   - created_at    : Timestamp otomatis saat data dibuat
 *   - updated_at    : Timestamp otomatis saat data terakhir diperbarui
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model; // Base class Eloquent ORM dari Laravel

class Scholarship extends Model
{
    /**
     * Mengatur tipe data Primary Key agar bertipe string.
     * Diperlukan karena ID beasiswa menggunakan string (bukan integer auto-increment).
     *
     * @var string Tipe data primary key
     */
    protected $keyType = 'string';

    /**
     * Menonaktifkan auto-incrementing pada primary key.
     * Karena ID didefinisikan secara manual oleh admin, bukan oleh database.
     *
     * @var bool False = primary key tidak auto-increment
     */
    public $incrementing = false;

    /**
     * Mengizinkan semua kolom tabel untuk diisi secara massal (mass-assignment).
     * Dengan $guarded = [], semua field dapat digunakan pada Scholarship::create() dan update().
     *
     * Catatan: Validasi tetap wajib dilakukan di layer Controller sebelum memanggil create/update.
     *
     * @var array<string> Array kolom yang dikecualikan dari mass-assignment (kosong = semua diizinkan)
     */
    protected $guarded = [];
}

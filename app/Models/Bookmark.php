<?php

/**
 * File: app/Models/Bookmark.php
 *
 * Model Eloquent untuk tabel 'bookmarks' di database.
 * Merepresentasikan relasi many-to-many antara Pengguna (User) dan Beasiswa (Scholarship)
 * yang telah disimpan (di-bookmark) oleh pengguna tersebut.
 *
 * Kolom tabel bookmarks:
 *   - id             : Auto-increment primary key
 *   - user_id        : Foreign key ke tabel users (pengguna yang melakukan bookmark)
 *   - scholarship_id : Foreign key ke tabel scholarships (beasiswa yang di-bookmark)
 *   - created_at     : Timestamp otomatis saat data dibuat
 *   - updated_at     : Timestamp otomatis saat data terakhir diperbarui
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;            // Base class Eloquent ORM dari Laravel
use Illuminate\Database\Eloquent\Relations\BelongsTo; // Tipe relasi Many-to-One di Eloquent

class Bookmark extends Model
{
    /**
     * Kolom yang diizinkan untuk diisi secara massal (mass-assignment).
     * Hanya user_id dan scholarship_id yang boleh diisi saat Bookmark::create().
     *
     * @var array<string> Nama-nama kolom yang dapat diisi secara massal
     */
    protected $fillable = [
        'user_id',         // ID pengguna pemilik bookmark
        'scholarship_id',  // ID beasiswa yang di-bookmark
    ];

    /**
     * Relasi many-to-one ke model User (Pemilik Bookmark).
     *
     * Satu bookmark dimiliki oleh satu pengguna.
     * Contoh penggunaan: $bookmark->user->name
     *
     * @return BelongsTo Relasi ke model User
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class); // Relasi menggunakan foreign key 'user_id' secara default
    }

    /**
     * Relasi many-to-one ke model Scholarship (Beasiswa yang disimpan).
     *
     * Satu bookmark merujuk ke satu beasiswa.
     * Contoh penggunaan: $bookmark->scholarship->title
     *
     * @return BelongsTo Relasi ke model Scholarship menggunakan 'scholarship_id' dan 'id'
     */
    public function scholarship(): BelongsTo
    {
        // Eksplisit mendefinisikan foreign key dan owner key karena Scholarship menggunakan string ID
        return $this->belongsTo(Scholarship::class, 'scholarship_id', 'id');
    }
}

<?php

/**
 * File: app/Models/User.php
 *
 * Model Eloquent untuk tabel 'users' di database.
 * Merepresentasikan akun pengguna aplikasi Beasiswapedia, baik pengguna biasa maupun admin.
 * Model ini mewarisi kelas Authenticatable milik Laravel yang menyediakan
 * dukungan otentikasi lengkap (login, logout, session, dll.).
 *
 * Kolom tabel users:
 *   - id                : Auto-increment primary key
 *   - name              : Nama lengkap pengguna
 *   - email             : Alamat email unik sebagai identitas login
 *   - email_verified_at : Timestamp verifikasi email (nullable — fitur verifikasi opsional)
 *   - password          : Password yang sudah di-hash menggunakan bcrypt
 *   - role              : Peran pengguna: 'user' (pengguna biasa) atau 'admin' (administrator)
 *   - remember_token    : Token untuk fitur "ingat saya" (auto-managed Laravel)
 *   - created_at        : Timestamp otomatis saat akun dibuat
 *   - updated_at        : Timestamp otomatis saat akun terakhir diperbarui
 */

namespace App\Models;

// Kelas Authenticatable adalah base class user dengan dukungan auth penuh dari Laravel
use Illuminate\Foundation\Auth\User as Authenticatable;

// HasFactory menyediakan method factory() untuk pembuatan data testing di seeder/test
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Database\Factories\UserFactory;

// Notifiable memungkinkan pengiriman notifikasi email/database ke user
use Illuminate\Notifications\Notifiable;

// Attribute PHP 8 untuk mendefinisikan fillable fields secara ringkas
use Illuminate\Database\Eloquent\Attributes\Fillable;

// Attribute PHP 8 untuk mendefinisikan hidden fields secara ringkas
use Illuminate\Database\Eloquent\Attributes\Hidden;

/**
 * Mendefinisikan kolom yang dapat diisi secara massal (mass-assignment).
 * Hanya 'name', 'email', dan 'password' yang diizinkan oleh $fillable.
 * Kolom 'role' sengaja tidak disertakan di sini — diset secara eksplisit di controller.
 */
#[Fillable(['name', 'email', 'password', 'role'])]

/**
 * Mendefinisikan kolom yang disembunyikan dari serialisasi JSON/array.
 * 'password' dan 'remember_token' tidak akan muncul saat $user->toArray() atau response JSON.
 */
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> Mendaftarkan factory untuk testing dan seeding */
    use HasFactory, Notifiable;

    /**
     * Mendefinisikan casting atribut database ke tipe data PHP yang sesuai.
     *
     * - 'email_verified_at' di-cast ke objek Carbon (datetime) untuk kemudahan manipulasi tanggal
     * - 'password' di-cast ke 'hashed' — Laravel otomatis meng-hash saat assignment (Laravel 10+)
     *
     * @return array<string, string> Peta nama kolom ke tipe cast
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime', // Cast ke objek Carbon untuk format tanggal fleksibel
            'password'          => 'hashed',   // Hash otomatis saat $user->password = 'plain' (Laravel 10+)
        ];
    }
}

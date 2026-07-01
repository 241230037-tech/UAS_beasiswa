<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

<<<<<<< HEAD
/**
 * Class User
 * Model untuk merepresentasikan data Pengguna (User) di dalam database.
 * Model ini mewarisi kelas Authenticatable untuk menangani otentikasi default Laravel.
 */
#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
=======
#[Fillable([
    'name',
    'email',
    'password',
    'role',
    'status',
])]
#[Hidden([
    'password',
    'remember_token',
])]
>>>>>>> bb393d0d59e3b7b4171a66201def415e171419a7
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
<<<<<<< HEAD
     * Menentukan casting atribut database ke tipe data objek PHP.
     * Email verified cast ke datetime, password cast ke hashed otomatis saat disimpan.
=======
     * Cast attributes.
>>>>>>> bb393d0d59e3b7b4171a66201def415e171419a7
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'status' => 'boolean',
        ];
    }

    /**
     * Relasi ke Profil Mahasiswa (One to One)
     */
    public function profilMahasiswa()
    {
        return $this->hasOne(ProfilMahasiswa::class);
    }

    /**
     * Relasi ke Bookmark (One to Many)
     */
    public function bookmarks()
    {
        return $this->hasMany(Bookmark::class);
    }

    /**
     * Relasi ke Beasiswa (Admin yang membuat beasiswa)
     */
    public function beasiswa()
    {
        return $this->hasMany(Beasiswa::class, 'admin_id');
    }

    /**
     * Cek apakah user adalah Admin
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Cek apakah user adalah Mahasiswa
     */
    public function isMahasiswa(): bool
    {
        return $this->role === 'mahasiswa';
    }
}
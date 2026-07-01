<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

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
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Cast attributes.
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
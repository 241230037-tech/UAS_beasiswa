<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
<<<<<<< HEAD
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Bookmark
 * Model untuk merepresentasikan tabel data Bookmark (bookmarks) di database.
 * Menyimpan relasi antara Pengguna (User) dan Beasiswa (Scholarship) favorit.
 */
class Bookmark extends Model
{
    // Mengizinkan pengisian massal untuk user_id dan scholarship_id
    protected $fillable = ['user_id', 'scholarship_id'];

    /**
     * Relasi many-to-one ke model User (Pemilik Bookmark).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi many-to-one ke model Scholarship (Beasiswa yang disimpan).
     */
    public function scholarship(): BelongsTo
    {
        return $this->belongsTo(Scholarship::class, 'scholarship_id', 'id');
    }
=======

class Bookmark extends Model
{
    //
>>>>>>> bb393d0d59e3b7b4171a66201def415e171419a7
}

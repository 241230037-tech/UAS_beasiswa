<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Iklan extends Model
{
    protected $fillable = [
        'judul',
        'gambar',
        'deskripsi',
        'link',
        'status',
        'admin_id',
    ];

    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}
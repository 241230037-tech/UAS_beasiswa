<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Beasiswa extends Model
{
    protected $fillable = [
        'nama',
        'penyelenggara',
        'deskripsi',
        'persyaratan',
        'minimal_ipk',
        'jurusan',
        'semester_min',
        'domisili',
        'deadline',
        'link_pendaftaran',
        'gambar',
        'status',
        'admin_id',
    ];

    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}
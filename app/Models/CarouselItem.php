<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CarouselItem extends Model
{
    // Mengatur atribut yang dapat diisi secara massal
    protected $fillable = [
        'type',
        'scholarship_id',
        'title',
        'subtitle',
        'description',
        'image_url',
        'video_url',
        'link',
        'order_index',
    ];

    /**
     * Relasi many-to-one ke model Scholarship untuk menarik info beasiswa terhubung.
     */
    public function scholarship()
    {
        return $this->belongsTo(Scholarship::class);
    }
}

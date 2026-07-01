<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class AdBanner
 * Model untuk merepresentasikan tabel data Spanduk Iklan (ad_banners) di database.
 */
class AdBanner extends Model
{
    // Mengizinkan semua field iklan untuk diisi secara massal (mass-assignment) saat store/update data
    protected $guarded = [];
}

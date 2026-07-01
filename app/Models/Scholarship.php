<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Scholarship
 * Model untuk merepresentasikan tabel data Beasiswa (scholarships) di database.
 */
class Scholarship extends Model
{
    // Mengatur tipe data Primary Key agar bertipe string (karena ID beasiswa menggunakan string unik seperti '1', '2')
    protected $keyType = 'string';
    
    // Menolak auto-incrementing pada primary key karena kita mendefinisikan ID secara manual
    public $incrementing = false;
    
    // Mengizinkan semua field untuk diisi secara massal (mass-assignment) saat store/update data
    protected $guarded = [];
}

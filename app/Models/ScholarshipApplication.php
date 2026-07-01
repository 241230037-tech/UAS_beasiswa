<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ScholarshipApplication
 * Model untuk merepresentasikan tabel lamaran pendaftaran beasiswa (scholarship_applications) di database.
 */
class ScholarshipApplication extends Model
{
    // Mengizinkan semua field pendaftaran untuk diisi secara massal (mass-assignment)
    protected $guarded = [];
}

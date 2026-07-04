<?php

namespace Database\Seeders;

use App\Models\Beasiswa;
use Illuminate\Database\Seeder;

class BeasiswaSeeder extends Seeder
{
    public function run(): void
    {
        Beasiswa::create([
            'nama' => 'Beasiswa LPDP',
            'penyelenggara' => 'LPDP',
            'deskripsi' => 'Beasiswa penuh untuk S2 dan S3.',
            'persyaratan' => 'IPK minimal 3.25',
            'minimal_ipk' => 3.25,
            'jurusan' => 'Semua Jurusan',
            'semester_min' => 5,
            'domisili' => 'Indonesia',
            'deadline' => '2026-12-31',
            'link_pendaftaran' => 'https://lpdp.kemenkeu.go.id',
            'status' => 'dibuka',
            'admin_id' => 1,
        ]);

        Beasiswa::create([
            'nama' => 'Beasiswa Bank Indonesia',
            'penyelenggara' => 'Bank Indonesia',
            'deskripsi' => 'Program GenBI.',
            'persyaratan' => 'IPK minimal 3.00',
            'minimal_ipk' => 3.00,
            'jurusan' => 'Semua Jurusan',
            'semester_min' => 3,
            'domisili' => 'Indonesia',
            'deadline' => '2026-10-20',
            'link_pendaftaran' => 'https://www.bi.go.id',
            'status' => 'dibuka',
            'admin_id' => 1,
        ]);
    }
}
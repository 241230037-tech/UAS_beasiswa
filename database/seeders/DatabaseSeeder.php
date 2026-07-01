<?php

namespace Database\Seeders;

<<<<<<< HEAD
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Scholarship;
use App\Models\AdBanner;
use App\Data\ScholarshipData;
=======
>>>>>>> bb393d0d59e3b7b4171a66201def415e171419a7
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
<<<<<<< HEAD
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed scholarships
        $scholarships = ScholarshipData::all();
        foreach ($scholarships as $s) {
            Scholarship::create($s);
        }

        // Seed ad banners
        $adBanners = ScholarshipData::adBanners();
        foreach ($adBanners as $ad) {
            AdBanner::create($ad);
        }

        // Seed Akun Admin Tetap (Fixed Admin Accounts)
        User::create([
            'name' => 'Administrator 1',
            'email' => 'admin1@email.com',
            'password' => bcrypt('admin1'), // Password: admin1
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Administrator 2',
            'email' => 'admin2@email.com',
            'password' => bcrypt('admin2'), // Password: admin2
            'role' => 'admin',
=======
    public function run(): void
    {
        $this->call([
            AdminSeeder::class,
>>>>>>> bb393d0d59e3b7b4171a66201def415e171419a7
        ]);
    }
}
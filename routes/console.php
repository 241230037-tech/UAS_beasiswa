<?php

/**
 * File: routes/console.php
 *
 * Tempat untuk mendefinisikan perintah konsol Artisan berbasis penutupan (closure).
 * Perintah di sini dapat dijalankan melalui terminal dengan perintah: php artisan inspire
 */

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

// Perintah konsol sederhana untuk menampilkan kutipan motivasi inspiratif
Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Menampilkan kutipan motivasi inspiratif');

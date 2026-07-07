<?php

/**
 * File Rute Web (routes/web.php)
 * Di sinilah kita mendefinisikan seluruh URL rute web yang dapat diakses oleh browser 
 * dan dipetakan langsung ke method controller yang sesuai di PageController.
 */

use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;

// Rute Halaman Pengunjung / Publik
// Rute '/' mengarahkan ke Landing Page awal saat pertama kali membuka website.
Route::get('/', [PageController::class, 'landing']);

// Rute '/home' mengarahkan ke Halaman Utama portal beasiswa yang menampilkan banner promosi & highlight beasiswa.
Route::get('/home', [PageController::class, 'home'])->name('home');

// Rute '/login' menampilkan halaman login dan pendaftaran akun tiruan.
Route::get('/login', [PageController::class, 'login'])->name('login');

// Rute '/library' menampilkan Katalog Beasiswa lengkap beserta fitur filter pencarian, negara, dan tingkat studi.
Route::get('/library', [PageController::class, 'library'])->name('library');

// Rute '/dashboard' menampilkan halaman profil pengguna dan daftar beasiswa yang telah mereka simpan (bookmark).
Route::get('/dashboard', [PageController::class, 'dashboard'])->name('dashboard');

// Rute '/scholarship/{id}' menampilkan detail lengkap sebuah beasiswa berdasarkan ID uniknya.
Route::get('/scholarship/{id}', [PageController::class, 'scholarshipDetail'])->name('scholarship.detail');

// Rute '/scholarship/{id}/register' menampilkan formulir pendaftaran lamaran beasiswa bersangkutan.
Route::get('/scholarship/{id}/register', [PageController::class, 'register'])->name('scholarship.register');

// Rute POST '/api/scholarship/register' memproses data pendaftaran beasiswa dan file dokumen (KTP, Ijazah, dll).
Route::post('/api/scholarship/register', [PageController::class, 'submitRegistration'])->name('api.scholarship.register');

// Rute '/tutorial' menampilkan tutorial langkah-demi-langkah cara mendaftar beasiswa secara umum.
Route::get('/tutorial', [PageController::class, 'tutorial'])->name('tutorial');

// Rute '/admin' menampilkan halaman dashboard panel admin untuk memanipulasi data beasiswa & iklan.
Route::get('/admin', [PageController::class, 'admin'])->name('admin');

// Rute API CRUD untuk Administrator dalam mengelola Data Beasiswa
Route::post('/admin/scholarships', [PageController::class, 'storeScholarship'])->name('admin.scholarships.store');
Route::put('/admin/scholarships/{id}', [PageController::class, 'updateScholarship'])->name('admin.scholarships.update');
Route::delete('/admin/scholarships/{id}', [PageController::class, 'deleteScholarship'])->name('admin.scholarships.delete');
// Rute khusus untuk upload file gambar/logo beasiswa ke direktori storage/public
Route::post('/admin/scholarships/upload-image', [PageController::class, 'uploadScholarshipImage'])->name('admin.scholarships.upload-image');

// Rute API CRUD untuk Administrator dalam mengelola Spanduk Iklan (Ads Banner)
Route::post('/admin/ads', [PageController::class, 'storeAd'])->name('admin.ads.store');
Route::put('/admin/ads/{id}', [PageController::class, 'updateAd'])->name('admin.ads.update');
Route::delete('/admin/ads/{id}', [PageController::class, 'deleteAd'])->name('admin.ads.delete');
// Rute khusus untuk upload file gambar iklan ke direktori storage/public
Route::post('/admin/ads/upload-image', [PageController::class, 'uploadAdImage'])->name('admin.ads.upload-image');

// Rute API CRUD untuk Administrator dalam mengelola Akun Admin Lainnya (Tugas 9)
Route::post('/admin/admins', [PageController::class, 'storeAdmin'])->name('admin.admins.store');
Route::put('/admin/admins/{id}', [PageController::class, 'updateAdmin'])->name('admin.admins.update');
Route::delete('/admin/admins/{id}', [PageController::class, 'deleteAdmin'])->name('admin.admins.delete');

// Rute API CRUD untuk Administrator dalam mengelola Slide Carousel / Slider (Request 2)
Route::post('/admin/carousel', [PageController::class, 'storeCarouselItem'])->name('admin.carousel.store');
Route::put('/admin/carousel/{id}', [PageController::class, 'updateCarouselItem'])->name('admin.carousel.update');
Route::delete('/admin/carousel/{id}', [PageController::class, 'deleteCarouselItem'])->name('admin.carousel.delete');
Route::post('/admin/carousel/upload-video', [PageController::class, 'uploadCarouselVideo'])->name('admin.carousel.upload-video');
Route::post('/admin/carousel/upload-video-chunk', [PageController::class, 'uploadCarouselVideoChunk'])->name('admin.carousel.upload-video-chunk');
// Route streaming video dengan HTTP Range Request — wajib untuk fitur seek/skip di browser
Route::get('/stream/video/{filename}', [PageController::class, 'streamCarouselVideo'])->name('video.stream')->where('filename', '.+');

// Rute API Otentikasi Berbasis Database (Session-backed)
Route::post('/api/login', [PageController::class, 'apiLogin'])->name('api.login');
Route::post('/api/register', [PageController::class, 'apiRegister'])->name('api.register');
Route::post('/api/logout', [PageController::class, 'apiLogout'])->name('api.logout');
// Rute API Update Profil dan Password Pengguna Terintegrasi DB (Tugas 10)
Route::post('/api/update-profile', [PageController::class, 'updateProfile'])->name('api.update-profile');


// Rute API Bookmark Berbasis Database
Route::get('/api/bookmarks', [PageController::class, 'getBookmarks'])->name('api.bookmarks');
Route::post('/api/bookmarks/toggle', [PageController::class, 'toggleBookmark'])->name('api.bookmarks.toggle');


<?php

/**
 * File: routes/web.php
 *
 * Mendefinisikan seluruh rute URL web yang dapat diakses oleh browser.
 * Setiap rute dipetakan ke method pada controller yang sesuai dengan
 * tanggung jawabnya masing-masing (Single Responsibility Principle).
 *
 * Daftar Controller:
 *   - PageController      : Halaman publik (landing, home, library, dashboard, dll.)
 *   - ScholarshipController : CRUD beasiswa (admin)
 *   - AdBannerController  : CRUD iklan (admin)
 *   - CarouselController  : CRUD carousel + streaming video
 *   - AdminController     : CRUD akun admin
 *   - AuthController      : Login, register, logout, update profil
 *   - BookmarkController  : Get & toggle bookmark beasiswa
 */

use App\Http\Controllers\AdminController;         // Controller manajemen akun admin
use App\Http\Controllers\AdBannerController;      // Controller manajemen spanduk iklan
use App\Http\Controllers\AuthController;          // Controller autentikasi pengguna
use App\Http\Controllers\BookmarkController;      // Controller bookmark beasiswa
use App\Http\Controllers\CarouselController;      // Controller carousel/slider + video
use App\Http\Controllers\PageController;          // Controller halaman publik
use App\Http\Controllers\ScholarshipController;   // Controller manajemen data beasiswa
use Illuminate\Support\Facades\Route;             // Facade untuk mendefinisikan rute

// ============================================================
// GRUP 1: Halaman Publik (dapat diakses semua pengunjung)
// ============================================================

// Halaman Landing Page — tampilan pertama saat mengunjungi website
Route::get('/', [PageController::class, 'landing']);

// Halaman Beranda (Home) — portal beasiswa dengan slider & katalog
Route::get('/home', [PageController::class, 'home'])->name('home');

// Halaman Login & Registrasi — form masuk dan daftar akun
Route::get('/login', [PageController::class, 'login'])->name('login');

// Halaman Katalog Beasiswa (Library) — daftar lengkap semua beasiswa dengan filter
Route::get('/library', [PageController::class, 'library'])->name('library');

// Halaman Dashboard — profil pengguna dan daftar beasiswa yang dibookmark
Route::get('/dashboard', [PageController::class, 'dashboard'])->name('dashboard');

// Halaman Detail Beasiswa — informasi lengkap satu beasiswa berdasarkan ID
Route::get('/scholarship/{id}', [PageController::class, 'scholarshipDetail'])->name('scholarship.detail');

// Halaman Formulir Pendaftaran — form lamaran beasiswa berdasarkan ID
Route::get('/scholarship/{id}/register', [PageController::class, 'register'])->name('scholarship.register');

// Halaman Tutorial — panduan langkah-demi-langkah cara mendaftar beasiswa
Route::get('/tutorial', [PageController::class, 'tutorial'])->name('tutorial');

// Halaman Panel Admin — dashboard manajemen data (khusus admin)
Route::get('/admin', [PageController::class, 'admin'])->name('admin');

// ============================================================
// GRUP 2: API Pendaftaran Beasiswa
// ============================================================

// Proses pengiriman formulir lamaran beasiswa beserta file dokumen (KTP, Ijazah, Transkrip, CV)
Route::post('/api/scholarship/register', [PageController::class, 'submitRegistration'])
    ->name('api.scholarship.register');

// ============================================================
// GRUP 3: CRUD Beasiswa (Admin)
// ============================================================

// Tambah beasiswa baru ke database
Route::post('/admin/scholarships', [ScholarshipController::class, 'store'])
    ->name('admin.scholarships.store');

// Perbarui data beasiswa berdasarkan ID
Route::put('/admin/scholarships/{id}', [ScholarshipController::class, 'update'])
    ->name('admin.scholarships.update');

// Hapus beasiswa berdasarkan ID
Route::delete('/admin/scholarships/{id}', [ScholarshipController::class, 'destroy'])
    ->name('admin.scholarships.delete');

// Upload file logo/gambar beasiswa ke storage
Route::post('/admin/scholarships/upload-image', [ScholarshipController::class, 'uploadImage'])
    ->name('admin.scholarships.upload-image');

// ============================================================
// GRUP 4: CRUD Spanduk Iklan / Ad Banner (Admin)
// ============================================================

// Tambah iklan baru ke database
Route::post('/admin/ads', [AdBannerController::class, 'store'])
    ->name('admin.ads.store');

// Perbarui data iklan berdasarkan ID
Route::put('/admin/ads/{id}', [AdBannerController::class, 'update'])
    ->name('admin.ads.update');

// Hapus iklan berdasarkan ID (beserta file gambarnya di storage)
Route::delete('/admin/ads/{id}', [AdBannerController::class, 'destroy'])
    ->name('admin.ads.delete');

// Upload file gambar/media iklan ke storage
Route::post('/admin/ads/upload-image', [AdBannerController::class, 'uploadImage'])
    ->name('admin.ads.upload-image');

// Pelacakan klik iklan dan redirect ke tautan tujuan
Route::get('/ad/click/{id}', [AdBannerController::class, 'trackClick'])
    ->name('ad.click');

// ============================================================
// GRUP 5: CRUD Carousel / Slider (Admin)
// ============================================================

// Tambah slide carousel baru ke database
Route::post('/admin/carousel', [CarouselController::class, 'store'])
    ->name('admin.carousel.store');

// Perbarui data slide carousel berdasarkan ID
Route::put('/admin/carousel/{id}', [CarouselController::class, 'update'])
    ->name('admin.carousel.update');

// Hapus slide carousel berdasarkan ID
Route::delete('/admin/carousel/{id}', [CarouselController::class, 'destroy'])
    ->name('admin.carousel.delete');

// Upload file video carousel (untuk file kecil — upload biasa)
Route::post('/admin/carousel/upload-video', [CarouselController::class, 'uploadVideo'])
    ->name('admin.carousel.upload-video');

// Upload file video carousel dalam potongan (chunked upload — untuk file besar)
Route::post('/admin/carousel/upload-video-chunk', [CarouselController::class, 'uploadVideoChunk'])
    ->name('admin.carousel.upload-video-chunk');

// Streaming video carousel dengan dukungan HTTP Range Request (untuk seek/skip video)
Route::get('/stream/video/{filename}', [CarouselController::class, 'streamVideo'])
    ->name('video.stream')
    ->where('filename', '.+'); // Regex memungkinkan karakter '.' di nama file (misal: video.mp4)

// ============================================================
// GRUP 6: CRUD Akun Admin (Super Admin)
// ============================================================

// Tambah akun admin baru ke database
Route::post('/admin/admins', [AdminController::class, 'store'])
    ->name('admin.admins.store');

// Perbarui data akun admin berdasarkan ID
Route::put('/admin/admins/{id}', [AdminController::class, 'update'])
    ->name('admin.admins.update');

// Hapus akun admin berdasarkan ID (tidak dapat menghapus diri sendiri)
Route::delete('/admin/admins/{id}', [AdminController::class, 'destroy'])
    ->name('admin.admins.delete');

// Hapus akun pengguna biasa berdasarkan ID
Route::delete('/admin/users/{id}', [AdminController::class, 'destroyUser'])
    ->name('admin.users.delete');

// Atur waktu kadaluarsa/mati akun pengguna biasa berdasarkan ID
Route::put('/admin/users/{id}/deactivate', [AdminController::class, 'setDeactivation'])
    ->name('admin.users.deactivate');

// Perbarui status pendaftaran beasiswa (dikirim/ditolak) berdasarkan ID
Route::put('/admin/applications/{id}/status', [AdminController::class, 'updateApplicationStatus'])
    ->name('admin.applications.updateStatus');

// Update pengaturan global (misal: batas masa aktif akun global)
Route::put('/admin/settings', [AdminController::class, 'updateGlobalSettings'])
    ->name('admin.settings.update');

// API Asisten Chatbot AI
Route::post('/api/chatbot', [\App\Http\Controllers\ChatbotController::class, 'respond'])
    ->name('api.chatbot');

// ============================================================
// GRUP 7: Autentikasi Berbasis Database (Session)
// ============================================================

// Proses login pengguna dengan verifikasi email & password
Route::post('/api/login', [AuthController::class, 'login'])
    ->name('api.login');

// Proses pendaftaran akun pengguna baru
Route::post('/api/register', [AuthController::class, 'register'])
    ->name('api.register');

// Proses logout pengguna (menghapus session)
Route::post('/api/logout', [AuthController::class, 'logout'])
    ->name('api.logout');

// Perbarui nama dan/atau password pengguna yang sedang login
Route::post('/api/update-profile', [AuthController::class, 'updateProfile'])
    ->name('api.update-profile');

// ============================================================
// GRUP 8: Bookmark Beasiswa (Pengguna Login)
// ============================================================

// Ambil daftar ID beasiswa yang telah dibookmark oleh pengguna yang sedang login
Route::get('/api/bookmarks', [BookmarkController::class, 'index'])
    ->name('api.bookmarks');

// Toggle bookmark: tambah jika belum ada, hapus jika sudah ada
Route::post('/api/bookmarks/toggle', [BookmarkController::class, 'toggle'])
    ->name('api.bookmarks.toggle');

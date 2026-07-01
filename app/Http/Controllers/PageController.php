<?php

namespace App\Http\Controllers;

use App\Data\ScholarshipData;
use App\Models\Scholarship;
use App\Models\AdBanner;
use App\Models\ScholarshipApplication;
use App\Models\User;
use App\Models\Bookmark;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

/**
 * Class PageController
 * Controller utama yang menangani semua logika navigasi halaman, validasi input pendaftaran,
 * dan API CRUD untuk manipulasi data beasiswa serta iklan oleh administrator.
 */
class PageController extends Controller
{
    /**
     * Menampilkan halaman Landing Page awal saat pengguna mengunjungi website pertama kali.
     */
    public function landing(): View
    {
        return view('pages.landing');
    }

    /**
     * Menampilkan halaman Beranda (Home) yang berisi slider iklan aktif
     * serta daftar beasiswa unggulan yang diambil dari database.
     */
    public function home(): View
    {
        return view('pages.home', [
            // Mengambil semua data beasiswa dari database dan mengubahnya ke bentuk array biasa
            'scholarships' => Scholarship::all()->toArray(),
            // Mengambil semua data spanduk iklan banner
            'adBanners' => AdBanner::all()->toArray(),
        ]);
    }

    /**
     * Menampilkan halaman Login / Registrasi akun tiruan.
     * Menerima parameter query 'redirect' untuk mengalihkan kembali pengguna setelah sukses masuk.
     */
    public function login(Request $request): View
    {
        return view('pages.login', [
            'redirect' => $request->query('redirect', '/home'),
            'role' => $request->query('role', ''), // Parameter query untuk menentukan form login admin/pengguna
        ]);
    }

    /**
     * Menampilkan Katalog Beasiswa (Library).
     * Menerima kata kunci pencarian 'q' jika ada yang dikirimkan via form pencarian di navbar.
     */
    public function library(Request $request): View
    {
        return view('pages.library', [
            'scholarships' => Scholarship::all()->toArray(),
            'filters' => [
                'q' => $request->query('q', ''),
            ],
        ]);
    }

    /**
     * Menampilkan Dashboard akun pengguna (berisi daftar beasiswa yang telah dibookmark).
     */
    public function dashboard(): View
    {
        return view('pages.dashboard', [
            'scholarships' => Scholarship::all()->toArray(),
        ]);
    }

    /**
     * Menampilkan Halaman Detail Beasiswa berdasarkan ID uniknya.
     * Jika beasiswa tidak ditemukan di database, akan mengembalikan respons 404 (Not Found).
     */
    public function scholarshipDetail(string $id): View
    {
        $scholarship = Scholarship::find($id);

        if (! $scholarship) {
            abort(404);
        }

        return view('pages.scholarship-detail', [
            'scholarship' => $scholarship->toArray(),
            'extra' => ScholarshipData::extra($id), // Mengambil info tambahan seperti syarat & manfaat beasiswa
        ]);
    }

    /**
     * Menampilkan halaman formulir lamaran pendaftaran untuk beasiswa tertentu.
     * Mewajibkan beasiswa tersebut valid.
     */
    public function register(string $id): View
    {
        $scholarship = Scholarship::find($id);

        if (! $scholarship) {
            abort(404);
        }

        return view('pages.register', [
            'scholarship' => $scholarship->toArray(),
            'extra' => ScholarshipData::extra($id),
        ]);
    }

    /**
     * Memproses pengiriman formulir lamaran pendaftaran beasiswa (POST).
     * Melakukan validasi berkas (ukuran, ekstensi) dan menyimpannya di folder storage.
     */
    public function submitRegistration(Request $request): JsonResponse
    {
        // Melakukan validasi data inputan pendaftar.
        $data = $request->validate([
            'scholarship_id' => 'required|string',
            'scholarship_title' => 'required|string',
            'full_name' => 'required|string',
            'nik' => 'required|string|size:16', // NIK harus berupa string berukuran 16 karakter
            'email' => 'required|email',
            'phone' => 'required|string',
            'birth_date' => 'required|date',
            'gender' => 'required|string',
            'address' => 'required|string',
            'applied_level' => 'required|string',
            'university' => 'required|string',
            'major' => 'required|string',
            'gpa' => 'required|string',
            'english_score' => 'nullable|string',
            'target_university' => 'nullable|string',
            'ktp' => 'required|file|max:2048', // File KTP wajib diunggah, maksimal 2MB
            'ijazah' => 'required|file|max:2048', // File Ijazah wajib diunggah, maksimal 2MB
            'transcript' => 'required|file|max:2048', // File Transkrip wajib diunggah, maksimal 2MB
            'cv' => 'nullable|file|max:2048', // CV opsional
            'motivation' => 'required|string|min:50', // Motivation letter minimal 50 karakter
        ]);

        // Menyimpan berkas-berkas yang diunggah ke disk 'public' di bawah direktori 'uploads'
        $ktpPath = $request->file('ktp')->store('uploads', 'public');
        $ijazahPath = $request->file('ijazah')->store('uploads', 'public');
        $transcriptPath = $request->file('transcript')->store('uploads', 'public');
        $cvPath = $request->hasFile('cv') ? $request->file('cv')->store('uploads', 'public') : null;

        // Menyimpan data pendaftaran lamaran beasiswa ke database SQLite
        $application = ScholarshipApplication::create([
            'scholarship_id' => $data['scholarship_id'],
            'scholarship_title' => $data['scholarship_title'],
            'full_name' => $data['full_name'],
            'nik' => $data['nik'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'birth_date' => $data['birth_date'],
            'gender' => $data['gender'],
            'address' => $data['address'],
            'applied_level' => $data['applied_level'],
            'university' => $data['university'],
            'major' => $data['major'],
            'gpa' => $data['gpa'],
            'english_score' => $data['english_score'],
            'target_university' => $data['target_university'],
            'ktp_path' => $ktpPath,
            'ijazah_path' => $ijazahPath,
            'transcript_path' => $transcriptPath,
            'cv_path' => $cvPath,
            'motivation' => $data['motivation'],
        ]);

        // Mengembalikan respons sukses dalam format JSON ke AJAX frontend
        return response()->json([
            'success' => true,
            'message' => 'Pendaftaran berhasil dikirim!',
            'application' => $application
        ]);
    }

    /**
     * Menampilkan halaman panduan dan tutorial pendaftaran beasiswa.
     */
    public function tutorial(): View
    {
        return view('pages.tutorial', [
            'steps' => ScholarshipData::tutorialSteps(), // Mengambil langkah tutorial statis
        ]);
    }

    /**
     * Menampilkan Dashboard Admin (Halaman Manajemen).
     * Mengambil data beasiswa dan iklan untuk dikelola.
     */
    public function admin(): View
    {
        return view('pages.admin', [
            'scholarships' => Scholarship::all()->toArray(),
            'adBanners' => AdBanner::all()->toArray(),
        ]);
    }

    /**
     * Menyimpan data beasiswa baru ke database (POST).
     */
    public function storeScholarship(Request $request): JsonResponse
    {
        $data = $request->validate([
            'id' => 'required|string',
            'title' => 'required|string',
            'provider' => 'required|string',
            'location' => 'required|string',
            'flag' => 'nullable|string',
            'level' => 'required|string',
            'amount' => 'required|string',
            'deadline' => 'required|string',
            'status' => 'required|string',
            'image' => 'required|string',
            'external_link' => 'required|string',
            'updated_ago' => 'required|string',
        ]);

        $s = Scholarship::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Beasiswa berhasil ditambahkan!',
            'data' => $s
        ]);
    }

    /**
     * Memperbarui data beasiswa berdasarkan ID (PUT/PATCH).
     */
    public function updateScholarship(Request $request, string $id): JsonResponse
    {
        $s = Scholarship::find($id);
        if (!$s) {
            return response()->json(['success' => false, 'message' => 'Beasiswa tidak ditemukan.'], 404);
        }

        $data = $request->validate([
            'title' => 'required|string',
            'provider' => 'required|string',
            'location' => 'required|string',
            'flag' => 'nullable|string',
            'level' => 'required|string',
            'amount' => 'required|string',
            'deadline' => 'required|string',
            'status' => 'required|string',
            'image' => 'required|string',
            'external_link' => 'required|string',
            'updated_ago' => 'required|string',
        ]);

        $s->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Beasiswa berhasil diperbarui!',
            'data' => $s
        ]);
    }

    /**
     * Menghapus data beasiswa berdasarkan ID (DELETE).
     */
    public function deleteScholarship(string $id): JsonResponse
    {
        $s = Scholarship::find($id);
        if (!$s) {
            return response()->json(['success' => false, 'message' => 'Beasiswa tidak ditemukan.'], 404);
        }

        $s->delete();

        return response()->json([
            'success' => true,
            'message' => 'Beasiswa berhasil dihapus!'
        ]);
    }

    /**
     * Menyimpan data iklan baru ke database (POST).
     */
    public function storeAd(Request $request): JsonResponse
    {
        // Validasi field iklan; image_url bersifat opsional (nullable)
        $data = $request->validate([
            'title'       => 'required|string',
            'subtitle'    => 'required|string',
            'description' => 'required|string',
            'cta_text'    => 'required|string',
            'bg_from'     => 'required|string',
            'bg_to'       => 'required|string',
            'tag'         => 'required|string',
            'link'        => 'required|string',
            'image_url'   => 'nullable|string', // URL gambar iklan (diisi otomatis dari upload)
        ]);

        $ad = AdBanner::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Iklan berhasil ditambahkan!',
            'data'    => $ad
        ]);
    }

    /**
     * Memperbarui data iklan berdasarkan ID (PUT/PATCH).
     */
    public function updateAd(Request $request, int $id): JsonResponse
    {
        $ad = AdBanner::find($id);
        if (!$ad) {
            return response()->json(['success' => false, 'message' => 'Iklan tidak ditemukan.'], 404);
        }

        // Validasi field iklan; image_url opsional, tidak wajib diisi ulang saat edit
        $data = $request->validate([
            'title'       => 'required|string',
            'subtitle'    => 'required|string',
            'description' => 'required|string',
            'cta_text'    => 'required|string',
            'bg_from'     => 'required|string',
            'bg_to'       => 'required|string',
            'tag'         => 'required|string',
            'link'        => 'required|string',
            'image_url'   => 'nullable|string', // URL gambar iklan (opsional saat update)
        ]);

        $ad->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Iklan berhasil diperbarui!',
            'data'    => $ad
        ]);
    }

    /**
     * Mengupload file gambar untuk spanduk iklan ke direktori storage/public/ad-images.
     * Mengembalikan URL publik file yang dapat disimpan di kolom image_url tabel ad_banners.
     *
     * Cara kerja:
     *  1. Admin memilih file gambar dari form modal iklan.
     *  2. File dikirim via POST multipart/form-data ke endpoint ini.
     *  3. File disimpan di storage/app/public/ad-images/ (dapat diakses publik via symlink).
     *  4. URL publiknya dikembalikan sebagai respons JSON.
     *  5. URL tersebut otomatis diisi ke field image_url dan ikut tersimpan ke database saat form di-submit.
     */
    public function uploadAdImage(Request $request): JsonResponse
    {
        // Validasi: file wajib ada, harus berupa gambar, maksimal ukuran 2MB (2048 KB)
        $request->validate([
            'image' => 'required|image|max:2048',
        ]);

        // Simpan file ke storage/app/public/ad-images/ dengan nama acak yang aman
        $path = $request->file('image')->store('ad-images', 'public');

        // Kembalikan URL publik yang bisa langsung digunakan di tag <img> atau disimpan di database
        return response()->json([
            'success'   => true,
            'url'       => asset('storage/' . $path),
            'path'      => $path,
        ]);
    }

    /**
     * Menghapus data iklan berdasarkan ID (DELETE).
     * Jika iklan memiliki gambar yang diupload, gambar tersebut juga dihapus dari storage.
     */
    public function deleteAd(int $id): JsonResponse
    {
        $ad = AdBanner::find($id);
        if (!$ad) {
            return response()->json(['success' => false, 'message' => 'Iklan tidak ditemukan.'], 404);
        }

        // Jika iklan memiliki gambar yang diupload, hapus juga file fisiknya dari storage
        if ($ad->image_url) {
            // Ambil path relatif dari URL publik (setelah "/storage/")
            $relativePath = str_replace('/storage/', '', parse_url($ad->image_url, PHP_URL_PATH));
            Storage::disk('public')->delete($relativePath);
        }

        $ad->delete();

        return response()->json([
            'success' => true,
            'message' => 'Iklan berhasil dihapus!'
        ]);
    }

    /**
     * API Login Berbasis Database.
     * Menerima email/username dan password, lalu memverifikasi di database.
     */
    public function apiLogin(Request $request): JsonResponse
    {
        $data = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        $emailInput = trim($data['email']);
        $password = $data['password'];

        // Jika input username admin tanpa domain, tambahkan @email.com otomatis
        if (!str_contains($emailInput, '@')) {
            $emailInput = $emailInput . '@email.com';
        }

        // Cari user berdasarkan email
        $user = User::where('email', $emailInput)->first();

        if ($user && Hash::check($password, $user->password)) {
            // Loginkan user ke session Laravel
            Auth::login($user);

            return response()->json([
                'success' => true,
                'message' => 'Login berhasil!',
                'user' => [
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role,
                ]
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Kredensial login salah atau akun tidak ditemukan.'
        ], 401);
    }

    /**
     * API Registrasi Berbasis Database.
     * Menyimpan data pengguna baru ke tabel users dengan role 'user'.
     */
    public function apiRegister(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => 'required|string|min:3',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
        ]);

        $user = User::create([
            'name' => trim($data['name']),
            'email' => trim($data['email']),
            'password' => Hash::make($data['password']),
            'role' => 'user', // Default pendaftar baru adalah 'user'
        ]);

        // Otomatis loginkan setelah registrasi
        Auth::login($user);

        return response()->json([
            'success' => true,
            'message' => 'Registrasi berhasil!',
            'user' => [
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
            ]
        ]);
    }

    /**
     * API Logout Berbasis Database.
     * Membersihkan session otentikasi Laravel.
     */
    public function apiLogout(): JsonResponse
    {
        Auth::logout();

        return response()->json([
            'success' => true,
            'message' => 'Berhasil keluar akun!'
        ]);
    }

    /**
     * API Get Bookmarks.
     * Mengambil daftar ID beasiswa yang telah dibookmark oleh user yang login.
     */
    public function getBookmarks(): JsonResponse
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json([], 401);
        }

        // Ambil semua scholarship_id milik user saat ini
        $bookmarks = Bookmark::where('user_id', $user->id)->pluck('scholarship_id');

        return response()->json($bookmarks);
    }

    /**
     * API Toggle Bookmark.
     * Menyimpan atau menghapus bookmark beasiswa berdasarkan relasi user.
     */
    public function toggleBookmark(Request $request): JsonResponse
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Silakan login terlebih dahulu.'
            ], 401);
        }

        $data = $request->validate([
            'scholarship_id' => 'required|string|exists:scholarships,id',
        ]);

        $scholarshipId = $data['scholarship_id'];

        // Periksa jika sudah dibookmark
        $bookmark = Bookmark::where('user_id', $user->id)
                            ->where('scholarship_id', $scholarshipId)
                            ->first();

        if ($bookmark) {
            $bookmark->delete();
            return response()->json([
                'success' => true,
                'status' => 'removed',
                'message' => 'Beasiswa dihapus dari Bookmark.'
            ]);
        } else {
            Bookmark::create([
                'user_id' => $user->id,
                'scholarship_id' => $scholarshipId,
            ]);
            return response()->json([
                'success' => true,
                'status' => 'added',
                'message' => 'Beasiswa ditambahkan ke Bookmark!'
            ]);
        }
    }
}

<?php

/**
 * File: app/Http/Controllers/PageController.php
 *
 * Controller ini menangani seluruh navigasi halaman publik aplikasi Beasiswapedia.
 * Setiap method bertanggung jawab atas satu halaman — menyiapkan data yang dibutuhkan
 * view dan meneruskannya ke template Blade yang sesuai.
 *
 * Operasi CRUD dan API dipindahkan ke controller terpisah yang lebih fokus:
 *   - ScholarshipController  : CRUD beasiswa (admin)
 *   - AdBannerController     : CRUD iklan (admin)
 *   - CarouselController     : CRUD carousel + streaming video
 *   - AdminController        : CRUD akun admin
 *   - AuthController         : Login, register, logout, update profil
 *   - BookmarkController     : Get & toggle bookmark beasiswa
 */

namespace App\Http\Controllers;

use App\Data\ScholarshipData;         // Data statis beasiswa sebagai fallback jika database kosong
use App\Models\AdBanner;              // Model Eloquent untuk tabel ad_banners
use App\Models\CarouselItem;          // Model Eloquent untuk tabel carousel_items
use App\Models\Scholarship;           // Model Eloquent untuk tabel scholarships
use App\Models\ScholarshipApplication; // Model Eloquent untuk tabel scholarship_applications
use App\Models\User;                  // Model Eloquent untuk tabel users
use Illuminate\Http\JsonResponse;     // Tipe return untuk respons JSON ke frontend
use Illuminate\Http\Request;          // Objek request yang berisi parameter query dan input
use Illuminate\View\View;             // Tipe return untuk respons yang merender template Blade

class PageController extends Controller
{
    /**
     * Menampilkan halaman Landing Page utama yang pertama kali dilihat pengunjung.
     *
     * Halaman ini memuat data iklan, beasiswa unggulan, dan item carousel dari database.
     * Jika database kosong atau terjadi error, data fallback statis dari ScholarshipData digunakan.
     *
     * @return View Template blade 'pages.landing' beserta data yang diperlukan.
     */
    public function landing(): View
    {
        // Inisialisasi variabel dengan array kosong sebagai nilai default
        $ads          = [];
        $scholarships = [];
        $carouselItems = [];

        try {
            // Ambil maksimal 4 iklan dari database untuk ditampilkan di slider landing
            $ads = AdBanner::limit(4)->get()->toArray();

            // Jika iklan di database kurang dari 4, gunakan data fallback statis
            if (count($ads) < 4) {
                $rawAds = ScholarshipData::adBanners(); // Data iklan statis dari kelas ScholarshipData
                $ads    = array_slice($rawAds, 0, 4);  // Ambil hanya 4 item pertama
            }
        } catch (\Throwable $e) {
            // Jika query database gagal (misal tabel belum ada), gunakan data fallback statis
            $ads = array_slice(ScholarshipData::adBanners(), 0, 4);
        }

        try {
            // Ambil maksimal 5 beasiswa unggulan dari database untuk ditampilkan di landing
            $scholarships = Scholarship::limit(5)->get()->toArray();

            // Jika beasiswa di database kurang dari 5, gunakan data fallback statis
            if (count($scholarships) < 5) {
                $rawScholarships = ScholarshipData::all();         // Data beasiswa statis
                $scholarships    = array_slice($rawScholarships, 0, 5); // Ambil 5 item pertama
            }
        } catch (\Throwable $e) {
            // Fallback ke data statis jika query database gagal
            $scholarships = array_slice(ScholarshipData::all(), 0, 5);
        }

        try {
            // Ambil item carousel yang diurutkan berdasarkan order_index secara ascending
            // with('scholarship') memuat data beasiswa terkait untuk slide bertipe 'scholarship'
            $carouselItems = CarouselItem::with('scholarship')
                ->orderBy('order_index', 'asc')
                ->get()
                ->toArray();
        } catch (\Throwable $e) {
            // Jika query gagal, gunakan array kosong (tidak ada carousel)
            $carouselItems = [];
        }

        // Render template landing page dengan data yang telah disiapkan
        return view('pages.landing', [
            'ads'          => $ads,           // Data iklan untuk slider
            'scholarships' => $scholarships,  // Data beasiswa untuk section highlight
            'carouselItems' => $carouselItems, // Data slide carousel dinamis
        ]);
    }

    /**
     * Menampilkan halaman Beranda (Home) portal beasiswa.
     *
     * Halaman ini memuat daftar semua beasiswa, item carousel, dan iklan yang dipisah
     * berdasarkan posisi tampilnya (atas/bawah halaman).
     *
     * @return View Template blade 'pages.home' beserta data yang diperlukan.
     */
    public function home(): View
    {
        // Inisialisasi semua variabel dengan array kosong sebagai default
        $scholarships  = [];
        $carouselItems = [];
        $topAds        = [];
        $bottomAds     = [];

        try {
            // Ambil semua data beasiswa dari database
            $scholarships = Scholarship::all()->toArray();

            // Ambil semua item carousel yang diurutkan berdasarkan order_index
            $carouselItems = CarouselItem::with('scholarship')
                ->orderBy('order_index', 'asc')
                ->get()
                ->toArray();

            // Pisahkan iklan berdasarkan posisi: 'top' (atas halaman) dan 'bottom' (bawah halaman)
            $topAds    = AdBanner::where('position', 'top')->get()->toArray();
            $bottomAds = AdBanner::where('position', 'bottom')->get()->toArray();
        } catch (\Throwable $e) {
            // Jika ada error query, gunakan array kosong untuk semua variabel
            $scholarships  = [];
            $carouselItems = [];
            $topAds        = [];
            $bottomAds     = [];
        }

        // Render template halaman home dengan semua data yang telah disiapkan
        return view('pages.home', [
            'scholarships'  => $scholarships,  // Semua data beasiswa untuk katalog
            'carouselItems' => $carouselItems, // Data slide carousel/slider hero
            'topAds'        => $topAds,        // Iklan yang tampil di bagian atas halaman
            'bottomAds'     => $bottomAds,     // Iklan yang tampil di bagian bawah halaman
        ]);
    }

    /**
     * Menampilkan halaman Login dan Registrasi akun.
     *
     * @param  Request  $request  Request berisi parameter query 'redirect' (tujuan setelah login) dan 'role'.
     * @return View Template blade 'pages.login'.
     */
    public function login(Request $request): View
    {
        return view('pages.login', [
            // URL tujuan redirect setelah login berhasil; default ke '/home' jika tidak ada
            'redirect' => $request->query('redirect', '/home'),

            // Role yang menentukan tampilan form (admin/user); default kosong
            'role'     => $request->query('role', ''),
        ]);
    }

    /**
     * Menampilkan halaman Katalog Beasiswa (Library).
     *
     * Mengambil semua data beasiswa dari database. Filter dan pencarian ditangani
     * di sisi frontend menggunakan JavaScript.
     *
     * @param  Request  $request  Request berisi parameter 'q' untuk kata kunci pencarian.
     * @return View Template blade 'pages.library' beserta daftar beasiswa.
     */
    public function library(Request $request): View
    {
        $scholarships = []; // Default array kosong jika database tidak tersedia

        try {
            // Ambil semua data beasiswa dari database tanpa filter di sisi server
            $scholarships = Scholarship::all()->toArray();
        } catch (\Throwable $e) {
            // Jika query gagal, gunakan array kosong
            $scholarships = [];
        }

        // Render template library dengan data beasiswa dan filter aktif
        return view('pages.library', [
            'scholarships' => $scholarships,
            'filters'      => [
                'q' => $request->query('q', ''), // Kata kunci pencarian untuk pra-isi form search
            ],
        ]);
    }

    /**
     * Menampilkan halaman Dashboard akun pengguna.
     *
     * Halaman ini menampilkan profil pengguna dan daftar beasiswa yang dapat di-bookmark.
     * Data bookmark itu sendiri diambil secara terpisah via API /api/bookmarks di frontend.
     *
     * @return View Template blade 'pages.dashboard' beserta daftar semua beasiswa.
     */
    public function dashboard(): View
    {
        $scholarships = []; // Default array kosong jika database tidak tersedia

        try {
            // Ambil semua beasiswa untuk referensi data saat menampilkan bookmark
            $scholarships = Scholarship::all()->toArray();
        } catch (\Throwable $e) {
            // Jika query gagal, gunakan array kosong
            $scholarships = [];
        }

        // Render template dashboard dengan data beasiswa
        return view('pages.dashboard', [
            'scholarships' => $scholarships,
        ]);
    }

    /**
     * Menampilkan halaman Detail Beasiswa berdasarkan ID uniknya.
     *
     * @param  string  $id  ID unik beasiswa yang ingin dilihat detailnya.
     * @return View Template blade 'pages.scholarship-detail' atau abort(404) jika tidak ditemukan.
     */
    public function scholarshipDetail(string $id): View
    {
        // Cari beasiswa berdasarkan ID di database
        $scholarship = Scholarship::find($id);

        // Tampilkan halaman 404 jika beasiswa tidak ditemukan
        if (! $scholarship) {
            abort(404);
        }

        // Tingkatkan jumlah kunjungan (visits) beasiswa ini
        try {
            $scholarship->increment('visits');
        } catch (\Throwable $e) {
            // Abaikan error jika kolom belum siap
        }

        // Render halaman detail dengan data beasiswa dan info tambahan statis (syarat, manfaat, dll.)
        return view('pages.scholarship-detail', [
            'scholarship' => $scholarship->toArray(),
            'extra'       => ScholarshipData::extra($id), // Info tambahan seperti syarat & manfaat dari data statis
        ]);
    }

    /**
     * Menampilkan halaman formulir lamaran pendaftaran beasiswa.
     *
     * @param  string  $id  ID unik beasiswa yang ingin didaftar.
     * @return View Template blade 'pages.register' atau abort(404) jika tidak ditemukan.
     */
    public function register(string $id): View
    {
        // Cari beasiswa berdasarkan ID; pastikan valid sebelum menampilkan form pendaftaran
        $scholarship = Scholarship::find($id);

        // Tampilkan halaman 404 jika beasiswa tidak ditemukan
        if (! $scholarship) {
            abort(404);
        }

        // Render template formulir pendaftaran dengan data beasiswa dan info tambahan
        return view('pages.register', [
            'scholarship' => $scholarship->toArray(),
            'extra'       => ScholarshipData::extra($id), // Info tambahan untuk ditampilkan di form
        ]);
    }

    /**
     * Memproses pengiriman formulir lamaran pendaftaran beasiswa (POST).
     *
     * Melakukan validasi berkas dokumen (ukuran, ekstensi), menyimpannya di storage,
     * lalu menyimpan record lamaran ke database.
     *
     * @param  Request  $request  Data pendaftaran lengkap termasuk file upload dokumen.
     * @return JsonResponse       Respons sukses dengan data lamaran atau error validasi.
     */
    public function submitRegistration(Request $request): JsonResponse
    {
        // Validasi semua field formulir pendaftaran beasiswa
        $data = $request->validate([
            'scholarship_id'    => 'required|string',           // ID beasiswa yang didaftar
            'scholarship_title' => 'required|string',           // Nama beasiswa yang didaftar
            'full_name'         => 'required|string',           // Nama lengkap pendaftar
            'nik'               => 'required|string|size:16',   // NIK KTP (16 digit)
            'email'             => 'required|email',            // Email aktif pendaftar
            'phone'             => 'required|string',           // Nomor telepon pendaftar
            'birth_date'        => 'required|date',             // Tanggal lahir pendaftar
            'gender'            => 'required|string',           // Jenis kelamin pendaftar
            'address'           => 'required|string',           // Alamat lengkap pendaftar
            'applied_level'     => 'required|string',           // Jenjang yang dilamar (S1/S2/S3)
            'university'        => 'required|string',           // Universitas asal pendaftar
            'major'             => 'required|string',           // Jurusan/program studi pendaftar
            'gpa'               => 'required|string',           // IPK pendaftar
            'english_score'     => 'nullable|string',           // Skor bahasa Inggris (TOEFL/IELTS) — opsional
            'target_university' => 'nullable|string',           // Universitas tujuan — opsional
            'ktp'               => 'required|file|max:2048',    // File KTP wajib, maksimal 2MB
            'ijazah'            => 'required|file|max:2048',    // File Ijazah wajib, maksimal 2MB
            'transcript'        => 'required|file|max:2048',    // File Transkrip wajib, maksimal 2MB
            'cv'                => 'nullable|file|max:2048',    // File CV opsional, maksimal 2MB
            'motivation'        => 'required|string|min:50',    // Motivation letter minimal 50 karakter
        ]);

        // Simpan masing-masing berkas yang diunggah ke storage/app/public/uploads/
        $ktpPath        = $request->file('ktp')->store('uploads', 'public');
        $ijazahPath     = $request->file('ijazah')->store('uploads', 'public');
        $transcriptPath = $request->file('transcript')->store('uploads', 'public');

        // CV bersifat opsional — simpan hanya jika ada yang diunggah
        $cvPath = $request->hasFile('cv')
            ? $request->file('cv')->store('uploads', 'public')
            : null;

        // Simpan record lamaran beasiswa ke database dengan semua data tervalidasi
        $application = ScholarshipApplication::create([
            'scholarship_id'    => $data['scholarship_id'],
            'scholarship_title' => $data['scholarship_title'],
            'full_name'         => $data['full_name'],
            'nik'               => $data['nik'],
            'email'             => $data['email'],
            'phone'             => $data['phone'],
            'birth_date'        => $data['birth_date'],
            'gender'            => $data['gender'],
            'address'           => $data['address'],
            'applied_level'     => $data['applied_level'],
            'university'        => $data['university'],
            'major'             => $data['major'],
            'gpa'               => $data['gpa'],
            'english_score'     => $data['english_score'],
            'target_university' => $data['target_university'],
            'ktp_path'          => $ktpPath,         // Path file KTP di storage
            'ijazah_path'       => $ijazahPath,      // Path file Ijazah di storage
            'transcript_path'   => $transcriptPath,  // Path file Transkrip di storage
            'cv_path'           => $cvPath,          // Path file CV di storage (null jika tidak diupload)
            'motivation'        => $data['motivation'],
        ]);

        // Kembalikan respons sukses beserta data lamaran yang baru disimpan
        return response()->json([
            'success'     => true,
            'message'     => 'Pendaftaran berhasil dikirim!',
            'application' => $application,
        ]);
    }

    /**
     * Menampilkan halaman Tutorial langkah-demi-langkah pendaftaran beasiswa.
     *
     * @return View Template blade 'pages.tutorial' beserta data langkah-langkah tutorial.
     */
    public function tutorial(): View
    {
        // Render template tutorial dengan data langkah statis dari ScholarshipData
        return view('pages.tutorial', [
            'steps' => ScholarshipData::tutorialSteps(), // Array langkah-langkah tutorial statis
        ]);
    }

    /**
     * Menampilkan Dashboard Admin (Panel Manajemen).
     *
     * Mengambil semua data yang dibutuhkan panel admin: beasiswa, iklan, akun admin,
     * akun pengguna, item carousel, dan informasi konfigurasi PHP upload.
     *
     * @return View Template blade 'pages.admin' beserta semua data manajemen.
     */
    public function admin(): View
    {
        // Inisialisasi semua variabel dengan array kosong sebagai default
        $scholarships  = [];
        $adBanners     = [];
        $admins        = [];
        $users         = [];
        $carouselItems = [];

        try {
            // Ambil semua data beasiswa dari database
            $scholarships = Scholarship::all()->toArray();

            // Ambil semua data spanduk iklan dari database
            $adBanners = AdBanner::all()->toArray();

            // Ambil semua akun dengan role 'admin' dari database
            $admins = User::where('role', 'admin')->get()->toArray();

            // Ambil semua akun dengan role 'user' (pengguna biasa) dari database
            $users = User::where('role', 'user')->get()->toArray();

            // Ambil item carousel yang diurutkan berdasarkan order_index
            $carouselItems = CarouselItem::orderBy('order_index', 'asc')->get()->toArray();
        } catch (\Throwable $e) {
            // Jika ada error query, reset semua variabel ke array kosong
            $scholarships  = [];
            $adBanners     = [];
            $admins        = [];
            $users         = [];
            $carouselItems = [];
        }

        $accountActiveDays = \App\Models\Setting::get('account_active_days', 30);

        // Render template panel admin dengan semua data yang diperlukan
        return view('pages.admin', [
            'scholarships'      => $scholarships,  // Data beasiswa untuk tabel manajemen
            'adBanners'         => $adBanners,     // Data iklan untuk tabel manajemen
            'admins'            => $admins,        // Data akun admin untuk tabel manajemen
            'users'             => $users,         // Data akun pengguna untuk tabel manajemen
            'carouselItems'     => $carouselItems, // Data slide carousel untuk tabel manajemen
            'accountActiveDays' => (int) $accountActiveDays, // Pengaturan global batas masa aktif akun
            'phpUploadMax'      => $phpUploadMax,  // Batas upload PHP (ditampilkan sebagai info di UI)
            'phpPostMax'        => $phpPostMax,    // Batas POST PHP (ditampilkan sebagai info di UI)
        ]);
    }
}

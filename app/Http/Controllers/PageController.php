<?php

namespace App\Http\Controllers;

use App\Data\ScholarshipData;
use App\Models\Scholarship;
use App\Models\AdBanner;
use App\Models\ScholarshipApplication;
use App\Models\User;
use App\Models\Bookmark;
use App\Models\CarouselItem; // Model carousel item dinamis - Request 2
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

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
        $ads = [];
        $scholarships = [];
        $carouselItems = [];

        try {
            $ads = AdBanner::limit(4)->get()->toArray();
            if (count($ads) < 4) {
                // Tambahkan fallback iklan jika di database kurang dari 4
                $rawAds = ScholarshipData::adBanners();
                $ads = array_slice($rawAds, 0, 4);
            }
        } catch (\Throwable $e) {
            $ads = array_slice(ScholarshipData::adBanners(), 0, 4);
        }

        try {
            $scholarships = Scholarship::limit(5)->get()->toArray();
            if (count($scholarships) < 5) {
                // Tambahkan fallback beasiswa jika di database kurang dari 5
                $rawScholarships = ScholarshipData::all();
                $scholarships = array_slice($rawScholarships, 0, 5);
            }
        } catch (\Throwable $e) {
            $scholarships = array_slice(ScholarshipData::all(), 0, 5);
        }

        try {
            $carouselItems = CarouselItem::with('scholarship')->orderBy('order_index', 'asc')->get()->toArray();
        } catch (\Throwable $e) {
            $carouselItems = [];
        }

        return view('pages.landing', [
            'ads' => $ads,
            'scholarships' => $scholarships,
            'carouselItems' => $carouselItems,
        ]);
    }

    /**
     * Menampilkan halaman Beranda (Home) yang berisi slider iklan aktif
     * serta daftar beasiswa unggulan yang diambil dari database.
     */
    public function home(): View
    {
        $scholarships = [];
        $carouselItems = [];
        $topAds = [];
        $bottomAds = [];

        try {
            $scholarships = Scholarship::all()->toArray();
            // Ambil item carousel terurut sesuai order_index - Request 2
            $carouselItems = CarouselItem::with('scholarship')->orderBy('order_index', 'asc')->get()->toArray();
            // Pisahkan iklan atas dan bawah - Request 5
            $topAds = AdBanner::where('position', 'top')->get()->toArray();
            $bottomAds = AdBanner::where('position', 'bottom')->get()->toArray();
        } catch (\Throwable $e) {
            $scholarships = [];
            $carouselItems = [];
            $topAds = [];
            $bottomAds = [];
        }

        return view('pages.home', [
            'scholarships' => $scholarships,
            'carouselItems' => $carouselItems,
            'topAds' => $topAds,
            'bottomAds' => $bottomAds,
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
        $scholarships = [];

        try {
            $scholarships = Scholarship::all()->toArray();
        } catch (\Throwable $e) {
            $scholarships = [];
        }

        return view('pages.library', [
            'scholarships' => $scholarships,
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
        $scholarships = [];

        try {
            $scholarships = Scholarship::all()->toArray();
        } catch (\Throwable $e) {
            $scholarships = [];
        }

        return view('pages.dashboard', [
            'scholarships' => $scholarships,
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
     * Mengambil data beasiswa, iklan, akun admin, dan akun pengguna.
     */
    public function admin(): View
    {
        $scholarships = [];
        $adBanners = [];
        $admins = [];
        $users = [];
        $carouselItems = [];

        try {
            $scholarships = Scholarship::all()->toArray();
            $adBanners = AdBanner::all()->toArray();
            $admins = User::where('role', 'admin')->get()->toArray();
            $users = User::where('role', 'user')->get()->toArray();
            $carouselItems = CarouselItem::orderBy('order_index', 'asc')->get()->toArray();
        } catch (\Throwable $e) {
            $scholarships = [];
            $adBanners = [];
            $admins = [];
            $users = [];
            $carouselItems = [];
        }

        $phpUploadMax = ini_get('upload_max_filesize');
        $phpPostMax = ini_get('post_max_size');

        return view('pages.admin', [
            'scholarships' => $scholarships,
            'adBanners' => $adBanners,
            'admins' => $admins,
            'users' => $users,
            'carouselItems' => $carouselItems,
            'phpUploadMax' => $phpUploadMax,
            'phpPostMax' => $phpPostMax,
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
        // Validasi field iklan; image_url bersifat opsional (nullable), position wajib diisi - Request 5
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
            'position'    => 'required|string|in:top,bottom', // Penempatan iklan - Request 5
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

        // Validasi field iklan; position wajib diisi - Request 5
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
            'position'    => 'required|string|in:top,bottom', // Penempatan iklan - Request 5
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
        // Validasi: berkas wajib ada, berupa gambar atau video singkat (mp4/webm/mov), maks 15MB
        $request->validate([
            'image' => 'required|file|mimes:jpeg,png,jpg,gif,svg,mp4,webm,ogg,mov|max:15360',
        ]);

        // Simpan file ke storage/app/public/ad-images/ dengan nama acak yang aman
        $path = $request->file('image')->store('ad-images', 'public');

        // Kembalikan URL publik yang bisa langsung digunakan di tag <img> atau <video>
        return response()->json([
            'success'   => true,
            'url'       => asset('storage/' . $path),
            'path'      => $path,
        ]);
    }

    /**
     * Streaming video carousel dengan dukungan HTTP Range Request (206 Partial Content).
     * Inilah yang memungkinkan browser untuk seek/skip video ke menit manapun.
     * Tanpa handler ini, artisan serve tidak mengembalikan header Range yang benar.
     */
    public function streamCarouselVideo(Request $request, string $filename): StreamedResponse|\Illuminate\Http\Response
    {
        // Sanitasi nama file untuk keamanan — tolak path traversal
        $filename = basename($filename);
        $filePath = storage_path('app/public/carousel-videos/' . $filename);

        if (!file_exists($filePath) || !is_file($filePath)) {
            abort(404, 'Video tidak ditemukan.');
        }

        $fileSize   = filesize($filePath);
        $mimeType   = mime_content_type($filePath) ?: 'video/mp4';
        // Pastikan mime type benar untuk video
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        $mimeMap = [
            'mp4'  => 'video/mp4',
            'webm' => 'video/webm',
            'ogg'  => 'video/ogg',
            'mov'  => 'video/quicktime',
            'avi'  => 'video/x-msvideo',
        ];
        if (isset($mimeMap[$ext])) {
            $mimeType = $mimeMap[$ext];
        }

        $rangeHeader = $request->header('Range');

        if (!$rangeHeader) {
            // Tidak ada Range header — kirim seluruh file dengan header Accept-Ranges
            return response()->stream(function () use ($filePath, $fileSize) {
                $handle = fopen($filePath, 'rb');
                while (!feof($handle)) {
                    echo fread($handle, 1024 * 64);
                    flush();
                    ob_flush();
                }
                fclose($handle);
            }, 200, [
                'Content-Type'   => $mimeType,
                'Content-Length' => $fileSize,
                'Accept-Ranges'  => 'bytes',
                'Cache-Control'  => 'public, max-age=3600',
            ]);
        }

        // Parse Range header: "bytes=START-END"
        preg_match('/bytes=(\d+)-(\d*)/', $rangeHeader, $matches);
        $start = isset($matches[1]) ? (int) $matches[1] : 0;
        $end   = isset($matches[2]) && $matches[2] !== '' ? (int) $matches[2] : $fileSize - 1;
        $end   = min($end, $fileSize - 1);

        if ($start > $end || $start >= $fileSize) {
            return response('Requested Range Not Satisfiable', 416, [
                'Content-Range' => "bytes */{$fileSize}",
            ]);
        }

        $length = $end - $start + 1;

        // Kembalikan Partial Content (206) dengan potongan byte yang diminta
        return response()->stream(function () use ($filePath, $start, $length) {
            $handle = fopen($filePath, 'rb');
            fseek($handle, $start);
            $remaining = $length;
            while (!feof($handle) && $remaining > 0) {
                $chunkSize = min(1024 * 64, $remaining); // 64KB per chunk
                $data = fread($handle, $chunkSize);
                echo $data;
                $remaining -= strlen($data);
                flush();
                ob_flush();
            }
            fclose($handle);
        }, 206, [
            'Content-Type'   => $mimeType,
            'Content-Range'  => "bytes {$start}-{$end}/{$fileSize}",
            'Content-Length' => $length,
            'Accept-Ranges'  => 'bytes',
            'Cache-Control'  => 'public, max-age=3600',
        ]);
    }

    /**
     * Mengupload file video lokal untuk slide carousel ke direktori storage/public/carousel-videos (Request 7).
     * Mendukung upload biasa (untuk file kecil) dan chunked upload (untuk file besar).
     * Mengembalikan URL publik file yang disimpan ke database.
     */
    public function uploadCarouselVideo(Request $request): JsonResponse
    {
        // Validasi: berkas wajib ada, berupa video (mp4/webm/mov/avi/ogg), maks sesuai php.ini
        $request->validate([
            'video' => 'required|file|mimes:mp4,webm,ogg,mov,avi',
        ]);

        // Simpan file ke storage/app/public/carousel-videos/
        $path = $request->file('video')->store('carousel-videos', 'public');

        // Kembalikan URL publik berkas video
        return response()->json([
            'success'   => true,
            'url'       => asset('storage/' . $path),
            'path'      => $path,
        ]);
    }

    /**
     * Menerima satu potongan (chunk) video dan menyimpannya sementara,
     * lalu menggabungkan seluruh potongan menjadi satu file utuh ketika semua chunk diterima.
     * Ini memungkinkan upload video berukuran sangat besar (melebihi upload_max_filesize PHP)
     * karena setiap request hanya membawa potongan kecil (~2MB).
     */
    public function uploadCarouselVideoChunk(Request $request): JsonResponse
    {
        // Validasi potongan chunk yang diterima
        $request->validate([
            'chunk'        => 'required|file',
            'chunk_index'  => 'required|integer|min:0',
            'total_chunks' => 'required|integer|min:1',
            'filename'     => 'required|string',
            'upload_id'    => 'required|string',
        ]);

        $chunkIndex  = (int) $request->input('chunk_index');
        $totalChunks = (int) $request->input('total_chunks');
        $uploadId    = preg_replace('/[^a-zA-Z0-9_-]/', '', $request->input('upload_id'));
        $originalName = $request->input('filename');

        // Validasi ekstensi file asli
        $ext = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
        $allowedExt = ['mp4', 'webm', 'ogg', 'mov', 'avi'];
        if (!in_array($ext, $allowedExt)) {
            return response()->json([
                'success' => false,
                'message' => 'Format video tidak didukung. Gunakan MP4, WebM, OGG, MOV, atau AVI.',
            ], 422);
        }

        // Direktori sementara untuk menyimpan chunk
        $tempDir = storage_path('app/chunks/' . $uploadId);
        if (!file_exists($tempDir)) {
            mkdir($tempDir, 0755, true);
        }

        // Simpan chunk ke direktori temp
        $chunkPath = $tempDir . '/chunk_' . $chunkIndex;
        $request->file('chunk')->move($tempDir, 'chunk_' . $chunkIndex);

        // Cek apakah semua chunk sudah diterima
        $receivedChunks = count(glob($tempDir . '/chunk_*'));
        if ($receivedChunks < $totalChunks) {
            // Belum semua chunk diterima, beritahu klien untuk lanjut kirim
            return response()->json([
                'success'     => true,
                'done'        => false,
                'received'    => $receivedChunks,
                'total'       => $totalChunks,
                'message'     => "Chunk {$chunkIndex} diterima ({$receivedChunks}/{$totalChunks}).",
            ]);
        }

        // Semua chunk sudah diterima — gabungkan menjadi satu file
        $finalFilename = $uploadId . '.' . $ext;
        $finalDir = storage_path('app/public/carousel-videos');
        if (!file_exists($finalDir)) {
            mkdir($finalDir, 0755, true);
        }
        $finalPath = $finalDir . '/' . $finalFilename;

        $outputFile = fopen($finalPath, 'wb');
        for ($i = 0; $i < $totalChunks; $i++) {
            $chunkFile = $tempDir . '/chunk_' . $i;
            if (!file_exists($chunkFile)) {
                fclose($outputFile);
                // Hapus file yang sudah dibuat jika gagal
                if (file_exists($finalPath)) unlink($finalPath);
                return response()->json([
                    'success' => false,
                    'message' => "Potongan video ke-{$i} hilang. Silakan upload ulang.",
                ], 500);
            }
            $chunkHandle = fopen($chunkFile, 'rb');
            stream_copy_to_stream($chunkHandle, $outputFile);
            fclose($chunkHandle);
        }
        fclose($outputFile);

        // Hapus direktori chunk sementara
        array_map('unlink', glob($tempDir . '/chunk_*'));
        rmdir($tempDir);

        $publicPath = 'carousel-videos/' . $finalFilename;

        return response()->json([
            'success' => true,
            'done'    => true,
            'url'     => asset('storage/' . $publicPath),
            'path'    => $publicPath,
            'message' => 'Video berhasil diunggah!',
        ]);
    }

    /**
     * Mengupload file logo/gambar beasiswa ke direktori storage/public/scholarship-logos.
     * Mengembalikan URL publik file yang disimpan ke database.
     */
    public function uploadScholarshipImage(Request $request): JsonResponse
    {
        // Validasi file logo beasiswa (harus berupa gambar, maksimal 2MB)
        $request->validate([
            'image' => 'required|image|max:2048',
        ]);

        // Simpan file ke storage/app/public/scholarship-logos/
        $path = $request->file('image')->store('scholarship-logos', 'public');

        return response()->json([
            'success' => true,
            'url'     => asset('storage/' . $path),
            'path'    => $path,
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
     * Menyimpan data akun admin baru ke database (POST) - Tugas 9.
     */
    public function storeAdmin(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => 'required|string|min:3',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
        ]);

        // Simpan admin dengan role 'admin'
        $admin = User::create([
            'name' => trim($data['name']),
            'email' => trim($data['email']),
            'password' => Hash::make($data['password']),
            'role' => 'admin',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Akun admin berhasil ditambahkan!',
            'data' => [
                'id' => $admin->id,
                'name' => $admin->name,
                'email' => $admin->email,
                'role' => $admin->role,
            ]
        ]);
    }

    /**
     * Memperbarui data akun admin berdasarkan ID (PUT/PATCH) - Tugas 9.
     */
    public function updateAdmin(Request $request, int $id): JsonResponse
    {
        // Cari admin berdasarkan ID dan role 'admin'
        $admin = User::where('id', $id)->where('role', 'admin')->first();
        if (!$admin) {
            return response()->json(['success' => false, 'message' => 'Akun admin tidak ditemukan.'], 404);
        }

        $data = $request->validate([
            'name' => 'required|string|min:3',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|string|min:6',
        ]);

        $admin->name = trim($data['name']);
        $admin->email = trim($data['email']);
        
        // Perbarui password hanya jika diinputkan
        if (!empty($data['password'])) {
            $admin->password = Hash::make($data['password']);
        }

        $admin->save();

        return response()->json([
            'success' => true,
            'message' => 'Akun admin berhasil diperbarui!',
            'data' => [
                'id' => $admin->id,
                'name' => $admin->name,
                'email' => $admin->email,
                'role' => $admin->role,
            ]
        ]);
    }

    /**
     * Menghapus data akun admin berdasarkan ID (DELETE) - Tugas 9.
     */
    public function deleteAdmin(int $id): JsonResponse
    {
        // Mencegah penghapusan akun diri sendiri yang sedang login
        if (Auth::id() === $id) {
            return response()->json(['success' => false, 'message' => 'Anda tidak dapat menghapus akun Anda sendiri yang sedang digunakan.'], 400);
        }

        $admin = User::where('id', $id)->where('role', 'admin')->first();
        if (!$admin) {
            return response()->json(['success' => false, 'message' => 'Akun admin tidak ditemukan.'], 404);
        }

        $admin->delete();

        return response()->json([
            'success' => true,
            'message' => 'Akun admin berhasil dihapus!'
        ]);
    }

    /**
     * Menyimpan data item carousel baru (POST) - Request 2.
     */
    public function storeCarouselItem(Request $request): JsonResponse
    {
        $data = $request->validate([
            'type'           => 'required|string|in:scholarship,video',
            'scholarship_id' => 'nullable|integer|exists:scholarships,id',
            'title'          => 'nullable|string',
            'subtitle'       => 'nullable|string',
            'description'    => 'nullable|string',
            'image_url'      => 'nullable|string',
            'video_url'      => 'nullable|string',
            'link'           => 'nullable|string',
            'order_index'    => 'nullable|integer',
        ]);

        $item = CarouselItem::create($data);

        // Jika bertipe beasiswa, pastikan relasi termuat
        if ($item->type === 'scholarship') {
            $item->load('scholarship');
        }

        return response()->json([
            'success' => true,
            'message' => 'Slide carousel berhasil ditambahkan!',
            'data'    => $item
        ]);
    }

    /**
     * Memperbarui data item carousel berdasarkan ID (PUT/PATCH) - Request 2.
     */
    public function updateCarouselItem(Request $request, int $id): JsonResponse
    {
        $item = CarouselItem::find($id);
        if (!$item) {
            return response()->json(['success' => false, 'message' => 'Slide tidak ditemukan.'], 404);
        }

        $data = $request->validate([
            'type'           => 'required|string|in:scholarship,video',
            'scholarship_id' => 'nullable|integer|exists:scholarships,id',
            'title'          => 'nullable|string',
            'subtitle'       => 'nullable|string',
            'description'    => 'nullable|string',
            'image_url'      => 'nullable|string',
            'video_url'      => 'nullable|string',
            'link'           => 'nullable|string',
            'order_index'    => 'nullable|integer',
        ]);

        $item->update($data);

        if ($item->type === 'scholarship') {
            $item->load('scholarship');
        }

        return response()->json([
            'success' => true,
            'message' => 'Slide carousel berhasil diperbarui!',
            'data'    => $item
        ]);
    }

    /**
     * Menghapus data item carousel berdasarkan ID (DELETE) - Request 2.
     */
    public function deleteCarouselItem(int $id): JsonResponse
    {
        $item = CarouselItem::find($id);
        if (!$item) {
            return response()->json(['success' => false, 'message' => 'Slide tidak ditemukan.'], 404);
        }

        $item->delete();

        return response()->json([
            'success' => true,
            'message' => 'Slide carousel berhasil dihapus!'
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
            // Login user ke session Laravel
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
     * Menyimpan data pengguna baru ke dalam tabel users dengan role 'user'.
     */
    public function apiRegister(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => 'required|string|min:3',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
        ]);

        // Menyimpan data pengguna baru ke database tanpa otomatis login
        $user = User::create([
            'name' => trim($data['name']),
            'email' => trim($data['email']),
            'password' => Hash::make($data['password']),
            'role' => 'user', // Akun baru memiliki role 'user'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Registrasi berhasil! Silakan masuk menggunakan akun baru Anda.',
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

    /**
     * API Update Profil dan Ganti Password Pengguna Terintegrasi DB (Tugas 10).
     */
    public function updateProfile(Request $request): JsonResponse
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Sesi Anda telah berakhir. Silakan login kembali.'], 401);
        }

        $data = $request->validate([
            'name' => 'required|string|min:3',
            'current_password' => 'nullable|string',
            'new_password' => 'nullable|string|min:6',
        ]);

        $user->name = trim($data['name']);

        // Jika pengguna ingin mengganti kata sandinya
        if (!empty($data['current_password'])) {
            // Verifikasi kecocokan password lama di database
            if (!Hash::check($data['current_password'], $user->password)) {
                return response()->json(['success' => false, 'message' => 'Kata sandi saat ini yang Anda masukkan salah.'], 422);
            }

            if (empty($data['new_password'])) {
                return response()->json(['success' => false, 'message' => 'Silakan masukkan kata sandi baru Anda.'], 422);
            }

            $user->password = Hash::make($data['new_password']);
        }

        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Profil dan kata sandi berhasil diperbarui!',
            'user' => [
                'name' => $user->name,
                'email' => $user->email,
            ]
        ]);
    }
}

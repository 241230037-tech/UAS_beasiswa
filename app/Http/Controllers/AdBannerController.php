<?php

/**
 * File: app/Http/Controllers/AdBannerController.php
 *
 * Controller ini bertanggung jawab atas seluruh operasi CRUD (Create, Read, Update, Delete)
 * data Spanduk Iklan (Ad Banner) yang dilakukan oleh Administrator melalui panel admin.
 * Mengelola iklan yang tampil di posisi atas (top) atau bawah (bottom) halaman.
 */

namespace App\Http\Controllers;

use App\Models\AdBanner;             // Model Eloquent untuk tabel ad_banners
use Illuminate\Http\JsonResponse;    // Tipe return untuk respons JSON ke frontend
use Illuminate\Http\Request;         // Objek request yang berisi data inputan dan file upload
use Illuminate\Support\Facades\Storage; // Facade Laravel untuk manipulasi file di storage disk

class AdBannerController extends Controller
{
    /**
     * Menyimpan data Spanduk Iklan baru ke database.
     *
     * Endpoint: POST /admin/ads
     * Dipanggil dari form tambah iklan di panel admin.
     *
     * @param  Request  $request  Data iklan yang dikirim dari form modal admin.
     * @return JsonResponse       Respons sukses/gagal dalam format JSON.
     */
    public function store(Request $request): JsonResponse
    {
        // Validasi semua field iklan; position wajib 'top' atau 'bottom'
        $data = $request->validate([
            'title'       => 'required|string',                   // Judul utama iklan
            'subtitle'    => 'required|string',                   // Sub-judul iklan
            'description' => 'required|string',                   // Deskripsi singkat iklan
            'cta_text'    => 'required|string',                   // Teks tombol Call-To-Action (misal: "Daftar Sekarang")
            'bg_from'     => 'required|string',                   // Warna awal gradient background (hex/Tailwind class)
            'bg_to'       => 'required|string',                   // Warna akhir gradient background
            'tag'         => 'required|string',                   // Label/tag kategori iklan (misal: "PROMO")
            'link'        => 'required|string',                   // URL tujuan saat iklan diklik
            'image_url'   => 'nullable|string',                   // URL gambar latar iklan (diisi otomatis dari upload)
            'position'    => 'required|string|in:top,bottom',     // Posisi tampil: 'top' (atas) atau 'bottom' (bawah)
        ]);

        // Buat record iklan baru di database menggunakan mass-assignment
        $ad = AdBanner::create($data);

        // Kembalikan respons sukses beserta data iklan yang baru dibuat
        return response()->json([
            'success' => true,
            'message' => 'Iklan berhasil ditambahkan!',
            'data'    => $ad,
        ]);
    }

    /**
     * Memperbarui data Spanduk Iklan yang sudah ada berdasarkan ID.
     *
     * Endpoint: PUT /admin/ads/{id}
     * Dipanggil dari form edit iklan di panel admin.
     *
     * @param  Request  $request  Data iklan yang diperbarui dari form modal edit.
     * @param  int      $id       ID iklan yang akan diperbarui.
     * @return JsonResponse       Respons sukses/gagal dalam format JSON.
     */
    public function update(Request $request, int $id): JsonResponse
    {
        // Cari iklan berdasarkan ID; kembalikan 404 jika tidak ditemukan
        $ad = AdBanner::find($id);
        if (! $ad) {
            return response()->json([
                'success' => false,
                'message' => 'Iklan tidak ditemukan.',
            ], 404);
        }

        // Validasi field yang boleh diperbarui; position tetap wajib 'top' atau 'bottom'
        $data = $request->validate([
            'title'       => 'required|string',                   // Judul utama iklan
            'subtitle'    => 'required|string',                   // Sub-judul iklan
            'description' => 'required|string',                   // Deskripsi singkat iklan
            'cta_text'    => 'required|string',                   // Teks tombol Call-To-Action
            'bg_from'     => 'required|string',                   // Warna awal gradient background
            'bg_to'       => 'required|string',                   // Warna akhir gradient background
            'tag'         => 'required|string',                   // Label/tag kategori iklan
            'link'        => 'required|string',                   // URL tujuan klik iklan
            'image_url'   => 'nullable|string',                   // URL gambar latar (opsional saat update)
            'position'    => 'required|string|in:top,bottom',     // Posisi tampil iklan
        ]);

        // Perbarui data iklan di database
        $ad->update($data);

        // Kembalikan respons sukses beserta data iklan terbaru
        return response()->json([
            'success' => true,
            'message' => 'Iklan berhasil diperbarui!',
            'data'    => $ad,
        ]);
    }

    /**
     * Menghapus data Spanduk Iklan dari database beserta file gambarnya (jika ada).
     *
     * Endpoint: DELETE /admin/ads/{id}
     * Dipanggil dari tombol hapus di tabel manajemen iklan panel admin.
     *
     * @param  int  $id  ID iklan yang akan dihapus.
     * @return JsonResponse Respons sukses/gagal dalam format JSON.
     */
    public function destroy(int $id): JsonResponse
    {
        // Cari iklan berdasarkan ID; kembalikan 404 jika tidak ditemukan
        $ad = AdBanner::find($id);
        if (! $ad) {
            return response()->json([
                'success' => false,
                'message' => 'Iklan tidak ditemukan.',
            ], 404);
        }

        // Jika iklan memiliki gambar yang pernah diupload, hapus juga file fisiknya dari storage
        if ($ad->image_url) {
            // Ambil path relatif dari URL publik dengan memotong bagian '/storage/'
            $relativePath = str_replace('/storage/', '', parse_url($ad->image_url, PHP_URL_PATH));

            // Hapus file dari disk 'public' (storage/app/public/)
            Storage::disk('public')->delete($relativePath);
        }

        // Hapus record iklan dari database
        $ad->delete();

        // Kembalikan respons sukses tanpa data
        return response()->json([
            'success' => true,
            'message' => 'Iklan berhasil dihapus!',
        ]);
    }

    /**
     * Mengupload file gambar/media untuk Spanduk Iklan ke storage server.
     *
     * Endpoint: POST /admin/ads/upload-image
     *
     * Cara kerja:
     *  1. Admin memilih file dari form modal iklan.
     *  2. File dikirim via POST multipart/form-data ke endpoint ini.
     *  3. File disimpan di storage/app/public/ad-images/ (dapat diakses publik via symlink).
     *  4. URL publik dikembalikan sebagai JSON.
     *  5. URL tersebut otomatis diisi ke field image_url saat form di-submit.
     *
     * @param  Request  $request  Request yang mengandung file gambar/video iklan.
     * @return JsonResponse       URL publik dan path relatif file yang diunggah.
     */
    public function uploadImage(Request $request): JsonResponse
    {
        // Validasi: file wajib ada, berupa gambar atau video singkat, maksimal 15MB
        $request->validate([
            'image' => 'required|file|mimes:jpeg,png,jpg,gif,svg,mp4,webm,ogg,mov|max:15360',
        ]);

        // Simpan file ke storage/app/public/ad-images/ dengan nama acak yang aman
        $path = $request->file('image')->store('ad-images', 'public');

        // Kembalikan URL publik yang bisa langsung digunakan di tag <img> atau <video>
        return response()->json([
            'success' => true,
            'url'     => asset('storage/' . $path),  // URL lengkap yang dapat diakses publik
            'path'    => $path,                       // Path relatif untuk referensi internal
        ]);
    }
}

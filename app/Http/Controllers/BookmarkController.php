<?php

/**
 * File: app/Http/Controllers/BookmarkController.php
 *
 * Controller ini bertanggung jawab atas operasi Bookmark Beasiswa:
 * mengambil daftar bookmark milik pengguna yang sedang login, dan
 * toggle (tambah/hapus) bookmark beasiswa tertentu.
 * Mengharuskan pengguna sudah terautentikasi (login) sebelum dapat menggunakan fitur ini.
 */

namespace App\Http\Controllers;

use App\Models\Bookmark;             // Model Eloquent untuk tabel bookmarks
use Illuminate\Http\JsonResponse;   // Tipe return untuk respons JSON ke frontend
use Illuminate\Http\Request;        // Objek request yang berisi data inputan
use Illuminate\Support\Facades\Auth; // Facade untuk mengakses data user yang sedang login

class BookmarkController extends Controller
{
    /**
     * Mengambil daftar ID beasiswa yang telah dibookmark oleh pengguna yang sedang login.
     *
     * Endpoint: GET /api/bookmarks
     * Dipanggil dari frontend saat halaman dimuat untuk menentukan ikon bookmark aktif/tidak aktif.
     *
     * @return JsonResponse Array ID beasiswa yang dibookmark, atau 401 jika belum login.
     */
    public function index(): JsonResponse
    {
        // Dapatkan user yang sedang login via session Laravel
        $user = Auth::user();

        // Kembalikan array kosong dengan status 401 jika pengguna belum login
        if (! $user) {
            return response()->json([], 401);
        }

        // Ambil semua scholarship_id yang telah dibookmark oleh user ini (hanya ID-nya saja)
        $bookmarks = Bookmark::where('user_id', $user->id) // Filter berdasarkan user yang login
                             ->pluck('scholarship_id');    // Ambil hanya kolom scholarship_id sebagai array

        // Kembalikan array ID beasiswa sebagai JSON (misal: ["1", "3", "7"])
        return response()->json($bookmarks);
    }

    /**
     * Toggle bookmark beasiswa: tambahkan jika belum ada, hapus jika sudah ada.
     *
     * Endpoint: POST /api/bookmarks/toggle
     * Dipanggil dari tombol ikon bookmark di kartu beasiswa.
     *
     * @param  Request  $request  Data yang berisi scholarship_id yang akan di-toggle.
     * @return JsonResponse       Status operasi ('added' atau 'removed') beserta pesan.
     */
    public function toggle(Request $request): JsonResponse
    {
        // Dapatkan user yang sedang login via session Laravel
        $user = Auth::user();

        // Kembalikan error 401 jika pengguna belum login
        if (! $user) {
            return response()->json([
                'success' => false,
                'message' => 'Silakan login terlebih dahulu.',
            ], 401);
        }

        // Validasi bahwa scholarship_id dikirim dan ada di database scholarships
        $data = $request->validate([
            'scholarship_id' => 'required|string|exists:scholarships,id', // ID harus ada di tabel scholarships
        ]);

        $scholarshipId = $data['scholarship_id']; // ID beasiswa yang akan di-toggle

        // Cari apakah bookmark untuk user + beasiswa ini sudah ada
        $bookmark = Bookmark::where('user_id', $user->id)
                            ->where('scholarship_id', $scholarshipId)
                            ->first();

        // Jika bookmark sudah ada → hapus (un-bookmark)
        if ($bookmark) {
            $bookmark->delete(); // Hapus record bookmark dari database

            return response()->json([
                'success' => true,
                'status'  => 'removed',                              // Status untuk frontend
                'message' => 'Beasiswa dihapus dari Bookmark.',
            ]);
        }

        // Jika bookmark belum ada → tambahkan (bookmark)
        Bookmark::create([
            'user_id'        => $user->id,     // ID pengguna yang melakukan bookmark
            'scholarship_id' => $scholarshipId, // ID beasiswa yang di-bookmark
        ]);

        return response()->json([
            'success' => true,
            'status'  => 'added',                                    // Status untuk frontend
            'message' => 'Beasiswa ditambahkan ke Bookmark!',
        ]);
    }
}

<?php

/**
 * File: app/Http/Controllers/ScholarshipController.php
 *
 * Controller ini bertanggung jawab atas seluruh operasi CRUD (Create, Read, Update, Delete)
 * data Beasiswa yang dilakukan oleh Administrator melalui panel admin.
 * Memisahkan logika beasiswa dari PageController agar lebih modular dan mudah dirawat.
 */

namespace App\Http\Controllers;

use App\Models\Scholarship;          // Model Eloquent untuk tabel scholarships
use Illuminate\Http\JsonResponse;    // Tipe return untuk respons JSON ke frontend
use Illuminate\Http\Request;         // Objek request yang berisi data inputan dan file upload

class ScholarshipController extends Controller
{
    /**
     * Menyimpan data Beasiswa baru ke database.
     *
     * Endpoint: POST /admin/scholarships
     * Dipanggil dari form tambah beasiswa di panel admin.
     *
     * @param  Request  $request  Data beasiswa yang dikirim dari form modal admin.
     * @return JsonResponse       Respons sukses/gagal dalam format JSON.
     */
    public function store(Request $request): JsonResponse
    {
        // Validasi semua field beasiswa yang wajib diisi admin
        $data = $request->validate([
            'id'            => 'required|string',           // ID unik beasiswa (string manual)
            'title'         => 'required|string',           // Judul/nama beasiswa
            'provider'      => 'required|string',           // Penyelenggara beasiswa (misal: LPDP, Chevening)
            'location'      => 'required|string',           // Negara atau kota tujuan beasiswa
            'flag'          => 'nullable|string',           // Emoji bendera negara (opsional)
            'level'         => 'required|string',           // Jenjang studi: S1, S2, S3, dll.
            'amount'        => 'required|string',           // Nilai/jumlah tunjangan beasiswa
            'deadline'      => 'required|string',           // Batas waktu pendaftaran
            'status'        => 'required|string',           // Status: Buka / Tutup / Segera Buka
            'image'         => 'required|string',           // URL gambar/logo beasiswa
            'external_link' => 'required|string',           // Tautan ke website resmi penyelenggara
            'updated_ago'   => 'required|string',           // Keterangan kapan terakhir diperbarui
        ]);

        // Simpan data beasiswa baru ke database menggunakan mass-assignment
        $scholarship = Scholarship::create($data);

        // Kembalikan respons sukses beserta data beasiswa yang baru dibuat
        return response()->json([
            'success' => true,
            'message' => 'Beasiswa berhasil ditambahkan!',
            'data'    => $scholarship,
        ]);
    }

    /**
     * Memperbarui data Beasiswa yang sudah ada berdasarkan ID.
     *
     * Endpoint: PUT /admin/scholarships/{id}
     * Dipanggil dari form edit beasiswa di panel admin.
     *
     * @param  Request  $request  Data beasiswa yang diperbarui dari form modal edit.
     * @param  string   $id       ID beasiswa yang akan diperbarui.
     * @return JsonResponse       Respons sukses/gagal dalam format JSON.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        // Cari beasiswa berdasarkan ID; kembalikan 404 jika tidak ditemukan
        $scholarship = Scholarship::find($id);
        if (! $scholarship) {
            return response()->json([
                'success' => false,
                'message' => 'Beasiswa tidak ditemukan.',
            ], 404);
        }

        // Validasi field yang boleh diperbarui (ID tidak divalidasi karena tidak boleh diubah)
        $data = $request->validate([
            'title'         => 'required|string',           // Judul/nama beasiswa
            'provider'      => 'required|string',           // Penyelenggara beasiswa
            'location'      => 'required|string',           // Negara atau kota tujuan
            'flag'          => 'nullable|string',           // Emoji bendera negara (opsional)
            'level'         => 'required|string',           // Jenjang studi
            'amount'        => 'required|string',           // Nilai/jumlah tunjangan
            'deadline'      => 'required|string',           // Batas waktu pendaftaran
            'status'        => 'required|string',           // Status beasiswa
            'image'         => 'required|string',           // URL gambar/logo
            'external_link' => 'required|string',           // Tautan website resmi
            'updated_ago'   => 'required|string',           // Keterangan waktu pembaruan
        ]);

        // Perbarui data beasiswa di database dengan data yang telah divalidasi
        $scholarship->update($data);

        // Kembalikan respons sukses beserta data terbaru
        return response()->json([
            'success' => true,
            'message' => 'Beasiswa berhasil diperbarui!',
            'data'    => $scholarship,
        ]);
    }

    /**
     * Menghapus data Beasiswa dari database berdasarkan ID.
     *
     * Endpoint: DELETE /admin/scholarships/{id}
     * Dipanggil dari tombol hapus di tabel manajemen beasiswa panel admin.
     *
     * @param  string  $id  ID beasiswa yang akan dihapus.
     * @return JsonResponse Respons sukses/gagal dalam format JSON.
     */
    public function destroy(string $id): JsonResponse
    {
        // Cari beasiswa berdasarkan ID; kembalikan 404 jika tidak ditemukan
        $scholarship = Scholarship::find($id);
        if (! $scholarship) {
            return response()->json([
                'success' => false,
                'message' => 'Beasiswa tidak ditemukan.',
            ], 404);
        }

        // Hapus record beasiswa dari database
        $scholarship->delete();

        // Kembalikan respons sukses tanpa data
        return response()->json([
            'success' => true,
            'message' => 'Beasiswa berhasil dihapus!',
        ]);
    }

    /**
     * Mengupload file logo/gambar beasiswa ke storage server.
     *
     * Endpoint: POST /admin/scholarships/upload-image
     *
     * Cara kerja:
     *  1. Admin memilih file gambar dari form modal beasiswa.
     *  2. File dikirim via POST multipart/form-data ke endpoint ini.
     *  3. File disimpan di storage/app/public/scholarship-logos/ (diakses publik via symlink).
     *  4. URL publik dikembalikan sebagai JSON.
     *  5. URL tersebut otomatis diisi ke field image dan tersimpan saat form di-submit.
     *
     * @param  Request  $request  Request yang mengandung file gambar yang diunggah.
     * @return JsonResponse       URL publik dan path relatif file yang diunggah.
     */
    public function uploadImage(Request $request): JsonResponse
    {
        // Validasi: file wajib ada, harus berupa gambar (jpeg/png/jpg/gif/svg), maksimal 2MB
        $request->validate([
            'image' => 'required|image|max:2048',
        ]);

        // Simpan file ke storage/app/public/scholarship-logos/ dengan nama acak yang aman
        $path = $request->file('image')->store('scholarship-logos', 'public');

        // Kembalikan URL publik yang bisa langsung digunakan di tag <img>
        return response()->json([
            'success' => true,
            'url'     => asset('storage/' . $path),  // URL lengkap yang dapat diakses publik
            'path'    => $path,                       // Path relatif untuk referensi internal
        ]);
    }
}

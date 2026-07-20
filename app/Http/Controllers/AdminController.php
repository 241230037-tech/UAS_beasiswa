<?php

/**
 * File: app/Http/Controllers/AdminController.php
 *
 * Controller ini bertanggung jawab atas seluruh operasi CRUD (Create, Read, Update, Delete)
 * akun Administrator yang dilakukan oleh Super Admin melalui panel admin.
 * Hanya mengelola user dengan role 'admin', bukan pengguna biasa (role 'user').
 */

namespace App\Http\Controllers;

use App\Models\User;                 // Model Eloquent untuk tabel users
use Illuminate\Http\JsonResponse;   // Tipe return untuk respons JSON ke frontend
use Illuminate\Http\Request;        // Objek request yang berisi data inputan
use Illuminate\Support\Facades\Auth;  // Facade untuk mengakses data user yang sedang login
use Illuminate\Support\Facades\Hash;  // Facade untuk hashing password secara aman

class AdminController extends Controller
{
    /**
     * Menyimpan akun Administrator baru ke database.
     *
     * Endpoint: POST /admin/admins
     * Dipanggil dari form tambah admin di panel admin.
     *
     * @param  Request  $request  Data akun admin yang dikirim dari form modal admin.
     * @return JsonResponse       Respons sukses/gagal dalam format JSON.
     */
    public function store(Request $request): JsonResponse
    {
        // Validasi field akun admin baru; email harus unik di tabel users
        $data = $request->validate([
            'name'     => 'required|string|min:3',         // Nama lengkap admin (minimal 3 karakter)
            'email'    => 'required|email|unique:users,email', // Email unik sebagai kredensial login
            'password' => 'required|string|min:6',         // Password minimal 6 karakter
        ]);

        // Buat akun admin baru di database dengan role 'admin'
        $admin = User::create([
            'name'     => trim($data['name']),             // Hapus spasi di awal/akhir nama
            'email'    => trim($data['email']),             // Hapus spasi di awal/akhir email
            'password' => Hash::make($data['password']),   // Hash password sebelum disimpan ke DB
            'role'     => 'admin',                          // Tetapkan role sebagai 'admin'
        ]);

        // Kembalikan respons sukses beserta data admin (tanpa password)
        return response()->json([
            'success' => true,
            'message' => 'Akun admin berhasil ditambahkan!',
            'data'    => [
                'id'    => $admin->id,
                'name'  => $admin->name,
                'email' => $admin->email,
                'role'  => $admin->role,
            ],
        ]);
    }

    /**
     * Memperbarui data akun Administrator yang sudah ada berdasarkan ID.
     *
     * Endpoint: PUT /admin/admins/{id}
     * Dipanggil dari form edit admin di panel admin.
     * Password hanya diperbarui jika kolom password diisi.
     *
     * @param  Request  $request  Data akun admin yang diperbarui dari form modal edit.
     * @param  int      $id       ID akun admin yang akan diperbarui.
     * @return JsonResponse       Respons sukses/gagal dalam format JSON.
     */
    public function update(Request $request, int $id): JsonResponse
    {
        // Cari admin berdasarkan ID dan pastikan role-nya adalah 'admin' (bukan user biasa)
        $admin = User::where('id', $id)->where('role', 'admin')->first();
        if (! $admin) {
            return response()->json([
                'success' => false,
                'message' => 'Akun admin tidak ditemukan.',
            ], 404);
        }

        // Validasi field yang dapat diperbarui; email harus tetap unik (kecuali untuk dirinya sendiri)
        $data = $request->validate([
            'name'     => 'required|string|min:3',
            'email'    => 'required|email|unique:users,email,' . $id, // Izinkan email yang sama dengan akun ini
            'password' => 'nullable|string|min:6',         // Password opsional saat update
        ]);

        // Perbarui nama dan email
        $admin->name  = trim($data['name']);
        $admin->email = trim($data['email']);

        // Perbarui password hanya jika admin mengisinya (tidak kosong)
        if (! empty($data['password'])) {
            $admin->password = Hash::make($data['password']); // Hash password baru sebelum disimpan
        }

        // Simpan perubahan ke database
        $admin->save();

        // Kembalikan respons sukses beserta data admin terbaru (tanpa password)
        return response()->json([
            'success' => true,
            'message' => 'Akun admin berhasil diperbarui!',
            'data'    => [
                'id'    => $admin->id,
                'name'  => $admin->name,
                'email' => $admin->email,
                'role'  => $admin->role,
            ],
        ]);
    }

    /**
     * Menghapus akun Administrator dari database berdasarkan ID.
     *
     * Endpoint: DELETE /admin/admins/{id}
     * Dipanggil dari tombol hapus di tabel manajemen admin panel.
     *
     * Catatan keamanan: Admin tidak dapat menghapus akun dirinya sendiri yang sedang aktif.
     *
     * @param  int  $id  ID akun admin yang akan dihapus.
     * @return JsonResponse Respons sukses/gagal dalam format JSON.
     */
    public function destroy(int $id): JsonResponse
    {
        // Keamanan: cegah penghapusan akun yang sedang digunakan untuk login saat ini
        if (Auth::id() === $id) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak dapat menghapus akun Anda sendiri yang sedang digunakan.',
            ], 400);
        }

        // Cari admin berdasarkan ID dan pastikan role-nya adalah 'admin'
        $admin = User::where('id', $id)->where('role', 'admin')->first();
        if (! $admin) {
            return response()->json([
                'success' => false,
                'message' => 'Akun admin tidak ditemukan.',
            ], 404);
        }

        // Hapus record akun admin dari database
        $admin->delete();

        // Kembalikan respons sukses tanpa data
        return response()->json([
            'success' => true,
            'message' => 'Akun admin berhasil dihapus!',
        ]);
    }
}

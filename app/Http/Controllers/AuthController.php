<?php

/**
 * File: app/Http/Controllers/AuthController.php
 *
 * Controller ini bertanggung jawab atas seluruh operasi autentikasi berbasis database:
 * login, registrasi akun baru, logout, dan pembaruan profil/password pengguna.
 * Menggunakan sistem session Laravel (Auth::login) bukan token-based authentication.
 */

namespace App\Http\Controllers;

use App\Models\User;                    // Model Eloquent untuk tabel users
use Illuminate\Http\JsonResponse;      // Tipe return untuk respons JSON ke frontend
use Illuminate\Http\Request;           // Objek request yang berisi data inputan
use Illuminate\Support\Facades\Auth;   // Facade untuk login, logout, dan akses user yang aktif
use Illuminate\Support\Facades\Hash;   // Facade untuk hashing dan verifikasi password secara aman

class AuthController extends Controller
{
    /**
     * Proses login pengguna berdasarkan email dan password dari database.
     *
     * Endpoint: POST /api/login
     * Dipanggil dari form login di halaman login. Setelah berhasil, session Laravel dibuat.
     *
     * Catatan: Jika email diinput tanpa domain '@', sistem menambahkan '@email.com' secara otomatis
     * untuk mendukung format username singkat (digunakan khusus untuk login admin).
     *
     * @param  Request  $request  Data login (email dan password) dari form.
     * @return JsonResponse       Respons sukses dengan data user atau gagal dengan pesan error.
     */
    public function login(Request $request): JsonResponse
    {
        // Validasi bahwa email dan password dikirimkan
        $data = $request->validate([
            'email'    => 'required|string',   // Dapat berupa email penuh atau username pendek
            'password' => 'required|string',   // Password akun
        ]);

        $emailInput = trim($data['email']); // Hapus spasi dari input email
        $password   = $data['password'];

        // Jika input tidak mengandung '@', tambahkan '@email.com' untuk mendukung username singkat
        if (! str_contains($emailInput, '@')) {
            $emailInput = $emailInput . '@email.com';
        }

        // Cari pengguna di database berdasarkan email
        $user = User::where('email', $emailInput)->first();

        // Verifikasi: user ditemukan DAN password cocok dengan hash di database
        if ($user && Hash::check($password, $user->password)) {
            // Cek apakah akun pengguna biasa tersebut mati (kadaluarsa global atau per akun)
            if ($user->role === 'user' && is_null($user->last_opened_at)) {
                $isDead = false;

                // 1. Cek pengaturan global batas masa aktif akun (dalam hari)
                $globalActiveDays = (int) \App\Models\Setting::get('account_active_days', 30);
                if ($globalActiveDays > 0 && $user->created_at) {
                    $expirationDate = \Carbon\Carbon::parse($user->created_at)->addDays($globalActiveDays);
                    if (now()->greaterThan($expirationDate)) {
                        $isDead = true;
                    }
                }

                // 2. Cek deactivation_at khusus jika diatur secara individual
                if (!empty($user->deactivation_at)) {
                    $deactivateTime = \Carbon\Carbon::parse($user->deactivation_at);
                    if (now()->greaterThan($deactivateTime)) {
                        $isDead = true;
                    }
                }

                if ($isDead) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Akun Anda telah dinonaktifkan/kadaluarsa karena tidak pernah dibuka/login selama batas masa aktif yang ditentukan.',
                    ], 403);
                }
            }

            // Perbarui last_opened_at menjadi waktu saat login sukses
            $user->last_opened_at = now();
            $user->save();

            // Login pengguna ke session Laravel
            Auth::login($user);

            // Kembalikan data user (tanpa password) sebagai konfirmasi login berhasil
            return response()->json([
                'success' => true,
                'message' => 'Login berhasil!',
                'user'    => [
                    'name'  => $user->name,
                    'email' => $user->email,
                    'role'  => $user->role,  // Role digunakan frontend untuk menentukan akses halaman
                ],
            ]);
        }

        // Jika email tidak ditemukan atau password salah, kembalikan error 401 Unauthorized
        return response()->json([
            'success' => false,
            'message' => 'Kredensial login salah atau akun tidak ditemukan.',
        ], 401);
    }

    /**
     * Mendaftarkan akun pengguna baru ke database.
     *
     * Endpoint: POST /api/register
     * Dipanggil dari form registrasi di halaman login/registrasi.
     * Akun baru selalu mendapat role 'user' (bukan admin).
     *
     * @param  Request  $request  Data registrasi (name, email, password) dari form.
     * @return JsonResponse       Respons sukses dengan data user baru atau gagal dengan pesan error.
     */
    public function register(Request $request): JsonResponse
    {
        // Validasi data registrasi; email harus unik di database
        $data = $request->validate([
            'name'     => 'required|string|min:3',           // Nama minimal 3 karakter
            'email'    => 'required|email|unique:users,email', // Email unik sebagai identitas akun
            'password' => 'required|string|min:6',           // Password minimal 6 karakter
        ]);

        // Buat akun pengguna baru di database
        $user = User::create([
            'name'     => trim($data['name']),               // Nama pengguna (spasi dihapus)
            'email'    => trim($data['email']),               // Email pengguna (spasi dihapus)
            'password' => Hash::make($data['password']),     // Hash password sebelum disimpan
            'role'     => 'user',                             // Akun baru selalu bertipe 'user'
        ]);

        // Kembalikan konfirmasi registrasi berhasil; pengguna perlu login secara manual
        return response()->json([
            'success' => true,
            'message' => 'Registrasi berhasil! Silakan masuk menggunakan akun baru Anda.',
            'user'    => [
                'name'  => $user->name,
                'email' => $user->email,
                'role'  => $user->role,
            ],
        ]);
    }

    /**
     * Proses logout pengguna dengan menghapus session autentikasi Laravel.
     *
     * Endpoint: POST /api/logout
     * Dipanggil dari tombol logout di navbar.
     *
     * @return JsonResponse Respons sukses konfirmasi logout.
     */
    public function logout(): JsonResponse
    {
        // Hapus data autentikasi dari session Laravel
        Auth::logout();

        // Kembalikan konfirmasi bahwa pengguna telah berhasil keluar
        return response()->json([
            'success' => true,
            'message' => 'Berhasil keluar akun!',
        ]);
    }

    /**
     * Memperbarui profil (nama) dan/atau password pengguna yang sedang login.
     *
     * Endpoint: POST /api/update-profile
     * Dipanggil dari form edit profil di halaman dashboard pengguna.
     *
     * Catatan keamanan:
     *  - Password lama (current_password) wajib diverifikasi sebelum password baru disimpan.
     *  - Jika current_password tidak dikirim, hanya nama yang diperbarui.
     *
     * @param  Request  $request  Data profil yang diperbarui dari form dashboard.
     * @return JsonResponse       Respons sukses dengan data terbaru atau gagal dengan pesan error.
     */
    public function updateProfile(Request $request): JsonResponse
    {
        // Dapatkan user yang sedang login via session Laravel
        $user = Auth::user();

        // Kembalikan error 401 jika sesi sudah kadaluarsa
        if (! $user) {
            return response()->json([
                'success' => false,
                'message' => 'Sesi Anda telah berakhir. Silakan login kembali.',
            ], 401);
        }

        // Validasi data yang diperbarui; password baru dan lama bersifat opsional
        $data = $request->validate([
            'name'             => 'required|string|min:3',    // Nama wajib diisi dan minimal 3 karakter
            'current_password' => 'nullable|string',           // Password lama (opsional, hanya jika ingin ganti password)
            'new_password'     => 'nullable|string|min:6',    // Password baru (opsional, minimal 6 karakter)
        ]);

        // Perbarui nama pengguna
        $user->name = trim($data['name']);

        // Jika pengguna ingin mengganti password (current_password dikirim)
        if (! empty($data['current_password'])) {
            // Verifikasi bahwa password lama yang diinputkan cocok dengan yang tersimpan di database
            if (! Hash::check($data['current_password'], $user->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kata sandi saat ini yang Anda masukkan salah.',
                ], 422);
            }

            // Pastikan password baru tidak kosong
            if (empty($data['new_password'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Silakan masukkan kata sandi baru Anda.',
                ], 422);
            }

            // Simpan password baru yang sudah di-hash ke model user
            $user->password = Hash::make($data['new_password']);
        }

        // Simpan semua perubahan (nama dan/atau password) ke database
        $user->save();

        // Kembalikan respons sukses beserta data profil terbaru
        return response()->json([
            'success' => true,
            'message' => 'Profil dan kata sandi berhasil diperbarui!',
            'user'    => [
                'name'  => $user->name,
                'email' => $user->email,
            ],
        ]);
    }
}

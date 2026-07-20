<?php

/**
 * File: app/Http/Controllers/CarouselController.php
 *
 * Controller ini bertanggung jawab atas seluruh operasi CRUD (Create, Read, Update, Delete)
 * data Slide Carousel/Slider serta penanganan upload video dan streaming video dengan
 * dukungan HTTP Range Request (206 Partial Content) agar browser dapat seek/skip video.
 */

namespace App\Http\Controllers;

use App\Models\CarouselItem;                                      // Model Eloquent untuk tabel carousel_items
use Illuminate\Http\JsonResponse;                                 // Tipe return untuk respons JSON ke frontend
use Illuminate\Http\Request;                                      // Objek request yang berisi data inputan dan file upload
use Illuminate\Http\Response;                                     // Objek respons HTTP standar
use Symfony\Component\HttpFoundation\StreamedResponse;            // Tipe return untuk streaming data (video)

class CarouselController extends Controller
{
    /**
     * Menyimpan data Slide Carousel baru ke database.
     *
     * Endpoint: POST /admin/carousel
     * Slide dapat bertipe 'scholarship' (menampilkan data beasiswa) atau 'video' (video lokal/eksternal).
     *
     * @param  Request  $request  Data slide yang dikirim dari form modal admin.
     * @return JsonResponse       Respons sukses/gagal dalam format JSON.
     */
    public function store(Request $request): JsonResponse
    {
        // Validasi field slide carousel; type wajib 'scholarship' atau 'video'
        $data = $request->validate([
            'type'           => 'required|string|in:scholarship,video', // Jenis slide
            'scholarship_id' => 'nullable|integer|exists:scholarships,id', // ID beasiswa jika bertipe scholarship
            'title'          => 'nullable|string',            // Judul slide (opsional)
            'subtitle'       => 'nullable|string',            // Sub-judul slide (opsional)
            'description'    => 'nullable|string',            // Deskripsi singkat slide (opsional)
            'image_url'      => 'nullable|string',            // URL gambar latar slide (opsional)
            'video_url'      => 'nullable|string',            // URL video slide (opsional, diisi dari upload)
            'link'           => 'nullable|string',            // URL tujuan saat slide diklik (opsional)
            'order_index'    => 'nullable|integer',           // Urutan tampil slide (0 = paling awal)
        ]);

        // Buat record slide carousel baru di database
        $item = CarouselItem::create($data);

        // Jika bertipe beasiswa, muat relasi scholarship agar data beasiswa ikut di respons
        if ($item->type === 'scholarship') {
            $item->load('scholarship');
        }

        // Kembalikan respons sukses beserta data slide yang baru dibuat
        return response()->json([
            'success' => true,
            'message' => 'Slide carousel berhasil ditambahkan!',
            'data'    => $item,
        ]);
    }

    /**
     * Memperbarui data Slide Carousel yang sudah ada berdasarkan ID.
     *
     * Endpoint: PUT /admin/carousel/{id}
     *
     * @param  Request  $request  Data slide yang diperbarui dari form modal edit.
     * @param  int      $id       ID slide carousel yang akan diperbarui.
     * @return JsonResponse       Respons sukses/gagal dalam format JSON.
     */
    public function update(Request $request, int $id): JsonResponse
    {
        // Cari slide berdasarkan ID; kembalikan 404 jika tidak ditemukan
        $item = CarouselItem::find($id);
        if (! $item) {
            return response()->json([
                'success' => false,
                'message' => 'Slide tidak ditemukan.',
            ], 404);
        }

        // Validasi field slide yang boleh diperbarui
        $data = $request->validate([
            'type'           => 'required|string|in:scholarship,video', // Jenis slide
            'scholarship_id' => 'nullable|integer|exists:scholarships,id', // ID beasiswa
            'title'          => 'nullable|string',            // Judul slide
            'subtitle'       => 'nullable|string',            // Sub-judul slide
            'description'    => 'nullable|string',            // Deskripsi slide
            'image_url'      => 'nullable|string',            // URL gambar latar
            'video_url'      => 'nullable|string',            // URL video
            'link'           => 'nullable|string',            // URL tujuan klik
            'order_index'    => 'nullable|integer',           // Urutan tampil
        ]);

        // Perbarui data slide di database
        $item->update($data);

        // Jika bertipe beasiswa, muat ulang relasi scholarship setelah update
        if ($item->type === 'scholarship') {
            $item->load('scholarship');
        }

        // Kembalikan respons sukses beserta data slide terbaru
        return response()->json([
            'success' => true,
            'message' => 'Slide carousel berhasil diperbarui!',
            'data'    => $item,
        ]);
    }

    /**
     * Menghapus data Slide Carousel dari database berdasarkan ID.
     *
     * Endpoint: DELETE /admin/carousel/{id}
     *
     * @param  int  $id  ID slide carousel yang akan dihapus.
     * @return JsonResponse Respons sukses/gagal dalam format JSON.
     */
    public function destroy(int $id): JsonResponse
    {
        // Cari slide berdasarkan ID; kembalikan 404 jika tidak ditemukan
        $item = CarouselItem::find($id);
        if (! $item) {
            return response()->json([
                'success' => false,
                'message' => 'Slide tidak ditemukan.',
            ], 404);
        }

        // Hapus record slide dari database
        $item->delete();

        // Kembalikan respons sukses tanpa data
        return response()->json([
            'success' => true,
            'message' => 'Slide carousel berhasil dihapus!',
        ]);
    }

    /**
     * Mengupload file video untuk slide carousel (upload biasa untuk file kecil).
     *
     * Endpoint: POST /admin/carousel/upload-video
     * Untuk file besar, gunakan endpoint chunked upload di bawah ini.
     *
     * @param  Request  $request  Request yang mengandung file video yang diunggah.
     * @return JsonResponse       URL publik dan path relatif file yang diunggah.
     */
    public function uploadVideo(Request $request): JsonResponse
    {
        // Validasi: file wajib ada, berupa video (mp4/webm/ogg/mov/avi) tanpa batas ukuran tertentu
        $request->validate([
            'video' => 'required|file|mimes:mp4,webm,ogg,mov,avi',
        ]);

        // Simpan file video ke storage/app/public/carousel-videos/ dengan nama acak
        $path = $request->file('video')->store('carousel-videos', 'public');

        // Kembalikan URL publik file video yang diunggah
        return response()->json([
            'success' => true,
            'url'     => asset('storage/' . $path),  // URL publik untuk ditampilkan di video player
            'path'    => $path,                       // Path relatif untuk referensi internal
        ]);
    }

    /**
     * Menerima satu potongan (chunk) video dan menggabungkan seluruh chunk menjadi satu file utuh.
     *
     * Endpoint: POST /admin/carousel/upload-video-chunk
     *
     * Cara kerja chunked upload:
     *  1. Frontend memotong file video menjadi beberapa chunk kecil (~2MB per chunk).
     *  2. Setiap chunk dikirim satu per satu dengan index dan upload_id yang unik.
     *  3. Setiap chunk disimpan sementara di storage/app/chunks/{upload_id}/chunk_{index}.
     *  4. Ketika semua chunk diterima, server menggabungkannya menjadi satu file video utuh.
     *  5. File final disimpan di storage/app/public/carousel-videos/ dan URL publiknya dikembalikan.
     *
     * Ini memungkinkan upload video berukuran sangat besar yang melebihi upload_max_filesize PHP.
     *
     * @param  Request  $request  Request yang mengandung satu chunk video dan metadata upload.
     * @return JsonResponse       Status penerimaan chunk atau URL final jika sudah selesai.
     */
    public function uploadVideoChunk(Request $request): JsonResponse
    {
        // Validasi metadata chunk yang wajib ada dalam setiap request
        $request->validate([
            'chunk'        => 'required|file',         // Potongan binary video
            'chunk_index'  => 'required|integer|min:0', // Nomor urut chunk (mulai dari 0)
            'total_chunks' => 'required|integer|min:1', // Total jumlah chunk keseluruhan
            'filename'     => 'required|string',       // Nama file asli (untuk menentukan ekstensi)
            'upload_id'    => 'required|string',       // ID unik sesi upload ini
        ]);

        // Ambil data dari request dan sanitasi untuk keamanan
        $chunkIndex   = (int) $request->input('chunk_index');
        $totalChunks  = (int) $request->input('total_chunks');
        $uploadId     = preg_replace('/[^a-zA-Z0-9_-]/', '', $request->input('upload_id')); // Cegah path traversal
        $originalName = $request->input('filename');

        // Validasi ekstensi file dari nama file asli
        $ext        = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
        $allowedExt = ['mp4', 'webm', 'ogg', 'mov', 'avi'];
        if (! in_array($ext, $allowedExt)) {
            // Tolak jika format video tidak didukung
            return response()->json([
                'success' => false,
                'message' => 'Format video tidak didukung. Gunakan MP4, WebM, OGG, MOV, atau AVI.',
            ], 422);
        }

        // Direktori sementara untuk menyimpan chunk berdasarkan upload_id unik
        $tempDir = storage_path('app/chunks/' . $uploadId);
        if (! file_exists($tempDir)) {
            mkdir($tempDir, 0755, true); // Buat direktori jika belum ada
        }

        // Pindahkan chunk yang diterima ke direktori sementara dengan nama 'chunk_{index}'
        $request->file('chunk')->move($tempDir, 'chunk_' . $chunkIndex);

        // Hitung berapa chunk yang sudah diterima sejauh ini
        $receivedChunks = count(glob($tempDir . '/chunk_*'));

        // Jika belum semua chunk diterima, beritahu frontend untuk melanjutkan pengiriman
        if ($receivedChunks < $totalChunks) {
            return response()->json([
                'success'  => true,
                'done'     => false,                    // Tandai bahwa upload belum selesai
                'received' => $receivedChunks,
                'total'    => $totalChunks,
                'message'  => "Chunk {$chunkIndex} diterima ({$receivedChunks}/{$totalChunks}).",
            ]);
        }

        // --- Semua chunk sudah diterima — mulai proses penggabungan ---

        // Tentukan nama dan path file final
        $finalFilename = $uploadId . '.' . $ext;
        $finalDir      = storage_path('app/public/carousel-videos');
        if (! file_exists($finalDir)) {
            mkdir($finalDir, 0755, true); // Buat direktori tujuan jika belum ada
        }
        $finalPath = $finalDir . '/' . $finalFilename;

        // Buka file output untuk ditulis secara binary
        $outputFile = fopen($finalPath, 'wb');

        // Gabungkan chunk satu per satu secara berurutan berdasarkan indeks
        for ($i = 0; $i < $totalChunks; $i++) {
            $chunkFile = $tempDir . '/chunk_' . $i;

            // Jika ada chunk yang hilang, batalkan dan hapus file yang sudah terbuat
            if (! file_exists($chunkFile)) {
                fclose($outputFile);
                if (file_exists($finalPath)) {
                    unlink($finalPath); // Hapus file parsial yang gagal
                }
                return response()->json([
                    'success' => false,
                    'message' => "Potongan video ke-{$i} hilang. Silakan upload ulang.",
                ], 500);
            }

            // Salin isi chunk ke file output
            $chunkHandle = fopen($chunkFile, 'rb');
            stream_copy_to_stream($chunkHandle, $outputFile);
            fclose($chunkHandle);
        }

        // Tutup file output setelah semua chunk digabungkan
        fclose($outputFile);

        // Hapus semua file chunk sementara dan direktori temp untuk menjaga kebersihan storage
        array_map('unlink', glob($tempDir . '/chunk_*'));
        rmdir($tempDir);

        // Path relatif publik untuk disimpan ke database
        $publicPath = 'carousel-videos/' . $finalFilename;

        // Kembalikan URL publik file video yang telah berhasil digabungkan
        return response()->json([
            'success' => true,
            'done'    => true,                            // Tandai bahwa upload sudah selesai
            'url'     => asset('storage/' . $publicPath), // URL publik file video final
            'path'    => $publicPath,                     // Path relatif untuk database
            'message' => 'Video berhasil diunggah!',
        ]);
    }

    /**
     * Streaming video carousel dengan dukungan HTTP Range Request (206 Partial Content).
     *
     * Endpoint: GET /stream/video/{filename}
     *
     * Mengapa endpoint ini diperlukan:
     *  - Browser membutuhkan header 'Accept-Ranges: bytes' untuk fitur seek/skip video.
     *  - Laravel Artisan development server tidak melayani Range Request secara native.
     *  - Handler ini memproses header 'Range' secara manual dan mengembalikan potongan byte
     *    yang tepat agar video dapat diputar dari posisi manapun tanpa buffering ulang.
     *
     * @param  Request  $request   Request HTTP yang mungkin berisi header 'Range'.
     * @param  string   $filename  Nama file video di direktori carousel-videos.
     * @return StreamedResponse|Response Respons streaming atau error 404/416.
     */
    public function streamVideo(Request $request, string $filename): StreamedResponse|Response
    {
        // Sanitasi nama file untuk mencegah path traversal (serangan direktori)
        $filename = basename($filename);
        $filePath = storage_path('app/public/carousel-videos/' . $filename);

        // Pastikan file ada dan merupakan file (bukan direktori)
        if (! file_exists($filePath) || ! is_file($filePath)) {
            abort(404, 'Video tidak ditemukan.');
        }

        $fileSize = filesize($filePath);                        // Ukuran file dalam bytes
        $mimeType = mime_content_type($filePath) ?: 'video/mp4'; // MIME type dari file

        // Peta ekstensi ke MIME type yang benar untuk memastikan browser memproses dengan tepat
        $ext     = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        $mimeMap = [
            'mp4'  => 'video/mp4',
            'webm' => 'video/webm',
            'ogg'  => 'video/ogg',
            'mov'  => 'video/quicktime',
            'avi'  => 'video/x-msvideo',
        ];

        // Gunakan MIME type dari peta jika ekstensi dikenal
        if (isset($mimeMap[$ext])) {
            $mimeType = $mimeMap[$ext];
        }

        // Baca header Range dari request (dikirim browser saat seek/skip video)
        $rangeHeader = $request->header('Range');

        // Jika tidak ada Range header, kirim seluruh file dengan header Accept-Ranges
        if (! $rangeHeader) {
            return response()->stream(function () use ($filePath) {
                $handle = fopen($filePath, 'rb');           // Buka file dalam mode baca binary
                while (! feof($handle)) {
                    echo fread($handle, 1024 * 64);         // Baca dan kirim 64KB per iterasi
                    flush();                                // Kirim buffer ke browser
                    ob_flush();                             // Hapus output buffer
                }
                fclose($handle);                            // Tutup file handle
            }, 200, [
                'Content-Type'   => $mimeType,             // MIME type video
                'Content-Length' => $fileSize,             // Total ukuran file
                'Accept-Ranges'  => 'bytes',               // Beritahu browser bahwa Range didukung
                'Cache-Control'  => 'public, max-age=3600', // Cache 1 jam di browser
            ]);
        }

        // Parse Range header untuk mendapatkan byte awal dan akhir yang diminta
        // Format: "bytes=START-END" (misal: "bytes=0-1048575")
        preg_match('/bytes=(\d+)-(\d*)/', $rangeHeader, $matches);
        $start = isset($matches[1]) ? (int) $matches[1] : 0;
        $end   = isset($matches[2]) && $matches[2] !== '' ? (int) $matches[2] : $fileSize - 1;
        $end   = min($end, $fileSize - 1); // Pastikan end tidak melebihi ukuran file

        // Validasi range: start tidak boleh lebih besar dari end atau melebihi ukuran file
        if ($start > $end || $start >= $fileSize) {
            return response('Requested Range Not Satisfiable', 416, [
                'Content-Range' => "bytes */{$fileSize}", // Informasikan ukuran total ke browser
            ]);
        }

        // Hitung panjang byte yang akan dikirim
        $length = $end - $start + 1;

        // Kembalikan Partial Content (206) dengan potongan byte yang diminta browser
        return response()->stream(function () use ($filePath, $start, $length) {
            $handle    = fopen($filePath, 'rb'); // Buka file dalam mode baca binary
            fseek($handle, $start);              // Pindah ke posisi byte awal
            $remaining = $length;               // Sisa byte yang perlu dikirim

            // Kirim byte secara bertahap hingga semua byte terkirim
            while (! feof($handle) && $remaining > 0) {
                $chunkSize = min(1024 * 64, $remaining); // Kirim 64KB atau sisa byte (mana yang lebih kecil)
                $data      = fread($handle, $chunkSize);
                echo $data;
                $remaining -= strlen($data);            // Kurangi sisa byte yang perlu dikirim
                flush();                                // Kirim ke browser
                ob_flush();                             // Hapus output buffer
            }

            fclose($handle); // Tutup file handle setelah selesai
        }, 206, [
            'Content-Type'   => $mimeType,                          // MIME type video
            'Content-Range'  => "bytes {$start}-{$end}/{$fileSize}", // Range yang dikirim
            'Content-Length' => $length,                            // Panjang byte yang dikirim
            'Accept-Ranges'  => 'bytes',                            // Konfirmasi dukungan Range
            'Cache-Control'  => 'public, max-age=3600',             // Cache 1 jam
        ]);
    }
}

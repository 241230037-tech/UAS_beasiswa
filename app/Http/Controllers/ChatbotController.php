<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ChatbotController extends Controller
{
    /**
     * Menangani pesan obrolan pengguna dan menghubungkannya dengan AI nyata.
     */
    public function respond(Request $request)
    {
        $message = trim($request->input('message', ''));

        if (empty($message)) {
            return response()->json([
                'success' => false,
                'message' => 'Pesan tidak boleh kosong.'
            ], 400);
        }

        $systemPrompt = "Anda adalah Asisten Beasiswapedia AI, asisten virtual pintar, ramah, dan solutif yang membantu siswa dan mahasiswa Indonesia meraih beasiswa impian. Jawablah pertanyaan pengguna secara informatif, terstruktur (gunakan poin jika perlu), ramah, dan memberikan dorongan semangat positif. Jawab SINGKAT dan padat, maksimal 3-4 kalimat atau poin. Jawab dalam Bahasa Indonesia.";

        try {
            // Coba Pollinations AI (endpoint chat completions)
            $response = Http::timeout(20)
                ->withHeaders([
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ])
                ->post('https://text.pollinations.ai/openai', [
                    'model' => 'openai',
                    'messages' => [
                        ['role' => 'system', 'content' => $systemPrompt],
                        ['role' => 'user', 'content' => $message]
                    ],
                    'seed' => 42,
                    'private' => true,
                ]);

            if ($response->successful()) {
                $body = $response->json();
                $reply = $body['choices'][0]['message']['content'] ?? null;
                if (!empty($reply)) {
                    return response()->json([
                        'success' => true,
                        'reply' => trim($reply)
                    ]);
                }
            }
        } catch (\Exception $e) {
            Log::warning('Pollinations AI failed: ' . $e->getMessage());
        }

        try {
            // Fallback: Pollinations AI plain text endpoint
            $encodedSystem = urlencode($systemPrompt);
            $encodedMsg = urlencode($message);
            $response = Http::timeout(20)
                ->withHeaders(['Accept' => 'text/plain'])
                ->get("https://text.pollinations.ai/{$encodedMsg}?system={$encodedSystem}&model=openai&private=true");

            if ($response->successful()) {
                $reply = trim($response->body());
                if (!empty($reply) && strlen($reply) > 10) {
                    return response()->json([
                        'success' => true,
                        'reply' => $reply
                    ]);
                }
            }
        } catch (\Exception $e) {
            Log::warning('Pollinations fallback failed: ' . $e->getMessage());
        }

        // Final fallback: jawaban statis kontekstual berdasarkan kata kunci
        $lower = strtolower($message);
        $reply = $this->getContextualFallback($lower);

        return response()->json([
            'success' => true,
            'reply' => $reply
        ]);
    }

    private function getContextualFallback(string $lower): string
    {
        if (str_contains($lower, 'lpdp')) {
            return "**LPDP** adalah beasiswa bergengsi dari pemerintah Indonesia. Tips utama: pastikan motivation letter Anda menceritakan kontribusi nyata untuk Indonesia setelah studi, serta persiapkan wawancara dengan memahami visi LPDP secara mendalam. Kunjungi lpdp.kemenkeu.go.id untuk pendaftaran resmi.";
        }
        if (str_contains($lower, 'essay') || str_contains($lower, 'motivation letter') || str_contains($lower, 'esai')) {
            return "Tips menulis **motivation letter** yang kuat:\n1. Ceritakan latar belakang dan alasan spesifik memilih beasiswa ini\n2. Hubungkan tujuan studi dengan kontribusi nyata setelah lulus\n3. Gunakan data/pencapaian konkret, bukan kalimat umum\n4. Pastikan bahasa lugas, terstruktur, dan bebas typo";
        }
        if (str_contains($lower, 'dokumen') || str_contains($lower, 'berkas') || str_contains($lower, 'syarat')) {
            return "Dokumen umum yang biasanya diperlukan untuk beasiswa:\n1. KTP / Paspor\n2. Ijazah & Transkrip Nilai (legalisir)\n3. Sertifikat bahasa (TOEFL/IELTS/TOEIC)\n4. CV / Resume terbaru\n5. Motivation Letter & Rencana Studi\n6. Surat Rekomendasi";
        }
        if (str_contains($lower, 'beasiswa') && (str_contains($lower, 'luar negeri') || str_contains($lower, 's2') || str_contains($lower, 's3'))) {
            return "Beasiswa luar negeri populer untuk S2/S3: **LPDP**, **Chevening** (UK), **DAAD** (Jerman), **Mext** (Jepang), **KGSP** (Korea), dan **Australia Awards**. Setiap beasiswa punya jadwal berbeda, sebaiknya pantau website resminya dan mulai persiapkan dokumen dari jauh hari.";
        }
        if (str_contains($lower, 'toefl') || str_contains($lower, 'ielts') || str_contains($lower, 'bahasa inggris')) {
            return "Skor bahasa Inggris minimum umumnya: **TOEFL iBT 80+** atau **IELTS 6.5+** untuk sebagian besar beasiswa internasional. Tips meningkatkan skor: latihan soal rutin, ikuti kelas persiapan, dan konsistensi membaca/menonton konten berbahasa Inggris setiap hari.";
        }

        return "Halo! Saya **Asisten Beasiswapedia AI** siap membantu. Anda bisa tanyakan tentang:\n- Tips menulis motivation letter\n- Syarat dokumen beasiswa\n- Rekomendasi beasiswa S1/S2/S3\n- Persiapan tes TOEFL/IELTS\n- Beasiswa LPDP, Chevening, DAAD, dll.\n\nSilakan ajukan pertanyaan Anda! 😊";
    }
}

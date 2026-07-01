<?php

namespace App\Data;

class ScholarshipData
{
    public static function all(): array
    {
        return [
            [
                'id' => '1',
                'title' => 'Beasiswa S2/S3 LPDP (Dalam & Luar Negeri)',
                'provider' => 'LPDP - Lembaga Pengelola Dana Pendidikan',
                'location' => 'Indonesia',
                'deadline' => '30 Jul 2026',
                'amount' => 'Fully Funded',
                'image' => '/images/logos/lpdp.png',
                'external_link' => 'https://lpdp.kemenkeu.go.id/',
                'flag' => '🇮🇩',
                'status' => 'Dibuka',
                'updated_ago' => '2 jam lalu',
                'level' => 'S2/S3',
            ],
            [
                'id' => '2',
                'title' => 'Beasiswa Unggulan Kemendikbudristek',
                'provider' => 'Kementerian Pendidikan dan Kebudayaan RI',
                'location' => 'Indonesia',
                'deadline' => '15 Ags 2026',
                'amount' => 'UKT + Biaya Hidup + Buku',
                'image' => '/images/logos/kampusmerdeka.png',
                'external_link' => 'https://beasiswaunggulan.kemdikbud.go.id/',
                'flag' => '🇮🇩',
                'status' => 'Akan Datang',
                'updated_ago' => '5 jam lalu',
                'level' => 'S1-S3',
            ],
            [
                'id' => '3',
                'title' => 'Beasiswa KIP Kuliah Merdeka 2026',
                'provider' => 'Kemendikbudristek RI',
                'location' => 'Indonesia',
                'deadline' => '31 Okt 2026',
                'amount' => 'UKT Penuh + Biaya Hidup',
                'image' => '/images/logos/kampusmerdeka.png',
                'external_link' => 'https://kip-kuliah.kemdikbud.go.id/',
                'flag' => '🇮🇩',
                'status' => 'Dibuka',
                'updated_ago' => '8 jam lalu',
                'level' => 'S1/D4',
            ],
            [
                'id' => '4',
                'title' => 'Beasiswa Bank Indonesia (BI) 2026',
                'provider' => 'Bank Indonesia',
                'location' => 'Indonesia',
                'deadline' => '10 Ags 2026',
                'amount' => 'Rp 12 Juta / Semester',
                'image' => '/images/logos/bankindonesia.png',
                'external_link' => 'https://www.bi.go.id/',
                'flag' => '🇮🇩',
                'status' => 'Dibuka',
                'updated_ago' => '1 hari lalu',
                'level' => 'S1/D3',
            ],
            [
                'id' => '5',
                'title' => 'Beasiswa BAZNAS (Badan Amil Zakat)',
                'provider' => 'Badan Amil Zakat Nasional',
                'location' => 'Indonesia',
                'deadline' => '20 Jul 2026',
                'amount' => 'Subsidi UKT & Pembinaan',
                'image' => '/images/logos/baznas.png',
                'external_link' => 'https://beasiswa.baznas.go.id/',
                'flag' => '🇮🇩',
                'status' => 'Dibuka',
                'updated_ago' => '1 hari lalu',
                'level' => 'S1',
            ],
            [
                'id' => '6',
                'title' => 'Djarum Beasiswa Plus',
                'provider' => 'Djarum Foundation',
                'location' => 'Indonesia',
                'deadline' => '30 Jun 2026',
                'amount' => 'Rp 1 Juta/Bulan + Pelatihan',
                'image' => '/images/logos/djarum.png',
                'external_link' => 'https://djarumbeasiswaplus.org/',
                'flag' => '🇮🇩',
                'status' => 'Dibuka',
                'updated_ago' => '2 hari lalu',
                'level' => 'S1',
            ],
            [
                'id' => '7',
                'title' => 'Beasiswa Bakti BCA 2026/2027',
                'provider' => 'PT Bank Central Asia Tbk',
                'location' => 'Indonesia',
                'deadline' => '25 Jun 2026',
                'amount' => 'Bantuan Dana + Soft Skills',
                'image' => '/images/logos/bca.png',
                'external_link' => 'https://www.bca.co.id/',
                'flag' => '🇮🇩',
                'status' => 'Dibuka',
                'updated_ago' => '3 hari lalu',
                'level' => 'S1',
            ],
            [
                'id' => '8',
                'title' => 'Beasiswa Sobat Bumi Pertamina',
                'provider' => 'Pertamina Foundation',
                'location' => 'Indonesia',
                'deadline' => '12 Jul 2026',
                'amount' => 'Bantuan UKT + Uang Saku',
                'image' => '/images/logos/pertamina.png',
                'external_link' => 'https://pertaminafoundation.org/',
                'flag' => '🇮🇩',
                'status' => 'Dibuka',
                'updated_ago' => '3 hari lalu',
                'level' => 'S1',
            ],
            [
                'id' => '9',
                'title' => 'Beasiswa Astra 1st Program',
                'provider' => 'PT Astra International Tbk',
                'location' => 'Indonesia',
                'deadline' => '18 Jul 2026',
                'amount' => 'Uang Saku & Proyek Kerja',
                'image' => '/images/logos/astra.png',
                'external_link' => 'https://career.astra.co.id/',
                'flag' => '🇮🇩',
                'status' => 'Akan Datang',
                'updated_ago' => '4 hari lalu',
                'level' => 'S1',
            ],
            [
                'id' => '10',
                'title' => 'Beasiswa YBM BRILiaN Bright Scholarship',
                'provider' => 'Yayasan Baitul Maal BRILiaN (BRI)',
                'location' => 'Indonesia',
                'deadline' => '05 Jul 2026',
                'amount' => 'Uang Saku + Asrama + Pembinaan',
                'image' => '/images/logos/brilian.png',
                'external_link' => 'https://ybmbrilian.id/',
                'flag' => '🇮🇩',
                'status' => 'Dibuka',
                'updated_ago' => '5 hari lalu',
                'level' => 'S1',
            ],
            [
                'id' => '11',
                'title' => 'Beasiswa XL Future Leaders (XLFL)',
                'provider' => 'PT XL Axiata Tbk',
                'location' => 'Indonesia',
                'deadline' => '14 Jul 2026',
                'amount' => 'Gadget + Uang Saku + Training',
                'image' => '/images/logos/xl.png',
                'external_link' => 'https://xlfutureleaders.com/',
                'flag' => '🇮🇩',
                'status' => 'Dibuka',
                'updated_ago' => '1 minggu lalu',
                'level' => 'S1',
            ],
            [
                'id' => '12',
                'title' => 'Beasiswa Indonesia Bangkit (BIB)',
                'provider' => 'Kementerian Agama RI',
                'location' => 'Indonesia',
                'deadline' => '10 Jul 2026',
                'amount' => 'Fully Funded',
                'image' => '/images/logos/kemenag.png',
                'external_link' => 'https://beasiswa.kemenag.go.id/',
                'flag' => '🇮🇩',
                'status' => 'Dibuka',
                'updated_ago' => '1 minggu lalu',
                'level' => 'S1-S3',
            ],
        ];
    }

    public static function find(string $id): ?array
    {
        foreach (self::all() as $scholarship) {
            if ($scholarship['id'] === $id) {
                return $scholarship;
            }
        }

        return null;
    }

    public static function extra(string $id): array
    {
        $extras = [
            '1' => [
                'description' => 'Program beasiswa penuh dari pemerintah Indonesia untuk melanjutkan studi S2/S3 di universitas terbaik dunia. LPDP mendukung putra-putri terbaik bangsa yang ingin berkontribusi untuk Indonesia.',
                'requirements' => ['IPK minimal 3.0 (S2)', 'Usia maksimal 35 tahun', 'LoA dari universitas tujuan', 'Skor TOEFL/IELTS resmi', 'Surat rekomendasi 2 orang', 'Esai rencana studi'],
                'benefits' => ['Biaya pendidikan penuh', 'Biaya hidup bulanan', 'Tiket pesawat PP', 'Asuransi kesehatan', 'Biaya penelitian', 'Allowance kedatangan'],
            ],
        ];

        if (isset($extras[$id])) {
            return $extras[$id];
        }

        $scholarship = self::find($id) ?? self::all()[0];

        return [
            'description' => $scholarship['title'].' adalah program beasiswa bergengsi yang memberikan kesempatan bagi pelajar Indonesia untuk mengembangkan diri di tingkat internasional.',
            'requirements' => ['Memenuhi persyaratan akademik', 'Skor bahasa Inggris yang memadai', 'Surat motivasi / motivation letter', 'Surat rekomendasi', 'Dokumen pendukung lainnya'],
            'benefits' => ['Biaya pendidikan', 'Biaya hidup bulanan', 'Asuransi kesehatan', 'Dukungan perjalanan'],
        ];
    }

    public static function adBanners(): array
    {
        return [
            [
                'title' => 'KONSULTASI BEASISWA',
                'subtitle' => 'GRATIS!',
                'description' => 'Bimbingan langsung dari mentor berpengalaman',
                'cta_text' => 'Daftar Sekarang',
                'bg_from' => '#1a237e',
                'bg_to' => '#283593',
                'tag' => 'PROMO',
                'link' => '/tutorial',
            ],
            [
                'title' => 'KURSUS IELTS & TOEFL',
                'subtitle' => 'SKOR TINGGI!',
                'description' => 'Persiapan ujian bahasa untuk beasiswa luar negeri',
                'cta_text' => 'Coba Gratis',
                'bg_from' => '#004d40',
                'bg_to' => '#00695c',
                'tag' => 'HOT',
                'link' => '/library',
            ],
            [
                'title' => 'ESSAY BEASISWA',
                'subtitle' => 'PROFESIONAL',
                'description' => 'Jasa review & editing motivation letter',
                'cta_text' => 'Lihat Paket',
                'bg_from' => '#4a148c',
                'bg_to' => '#6a1b9a',
                'tag' => 'NEW',
                'link' => '/library',
            ],
        ];
    }

    public static function tutorialSteps(): array
    {
        return [
            [
                'num' => '01',
                'icon' => 'file-text',
                'title' => 'Persiapan Berkas Utama',
                'desc' => 'Siapkan dokumen dasar seperti scan KTP, KK, Ijazah, dan Transkrip Nilai yang sudah diterjemahkan ke bahasa Inggris (jika mendaftar luar negeri) oleh penerjemah tersumpah.',
                'tips' => ['Gunakan scanner berkualitas tinggi, hindari memfoto dokumen.', 'Simpan dalam format PDF dengan penamaan yang rapi (misal: Ijazah_Nama.pdf).'],
            ],
            [
                'num' => '02',
                'icon' => 'award',
                'title' => 'Sertifikasi Kemampuan Bahasa',
                'desc' => 'Beasiswa luar negeri hampir selalu mensyaratkan bukti kecakapan bahasa Inggris (IELTS/TOEFL iBT). Beasiswa dalam negeri umumnya meminta TOEFL ITP.',
                'tips' => ['Persiapkan belajar bahasa Inggris minimal 3-6 bulan sebelum tes resmi.', 'Periksa nilai skor minimum yang diminta beasiswa target Anda.'],
            ],
            [
                'num' => '03',
                'icon' => 'book-open',
                'title' => 'Mendapatkan Surat Penerimaan (LoA)',
                'desc' => 'Letter of Acceptance (LoA) adalah bukti resmi bahwa Anda telah diterima di universitas tujuan. Beberapa beasiswa mewajibkan atau memprioritaskan pelamar yang sudah punya LoA.',
                'tips' => ['Daftar ke universitas target terlebih dahulu untuk mengamankan LoA.', 'Pilih LoA Unconditional (diterima tanpa syarat tambahan).'],
            ],
            [
                'num' => '04',
                'icon' => 'sparkles',
                'title' => 'Menulis Motivation Letter & Esai',
                'desc' => 'Ini adalah bagian krusial di mana Anda meyakinkan komite beasiswa mengapa Anda layak dipilih. Ceritakan kelebihan, rencana studi, dan kontribusi nyata pasca studi.',
                'tips' => ['Gunakan struktur esai yang runut: Pembuka, Isi (Rencana Studi/Kontribusi), Penutup.', 'Minta bantuan alumni/awardee untuk mereview esai Anda.'],
            ],
            [
                'num' => '05',
                'icon' => 'message-circle',
                'title' => 'Mendapatkan Surat Rekomendasi',
                'desc' => 'Mintalah rekomendasi dari dosen wali, dekan, atau atasan tempat kerja Anda. Surat ini berisi ulasan kompetensi, etika kerja, dan kepemimpinan Anda.',
                'tips' => ['Hubungi pemberi rekomendasi jauh-jauh hari (minimal 1 bulan).', 'Sediakan draf deskripsi prestasi Anda untuk memudahkan mereka.'],
            ],
            [
                'num' => '06',
                'icon' => 'check-circle',
                'title' => 'Pengiriman Berkas & Seleksi Wawancara',
                'desc' => 'Kirim seluruh berkas sebelum deadline berakhir. Jika lolos seleksi administrasi, persiapkan diri untuk menghadapi wawancara panel dari panitia beasiswa.',
                'tips' => ['Latihan wawancara (mock interview) bersama rekan Anda.', 'Pahami isi esai Anda sendiri karena pertanyaan wawancara akan berpusat di sana.'],
            ],
        ];
    }
}

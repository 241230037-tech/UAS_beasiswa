<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BeasiswaPedia</title>

    @vite(['resources/css/app.css','resources/js/app.js'])
</head>

<body class="bg-gray-50">

<!-- Navbar -->
<nav class="bg-white shadow-sm fixed w-full z-50">
    <div class="max-w-7xl mx-auto px-6">

        <div class="flex justify-between items-center h-20">

            <a href="/" class="text-3xl font-bold text-blue-700">
                🎓 BeasiswaPedia
            </a>

            <div class="hidden md:flex items-center space-x-8">

                <a href="#" class="text-gray-600 hover:text-blue-600">
                    Home
                </a>

                <a href="#" class="text-gray-600 hover:text-blue-600">
                    Beasiswa
                </a>

                <a href="#" class="text-gray-600 hover:text-blue-600">
                    Tentang
                </a>

                <a href="#" class="text-gray-600 hover:text-blue-600">
                    Kontak
                </a>

            </div>

            <div class="space-x-3">

                <a href="{{ route('login') }}"
                   class="px-5 py-2 rounded-lg border border-blue-600 text-blue-600 hover:bg-blue-50">

                    Login

                </a>

                <a href="{{ route('register') }}"
                   class="px-5 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700">

                    Register

                </a>

            </div>

        </div>

    </div>
</nav>

<!-- Hero -->
<section class="pt-36 pb-24">

<div class="max-w-7xl mx-auto px-6">

<div class="grid lg:grid-cols-2 gap-16 items-center">

<div>

<span class="bg-blue-100 text-blue-700 px-4 py-2 rounded-full">

Platform Informasi Beasiswa 

</span>

<h1 class="text-6xl font-extrabold mt-8 leading-tight">

Temukan

<span class="text-blue-600">

Beasiswa

</span>

Impianmu.

</h1>

<p class="text-gray-600 mt-8 text-lg leading-8">

BeasiswaPedia membantu mahasiswa menemukan berbagai informasi
beasiswa dalam negeri maupun luar negeri secara cepat,
lengkap, dan terpercaya.

</p>

<div class="mt-10 flex gap-4">

<a href="{{ route('register') }}"
class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-4 rounded-xl">

Daftar Sekarang

</a>

<a href="#fitur"
class="border border-blue-600 text-blue-600 px-8 py-4 rounded-xl">

Pelajari

</a>

</div>

</div>

<div>

<img
src="https://pmb.unmuhpnk.ac.id/assets/images/hero-mahasiswa.png"
class="rounded-3xl shadow-2xl">

</div>

</div>

</div>

</section>

<!-- Statistik -->

<section class="pb-24">

<div class="max-w-7xl mx-auto">

<div class="grid md:grid-cols-4 gap-8">

<div class="bg-white rounded-3xl shadow p-8 text-center">

<h2 class="text-5xl font-bold text-blue-600">

200+

</h2>

<p class="text-gray-500 mt-3">

Beasiswa Aktif

</p>

</div>

<div class="bg-white rounded-3xl shadow p-8 text-center">

<h2 class="text-5xl font-bold text-green-600">

3000+

</h2>

<p class="text-gray-500 mt-3">

Mahasiswa

</p>

</div>

<div class="bg-white rounded-3xl shadow p-8 text-center">

<h2 class="text-5xl font-bold text-yellow-500">

75+

</h2>

<p class="text-gray-500 mt-3">

Universitas

</p>

</div>

<div class="bg-white rounded-3xl shadow p-8 text-center">

<h2 class="text-5xl font-bold text-red-500">

50+

</h2>

<p class="text-gray-500 mt-3">

Mitra

</p>

</div>

</div>

</div>

</section>

<!-- Fitur -->

<section id="fitur" class="bg-white py-24">

<div class="max-w-7xl mx-auto">

<div class="text-center">

<h2 class="text-4xl font-bold">

Mengapa Memilih BeasiswaPedia?

</h2>

<p class="text-gray-500 mt-4">

Semua kebutuhan pencarian beasiswa dalam satu platform.

</p>

</div>

<div class="grid lg:grid-cols-4 gap-8 mt-16">

<div class="bg-gray-50 rounded-3xl p-8 hover:shadow-xl transition">

<div class="text-5xl">

🎓

</div>

<h3 class="font-bold mt-5">

Informasi Terbaru

</h3>

<p class="text-gray-500 mt-3">

Update setiap hari.

</p>

</div>

<div class="bg-gray-50 rounded-3xl p-8 hover:shadow-xl transition">

<div class="text-5xl">

❤️

</div>

<h3 class="font-bold mt-5">

Bookmark

</h3>

<p class="text-gray-500 mt-3">

Simpan beasiswa favorit.

</p>

</div>

<div class="bg-gray-50 rounded-3xl p-8 hover:shadow-xl transition">

<div class="text-5xl">

📅

</div>

<h3 class="font-bold mt-5">

Deadline

</h3>

<p class="text-gray-500 mt-3">

Tidak akan ketinggalan.

</p>

</div>

<div class="bg-gray-50 rounded-3xl p-8 hover:shadow-xl transition">

<div class="text-5xl">

📄

</div>

<h3 class="font-bold mt-5">

Persyaratan

</h3>

<p class="text-gray-500 mt-3">

Lengkap dan jelas.

</p>

</div>

</div>

</div>

</section>

<footer class="bg-slate-900 text-white py-8">

<div class="max-w-7xl mx-auto text-center">

<h2 class="text-2xl font-bold">

🎓 BeasiswaPedia

</h2>

<p class="text-gray-400 mt-3">

© 2026 BeasiswaPedia. All Rights Reserved.

</p>

</div>

</footer>

</body>
</html>
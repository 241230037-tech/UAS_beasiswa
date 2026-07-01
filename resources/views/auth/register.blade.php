<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - BeasiswaPedia</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100">

<div class="min-h-screen flex">

    <!-- LEFT -->
    <div class="hidden lg:flex w-1/2 bg-gradient-to-br from-green-600 via-emerald-600 to-blue-700 text-white p-16 flex-col justify-center">

        <h1 class="text-5xl font-extrabold mb-6">
            🎓 BeasiswaPedia
        </h1>

        <p class="text-xl text-green-100 leading-relaxed">

            Bergabung sekarang dan mulai perjalananmu menuju beasiswa impian.

        </p>

        <div class="mt-12">

            <div class="bg-white/10 backdrop-blur rounded-3xl p-8">

                <h3 class="text-2xl font-semibold mb-5">

                    Keuntungan Menjadi Member

                </h3>

                <ul class="space-y-4 text-green-100">

                    <li>🎓 Akses informasi beasiswa terbaru</li>

                    <li>❤️ Simpan beasiswa favorit</li>

                    <li>📅 Pengingat deadline pendaftaran</li>

                    <li>📈 Pantau status pengajuan</li>

                </ul>

            </div>

        </div>

    </div>

    <!-- RIGHT -->

    <div class="w-full lg:w-1/2 flex justify-center items-center bg-white">

        <div class="w-full max-w-lg px-8 py-10">

            <h2 class="text-4xl font-bold text-gray-800">

                Buat Akun Baru

            </h2>

            <p class="text-gray-500 mt-2 mb-8">

                Daftarkan dirimu untuk mulai menggunakan BeasiswaPedia.

            </p>

            <form method="POST" action="{{ route('register') }}">

                @csrf

                <!-- Nama -->

                <div class="mb-5">

                    <label class="block mb-2 font-medium">

                        Nama Lengkap

                    </label>

                    <input
                        type="text"
                        name="name"
                        value="{{ old('name') }}"
                        required
                        autofocus
                        class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:ring-2 focus:ring-green-500 focus:outline-none">

                    @error('name')
                        <small class="text-red-500">{{ $message }}</small>
                    @enderror

                </div>

                <!-- Email -->

                <div class="mb-5">

                    <label class="block mb-2 font-medium">

                        Email

                    </label>

                    <input
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:ring-2 focus:ring-green-500 focus:outline-none">

                    @error('email')
                        <small class="text-red-500">{{ $message }}</small>
                    @enderror

                </div>

                <!-- Password -->

                <div class="mb-5">

                    <label class="block mb-2 font-medium">

                        Password

                    </label>

                    <input
                        type="password"
                        name="password"
                        required
                        class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:ring-2 focus:ring-green-500 focus:outline-none">

                    @error('password')
                        <small class="text-red-500">{{ $message }}</small>
                    @enderror

                </div>

                <!-- Konfirmasi Password -->

                <div class="mb-8">

                    <label class="block mb-2 font-medium">

                        Konfirmasi Password

                    </label>

                    <input
                        type="password"
                        name="password_confirmation"
                        required
                        class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:ring-2 focus:ring-green-500 focus:outline-none">

                </div>

                <!-- Tombol -->

                <button
                    type="submit"
                    class="w-full rounded-xl bg-green-600 py-3 text-white font-semibold hover:bg-green-700 transition duration-300">

                    Daftar Sekarang

                </button>

            </form>

            <p class="text-center text-gray-500 mt-8">

                Sudah punya akun?

                <a
                    href="{{ route('login') }}"
                    class="font-semibold text-blue-600 hover:underline">

                    Login di sini

                </a>

            </p>

        </div>

    </div>

</div>

</body>
</html>
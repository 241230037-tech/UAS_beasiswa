<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - BeasiswaPedia</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100">

<div class="min-h-screen flex">

    <!-- Left -->
    <div class="hidden lg:flex w-1/2 bg-gradient-to-br from-blue-700 to-indigo-600 text-white p-16 flex-col justify-center">

        <h1 class="text-5xl font-extrabold mb-6">
            🎓 BeasiswaPedia
        </h1>

        <p class="text-xl leading-relaxed text-blue-100">
            Temukan berbagai informasi beasiswa dalam negeri maupun luar negeri
            dengan mudah dan cepat.
        </p>

        <div class="mt-10">

            <div class="bg-white/10 rounded-3xl p-8 backdrop-blur">

                <h3 class="text-2xl font-semibold mb-4">
                    Mengapa BeasiswaPedia?
                </h3>

                <ul class="space-y-3 text-blue-100">

                    <li>✔ Informasi beasiswa terbaru</li>

                    <li>✔ Simpan beasiswa favorit</li>

                    <li>✔ Pantau deadline pendaftaran</li>

                    <li>✔ Mudah digunakan mahasiswa</li>

                </ul>

            </div>

        </div>

    </div>

    <!-- Right -->
    <div class="w-full lg:w-1/2 flex justify-center items-center bg-white">

        <div class="w-full max-w-md px-8">

            <h2 class="text-4xl font-bold text-gray-800">
                Selamat Datang 👋
            </h2>

            <p class="text-gray-500 mt-2 mb-8">
                Login untuk melanjutkan ke BeasiswaPedia.
            </p>

            <form method="POST" action="{{ route('login') }}">

                @csrf

                <div class="mb-5">

                    <label class="block mb-2 font-medium">
                        Email
                    </label>

                    <input
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        autofocus
                        class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:outline-none">

                    @error('email')
                        <small class="text-red-500">{{ $message }}</small>
                    @enderror

                </div>

                <div class="mb-5">

                    <label class="block mb-2 font-medium">
                        Password
                    </label>

                    <input
                        type="password"
                        name="password"
                        required
                        class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:outline-none">

                    @error('password')
                        <small class="text-red-500">{{ $message }}</small>
                    @enderror

                </div>

                <div class="flex items-center justify-between mb-6">

                    <label class="flex items-center gap-2">

                        <input
                            type="checkbox"
                            name="remember"
                            class="rounded border-gray-300">

                        <span class="text-sm">
                            Ingat saya
                        </span>

                    </label>

                    @if(Route::has('password.request'))

                        <a
                            href="{{ route('password.request') }}"
                            class="text-blue-600 text-sm hover:underline">

                            Lupa Password?

                        </a>

                    @endif

                </div>

                <button
                    class="w-full rounded-xl bg-blue-600 py-3 font-semibold text-white hover:bg-blue-700 transition">

                    Masuk

                </button>

            </form>

            <p class="text-center mt-8 text-gray-500">

                Belum punya akun?

                <a
                    href="{{ route('register') }}"
                    class="text-blue-600 font-semibold">

                    Daftar Sekarang

                </a>

            </p>

        </div>

    </div>

</div>

</body>
</html>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <title>BeasiswaPedia</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap -->
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">

    <!-- Font Awesome -->
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    @vite(['resources/css/app.css','resources/js/app.js'])

</head>

<body class="hold-transition sidebar-mini">

<div class="wrapper">

    @include('layouts.navbar')

    @include('layouts.sidebar')

    <div class="content-wrapper">

        <section class="content pt-3">

            <div class="container-fluid">

                @yield('content')

            </div>

        </section>

    </div>

    @include('layouts.footer')

</div>

<script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>

</body>

<form method="POST" action="{{ route('logout') }}" class="mt-auto">
    @csrf

    <button
        type="submit"
        class="flex w-full items-center gap-3 rounded-lg px-4 py-3 text-red-600 hover:bg-red-50 transition">

        <svg xmlns="http://www.w3.org/2000/svg"
             class="h-5 w-5"
             fill="none"
             viewBox="0 0 24 24"
             stroke="currentColor">

            <path stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h6a2 2 0 012 2v1"/>

        </svg>

        Logout

    </button>
</form>

</html>
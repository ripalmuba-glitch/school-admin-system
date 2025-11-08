<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-100 dark:bg-gray-900 h-full">

    {{-- Ini adalah layout untuk halaman tamu (Lupa Password, Verifikasi Email) --}}

    <div class="min-h-screen flex items-center justify-center p-6 sm:p-12"
         style="background-image: url('{{ asset('images/school-bg.jpg') }}'); background-size: cover; background-position: center;">

        <div class="w-full max-w-md bg-white dark:bg-gray-800 shadow-xl rounded-lg p-8">

            {{-- PERBAIKAN LOGO DI SINI --}}
            <div class="mb-8 flex items-center justify-center lg:justify-start">
                <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/1/1a/Lambang_Tut_Wuri_Handayani_rev_2.svg/1200px-Lambang_Tut_Wuri_Handayani_rev_2.svg.png"
                     alt="Logo Tut Wuri Handayani" class="h-20 w-20 mr-4">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                        SI ADMIN SEKOLAH
                    </h2>
                </div>
            </div>

            {{-- Slot ini akan diisi oleh form Verifikasi Email / Lupa Password --}}
            {{ $slot }}

        </div>
    </div>
</body>
</html>

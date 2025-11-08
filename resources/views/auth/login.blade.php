@php
    // Kita tidak menggunakan x-guest-layout bawaan, kita buat layout kustom di sini
    // untuk mengontrol tata letak dua kolom.
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} | Masuk Admin Sekolah</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-100 dark:bg-gray-900 h-full">

    <div class="min-h-screen flex">

        <div class="hidden lg:flex w-1/2 bg-indigo-700 text-white p-12 items-center justify-center relative">

            <div class="absolute inset-0 bg-cover bg-center opacity-20" style="background-image: url('{{ asset('images/school-bg.jpg') }}');"></div>

            <div class="relative z-10 max-w-md">
                <h1 class="text-4xl font-bold mb-4">
                    Ayo Masuk
                </h1>

                {{-- Kutipan Ki Hajar Dewantara --}}
                <blockquote class="text-xl italic border-l-4 border-white pl-4 my-6">
                    "Apapun yang dilakukan oleh seseorang itu, hendaknya dapat bermanfaat bagi dirinya sendiri, bermanfaat bagi bangsanya, dan bermanfaat bagi manusia di dunia pada umumnya."
                </blockquote>
                <p class="text-lg font-semibold">
                    — Ki Hajar Dewantara
                </p>
            </div>

            <footer class="absolute bottom-4 left-4 text-sm opacity-80">
                Copyright © {{ date('Y') }}. Sistem Informasi Administrasi Sekolah.
            </footer>
        </div>

        <div class="w-full lg:w-1/2 flex items-center justify-center p-6 sm:p-12">
            <div class="w-full max-w-md">

                <div class="mb-8 flex items-center justify-center lg:justify-start">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/1/1a/Lambang_Tut_Wuri_Handayani_rev_2.svg/1200px-Lambang_Tut_Wuri_Handayani_rev_2.svg.png"
                         alt="Logo Tut Wuri Handayani" class="h-20 w-20 mr-4">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                            Laman Masuk
                        </h2>
                        <p class="text-lg text-gray-600 dark:text-gray-400">
                            Sistem Administrasi Sekolah
                        </p>
                    </div>
                </div>

                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div>
                        <x-input-label for="email" :value="__('Akun Pengguna (Email)')" />
                        <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <div class="mt-4">
                        <x-input-label for="password" :value="__('Kata Sandi')" />

                        <x-text-input id="password" class="block mt-1 w-full"
                                        type="password"
                                        name="password"
                                        required autocomplete="current-password" />

                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <div class="block mt-4 flex justify-between items-center">
                        <label for="remember_me" class="inline-flex items-center">
                            <input id="remember_me" type="checkbox" class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800" name="remember">
                            <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Ingat Saya') }}</span>
                        </label>

                        @if (Route::has('password.request'))
                            <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('password.request') }}">
                                {{ __('Lupa Kata Sandi?') }}
                            </a>
                        @endif
                    </div>

                    <div class="flex items-center justify-end mt-6">
                        <x-primary-button class="w-full justify-center py-2 text-lg">
                            {{ __('Masuk') }}
                        </x-primary-button>
                    </div>

                    {{-- Tautan Register Dihapus karena hanya Admin yang bisa membuat akun --}}
                </form>
            </div>
        </div>
    </div>
</body>
</html>

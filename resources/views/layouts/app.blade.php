<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-gray-100 dark:bg-gray-200">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* Sembunyikan scrollbar untuk Chrome, Safari, dan Opera */
        .sidebar-scroll::-webkit-scrollbar {
            display: none;
        }
        /* Sembunyikan scrollbar untuk IE, Edge, dan Firefox */
        .sidebar-scroll {
            -ms-overflow-style: none;  /* IE dan Edge */
            scrollbar-width: none;  /* Firefox */
        }
    </style>

    {{-- PERBAIKAN PWA: Hapus @pwaAIO dan tambahkan link manifest secara manual --}}
    <link rel="manifest" href="{{ asset('/manifest.json') }}">

</head>
<body class="font-sans antialiased">

    <div x-data="{
            sidebarOpen: false,
            adminMenuOpen: @json(request()->routeIs('admin.dashboard') || request()->routeIs('admin.users.*') || request()->routeIs('admin.settings.*')),
            masterDataMenuOpen: @json(request()->routeIs('admin.classrooms.*') || request()->routeIs('admin.academicyears.*') || request()->routeIs('admin.teachers.*') || request()->routeIs('admin.students.*') || request()->routeIs('admin.subjects.*')),
            akademikMenuOpen: @json(request()->routeIs('admin.schedules.*') || request()->routeIs('admin.attendances.*') || request()->routeIs('admin.grades.*') || request()->routeIs('admin.promotions.*')),
            keuanganMenuOpen: @json(request()->routeIs('admin.payment_types.*') || request()->routeIs('admin.payments.*'))
         }"
         @keydown.escape.window="sidebarOpen = false">

        @include('layouts.sidebar')

        <div class="lg:pl-64 flex flex-col flex-1">

            @include('layouts.navigation-top')

            <main class="flex-1 py-6">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    {{-- Slot Header (Judul Halaman) --}}
                    @if (isset($header))
                        <h1 class="text-2xl font-semibold text-gray-900 mb-6">{{ $header }}</h1>
                    @endif

                    {{-- Slot Konten (Isi Halaman) --}}
                    {{ $slot }}
                </div>
            </main>
        </div>
    </div>

    {{-- PERBAIKAN PWA: Tambahkan skrip pendaftaran Service Worker --}}
    <script>
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('/sw.js')
                .then(function(registration) {
                    console.log('PWA Service Worker registered with scope:', registration.scope);
                })
                .catch(function(error) {
                    console.log('PWA Service Worker registration failed:', error);
                });
        }
    </script>
</body>
</html>

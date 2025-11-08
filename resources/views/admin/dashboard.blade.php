<x-app-layout>
    {{-- Slot Header untuk judul halaman --}}
    <x-slot name="header">
        Dashboard
    </x-slot>

    {{-- Konten Utama Dashboard --}}
    <div class="space-y-6">

        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 text-center">
            {{-- Ganti dengan URL Logo Sekolah Anda --}}
            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/1/1a/Lambang_Tut_Wuri_Handayani_rev_2.svg/1200px-Lambang_Tut_Wuri_Handayani_rev_2.svg.png"
                 alt="Logo Sekolah" class="h-24 w-24 mx-auto mb-4">
            <h2 class="text-3xl font-bold text-gray-900 dark:text-white">
                SELAMAT DATANG DI SI ADMINGUR
            </h2>
            <p class="text-lg text-gray-600 dark:text-gray-400 mt-2">
                Sistem Informasi Administrasi Guru {{ config('app.name') }}
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

            {{-- Card 1: Guru Aktif --}}
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-blue-500 p-3 rounded-full">
                        <x-icons.user-circle class="h-6 w-6 text-white" />
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">GURU AKTIF</p>
                        {{-- TODO: Ganti 53 dengan data dinamis --}}
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">53</p>
                    </div>
                </div>
                <div class="mt-4 border-t border-gray-200 dark:border-gray-700 pt-3">
                    <a href="#" class="text-sm text-blue-500 hover:underline">Lihat Selengkapnya &rarr;</a>
                </div>
            </div>

            {{-- Card 2: Siswa Aktif --}}
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-green-500 p-3 rounded-full">
                        <x-icons.user class="h-6 w-6 text-white" />
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">SISWA AKTIF</KBD></p>
                        {{-- TODO: Ganti 64 dengan data dinamis --}}
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">640</p>
                    </div>
                </div>
                <div class="mt-4 border-t border-gray-200 dark:border-gray-700 pt-3">
                    <a href="#" class="text-sm text-green-500 hover:underline">Lihat Selengkapnya &rarr;</a>
                </div>
            </div>

            {{-- Card 3: Kelas --}}
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-yellow-500 p-3 rounded-full">
                        <x-icons.building class="h-6 w-6 text-white" />
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">KELAS</p>
                        {{-- TODO: Ganti 9 dengan data dinamis --}}
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">9</p>
                    </div>
                </div>
                <div class="mt-4 border-t border-gray-200 dark:border-gray-700 pt-3">
                    <a href="{{ route('admin.classrooms.index') }}" class="text-sm text-yellow-500 hover:underline">Lihat Selengkapnya &rarr;</a>
                </div>
            </div>

            {{-- Card 4: Mata Pelajaran --}}
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-red-500 p-3 rounded-full">
                        <x-icons.book class="h-6 w-6 text-white" />
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">MATA PELAJARAN</p>
                        {{-- TODO: Ganti 14 dengan data dinamis --}}
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">14</p>
                    </div>
                </div>
                <div class="mt-4 border-t border-gray-200 dark:border-gray-700 pt-3">
                    <a href="#" class="text-sm text-red-500 hover:underline">Lihat Selengkapnya &rarr;</a>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>

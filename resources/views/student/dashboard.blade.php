<x-student-layout>
    <x-slot name="header">
        Dasbor
    </x-slot>

    <div class="space-y-6">

        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
            <h2 class="text-3xl font-bold text-gray-900 dark:text-white">
                Selamat Datang, {{ $student->full_name }}!
            </h2>
            <p class="text-lg text-gray-600 dark:text-gray-400 mt-2">
                Kelas Anda saat ini: <strong>{{ $currentClassroom->name ?? 'Belum Ditentukan' }}</strong>
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

            {{-- Card 1: Jadwal Pelajaran --}}
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-blue-500 p-3 rounded-full">
                        <x-icons.schedule class="h-6 w-6 text-white" />
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">JADWAL PELAJARAN</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $scheduleCount }} Mata Pelajaran</p>
                    </div>
                </div>
                <div class="mt-4 border-t border-gray-200 dark:border-gray-700 pt-3">
                    <a href="#" class="text-sm text-blue-500 hover:underline">Lihat Jadwal &rarr;</a>
                </div>
            </div>

            {{-- Card 2: Absensi --}}
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-yellow-500 p-3 rounded-full">
                        <x-icons.check-circle class="h-6 w-6 text-white" />
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">ABSENSI (S/I/A)</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $attendanceSummary }} Hari</p>
                    </div>
                </div>
                <div class="mt-4 border-t border-gray-200 dark:border-gray-700 pt-3">
                    <a href="#" class="text-sm text-yellow-500 hover:underline">Lihat Rekap Absensi &rarr;</a>
                </div>
            </div>

            {{-- Card 3: Tagihan --}}
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-red-500 p-3 rounded-full">
                        <x-icons.dollar class="h-6 w-6 text-white" />
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">TAGIHAN BELUM LUNAS</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $unpaidBills }} Tagihan</p>
                    </div>
                </div>
                <div class="mt-4 border-t border-gray-200 dark:border-gray-700 pt-3">
                    <a href="#" class="text-sm text-red-500 hover:underline">Lihat Detail Tagihan &rarr;</a>
                </div>
            </div>

        </div>
    </div>
</x-student-layout>

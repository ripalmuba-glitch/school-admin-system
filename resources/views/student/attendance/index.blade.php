<x-student-layout>
    <x-slot name="header">
        Laporan Absensi
    </x-slot>

    <div class="space-y-6">

        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white">
                Rekapitulasi Absensi
            </h2>
            <p class="text-gray-600 dark:text-gray-400">
                Tahun Ajaran Aktif ({{ $activeYear->year_name ?? 'N/A' }}). Hanya menampilkan Sakit, Izin, dan Alpa.
            </p>
        </div>

        @forelse ($summaryBySubject as $subjectName => $attendances)
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
                <div class="p-6 border-b dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                        {{ $subjectName }}
                    </h3>
                </div>

                <table class="min-w-full">
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach ($attendances as $attendance)
                            <tr class="flex justify-between items-center px-6 py-4">
                                <td class="text-sm font-medium">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                        @if($attendance->status == 'Sakit') bg-yellow-100 text-yellow-800
                                        @elseif($attendance->status == 'Izin') bg-blue-100 text-blue-800
                                        @else bg-red-100 text-red-800 @endif">
                                        {{ $attendance->status }}
                                    </span>
                                </td>
                                <td class="text-sm text-gray-900 dark:text-white font-bold">
                                    {{ $attendance->total_days }} Hari
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @empty
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                <p class="text-center text-gray-500 dark:text-gray-400">
                    Selamat! Tidak ada catatan absensi (Sakit, Izin, Alpa) untuk Anda di tahun ajaran ini.
                </p>
            </div>
        @endforelse
    </div>
</x-student-layout>

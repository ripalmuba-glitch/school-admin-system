<x-student-layout>
    <x-slot name="header">
        Laporan Nilai (Transkrip)
    </x-slot>

    <div class="space-y-6">

        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white">
                Transkrip Nilai
            </h2>
            <p class="text-gray-600 dark:text-gray-400">
                Tahun Ajaran Aktif ({{ $activeYear->year_name ?? 'N/A' }}).
            </p>
        </div>

        @forelse ($gradesBySubject as $subjectName => $grades)
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
                <div class="p-6 border-b dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                        {{ $subjectName }}
                    </h3>
                    {{-- Menampilkan nama guru (ambil dari nilai pertama, asumsi guru sama) --}}
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        Guru: {{ $grades->first()->teacher->full_name ?? 'N/A' }}
                    </p>
                </div>

                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tipe Penilaian</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Nilai</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach ($grades as $grade)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $grade->grade_type }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300 text-center font-bold text-lg">
                                    {{ number_format($grade->score, 2) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @empty
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                <p class="text-center text-gray-500 dark:text-gray-400">
                    Belum ada data nilai yang diinput untuk Anda di tahun ajaran ini.
                </p>
            </div>
        @endforelse
    </div>
</x-student-layout>

<x-student-layout>
    <x-slot name="header">
        Jadwal Pelajaran
    </x-slot>

    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
        <div class="mb-6">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white">
                Kelas: {{ $currentClassroom->name ?? 'Belum ada kelas' }}
            </h2>
            <p class="text-gray-600 dark:text-gray-400">
                Jadwal pelajaran untuk Tahun Ajaran Aktif ({{ $activeYear->year_name ?? 'N/A' }}).
            </p>
        </div>

        <div class="space-y-6">
            @if ($currentClassroom)
                @forelse ($days as $dayNumber => $dayName)
                    {{-- Hanya tampilkan hari jika ada jadwalnya --}}
                    @if (isset($schedules[$dayNumber]) && $schedules[$dayNumber]->count() > 0)
                        <div class="border rounded-lg dark:border-gray-700 overflow-hidden">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white bg-gray-50 dark:bg-gray-700 p-4 border-b dark:border-gray-600">
                                {{ $dayName }}
                            </h3>
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Jam</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Mata Pelajaran</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Guru</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach ($schedules[$dayNumber] as $schedule)
                                        <tr>
                                            <td class="px-6 py-4 w-1/4 text-sm font-medium text-gray-900 dark:text-white">
                                                {{ $schedule->start_time->format('H:i') }} - {{ $schedule->end_time->format('H:i') }}
                                            </td>
                                            <td class="px-6 py-4 w-1/2 text-sm text-gray-700 dark:text-gray-300">
                                                {{ $schedule->subject->name }}
                                            </td>
                                            <td class="px-6 py-4 w-1/4 text-sm text-gray-700 dark:text-gray-300">
                                                {{ $schedule->teacher->full_name }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                @empty
                    {{-- Jika $days kosong (seharusnya tidak mungkin) --}}
                    <p class="text-center text-gray-500 dark:text-gray-400">
                        Tidak ada jadwal yang ditemukan.
                    </p>
                @endforelse
            @else
                <p class="text-center text-gray-500 dark:text-gray-400">
                    Anda tidak terdaftar di kelas manapun pada tahun ajaran ini.
                </p>
            @endif
        </div>

    </div>
</x-student-layout>

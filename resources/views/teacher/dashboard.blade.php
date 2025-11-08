{{--
PENTING: Kita menggunakan layout 'teacher' baru kita,
BUKAN 'app-layout' milik admin.
--}}
<x-teacher-layout>
    <x-slot name="header">
        Dasbor (Jadwal Mengajar Saya)
    </x-slot>

    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6" role="alert">
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6" role="alert">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
        <div class="mb-6">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white">
                Selamat Datang, {{ $teacher->full_name }}
            </h2>
            <p class="text-gray-600 dark:text-gray-400">
                Berikut adalah jadwal mengajar Anda untuk Tahun Ajaran Aktif ({{ $activeYear->year_name ?? 'N/A' }}).
            </p>
        </div>

        <div class="space-y-6">
            @forelse ($days as $dayNumber => $dayName)
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
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Kelas</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach ($schedules[$dayNumber] as $schedule)
                                    <tr>
                                        <td class="px-6 py-4 w-1/5 text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $schedule->start_time->format('H:i') }} - {{ $schedule->end_time->format('H:i') }}
                                        </td>
                                        <td class="px-6 py-4 w-2/5 text-sm text-gray-700 dark:text-gray-300">
                                            {{ $schedule->subject->name }}
                                        </td>
                                        <td class="px-6 py-4 w-1/5 text-sm text-gray-700 dark:text-gray-300">
                                            {{ $schedule->classroom->name }}
                                        </td>
                                        <td class="px-6 py-4 w-1/5 text-sm text-gray-700 dark:text-gray-300">
                                            {{-- PERUBAHAN DI SINI: Tombol Ambil Absen --}}
                                            @if ($schedule->day_of_week == now()->dayOfWeekIso)
                                                <a href="{{ route('teacher.attendances.create', $schedule) }}" class="inline-flex items-center px-3 py-1.5 bg-cyan-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-cyan-500">
                                                    Ambil Absen
                                                </a>
                                            @else
                                                <span class="text-xs text-gray-400">Hanya di hari H</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            @empty
                <p class="text-center text-gray-500 dark:text-gray-400">
                    Tidak ada jadwal mengajar yang ditemukan untuk Anda di tahun ajaran ini.
                </p>
            @endforelse
        </div>

    </div>
</x-teacher-layout>

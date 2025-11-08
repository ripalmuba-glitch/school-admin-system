<x-teacher-layout>
    <x-slot name="header">
        Laporan Absensi Saya
    </x-slot>

    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">

        <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-6 text-center">
            Laporan Absensi Saya
        </h2>

        <form method="GET" action="{{ route('teacher.attendances.report') }}" class="mb-6 bg-gray-50 dark:bg-gray-700 p-4 rounded-lg border dark:border-gray-600">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div>
                    <label for="classroom_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Kelas</label>
                    <select name="classroom_id" id="classroom_id" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-cyan-500 focus:ring-cyan-500 dark:bg-gray-900 dark:border-gray-600 dark:text-white">
                        <option value="">Pilih Kelas</option>
                        @foreach ($classrooms as $classroom)
                            <option value="{{ $classroom->id }}" {{ $classroomId == $classroom->id ? 'selected' : '' }}>
                                {{ $classroom->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="subject_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Mata Pelajaran</label>
                    <select name="subject_id" id="subject_id" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-cyan-500 focus:ring-cyan-500 dark:bg-gray-900 dark:border-gray-600 dark:text-white">
                        <option value="">Pilih Mata Pelajaran</option>
                        @foreach ($subjects as $subject)
                            <option value="{{ $subject->id }}" {{ $subjectId == $subject->id ? 'selected' : '' }}>
                                {{ $subject->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="start_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Dari Tanggal</label>
                    <input type="date" name="start_date" id="start_date" value="{{ $startDate }}" required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-cyan-500 focus:ring-cyan-500 dark:bg-gray-900 dark:border-gray-600 dark:text-white">
                </div>
                <div>
                    <label for="end_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Sampai Tanggal</label>
                    <input type="date" name="end_date" id="end_date" value="{{ $endDate }}" required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-cyan-500 focus:ring-cyan-500 dark:bg-gray-900 dark:border-gray-600 dark:text-white">
                </div>
            </div>
            <div class="flex justify-end mt-4">
                <button type="submit" class="w-full md:w-auto inline-flex items-center justify-center px-4 py-2 bg-cyan-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-cyan-600">
                    Tampilkan Laporan
                </button>
            </div>
        </form>

        <div class="overflow-x-auto border rounded-lg dark:border-gray-700">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Nama Siswa</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Catatan</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($attendances as $attendance)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ $attendance->attendance_date->format('d M Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                {{ $attendance->student->full_name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                    @if($attendance->status == 'Hadir') bg-green-100 text-green-800
                                    @elseif($attendance->status == 'Sakit') bg-yellow-100 text-yellow-800
                                    @elseif($attendance->status == 'Izin') bg-blue-100 text-blue-800
                                    @else bg-red-100 text-red-800 @endif">
                                    {{ $attendance->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ $attendance->notes ?? '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                @if($classroomId && $subjectId)
                                    Data absensi tidak ditemukan.
                                @else
                                    Silakan pilih filter di atas untuk menampilkan laporan.
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</x-teacher-layout>

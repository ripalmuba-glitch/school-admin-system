<x-teacher-layout>
    <x-slot name="header">
        Ambil Absensi
    </x-slot>

    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">

        <div class="mb-6 border-b dark:border-gray-700 pb-6">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white">
                Kelas: {{ $schedule->classroom->name }}
            </h2>
            <p class="text-gray-600 dark:text-gray-400">
                Mata Pelajaran: <strong>{{ $schedule->subject->name }}</strong>
            </p>
            <p class="text-gray-600 dark:text-gray-400">
                Jadwal: {{ $schedule->dayName }}, {{ $schedule->start_time->format('H:i') }} - {{ $schedule->end_time->format('H:i') }}
            </p>
            <p class="text-gray-600 dark:text-gray-400">
                Tanggal Absensi: <strong>{{ \Carbon\Carbon::parse($attendance_date)->format('d F Y') }}</strong>
            </p>
        </div>

        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('teacher.attendances.store') }}" method="POST">
            @csrf

            {{-- Data Tersembunyi untuk Form --}}
            <input type="hidden" name="schedule_id" value="{{ $schedule->id }}">
            <input type="hidden" name="classroom_id" value="{{ $schedule->classroom_id }}">
            <input type="hidden" name="subject_id" value="{{ $schedule->subject_id }}">
            <input type="hidden" name="academic_year_id" value="{{ $activeYear->id }}">
            <input type="hidden" name="teacher_id" value="{{ $teacher->id }}">
            <input type="hidden" name="attendance_date" value="{{ $attendance_date }}">

            <div class="overflow-x-auto border rounded-lg dark:border-gray-700">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Nama Siswa</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">NISN</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Catatan</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse ($students as $index => $student)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                    <input type="hidden" name="attendances[{{ $index }}][student_id]" value="{{ $student->id }}">
                                    {{ $student->full_name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    {{ $student->nisn }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <select name="attendances[{{ $index }}][status]"
                                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-cyan-500 focus:ring-cyan-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white text-sm py-1">
                                        <option value="Hadir" {{ $student->status == 'Hadir' ? 'selected' : '' }}>Hadir</option>
                                        <option value="Sakit" {{ $student->status == 'Sakit' ? 'selected' : '' }}>Sakit</option>
                                        <option value="Izin" {{ $student->status == 'Izin' ? 'selected' : '' }}>Izin</option>
                                        <option value="Alpa" {{ $student->status == 'Alpa' ? 'selected' : '' }}>Alpa</option>
                                    </select>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <input type="text" name="attendances[{{ $index }}][notes]"
                                           value="{{ $student->notes ?? '' }}"
                                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-cyan-500 focus:ring-cyan-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white text-sm py-1"
                                           placeholder="Opsional">
                                </td>
                            </tr>
                        @empty
                             <tr>
                                <td colspan="4" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                    Tidak ada siswa yang terdaftar di kelas ini pada tahun ajaran aktif.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="flex justify-end pt-6 space-x-4">
                <a href="{{ route('teacher.dashboard') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-200">
                    Batal
                </a>
                <button type="submit"
                        class="inline-flex items-center px-4 py-2 bg-cyan-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-cyan-600"
                        @if($students->isEmpty()) disabled @endif>
                    Simpan Absensi
                </button>
            </div>
        </form>
    </div>
</x-teacher-layout>

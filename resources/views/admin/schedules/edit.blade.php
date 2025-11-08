<x-app-layout>
    <x-slot name="header">
        Modul Jadwal Pelajaran
    </x-slot>

    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
        <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-6 text-center">
            Edit Jadwal Pelajaran
        </h2>

        <form action="{{ route('admin.schedules.update', $schedule) }}" method="POST" class="space-y-6 max-w-lg mx-auto">
            @csrf
            @method('PUT')

            <div>
                <label for="academic_year_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tahun Ajaran</label>
                <select name="academic_year_id" id="academic_year_id" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-cyan-500 focus:ring-cyan-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    @foreach ($academicYears as $year)
                        <option value="{{ $year->id }}" {{ (old('academic_year_id', $schedule->academic_year_id) == $year->id) ? 'selected' : '' }}>
                            {{ $year->year_name }} {{ $year->is_active ? '(Aktif)' : '' }}
                        </option>
                    @endforeach
                </select>
                @error('academic_year_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="classroom_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Kelas</label>
                <select name="classroom_id" id="classroom_id" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-cyan-500 focus:ring-cyan-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    <option value="">Pilih Kelas</option>
                    @foreach ($classrooms as $classroom)
                        <option value="{{ $classroom->id }}" {{ (old('classroom_id', $schedule->classroom_id) == $classroom->id) ? 'selected' : '' }}>
                            {{ $classroom->name }}
                        </option>
                    @endforeach
                </select>
                @error('classroom_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="day_of_week" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Hari</label>
                <select name="day_of_week" id="day_of_week" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-cyan-500 focus:ring-cyan-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    <option value="">Pilih Hari</option>
                    @foreach ($days as $dayNumber => $dayName)
                        <option value="{{ $dayNumber }}" {{ (old('day_of_week', $schedule->day_of_week) == $dayNumber) ? 'selected' : '' }}>
                            {{ $dayName }}
                        </option>
                    @endforeach
                </select>
                @error('day_of_week') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="subject_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Mata Pelajaran</label>
                <select name="subject_id" id="subject_id" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-cyan-500 focus:ring-cyan-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    <option value="">Pilih Mata Pelajaran</option>
                    @foreach ($subjects as $subject)
                        <option value="{{ $subject->id }}" {{ (old('subject_id', $schedule->subject_id) == $subject->id) ? 'selected' : '' }}>
                            {{ $subject->name }} ({{ $subject->code }})
                        </option>
                    @endforeach
                </select>
                @error('subject_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="teacher_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Guru Pengajar</label>
                <select name="teacher_id" id="teacher_id" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-cyan-500 focus:ring-cyan-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    <option value="">Pilih Guru</option>
                    @foreach ($teachers as $teacher)
                        <option value="{{ $teacher->id }}" {{ (old('teacher_id', $schedule->teacher_id) == $teacher->id) ? 'selected' : '' }}>
                            {{ $teacher->full_name }}
                        </option>
                    @endforeach
                </select>
                @error('teacher_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="start_time" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Jam Mulai</label>
                    <input type="time" name="start_time" id="start_time" value="{{ old('start_time', $schedule->start_time->format('H:i')) }}" required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-cyan-500 focus:ring-cyan-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    @error('start_time') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="end_time" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Jam Selesai</label>
                    <input type="time" name="end_time" id="end_time" value="{{ old('end_time', $schedule->end_time->format('H:i')) }}" required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-cyan-500 focus:ring-cyan-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    @error('end_time') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="flex justify-end space-x-4 pt-4">
                <a href="{{ route('admin.schedules.index', ['classroom_id' => $schedule->classroom_id]) }}" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-200">
                    Batal
                </a>
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-cyan-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-cyan-600">
                    Perbarui Jadwal
                </button>
            </div>
        </form>
    </div>
</x-app-layout>

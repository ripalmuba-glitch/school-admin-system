<x-teacher-layout>
    <x-slot name="header">
        Input Nilai
    </x-slot>

    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">

        <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-6 text-center">
            Input Nilai Siswa
        </h2>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                {{ session('error') }}
            </div>
        @endif

        <form method="GET" action="{{ route('teacher.grades.create') }}" class="mb-6 bg-gray-50 dark:bg-gray-700 p-4 rounded-lg border dark:border-gray-600">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div>
                    <label for="classroom_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Kelas</label>
                    <select name="classroom_id" id="classroom_id" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-cyan-500 focus:ring-cyan-500 dark:bg-gray-900 dark:border-gray-600 dark:text-white">
                        <option value="">Pilih Kelas</option>
                        @foreach ($classrooms as $classroom)
                            <option value="{{ $classroom->id }}" {{ $selectedClassroomId == $classroom->id ? 'selected' : '' }}>
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
                            <option value="{{ $subject->id }}" {{ $selectedSubjectId == $subject->id ? 'selected' : '' }}>
                                {{ $subject->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="grade_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tipe Nilai</label>
                    <select name="grade_type" id="grade_type" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-cyan-500 focus:ring-cyan-500 dark:bg-gray-900 dark:border-gray-600 dark:text-white">
                        <option value="">Pilih Tipe Nilai</option>
                        @foreach ($gradeTypes as $type)
                            <option value="{{ $type }}" {{ $selectedGradeType == $type ? 'selected' : '' }}>
                                {{ $type }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2 bg-cyan-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-cyan-600">
                        Ambil Data Siswa
                    </button>
                </div>
            </div>
        </form>

        @if (count($students) > 0)
            <form action="{{ route('teacher.grades.store') }}" method="POST">
                @csrf
                {{-- Data Tersembunyi untuk Form --}}
                <input type="hidden" name="academic_year_id" value="{{ $activeYear?->id }}">
                <input type="hidden" name="classroom_id" value="{{ $selectedClassroomId }}">
                <input type="hidden" name="subject_id" value="{{ $selectedSubjectId }}">
                <input type="hidden" name="grade_type" value="{{ $selectedGradeType }}">
                <input type="hidden" name="teacher_id" value="{{ $teacher->id }}">

                <div class="overflow-x-auto border rounded-lg dark:border-gray-700">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Nama Siswa</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">NISN</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider" style="width: 15%;">Nilai (0-100)</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach ($students as $index => $student)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                        <input type="hidden" name="grades[{{ $index }}][student_id]" value="{{ $student->id }}">
                                        {{ $student->full_name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        {{ $student->nisn }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <input type="number" name="grades[{{ $index }}][score]"
                                               value="{{ old('grades.'.$index.'.score', $student->score) }}"
                                               min="0" max="100" step="0.01"
                                               class="block w-full rounded-md border-gray-300 shadow-sm focus:border-cyan-500 focus:ring-cyan-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white text-sm py-1"
                                               placeholder="0.00">
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="flex justify-end pt-6">
                    <button type="submit"
                            class="inline-flex items-center px-4 py-2 bg-cyan-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-cyan-600">
                        Simpan Nilai
                    </button>
                </div>
            </form>
        @elseif ($selectedClassroomId && $selectedSubjectId && $selectedGradeType)
            <p class="text-center text-gray-500 dark:text-gray-400">Tidak ada siswa yang ditemukan di kelas ini untuk tahun ajaran aktif.</p>
        @else
             <p class="text-center text-gray-500 dark:text-gray-400">Silakan pilih Kelas, Mata Pelajaran, dan Tipe Nilai, lalu klik "Ambil Data Siswa".</p>
        @endif

    </div>
</x-teacher-layout>

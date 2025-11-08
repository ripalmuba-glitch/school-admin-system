<x-app-layout>
    <x-slot name="header">
        Modul Absensi
    </x-slot>

    {{--
      Komponen ini menggunakan Alpine.js untuk mengambil data siswa secara dinamis
      saat Kelas, Mapel, atau Tanggal diubah.
    --}}
    <div
        x-data="{
            classroom_id: '{{ old('classroom_id', $classrooms->first()?->id) }}',
            subject_id: '{{ old('subject_id', $subjects->first()?->id) }}',
            attendance_date: '{{ old('attendance_date', now()->format('Y-m-d')) }}',
            students: [],
            isLoading: false,
            fetchStudents() {
                if (!this.classroom_id || !this.subject_id || !this.attendance_date) {
                    this.students = [];
                    return;
                }

                this.isLoading = true;
                this.students = [];

                const url = `{{ route('admin.api.students-by-classroom') }}?classroom_id=${this.classroom_id}&subject_id=${this.subject_id}&date=${this.attendance_date}`;

                fetch(url)
                    .then(response => response.json())
                    .then(data => {
                        this.students = data;
                        this.isLoading = false;
                    })
                    .catch(error => {
                        console.error('Error fetching students:', error);
                        this.isLoading = false;
                        alert('Gagal memuat daftar siswa. Cek konsol (F12) untuk detail.');
                    });
            },
            init() {
                this.fetchStudents(); // Muat siswa saat halaman pertama kali dibuka
            }
        }"
        x-init="init()"
        class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">

        <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-6 text-center">
            Ambil Absensi Siswa
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

        <form action="{{ route('admin.attendances.store') }}" method="POST">
            @csrf

            {{-- Data Tersembunyi untuk Form --}}
            <input type="hidden" name="academic_year_id" value="{{ $activeYear?->id }}">
            {{-- Hapus hidden teacher_id --}}

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="classroom_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Kelas</label>
                    <select name="classroom_id" id="classroom_id" x-model="classroom_id" @change="fetchStudents" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-cyan-500 focus:ring-cyan-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <option value="">Pilih Kelas</option>
                        @foreach ($classrooms as $classroom)
                            <option value="{{ $classroom->id }}">{{ $classroom->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="subject_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Mata Pelajaran</label>
                    <select name="subject_id" id="subject_id" x-model="subject_id" @change="fetchStudents" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-cyan-500 focus:ring-cyan-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <option value="">Pilih Mata Pelajaran</option>
                        @foreach ($subjects as $subject)
                            <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="attendance_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tanggal Absensi</label>
                    <input type="date" name="attendance_date" id="attendance_date" x-model="attendance_date" @change="fetchStudents" required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-cyan-500 focus:ring-cyan-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                </div>

                {{-- PERBAIKAN: Tambahkan Dropdown Guru --}}
                <div>
                    <label for="teacher_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Guru Pengajar</label>
                    <select name="teacher_id" id="teacher_id" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-cyan-500 focus:ring-cyan-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <option value="">Pilih Guru Pengajar</option>
                        @foreach ($teachers as $teacher)
                            <option value="{{ $teacher->id }}">{{ $teacher->full_name }}</option>
                        @endforeach
                    </select>
                     @error('teacher_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
            </div>

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

                        {{-- Loading Spinner --}}
                        <template x-if="isLoading">
                            <tr>
                                <td colspan="4" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                    Memuat data siswa...
                                </td>
                            </tr>
                        </template>

                        {{-- Daftar Siswa --}}
                        <template x-for="(student, index) in students" :key="student.id">
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                    <input type="hidden" :name="`attendances[${index}][student_id]`" :value="student.id">
                                    <span x-text="student.full_name"></span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    <span x-text="student.nisn"></span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <select :name="`attendances[${index}][status]`"
                                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-cyan-500 focus:ring-cyan-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white text-sm py-1">
                                        <option value="Hadir" :selected="student.status === 'Hadir'">Hadir</option>
                                        <option value="Sakit" :selected="student.status === 'Sakit'">Sakit</option>
                                        <option value="Izin" :selected="student.status === 'Izin'">Izin</option>
                                        <option value="Alpa" :selected="student.status === 'Alpa'">Alpa</option>
                                    </select>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <input type="text" :name="`attendances[${index}][notes]`"
                                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-cyan-500 focus:ring-cyan-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white text-sm py-1"
                                           placeholder="Opsional">
                                </td>
                            </tr>
                        </template>

                        {{-- Jika tidak ada siswa --}}
                        <template x-if="!isLoading && students.length === 0">
                            <tr>
                                <td colspan="4" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                    Tidak ada siswa di kelas ini atau data filter belum lengkap.
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>

            <div class="flex justify-end pt-6">
                <button type="submit"
                        class="inline-flex items-center px-4 py-2 bg-cyan-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-cyan-600"
                        :disabled="isLoading || students.length === 0">
                    Simpan Absensi
                </button>
            </div>
        </form>
    </div>
</x-app-layout>

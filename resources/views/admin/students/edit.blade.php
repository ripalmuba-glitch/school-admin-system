<x-app-layout>
    <x-slot name="header">
        Modul Siswa
    </x-slot>

    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
        <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-6 text-center">
            Edit Data Siswa: {{ $student->full_name }}
        </h2>

        <form action="{{ route('admin.students.update', $student) }}" method="POST" class="space-y-6 max-w-2xl mx-auto">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- KIRI: DATA PROFIL --}}
                <div class="space-y-6">
                    <p class="text-lg font-semibold text-gray-700 dark:text-gray-300 border-b pb-2">Data Profil Siswa</p>

                    <div>
                        <label for="full_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nama Lengkap</label>
                        <input type="text" name="full_name" id="full_name" value="{{ old('full_name', $student->full_name) }}" required
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-cyan-500 focus:ring-cyan-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        @error('full_name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="nisn" class="block text-sm font-medium text-gray-700 dark:text-gray-300">NISN</label>
                        <input type="text" name="nisn" id="nisn" value="{{ old('nisn', $student->nisn) }}" required
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-cyan-500 focus:ring-cyan-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        @error('nisn') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="gender" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Jenis Kelamin</label>
                        <select name="gender" id="gender" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-cyan-500 focus:ring-cyan-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            <option value="Laki-laki" {{ old('gender', $student->gender) == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="Perempuan" {{ old('gender', $student->gender) == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                        @error('gender') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="place_of_birth" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tempat Lahir (Opsional)</label>
                        <input type="text" name="place_of_birth" id="place_of_birth" value="{{ old('place_of_birth', $student->place_of_birth) }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-cyan-500 focus:ring-cyan-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        @error('place_of_birth') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="date_of_birth" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tanggal Lahir (Opsional)</label>
                        <input type="date" name="date_of_birth" id="date_of_birth" value="{{ old('date_of_birth', $student->date_of_birth?->format('Y-m-d')) }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-cyan-500 focus:ring-cyan-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        @error('date_of_birth') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="parent_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nama Orang Tua (Opsional)</label>
                        <input type="text" name="parent_name" id="parent_name" value="{{ old('parent_name', $student->parent_name) }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-cyan-500 focus:ring-cyan-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        @error('parent_name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="address" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Alamat (Opsional)</label>
                        <textarea name="address" id="address" rows="3"
                                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-cyan-500 focus:ring-cyan-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">{{ old('address', $student->address) }}</textarea>
                        @error('address') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>

                {{-- KANAN: DATA AKUN & PENEMPATAN --}}
                <div class="space-y-6">
                    <p class="text-lg font-semibold text-gray-700 dark:text-gray-300 border-b pb-2">Data Akun Login</p>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Alamat Email</label>
                        <input type="email" name="email" id="email" value="{{ old('email', $student->user->email) }}" required
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-cyan-500 focus:ring-cyan-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Password Baru (Opsional)</label>
                        <input type="password" name="password" id="password"
                               placeholder="Kosongkan jika tidak ingin ganti"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-cyan-500 focus:ring-cyan-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        @error('password') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Konfirmasi Password Baru</label>
                        <input type="password" name="password_confirmation" id="password_confirmation"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-cyan-500 focus:ring-cyan-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    </div>

                    <p class="text-lg font-semibold text-gray-700 dark:text-gray-300 border-b pb-2 pt-4">Penempatan Kelas</p>

                    <div>
                        <label for="classroom_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Kelas</label>
                        <select name="classroom_id" id="classroom_id" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-cyan-500 focus:ring-cyan-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            <option value="">Pilih Kelas</option>
                            @foreach ($classrooms as $classroom)
                                <option value="{{ $classroom->id }}" {{ (old('classroom_id') == $classroom->id) || (!old('classroom_id') && $currentAssignment?->classroom_id == $classroom->id) ? 'selected' : '' }}>
                                    {{ $classroom->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('classroom_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="academic_year_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tahun Ajaran</label>
                        <select name="academic_year_id" id="academic_year_id" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-cyan-500 focus:ring-cyan-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            <option value="">Pilih Tahun Ajaran</option>
                            @foreach ($academicYears as $year)
                                <option value="{{ $year->id }}" {{ (old('academic_year_id') == $year->id) || (!old('academic_year_id') && $currentAssignment?->academic_year_id == $year->id) ? 'selected' : '' }}>
                                    {{ $year->year_name }} {{ $year->is_active ? '(Aktif)' : '' }}
                                </option>
                            @endforeach
                        </select>
                        @error('academic_year_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <div class="flex justify-end space-x-4 pt-4">
                <a href="{{ route('admin.students.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-200">
                    Batal
                </a>
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-cyan-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-cyan-600">
                    Perbarui Siswa
                </button>
            </div>
        </form>
    </div>
</x-app-layout>

<x-app-layout>
    <x-slot name="header">
        Modul Tahun Ajaran
    </x-slot>

    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
        <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-6 text-center">
            Edit Tahun Ajaran: {{ $academicyear->year_name }}
        </h2>

        <form action="{{ route('admin.academicyears.update', $academicyear) }}" method="POST" class="space-y-6 max-w-lg mx-auto">
            @csrf
            @method('PUT')

            <div>
                <label for="year_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nama Tahun Ajaran</label>
                <input type="text" name="year_name" id="year_name" value="{{ old('year_name', $academicyear->year_name) }}" required
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-cyan-500 focus:ring-cyan-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                @error('year_name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="start_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tanggal Mulai</label>
                <input type="date" name="start_date" id="start_date" value="{{ old('start_date', $academicyear->start_date->format('Y-m-d')) }}" required
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-cyan-500 focus:ring-cyan-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                @error('start_date') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="end_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tanggal Berakhir</label>
                <input type="date" name="end_date" id="end_date" value="{{ old('end_date', $academicyear->end_date->format('Y-m-d')) }}" required
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-cyan-500 focus:ring-cyan-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                @error('end_date') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="flex items-center">
                <input id="is_active" name="is_active" type="checkbox" value="1" {{ old('is_active', $academicyear->is_active) ? 'checked' : '' }}
                       class="h-4 w-4 text-cyan-600 border-gray-300 rounded focus:ring-cyan-500">
                <label for="is_active" class="ml-2 block text-sm text-gray-900 dark:text-gray-300">
                    Set sebagai Tahun Ajaran Aktif
                </label>
            </div>


            <div class="flex justify-end space-x-4 pt-4">
                <a href="{{ route('admin.academicyears.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-200">
                    Batal
                </a>
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-cyan-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-cyan-600">
                    Perbarui
                </button>
            </div>
        </form>
    </div>
</x-app-layout>

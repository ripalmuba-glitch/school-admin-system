<x-app-layout>
    <x-slot name="header">
        Modul Kelas
    </x-slot>

    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
        <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-6 text-center">
            Tambah Kelas Baru
        </h2>

        <form action="{{ route('admin.classrooms.store') }}" method="POST" class="space-y-6 max-w-lg mx-auto">
            @csrf

            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nama Kelas (Contoh: X IPA 1)</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-cyan-500 focus:ring-cyan-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                       placeholder="Contoh: X IPA 1">
                @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>


            <div class="flex justify-end space-x-4 pt-4">
                <a href="{{ route('admin.classrooms.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-200">
                    Batal
                </a>
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-cyan-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-cyan-600">
                    Simpan Kelas
                </button>
            </div>
        </form>
    </div>
</x-app-layout>

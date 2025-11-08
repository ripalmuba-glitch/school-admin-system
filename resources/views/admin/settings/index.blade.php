<x-app-layout>
    <x-slot name="header">
        Pengaturan Sekolah
    </x-slot>

    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
        <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-6 text-center">
            Pengaturan Informasi Sekolah
        </h2>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4 max-w-lg mx-auto" role="alert">
                {{ session('success') }}
            </div>
        @endif

        {{-- Form ini harus 'multipart/form-data' untuk handle upload logo --}}
        <form action="{{ route('admin.settings.store') }}" method="POST" class="space-y-6 max-w-lg mx-auto" enctype="multipart/form-data">
            @csrf

            <div>
                <label for="school_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nama Sekolah</label>
                <input type="text" name="school_name" id="school_name"
                       value="{{ old('school_name', $settings['school_name'] ?? '') }}" required
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-cyan-500 focus:ring-cyan-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                @error('school_name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="school_address" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Alamat Sekolah</label>
                <textarea name="school_address" id="school_address" rows="3"
                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-cyan-500 focus:ring-cyan-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">{{ old('school_address', $settings['school_address'] ?? '') }}</textarea>
                @error('school_address') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="school_phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300">No. Telepon</label>
                <input type="text" name="school_phone" id="school_phone"
                       value="{{ old('school_phone', $settings['school_phone'] ?? '') }}"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-cyan-500 focus:ring-cyan-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                @error('school_phone') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="school_logo" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Logo Sekolah</label>

                {{-- Tampilkan Logo Saat Ini (jika ada) --}}
                @if (isset($settings['school_logo']))
                    <div class="my-2">
                        <img src="{{ Storage::url($settings['school_logo']) }}" alt="Logo Saat Ini" class="h-20 w-auto bg-gray-100 p-2 rounded">
                    </div>
                @endif

                <input type="file" name="school_logo" id="school_logo"
                       class="mt-1 block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400">
                <small class="text-gray-500">Kosongkan jika tidak ingin mengubah logo. (Max: 2MB)</small>
                @error('school_logo') <span class="text-red-500 text-sm d-block">{{ $message }}</span> @enderror
            </div>


            <div class="flex justify-end pt-4">
                <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2 bg-cyan-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-cyan-600">
                    Simpan Pengaturan
                </button>
            </div>
        </form>
    </div>
</x-app-layout>

<x-app-layout>
    <x-slot name="header">
        Modul Keuangan
    </x-slot>

    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
        <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-6 text-center">
            Tambah Jenis Pembayaran Baru
        </h2>

        <form action="{{ route('admin.payment_types.store') }}" method="POST" class="space-y-6 max-w-lg mx-auto">
            @csrf

            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nama Pembayaran</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-cyan-500 focus:ring-cyan-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                       placeholder="Contoh: SPP Bulanan">
                @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="academic_year_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tahun Ajaran</label>
                <select name="academic_year_id" id="academic_year_id" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-cyan-500 focus:ring-cyan-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    <option value="">Pilih Tahun Ajaran</option>
                    @foreach ($academicYears as $year)
                        <option value="{{ $year->id }}" {{ (old('academic_year_id') == $year->id) || (!old('academic_year_id') && $year->is_active) ? 'selected' : '' }}>
                            {{ $year->year_name }} {{ $year->is_active ? '(Aktif)' : '' }}
                        </option>
                    @endforeach
                </select>
                @error('academic_year_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="type" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tipe</label>
                <select name="type" id="type" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-cyan-500 focus:ring-cyan-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    <option value="once" {{ old('type') == 'once' ? 'selected' : '' }}>Sekali Bayar (Once)</option>
                    <option value="monthly" {{ old('type') == 'monthly' ? 'selected' : '' }}>Bulanan (Monthly)</option>
                </select>
                @error('type') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nominal (Rp)</label>
                <input type="number" name="amount" id="amount" value="{{ old('amount') }}" required
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-cyan-500 focus:ring-cyan-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                       placeholder="Contoh: 150000">
                @error('amount') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Deskripsi (Opsional)</label>
                <textarea name="description" id="description" rows="3"
                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-cyan-500 focus:ring-cyan-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">{{ old('description') }}</textarea>
                @error('description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>


            <div class="flex justify-end space-x-4 pt-4">
                <a href="{{ route('admin.payment_types.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-200">
                    Batal
                </a>
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-cyan-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-cyan-600">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</x-app-layout>

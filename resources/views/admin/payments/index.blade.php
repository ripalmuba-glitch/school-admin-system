<x-app-layout>
    <x-slot name="header">
        Transaksi Pembayaran
    </x-slot>

    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
        <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-6">
            Cari Siswa
        </h2>

        <p class="text-gray-600 dark:text-gray-400 mb-4">
            Masukkan Nama atau NISN siswa untuk melihat detail tagihan dan melakukan transaksi pembayaran.
        </p>

        <form method="GET" action="{{ route('admin.payments.index') }}" class="flex items-center space-x-2">
            <input type="text" name="search"
                   class="block w-full md:w-1/2 rounded-md border-gray-300 shadow-sm focus:border-cyan-500 focus:ring-cyan-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                   placeholder="Masukkan Nama atau NISN..."
                   value="{{ $search ?? '' }}"
                   required>
            <button type="submit" class="inline-flex items-center px-4 py-2 bg-cyan-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-cyan-600">
                Cari
            </button>
        </form>

        @if ($search)
            <div class="mt-8">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Hasil Pencarian untuk "{{ $search }}"</h3>

                @if ($students->count() > 0)
                    <ul class="divide-y divide-gray-200 dark:divide-gray-700 border-t border-b dark:border-gray-700 mt-4">
                        @foreach ($students as $student)
                            <li class="p-4 flex justify-between items-center hover:bg-gray-50 dark:hover:bg-gray-700">
                                <div>
                                    <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $student->full_name }}</p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">NISN: {{ $student->nisn }}</p>
                                </div>
                                <a href="{{ route('admin.payments.show', $student) }}" class="inline-flex items-center px-3 py-1.5 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500">
                                    Lihat Tagihan
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="mt-4 text-gray-500 dark:text-gray-400">Siswa tidak ditemukan.</p>
                @endif
            </div>
        @endif
    </div>
</x-app-layout>

<x-app-layout>
    <x-slot name="header">
        Proses Kenaikan Kelas
    </x-slot>

    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
        <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-6 text-center">
            Proses Kenaikan Kelas
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

        @if (!$fromYear)
            <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded relative mb-4" role="alert">
                <strong>Peringatan:</strong> Tidak ada Tahun Ajaran yang Aktif. Silakan atur Tahun Ajaran Aktif di <a href="{{ route('admin.academicyears.index') }}" class="font-bold underline">Modul Tahun Ajaran</a>.
            </div>
        @elseif ($toYears->isEmpty())
             <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded relative mb-4" role="alert">
                <strong>Peringatan:</strong> Tidak ada Tahun Ajaran (tujuan) yang ditemukan. Silakan buat Tahun Ajaran baru (misal: 2025/2026) di <a href="{{ route('admin.academicyears.index') }}" class="font-bold underline">Modul Tahun Ajaran</a>.
            </div>
        @else
            <form action="{{ route('admin.promotions.store') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin memproses Kenaikan Kelas? Tindakan ini akan memindahkan siswa ke tahun ajaran baru dan tidak dapat diurungkan dengan mudah.')">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg border dark:border-gray-600">
                    <div>
                        <label for="from_year_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Dari Tahun Ajaran</label>
                        <input type="text" value="{{ $fromYear->year_name }} (Aktif)" disabled
                               class="mt-1 block w-full rounded-md border-gray-300 bg-gray-100 shadow-sm dark:bg-gray-900 dark:border-gray-600 dark:text-gray-300">
                        <input type="hidden" name="from_year_id" value="{{ $fromYear->id }}">
                    </div>
                    <div>
                        <label for="to_year_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Ke Tahun Ajaran</label>
                        <select name="to_year_id" id="to_year_id" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-cyan-500 focus:ring-cyan-500 dark:bg-gray-900 dark:border-gray-600 dark:text-white">
                            <option value="">Pilih Tahun Ajaran Tujuan</option>
                            @foreach ($toYears as $year)
                                <option value="{{ $year->id }}">{{ $year->year_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Pilih Kelas Tujuan</h3>

                <div class="overflow-x-auto border rounded-lg dark:border-gray-700">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Kelas Asal (T.A. {{ $fromYear->year_name }})</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Jumlah Siswa</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Pindahkan Ke Kelas</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse ($classroomsWithStudents as $index => $fromClass)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $fromClass->name }}
                                        <input type="hidden" name="promotions[{{ $index }}][from_classroom_id]" value="{{ $fromClass->id }}">
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        {{ $fromClass->students_count }} Siswa
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <select name="promotions[{{ $index }}][to_classroom_id]" required
                                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-cyan-500 focus:ring-cyan-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white text-sm py-1">
                                            <option value="">Pilih Kelas Tujuan...</option>
                                            @foreach ($allClassrooms as $toClass)
                                                {{-- Logika sederhana: coba pilih kelas yang namanya mirip (misal: X IPA 1 -> XI IPA 1) --}}
                                                @php
                                                    $isSuggested = (str_replace('X ', 'XI ', $fromClass->name) == $toClass->name) || (str_replace('XI ', 'XII ', $fromClass->name) == $toClass->name);
                                                @endphp
                                                <option value="{{ $toClass->id }}" @if($isSuggested) selected @endif>
                                                    {{ $toClass->name }}
                                                </option>
                                            @endforeach
                                            <option value="{{ $fromClass->id }}" class="text-yellow-500">(Tinggal Kelas)</option>
                                        </select>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                        Tidak ada siswa yang terdaftar di kelas manapun pada tahun ajaran aktif ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="flex justify-end pt-6">
                    <button type="submit"
                            class="inline-flex items-center px-6 py-3 bg-cyan-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-cyan-600"
                            @if($classroomsWithStudents->isEmpty()) disabled @endif>
                        Jalankan Proses Kenaikan Kelas
                    </button>
                </div>
            </form>
        @endif
    </div>
</x-app-layout>

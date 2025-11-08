<x-app-layout>
    <x-slot name="header">
        Modul Jadwal Pelajaran
    </x-slot>

    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
            <div>
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Jadwal Pelajaran</h2>
                <p class="text-sm text-gray-600 dark:text-gray-400">Pilih kelas untuk melihat atau mengelola jadwal.</p>
            </div>
            <a href="{{ route('admin.schedules.create') }}" class="mt-4 md:mt-0 inline-flex items-center px-4 py-2 bg-cyan-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-cyan-600 active:bg-cyan-800 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:ring-offset-2 transition ease-in-out duration-150">
                Tambah Jadwal Baru
            </a>
        </div>

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

        <form method="GET" action="{{ route('admin.schedules.index') }}" class="mb-6">
            <label for="classroom_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Pilih Kelas</label>
            <select name="classroom_id" id="classroom_id" class="mt-1 block w-full md:w-1/3 rounded-md border-gray-300 shadow-sm focus:border-cyan-500 focus:ring-cyan-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" onchange="this.form.submit()">
                @foreach ($classrooms as $classroom)
                    <option value="{{ $classroom->id }}" {{ $selectedClassroomId == $classroom->id ? 'selected' : '' }}>
                        {{ $classroom->name }}
                    </option>
                @endforeach
            </select>
        </form>

        <div class="space-y-6">
            @forelse ($days as $dayNumber => $dayName)
                @if (isset($schedules[$dayNumber]) && $schedules[$dayNumber]->count() > 0)
                    <div class="border rounded-lg dark:border-gray-700 overflow-hidden">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white bg-gray-50 dark:bg-gray-700 p-4 border-b dark:border-gray-600">
                            {{ $dayName }}
                        </h3>
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach ($schedules[$dayNumber] as $schedule)
                                    <tr>
                                        <td class="px-6 py-4 w-1/4 text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $schedule->start_time->format('H:i') }} - {{ $schedule->end_time->format('H:i') }}
                                        </td>
                                        <td class="px-6 py-4 w-1/2 text-sm text-gray-700 dark:text-gray-300">
                                            <p class="font-bold">{{ $schedule->subject->name }}</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $schedule->teacher->full_name }}</p>
                                        </td>
                                        <td class="px-6 py-4 w-1/4 text-right text-sm">
                                            <a href="{{ route('admin.schedules.edit', $schedule) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-600 mr-3">Edit</a>
                                            <form action="{{ route('admin.schedules.destroy', $schedule) }}" method="POST" class="inline" onsubmit="return confirm('Yakin hapus jadwal ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-600">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            @empty
                <p class="text-gray-500 dark:text-gray-400">Belum ada jadwal yang dibuat untuk kelas ini.</p>
            @endforelse
        </div>

    </div>
</x-app-layout>

<x-app-layout>
    <x-slot name="header">
        Cetak Rapor: {{ $student->full_name }}
    </x-slot>

    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white">
                    Preview Rapor Siswa
                </h2>
                <p class="text-gray-600 dark:text-gray-400">
                    Tahun Ajaran Aktif ({{ $activeYear->year_name ?? 'N/A' }}).
                </p>
            </div>

            {{-- Tombol Download PDF --}}
            <a href="{{ route('admin.students.report.download', $student) }}"
               class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
               <x-icons.download class="h-4 w-4 mr-2" />
                Download PDF
            </a>
        </div>
    </div>

    {{-- Container untuk preview PDF --}}
    <div class="mt-6 w-full max-w-4xl mx-auto bg-white shadow-lg p-10 border">
        {{-- Memuat template PDF yang sudah diperbaiki --}}
        @include('admin.students.report_card_pdf', [
            'student' => $student,
            'currentClassroom' => $currentClassroom,
            'activeYear' => $activeYear,
            'gradesBySubject' => $gradesBySubject,
            'attendanceSummary' => $attendanceSummary,
            'settings' => $settings,
            'logoPath' => $logoPath
        ])
    </div>

</x-app-layout>

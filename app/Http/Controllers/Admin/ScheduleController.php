<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Models\AcademicYear;
use App\Models\Classroom;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ScheduleController extends Controller
{
    /**
     * Menampilkan daftar jadwal pelajaran.
     * Tampilan utama akan berupa filter per kelas.
     */
    public function index(Request $request): View
    {
        $classrooms = Classroom::orderBy('name')->get();
        $selectedClassroomId = $request->query('classroom_id', $classrooms->first()->id ?? null);

        $schedules = [];
        if ($selectedClassroomId) {
            $schedules = Schedule::where('classroom_id', $selectedClassroomId)
                ->with(['subject', 'teacher'])
                // Urutkan berdasarkan hari, lalu jam mulai
                ->orderBy('day_of_week', 'asc')
                ->orderBy('start_time', 'asc')
                ->get()
                ->groupBy('day_of_week'); // Kelompokkan berdasarkan hari
        }

        // Daftar hari untuk iterasi di view
        $days = [1 => 'Senin', 2 => 'Selasa', 3 => 'Rabu', 4 => 'Kamis', 5 => 'Jumat', 6 => 'Sabtu', 7 => 'Minggu'];

        return view('admin.schedules.index', compact('classrooms', 'selectedClassroomId', 'schedules', 'days'));
    }

    /**
     * Menampilkan form untuk membuat jadwal baru.
     */
    public function create(): View
    {
        $data = $this->getFormData();
        return view('admin.schedules.create', $data);
    }

    /**
     * Menyimpan jadwal baru ke database.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'academic_year_id' => 'required|exists:academic_years,id',
            'classroom_id' => 'required|exists:classrooms,id',
            'subject_id' => 'required|exists:subjects,id',
            'teacher_id' => 'required|exists:teachers,id',
            'day_of_week' => 'required|integer|between:1,7',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);

        // TODO: Tambahkan validasi (conflict check) di sini
        // Cek apakah sudah ada jadwal di kelas/guru/ruangan yang sama di jam yang sama

        Schedule::create($request->all());

        return redirect()->route('admin.schedules.index', ['classroom_id' => $request->classroom_id])
            ->with('success', 'Jadwal pelajaran berhasil ditambahkan.');
    }

    /**
     * Menampilkan form untuk mengedit jadwal.
     */
    public function edit(Schedule $schedule): View
    {
        $data = $this->getFormData();
        return view('admin.schedules.edit', compact('schedule'), $data);
    }

    /**
     * Memperbarui jadwal di database.
     */
    public function update(Request $request, Schedule $schedule): RedirectResponse
    {
        $request->validate([
            'academic_year_id' => 'required|exists:academic_years,id',
            'classroom_id' => 'required|exists:classrooms,id',
            'subject_id' => 'required|exists:subjects,id',
            'teacher_id' => 'required|exists:teachers,id',
            'day_of_week' => 'required|integer|between:1,7',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);

        $schedule->update($request->all());

        return redirect()->route('admin.schedules.index', ['classroom_id' => $request->classroom_id])
            ->with('success', 'Jadwal pelajaran berhasil diperbarui.');
    }

    /**
     * Menghapus jadwal dari database.
     */
    public function destroy(Schedule $schedule): RedirectResponse
    {
        $classroomId = $schedule->classroom_id; // Simpan ID kelas sebelum dihapus
        $schedule->delete();

        return redirect()->route('admin.schedules.index', ['classroom_id' => $classroomId])
            ->with('success', 'Jadwal pelajaran berhasil dihapus.');
    }

    /**
     * Helper untuk mengambil data master untuk form.
     */
    private function getFormData(): array
    {
        return [
            'academicYears' => AcademicYear::orderBy('start_date', 'desc')->get(),
            'classrooms' => Classroom::orderBy('name')->get(),
            'subjects' => Subject::orderBy('name')->get(),
            'teachers' => Teacher::orderBy('full_name')->get(),
            'days' => [
                1 => 'Senin', 2 => 'Selasa', 3 => 'Rabu',
                4 => 'Kamis', 5 => 'Jumat', 6 => 'Sabtu', 7 => 'Minggu'
            ],
        ];
    }
}

<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\AcademicYear;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ScheduleController extends Controller
{
    /**
     * Menampilkan jadwal pelajaran siswa.
     */
    public function index(): View
    {
        // 1. Dapatkan data Siswa yang sedang login
        $student = Student::where('user_id', Auth::id())->firstOrFail();

        // 2. Dapatkan Tahun Ajaran Aktif
        $activeYear = AcademicYear::where('is_active', true)->firstOrFail();

        // 3. Ambil data kelas siswa saat ini
        $currentClassroom = $student->classrooms()
                                ->wherePivot('academic_year_id', $activeYear->id)
                                ->first();

        $schedules = [];
        if ($currentClassroom) {
            // 4. Ambil jadwal untuk kelas tersebut di tahun ajaran aktif
            $schedules = Schedule::where('classroom_id', $currentClassroom->id)
                ->where('academic_year_id', $activeYear->id)
                ->with(['subject', 'teacher'])
                ->orderBy('day_of_week', 'asc')
                ->orderBy('start_time', 'asc')
                ->get()
                ->groupBy('day_of_week'); // Kelompokkan berdasarkan hari
        }

        // Daftar hari untuk iterasi di view
        $days = [1 => 'Senin', 2 => 'Selasa', 3 => 'Rabu', 4 => 'Kamis', 5 => 'Jumat', 6 => 'Sabtu', 7 => 'Minggu'];

        return view('student.schedule.index', compact(
            'student',
            'schedules',
            'days',
            'activeYear',
            'currentClassroom'
        ));
    }
}

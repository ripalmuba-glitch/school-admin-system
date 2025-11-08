<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\AcademicYear;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Menampilkan dasbor untuk Siswa.
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

        // 4. Ambil data ringkasan untuk "Kartu"

        // Jumlah absensi (Alpa/Izin/Sakit) di tahun ajaran aktif
        $attendanceSummary = $student->attendances()
            ->where('academic_year_id', $activeYear->id)
            ->whereIn('status', ['Alpa', 'Izin', 'Sakit'])
            ->count();

        // Jumlah jadwal pelajaran di kelasnya
        $scheduleCount = $currentClassroom ? $currentClassroom->schedules()
                            ->where('academic_year_id', $activeYear->id)
                            ->count() : 0;

        // Jumlah tagihan yang belum lunas
        $unpaidBills = $student->bills()
            ->where('academic_year_id', $activeYear->id)
            ->where('status', '!=', 'paid')
            ->count();


        return view('student.dashboard', compact(
            'student',
            'currentClassroom',
            'attendanceSummary',
            'scheduleCount',
            'unpaidBills'
        ));
    }
}

<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\AcademicYear;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AttendanceController extends Controller
{
    /**
     * Menampilkan laporan absensi siswa.
     */
    public function index(): View
    {
        // 1. Dapatkan data Siswa yang sedang login
        $student = Student::where('user_id', Auth::id())->firstOrFail();

        // 2. Dapatkan Tahun Ajaran Aktif
        $activeYear = AcademicYear::where('is_active', true)->firstOrFail();

        // 3. Ambil data absensi siswa di tahun ajaran aktif
        // Kita hitung jumlah (COUNT) dan kelompokkan (GROUP BY)
        // berdasarkan mata pelajaran dan status.
        $attendanceSummary = $student->attendances()
            ->where('academic_year_id', $activeYear->id)
            ->whereIn('status', ['Sakit', 'Izin', 'Alpa']) // Hanya hitung S, I, A
            ->with('subject') // Ambil data mata pelajaran
            ->select(
                'subject_id',
                'status',
                DB::raw('COUNT(status) as total_days') // Hitung jumlah hari
            )
            ->groupBy('subject_id', 'status')
            ->orderBy('subject_id')
            ->get();

        // 4. Kelompokkan hasil di atas berdasarkan nama mata pelajaran
        // Hasilnya akan seperti:
        // [ "Matematika" => [ ["status" => "Sakit", "total_days" => 2], ["status" => "Alpa", "total_days" => 1] ], ... ]
        $summaryBySubject = $attendanceSummary->groupBy('subject.name');

        return view('student.attendance.index', compact(
            'student',
            'summaryBySubject',
            'activeYear'
        ));
    }
}

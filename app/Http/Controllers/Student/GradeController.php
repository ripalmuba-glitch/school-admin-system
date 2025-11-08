<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\AcademicYear;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class GradeController extends Controller
{
    /**
     * Menampilkan laporan nilai (transkrip) siswa.
     */
    public function index(): View
    {
        // 1. Dapatkan data Siswa yang sedang login
        $student = Student::where('user_id', Auth::id())->firstOrFail();

        // 2. Dapatkan Tahun Ajaran Aktif
        $activeYear = AcademicYear::where('is_active', true)->firstOrFail();

        // 3. Ambil semua data nilai siswa di tahun ajaran aktif
        $grades = $student->grades()
            ->where('academic_year_id', $activeYear->id)
            ->with(['subject', 'teacher']) // Muat relasi subjek dan guru
            ->orderBy('subject_id') // Urutkan berdasarkan ID mapel
            ->get();

        // 4. Kelompokkan nilai berdasarkan nama mata pelajaran
        $gradesBySubject = $grades->groupBy('subject.name');

        return view('student.grades.index', compact(
            'student',
            'gradesBySubject',
            'activeYear'
        ));
    }
}

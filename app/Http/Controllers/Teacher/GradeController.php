<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Grade;
use App\Models\Classroom;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\AcademicYear;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class GradeController extends Controller
{
    /**
     * Menampilkan halaman utama untuk input nilai (pilih kelas, mapel, tipe nilai).
     */
    public function create(Request $request): View
    {
        // 1. Ambil data Guru yang login
        $teacher = Teacher::where('user_id', Auth::id())->firstOrFail();

        // 2. Ambil Tahun Ajaran Aktif
        $activeYear = AcademicYear::where('is_active', true)->firstOrFail();

        // 3. Ambil data master HANYA yang diajar guru ini
        // (Kita gunakan relasi yang sudah kita buat di Model Teacher)
        $classrooms = $teacher->classrooms()->orderBy('name')->get();
        $subjects = $teacher->subjects()->orderBy('name')->get();

        // 4. Tipe Nilai
        $gradeTypes = ['Tugas Harian', 'Ulangan Harian', 'UTS', 'UAS'];

        // 5. Ambil data filter dari request (jika ada)
        $selectedClassroomId = $request->query('classroom_id');
        $selectedSubjectId = $request->query('subject_id');
        $selectedGradeType = $request->query('grade_type');

        $students = [];
        if ($selectedClassroomId && $activeYear) {
            // 6. Ambil siswa di kelas & tahun ajaran aktif
            $classroom = Classroom::find($selectedClassroomId);
            $students = $classroom->students()
                            ->wherePivot('academic_year_id', $activeYear->id)
                            ->orderBy('full_name')
                            ->get();

            // 7. Ambil nilai yang sudah ada (jika ada)
            if ($selectedSubjectId && $selectedGradeType) {
                $existingGrades = Grade::where('classroom_id', $selectedClassroomId)
                                ->where('subject_id', $selectedSubjectId)
                                ->where('grade_type', $selectedGradeType)
                                ->where('academic_year_id', $activeYear->id)
                                ->where('teacher_id', $teacher->id) // Hanya nilai yg diinput guru ini
                                ->pluck('score', 'student_id');

                // Gabungkan data siswa dengan nilai yang sudah ada
                $students = $students->map(function($student) use ($existingGrades) {
                    $student->score = $existingGrades[$student->id] ?? null;
                    return $student;
                });
            }
        }

        return view('teacher.grades.create', compact(
            'classrooms', 'subjects', 'activeYear', 'teacher', 'gradeTypes',
            'selectedClassroomId', 'selectedSubjectId', 'selectedGradeType', 'students'
        ));
    }

    /**
     * Menyimpan data nilai dari form guru.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'classroom_id' => 'required|exists:classrooms,id',
            'subject_id' => 'required|exists:subjects,id',
            'academic_year_id' => 'required|exists:academic_years,id',
            'teacher_id' => 'required|exists:teachers,id',
            'grade_type' => 'required|string',
            'grades' => 'required|array',
            'grades.*.student_id' => 'required|exists:students,id',
            'grades.*.score' => 'nullable|numeric|min:0|max:100',
        ]);

        // Pastikan guru yang submit adalah guru yang login
        $teacher = Teacher::where('user_id', Auth::id())->firstOrFail();
        if ($request->teacher_id != $teacher->id) {
             return back()->with('error', 'Kesalahan autentikasi guru.');
        }

        $gradesData = $request->input('grades');

        DB::beginTransaction();
        try {
            foreach ($gradesData as $data) {
                // Hanya simpan jika nilai diisi
                if (isset($data['score']) && $data['score'] !== null) {
                    Grade::updateOrCreate(
                        [
                            'student_id' => $data['student_id'],
                            'subject_id' => $request->subject_id,
                            'academic_year_id' => $request->academic_year_id,
                            'grade_type' => $request->grade_type,
                        ],
                        [
                            'classroom_id' => $request->classroom_id,
                            'teacher_id' => $request->teacher_id,
                            'score' => $data['score'],
                            'notes' => $data['notes'] ?? null,
                        ]
                    );
                }
            }

            DB::commit();

            // Redirect kembali ke halaman yang sama dengan filter terisi
            return redirect()->route('teacher.grades.create', [
                'classroom_id' => $request->classroom_id,
                'subject_id' => $request->subject_id,
                'grade_type' => $request->grade_type,
            ])->with('success', 'Nilai berhasil disimpan.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menyimpan nilai: ' . $e->getMessage());
        }
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
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
// --- IMPORT BARU ---
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AttendanceReportExport;
use Carbon\Carbon;
use Maatwebsite\Excel\Excel as ExcelWriter;
// --------------------

class AttendanceController extends Controller
{
    /**
     * Menampilkan halaman utama untuk mengambil absensi (pilih kelas, mapel, tanggal).
     */
    public function create(): View
    {
        $classrooms = Classroom::orderBy('name')->get();
        $subjects = Subject::orderBy('name')->get();
        $activeYear = AcademicYear::where('is_active', true)->first();
        $teachers = Teacher::orderBy('full_name')->get();

        return view('admin.attendances.create', compact('classrooms', 'subjects', 'activeYear', 'teachers'));
    }

    /**
     * Menyimpan data absensi dari form.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'classroom_id' => 'required|exists:classrooms,id',
            'subject_id' => 'required|exists:subjects,id',
            'academic_year_id' => 'required|exists:academic_years,id',
            'teacher_id' => 'required|exists:teachers,id',
            'attendance_date' => 'required|date',
            'attendances' => 'required|array',
            'attendances.*.student_id' => 'required|exists:students,id',
            'attendances.*.status' => 'required|in:Hadir,Sakit,Izin,Alpa',
        ]);

        $attendancesData = $request->input('attendances');

        DB::beginTransaction();
        try {
            foreach ($attendancesData as $data) {
                Attendance::updateOrCreate(
                    [
                        'student_id' => $data['student_id'],
                        'subject_id' => $request->subject_id,
                        'attendance_date' => $request->attendance_date,
                    ],
                    [
                        'academic_year_id' => $request->academic_year_id,
                        'classroom_id' => $request->classroom_id,
                        'teacher_id' => $request->teacher_id,
                        'status' => $data['status'],
                        'notes' => $data['notes'] ?? null,
                    ]
                );
            }

            DB::commit();

            return redirect()->route('admin.attendances.report', [
                'classroom_id' => $request->classroom_id,
                'subject_id' => $request->subject_id,
                'date' => $request->attendance_date,
            ])->with('success', 'Absensi berhasil disimpan.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menyimpan absensi: ' . $e->getMessage());
        }
    }

    /**
     * Menampilkan halaman Laporan Absensi (Filter).
     */
    public function report(Request $request): View
    {
        $classrooms = Classroom::orderBy('name')->get();
        $subjects = Subject::orderBy('name')->get();

        $classroomId = $request->query('classroom_id');
        $subjectId = $request->query('subject_id');
        $date = $request->query('date', now()->format('Y-m-d'));

        $attendances = [];
        if ($classroomId && $subjectId && $date) {

            $attendances = Attendance::where('attendances.classroom_id', $classroomId)
                ->where('attendances.subject_id', $subjectId)
                ->where('attendances.attendance_date', $date)
                ->join('students', 'attendances.student_id', '=', 'students.id')
                ->with('student')
                ->orderBy('students.full_name', 'asc')
                ->select('attendances.*')
                ->get();
        }

        return view('admin.attendances.report', compact(
            'classrooms', 'subjects', 'attendances',
            'classroomId', 'subjectId', 'date'
        ));
    }

    /**
     * [API/Helper] Mengambil daftar siswa berdasarkan kelas.
     */
    public function getStudentsByClassroom(Request $request)
    {
        // ... (Kode fungsi ini tetap sama, tidak perlu diubah) ...
        $request->validate(['classroom_id' => 'required|exists:classrooms,id']);
        $activeYear = AcademicYear::where('is_active', true)->first();
        if (!$activeYear) {
            return response()->json(['error' => 'Tidak ada tahun ajaran aktif.'], 404);
        }
        $classroom = Classroom::find($request->classroom_id);
        $students = $classroom->students()
                        ->wherePivot('academic_year_id', $activeYear->id)
                        ->orderBy('full_name')
                        ->get(['students.id', 'students.full_name', 'students.nisn']);
        $subjectId = $request->query('subject_id');
        $date = $request->query('date', now()->format('Y-m-d'));
        $existingAttendances = Attendance::where('classroom_id', $request->classroom_id)
                                ->where('subject_id', $subjectId)
                                ->where('attendance_date', $date)
                                ->pluck('status', 'student_id');
        $studentsWithStatus = $students->map(function($student) use ($existingAttendances) {
            $student->status = $existingAttendances[$student->id] ?? 'Hadir';
            return $student;
        });
        return response()->json($studentsWithStatus);
    }

    /**
     * [BARU] Mendownload laporan absensi sebagai Excel atau PDF.
     */
    public function downloadReport(Request $request)
    {
        // Validasi input filter
        $request->validate([
            'classroom_id' => 'required|exists:classrooms,id',
            'subject_id' => 'required|exists:subjects,id',
            'date' => 'required|date',
            'type' => 'required|in:excel,pdf', // Menentukan tipe download
        ]);

        $classroomId = $request->query('classroom_id');
        $subjectId = $request->query('subject_id');
        $date = $request->query('date');

        // 1. Ambil data (query yang sama dengan report())
        $attendances = Attendance::where('attendances.classroom_id', $classroomId)
            ->where('attendances.subject_id', $subjectId)
            ->where('attendances.attendance_date', $date)
            ->join('students', 'attendances.student_id', '=', 'students.id')
            ->with('student')
            ->orderBy('students.full_name', 'asc')
            ->select('attendances.*')
            ->get();

        // 2. Ambil data pendukung untuk judul file
        $classroom = Classroom::find($classroomId);
        $subject = Subject::find($subjectId);
        $dateFormatted = Carbon::parse($date)->format('d-m-Y');

        // 3. Buat instance Export Class
        $export = new AttendanceReportExport($attendances, $classroom, $subject, $date);

        // 4. Tentukan Tipe & Nama File
        $type = $request->query('type');

        if ($type == 'pdf') {
            $fileName = "Laporan Absensi - {$classroom->name} - {$dateFormatted}.pdf";
            // Download PDF
            return Excel::download($export, $fileName, ExcelWriter::DOMPDF);
        } else {
            $fileName = "Laporan Absensi - {$classroom->name} - {$dateFormatted}.xlsx";
            // Download Excel
            return Excel::download($export, $fileName, ExcelWriter::XLSX);
        }
    }
}

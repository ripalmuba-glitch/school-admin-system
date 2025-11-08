<?php

// --- PERBAIKAN NAMESPACE DI SINI ---
namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Classroom;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\AcademicYear;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class AttendanceController extends Controller
{
    /**
     * Menampilkan form untuk MENGAMBIL absensi berdasarkan Jadwal.
     */
    public function create(Schedule $schedule): View
    {
        // 1. Ambil data guru yang login
        $teacher = Teacher::where('user_id', Auth::id())->firstOrFail();

        // 2. Verifikasi apakah guru ini berhak mengakses jadwal ini
        if ($schedule->teacher_id !== $teacher->id) {
            abort(403, 'Anda tidak berhak mengakses jadwal ini.');
        }

        // 3. Ambil Tahun Ajaran Aktif
        $activeYear = AcademicYear::where('is_active', true)->firstOrFail();

        // 4. Load relasi yang diperlukan dari jadwal
        $schedule->load(['classroom', 'subject']);

        // 5. Ambil semua siswa di kelas tersebut PADA TAHUN AJARAN AKTIF
        $students = $schedule->classroom->students()
                        ->wherePivot('academic_year_id', $activeYear->id)
                        ->orderBy('full_name')
                        ->get();

        // 6. Cek apakah absensi sudah pernah diambil untuk hari ini
        $today = now()->format('Y-m-d');
        $existingAttendances = Attendance::where('classroom_id', $schedule->classroom_id)
                                ->where('subject_id', $schedule->subject_id)
                                ->where('attendance_date', $today)
                                ->where('teacher_id', $teacher->id)
                                ->pluck('status', 'student_id');

        // 7. Gabungkan data siswa dengan status absensi yang sudah ada
        $studentsWithStatus = $students->map(function($student) use ($existingAttendances) {
            $student->status = $existingAttendances[$student->id] ?? 'Hadir'; // Default 'Hadir'
            return $student;
        });

        return view('teacher.attendances.create', [
            'schedule' => $schedule,
            'students' => $studentsWithStatus,
            'activeYear' => $activeYear,
            'teacher' => $teacher,
            'attendance_date' => $today,
        ]);
    }

    /**
     * Menyimpan data absensi yang diambil oleh Guru.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            // Data tersembunyi
            'schedule_id' => 'required|exists:schedules,id',
            'classroom_id' => 'required|exists:classrooms,id',
            'subject_id' => 'required|exists:subjects,id',
            'academic_year_id' => 'required|exists:academic_years,id',
            'teacher_id' => 'required|exists:teachers,id',
            'attendance_date' => 'required|date',

            // Data dari form
            'attendances' => 'required|array',
            'attendances.*.student_id' => 'required|exists:students,id',
            'attendances.*.status' => 'required|in:Hadir,Sakit,Izin,Alpa',
        ]);

        $attendancesData = $request->input('attendances');

        DB::beginTransaction();
        try {
            foreach ($attendancesData as $data) {
                // Gunakan updateOrCreate untuk menghindari duplikasi
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

            // Arahkan ke dasbor guru setelah berhasil
            return redirect()->route('teacher.dashboard')
                ->with('success', 'Absensi berhasil disimpan.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menyimpan absensi: ' . $e->getMessage());
        }
    }

    /**
     * Menampilkan halaman Laporan Absensi (khusus guru).
     */
    public function report(Request $request): View
    {
        $teacher = Teacher::where('user_id', Auth::id())->firstOrFail();

        // Ambil data filter dari request
        $subjectId = $request->query('subject_id');
        $classroomId = $request->query('classroom_id');
        $startDate = $request->query('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->query('end_date', now()->endOfMonth()->format('Y-m-d'));

        // Filter data master HANYA yang diajar guru ini
        // Kita perlu mengambil ID mapel dan kelas yang diajar oleh guru ini
        $subjectIds = $teacher->subjects()->pluck('subjects.id');
        $classroomIds = $teacher->classrooms()->pluck('classrooms.id');

        $subjects = Subject::whereIn('id', $subjectIds)->orderBy('name')->get();
        $classrooms = Classroom::whereIn('id', $classroomIds)->orderBy('name')->get();

        $attendances = collect(); // Data kosong by default

        if ($subjectId && $classroomId) {
            // --- PERBAIKAN QUERY DI SINI ---
            $attendances = Attendance::where('attendances.teacher_id', $teacher->id)
                ->where('attendances.classroom_id', $classroomId)
                ->where('attendances.subject_id', $subjectId)
                ->whereBetween('attendances.attendance_date', [$startDate, $endDate])
                ->join('students', 'attendances.student_id', '=', 'students.id') // JOIN
                ->with('student')
                ->orderBy('attendances.attendance_date', 'desc')
                ->orderBy('students.full_name', 'asc') // Urutkan berdasarkan nama
                ->select('attendances.*') // SELECT
                ->get();
        }

        return view('teacher.attendances.report', compact(
            'subjects', 'classrooms', 'attendances',
            'subjectId', 'classroomId', 'startDate', 'endDate'
        ));
    }

    /*
     * [HELPER TAMBAHAN] Kita perlu mengambil data relasi guru ke mapel/kelas
     * Modifikasi model Teacher Anda.
    */
}

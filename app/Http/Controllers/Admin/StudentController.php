<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\User;
use App\Models\Role;
use App\Models\Classroom;
use App\Models\AcademicYear;
use App\Models\Grade;
use App\Models\Attendance;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
// --- HAPUS IMPORT EXCEL ---
// use Maatwebsite\Excel\Facades\Excel;
// use App\Exports\ReportCardExport;
// use Maatwebsite\Excel\Excel as ExcelWriter;
// ------------------------------
// --- IMPORT BARU UNTUK PDF ---
use Barryvdh\DomPDF\Facade\Pdf;
// ------------------------------

class StudentController extends Controller
{
    /**
     * Menampilkan daftar siswa.
     */
    public function index(): View
    {
        $students = Student::with(['user', 'classrooms' => function ($query) {
            $query->orderBy('pivot_academic_year_id', 'desc');
        }])->orderBy('full_name', 'asc')->paginate(10);

        return view('admin.students.index', compact('students'));
    }

    /**
     * Menampilkan form untuk membuat siswa baru.
     */
    public function create(): View
    {
        $classrooms = Classroom::orderBy('name')->get();
        $academicYears = AcademicYear::orderBy('start_date', 'desc')->get();

        return view('admin.students.create', compact('classrooms', 'academicYears'));
    }

    /**
     * Menyimpan siswa baru.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'nisn' => ['required', 'string', 'max:50', 'unique:students,nisn'],
            'gender' => ['required', 'in:Laki-laki,Perempuan'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'classroom_id' => ['required', 'exists:classrooms,id'],
            'academic_year_id' => ['required', 'exists:academic_years,id'],
        ]);

        $siswaRole = Role::where('name', 'Siswa')->first();
        if (!$siswaRole) {
            return back()->with('error', 'Role "Siswa" tidak ditemukan.');
        }

        DB::beginTransaction();
        try {
            $user = User::create([
                'name' => $request->full_name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role_id' => $siswaRole->id,
                'email_verified_at' => now(),
            ]);

            $student = Student::create([
                'user_id' => $user->id,
                'full_name' => $request->full_name,
                'nisn' => $request->nisn,
                'gender' => $request->gender,
                'place_of_birth' => $request->place_of_birth,
                'date_of_birth' => $request->date_of_birth,
                'address' => $request->address,
                'parent_name' => $request->parent_name,
            ]);

            $student->classrooms()->attach($request->classroom_id, [
                'academic_year_id' => $request->academic_year_id
            ]);

            DB::commit();
            return redirect()->route('admin.students.index')->with('success', 'Data Siswa berhasil ditambahkan.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage());
        }
    }

    /**
     * Menampilkan form untuk mengedit data siswa.
     */
    public function edit(Student $student): View
    {
        $student->load('user');
        $classrooms = Classroom::orderBy('name')->get();
        $academicYears = AcademicYear::orderBy('start_date', 'desc')->get();

        $currentAssignment = DB::table('student_class')
                            ->where('student_id', $student->id)
                            ->orderBy('academic_year_id', 'desc')
                            ->first();

        return view('admin.students.edit', compact('student', 'classrooms', 'academicYears', 'currentAssignment'));
    }

    /**
     * Memperbarui data siswa di database.
     */
    public function update(Request $request, Student $student): RedirectResponse
    {
         $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'nisn' => ['required', 'string', 'max:50', 'unique:students,nisn,' . $student->id],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email,' . $student->user_id],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
            'classroom_id' => ['required', 'exists:classrooms,id'],
            'academic_year_id' => ['required', 'exists:academic_years,id'],
        ]);

        DB::beginTransaction();
        try {
            $student->user->update([
                'name' => $request->full_name,
                'email' => $request->email,
                'password' => $request->password ? Hash::make($request->password) : $student->user->password,
            ]);

            $student->update($request->except(['email', 'password', 'classroom_id', 'academic_year_id']));

            $student->classrooms()->sync([
                $request->classroom_id => ['academic_year_id' => $request->academic_year_id]
            ]);

            DB::commit();
            return redirect()->route('admin.students.index')->with('success', 'Data Siswa berhasil diperbarui.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan saat memperbarui data: ' . $e->getMessage());
        }
    }

    /**
     * Menghapus data siswa (dan akun user terkait).
     */
    public function destroy(Student $student): RedirectResponse
    {
        DB::beginTransaction();
        try {
            $student->load('user');
            $user = $student->user;

            $student->classrooms()->detach();
            $student->delete();

            if ($user) {
                $user->delete();
            }

            DB::commit();
            return redirect()->route('admin.students.index')->with('success', 'Data Siswa (dan akun login) berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menghapus data siswa: ' . $e->getMessage());
        }
    }

    // --- FUNGSI CETAK RAPOR ---

    /**
     * [HELPER] Mengambil data rapor.
     */
    private function getReportCardData(Student $student)
    {
        $activeYear = AcademicYear::where('is_active', true)->firstOrFail();
        $currentClassroom = $student->classrooms()
                                ->wherePivot('academic_year_id', $activeYear->id)
                                ->first();
        $grades = $student->grades()
            ->where('academic_year_id', $activeYear->id)
            ->with(['subject', 'teacher'])
            ->orderBy('subject_id')
            ->get();
        $gradesBySubject = $grades->groupBy('subject.name');
        $attendanceSummary = $student->attendances()
            ->where('academic_year_id', $activeYear->id)
            ->whereIn('status', ['Sakit', 'Izin', 'Alpa'])
            ->select('status', DB::raw('COUNT(status) as total_days'))
            ->groupBy('status')
            ->pluck('total_days', 'status');

        $settings = Setting::pluck('value', 'key');

        $logoPath = null;
        if (!empty($settings['school_logo'])) {
            $fullPath = Storage::disk('public')->path($settings['school_logo']);
            if (file_exists($fullPath)) {
                $logoPath = $fullPath;
            }
        }

        return [
            'student' => $student,
            'currentClassroom' => $currentClassroom,
            'activeYear' => $activeYear,
            'gradesBySubject' => $gradesBySubject,
            'attendanceSummary' => $attendanceSummary,
            'settings' => $settings,
            'logoPath' => $logoPath,
        ];
    }

    /**
     * Menampilkan halaman preview Rapor Siswa.
     */
    public function showReportCard(Student $student): View
    {
        $data = $this->getReportCardData($student);
        return view('admin.students.report_card', $data);
    }

    /**
     * [DIPERBARUI] Mengunduh Rapor Siswa sebagai PDF.
     */
    public function downloadReportCard(Student $student)
    {
        $data = $this->getReportCardData($student);

        // Tentukan Nama File
        $fileName = "Rapor - {$student->nisn} - {$student->full_name}.pdf";

        // --- PERBAIKAN LOGIKA DOWNLOAD DI SINI ---
        // Kita gunakan library 'barryvdh/laravel-dompdf'

        $pdf = Pdf::loadView('admin.students.report_card_pdf', $data)
                    ->setPaper('folio', 'portrait'); // Set kertas F4/Folio

        return $pdf->download($fileName);
        // ----------------------------------------
    }
}

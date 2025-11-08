<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\Classroom;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class PromotionController extends Controller
{
    /**
     * Menampilkan halaman Kenaikan Kelas.
     */
    public function index(): View
    {
        // 1. Ambil Tahun Ajaran Aktif (sebagai "Dari Tahun Ajaran")
        $fromYear = AcademicYear::where('is_active', true)->first();

        // 2. Ambil Tahun Ajaran Lainnya (sebagai "Ke Tahun Ajaran")
        $toYears = AcademicYear::where('is_active', false)
                        ->orderBy('start_date', 'desc')
                        ->get();

        // 3. Ambil semua kelas
        $allClassrooms = Classroom::orderBy('name')->get();

        // 4. Ambil kelas yang memiliki siswa di tahun ajaran aktif
        $classroomsWithStudents = collect();
        if ($fromYear) {
            $classroomsWithStudents = Classroom::whereHas('students', function ($query) use ($fromYear) {
                $query->where('student_class.academic_year_id', $fromYear->id);
            })->withCount(['students' => function ($query) use ($fromYear) {
                $query->where('student_class.academic_year_id', $fromYear->id);
            }])->orderBy('name')->get();
        }

        return view('admin.promotions.index', compact(
            'fromYear',
            'toYears',
            'allClassrooms',
            'classroomsWithStudents'
        ));
    }

    /**
     * Menyimpan (menjalankan) proses Kenaikan Kelas.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'from_year_id' => 'required|exists:academic_years,id',
            'to_year_id' => 'required|exists:academic_years,id',
            'promotions' => 'required|array',
            'promotions.*.from_classroom_id' => 'required|exists:classrooms,id',
            'promotions.*.to_classroom_id' => 'required|exists:classrooms,id',
        ]);

        $fromYearId = $request->from_year_id;
        $toYearId = $request->to_year_id;
        $promotions = $request->promotions;

        $promotedCount = 0;

        DB::beginTransaction();
        try {
            foreach ($promotions as $promo) {
                $fromClassId = $promo['from_classroom_id'];
                $toClassId = $promo['to_classroom_id'];

                // 1. Ambil semua ID siswa di kelas "Dari" pada tahun ajaran "Dari"
                $studentIds = DB::table('student_class')
                                ->where('classroom_id', $fromClassId)
                                ->where('academic_year_id', $fromYearId)
                                ->pluck('student_id');

                // 2. Ambil semua siswa berdasarkan ID
                $students = Student::findMany($studentIds);

                // 3. Attach siswa-siswa ini ke kelas "Ke" pada tahun ajaran "Ke"
                foreach ($students as $student) {
                    // Cek dulu apakah siswa sudah ada di kelas tujuan (menghindari duplikat)
                    $exists = $student->classrooms()
                                    ->where('classroom_id', $toClassId)
                                    ->where('academic_year_id', $toYearId)
                                    ->exists();

                    if (!$exists) {
                        // Gunakan attach() untuk menambahkan data baru ke tabel pivot
                        $student->classrooms()->attach($toClassId, [
                            'academic_year_id' => $toYearId
                        ]);
                        $promotedCount++;
                    }
                }
            }

            DB::commit();
            return redirect()->route('admin.promotions.index')
                ->with('success', "Proses kenaikan kelas berhasil. Total $promotedCount siswa telah dipromosikan ke tahun ajaran baru.");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memproses kenaikan kelas: ' . $e->getMessage());
        }
    }
}

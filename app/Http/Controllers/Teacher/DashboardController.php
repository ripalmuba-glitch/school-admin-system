<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\Schedule;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Menampilkan dasbor untuk Guru.
     */
    public function index(): View
    {
        // 1. Dapatkan data Guru yang sedang login
        $teacher = Teacher::where('user_id', Auth::id())->first();

        // 2. Dapatkan Tahun Ajaran Aktif
        $activeYear = AcademicYear::where('is_active', true)->first();

        $schedules = [];
        if ($teacher && $activeYear) {
            // 3. Ambil jadwal guru tsb di tahun ajaran aktif
            $schedules = Schedule::where('teacher_id', $teacher->id)
                ->where('academic_year_id', $activeYear->id)
                ->with(['classroom', 'subject'])
                ->orderBy('day_of_week', 'asc')
                ->orderBy('start_time', 'asc')
                ->get()
                ->groupBy('day_of_week'); // Kelompokkan berdasarkan hari
        }

        // Daftar hari untuk iterasi di view
        $days = [1 => 'Senin', 2 => 'Selasa', 3 => 'Rabu', 4 => 'Kamis', 5 => 'Jumat', 6 => 'Sabtu', 7 => 'Minggu'];

        return view('teacher.dashboard', compact('teacher', 'schedules', 'days', 'activeYear'));
    }
}

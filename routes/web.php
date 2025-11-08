<?php

use App\Http\Controllers\ProfileController;
// ... (Kontroller Admin) ...
use App\Http\Controllers\Admin\AttendanceController;
use App\Http\Controllers\Admin\GradeController;
use App\Http\Controllers\Admin\PaymentTypeController;
use App\Http\Controllers\Admin\PaymentTransactionController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\PromotionController; // <-- IMPORT BARU
// ... (Kontroller Guru) ...
use App\Http\Controllers\Teacher\DashboardController as TeacherDashboardController;
use App\Http\Controllers\Teacher\AttendanceController as TeacherAttendanceController;
use App\Http\Controllers\Teacher\GradeController as TeacherGradeController;
// ... (Kontroller Siswa) ...
use App\Http\Controllers\Student\DashboardController as StudentDashboardController;
use App\Http\Controllers\Student\ScheduleController as StudentScheduleController;
use App\Http\Controllers\Student\GradeController as StudentGradeController;
use App\Http\Controllers\Student\AttendanceController as StudentAttendanceController;
use App\Http\Controllers\Student\PaymentController as StudentPaymentController;
// ------------------------------
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ... (Rute / dan /dashboard) ...
Route::get('/', function () {
    if (auth()->check()) { return redirect()->route('dashboard'); }
    return redirect()->route('login');
});
Route::get('/dashboard', function () {
    $user = auth()->user();
    if ($user->hasRole('Admin')) { return redirect()->route('admin.dashboard'); }
    if ($user->hasRole('Guru')) { return redirect()->route('teacher.dashboard'); }
    if ($user->hasRole('Siswa')) { return redirect()->route('student.dashboard'); }
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


// ======================================================================
// 🔐 GRUP RUTE UNTUK ADMIN
// ======================================================================
Route::middleware(['auth', 'verified', 'role:Admin'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/dashboard', function () { return view('admin.dashboard'); })->name('dashboard');

    // ... (Rute Admin, Data Induk, Akademik, Keuangan) ...
    Route::resource('users', App\Http\Controllers\Admin\UserController::class)->except(['show']);
    Route::resource('classrooms', App\Http\Controllers\Admin\ClassroomController::class)->except(['show']);
    Route::resource('academicyears', App\Http\Controllers\Admin\AcademicYearController::class)->except(['show']);
    Route::resource('teachers', App\Http\Controllers\Admin\TeacherController::class)->except(['show']);
    Route::resource('students', App\Http\Controllers\Admin\StudentController::class)->except(['show']);
    Route::resource('subjects', App\Http\Controllers\Admin\SubjectController::class)->except(['show']);
    Route::resource('schedules', App\Http\Controllers\Admin\ScheduleController::class)->except(['show']);
    Route::get('attendances', [AttendanceController::class, 'report'])->name('attendances.report');
    Route::get('attendances/create', [AttendanceController::class, 'create'])->name('attendances.create');
    Route::post('attendances', [AttendanceController::class, 'store'])->name('attendances.store');
    Route::get('attendances/download', [AttendanceController::class, 'downloadReport'])->name('attendances.download');
    Route::get('grades/create', [GradeController::class, 'create'])->name('grades.create');
    Route::post('grades', [GradeController::class, 'store'])->name('grades.store');
    Route::resource('payment_types', PaymentTypeController::class)->except(['show']);
    Route::get('payments', [PaymentTransactionController::class, 'index'])->name('payments.index');
    Route::get('payments/arrears', [PaymentTransactionController::class, 'arrearsReport'])->name('payments.arrears');
    Route::get('payments/{student}', [PaymentTransactionController::class, 'show'])->name('payments.show');
    Route::post('payments', [PaymentTransactionController::class, 'store'])->name('payments.store');
    Route::get('students/{student}/report', [App\Http\Controllers\Admin\StudentController::class, 'showReportCard'])->name('students.report');
    Route::get('students/{student}/report/download', [App\Http\Controllers\Admin\StudentController::class, 'downloadReportCard'])->name('students.report.download');

    // --- MODUL PENGATURAN ---
    Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('settings', [SettingController::class, 'store'])->name('settings.store');

    // --- MODUL KENAIKAN KELAS (BARU) ---
    Route::get('promotions', [PromotionController::class, 'index'])->name('promotions.index');
    Route::post('promotions', [PromotionController::class, 'store'])->name('promotions.store');

    // Rute API Helper
    Route::get('api/students-by-classroom', [AttendanceController::class, 'getStudentsByClassroom'])
         ->name('api.students-by-classroom');
});

// ... (Grup Rute Guru dan Siswa) ...
// ======================================================================
// 👨‍🏫 GRUP RUTE UNTUK GURU
// ======================================================================
Route::middleware(['auth', 'verified', 'role:Guru'])->prefix('teacher')->name('teacher.')->group(function () {
    Route::get('/dashboard', [TeacherDashboardController::class, 'index'])->name('dashboard');
    Route::get('/attendances/create/{schedule}', [TeacherAttendanceController::class, 'create'])->name('attendances.create');
    Route::post('/attendances', [TeacherAttendanceController::class, 'store'])->name('attendances.store');
    Route::get('/attendances/report', [TeacherAttendanceController::class, 'report'])->name('attendances.report');
    Route::get('/grades/create', [TeacherGradeController::class, 'create'])->name('grades.create');
    Route::post('/grades', [TeacherGradeController::class, 'store'])->name('grades.store');
});
// ======================================================================
// 🎓 GRUP RUTE UNTUK SISWA
// ======================================================================
Route::middleware(['auth', 'verified', 'role:Siswa'])->prefix('student')->name('student.')->group(function () {
    Route::get('/dashboard', [StudentDashboardController::class, 'index'])->name('dashboard');
    Route::get('/schedule', [StudentScheduleController::class, 'index'])->name('schedule.index');
    Route::get('/grades', [StudentGradeController::class, 'index'])->name('grades.index');
    Route::get('/attendance', [StudentAttendanceController::class, 'index'])->name('attendance.index');
    Route::get('/payments', [StudentPaymentController::class, 'index'])->name('payments.index');
});
// ======================================================================
// ⚙️ RUTE PROFIL BAWAAN
// ======================================================================
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
// 🔑 Menyertakan rute otentikasi
require __DIR__.'/auth.php';

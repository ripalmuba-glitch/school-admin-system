<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\AcademicYear;
use App\Models\StudentPaymentBill;
use App\Models\PaymentTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PaymentController extends Controller
{
    /**
     * Menampilkan halaman detail tagihan seorang siswa.
     */
    public function index(): View
    {
        // 1. Dapatkan data Siswa yang sedang login
        $student = Student::where('user_id', Auth::id())->firstOrFail();

        // 2. Dapatkan Tahun Ajaran Aktif
        $activeYear = AcademicYear::where('is_active', true)->firstOrFail();

        // 3. Ambil semua tagihan siswa di tahun ajaran aktif
        // (Logika ini sama dengan di PaymentTransactionController milik Admin)
        $bills = StudentPaymentBill::where('student_id', $student->id)
                    ->where('academic_year_id', $activeYear->id)
                    ->with('paymentType')
                    ->orderBy('payment_type_id')
                    ->orderBy('month')
                    ->get();

        // 4. Pisahkan tagihan "Once" (Sekali) dan "Monthly" (Bulanan)
        $monthlyBills = $bills->where('paymentType.type', 'monthly')->groupBy('paymentType.name');
        $onceBills = $bills->where('paymentType.type', 'once');

        // 5. Ambil riwayat 5 transaksi terakhir
        $recentTransactions = PaymentTransaction::where('student_id', $student->id)
                                ->orderBy('transaction_date', 'desc')
                                ->limit(5)
                                ->with('admin') // Ambil data admin yang memproses
                                ->get();

        return view('student.payments.index', compact(
            'student',
            'activeYear',
            'monthlyBills',
            'onceBills',
            'recentTransactions'
        ));
    }
}

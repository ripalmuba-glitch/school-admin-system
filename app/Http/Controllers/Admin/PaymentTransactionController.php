<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\PaymentType;
use App\Models\PaymentTransaction;
use App\Models\StudentPaymentBill;
use App\Models\AcademicYear;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class PaymentTransactionController extends Controller
{
    /**
     * Menampilkan halaman pencarian siswa untuk transaksi.
     */
    public function index(Request $request): View
    {
        $search = $request->query('search');
        $students = collect(); // Koleksi kosong by default

        if ($search) {
            $students = Student::where('full_name', 'LIKE', "%{$search}%")
                                ->orWhere('nisn', 'LIKE', "%{$search}%")
                                ->limit(10)
                                ->get();
        }

        return view('admin.payments.index', compact('students', 'search'));
    }

    /**
     * Menampilkan halaman detail tagihan seorang siswa.
     */
    public function show(Student $student): View
    {
        // 1. Ambil Tahun Ajaran Aktif
        $activeYear = AcademicYear::where('is_active', true)->first();
        if (!$activeYear) {
            abort(404, 'Tidak ada tahun ajaran aktif.');
        }

        // 2. Generate Tagihan untuk siswa ini jika belum ada
        $this->generateBillsForStudent($student, $activeYear);

        // 3. Ambil semua tagihan siswa di tahun ajaran aktif
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
                                ->get();

        return view('admin.payments.show', compact(
            'student', 'activeYear', 'monthlyBills', 'onceBills', 'recentTransactions'
        ));
    }

    /**
     * Menyimpan transaksi pembayaran baru.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'bill_id' => 'required|exists:student_payment_bills,id',
            'amount' => 'required|numeric|min:1',
            'transaction_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        $bill = StudentPaymentBill::findOrFail($request->bill_id);
        $student = Student::findOrFail($request->student_id);
        $amountToPay = $request->amount;

        // Cek apakah pembayaran melebihi sisa tagihan
        $remainingBill = $bill->amount - $bill->amount_paid;
        if ($amountToPay > $remainingBill) {
            return back()->with('error', 'Jumlah pembayaran melebihi sisa tagihan (Rp. ' . number_format($remainingBill, 0, ',', '.') . ')');
        }

        DB::beginTransaction();
        try {
            // 1. Catat Transaksi
            PaymentTransaction::create([
                'transaction_code' => 'INV-' . time() . '-' . $student->id, // Ganti dengan generator kode yang lebih baik
                'student_id' => $student->id,
                'student_payment_bill_id' => $bill->id,
                'admin_id' => Auth::id(),
                'transaction_date' => $request->transaction_date,
                'amount' => $amountToPay,
                'notes' => $request->notes,
            ]);

            // 2. Update status tagihan (bill)
            $bill->amount_paid += $amountToPay;
            $newRemaining = $bill->amount - $bill->amount_paid;

            if ($newRemaining <= 0) {
                $bill->status = 'paid';
            } else {
                $bill->status = 'partially_paid';
            }

            $bill->save();

            DB::commit();
            return redirect()->route('admin.payments.show', $student->id)
                ->with('success', 'Pembayaran berhasil dicatat.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menyimpan pembayaran: ' . $e->getMessage());
        }
    }


    /**
     * [HELPER] Fungsi untuk men-generate tagihan siswa
     */
    private function generateBillsForStudent(Student $student, AcademicYear $activeYear)
    {
        // 1. Ambil semua tipe pembayaran di tahun ajaran aktif
        $paymentTypes = PaymentType::where('academic_year_id', $activeYear->id)->get();

        foreach ($paymentTypes as $type) {

            if ($type->type == 'monthly') {
                // Tipe Bulanan (SPP): Generate 12 bulan
                for ($month = 1; $month <= 12; $month++) {
                    StudentPaymentBill::firstOrCreate(
                        [
                            'student_id' => $student->id,
                            'payment_type_id' => $type->id,
                            'academic_year_id' => $activeYear->id,
                            'month' => $month,
                        ],
                        [
                            'amount' => $type->amount,
                            'status' => 'unpaid',
                        ]
                    );
                }
            } else {
                // Tipe Sekali Bayar (Uang Gedung): Generate 1 tagihan
                StudentPaymentBill::firstOrCreate(
                    [
                        'student_id' => $student->id,
                        'payment_type_id' => $type->id,
                        'academic_year_id' => $activeYear->id,
                        'month' => null, // null untuk tagihan sekali bayar
                    ],
                    [
                        'amount' => $type->amount,
                        'status' => 'unpaid',
                    ]
                );
            }
        }
    }

    /**
     * [BARU] Menampilkan Laporan Tunggakan Keuangan.
     */
    public function arrearsReport(Request $request): View
    {
        $activeYear = AcademicYear::where('is_active', true)->firstOrFail();

        // Ambil semua tagihan yang 'unpaid' atau 'partially_paid' di tahun aktif
        $arrears = StudentPaymentBill::where('academic_year_id', $activeYear->id)
                    ->where('status', '!=', 'paid')
                    ->with(['student', 'paymentType']) // Eager load relasi
                    ->orderBy(Student::select('full_name')->whereColumn('students.id', 'student_payment_bills.student_id')) // Urutkan berdasarkan Nama Siswa
                    ->orderBy('month', 'asc') // Urutkan berdasarkan Bulan
                    ->paginate(20); // Paginasi data

        return view('admin.payments.arrears', compact('arrears', 'activeYear'));
    }
}

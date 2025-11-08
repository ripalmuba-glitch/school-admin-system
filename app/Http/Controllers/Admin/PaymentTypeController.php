<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentType;
use App\Models\AcademicYear;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class PaymentTypeController extends Controller
{
    /**
     * Menampilkan daftar jenis pembayaran.
     */
    public function index(): View
    {
        $paymentTypes = PaymentType::with('academicYear')
                            ->orderBy('academic_year_id', 'desc')
                            ->orderBy('name', 'asc')
                            ->paginate(10);
        return view('admin.payment_types.index', compact('paymentTypes'));
    }

    /**
     * Menampilkan form untuk membuat jenis pembayaran baru.
     */
    public function create(): View
    {
        $academicYears = AcademicYear::orderBy('start_date', 'desc')->get();
        return view('admin.payment_types.create', compact('academicYears'));
    }

    /**
     * Menyimpan jenis pembayaran baru ke database.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'academic_year_id' => 'required|exists:academic_years,id',
            'type' => 'required|in:once,monthly',
            'amount' => 'required|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        PaymentType::create($request->all());

        return redirect()->route('admin.payment_types.index')
            ->with('success', 'Jenis Pembayaran berhasil ditambahkan.');
    }

    /**
     * Menampilkan form untuk mengedit jenis pembayaran.
     */
    public function edit(PaymentType $paymentType): View
    {
        $academicYears = AcademicYear::orderBy('start_date', 'desc')->get();
        return view('admin.payment_types.edit', compact('paymentType', 'academicYears'));
    }

    /**
     * Memperbarui jenis pembayaran di database.
     */
    public function update(Request $request, PaymentType $paymentType): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'academic_year_id' => 'required|exists:academic_years,id',
            'type' => 'required|in:once,monthly',
            'amount' => 'required|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        $paymentType->update($request->all());

        return redirect()->route('admin.payment_types.index')
            ->with('success', 'Jenis Pembayaran berhasil diperbarui.');
    }

    /**
     * Menghapus jenis pembayaran dari database.
     */
    public function destroy(PaymentType $paymentType): RedirectResponse
    {
        // TODO: Tambahkan cek relasi jika sudah ada transaksi
        // if ($paymentType->transactions()->count() > 0) {
        //     return back()->with('error', 'Tidak bisa dihapus, sudah ada transaksi terkait.');
        // }

        $paymentType->delete();

        return redirect()->route('admin.payment_types.index')
            ->with('success', 'Jenis Pembayaran berhasil dihapus.');
    }
}

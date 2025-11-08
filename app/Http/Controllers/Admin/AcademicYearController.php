<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class AcademicYearController extends Controller
{
    /**
     * Tampilkan daftar Tahun Ajaran.
     */
    public function index(): View
    {
        // Ambil semua tahun ajaran, urutkan berdasarkan tahun terbaru
        $years = AcademicYear::orderBy('start_date', 'desc')->paginate(10);
        return view('admin.academic_years.index', compact('years'));
    }

    /**
     * Tampilkan form untuk membuat Tahun Ajaran baru.
     */
    public function create(): View
    {
        return view('admin.academic_years.create');
    }

    /**
     * Simpan Tahun Ajaran baru ke database.
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'year_name' => 'required|string|max:20|unique:academic_years,year_name',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'is_active' => 'nullable|boolean',
        ]);

        // Konversi checkbox (jika tidak dicentang, nilainya null)
        $data['is_active'] = $request->has('is_active');

        DB::beginTransaction();
        try {
            // Jika tahun ajaran ini akan di-set Aktif, nonaktifkan yang lain
            if ($data['is_active']) {
                AcademicYear::where('is_active', true)->update(['is_active' => false]);
            }

            AcademicYear::create($data);
            DB::commit();

            return redirect()->route('admin.academicyears.index')
                ->with('success', 'Tahun Ajaran berhasil ditambahkan.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menyimpan data: ' . $e->getMessage());
        }
    }

    /**
     * Tampilkan form untuk mengedit Tahun Ajaran.
     */
    public function edit(AcademicYear $academicyear): View
    {
        return view('admin.academic_years.edit', compact('academicyear'));
    }

    /**
     * Perbarui Tahun Ajaran di database.
     */
    public function update(Request $request, AcademicYear $academicyear): RedirectResponse
    {
        $data = $request->validate([
            'year_name' => 'required|string|max:20|unique:academic_years,year_name,' . $academicyear->id,
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'is_active' => 'nullable|boolean',
        ]);

        // Konversi checkbox
        $data['is_active'] = $request->has('is_active');

        DB::beginTransaction();
        try {
            // Jika tahun ajaran ini akan di-set Aktif, nonaktifkan yang lain
            if ($data['is_active']) {
                AcademicYear::where('is_active', true)
                            ->where('id', '!=', $academicyear->id)
                            ->update(['is_active' => false]);
            }

            $academicyear->update($data);
            DB::commit();

            return redirect()->route('admin.academicyears.index')
                ->with('success', 'Tahun Ajaran berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memperbarui data: ' . $e->getMessage());
        }
    }

    /**
     * Hapus Tahun Ajaran dari database.
     */
    public function destroy(AcademicYear $academicyear): RedirectResponse
    {
        // PENTING: Cegah penghapusan tahun ajaran yang sedang aktif
        if ($academicyear->is_active) {
             return back()->with('error', 'Tidak dapat menghapus Tahun Ajaran yang sedang Aktif.');
        }

        // TODO: Tambahkan cek relasi (misal: apakah masih ada siswa/jadwal yang terhubung)
        // if ($academicyear->students()->count() > 0) { ... }

        $academicyear->delete();

        return redirect()->route('admin.academicyears.index')
            ->with('success', 'Tahun Ajaran berhasil dihapus.');
    }
}

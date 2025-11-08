<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Classroom;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ClassroomController extends Controller
{
    /**
     * Tampilkan daftar kelas.
     */
    public function index(): View
    {
        // Ambil data kelas beserta jumlah siswa di setiap kelas
        $classrooms = Classroom::withCount('students')->orderBy('name', 'asc')->paginate(10);
        return view('admin.classrooms.index', compact('classrooms'));
    }

    /**
     * Tampilkan form untuk membuat kelas baru.
     */
    public function create(): View
    {
        return view('admin.classrooms.create');
    }

    /**
     * Simpan kelas baru ke database.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:classrooms,name',
        ], [
            'name.unique' => 'Nama kelas sudah ada.',
            'name.required' => 'Nama kelas wajib diisi.',
        ]);

        Classroom::create($request->all());

        return redirect()->route('admin.classrooms.index')
            ->with('success', 'Kelas berhasil ditambahkan.');
    }

    /**
     * Tampilkan form untuk mengedit kelas.
     */
    public function edit(Classroom $classroom): View
    {
        return view('admin.classrooms.edit', compact('classroom'));
    }

    /**
     * Perbarui kelas di database.
     */
    public function update(Request $request, Classroom $classroom): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:classrooms,name,' . $classroom->id,
        ], [
            'name.unique' => 'Nama kelas sudah ada.',
        ]);

        $classroom->update($request->all());

        return redirect()->route('admin.classrooms.index')
            ->with('success', 'Kelas berhasil diperbarui.');
    }

    /**
     * Hapus kelas dari database.
     */
    public function destroy(Classroom $classroom): RedirectResponse
    {
        // PENTING: Cek apakah kelas masih memiliki siswa
        // Kita perlu me-load relasi 'students' terlebih dahulu
        $classroom->loadCount('students');

        if ($classroom->students_count > 0) {
            return back()->with('error', 'Kelas tidak bisa dihapus karena masih memiliki ' . $classroom->students_count . ' siswa.');
        }

        $classroom->delete();

        return redirect()->route('admin.classrooms.index')
            ->with('success', 'Kelas berhasil dihapus.');
    }
}

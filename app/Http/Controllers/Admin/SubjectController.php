<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class SubjectController extends Controller
{
    /**
     * Menampilkan daftar mata pelajaran.
     */
    public function index(): View
    {
        $subjects = Subject::orderBy('name', 'asc')->paginate(10);
        return view('admin.subjects.index', compact('subjects'));
    }

    /**
     * Menampilkan form untuk membuat mata pelajaran baru.
     */
    public function create(): View
    {
        return view('admin.subjects.create');
    }

    /**
     * Menyimpan mata pelajaran baru ke database.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:subjects,name',
            'code' => 'required|string|max:20|unique:subjects,code',
            'kkm' => 'nullable|integer|min:0|max:100',
        ], [
            'name.unique' => 'Nama mata pelajaran sudah ada.',
            'code.unique' => 'Kode mata pelajaran sudah ada.',
            'kkm.integer' => 'KKM harus berupa angka.',
        ]);

        Subject::create($request->all());

        return redirect()->route('admin.subjects.index')
            ->with('success', 'Mata Pelajaran berhasil ditambahkan.');
    }

    /**
     * Menampilkan form untuk mengedit mata pelajaran.
     */
    public function edit(Subject $subject): View
    {
        return view('admin.subjects.edit', compact('subject'));
    }

    /**
     * Memperbarui mata pelajaran di database.
     */
    public function update(Request $request, Subject $subject): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:subjects,name,' . $subject->id,
            'code' => 'required|string|max:20|unique:subjects,code,' . $subject->id,
            'kkm' => 'nullable|integer|min:0|max:100',
        ], [
            'name.unique' => 'Nama mata pelajaran sudah ada.',
            'code.unique' => 'Kode mata pelajaran sudah ada.',
            'kkm.integer' => 'KKM harus berupa angka.',
        ]);

        $subject->update($request->all());

        return redirect()->route('admin.subjects.index')
            ->with('success', 'Mata Pelajaran berhasil diperbarui.');
    }

    /**
     * Menghapus mata pelajaran dari database.
     */
    public function destroy(Subject $subject): RedirectResponse
    {
        // TODO: Tambahkan cek relasi di sini
        // Misalnya, cek apakah mata pelajaran ini sedang diajar oleh guru
        // atau ada di jadwal pelajaran sebelum dihapus.
        // if ($subject->teachers()->count() > 0) {
        //     return back()->with('error', 'Mata pelajaran tidak bisa dihapus karena masih terkait data guru.');
        // }

        $subject->delete();

        return redirect()->route('admin.subjects.index')
            ->with('success', 'Mata Pelajaran berhasil dihapus.');
    }
}

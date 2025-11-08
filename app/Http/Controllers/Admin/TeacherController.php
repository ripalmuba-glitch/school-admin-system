<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class TeacherController extends Controller
{
    /**
     * Menampilkan daftar semua guru.
     */
    public function index()
    {
        // Ambil guru beserta data user (email) terkait
        $teachers = Teacher::with('user')->orderBy('full_name', 'asc')->paginate(10);
        return view('admin.teachers.index', compact('teachers'));
    }

    /**
     * Menampilkan form untuk membuat guru baru.
     */
    public function create()
    {
        return view('admin.teachers.create');
    }

    /**
     * Menyimpan guru baru (beserta akun user) ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'nip' => ['nullable', 'string', 'max:50', 'unique:teachers,nip'],
            'gender' => ['required', 'in:Laki-laki,Perempuan'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Cari Role 'Guru'
        $guruRole = Role::where('name', 'Guru')->first();
        if (!$guruRole) {
            return back()->with('error', 'Role "Guru" tidak ditemukan. Harap buat role tersebut terlebih dahulu.');
        }

        // Gunakan Transaction untuk memastikan kedua data (User & Teacher) berhasil dibuat
        DB::beginTransaction();
        try {
            // 1. Buat Akun User
            $user = User::create([
                'name' => $request->full_name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role_id' => $guruRole->id,
                'email_verified_at' => now(), // Anggap langsung terverifikasi
            ]);

            // 2. Buat Data Guru
            Teacher::create([
                'user_id' => $user->id,
                'full_name' => $request->full_name,
                'nip' => $request->nip,
                'gender' => $request->gender,
            ]);

            DB::commit(); // Simpan perubahan jika semua berhasil
            return redirect()->route('admin.teachers.index')->with('success', 'Data Guru berhasil ditambahkan.');

        } catch (\Exception $e) {
            DB::rollBack(); // Batalkan semua jika ada kegagalan
            // Tampilkan pesan error
            return back()->with('error', 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage());
        }
    }


    /**
     * Menampilkan form untuk mengedit data guru.
     */
    public function edit(Teacher $teacher)
    {
        // Muat data user terkait untuk ditampilkan di form
        $teacher->load('user');
        return view('admin.teachers.edit', compact('teacher'));
    }

    /**
     * Memperbarui data guru di database.
     */
    public function update(Request $request, Teacher $teacher)
    {
        $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'nip' => ['nullable', 'string', 'max:50', 'unique:teachers,nip,' . $teacher->id],
            'gender' => ['required', 'in:Laki-laki,Perempuan'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email,' . $teacher->user_id],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
        ]);

        DB::beginTransaction();
        try {
            // 1. Update Akun User
            $user = $teacher->user;
            $user->update([
                'name' => $request->full_name,
                'email' => $request->email,
                'password' => $request->password ? Hash::make($request->password) : $user->password,
            ]);

            // 2. Update Data Guru
            $teacher->update([
                'full_name' => $request->full_name,
                'nip' => $request->nip,
                'gender' => $request->gender,
            ]);

            DB::commit();
            return redirect()->route('admin.teachers.index')->with('success', 'Data Guru berhasil diperbarui.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan saat memperbarui data: ' . $e->getMessage());
        }
    }

    /**
     * Menghapus data guru (dan akun user terkait).
     */
    public function destroy(Teacher $teacher)
    {
        DB::beginTransaction();
        try {
            // Hapus data guru akan otomatis menghapus user (jika di-set cascade di migrasi)
            // Jika tidak, kita hapus manual
            $teacher->user()->delete(); // Hapus user
            $teacher->delete(); // Hapus guru

            DB::commit();
            return redirect()->route('admin.teachers.index')->with('success', 'Data Guru berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menghapus data guru.');
        }
    }
}

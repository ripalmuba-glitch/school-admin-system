<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage; // <-- Import untuk file upload

class SettingController extends Controller
{
    /**
     * Menampilkan halaman pengaturan.
     */
    public function index(): View
    {
        // Ambil semua settings dan ubah menjadi format 'key' => 'value'
        $settings = Setting::pluck('value', 'key');

        return view('admin.settings.index', compact('settings'));
    }

    /**
     * Menyimpan atau memperbarui pengaturan.
     */
    public function store(Request $request): RedirectResponse
    {
        $rules = [
            'school_name' => 'required|string|max:255',
            'school_address' => 'nullable|string',
            'school_phone' => 'nullable|string|max:20',
            'school_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // 2MB Max
        ];

        $request->validate($rules);

        // Ambil semua data kecuali token
        $data = $request->except('_token');

        // Handle File Logo
        if ($request->hasFile('school_logo')) {
            // Hapus logo lama jika ada
            $oldLogo = Setting::where('key', 'school_logo')->first();
            if ($oldLogo && $oldLogo->value) {
                Storage::disk('public')->delete($oldLogo->value);
            }

            // Simpan logo baru
            $path = $request->file('school_logo')->store('logos', 'public');
            $data['school_logo'] = $path;
        }

        // Simpan semua data ke database
        foreach ($data as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key], // Cari berdasarkan 'key'
                ['value' => $value] // Update atau Buat dengan 'value' ini
            );
        }

        return redirect()->route('admin.settings.index')
            ->with('success', 'Pengaturan berhasil disimpan.');
    }
}

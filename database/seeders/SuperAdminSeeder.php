<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Cari ID Role 'Admin'
        $adminRole = Role::where('name', 'Admin')->first();

        // Pastikan role 'Admin' ada
        if (!$adminRole) {
            $this->command->error("Role 'Admin' tidak ditemukan. Jalankan RoleSeeder terlebih dahulu.");
            return;
        }

        // --- DATA AKUN ADMIN PERTAMA ---
        User::create([
            'name' => 'Super Admin Sekolah',
            'email' => 'admin@sekolah.com', // <-- USERNAME (Email) Anda
            'password' => Hash::make('admin123'), // <-- PASSWORD Anda
            'role_id' => $adminRole->id,
            'email_verified_at' => now(), // Anggap sudah terverifikasi
        ]);

        $this->command->info('Akun Super Admin berhasil dibuat!');

        // Tampilkan kredensial untuk mempermudah
        $this->command->warn('----------------------------------');
        $this->command->warn('  KREDENSIAL LOGIN ADMIN');
        $this->command->warn('----------------------------------');
        $this->command->warn('  Username/Email: admin@sekolah.com');
        $this->command->warn('  Password:       admin123');
        $this->command->warn('----------------------------------');
    }
}

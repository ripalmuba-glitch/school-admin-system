<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
// use App\Models\User; // Tidak perlu diimpor jika hanya memanggil seeder lain

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Panggil seeder secara berurutan: Roles harus ada sebelum User dibuat.
        $this->call([
            RoleSeeder::class,        // Membuat peran (Admin, Guru, Siswa)
            SuperAdminSeeder::class,  // Membuat akun admin pertama
        ]);

        // Opsional: Jika Anda ingin menggunakan factory untuk data uji lainnya
        // User::factory(10)->create();
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('roles')->insert([
            ['name' => 'Admin', 'description' => 'Administrator Sistem dengan akses penuh.'],
            ['name' => 'Guru', 'description' => 'Guru dan Staf Pengajar.'],
            ['name' => 'Siswa', 'description' => 'Murid/Pelajar sekolah.'],
        ]);
    }
}

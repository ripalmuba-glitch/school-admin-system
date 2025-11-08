<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Tabel Tahun Ajaran
        Schema::create('academic_years', function (Blueprint $table) {
            $table->id();
            $table->string('year_name')->unique(); // e.g., 2024/2025
            $table->date('start_date');
            $table->date('end_date');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Tabel Kelas
        Schema::create('classrooms', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // e.g., X IPA 1
            $table->timestamps();
        });

        // Tabel Mata Pelajaran
        Schema::create('subjects', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('code')->unique();
            $table->integer('kkm')->nullable(); // Kriteria Ketuntasan Minimal
            $table->timestamps();
        });

        // Tabel Siswa
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('nisn')->unique();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade'); // Link ke akun login
            $table->string('full_name');
            $table->enum('gender', ['Laki-laki', 'Perempuan']);
            $table->string('place_of_birth')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->text('address')->nullable();
            $table->string('parent_name')->nullable();
            $table->timestamps();
        });

        // Tabel untuk menautkan Siswa ke Kelas dan Tahun Ajaran
        Schema::create('student_class', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->foreignId('classroom_id')->constrained()->onDelete('cascade');
            $table->foreignId('academic_year_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            $table->unique(['student_id', 'academic_year_id']); // Siswa hanya di satu kelas per tahun
        });

        // Tabel untuk Guru (Menambahkan kolom di tabel users yang sudah ada lebih baik,
        // tetapi untuk memisahkan data spesifik Guru, kita buat tabel ini)
        Schema::create('teachers', function (Blueprint $table) {
            $table->id();
            $table->string('nip')->unique()->nullable(); // Nomor Induk Pegawai
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade'); // Link ke akun login
            $table->string('full_name');
            $table->enum('gender', ['Laki-laki', 'Perempuan']);
            $table->timestamps();
        });

        // Relasi Guru-Mata Pelajaran (Guru bisa mengajar banyak mapel)
        Schema::create('teacher_subject', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_id')->constrained()->onDelete('cascade');
            $table->foreignId('subject_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            $table->unique(['teacher_id', 'subject_id']);
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teacher_subject');
        Schema::dropIfExists('teachers');
        Schema::dropIfExists('student_class');
        Schema::dropIfExists('students');
        Schema::dropIfExists('subjects');
        Schema::dropIfExists('classrooms');
        Schema::dropIfExists('academic_years');
    }
};

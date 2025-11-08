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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();

            // Relasi ke Data Master
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->foreignId('academic_year_id')->constrained()->onDelete('cascade');
            $table->foreignId('classroom_id')->constrained()->onDelete('cascade');
            $table->foreignId('subject_id')->constrained()->onDelete('cascade');
            $table->foreignId('teacher_id')->constrained()->onDelete('cascade'); // Guru yang mengambil absen

            // Informasi Absensi
            $table->date('attendance_date');
            $table->enum('status', ['Hadir', 'Sakit', 'Izin', 'Alpa']);
            $table->text('notes')->nullable(); // Catatan, misal: "Izin acara keluarga"

            $table->timestamps();

            // Mencegah duplikasi data absen (1 siswa hanya 1 status di 1 mapel pada 1 hari)
            $table->unique([
                'student_id',
                'subject_id',
                'attendance_date'
            ], 'student_subject_date_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};

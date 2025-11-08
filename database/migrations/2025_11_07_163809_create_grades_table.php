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
        Schema::create('grades', function (Blueprint $table) {
            $table->id();

            // Relasi ke Data Master
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->foreignId('academic_year_id')->constrained()->onDelete('cascade');
            $table->foreignId('classroom_id')->constrained()->onDelete('cascade');
            $table->foreignId('subject_id')->constrained()->onDelete('cascade');
            $table->foreignId('teacher_id')->constrained()->onDelete('cascade'); // Guru yang memberi nilai

            // Informasi Nilai
            $table->string('grade_type'); // Contoh: 'Tugas Harian', 'UTS', 'UAS'
            $table->decimal('score', 5, 2); // Nilai (misal: 85.50)
            $table->text('notes')->nullable(); // Catatan untuk nilai ini

            $table->timestamps();

            // Mencegah duplikasi (1 siswa, 1 mapel, 1 tipe nilai)
            $table->unique([
                'student_id',
                'subject_id',
                'academic_year_id',
                'grade_type'
            ], 'student_subject_year_grade_type_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grades');
    }
};

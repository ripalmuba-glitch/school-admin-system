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
        // Tabel ini untuk melacak tagihan yang WAJIB dibayar siswa
        Schema::create('student_payment_bills', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->foreignId('payment_type_id')->constrained()->onDelete('cascade');
            $table->foreignId('academic_year_id')->constrained()->onDelete('cascade');

            $table->decimal('amount', 15, 2); // Jumlah tagihan
            $table->decimal('amount_paid', 15, 2)->default(0.00); // Jumlah terbayar

            // Untuk SPP Bulanan, ini akan diisi (1-12)
            $table->integer('month')->nullable();

            $table->enum('status', ['unpaid', 'partially_paid', 'paid'])->default('unpaid');

            $table->timestamps();

            // 1 siswa hanya punya 1 tagihan SPP per bulan
            $table->unique(['student_id', 'payment_type_id', 'academic_year_id', 'month'], 'student_bill_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_payment_bills');
    }
};

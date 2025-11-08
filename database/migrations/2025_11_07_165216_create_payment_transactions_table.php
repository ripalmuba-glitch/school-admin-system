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
        // Tabel ini mencatat SETIAP transaksi yang masuk
        Schema::create('payment_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_code')->unique(); // No. Kuitansi
            $table->foreignId('student_id')->constrained()->onDelete('cascade');

            // Relasi ke tagihan (opsional, bisa null jika pembayaran bebas)
            $table->foreignId('student_payment_bill_id')->nullable()->constrained()->onDelete('set null');

            $table->foreignId('admin_id')->nullable()->constrained('users')->onDelete('set null'); // Admin yang mencatat

            $table->date('transaction_date');
            $table->decimal('amount', 15, 2);
            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_transactions');
    }
};

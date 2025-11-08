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
        // Tabel ini menyimpan semua pengaturan aplikasi
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique(); // 'school_name', 'school_address', 'school_logo'
            $table->text('value')->nullable(); // Nilai dari pengaturan
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};

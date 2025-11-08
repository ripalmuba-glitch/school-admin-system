<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany; // <-- IMPORT BARU

class Student extends Model
{
    use HasFactory;

    /**
     * Atribut yang dapat diisi secara massal.
     */
    protected $fillable = [
        'user_id',
        'nisn',
        'full_name',
        'gender',
        'place_of_birth',
        'date_of_birth',
        'address',
        'parent_name',
    ];

    /**
     * Atribut yang harus di-cast.
     */
    protected function casts(): array
    {
        return [
            'date_of_birth' => 'date',
        ];
    }

    /**
     * Mendapatkan data user (untuk login) yang terkait dengan siswa ini.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Mendapatkan kelas-kelas yang diikuti oleh siswa.
     */
    public function classrooms(): BelongsToMany
    {
        return $this->belongsToMany(Classroom::class, 'student_class')
                    ->withTimestamps()
                    ->withPivot('academic_year_id');
    }

    // --- RELASI BARU UNTUK DASBOR SISWA ---

    /**
     * Mendapatkan semua data absensi siswa.
     */
    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    /**
     * Mendapatkan semua data nilai siswa.
     */
    public function grades(): HasMany
    {
        return $this->hasMany(Grade::class);
    }

    /**
     * Mendapatkan semua data tagihan siswa.
     */
    public function bills(): HasMany
    {
        return $this->hasMany(StudentPaymentBill::class);
    }
}

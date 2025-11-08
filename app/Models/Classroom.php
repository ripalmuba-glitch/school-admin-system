<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany; // <-- PASTIKAN IMPORT INI ADA

class Classroom extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    /**
     * Mendapatkan siswa yang ada di kelas ini.
     * (Relasi ini sudah ada dari sebelumnya)
     */
    public function students(): BelongsToMany
    {
        return $this->belongsToMany(Student::class, 'student_class')
                    ->withTimestamps()
                    ->withPivot('academic_year_id');
    }

    /**
     * [BARU] Mendapatkan semua jadwal pelajaran untuk kelas ini.
     */
    public function schedules(): HasMany
    {
        // Relasi ke tabel 'schedules' melalui 'classroom_id'
        return $this->hasMany(Schedule::class);
    }
}

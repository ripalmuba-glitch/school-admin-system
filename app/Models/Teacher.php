<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany; // <-- IMPORT BARU

class Teacher extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nip',
        'full_name',
        'gender',
    ];

    /**
     * Mendapatkan data user (untuk login) yang terkait dengan guru ini.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Mata pelajaran yang diajar oleh guru ini.
     */
    public function subjects(): BelongsToMany
    {
        // Relasi melalui tabel pivot 'teacher_subject' yang kita buat di awal
        return $this->belongsToMany(Subject::class, 'teacher_subject');
    }

    /**
     * Kelas-kelas yang diajar oleh guru ini (melalui Jadwal).
     */
    public function classrooms(): BelongsToMany
    {
        // Ambil kelas unik yang ada di tabel 'schedules' untuk guru ini
        return $this->belongsToMany(Classroom::class, 'schedules')
                    ->withTimestamps()
                    ->distinct();
    }
}

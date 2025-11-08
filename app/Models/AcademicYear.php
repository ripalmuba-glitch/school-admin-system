<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class AcademicYear extends Model
{
    use HasFactory;

    /**
     * Atribut yang dapat diisi secara massal (mass assignable).
     */
    protected $fillable = [
        'year_name',
        'start_date',
        'end_date',
        'is_active',
    ];

    /**
     * Atribut yang harus di-cast ke tipe bawaan.
     */
    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'is_active' => 'boolean', // Penting untuk menyimpan 0/1 sebagai boolean
        ];
    }

    /**
     * Scope untuk mengambil Tahun Ajaran yang sedang aktif.
     */
    public function scopeActive(Builder $query): void
    {
        $query->where('is_active', true);
    }
}

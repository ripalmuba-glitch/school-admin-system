<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'type',
        'amount',
        'academic_year_id',
    ];

    /**
     * Casts untuk tipe data.
     */
    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
        ];
    }

    // --- RELASI ---

    public function academicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class);
    }
}

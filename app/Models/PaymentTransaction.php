<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_code',
        'student_id',
        'student_payment_bill_id',
        'admin_id',
        'transaction_date',
        'amount',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'transaction_date' => 'date',
            'amount' => 'decimal:2',
        ];
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function admin(): BelongsTo
    {
        // Relasi ke User (Admin)
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function bill(): BelongsTo
    {
        return $this->belongsTo(StudentPaymentBill::class, 'student_payment_bill_id');
    }
}

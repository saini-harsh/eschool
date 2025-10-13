<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    protected $fillable = [
        'institution_id',
        'student_id',
        'fee_structure_id',
        'amount',
        'payment_method',
        'transaction_id',
        'notes',
        'payment_date',
        'receipt_number',
        'status',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payment_date' => 'date',
    ];

    /**
     * Get the institution that owns the payment.
     */
    public function institution(): BelongsTo
    {
        return $this->belongsTo(Institution::class);
    }

    /**
     * Get the student that made the payment.
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Get the fee structure for this payment.
     */
    public function feeStructure(): BelongsTo
    {
        return $this->belongsTo(FeeStructure::class);
    }

    /**
     * Generate a unique receipt number.
     */
    public static function generateReceiptNumber(): string
    {
        do {
            $receiptNumber = 'RCP' . date('Ymd') . rand(1000, 9999);
        } while (self::where('receipt_number', $receiptNumber)->exists());

        return $receiptNumber;
    }
}

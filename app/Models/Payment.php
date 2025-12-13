<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    protected $fillable = [
        'institution_id',
        'student_id',
        'admission_id',
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
     * Get the admission associated with this payment.
     */
    public function admission(): BelongsTo
    {
        return $this->belongsTo(Admission::class);
    }

    /**
     * Get the fee structure for this payment.
     */
    public function feeStructure(): BelongsTo
    {
        return $this->belongsTo(FeeStructure::class);
    }

    /**
     * Generate a unique receipt number with institution code prefix.
     * Format: {INST_CODE}/PAY/{YEAR}{MONTH}{DAY}/{SEQUENTIAL}
     *
     * @param int $institutionId
     * @return string
     */
    public static function generateReceiptNumber($institutionId): string
    {
        $institution = Institution::find($institutionId);
        $institutionCode = $institution->institution_code ?? 'INST';

        $datePart = date('Ymd');

        // Get the last receipt number for this institution and date
        $lastPayment = self::where('institution_id', $institutionId)
            ->where('receipt_number', 'like', $institutionCode . '/PAY/' . $datePart . '/%')
            ->orderBy('id', 'desc')
            ->first();

        if ($lastPayment && $lastPayment->receipt_number) {
            // Extract sequential number
            $parts = explode('/', $lastPayment->receipt_number);
            $lastNumber = isset($parts[3]) ? (int)$parts[3] : 0;
            $sequentialNumber = $lastNumber + 1;
        } else {
            $sequentialNumber = 1;
        }

        $receiptNumber = $institutionCode . '/PAY/' . $datePart . '/' . str_pad($sequentialNumber, 4, '0', STR_PAD_LEFT);

        // Double-check uniqueness (in case of race condition)
        $counter = 0;
        while (self::where('receipt_number', $receiptNumber)->exists() && $counter < 100) {
            $sequentialNumber++;
            $receiptNumber = $institutionCode . '/PAY/' . $datePart . '/' . str_pad($sequentialNumber, 4, '0', STR_PAD_LEFT);
            $counter++;
        }

        return $receiptNumber;
    }
}

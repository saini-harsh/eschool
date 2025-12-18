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
        'discount_amount',
        'discount_percentage',
        'payment_method',
        'transaction_id',
        'notes',
        'payment_date',
        'receipt_number',
        'status',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'discount_percentage' => 'decimal:2',
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

        // Get the last sequential number for this institution (for SF, i.e., School Fee/Student Fee)
        $lastPayment = self::where('institution_id', $institutionId)
            ->where('receipt_number', 'like', $institutionCode . '/SF/%')
            ->orderBy('id', 'desc')
            ->first();

        if ($lastPayment && $lastPayment->receipt_number) {
            $parts = explode('/', $lastPayment->receipt_number);
            $lastNumber = isset($parts[2]) ? (int)ltrim($parts[2], '0') : 0;
            $sequentialNumber = $lastNumber + 1;
        } else {
            $sequentialNumber = 1;
        }

        $receiptNumber = $institutionCode . '/SF/' . str_pad($sequentialNumber, 4, '0', STR_PAD_LEFT);

        // Double-check uniqueness (in case of race condition)
        $counter = 0;
        while (self::where('receipt_number', $receiptNumber)->exists() && $counter < 100) {
            $sequentialNumber++;
            $receiptNumber = $institutionCode . '/SF/' . str_pad($sequentialNumber, 4, '0', STR_PAD_LEFT);
            $counter++;
        }

        return $receiptNumber;
    }

    /**
     * Get the tuition fee payment associated with this payment.
     */
    public function tuitionFeePayment()
    {
        return $this->hasOne(TuitionFeePayment::class, 'receipt_number', 'receipt_number');
    }
    public function hostelPayment()
    {
        return $this->hasOne(HostelPayment::class, 'receipt_number', 'receipt_number');
    }
}

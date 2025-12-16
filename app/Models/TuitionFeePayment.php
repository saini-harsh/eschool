<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class TuitionFeePayment extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'institution_id',
        'student_id',
        'admission_id',
        'fee_structure_id',
        'payment_amount',
        'payment_method',
        'transaction_id',
        'payment_date',
        'receipt_number',
        'selected_months',
        'monthly_fee_amount',
        'number_of_months',
        'status',
        'notes',
    ];

    protected $casts = [
        'payment_amount' => 'decimal:2',
        'monthly_fee_amount' => 'decimal:2',
        'payment_date' => 'date',
        'selected_months' => 'array',
    ];

    /**
     * Get the institution that owns the tuition fee payment.
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
     * Format: {INST_CODE}/TFP/{YEAR}{MONTH}{DAY}/{SEQUENTIAL}
     *
     * @param int $institutionId
     * @return string
     */
    public static function generateReceiptNumber($institutionId): string
    {
        $institution = Institution::find($institutionId);
        $institutionCode = $institution->institution_code ?? 'INST';

        // Get the last sequential number for this institution (for tuition fee payments)
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

        $receiptNumber = $institutionCode . '/TF/' . str_pad($sequentialNumber, 4, '0', STR_PAD_LEFT);

        // Double-check uniqueness
        $counter = 0;
        while (self::where('receipt_number', $receiptNumber)->exists() && $counter < 100) {
            $sequentialNumber++;
            $receiptNumber = $institutionCode . '/TF/' . str_pad($sequentialNumber, 4, '0', STR_PAD_LEFT);
            $counter++;
        }

        return $receiptNumber;
    }

    /**
     * Get the payment associated with this tuition fee payment.
     */
    public function payment()
    {
        return $this->belongsTo(Payment::class, 'receipt_number', 'receipt_number');
    }
}

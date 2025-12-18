<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HostelPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'hostel_id',
        'student_id',
        'institution_id',
        'amount',
        'discount_amount',
        'discount_percentage',
        'payment_date',
        'months_paid',
        'due_amount',
        'payment_type',
        'receipt_number',
        'fee_structure_id'
    ];

    public function hostel(): BelongsTo
    {
        return $this->belongsTo(Hostel::class);
    }

    public function institution(): BelongsTo
    {
        return $this->belongsTo(Institution::class);
    }

    public function feeStructure(): BelongsTo
    {
        return $this->belongsTo(FeeStructure::class);
    }
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    protected $casts = [
        'amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'discount_percentage' => 'decimal:2',
        'due_amount' => 'decimal:2',
        'payment_date' => 'date',
    ];

    public static function generateReceiptNumber($institutionId): string
    {
        $institution = Institution::find($institutionId);
        $institutionCode = $institution->institution_code ?? 'INST';

        // Get the last sequential number for this institution (for SF, i.e., School Fee/Student Fee)
        $lastPayment = self::where('institution_id', $institutionId)
            ->where('receipt_number', 'like', $institutionCode . '/HF/%')
            ->orderBy('id', 'desc')
            ->first();

        if ($lastPayment && $lastPayment->receipt_number) {
            $parts = explode('/', $lastPayment->receipt_number);
            $lastNumber = isset($parts[2]) ? (int)ltrim($parts[2], '0') : 0;
            $sequentialNumber = $lastNumber + 1;
        } else {
            $sequentialNumber = 1;
        }

        $receiptNumber = $institutionCode . '/HF/' . str_pad($sequentialNumber, 4, '0', STR_PAD_LEFT);

        // Double-check uniqueness (in case of race condition)
        $counter = 0;
        while (self::where('receipt_number', $receiptNumber)->exists() && $counter < 100) {
            $sequentialNumber++;
            $receiptNumber = $institutionCode . '/HF/' . str_pad($sequentialNumber, 4, '0', STR_PAD_LEFT);
            $counter++;
        }

        return $receiptNumber;
    }
}

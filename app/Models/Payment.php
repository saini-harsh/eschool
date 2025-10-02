<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'student_fee_id',
        'institution_id',
        'payment_reference',
        'amount',
        'payment_method',
        'payment_status',
        'payment_date',
        'payment_notes',
        'transaction_id',
        'receipt_number',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payment_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function studentFee()
    {
        return $this->belongsTo(StudentFee::class);
    }

    public function institution()
    {
        return $this->belongsTo(Institution::class);
    }

    public static function generatePaymentReference()
    {
        return 'PAY-' . date('Ymd') . '-' . strtoupper(uniqid());
    }

    public static function generateReceiptNumber()
    {
        return 'RCP-' . date('Ymd') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
    }

    // Date helper methods
    public function getFormattedPaymentDate()
    {
        return $this->payment_date ? Carbon::parse($this->payment_date)->format('M d, Y') : 'N/A';
    }

    public function getFormattedPaymentDateWithTime()
    {
        return $this->created_at ? Carbon::parse($this->created_at)->format('M d, Y h:i A') : 'N/A';
    }

    public function getFormattedCreatedAt()
    {
        return $this->created_at ? Carbon::parse($this->created_at)->format('d M, Y') : 'N/A';
    }

    public function isPaymentOverdue()
    {
        if (!$this->studentFee || !$this->studentFee->due_date) {
            return false;
        }
        return Carbon::parse($this->studentFee->due_date)->isPast() && $this->payment_status !== 'completed';
    }

    public function getDaysSincePayment()
    {
        return $this->payment_date ? Carbon::parse($this->payment_date)->diffInDays(now()) : 0;
    }
}

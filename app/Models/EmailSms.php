<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailSms extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'send_through',
        'recipient_type',
        'recipients',
        'institution_id',
        'status',
        'sent_at'
    ];

    protected $casts = [
        'recipients' => 'array',
        'sent_at' => 'datetime',
    ];

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeSent($query)
    {
        return $query->where('status', 'sent');
    }

    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    /**
     * Get the institution that owns the email/sms.
     */
    public function institution()
    {
        return $this->belongsTo(Institution::class);
    }
}

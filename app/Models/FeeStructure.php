<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FeeStructure extends Model
{
    protected $fillable = [
        'name',
        'description',
        'institution_id',
        'class_id',
        'section_id',
        'amount',
        'fee_type',
        'due_date',
        'status',
    ];

    protected $casts = [
        'due_date' => 'date',
        'amount' => 'decimal:2',
        'status' => 'boolean',
    ];

    /**
     * Get the institution that owns the fee structure.
     */
    public function institution(): BelongsTo
    {
        return $this->belongsTo(Institution::class);
    }

    /**
     * Get the class that owns the fee structure.
     */
    public function schoolClass(): BelongsTo
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    /**
     * Get the section that owns the fee structure.
     */
    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentAssignment extends Model
{
    protected $fillable = [
        'assignment_id',
        'student_id',
        'submitted_file',
        'submission_date',
        'status',
        'remarks',
        'marks',
        'feedback',
    ];

    protected $casts = [
        'submission_date' => 'datetime',
        'status' => 'string',
    ];

    /**
     * Get the assignment that owns the submission.
     */
    public function assignment(): BelongsTo
    {
        return $this->belongsTo(Assignment::class);
    }

    /**
     * Get the student that owns the submission.
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
}

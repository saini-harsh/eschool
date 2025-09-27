<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Assignment extends Model
{
    protected $fillable = [
        'title',
        'description',
        'institution_id',
        'teacher_id',
        'class_id',
        'section_id',
        'subject_id',
        'due_date',
        'assignment_file',
        'status',
        'admin_id',
    ];

    protected $casts = [
        'due_date' => 'date',
        'status' => 'boolean',
    ];

    /**
     * Get the institution that owns the assignment.
     */
    public function institution(): BelongsTo
    {
        return $this->belongsTo(Institution::class);
    }

    /**
     * Get the teacher that owns the assignment.
     */
    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class);
    }

    /**
     * Get the class that owns the assignment.
     */
    public function schoolClass(): BelongsTo
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    /**
     * Get the section that owns the assignment.
     */
    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class);
    }

    /**
     * Get the subject that owns the assignment.
     */
    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    /**
     * Get the admin that created the assignment.
     */
    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class);
    }

    /**
     * Get the student submissions for this assignment.
     */
    public function studentAssignments()
    {
        return $this->hasMany(StudentAssignment::class);
    }
}

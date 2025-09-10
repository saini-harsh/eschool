<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssignSubject extends Model
{
    protected $fillable = [
        'institution_id',
        'class_id',
        'section_id',
        'subject_id',
        'teacher_id',
        'status',
    ];
    
    protected $table = 'assign_subjects';
    
    public function institution()
    {
        return $this->belongsTo(Institution::class);
    }
    
    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }
    
    public function section()
    {
        return $this->belongsTo(Section::class, 'section_id');
    }
    
    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }
    
    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id');
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamMark extends Model
{
    protected $fillable = [
        'institution_id',
        'exam_id',
        'class_id',
        'section_id',
        'subject_id',
        'student_id',
        'marks_obtained',
        'total_marks',
        'pass_marks',
        'teacher_id',
    ];

    public function student(){ return $this->belongsTo(Student::class); }
    public function subject(){ return $this->belongsTo(Subject::class); }
    public function exam(){ return $this->belongsTo(Exam::class); }
}


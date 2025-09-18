<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    protected $fillable = [
        'institution_id',
        'exam_type_id',
        'title',
        'code',
        'class_id',
        'section_id',
        'start_date',
        'end_date',
        'morning_time',
        'evening_time',
        'subject_dates',
        'morning_subjects',
        'evening_subjects',
    ];
}

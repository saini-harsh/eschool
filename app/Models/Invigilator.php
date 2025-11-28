<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invigilator extends Model
{
    protected $fillable = [
        'teacher_id',
        'class_id',
        'class_room_id',
        'exam_id',
        'date',
        'time',
        'institution_id',
    ];

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function classRoom()
    {
        return $this->belongsTo(ClassRoom::class, 'class_room_id');
    }

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    public function institution()
    {
        return $this->belongsTo(Institution::class);
    }
}
    // Relationships (optional, uncomment if related models exist)
    // public function teacher()
    // {
    //     return $this->belongsTo(Teacher::class);
    // }

    // public function class()
    // {
    //     return $this->belongsTo(ClassModel::class, 'class_id');
    // }

    // public function exam()
    // {
    //     return $this->belongsTo(Exam::class);
    // }

    // public function institution()
    // {
    //     return $this->belongsTo(Institution::class);
    // }
// }

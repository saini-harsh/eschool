<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssignClassTeacher extends Model
{
    protected $fillable = [
        'institution_id',
        'class_id',
        'section_id',
        'teacher_id',
        'status',
    ];
    protected $table = 'assign_class_teachers';
    public function institution()
    {
        return $this->belongsTo(Institution::class);
    }
    public function class()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }
    public function section()
    {
        return $this->belongsTo(Section::class, 'section_id');
    }
    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id');
    }
}

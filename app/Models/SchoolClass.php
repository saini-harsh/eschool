<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;

class SchoolClass extends Model
{
    use HasFactory;

    protected $table = 'classes';

    protected $fillable = [
        'name',
        'student_count',
        'institution_id',
        'admin_id',
        'status',
    ];

    public function sec()
    {
        return $this->hasMany(Section::class);
    }

    public function sections()
    {
        return $this->hasMany(Section::class, 'class_id');
    }

    public function assignClassTeachers()
    {
        return $this->hasMany(AssignClassTeacher::class, 'class_id');
    }

    public function students()
    {
        return $this->hasMany(Student::class, 'class_id');
    }
}

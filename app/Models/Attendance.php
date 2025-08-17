<?php

namespace App\Models;

use App\Models\Student;
use App\Models\Teacher;
use App\Models\NonWorkingStaff;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Attendance extends Model
{
    use HasFactory;

    //
    protected $fillable = [
        'user_id',
        'role',
        'institution_id',
        'date',
        'status', // e.g., 'present', 'absent', 'late'
        'remarks',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'user_id');
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'user_id');
    }

    public function staff()
    {
        return $this->belongsTo(NonWorkingStaff::class, 'user_id');
    }
}

<?php

namespace App\Models;

use App\Models\Student;
use App\Models\Teacher;
use App\Models\ClassRoom;
use App\Models\Institution;
use App\Models\NonWorkingStaff;
use App\Models\SchoolClass;
use App\Models\Section;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'role', // 'student', 'teacher', 'nonworkingstaff'
        'institution_id',
        'class_id',
        'section_id',
        'teacher_id', // For student attendance - assigned teacher
        'date',
        'status', // 'present', 'absent', 'late'
        'remarks',
        'marked_by', // Who marked the attendance
        'marked_by_role', // Role of who marked the attendance
        'is_confirmed', // Whether attendance is confirmed by higher authority
        'confirmed_by', // Who confirmed the attendance
        'confirmed_at', // When it was confirmed
    ];

    protected $casts = [
        'date' => 'date',
        'is_confirmed' => 'boolean',
        'confirmed_at' => 'datetime',
    ];

    // Relationships
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

    public function assignedTeacher()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id');
    }

    public function markedBy()
    {
        return $this->belongsTo(Teacher::class, 'marked_by');
    }

    public function confirmedBy()
    {
        return $this->belongsTo(Teacher::class, 'confirmed_by');
    }

    // Scopes
    public function scopeByInstitution($query, $institutionId)
    {
        return $query->where('institution_id', $institutionId);
    }

    public function scopeByClass($query, $classId)
    {
        return $query->where('class_id', $classId);
    }

    public function scopeBySection($query, $sectionId)
    {
        return $query->where('section_id', $sectionId);
    }

    public function scopeByRole($query, $role)
    {
        return $query->where('role', $role);
    }

    public function scopeByDate($query, $date)
    {
        return $query->whereDate('date', $date);
    }

    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('date', [$startDate, $endDate]);
    }

    public function scopeConfirmed($query)
    {
        return $query->where('is_confirmed', true);
    }

    public function scopePending($query)
    {
        return $query->where('is_confirmed', false);
    }

    // Helper methods
    public function getUser()
    {
        switch ($this->role) {
            case 'student':
                return $this->student;
            case 'teacher':
                return $this->teacher;
            case 'nonworkingstaff':
                return $this->staff;
            default:
                return null;
        }
    }

    public function getUserName()
    {
        $user = $this->getUser();
        if ($user) {
            return $user->first_name . ' ' . $user->last_name;
        }
        return 'N/A';
    }

    public function getStatusBadgeClass()
    {
        switch ($this->status) {
            case 'present':
                return 'bg-success';
            case 'absent':
                return 'bg-danger';
            case 'late':
                return 'bg-warning';
            default:
                return 'bg-secondary';
        }
    }

    public function getStatusText()
    {
        return ucfirst($this->status);
    }
}

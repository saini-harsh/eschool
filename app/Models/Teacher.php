<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Teacher extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'dob',
        'email',
        'phone',
        'profile_image',
        'address',
        'pincode',
        'institution_code',
        'employee_id',
        'gender',
        'caste_tribe',
        'institution_id',
        'admin_id',
        'password',
        'decrypt_pw',
        'status',
    ];

    protected $hidden = [
        'password',
        'decrypt_pw',
        'remember_token',
    ];

    protected $casts = [
        'dob' => 'date',
        'status' => 'boolean',
    ];

    public function getAuthPassword()
    {
        return $this->password;
    }

    public function institution()
    {
        return $this->belongsTo(Institution::class);
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    public function assignClassTeachers()
    {
        return $this->hasMany(AssignClassTeacher::class);
    }

    public function attendance()
    {
        return $this->hasMany(Attendance::class, 'user_id')->where('role', 'teacher');
    }

    public function attendanceByDate($date)
    {
        return $this->attendance()->whereDate('date', $date)->first();
    }

    public function markedAttendance()
    {
        return $this->hasMany(Attendance::class, 'marked_by');
    }

    public function confirmedAttendance()
    {
        return $this->hasMany(Attendance::class, 'confirmed_by');
    }
}

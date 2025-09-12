<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NonWorkingStaff extends Model
{
    use HasFactory;

    protected $table = 'non_working_staff';

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
        'gender',
        'caste_tribe',
        'date_of_joining',
        'institution_id',
        'admin_id',
        'password',
        'decrypt_pw',
        'status',
        'employee_id',
        'designation',
        'department'
    ];

    protected $casts = [
        'dob' => 'date',
        'date_of_joining' => 'date',
        'status' => 'boolean'
    ];

    // Relationships
    public function institution()
    {
        return $this->belongsTo(Institution::class);
    }

    public function attendance()
    {
        return $this->hasMany(Attendance::class, 'user_id')->where('role', 'nonworkingstaff');
    }

    public function attendanceByDate($date)
    {
        return $this->attendance()->whereDate('date', $date)->first();
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeByInstitution($query, $institutionId)
    {
        return $query->where('institution_id', $institutionId);
    }

    // Methods
    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function getRoleDisplayAttribute()
    {
        return 'Non-working Staff';
    }
}

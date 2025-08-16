<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NonWorkingStaff extends Model
{
    use HasFactory;

    protected $table = 'non_working_staff';

    protected $fillable = [
        'institution_id',
        'name',
        'email',
        'phone',
        'profile_photo',
        'employee_id',
        'designation',
        'department',
        'status'
    ];

    protected $casts = [
        'status' => 'string'
    ];

    // Relationships
    public function institution()
    {
        return $this->belongsTo(Institution::class);
    }

    public function attendance()
    {
        return $this->hasMany(Attendance::class, 'role_id')
            ->where('role_type', 'nonworkingstaff');
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
        return $this->name;
    }

    public function getRoleDisplayAttribute()
    {
        return 'Non-working Staff';
    }
}

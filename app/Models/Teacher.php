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
}

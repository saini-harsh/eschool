<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;


class Student extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'photo',
        'email',
        'phone',
        'dob',
        'address',
        'pincode',
        'caste_tribe',
        'district',
        'institution_code',
        'teacher_id',
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

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }
}

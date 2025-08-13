<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;


class Institution extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name',
        'logo',
        'address',
        'pincode',
        'established_date',
        'board',
        'state',
        'district',
        'unique_id',
        'email',
        'website',
        'phone',
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
        'established_date' => 'date',
        'status' => 'boolean',
    ];

    public function getAuthPassword()
    {
        return $this->password;
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    public function teachers()
    {
        return $this->hasMany(Teacher::class);
    }

    public function students()
    {
        return $this->hasMany(Student::class);
    }
}

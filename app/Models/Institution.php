<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Institution extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'logo',
        'address',
        'pincode',
        'established_date',
        'board',
        'state',
        'district',
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


}

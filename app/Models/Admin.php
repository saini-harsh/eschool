<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name',
        'logo',
        'email',
        'phone',
        'address_line_1',
        'address_line_2',
        'country',
        'state',
        'city',
        'pin_code',
        'role',
        'setservices',
        'password',
        'decrypt_pw',
        'status'
    ];

    protected $hidden = [
        'password',
        'decrypt_pw',
        'remember_token',
    ];

    protected $casts = [
        'setservices' => 'array',
        'status' => 'boolean',
    ];

    public function getAuthPassword()
    {
        return $this->password;
    }
}

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
        'institution_id',
        'admin_id',
        'designation',
        'date_of_joining',
        'password',
        'decrypt_pw',
        'status',
    ];

    protected $hidden = [
        'password',
        'decrypt_pw',
    ];

    // Relationships
    public function institution()
    {
        return $this->belongsTo(Institution::class);
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }
}

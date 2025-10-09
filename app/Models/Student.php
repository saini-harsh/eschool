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
        'gender',
        'address',
        'permanent_address',
        'pincode',
        'caste_tribe',
        'district',
        'institution_code',
        'teacher_id',
        'institution_id',
        'class_id',
        'section_id',
        'admin_id',
        'password',
        'decrypt_pw',
        'status',
        // Academic Information
        'admission_date',
        'admission_number',
        'roll_number',
        'group',
        // Personal Information
        'religion',
        'blood_group',
        'category',
        'height',
        'weight',
        // Parent Information
        'father_name',
        'father_occupation',
        'father_phone',
        'father_photo',
        'mother_name',
        'mother_occupation',
        'mother_phone',
        'mother_photo',
        // Guardian Information
        'guardian_name',
        'guardian_relation',
        'guardian_relation_text',
        'guardian_email',
        'guardian_phone',
        'guardian_occupation',
        'guardian_address',
        'guardian_photo',
        // Document Information
        'national_id',
        'birth_certificate_number',
        'bank_name',
        'bank_account_number',
        'ifsc_code',
        'additional_notes',
        'document_01_title',
        'document_02_title',
        'document_03_title',
        'document_04_title',
        'document_01_file',
        'document_02_file',
        'document_03_file',
        'document_04_file',
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
    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }
    public function section()
    {
        return $this->belongsTo(Section::class, 'section_id');
    }

    public function attendance()
    {
        return $this->hasMany(Attendance::class, 'user_id')->where('role', 'student');
    }

    public function attendanceByDate($date)
    {
        return $this->attendance()->whereDate('date', $date)->first();
    }

    /**
     * Get the student's assignment submissions.
     */
    public function studentAssignments()
    {
        return $this->hasMany(StudentAssignment::class);
    }

    /**
     * Get the student's assignments based on class and section.
     */
    public function assignments()
    {
        return Assignment::where('class_id', $this->class_id)
            ->where('section_id', $this->section_id)
            ->where('status', 1)
            ->orderBy('created_at', 'desc');
    }

    public function studentFees()
    {
        return $this->hasMany(StudentFee::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}

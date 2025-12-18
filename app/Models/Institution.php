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
        'institution_code',
        'code',
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
        'permissions',
        'razorpay_key_id',
        'razorpay_key_secret',
        'razorpay_webhook_secret',
    ];

    protected $hidden = [
        'password',
        'decrypt_pw',
        'remember_token',
        'razorpay_key_secret',
        'razorpay_webhook_secret',
    ];

    protected $casts = [
        'established_date' => 'date',
        'status' => 'boolean',
        'permissions' => 'array',
    ];

    public function getAuthPassword()
    {
        return $this->password;
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    public function classes()
    {
        return $this->hasMany(SchoolClass::class, 'institution_id');
    }

    public function emailSms()
    {
        return $this->hasMany(EmailSms::class, 'institution_id');
    }

    public function exams()
    {
        return $this->hasMany(Exam::class, 'institution_id');
    }

    public function sections()
    {
        return $this->hasMany(Section::class, 'institution_id');
    }

    public function feeStructures()
    {
        return $this->hasMany(FeeStructure::class, 'institution_id');
    }

    public function examTypes(){
        return $this->hasMany(ExamType::class,'institution_id');
    }

    /**
     * Get salary structures for this institution
     */
    public function salaryStructures()
    {
        return $this->hasMany(SalaryStructure::class);
    }

    /**
     * Get salary payments for this institution
     */
    public function salaryPayments()
    {
        return $this->hasMany(SalaryPayment::class);
    }

    /**
     * Get teachers for this institution
     */
    public function teachers()
    {
        return $this->hasMany(Teacher::class);
    }

    /**
     * Get non-working staff for this institution
     */
    public function nonWorkingStaff()
    {
        return $this->hasMany(NonWorkingStaff::class);
    }

    /**
     * Check if Razorpay is configured
     */
    public function hasRazorpayConfig()
    {
        return !empty($this->razorpay_key_id) && !empty($this->razorpay_key_secret);
    }

    /**
     * Check if institution has a specific permission
     */
    public function hasPermission($permission)
    {
        if (!$this->permissions) {
            return true; // If no permissions set, grant all access
        }
        return in_array($permission, $this->permissions);
    }

    /**
     * Get all available permissions
     */
    public static function availablePermissions()
    {
        return [
            'dashboard' => 'Dashboard',
            'administration' => 'Administration',
            'institutions' => 'Institutions',
            'teachers' => 'Teachers',
            'students' => 'Students',
            'nonworkingstaff' => 'Non-Working Staff',
            'attendance' => 'Attendance',
            'academics' => 'Academics',
            'classes' => 'Classes',
            'sections' => 'Sections',
            'subjects' => 'Subjects',
            'assign_teacher' => 'Assign Class Teacher',
            'assign_subject' => 'Assign Subject',
            'assignments' => 'Assignments',
            'calendar' => 'Calendar',
            'events' => 'Event Management',
            'communication' => 'Communication',
            'email_sms' => 'Email / SMS',
            'exam_management' => 'Exam Management',
            'exams' => 'Exams',
            'exam_type' => 'Exam Type',
            'exam_setup' => 'Exam Setup',
            'marksheet' => 'Marksheet',
            'routine' => 'Routine',
            'class_routine' => 'Class Routine',
            'lesson_plan' => 'Lesson Plan',
            'salary_management' => 'Salary Management',
            'salary_structures' => 'Salary Structures',
            'salary_payments' => 'Salary Payments',
            'razorpay_settings' => 'Razorpay Settings',
            'settings' => 'Settings',
        ];
    }

}

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
        'employee_id',
        'gender',
        'caste_tribe',
        'institution_id',
        'admin_id',
        'password',
        'decrypt_pw',
        'status',
        'permissions',
        'salary',
        'salary_structure_id',
        'bank_account_number',
        'bank_ifsc_code',
        'bank_name',
        'bank_branch',
        'razorpay_contact_id',
        'razorpay_fund_account_id',
        'barcode',
        'qr_code',
        'biometric_id',
    ];

    protected $hidden = [
        'password',
        'decrypt_pw',
        'remember_token',
    ];

    protected $casts = [
        'dob' => 'date',
        'status' => 'boolean',
        'permissions' => 'array',
        'salary' => 'decimal:2',
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

    public function attendance()
    {
        return $this->hasMany(Attendance::class, 'user_id')->where('role', 'teacher');
    }

    public function attendanceByDate($date)
    {
        return $this->attendance()->whereDate('date', $date)->first();
    }

    public function markedAttendance()
    {
        return $this->hasMany(Attendance::class, 'marked_by');
    }

    public function confirmedAttendance()
    {
        return $this->hasMany(Attendance::class, 'confirmed_by');
    }

    public function assignedSubjects()
    {
        return $this->hasMany(AssignSubject::class);
    }

    public function assignments()
    {
        return $this->hasMany(Assignment::class);
    }

    /**
     * Get the salary structure for this teacher
     */
    public function salaryStructure()
    {
        return $this->belongsTo(SalaryStructure::class);
    }

    /**
     * Get salary payments for this teacher
     */
    public function salaryPayments()
    {
        return $this->hasMany(SalaryPayment::class, 'payee_id')
            ->where('payee_type', 'teacher');
    }

    /**
     * Get full name attribute
     */
    public function getFullNameAttribute()
    {
        return trim($this->first_name . ' ' . $this->last_name);
    }

    /**
     * Check if bank details are complete
     */
    public function hasBankDetails()
    {
        return !empty($this->bank_account_number) && !empty($this->bank_ifsc_code);
    }

    /**
     * Check if Razorpay is configured for this teacher
     */
    public function hasRazorpayConfig()
    {
        return !empty($this->razorpay_contact_id) && !empty($this->razorpay_fund_account_id);
    }

    /**
     * Check if teacher has a specific permission
     */
    public function hasPermission($permission)
    {
        if (!$this->permissions) {
            return true; // If no permissions set, grant all access
        }
        return in_array($permission, $this->permissions);
    }

    /**
     * Get all available permissions for teachers
     */
    public static function availablePermissions()
    {
        return [
            'dashboard' => 'Dashboard',
            'my_classes' => 'My Classes',
            'students' => 'Students',
            'attendance' => 'Attendance',
            'assignments' => 'Assignments',
            'lesson_plans' => 'Lesson Plans',
            'class_routine' => 'Class Routine',
            'exams' => 'Exams',
            'marksheet' => 'Marksheet',
            'my_salary' => 'My Salary',
            'settings' => 'Settings',
        ];
    }
}

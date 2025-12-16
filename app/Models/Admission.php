<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Admission extends Model
{
    use SoftDeletes;

    protected $fillable = [
        // Academic Information
        'institution_id',
        'institution_code',
        'admission_date',
        'admission_number',
        'roll_number',
        'class_id',
        'pen_no',

        // Previous Academic Information
        'previous_school_name',
        'previous_school_address',
        'previous_school_class',
        'previous_school_result',

        // Student Information
        'email',
        'phone',
        'address',
        'pincode',
        'district',
        'permanent_address',
        'permanent_pincode',
        'permanent_district',

        // Personal Information
        'first_name',
        'last_name',
        'gender',
        'dob',
        'religion',
        'caste_tribe',
        'photo',

        // Medical Record
        'blood_group',
        'height',
        'weight',

        // Parents Information
        'father_name',
        'mother_name',
        'father_occupation',
        'father_phone',
        'parent_aadhaar_front',
        'parent_aadhaar_back',

        // Guardian Information
        'guardian_name',
        'guardian_relation_text',
        'guardian_phone',
        'guardian_address',
        'guardian_aadhaar_front',
        'guardian_aadhaar_back',

        // Student Aadhaar Card Information
        'aadhaar_no',
        'aadhaar_front',
        'aadhaar_back',

        // Other Documents
        'document_01_title',
        'document_01_file',
        'document_02_title',
        'document_02_file',
        'document_03_title',
        'document_03_file',
        'document_04_title',
        'document_04_file',

        // Payment Information
        'admission_fee_amount',
        'admission_payment_method',
        'tuition_fee_amount',
        'tuition_payment_method',
        'hostel_admission_fee_amount',
        'hostel_admission_payment_method',
        'hostel_tuition_fee_amount',
        'hostel_tuition_payment_method',

        // Status and Metadata
        'status',
        'notes',
    ];

    protected $casts = [
        'admission_date' => 'date',
        'dob' => 'date',
        'admission_fee_amount' => 'decimal:2',
        'tuition_fee_amount' => 'decimal:2',
        'hostel_admission_fee_amount' => 'decimal:2',
        'hostel_tuition_fee_amount' => 'decimal:2',
    ];

    // Relationships
    public function institution()
    {
        return $this->belongsTo(Institution::class);
    }

    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function previousSchoolClass()
    {
        return $this->belongsTo(SchoolClass::class, 'previous_school_class');
    }
}

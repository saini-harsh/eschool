<?php

namespace App\Http\Controllers\Institution\Administration;

use Carbon\Carbon;
use App\Models\Section;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Institution;
use App\Models\SchoolClass;
use App\Models\District;
use App\Models\Payment;
use App\Models\TuitionFeePayment;
use App\Models\HostelPayment;
use Illuminate\Support\Str;
use App\Models\FeeStructure;
use App\Helpers\PermissionHelper;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class StudentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:institution');
    }

    /**
     * Generate admission number
     */
    public function generateAdmissionNumber(Request $request)
    {
        $request->validate([
            'institution_id' => 'required|integer',
            'class_id' => 'required|integer'
        ]);

        $admissionNumber = Student::generateAdmissionNumber(
            $request->institution_id,
            $request->class_id
        );

        return response()->json([
            'success' => true,
            'admission_number' => $admissionNumber
        ]);
    }

    /**
     * Generate roll number
     */
    public function generateRollNumber(Request $request)
    {
        $request->validate([
            'institution_id' => 'required|integer',
            'class_id' => 'required|integer',
            'section_id' => 'nullable|integer'
        ]);

        $rollNumber = Student::generateRollNumber(
            $request->institution_id,
            $request->class_id,
            $request->section_id
        );

        return response()->json([
            'success' => true,
            'roll_number' => $rollNumber
        ]);
    }

    public function Index(){
        $institutionId = auth('institution')->id();
        $classes = SchoolClass::where('institution_id', $institutionId)
            ->where('status', 1)
            ->withCount('students')
            ->get();
        return view('institution.administration.students.index',compact('classes'));
    }

    /**
     * Get all students with siblings
     */
    public function getStudentsWithSiblings()
    {
        $institutionId = auth('institution')->id();
        
        $students = Student::where('institution_id', $institutionId)
            ->where('has_sibling', true)
            ->whereNotNull('sibling_ids')
            ->with(['schoolClass', 'section'])
            ->get()
            ->map(function($student) {
                $siblingIds = is_array($student->sibling_ids) ? $student->sibling_ids : json_decode($student->sibling_ids, true);
                $siblings = [];
                if ($siblingIds) {
                    $siblings = Student::whereIn('id', $siblingIds)
                        ->where('institution_id', $student->institution_id)
                        ->with(['schoolClass', 'section'])
                        ->get(['id', 'first_name', 'middle_name', 'last_name', 'student_id', 'photo', 'class_id', 'section_id', 'email'])
                        ->map(function($sibling) {
                            return [
                                'id' => $sibling->id,
                                'name' => trim($sibling->first_name . ' ' . ($sibling->middle_name ?? '') . ' ' . $sibling->last_name),
                                'student_id' => $sibling->student_id,
                                'photo' => $sibling->photo,
                                'class' => $sibling->schoolClass->name ?? 'N/A',
                                'section' => $sibling->section->name ?? 'N/A',
                                'email' => $sibling->email,
                                'url' => route('institution.students.show', $sibling->id)
                            ];
                        });
                }
                
                return [
                    'id' => $student->id,
                    'name' => trim($student->first_name . ' ' . ($student->middle_name ?? '') . ' ' . $student->last_name),
                    'student_id' => $student->student_id,
                    'admission_number' => $student->admission_number,
                    'roll_number' => $student->roll_number,
                    'email' => $student->email,
                    'photo' => $student->photo,
                    'class' => $student->schoolClass->name ?? 'N/A',
                    'section' => $student->section->name ?? 'N/A',
                    'siblings_count' => count($siblings),
                    'siblings' => $siblings,
                    'url' => route('institution.students.show', $student->id)
                ];
            });

        return response()->json(['students' => $students]);
    }

    /**
     * Search students by name or ID
     */
    public function search(Request $request)
    {
        $institutionId = auth('institution')->id();
        $query = $request->input('q', '');
        
        if (strlen($query) < 2) {
            return response()->json(['students' => []]);
        }

        $students = Student::where('institution_id', $institutionId)
            ->where(function($q) use ($query) {
                $q->where('first_name', 'like', '%' . $query . '%')
                  ->orWhere('last_name', 'like', '%' . $query . '%')
                  ->orWhere('middle_name', 'like', '%' . $query . '%')
                  ->orWhereRaw("CONCAT(COALESCE(first_name, ''), ' ', COALESCE(middle_name, ''), ' ', COALESCE(last_name, '')) LIKE ?", ['%' . $query . '%'])
                  ->orWhere('student_id', 'like', '%' . $query . '%')
                  ->orWhere('admission_number', 'like', '%' . $query . '%')
                  ->orWhere('roll_number', 'like', '%' . $query . '%')
                  ->orWhere('email', 'like', '%' . $query . '%');
            })
            ->with(['schoolClass', 'section'])
            ->limit(20)
            ->get()
            ->map(function($student) {
                return [
                    'id' => $student->id,
                    'name' => trim($student->first_name . ' ' . ($student->middle_name ?? '') . ' ' . $student->last_name),
                    'student_id' => $student->student_id,
                    'admission_number' => $student->admission_number,
                    'roll_number' => $student->roll_number,
                    'email' => $student->email,
                    'class' => $student->schoolClass->name ?? 'N/A',
                    'section' => $student->section->name ?? 'N/A',
                    'photo' => $student->photo,
                    'url' => route('institution.students.show', $student->id)
                ];
            });

        return response()->json(['students' => $students]);
    }
    public function Create(){
        $institutionId = auth('institution')->id();
        $institution = Institution::find($institutionId);
        $classes = SchoolClass::where('institution_id', $institutionId)->get(['id','name','institution_id']);
        $sections = collect(); // Start with empty sections

        return view('institution.administration.students.create',compact('institution','classes','sections'));
    }

    // Method to get sections by institution ID and class ID
    public function getSectionsByClass($classId)
    {
        try {
            $institutionId = auth('institution')->id();

            // First verify the class belongs to the institution
            $class = SchoolClass::where('id', $classId)
                ->where('institution_id', $institutionId)
                ->first();

            if (!$class) {
                return response()->json(['sections' => [], 'error' => 'Class not found or does not belong to your institution']);
            }

            // Fetch sections directly from sections table by institution ID and class ID
            $sections = Section::where('institution_id', $institutionId)
                ->where('class_id', $classId)
                ->get(['id', 'name']);

            return response()->json(['sections' => $sections]);
        } catch (\Exception $e) {
            Log::error('Error in getSectionsByClass: ' . $e->getMessage());
            return response()->json(['sections' => [], 'error' => 'An error occurred while fetching sections']);
        }
    }

    // Method to get classes by institution ID
    public function getClassesByInstitution($institutionId)
    {
        $classes = SchoolClass::where('institution_id', $institutionId)
            ->where('status', 1)
            ->get(['id', 'name', 'institution_id', 'section_ids']);

        return response()->json(['classes' => $classes]);
    }

    // Method to get teachers by institution ID
    public function getTeachersByInstitution($institutionId)
    {
        $teachers = Teacher::where('institution_id', $institutionId)
            ->where('status', 1)
            ->get(['id', 'first_name', 'last_name', 'institution_id']);

        return response()->json(['teachers' => $teachers]);
    }
    public function Store(Request $request)
    {
        $institutionId = auth('institution')->id();
        $request->validate([
            'first_name'     => 'required|string|max:255',
            'middle_name'    => 'nullable|string|max:255',
            'last_name'      => 'required|string|max:255',
            'email'          => 'required|email|unique:students,email',
            'phone'          => 'required|string|max:20',
            'dob'            => 'required|string',
            'address'        => 'required|string|max:255',
            'permanent_address' => 'nullable|string|max:255',
            'pincode'        => 'required|string|max:10',
            'gender'         => 'required|in:Male,Female,Other',
            'caste_tribe'    => 'nullable|string|max:255',
            'district'       => 'required|string|max:255',
            'teacher_id'     => 'nullable|exists:teachers,id',
            'class_id'       => 'nullable|exists:classes,id',
            'section_id'     => 'nullable|exists:sections,id',
            'password'       => 'required|string|min:6',
            'photo'          => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
            // New fields validation
            'admission_date' => 'required|string',
            'admission_number' => 'nullable|string|max:50',
            'roll_number' => 'nullable|string|max:50',
            'group' => 'nullable|string|max:50',
            'religion' => 'nullable|string|max:50',
            'blood_group' => 'nullable|string|max:10',
            'category' => 'nullable|string|max:50',
            'height' => 'nullable|string|max:10',
            'weight' => 'nullable|string|max:10',
            'father_name' => 'nullable|string|max:255',
            'father_occupation' => 'nullable|string|max:255',
            'father_phone' => 'nullable|string|max:20',
            'father_photo' => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
            'mother_name' => 'nullable|string|max:255',
            'mother_occupation' => 'nullable|string|max:255',
            'mother_phone' => 'nullable|string|max:20',
            'mother_photo' => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
            'guardian_name' => 'nullable|string|max:255',
            'guardian_relation' => 'nullable|string|max:50',
            'guardian_relation_text' => 'nullable|string|max:50',
            'guardian_email' => 'nullable|email|max:255',
            'guardian_phone' => 'nullable|string|max:20',
            'guardian_occupation' => 'nullable|string|max:255',
            'guardian_address' => 'nullable|string|max:500',
            'guardian_photo' => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
            'aadhaar_no' => 'nullable|string|max:12',
            'aadhaar_front' => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
            'aadhaar_back' => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
            'pan_no' => 'nullable|string|max:10',
            'pan_front' => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
            'pan_back' => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
            'pen_no' => 'nullable|string|max:50',
            'birth_certificate_number' => 'nullable|string|max:50',
            'bank_name' => 'nullable|string|max:255',
            'bank_account_number' => 'nullable|string|max:50',
            'ifsc_code' => 'nullable|string|max:20',
            'additional_notes' => 'nullable|string|max:1000',
            'document_01_title' => 'nullable|string|max:255',
            'document_02_title' => 'nullable|string|max:255',
            'document_03_title' => 'nullable|string|max:255',
            'document_04_title' => 'nullable|string|max:255',
            'document_01_file' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
            'document_02_file' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
            'document_03_file' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
            'document_04_file' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
        ]);

        // Additional validation: ensure section belongs to selected class
        if ($request->filled('class_id') && $request->filled('section_id')) {
            $class = SchoolClass::find($request->class_id);
            if ($class) {
                $sectionIds = $class->section_ids ?? [];

                // If section_ids is a string (JSON), decode it
                if (is_string($sectionIds)) {
                    $sectionIds = json_decode($sectionIds, true) ?? [];
                }

                if (!is_array($sectionIds) || !in_array($request->section_id, $sectionIds)) {
                    return back()->withErrors(['section_id' => 'The selected section does not belong to the selected class.'])->withInput();
                }
            }
        }

        // Handle file uploads
        $photoPath = null;
        $fatherPhotoPath = null;
        $motherPhotoPath = null;
        $guardianPhotoPath = null;
        $aadhaarFrontPath = null;
        $aadhaarBackPath = null;
        $panFrontPath = null;
        $panBackPath = null;
        $document01Path = null;
        $document02Path = null;
        $document03Path = null;
        $document04Path = null;

        if ($request->hasFile('photo')) {
            $photoPath = $this->uploadFile($request->file('photo'), 'students');
        }
        if ($request->hasFile('father_photo')) {
            $fatherPhotoPath = $this->uploadFile($request->file('father_photo'), 'students/parents');
        }
        if ($request->hasFile('mother_photo')) {
            $motherPhotoPath = $this->uploadFile($request->file('mother_photo'), 'students/parents');
        }
        if ($request->hasFile('guardian_photo')) {
            $guardianPhotoPath = $this->uploadFile($request->file('guardian_photo'), 'students/guardians');
        }
        if ($request->hasFile('aadhaar_front')) {
            $aadhaarFrontPath = $this->uploadFile($request->file('aadhaar_front'), 'students/documents');
        }
        if ($request->hasFile('aadhaar_back')) {
            $aadhaarBackPath = $this->uploadFile($request->file('aadhaar_back'), 'students/documents');
        }
        if ($request->hasFile('pan_front')) {
            $panFrontPath = $this->uploadFile($request->file('pan_front'), 'students/documents');
        }
        if ($request->hasFile('pan_back')) {
            $panBackPath = $this->uploadFile($request->file('pan_back'), 'students/documents');
        }
        if ($request->hasFile('document_01_file')) {
            $document01Path = $this->uploadFile($request->file('document_01_file'), 'students/documents');
        }
        if ($request->hasFile('document_02_file')) {
            $document02Path = $this->uploadFile($request->file('document_02_file'), 'students/documents');
        }
        if ($request->hasFile('document_03_file')) {
            $document03Path = $this->uploadFile($request->file('document_03_file'), 'students/documents');
        }
        if ($request->hasFile('document_04_file')) {
            $document04Path = $this->uploadFile($request->file('document_04_file'), 'students/documents');
        }

        $student = new Student();
        $student->first_name     = $request->first_name;
        $student->middle_name    = $request->middle_name;
        $student->last_name      = $request->last_name;
        $student->photo          = $photoPath;
        $student->email          = $request->email;
        $student->phone          = $request->phone;
        $student->dob            = Carbon::parse($request->dob)->format('Y-m-d');
        $student->address        = $request->address;
        $student->permanent_address = $request->permanent_address;
        $student->pincode        = $request->pincode;
        $student->gender         = $request->gender;
        $student->caste_tribe    = $request->caste_tribe;
        $student->district       = $request->district;
        $student->institution_code = 'INS' . str_pad($institutionId, 3, '0', STR_PAD_LEFT);
        $student->teacher_id     = $request->teacher_id;
        $student->institution_id = $institutionId;
        $student->class_id       = $request->class_id;
        $student->section_id     = $request->section_id;
        $student->status         = 'admitted';
        $student->admin_id       = auth('institution')->id();
        $student->password       = Hash::make($request->password);
        $student->decrypt_pw     = $request->password;

        // New fields
        $student->admission_date = $request->admission_date ? Carbon::parse($request->admission_date)->format('Y-m-d') : null;
        $student->admission_number = $request->admission_number;
        $student->roll_number = $request->roll_number;
        $student->group = $request->group;
        $student->religion = $request->religion;
        $student->blood_group = $request->blood_group;
        $student->category = $request->category;
        $student->height = $request->height;
        $student->weight = $request->weight;
        $student->father_name = $request->father_name;
        $student->father_occupation = $request->father_occupation;
        $student->father_phone = $request->father_phone;
        $student->father_photo = $fatherPhotoPath;
        $student->mother_name = $request->mother_name;
        $student->mother_occupation = $request->mother_occupation;
        $student->mother_phone = $request->mother_phone;
        $student->mother_photo = $motherPhotoPath;
        $student->guardian_name = $request->guardian_name;
        $student->guardian_relation = $request->guardian_relation;
        $student->guardian_relation_text = $request->guardian_relation_text;
        $student->guardian_email = $request->guardian_email;
        $student->guardian_phone = $request->guardian_phone;
        $student->guardian_occupation = $request->guardian_occupation;
        $student->guardian_address = $request->guardian_address;
        $student->guardian_photo = $guardianPhotoPath;
        $student->aadhaar_no = $request->aadhaar_no;
        $student->aadhaar_front = $aadhaarFrontPath;
        $student->aadhaar_back = $aadhaarBackPath;
        $student->pan_no = $request->pan_no;
        $student->pan_front = $panFrontPath;
        $student->pan_back = $panBackPath;
        $student->pen_no = $request->pen_no;
        $student->birth_certificate_number = $request->birth_certificate_number;
        $student->bank_name = $request->bank_name;
        $student->bank_account_number = $request->bank_account_number;
        $student->ifsc_code = $request->ifsc_code;
        $student->additional_notes = $request->additional_notes;
        $student->document_01_title = $request->document_01_title;
        $student->document_02_title = $request->document_02_title;
        $student->document_03_title = $request->document_03_title;
        $student->document_04_title = $request->document_04_title;
        $student->document_01_file = $document01Path;
        $student->document_02_file = $document02Path;
        $student->document_03_file = $document03Path;
        $student->document_04_file = $document04Path;


        $student->save();

        if ($request->has('send_invite')) {
            // Send email or notification logic here
        }

        return redirect()->route('institution.students.index')->with('success', 'Student added successfully!');

    }
    public function Edit(Student $student)
    {
        $institutionId = auth('institution')->id();
        // Ensure the student belongs to the logged-in institution
        if ($student->institution_id !== $institutionId) {
            abort(403, 'Unauthorized access to student data.');
        }

        $institution = Institution::find($institutionId);
        $classes = SchoolClass::where('institution_id', $institutionId)
            ->get(['id','name','institution_id','section_ids']);
        $sections = collect();
        if ($student->class_id) {
            $class = SchoolClass::find($student->class_id);
            if ($class) {
                $sectionIds = $class->section_ids ?? [];

                // If section_ids is a string (JSON), decode it
                if (is_string($sectionIds)) {
                    $sectionIds = json_decode($sectionIds, true) ?? [];
                }

                if (is_array($sectionIds) && !empty($sectionIds)) {
                    $sections = Section::whereIn('id', $sectionIds)->get(['id','name']);
                }
            }
        }

        $teachers = Teacher::where('institution_id', $institutionId)->get(['id','first_name','last_name']);

        return view('institution.administration.students.edit', compact('student', 'institution','classes','sections','teachers'));
    }

    public function Show(Student $student)
    {
        $institutionId = auth('institution')->id();
        // Ensure the student belongs to the logged-in institution
        if ($student->institution_id !== $institutionId) {
            abort(403, 'Unauthorized access to student data.');
        }

        // Fetch all payment records for this student
        $payments = Payment::where('student_id', $student->id)
            ->where('institution_id', $institutionId)
            ->with(['feeStructure'])
            ->orderBy('payment_date', 'desc')
            ->get();

        $tuitionPayments = TuitionFeePayment::where('student_id', $student->id)
            ->where('institution_id', $institutionId)
            ->with(['feeStructure'])
            ->orderBy('payment_date', 'desc')
            ->get();

        $hostelPayments = HostelPayment::where('student_id', $student->id)
            ->where('institution_id', $institutionId)
            ->with(['feeStructure'])
            ->orderBy('payment_date', 'desc')
            ->get();

        // Group payments by month
        $paymentsByMonth = [];
        
        // Process regular payments
        foreach ($payments as $payment) {
            $monthKey = Carbon::parse($payment->payment_date)->format('Y-m');
            $monthName = Carbon::parse($payment->payment_date)->format('F Y');
            
            if (!isset($paymentsByMonth[$monthKey])) {
                $paymentsByMonth[$monthKey] = [
                    'month' => $monthName,
                    'month_key' => $monthKey,
                    'payments' => [],
                    'total_amount' => 0
                ];
            }
            
            $paymentsByMonth[$monthKey]['payments'][] = [
                'type' => 'Fee Payment',
                'date' => $payment->payment_date,
                'amount' => $payment->amount,
                'discount' => $payment->discount_amount,
                'receipt' => $payment->receipt_number,
                'method' => $payment->payment_method,
                'status' => $payment->status,
                'fee_structure' => $payment->feeStructure->name ?? 'N/A'
            ];
            
            $paymentsByMonth[$monthKey]['total_amount'] += $payment->amount;
        }

        // Process tuition fee payments (can span multiple months)
        foreach ($tuitionPayments as $tuitionPayment) {
            $paymentDate = Carbon::parse($tuitionPayment->payment_date);
            $selectedMonths = $tuitionPayment->selected_months ?? [];
            
            if (empty($selectedMonths)) {
                // If no months specified, use payment date month
                $monthKey = $paymentDate->format('Y-m');
                $monthName = $paymentDate->format('F Y');
                
                if (!isset($paymentsByMonth[$monthKey])) {
                    $paymentsByMonth[$monthKey] = [
                        'month' => $monthName,
                        'month_key' => $monthKey,
                        'payments' => [],
                        'total_amount' => 0
                    ];
                }
                
                $paymentsByMonth[$monthKey]['payments'][] = [
                    'type' => 'Tuition Fee',
                    'date' => $tuitionPayment->payment_date,
                    'amount' => $tuitionPayment->payment_amount,
                    'discount' => $tuitionPayment->discount_amount,
                    'receipt' => $tuitionPayment->receipt_number,
                    'method' => $tuitionPayment->payment_method,
                    'status' => $tuitionPayment->status,
                    'months' => $tuitionPayment->number_of_months,
                    'fee_structure' => $tuitionPayment->feeStructure->name ?? 'N/A'
                ];
                
                $paymentsByMonth[$monthKey]['total_amount'] += $tuitionPayment->payment_amount;
            } else {
                // Add payment to each selected month
                foreach ($selectedMonths as $monthNum) {
                    $year = $paymentDate->year;
                    $monthKey = Carbon::create($year, $monthNum, 1)->format('Y-m');
                    $monthName = Carbon::create($year, $monthNum, 1)->format('F Y');
                    
                    if (!isset($paymentsByMonth[$monthKey])) {
                        $paymentsByMonth[$monthKey] = [
                            'month' => $monthName,
                            'month_key' => $monthKey,
                            'payments' => [],
                            'total_amount' => 0
                        ];
                    }
                    
                    $monthlyAmount = $tuitionPayment->monthly_fee_amount ?? ($tuitionPayment->payment_amount / count($selectedMonths));
                    
                    $paymentsByMonth[$monthKey]['payments'][] = [
                        'type' => 'Tuition Fee',
                        'date' => $tuitionPayment->payment_date,
                        'amount' => $monthlyAmount,
                        'discount' => 0,
                        'receipt' => $tuitionPayment->receipt_number,
                        'method' => $tuitionPayment->payment_method,
                        'status' => $tuitionPayment->status,
                        'months' => 'Month ' . $monthNum,
                        'fee_structure' => $tuitionPayment->feeStructure->name ?? 'N/A'
                    ];
                    
                    $paymentsByMonth[$monthKey]['total_amount'] += $monthlyAmount;
                }
            }
        }

        // Process hostel payments
        foreach ($hostelPayments as $hostelPayment) {
            $monthKey = Carbon::parse($hostelPayment->payment_date)->format('Y-m');
            $monthName = Carbon::parse($hostelPayment->payment_date)->format('F Y');
            
            if (!isset($paymentsByMonth[$monthKey])) {
                $paymentsByMonth[$monthKey] = [
                    'month' => $monthName,
                    'month_key' => $monthKey,
                    'payments' => [],
                    'total_amount' => 0
                ];
            }
            
            $paymentsByMonth[$monthKey]['payments'][] = [
                'type' => 'Hostel Payment',
                'date' => $hostelPayment->payment_date,
                'amount' => $hostelPayment->amount,
                'discount' => $hostelPayment->discount_amount,
                'receipt' => $hostelPayment->receipt_number,
                'method' => 'cash', // HostelPayment doesn't have payment_method
                'status' => 'completed',
                'months' => $hostelPayment->months_paid ?? 1,
                'fee_structure' => $hostelPayment->feeStructure->name ?? 'N/A'
            ];
            
            $paymentsByMonth[$monthKey]['total_amount'] += $hostelPayment->amount;
        }

        // Sort by month (most recent first)
        krsort($paymentsByMonth);

        return view('institution.administration.students.show', compact('student', 'paymentsByMonth'));
    }

    public function Update(Request $request, Student $student)
    {
        $institutionId = auth('institution')->id();
        // Ensure the student belongs to the logged-in institution
        if ($student->institution_id !== $institutionId) {
            abort(403, 'Unauthorized access to student data.');
        }

        $request->validate([
            'first_name'     => 'required|string|max:255',
            'middle_name'    => 'nullable|string|max:255',
            'last_name'      => 'required|string|max:255',
            'email'          => 'required|email|unique:students,email,' . $student->id,
            'phone'          => 'required|string|max:20',
            'dob'            => 'required|string',
            'address'        => 'required|string|max:255',
            'permanent_address' => 'nullable|string|max:255',
            'pincode'        => 'required|string|max:10',
            'gender'         => 'required|in:Male,Female,Other',
            'caste_tribe'    => 'nullable|string|max:255',
            'district'       => 'required|string|max:255',
            'teacher_id'     => 'nullable|exists:teachers,id',
            'class_id'       => 'nullable|exists:classes,id',
            'section_id'     => 'nullable|exists:sections,id',
            'password'       => 'nullable|string|min:6',
            'photo'          => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
            'admission_date' => 'required|string',
            'admission_number' => 'nullable|string|max:50',
            'roll_number'    => 'nullable|string|max:50',
            'group'          => 'nullable|string|max:50',
            'religion'       => 'nullable|string|max:50',
            'blood_group'    => 'nullable|string|max:10',
            'category'       => 'nullable|string|max:50',
            'height'         => 'nullable|string|max:10',
            'weight'         => 'nullable|string|max:10',
            'father_name'    => 'nullable|string|max:255',
            'father_occupation' => 'nullable|string|max:255',
            'father_phone'   => 'nullable|string|max:20',
            'father_photo'   => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
            'mother_name'    => 'nullable|string|max:255',
            'mother_occupation' => 'nullable|string|max:255',
            'mother_phone'   => 'nullable|string|max:20',
            'mother_photo'   => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
            'guardian_name'  => 'nullable|string|max:255',
            'guardian_relation' => 'nullable|string|max:50',
            'guardian_relation_text' => 'nullable|string|max:50',
            'guardian_email' => 'nullable|email|max:255',
            'guardian_phone' => 'nullable|string|max:20',
            'guardian_occupation' => 'nullable|string|max:255',
            'guardian_address' => 'nullable|string|max:500',
            'guardian_photo' => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
            'aadhaar_no' => 'nullable|string|max:12',
            'aadhaar_front' => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
            'aadhaar_back' => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
            'pan_no' => 'nullable|string|max:10',
            'pan_front' => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
            'pan_back' => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
            'pen_no' => 'nullable|string|max:50',
            'birth_certificate_number' => 'nullable|string|max:50',
            'bank_name'      => 'nullable|string|max:255',
            'bank_account_number' => 'nullable|string|max:50',
            'ifsc_code'      => 'nullable|string|max:20',
            'additional_notes' => 'nullable|string|max:1000',
            'document_01_title' => 'nullable|string|max:255',
            'document_02_title' => 'nullable|string|max:255',
            'document_03_title' => 'nullable|string|max:255',
            'document_04_title' => 'nullable|string|max:255',
            'document_01_file' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
            'document_02_file' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
            'document_03_file' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
            'document_04_file' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
        ]);

        // Additional validation: ensure section belongs to selected class
        if ($request->filled('class_id') && $request->filled('section_id')) {
            $class = SchoolClass::find($request->class_id);
            if ($class) {
                $sectionIds = $class->section_ids ?? [];

                // If section_ids is a string (JSON), decode it
                if (is_string($sectionIds)) {
                    $sectionIds = json_decode($sectionIds, true) ?? [];
                }

                if (!is_array($sectionIds) || !in_array($request->section_id, $sectionIds)) {
                    return back()->withErrors(['section_id' => 'The selected section does not belong to the selected class.'])->withInput();
                }
            }
        }

        // Handle file uploads
        $photoPath = $student->photo; // Keep existing photo if not updated
        $fatherPhotoPath = $student->father_photo;
        $motherPhotoPath = $student->mother_photo;
        $guardianPhotoPath = $student->guardian_photo;
        $aadhaarFrontPath = $student->aadhaar_front;
        $aadhaarBackPath = $student->aadhaar_back;
        $panFrontPath = $student->pan_front;
        $panBackPath = $student->pan_back;
        $document01Path = $student->document_01_file;
        $document02Path = $student->document_02_file;
        $document03Path = $student->document_03_file;
        $document04Path = $student->document_04_file;

        if ($request->hasFile('photo')) {
            $photoPath = $this->uploadFile($request->file('photo'), 'students');
        }
        if ($request->hasFile('father_photo')) {
            $fatherPhotoPath = $this->uploadFile($request->file('father_photo'), 'students/parents');
        }
        if ($request->hasFile('mother_photo')) {
            $motherPhotoPath = $this->uploadFile($request->file('mother_photo'), 'students/parents');
        }
        if ($request->hasFile('guardian_photo')) {
            $guardianPhotoPath = $this->uploadFile($request->file('guardian_photo'), 'students/guardians');
        }
        if ($request->hasFile('aadhaar_front')) {
            $aadhaarFrontPath = $this->uploadFile($request->file('aadhaar_front'), 'students/documents');
        }
        if ($request->hasFile('aadhaar_back')) {
            $aadhaarBackPath = $this->uploadFile($request->file('aadhaar_back'), 'students/documents');
        }
        if ($request->hasFile('pan_front')) {
            $panFrontPath = $this->uploadFile($request->file('pan_front'), 'students/documents');
        }
        if ($request->hasFile('pan_back')) {
            $panBackPath = $this->uploadFile($request->file('pan_back'), 'students/documents');
        }
        if ($request->hasFile('document_01_file')) {
            $document01Path = $this->uploadFile($request->file('document_01_file'), 'students/documents');
        }
        if ($request->hasFile('document_02_file')) {
            $document02Path = $this->uploadFile($request->file('document_02_file'), 'students/documents');
        }
        if ($request->hasFile('document_03_file')) {
            $document03Path = $this->uploadFile($request->file('document_03_file'), 'students/documents');
        }
        if ($request->hasFile('document_04_file')) {
            $document04Path = $this->uploadFile($request->file('document_04_file'), 'students/documents');
        }

        $student->first_name     = $request->first_name;
        $student->middle_name    = $request->middle_name;
        $student->last_name      = $request->last_name;
        $student->photo          = $photoPath;
        $student->email          = $request->email;
        $student->phone          = $request->phone;
        $student->dob            = Carbon::parse($request->dob)->format('Y-m-d');
        $student->address        = $request->address;
        $student->permanent_address = $request->permanent_address;
        $student->pincode        = $request->pincode;
        $student->gender         = $request->gender;
        $student->caste_tribe    = $request->caste_tribe;
        $student->district       = $request->district;
        $student->teacher_id     = $request->teacher_id;
        $student->institution_id = $institutionId;
        $student->institution_code = 'INS' . str_pad($institutionId, 3, '0', STR_PAD_LEFT);
        $student->class_id       = $request->class_id;
        $student->section_id     = $request->section_id;
        $student->admin_id       = auth('institution')->id();

        // New fields
        $student->admission_date = $request->admission_date ? Carbon::parse($request->admission_date)->format('Y-m-d') : null;
        $student->admission_number = $request->admission_number;
        $student->roll_number = $request->roll_number;
        $student->group = $request->group;
        $student->religion = $request->religion;
        $student->blood_group = $request->blood_group;
        $student->category = $request->category;
        $student->height = $request->height;
        $student->weight = $request->weight;
        $student->father_name = $request->father_name;
        $student->father_occupation = $request->father_occupation;
        $student->father_phone = $request->father_phone;
        $student->father_photo = $fatherPhotoPath;
        $student->mother_name = $request->mother_name;
        $student->mother_occupation = $request->mother_occupation;
        $student->mother_phone = $request->mother_phone;
        $student->mother_photo = $motherPhotoPath;
        $student->guardian_name = $request->guardian_name;
        $student->guardian_relation = $request->guardian_relation;
        $student->guardian_relation_text = $request->guardian_relation_text;
        $student->guardian_email = $request->guardian_email;
        $student->guardian_phone = $request->guardian_phone;
        $student->guardian_occupation = $request->guardian_occupation;
        $student->guardian_address = $request->guardian_address;
        $student->guardian_photo = $guardianPhotoPath;
        $student->aadhaar_no = $request->aadhaar_no;
        $student->aadhaar_front = $aadhaarFrontPath;
        $student->aadhaar_back = $aadhaarBackPath;
        $student->pan_no = $request->pan_no;
        $student->pan_front = $panFrontPath;
        $student->pan_back = $panBackPath;
        $student->pen_no = $request->pen_no;
        $student->birth_certificate_number = $request->birth_certificate_number;
        $student->bank_name = $request->bank_name;
        $student->bank_account_number = $request->bank_account_number;
        $student->ifsc_code = $request->ifsc_code;
        $student->additional_notes = $request->additional_notes;
        $student->document_01_title = $request->document_01_title;
        $student->document_02_title = $request->document_02_title;
        $student->document_03_title = $request->document_03_title;
        $student->document_04_title = $request->document_04_title;
        $student->document_01_file = $document01Path;
        $student->document_02_file = $document02Path;
        $student->document_03_file = $document03Path;
        $student->document_04_file = $document04Path;

        // Update password only if provided
        if ($request->filled('password')) {
            $student->password   = Hash::make($request->password);
            $student->decrypt_pw = $request->password;
        }

        $student->save();

        return redirect()->route('institution.students.index')->with('success', 'Student updated successfully!');
    }
    public function Delete($id)
    {
        $institutionId = auth('institution')->id();
        $student = Student::where('id', $id)
                        ->where('institution_id', $institutionId)
                        ->firstOrFail();
        $student->delete();

        return redirect()->route('institution.students.index')->with('success', 'Student deleted successfully!');
    }

    /**
     * Update student status
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:0,1'
        ]);

        $institutionId = auth('institution')->id();
        $student = Student::where('id', $id)
                        ->where('institution_id', $institutionId)
                        ->firstOrFail();
        $student->status = $request->status;
        $student->save();

        return response()->json(['success' => true, 'message' => 'Status updated successfully']);
    }

    /**
     * Get students by class ID
     */
    public function getStudentsByClass($classId)
    {
        try {
            $institutionId = auth('institution')->id();

            // Verify the class belongs to the institution
            $class = SchoolClass::where('id', $classId)
                ->where('institution_id', $institutionId)
                ->first();

            if (!$class) {
                return response()->json(['students' => [], 'error' => 'Class not found']);
            }

            $students = Student::where('institution_id', $institutionId)
                ->where('class_id', $classId)
                ->with(['teacher', 'section'])
                ->get();

            // Get sections for this class to populate filter dropdown
            $sections = Section::where('institution_id', $institutionId)
                ->where('class_id', $classId)
                ->get(['id', 'name']);

            return response()->json([
                'students' => $students,
                'class' => $class,
                'sections' => $sections
            ]);
        } catch (\Exception $e) {
            Log::error('Error in getStudentsByClass: ' . $e->getMessage());
            return response()->json(['students' => [], 'error' => 'An error occurred while fetching students']);
        }
    }

    /**
     * Get students by class ID and section ID
     */
    public function getStudentsByClassAndSection($classId, $sectionId = null)
    {
        try {
            $institutionId = auth('institution')->id();

            // Verify the class belongs to the institution
            $class = SchoolClass::where('id', $classId)
                ->where('institution_id', $institutionId)
                ->first();

            if (!$class) {
                return response()->json(['students' => [], 'error' => 'Class not found']);
            }

            $query = Student::where('institution_id', $institutionId)
                ->where('class_id', $classId)
                ->with(['teacher', 'section']);

            // Add section filter if provided
            if ($sectionId) {
                $query->where('section_id', $sectionId);
            }

            $students = $query->get();

            // Get sections for this class to populate filter dropdown
            $sections = Section::where('institution_id', $institutionId)
                ->where('class_id', $classId)
                ->get(['id', 'name']);

            return response()->json([
                'students' => $students,
                'class' => $class,
                'sections' => $sections
            ]);
        } catch (\Exception $e) {
            Log::error('Error in getStudentsByClassAndSection: ' . $e->getMessage());
            return response()->json(['students' => [], 'error' => 'An error occurred while fetching students']);
        }
    }

    public function downloadPdf(Student $student)
    {
        $institutionId = auth('institution')->id();
        if ($student->institution_id !== $institutionId) {
            abort(403);
        }

        $student->load([
            'institution:id,name',
            'teacher:id,first_name,last_name',
            'schoolClass:id,name',
            'section:id,name'
        ]);

        $primaryColor = '#6366f1';
        $secondaryColor = '#0d6efd';

        $pdf = Pdf::loadView('admin.administration.students.pdf', [
            'student' => $student,
            'primaryColor' => $primaryColor,
            'secondaryColor' => $secondaryColor,
        ])->setPaper('a4');

        $fileName = 'Student_' . str_replace(['/', '\\'], '-', ($student->student_id ?? $student->id)) . '.pdf';
        return $pdf->download($fileName);
    }

    public function printIdCard(Student $student)
    {
        $institutionId = auth('institution')->id();
        if ($student->institution_id !== $institutionId) {
            abort(403);
        }

        $student->load([
            'institution:id,name,logo,address,email,phone,website,board,district,state,pincode',
            'schoolClass:id,name',
            'section:id,name'
        ]);

        $primaryColor = '#6366f1';
        $secondaryColor = '#0d6efd';

        $pdf = Pdf::loadView('admin.administration.students.id-card', [
            'student' => $student,
            'primaryColor' => $primaryColor,
            'secondaryColor' => $secondaryColor,
        ])->setPaper('a4');

        $fileName = 'Student_ID_Card_' . str_replace(['/', '\\'], '-', ($student->student_id ?? $student->id)) . '.pdf';
        return $pdf->stream($fileName);
    }

    /**
     * Import students from CSV file
     */
    public function import(Request $request)
    {
        try {
            $institutionId = auth('institution')->id();

            // Validate the request
            $validator = Validator::make($request->all(), [
                'class_id' => 'required|exists:classes,id',
                'section_id' => 'nullable|exists:sections,id',
                'csv_file' => 'required|file|mimes:csv,txt,xlsx,xls|max:10240', // 10MB max, allow Excel files
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Verify class belongs to institution
            $class = SchoolClass::where('id', $request->class_id)
                ->where('institution_id', $institutionId)
                ->first();

            if (!$class) {
                return response()->json([
                    'success' => false,
                    'message' => 'Class not found or does not belong to your institution.'
                ], 404);
            }

            // Verify section belongs to class (only if section_id is provided)
            $sectionId = null;
            if ($request->filled('section_id')) {
                $section = Section::where('id', $request->section_id)
                    ->where('class_id', $class->id)
                    ->where('institution_id', $institutionId)
                    ->first();
                if (!$section) {
                    return response()->json([
                        'success' => false,
                        'message' => 'The selected section does not belong to the selected class.'
                    ], 422);
                }
                $sectionId = $request->section_id;
            }



            // Process CSV file
            $file = $request->file('csv_file');
            $csvData = $this->parseCsvFile($file);

            if (empty($csvData)) {
                return response()->json([
                    'success' => false,
                    'message' => 'CSV file is empty or invalid.'
                ], 422);
            }

            // Import students
            $importResult = $this->importStudentsFromCsv($csvData, $institutionId, $request->class_id, $sectionId);

            return response()->json([
                'success' => true,
                'message' => "Import completed. {$importResult['successful']} students imported successfully, {$importResult['failed']} failed.",
                'details' => $importResult
            ]);

        } catch (\Exception $e) {
            Log::error('Student import error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred during import. Please try again.'
            ], 500);
        }
    }

    /**
     * Parse CSV/Excel file and return array of data
     */
    private function parseCsvFile($file)
    {
        $extension = $file->getClientOriginalExtension();
        
        // Handle Excel files
        if (in_array(strtolower($extension), ['xlsx', 'xls'])) {
            return $this->parseExcelFile($file);
        }
        
        // Handle CSV files
        $csvData = [];
        $handle = fopen($file->getPathname(), 'r');

        if ($handle === false) {
            return [];
        }

        // Get header row
        $headers = fgetcsv($handle);
        if ($headers === false) {
            fclose($handle);
            return [];
        }

        // Clean headers (remove BOM and trim)
        $headers = array_map(function($header) {
            return trim(str_replace("\xEF\xBB\xBF", '', $header));
        }, $headers);

        // Read data rows
        while (($row = fgetcsv($handle)) !== false) {
            if (count($row) === count($headers)) {
                $csvData[] = array_combine($headers, $row);
            }
        }

        fclose($handle);
        return $csvData;
    }

    /**
     * Parse Excel file and return array of data
     */
    private function parseExcelFile($file)
    {
        try {
            // Use PhpSpreadsheet if available, otherwise fallback to CSV conversion
            if (class_exists(\PhpOffice\PhpSpreadsheet\IOFactory::class)) {
                $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file->getPathname());
                $worksheet = $spreadsheet->getActiveSheet();
                $data = $worksheet->toArray();
                
                if (empty($data)) {
                    return [];
                }
                
                // First row is headers
                $headers = array_map(function($header) {
                    return trim(str_replace("\xEF\xBB\xBF", '', $header ?? ''));
                }, array_shift($data));
                
                // Convert rows to associative arrays
                $result = [];
                foreach ($data as $row) {
                    if (count($row) === count($headers)) {
                        $result[] = array_combine($headers, $row);
                    }
                }
                
                return $result;
            } else {
                // Fallback: try to read as CSV
                return $this->parseCsvFile($file);
            }
        } catch (\Exception $e) {
            Log::error('Excel parsing error: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Import students from CSV/Excel data
     */
    private function importStudentsFromCsv($csvData, $institutionId, $classId, $sectionId = null)
    {
        $successful = 0;
        $failed = 0;
        $errors = [];

        // Map Excel column names to database fields (exact headings from Excel)
        $columnMapping = [
            'Old / New' => 'old_new',
            'Roll No.' => 'roll_number',
            'Roll No' => 'roll_number', // Without period
            'Name' => 'name',
            'Gender' => 'gender',
            'DOB' => 'dob',
            'DOB Status' => 'dob_status',
            'PEN No.' => 'pen_no',
            'PEN No' => 'pen_no', // Without period
            'Aadhaar No.' => 'aadhaar_no',
            'Aadhaar No' => 'aadhaar_no', // Without period
            'Mother\'s Name' => 'mother_name',
            'Mothers Name' => 'mother_name', // Without apostrophe
            'Father\'s Name' => 'father_name',
            'Fathers Name' => 'father_name', // Without apostrophe
            'WhatsApp No.' => 'whatsapp_no',
            'WhatsApp No' => 'whatsapp_no', // Without period
            'Admission Date' => 'admission_date',
            'Address' => 'address',
            'Verification' => 'verification',
            'Admission Amount' => 'admission_amount',
            'KSO' => 'kso',
            'ID' => 'kso_id', // Maps to kso_id field
            'Total' => 'total',
            'Payment' => 'payment',
            'Admission Status' => 'admission_status',
            'Sibling' => 'sibling_name',
            'Name of the School' => 'previous_school_name',
            'Class' => 'class_name',
            'Result' => 'previous_school_result',
        ];

        foreach ($csvData as $index => $row) {
            try {
                // Normalize column names (map Excel headers to standard names)
                $normalizedRow = [];
                foreach ($row as $key => $value) {
                    $trimmedKey = trim($key);
                    $normalizedKey = null;
                    
                    // Check exact match first
                    if (isset($columnMapping[$trimmedKey])) {
                        $normalizedKey = $columnMapping[$trimmedKey];
                    } else {
                        // Check case-insensitive match
                        $lowerKey = strtolower($trimmedKey);
                        foreach ($columnMapping as $excelCol => $dbField) {
                            if (strtolower($excelCol) === $lowerKey) {
                                $normalizedKey = $dbField;
                                break;
                            }
                        }
                    }
                    
                    if ($normalizedKey) {
                        $normalizedRow[$normalizedKey] = trim($value ?? '');
                    } else {
                        // Keep original key for backward compatibility (normalize format)
                        $normalizedRow[strtolower(str_replace([' ', '-', '.'], '_', $trimmedKey))] = trim($value ?? '');
                    }
                }

                // Parse name field if provided (format: "First Middle Last" or "First Last")
                $name = $normalizedRow['name'] ?? '';
                if (!empty($name)) {
                    $nameParts = explode(' ', trim($name));
                    $normalizedRow['first_name'] = $nameParts[0] ?? '';
                    $normalizedRow['last_name'] = end($nameParts) ?? '';
                    $normalizedRow['middle_name'] = count($nameParts) > 2 ? implode(' ', array_slice($nameParts, 1, -1)) : null;
                }

                // Use WhatsApp No. as phone if phone not provided
                if (empty($normalizedRow['phone']) && !empty($normalizedRow['whatsapp_no'])) {
                    $normalizedRow['phone'] = $normalizedRow['whatsapp_no'];
                }

                // Validate required fields
                $requiredFields = ['first_name', 'last_name', 'gender', 'dob'];
                $missingFields = [];

                foreach ($requiredFields as $field) {
                    if (empty($normalizedRow[$field])) {
                        $missingFields[] = $field;
                    }
                }

                if (!empty($missingFields)) {
                    $errors[] = "Row " . ($index + 2) . ": Missing required fields: " . implode(', ', $missingFields);
                    $failed++;
                    continue;
                }

                // Check if email already exists (if email provided)
                if (!empty($normalizedRow['email']) && Student::where('email', $normalizedRow['email'])->exists()) {
                    $errors[] = "Row " . ($index + 2) . ": Email '{$normalizedRow['email']}' already exists";
                    $failed++;
                    continue;
                }

                // Validate email format (if email provided)
                if (!empty($normalizedRow['email']) && !filter_var($normalizedRow['email'], FILTER_VALIDATE_EMAIL)) {
                    $errors[] = "Row " . ($index + 2) . ": Invalid email format '{$normalizedRow['email']}'";
                    $failed++;
                    continue;
                }

                // Validate gender
                $gender = ucfirst(strtolower(trim($normalizedRow['gender'] ?? '')));
                if (!in_array($gender, ['Male', 'Female', 'Other'])) {
                    $errors[] = "Row " . ($index + 2) . ": Invalid gender '{$normalizedRow['gender']}'. Must be Male, Female, or Other";
                    $failed++;
                    continue;
                }

                // Parse DOB - try multiple formats
                $dob = null;
                $dobFormats = ['Y-m-d', 'd/m/Y', 'd-m-Y', 'Y/m/d', 'd.m.Y'];
                foreach ($dobFormats as $format) {
                    try {
                        $dob = Carbon::createFromFormat($format, trim($normalizedRow['dob'] ?? ''));
                        break;
                    } catch (\Exception $e) {
                        continue;
                    }
                }

                if (!$dob) {
                    $errors[] = "Row " . ($index + 2) . ": Invalid date format for DOB '{$normalizedRow['dob']}'. Use YYYY-MM-DD, DD/MM/YYYY, or DD-MM-YYYY";
                    $failed++;
                    continue;
                }

                // Create student
                $student = new Student();
                $student->first_name = $normalizedRow['first_name'];
                $student->last_name = $normalizedRow['last_name'];
                $student->middle_name = $normalizedRow['middle_name'] ?? null;
                $student->email = $normalizedRow['email'] ?? null;
                $student->phone = $normalizedRow['phone'] ?? null;
                $student->dob = $dob->format('Y-m-d');
                $student->dob_status = $normalizedRow['dob_status'] ?? 'Not Verified';
                // Handle Address column (separate from Verification)
                $student->address = $normalizedRow['address'] ?? 'Not Provided';
                $student->permanent_address = $normalizedRow['permanent_address'] ?? null;
                // Verification column is stored but not used in DB
                $student->pincode = $normalizedRow['pincode'] ?? '000000';
                $student->gender = $gender;
                $student->caste_tribe = $normalizedRow['caste_tribe'] ?? null;
                $student->district = $normalizedRow['district'] ?? 'Not Provided';
                $student->institution_code = 'INS' . str_pad($institutionId, 3, '0', STR_PAD_LEFT);
                $student->institution_id = $institutionId;
                $student->class_id = $classId;
                $student->section_id = $sectionId; // Can be null now
                $student->status = 1;
                $student->admin_id = $institutionId;

                // Generate default password
                $defaultPassword = 'student123';
                $student->password = Hash::make($defaultPassword);
                $student->decrypt_pw = $defaultPassword;

                // Parse admission date if provided
                if (!empty($normalizedRow['admission_date'])) {
                    $admissionDate = null;
                    foreach ($dobFormats as $format) {
                        try {
                            $admissionDate = Carbon::createFromFormat($format, trim($normalizedRow['admission_date']));
                            break;
                        } catch (\Exception $e) {
                            continue;
                        }
                    }
                    if ($admissionDate) {
                        $student->admission_date = $admissionDate->format('Y-m-d');
                    }
                }

                // Map Excel columns to database fields
                $student->admission_number = $normalizedRow['admission_number'] ?? null;
                $student->roll_number = $normalizedRow['roll_number'] ?? null;
                $student->pen_no = $normalizedRow['pen_no'] ?? null;
                $student->aadhaar_no = $normalizedRow['aadhaar_no'] ?? null;
                $student->religion = $normalizedRow['religion'] ?? null;
                $student->blood_group = $normalizedRow['blood_group'] ?? null;
                $student->father_name = $normalizedRow['father_name'] ?? null;
                $student->mother_name = $normalizedRow['mother_name'] ?? null;
                $student->previous_school_name = $normalizedRow['previous_school_name'] ?? null;
                $student->previous_school_result = $normalizedRow['previous_school_result'] ?? null;
                
                // Handle admission status if provided
                if (!empty($normalizedRow['admission_status'])) {
                    $admissionStatus = strtolower(trim($normalizedRow['admission_status']));
                    if (in_array($admissionStatus, ['admitted', 'active', '1', 'yes'])) {
                        $student->status = 1;
                    } elseif (in_array($admissionStatus, ['inactive', '0', 'no', 'pending'])) {
                        $student->status = 0;
                    }
                }
                
                // Note: Fields like Address Verification, Admission Amount, KSO ID, Total Payment
                // are not stored in the database but are included in the Excel for reference

                $student->save();
                $successful++;

            } catch (\Exception $e) {
                $errors[] = "Row " . ($index + 2) . ": " . $e->getMessage();
                $failed++;
            }
        }

        return [
            'successful' => $successful,
            'failed' => $failed,
            'errors' => $errors
        ];
    }

    /**
     * Download import template with all Excel headings
     */
    public function downloadTemplate()
    {
        $csvData = [];

        // Excel Headers - exact format as provided
        $csvData[] = [
            'Old / New',
            'Roll No.',
            'Name',
            'Gender',
            'DOB',
            'DOB Status',
            'PEN No.',
            'Aadhaar No.',
            'Mother\'s Name',
            'Father\'s Name',
            'WhatsApp No.',
            'Admission Date',
            'Address',
            'Verification',
            'Admission Amount',
            'KSO',
            'ID',
            'Total',
            'Payment',
            'Admission Status',
            'Sibling',
            'Name of the School',
            'Class',
            'Result'
        ];

        // Add example row
        $csvData[] = [
            'New',
            '001',
            'John Michael Doe',
            'Male',
            '15/05/2010',
            'Verified',
            'PEN123456',
            '123456789012',
            'Jane Doe',
            'John Doe Sr',
            '9876543210',
            '15/01/2024',
            '123 Main Street',
            'Verified',
            '5000',
            'KSO',
            '001',
            '5000',
            '5000',
            'Admitted',
            'Jane Doe Jr',
            'ABC School',
            'Class 1',
            'Pass'
        ];

        // Generate filename
        $filename = 'students_import_template.csv';

        // Set headers for CSV download
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
            'Pragma' => 'no-cache',
            'Expires' => '0'
        ];

        // Create CSV content
        $callback = function() use ($csvData) {
            $file = fopen('php://output', 'w');

            // Add BOM for UTF-8
            fwrite($file, "\xEF\xBB\xBF");

            foreach ($csvData as $row) {
                fputcsv($file, $row);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export all students for the institution
     */
    public function exportAll()
    {
        try {
            $institutionId = auth('institution')->id();

            $students = Student::where('institution_id', $institutionId)
                ->with(['teacher', 'section', 'schoolClass'])
                ->get();

            return $this->generateCSV($students, 'all_students');
        } catch (\Exception $e) {
            Log::error('Error exporting all students: ' . $e->getMessage());
            return response()->json(['error' => 'Export failed'], 500);
        }
    }

    /**
     * Export students for a specific class
     */
    public function exportByClass($classId, Request $request)
    {
        try {
            $institutionId = auth('institution')->id();

            // Verify the class belongs to the institution
            $class = SchoolClass::where('id', $classId)
                ->where('institution_id', $institutionId)
                ->first();

            if (!$class) {
                return response()->json(['error' => 'Class not found'], 404);
            }

            $query = Student::where('institution_id', $institutionId)
                ->where('class_id', $classId)
                ->with(['teacher', 'section', 'schoolClass']);

            // Add section filter if provided
            if ($request->has('section_id') && $request->section_id) {
                $query->where('section_id', $request->section_id);
            }

            // Add status filter (exclude inactive by default)
            if (!$request->has('include_inactive') || !$request->include_inactive) {
                $query->where('status', 1);
            }

            $students = $query->get();

            // Generate filename based on filters
            $filename = 'class_' . $class->name . '_students';
            if ($request->has('section_id') && $request->section_id) {
                $section = Section::find($request->section_id);
                if ($section) {
                    $filename .= '_section_' . $section->name;
                }
            }
            if ($request->has('include_inactive') && $request->include_inactive) {
                $filename .= '_with_inactive';
            }

            return $this->generateCSV($students, $filename);
        } catch (\Exception $e) {
            Log::error('Error exporting class students: ' . $e->getMessage());
            return response()->json(['error' => 'Export failed'], 500);
        }
    }

    /**
     * Generate CSV file for students
     */
    private function generateCSV($students, $filename)
    {
        $csvData = [];

        // Excel Headers - exact format as provided
        $csvData[] = [
            'Old / New',
            'Roll No.',
            'Name',
            'Gender',
            'DOB',
            'DOB Status',
            'PEN No.',
            'Aadhaar No.',
            'Mother\'s Name',
            'Father\'s Name',
            'WhatsApp No.',
            'Admission Date',
            'Address',
            'Verification',
            'Admission Amount',
            'KSO',
            'ID',
            'Total',
            'Payment',
            'Admission Status',
            'Sibling',
            'Name of the School',
            'Class',
            'Result'
        ];

        // Add student data
        foreach ($students as $student) {
            // Combine name fields
            $fullName = trim(($student->first_name ?? '') . ' ' . ($student->middle_name ?? '') . ' ' . ($student->last_name ?? ''));
            
            // Get sibling names if available
            $siblingNames = '';
            if ($student->has_sibling && $student->sibling_ids) {
                $siblingIds = is_array($student->sibling_ids) ? $student->sibling_ids : json_decode($student->sibling_ids, true);
                if ($siblingIds) {
                    $siblings = Student::whereIn('id', $siblingIds)->get(['first_name', 'middle_name', 'last_name']);
                    $siblingNames = $siblings->map(function($s) {
                        return trim(($s->first_name ?? '') . ' ' . ($s->middle_name ?? '') . ' ' . ($s->last_name ?? ''));
                    })->implode(', ');
                }
            }
            
            $csvData[] = [
                'New', // Old / New - default to New for existing students
                $student->roll_number ?? '',
                $fullName,
                $student->gender ?? '',
                $student->dob ? Carbon::parse($student->dob)->format('d/m/Y') : '',
                $student->dob_status ?? 'Not Verified',
                $student->pen_no ?? '',
                $student->aadhaar_no ?? '',
                $student->mother_name ?? '',
                $student->father_name ?? '',
                $student->phone ?? '', // WhatsApp No. - using phone field
                $student->admission_date ? Carbon::parse($student->admission_date)->format('d/m/Y') : '',
                $student->address ?? '', // Address
                '', // Verification - not stored in DB
                '', // Admission Amount - not stored in DB
                '', // KSO - not stored in DB
                '', // ID - not stored in DB
                '', // Total - not stored in DB
                '', // Payment - not stored in DB
                $student->status == 1 ? 'Admitted' : 'Inactive', // Admission Status
                $siblingNames, // Sibling
                $student->previous_school_name ?? '', // Name of the School
                $student->schoolClass->name ?? '', // Class
                $student->previous_school_result ?? '' // Result
            ];
        }

        // Generate filename with timestamp
        $timestamp = now()->format('Y-m-d_H-i-s');
        $fullFilename = $filename . '_' . $timestamp . '.csv';

        // Set headers for CSV download
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $fullFilename . '"',
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
            'Pragma' => 'no-cache',
            'Expires' => '0'
        ];

        // Create CSV content
        $callback = function() use ($csvData) {
            $file = fopen('php://output', 'w');

            // Add BOM for UTF-8
            fwrite($file, "\xEF\xBB\xBF");

            foreach ($csvData as $row) {
                fputcsv($file, $row);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Helper method to upload files
     */
    private function uploadFile($file, $folder)
    {
        $institutionId = auth('institution')->id();
        $institution = Institution::find($institutionId);
        
        $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        
        // Create institution-specific path
        $institutionFolder = $this->sanitizeInstitutionName($institution->name);
        $destinationPath = public_path('Institution/' . $institutionFolder . '/' . $folder);

        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0755, true);
        }

        $file->move($destinationPath, $fileName);

        return 'Institution/' . $institutionFolder . '/' . $folder . '/' . $fileName;
    }

    /**
     * Helper method to sanitize institution name for folder naming
     */
    private function sanitizeInstitutionName($name)
    {
        // Remove special characters and replace spaces with underscores
        $sanitized = preg_replace('/[^A-Za-z0-9\s]/', '', $name);
        $sanitized = preg_replace('/\s+/', '_', trim($sanitized));
        return $sanitized;
    }

    public function showAdmissionForm()
    {
        // Render the admission form view
        $institutionId = auth('institution')->id();
        $institution = Institution::find($institutionId);
        $classes = SchoolClass::where('institution_id', $institutionId)->get();
        $sections = Section::where('institution_id', $institutionId)->get();
        $feeStructure = FeeStructure::where('institution_id', $institutionId)->get();
        $districts = District::all();
        return view('institution.administration.students.admission.admission-form', compact('institution', 'classes', 'sections', 'feeStructure', 'districts'));
    }

    /**
     * Manage permissions for a student
     */
    public function managePermissions($id)
    {
        $institutionId = auth('institution')->id();
        $student = Student::where('id', $id)
                        ->where('institution_id', $institutionId)
                        ->firstOrFail();
        
        return view('institution.administration.students.permissions', compact('student'));
    }

    /**
     * Update permissions for a student
     */
    public function updatePermissions(Request $request, $id)
    {
        $institutionId = auth('institution')->id();
        $student = Student::where('id', $id)
                        ->where('institution_id', $institutionId)
                        ->firstOrFail();
        
        $permissions = $request->input('permissions', []);
        
        // If no permissions selected, set to null to allow all access
        $student->permissions = empty($permissions) ? null : $permissions;
        $student->save();

        return redirect()->route('institution.students.index')->with('success', 'Permissions updated successfully!');
    }
}

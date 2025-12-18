<?php

namespace App\Http\Controllers\Institution\Administration;

use App\Http\Controllers\Controller;
use App\Models\Admission;
use App\Models\Institution;
use App\Models\Payment;
use App\Models\TuitionFeePayment;
use App\Models\FeeStructure;
use App\Models\Student;
use App\Models\SchoolClass;
use App\Models\Hostel;
use App\Models\HostelPayment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class AdmissionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:institution');
    }

    /**
     * Search for students (for sibling selection)
     */
    public function searchSiblings(Request $request)
    {
        $institutionId = auth('institution')->id();
        $query = $request->get('q');

        $students = Student::where('institution_id', $institutionId)
            ->where(function ($q) use ($query) {
                $q->where('first_name', 'like', "%{$query}%")
                    ->orWhere('last_name', 'like', "%{$query}%")
                    ->orWhere('admission_number', 'like', "%{$query}%")
                    ->orWhere('student_id', 'like', "%{$query}%");
            })
            ->select('id', 'first_name', 'last_name', 'admission_number', 'father_name', 'mother_name', 'address', 'pincode', 'district')
            ->limit(10)
            ->get();

        return response()->json($students);
    }

    /**
     * Get student details for sibling auto-fill
     */
    public function getSiblingDetails($id)
    {
        $institutionId = auth('institution')->id();
        $student = Student::where('institution_id', $institutionId)
            ->findOrFail($id);

        return response()->json($student);
    }

    /**
     * List all admissions with filters
     */
    public function list(Request $request)
    {
        $institution = Auth::guard('institution')->user();

        $query = Admission::with(['institution', 'schoolClass'])
            ->where('institution_id', $institution->id);

        // Search by name
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', '%' . $search . '%')
                  ->orWhere('last_name', 'like', '%' . $search . '%')
                  ->orWhere('admission_number', 'like', '%' . $search . '%')
                  ->orWhere('roll_number', 'like', '%' . $search . '%')
                  ->orWhere('phone', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%');
            });
        }

        // Filter by class
        if ($request->filled('class_id')) {
            $query->where('class_id', $request->class_id);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by admission date from
        if ($request->filled('admission_date_from')) {
            $query->whereDate('admission_date', '>=', $request->admission_date_from);
        }

        // Filter by admission date to
        if ($request->filled('admission_date_to')) {
            $query->whereDate('admission_date', '<=', $request->admission_date_to);
        }

        // Filter by created date from
        if ($request->filled('created_from')) {
            $query->whereDate('created_at', '>=', $request->created_from);
        }

        // Filter by created date to
        if ($request->filled('created_to')) {
            $query->whereDate('created_at', '<=', $request->created_to);
        }

        $admissions = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        // Get classes for filter dropdown
        $classes = SchoolClass::where('institution_id', $institution->id)
            ->where('status', 1)
            ->orderBy('name')
            ->get();

        return view('institution.administration.students.admission.admission-list', compact('admissions', 'classes'));
    }

    /**
     * Store admission form data
     */
    public function store(Request $request)
    {
        // Get the authenticated institution
        $institution = Auth::guard('institution')->user();
        $institutionId = $institution->id;

        // Validate the request
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'phone' => 'nullable|string|max:20',
            'gender' => 'nullable|in:Male,Female,Other',
            'class_id' => 'nullable|exists:classes,id',
            'permanent_pincode' => 'nullable|string|max:10',
            'permanent_district' => 'nullable|string|max:100',
            'admission_date' => 'nullable|string',
            'dob' => 'nullable|string',
            'dob_status' => 'nullable|string|in:Verified,Not Verified',
            'age_years' => 'nullable|integer',
            'age_months' => 'nullable|integer',
            'email' => 'nullable|email|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
            'aadhaar_front' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
            'aadhaar_back' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
            'parent_aadhaar_front' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
            'parent_aadhaar_back' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
            'guardian_aadhaar_front' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
            'guardian_aadhaar_back' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
            'document_01_file' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120',
            'document_02_file' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120',
            'document_03_file' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120',
            'document_04_file' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120',
            'has_sibling' => 'nullable|boolean',
            'sibling_ids' => 'nullable|array',
            'sibling_ids.*' => 'exists:students,id',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->route('institution.admission.admission-form')
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // Prepare data array
            $data = [
                'institution_id' => $institutionId,
                'institution_code' => $institution->code,
                'admission_date' => $request->admission_date,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email ?? '',
                'phone' => $request->phone ?? '',
                'gender' => $request->gender,
                'class_id' => $request->class_id,
                'pen_no' => $request->pen_no,

                // Address Information
                'address' => $request->address,
                'pincode' => $request->pincode,
                'district' => $request->district,
                'permanent_address' => $request->permanent_address,
                'permanent_pincode' => $request->permanent_pincode,
                'permanent_district' => $request->permanent_district,

                // Personal Information
                'religion' => $request->religion,
                'caste_tribe' => $request->caste_tribe,
                'dob_status' => $request->dob_status ?? 'Not Verified',
                'age_years' => $request->age_years,
                'age_months' => $request->age_months,

                // Medical Record
                'blood_group' => $request->blood_group,
                'height' => $request->height,
                'weight' => $request->weight,

                // Parents Information
                'father_name' => $request->father_name,
                'mother_name' => $request->mother_name,
                'father_occupation' => $request->father_occupation,
                'father_phone' => $request->father_phone,

                // Guardian Information
                'guardian_name' => $request->guardian_name,
                'guardian_relation_text' => $request->guardian_relation_text,
                'guardian_phone' => $request->guardian_phone,
                'guardian_address' => $request->guardian_address,

                // Previous Academic Information
                'previous_school_name' => $request->previous_school_name,
                'previous_school_address' => $request->previous_school_address,
                'previous_school_class' => $request->previous_school_class,
                'previous_school_result' => $request->previous_school_result,

                // Aadhaar Information
                'aadhaar_no' => $request->aadhaar_no,

                // Document Titles
                'document_01_title' => $request->document_01_title,
                'document_02_title' => $request->document_02_title,
                'document_03_title' => $request->document_03_title,
                'document_04_title' => $request->document_04_title,

                // Payment Information
                'admission_fee_amount' => $request->admission_payment_amount ?? null,
                'admission_payment_method' => $request->admission_payment_method ?? null,
                'tuition_fee_amount' => $request->tuition_payment_amount ?? null,
                'tuition_payment_method' => $request->tuition_payment_method ?? null,
                'hostel_admission_fee_amount' => $request->hostel_admission_payment_amount ?? null,
                'hostel_admission_payment_method' => $request->hostel_admission_payment_method ?? null,
                'hostel_tuition_fee_amount' => $request->hostel_tuition_payment_amount ?? null,
                'hostel_tuition_payment_method' => $request->hostel_tuition_payment_method ?? null,

                'discount_category' => $request->discount_category ?? null,
                'discount_amount' => $request->discount_amount ?? 0,
                'discount_percentage' => $request->discount_percentage ?? 0,

                // Status
                'status' => 'pending',
                'has_sibling' => $request->has_sibling ? true : false,
                'sibling_ids' => $request->sibling_ids,
            ];

            // Parse dates (form uses "d M, Y" format)
            if ($request->admission_date) {
                try {
                    $data['admission_date'] = Carbon::createFromFormat('d M, Y', $request->admission_date)->format('Y-m-d');
                } catch (\Exception $e) {
                    // Try alternative format
                    $data['admission_date'] = Carbon::parse($request->admission_date)->format('Y-m-d');
                }
            }

            if ($request->dob) {
                try {
                    $data['dob'] = Carbon::createFromFormat('d M, Y', $request->dob)->format('Y-m-d');
                } catch (\Exception $e) {
                    // Try alternative format
                    $data['dob'] = Carbon::parse($request->dob)->format('Y-m-d');
                }
            }

            // Handle file uploads
            $institutionCode = $institution->institution_code ?? 'unknown';
            $uploadPath = "institution/{$institutionCode}/students/admissions";
            $fullPath = public_path($uploadPath);

            if (!File::exists($fullPath)) {
                File::makeDirectory($fullPath, 0755, true);
            }

            // Student Photo
            if ($request->hasFile('photo')) {
                $data['photo'] = $this->uploadFile($request->file('photo'), $uploadPath . '/photos', 'photo');
            }

            // Student Aadhaar Documents
            if ($request->hasFile('aadhaar_front')) {
                $data['aadhaar_front'] = $this->uploadFile($request->file('aadhaar_front'), $uploadPath . '/aadhaar', 'aadhaar_front');
            }

            if ($request->hasFile('aadhaar_back')) {
                $data['aadhaar_back'] = $this->uploadFile($request->file('aadhaar_back'), $uploadPath . '/aadhaar', 'aadhaar_back');
            }

            // Parent Aadhaar Documents
            if ($request->hasFile('parent_aadhaar_front')) {
                $data['parent_aadhaar_front'] = $this->uploadFile($request->file('parent_aadhaar_front'), $uploadPath . '/parents', 'parent_aadhaar_front');
            }

            if ($request->hasFile('parent_aadhaar_back')) {
                $data['parent_aadhaar_back'] = $this->uploadFile($request->file('parent_aadhaar_back'), $uploadPath . '/parents', 'parent_aadhaar_back');
            }

            // Guardian Aadhaar Documents
            if ($request->hasFile('guardian_aadhaar_front')) {
                $data['guardian_aadhaar_front'] = $this->uploadFile($request->file('guardian_aadhaar_front'), $uploadPath . '/guardians', 'guardian_aadhaar_front');
            }

            if ($request->hasFile('guardian_aadhaar_back')) {
                $data['guardian_aadhaar_back'] = $this->uploadFile($request->file('guardian_aadhaar_back'), $uploadPath . '/guardians', 'guardian_aadhaar_back');
            }

            // Other Documents
            $documentFields = ['document_01_file', 'document_02_file', 'document_03_file', 'document_04_file'];
            foreach ($documentFields as $field) {
                if ($request->hasFile($field)) {
                    $data[str_replace('_file', '', $field)] = $this->uploadFile($request->file($field), $uploadPath . '/documents', $field);
                }
            }

            // Generate admission number if not provided
            if (empty($request->admission_number)) {
                $data['admission_number'] = $this->generateAdmissionNumber($institutionId, $request->class_id);
            } else {
                $data['admission_number'] = $request->admission_number;
            }

            // Set roll number if provided
            if ($request->roll_number) {
                $data['roll_number'] = $request->roll_number;
            }

            // Create admission record
            $admission = Admission::create($data);
            // Create new student record

            $password = Student::generatePassword();

            // Prepare student data
            $studentData = array_merge($data, [
                'password' => bcrypt($password),
                'decrypt_pw' => $password,
                'address' => $data['permanent_address'],
                'pincode' => $data['permanent_pincode'],
                'district' => $data['permanent_district'],
                'student_id' => $request->admission_number,
                'discount_percentage' => $request->discount_percentage ?? 0,
                'has_sibling' => $data['has_sibling'],
                'sibling_ids' => $data['sibling_ids'],
            ]);
            $student = Student::create($studentData);

            // Link this student to existing siblings
            if ($data['has_sibling'] && !empty($data['sibling_ids'])) {
                foreach ($data['sibling_ids'] as $siblingId) {
                    $sibling = Student::find($siblingId);
                    if ($sibling) {
                        $currentSiblings = $sibling->sibling_ids ?? [];
                        if (!in_array($student->id, $currentSiblings)) {
                            $currentSiblings[] = $student->id;
                            $sibling->update([
                                'sibling_ids' => $currentSiblings,
                                'has_sibling' => true
                            ]);
                        }
                    }
                }
            }

            // Create hostel record
            $hostel = Hostel::create([
                'student_id' => $student->id ?? null,
                'institution_id' => $institutionId,
            ]);

            // Store payment records
            DB::beginTransaction();
            try {
                // Store admission fee payment in payments table
                if ($request->admission_payment_amount && $request->admission_payment_amount > 0 && $request->class_id) {
                    // Find admission fee structure
                    $admissionFeeStructure = FeeStructure::where('institution_id', $institutionId)
                        ->where('class_id', $request->class_id)
                        ->where(function ($query) {
                            $query->where('name', 'like', '%admission%')
                                ->orWhere('fee_type', 'onetime');
                        })
                        ->where('status', 1)
                        ->first();

                    // If no fee with "admission" in name, look for any one-time fee
                    if (!$admissionFeeStructure) {
                        $admissionFeeStructure = FeeStructure::where('institution_id', $institutionId)
                            ->where('class_id', $request->class_id)
                            ->where('fee_type', 'onetime')
                            ->where('status', 1)
                            ->first();
                    }

                    if ($admissionFeeStructure) {
                        // Create payment record in payments table
                        Payment::create([
                            'institution_id' => $institutionId,
                            'admission_id' => $admission->id,
                            'student_id' => $student->id ?? null, // Will be updated when student is created
                            'fee_structure_id' => $admissionFeeStructure->id,
                            'amount' => $request->admission_payment_amount,
                            'discount_amount' => $request->discount_amount ?? 0,
                            'discount_percentage' => $request->discount_percentage ?? 0,
                            'payment_method' => $request->admission_payment_method ?? 'cash',
                            'payment_date' => $data['admission_date'] ?? now()->format('Y-m-d'),
                            'receipt_number' => Payment::generateReceiptNumber($institutionId),
                            'status' => 'completed',
                            'notes' => 'Admission fee payment made during admission',
                        ]);
                    }
                }

                // Store tuition fee payment in tuition_fee_payments table
                if ($request->tuition_payment_amount && $request->tuition_payment_amount > 0 && $request->class_id) {
                    // Find tuition fee structure (monthly fee)
                    $tuitionFeeStructure = FeeStructure::where('institution_id', $institutionId)
                        ->where('class_id', $request->class_id)
                        ->where('fee_type', 'monthly')
                        ->where(function ($query) {
                            $query->where('name', 'like', '%tuition%');
                        })
                        ->where('status', 1)
                        ->first();

                    // If no fee with "tuition" in name, look for any monthly fee
                    if (!$tuitionFeeStructure) {
                        $tuitionFeeStructure = FeeStructure::where('institution_id', $institutionId)
                            ->where('class_id', $request->class_id)
                            ->where('fee_type', 'monthly')
                            ->where('status', 1)
                            ->first();
                    }

                    if ($tuitionFeeStructure) {
                        // Get selected months - check both array and comma-separated string
                        $selectedMonths = $request->tuition_months ?? [];
                        if (empty($selectedMonths) && $request->tuition_selected_months) {
                            $selectedMonths = explode(',', $request->tuition_selected_months);
                        }
                        $selectedMonthsArray = is_array($selectedMonths) ? array_filter($selectedMonths) : [];
                        $numberOfMonths = count($selectedMonthsArray);

                        // Get monthly fee amount
                        $monthlyFeeAmount = $tuitionFeeStructure->amount;

                        // Create tuition fee payment record in tuition_fee_payments table
                        TuitionFeePayment::create([
                            'institution_id' => $institutionId,
                            'admission_id' => $admission->id,
                            'student_id' => $student->id ?? null, // Will be updated when student is created
                            'fee_structure_id' => $tuitionFeeStructure->id,
                            'payment_amount' => $request->tuition_payment_amount,
                            'discount_amount' => 0, // Tuition discount not implemented in form yet
                            'discount_percentage' => 0,
                            'payment_method' => $request->tuition_payment_method ?? 'cash',
                            'payment_date' => $data['admission_date'] ?? now()->format('Y-m-d'),
                            'receipt_number' => TuitionFeePayment::generateReceiptNumber($institutionId),
                            'selected_months' => array_map('intval', $selectedMonthsArray),
                            'monthly_fee_amount' => $monthlyFeeAmount,
                            'number_of_months' => $numberOfMonths,
                            'status' => 'completed',
                            'notes' => 'Tuition fee payment made during admission',
                        ]);

                        // Also create payment record in payments table
                        Payment::create([
                            'institution_id' => $institutionId,
                            'admission_id' => $admission->id,
                            'student_id' => $student->id ?? null, // Will be updated when student is created
                            'fee_structure_id' => $tuitionFeeStructure->id,
                            'amount' => $request->tuition_payment_amount,
                            'discount_amount' => 0,
                            'discount_percentage' => 0,
                            'payment_method' => $request->tuition_payment_method ?? 'cash',
                            'payment_date' => $data['admission_date'] ?? now()->format('Y-m-d'),
                            'receipt_number' => Payment::generateReceiptNumber($institutionId),
                            'status' => 'completed',
                            'notes' => 'Tuition fee payment made during admission - Months: ' . implode(', ', array_map(function($m) {
                                $months = ['', 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
                                return $months[$m] ?? $m;
                            }, $selectedMonthsArray)),
                        ]);
                    }
                }

                if ($request->hostel_admission_payment_amount && $request->hostel_admission_payment_amount > 0 && $request->class_id) {
                    $hostelAdmissionFeeStructure = FeeStructure::where('institution_id', $institutionId)
                        ->where('class_id', $request->class_id)
                        ->where('fee_type', 'onetime')
                        ->where(function ($query) {
                            $query->where('name', 'like', '%hostel admission%');
                        })
                        ->where('status', 1)
                        ->first();
                    if ($hostelAdmissionFeeStructure) {
                        Payment::create([
                            'institution_id' => $institutionId,
                            'admission_id' => $admission->id,
                            'student_id' => $student->id ?? null, // Will be updated when student is created
                            'fee_structure_id' => $hostelAdmissionFeeStructure->id,
                            'amount' => $request->hostel_admission_payment_amount,
                            'discount_amount' => 0,
                            'discount_percentage' => 0,
                            'payment_method' => $request->hostel_admission_payment_method ?? 'cash',
                            'payment_date' => $data['admission_date'] ?? now()->format('Y-m-d'),
                            'receipt_number' => Payment::generateReceiptNumber($institutionId),
                            'status' => 'completed',
                            'notes' => 'Hostel admission fee payment made during admission',
                        ]);
                    }
                }

                if ($request->hostel_tuition_payment_amount && $request->hostel_tuition_payment_amount > 0) {
                    $hostelFeeStructure = FeeStructure::where('institution_id', $institutionId)
                        ->where('class_id', (int) $request->class_id)
                        ->where('fee_type', 'monthly')
                        ->whereRaw('LOWER(name) LIKE ?', ['%hostel fee%'])
                        ->where('status', 1)
                        ->first();

                    if ($hostelFeeStructure) {
                        HostelPayment::create([
                            'hostel_id' => $hostel->id ?? null,
                            'institution_id' => $student->institution_id ?? null,
                            'amount' => $request->hostel_tuition_payment_amount,
                            'discount_amount' => 0,
                            'discount_percentage' => 0,
                            'payment_date' => $data['admission_date'] ?? now()->format('Y-m-d'),
                            'months_paid' => $request->hostel_tuition_selected_months,
                            'receipt_number' => HostelPayment::generateReceiptNumber($student->institution_id),
                            'payment_method' => $request->hostel_tuition_payment_method ?? 'cash',
                            'fee_structure_id' => $hostelFeeStructure->id,
                            'student_id' => $student->id ?? null,
                        ]);
                    }
                }

                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                // Log the error but don't fail the admission creation
                Log::error('Error creating payment records: ' . $e->getMessage());
            }

            return redirect()
                ->route('institution.admission.success', $admission->id)
                ->with('success', 'Admission form submitted successfully!');

        } catch (\Exception $e) {
            return redirect()
                ->route('institution.admission.admission-form')
                ->withErrors(['error' => 'An error occurred while submitting the form: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Show admission details page
     */
    public function show($id)
    {
        $institution = Auth::guard('institution')->user();

        $admission = Admission::with([
            'institution',
            'schoolClass',
            'previousSchoolClass'
        ])
        ->where('institution_id', $institution->id)
        ->findOrFail($id);

        // Get payment records for this admission
        $admissionPayments = Payment::with('feeStructure')
            ->where('admission_id', $id)
            ->get();

        $tuitionFeePayments = TuitionFeePayment::with('feeStructure')
            ->where('admission_id', $id)
            ->get();

        return view('institution.administration.students.admission.admission-details', compact(
            'admission',
            'admissionPayments',
            'tuitionFeePayments'
        ));
    }

    /**
     * Show admission success page
     */
    public function success($id)
    {
        $institution = Auth::guard('institution')->user();

        $admission = Admission::with([
            'institution',
            'schoolClass'
        ])
        ->where('institution_id', $institution->id)
        ->findOrFail($id);

        // Get payment records for this admission
        $admissionPayments = Payment::with('feeStructure')
            ->where('admission_id', $id)
            ->get();

        $tuitionFeePayments = TuitionFeePayment::with('feeStructure')
            ->where('admission_id', $id)
            ->get();

        return view('institution.administration.students.admission.admission-success', compact(
            'admission',
            'admissionPayments',
            'tuitionFeePayments'
        ));
    }

    /**
     * Generate admission fee receipt
     */
    public function generateAdmissionReceipt($admissionId, $paymentId)
    {
        $institution = Auth::guard('institution')->user();

        $admission = Admission::with(['institution', 'schoolClass'])
            ->where('institution_id', $institution->id)
            ->findOrFail($admissionId);

        $payment = Payment::with(['student', 'feeStructure', 'feeStructure.schoolClass','TuitionFeePayment'])
            ->where('admission_id', $admissionId)
            ->where('id', $paymentId)
            ->where('institution_id', $institution->id)
            ->firstOrFail();
        return view('institution.payment.payments.show', compact('payment'));
        // return view('institution.administration.students.receipts.admission-receipt', compact('admission', 'payment'));
    }

    /**
     * Generate tuition fee receipt
     */
    public function generateTuitionReceipt($admissionId, $paymentId)
    {
        $institution = Auth::guard('institution')->user();

        $admission = Admission::with(['institution', 'schoolClass'])
            ->where('institution_id', $institution->id)
            ->findOrFail($admissionId);

        $payment = TuitionFeePayment::with(['student', 'feeStructure', 'feeStructure.schoolClass','Payment'])
            ->where('admission_id', $admissionId)
            ->where('id', $paymentId)
            ->where('institution_id', $institution->id)
            ->firstOrFail();

            return view('institution.payment.payments.show', compact('payment'));
        // return view('institution.administration.students.receipts.tuition-receipt', compact('admission', 'payment'));
    }

    /**
     * Print admission form
     */
    public function printForm($id)
    {
        $institution = Auth::guard('institution')->user();

        $admission = Admission::with([
            'institution',
            'schoolClass',
            'previousSchoolClass'
        ])
        ->where('institution_id', $institution->id)
        ->findOrFail($id);

        return view('institution.administration.students.admission.print-admission-form', compact('admission'));
    }

    /**
     * Upload file helper method
     */
    private function uploadFile($file, $folder, $prefix = 'file')
    {
        $institutionId = auth('institution')->id();
        $institution = Institution::find($institutionId);

        $fileName = time() . '_' . uniqid() . '_' . $prefix . '.' . $file->getClientOriginalExtension();

        // Create institution-specific path
        $institutionFolder = $this->sanitizeInstitutionName($institution->name);
        $fullPath = 'Institution/' . $institutionFolder . '/' . $folder;
        $destinationPath = public_path($fullPath);

        if (!File::exists($destinationPath)) {
            File::makeDirectory($destinationPath, 0755, true);
        }

        $file->move($destinationPath, $fileName);

        return $fullPath . '/' . $fileName;
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

    /**
     * Generate admission number
     */
    private function generateAdmissionNumber($institutionId, $classId = null)
    {
        $institution = Institution::find($institutionId);
        $institutionCode = $institution->institution_code ?? 'INST';

        // Academic year as YYYY-YYYY+1 (e.g., 2026-2027)
        $currentYear = (int)date('Y');
        $nextYear = $currentYear + 1;
        $academicYear = $currentYear . $nextYear;

        // For the display, should be like 2627 for 2026-2027
        $academicYearShort = substr($academicYear, 2, 2) . substr($academicYear, 6, 2);

        // Default classId as 0 padded to 2
        $classIdPart = $classId ? str_pad($classId, 2, '0', STR_PAD_LEFT) : '00';

        // Get the last admission for this institution, year, class
        $lastAdmission = Admission::where('institution_id', $institutionId)
            ->where('class_id', $classId)
            ->whereNotNull('admission_number')
            ->orderBy('id', 'desc')
            ->first();

        if ($lastAdmission && $lastAdmission->admission_number) {
            // Extract number after last '/' (sequential id)
            $parts = explode('/', $lastAdmission->admission_number);
            $lastNumber = isset($parts[3]) ? (int)$parts[3] : 0;
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        // Format: SKA/2627/01/001
        return $institutionCode
            . '/' . $nextYear
            . '/' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Preview admissions in Excel-like format
     */
    public function previewExcel(Request $request)
    {
        try {
            $institution = Auth::guard('institution')->user();

            $query = Admission::with(['institution', 'schoolClass', 'previousSchoolClass'])
                ->where('institution_id', $institution->id);

            // Apply same filters as list method
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('first_name', 'like', '%' . $search . '%')
                      ->orWhere('last_name', 'like', '%' . $search . '%')
                      ->orWhere('admission_number', 'like', '%' . $search . '%')
                      ->orWhere('roll_number', 'like', '%' . $search . '%')
                      ->orWhere('phone', 'like', '%' . $search . '%')
                      ->orWhere('email', 'like', '%' . $search . '%');
                });
            }

            if ($request->filled('class_id')) {
                $query->where('class_id', $request->class_id);
            }

            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            if ($request->filled('admission_date_from')) {
                $query->whereDate('admission_date', '>=', $request->admission_date_from);
            }

            if ($request->filled('admission_date_to')) {
                $query->whereDate('admission_date', '<=', $request->admission_date_to);
            }

            if ($request->filled('created_from')) {
                $query->whereDate('created_at', '>=', $request->created_from);
            }

            if ($request->filled('created_to')) {
                $query->whereDate('created_at', '<=', $request->created_to);
            }

            $admissions = $query->orderBy('created_at', 'desc')->get();

            return view('institution.administration.students.admission.excel-preview', compact('admissions'));
        } catch (\Exception $e) {
            Log::error('Error previewing admissions: ' . $e->getMessage());
            return response()->json(['error' => 'Preview failed: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Export admissions to Excel
     */
    public function exportExcel(Request $request)
    {
        try {
            $institution = Auth::guard('institution')->user();

            $query = Admission::with(['institution', 'schoolClass', 'previousSchoolClass'])
                ->where('institution_id', $institution->id);

            // Apply same filters as list method
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('first_name', 'like', '%' . $search . '%')
                      ->orWhere('last_name', 'like', '%' . $search . '%')
                      ->orWhere('admission_number', 'like', '%' . $search . '%')
                      ->orWhere('roll_number', 'like', '%' . $search . '%')
                      ->orWhere('phone', 'like', '%' . $search . '%')
                      ->orWhere('email', 'like', '%' . $search . '%');
                });
            }

            if ($request->filled('class_id')) {
                $query->where('class_id', $request->class_id);
            }

            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            if ($request->filled('admission_date_from')) {
                $query->whereDate('admission_date', '>=', $request->admission_date_from);
            }

            if ($request->filled('admission_date_to')) {
                $query->whereDate('admission_date', '<=', $request->admission_date_to);
            }

            if ($request->filled('created_from')) {
                $query->whereDate('created_at', '>=', $request->created_from);
            }

            if ($request->filled('created_to')) {
                $query->whereDate('created_at', '<=', $request->created_to);
            }

            $admissions = $query->orderBy('created_at', 'desc')->get();

            return $this->generateExcel($admissions);
        } catch (\Exception $e) {
            Log::error('Error exporting admissions: ' . $e->getMessage());
            return redirect()
                ->route('institution.admission.list')
                ->withErrors(['error' => 'Export failed: ' . $e->getMessage()]);
        }
    }

    /**
     * Generate Excel file for admissions
     */
    private function generateExcel($admissions)
    {
        $excelData = [];

        // Excel Headers - Matching the requested preview format
        $excelData[] = [
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
            'Discount Category',
            'Discount Amount',
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

        // Add admission data
        foreach ($admissions as $admission) {
            $excelData[] = [
                'New', // Default to New for admission records
                $admission->roll_number ?? 'N/A',
                ($admission->first_name ?? '') . ' ' . ($admission->last_name ?? ''),
                $admission->gender ?? 'N/A',
                $admission->dob ? Carbon::parse($admission->dob)->format('d/m/Y') : 'N/A',
                'N/A', // DOB Status
                $admission->pen_no ?? 'N/A',
                $admission->aadhaar_no ?? 'N/A',
                $admission->mother_name ?? 'N/A',
                $admission->father_name ?? 'N/A',
                $admission->phone ?? 'N/A',
                $admission->admission_date ? Carbon::parse($admission->admission_date)->format('d/m/Y') : 'N/A',
                $admission->address ?? 'N/A',
                $admission->discount_category ?? 'None',
                $admission->discount_amount ?? '0.00',
                ucfirst($admission->status ?? 'Pending'), // Verification
                $admission->admission_fee_amount ?? '0.00',
                'N/A', // KSO
                $admission->admission_number ?? 'N/A',
                number_format(($admission->admission_fee_amount ?? 0) + ($admission->tuition_fee_amount ?? 0), 2),
                $admission->admission_payment_method ?? 'N/A',
                ucfirst($admission->status ?? 'Pending'),
                $admission->has_sibling && $admission->sibling_ids ? 
                    \App\Models\Student::whereIn('id', $admission->sibling_ids)->get()->map(fn($s) => $s->first_name . ' ' . $s->last_name)->implode(', ') : 'N/A',
                $admission->previous_school_name ?? 'N/A',
                $admission->previousSchoolClass->name ?? 'N/A',
                $admission->previous_school_result ?? 'N/A',
            ];
        }

        // Generate filename with timestamp
        $timestamp = now()->format('Y-m-d_H-i-s');
        $filename = 'admissions_' . $timestamp . '.xls';

        // Generate Excel XML format with styles for yellow header
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<?mso-application progid="Excel.Sheet"?>' . "\n";
        $xml .= '<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet"' . "\n";
        $xml .= ' xmlns:o="urn:schemas-microsoft-com:office:office"' . "\n";
        $xml .= ' xmlns:x="urn:schemas-microsoft-com:office:excel"' . "\n";
        $xml .= ' xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet"' . "\n";
        $xml .= ' xmlns:html="http://www.w3.org/TR/REC-html40">' . "\n";

        // Add Styles
        $xml .= '<Styles>' . "\n";
        $xml .= ' <Style ss:ID="Default" ss:Name="Normal">' . "\n";
        $xml .= '  <Alignment ss:Vertical="Bottom"/>' . "\n";
        $xml .= '  <Borders/>' . "\n";
        $xml .= '  <Font ss:FontName="Calibri" x:Family="Swiss" ss:Size="11" ss:Color="#000000"/>' . "\n";
        $xml .= '  <Interior/>' . "\n";
        $xml .= '  <NumberFormat/>' . "\n";
        $xml .= '  <Protection/>' . "\n";
        $xml .= ' </Style>' . "\n";
        $xml .= ' <Style ss:ID="Header">' . "\n";
        $xml .= '  <Alignment ss:Horizontal="Left" ss:Vertical="Center"/>' . "\n";
        $xml .= '  <Borders>' . "\n";
        $xml .= '   <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1"/>' . "\n";
        $xml .= '   <Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1"/>' . "\n";
        $xml .= '   <Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1"/>' . "\n";
        $xml .= '   <Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1"/>' . "\n";
        $xml .= '  </Borders>' . "\n";
        $xml .= '  <Font ss:FontName="Calibri" x:Family="Swiss" ss:Size="11" ss:Color="#000000" ss:Bold="1"/>' . "\n";
        $xml .= '  <Interior ss:Color="#FFFF00" ss:Pattern="Solid"/>' . "\n";
        $xml .= ' </Style>' . "\n";
        $xml .= ' <Style ss:ID="Cell">' . "\n";
        $xml .= '  <Borders>' . "\n";
        $xml .= '   <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1"/>' . "\n";
        $xml .= '   <Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1"/>' . "\n";
        $xml .= '   <Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1"/>' . "\n";
        $xml .= '   <Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1"/>' . "\n";
        $xml .= '  </Borders>' . "\n";
        $xml .= ' </Style>' . "\n";
        $xml .= '</Styles>' . "\n";

        $xml .= '<Worksheet ss:Name="Admissions">' . "\n";
        $xml .= '<Table>' . "\n";

        // Headers
        $xml .= '<Row ss:Height="20">' . "\n";
        foreach ($excelData[0] as $cell) {
            $cellValue = htmlspecialchars($cell, ENT_XML1, 'UTF-8');
            $xml .= '<Cell ss:StyleID="Header"><Data ss:Type="String">' . $cellValue . '</Data></Cell>' . "\n";
        }
        $xml .= '</Row>' . "\n";

        // Data
        for ($i = 1; $i < count($excelData); $i++) {
            $xml .= '<Row>' . "\n";
            foreach ($excelData[$i] as $cell) {
                $cellValue = htmlspecialchars($cell, ENT_XML1, 'UTF-8');
                $type = is_numeric($cell) ? 'Number' : 'String';
                $xml .= '<Cell ss:StyleID="Cell"><Data ss:Type="' . $type . '">' . $cellValue . '</Data></Cell>' . "\n";
            }
            $xml .= '</Row>' . "\n";
        }

        $xml .= '</Table>' . "\n";
        $xml .= '</Worksheet>' . "\n";
        $xml .= '</Workbook>';

        // Set headers for Excel download
        $headers = [
            'Content-Type' => 'application/vnd.ms-excel',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
            'Pragma' => 'no-cache',
            'Expires' => '0'
        ];

        return response($xml, 200, $headers);
    }
}

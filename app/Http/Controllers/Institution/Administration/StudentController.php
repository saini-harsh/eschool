<?php

namespace App\Http\Controllers\Institution\Administration;

use App\Http\Controllers\Controller;
use App\Models\Institution;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Section;
use App\Models\SchoolClass;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Barryvdh\DomPDF\Facade\Pdf;

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
            'class_id' => 'required|integer',
            'section_id' => 'required|integer'
        ]);

        $rollNumber = Student::generateRollNumber(
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
        $student->status         = 1;
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

        return view('institution.administration.students.show', compact('student'));
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

        $fileName = 'Student_' . ($student->student_id ?? $student->id) . '.pdf';
        return $pdf->download($fileName);
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
                'section_id' => 'required|exists:sections,id',
                'csv_file' => 'required|file|mimes:csv,txt|max:10240', // 10MB max
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

            // Verify section belongs to class
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
            $importResult = $this->importStudentsFromCsv($csvData, $institutionId, $request->class_id, $request->section_id);

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
     * Parse CSV file and return array of data
     */
    private function parseCsvFile($file)
    {
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
     * Import students from CSV data
     */
    private function importStudentsFromCsv($csvData, $institutionId, $classId, $sectionId)
    {
        $successful = 0;
        $failed = 0;
        $errors = [];

        foreach ($csvData as $index => $row) {
            try {
                // Validate required fields
                $requiredFields = ['first_name', 'last_name', 'email', 'phone', 'dob', 'address', 'pincode', 'gender', 'district'];
                $missingFields = [];

                foreach ($requiredFields as $field) {
                    if (empty($row[$field])) {
                        $missingFields[] = $field;
                    }
                }

                if (!empty($missingFields)) {
                    $errors[] = "Row " . ($index + 2) . ": Missing required fields: " . implode(', ', $missingFields);
                    $failed++;
                    continue;
                }

                // Check if email already exists
                if (Student::where('email', $row['email'])->exists()) {
                    $errors[] = "Row " . ($index + 2) . ": Email '{$row['email']}' already exists";
                    $failed++;
                    continue;
                }

                // Validate email format
                if (!filter_var($row['email'], FILTER_VALIDATE_EMAIL)) {
                    $errors[] = "Row " . ($index + 2) . ": Invalid email format '{$row['email']}'";
                    $failed++;
                    continue;
                }

                // Validate gender
                if (!in_array($row['gender'], ['Male', 'Female', 'Other'])) {
                    $errors[] = "Row " . ($index + 2) . ": Invalid gender '{$row['gender']}'. Must be Male, Female, or Other";
                    $failed++;
                    continue;
                }

                // Validate date format
                try {
                    $dob = Carbon::createFromFormat('Y-m-d', $row['dob']);
                } catch (\Exception $e) {
                    $errors[] = "Row " . ($index + 2) . ": Invalid date format for DOB '{$row['dob']}'. Use YYYY-MM-DD";
                    $failed++;
                    continue;
                }

                // Create student
                $student = new Student();
                $student->first_name = $row['first_name'];
                $student->last_name = $row['last_name'];
                $student->middle_name = $row['middle_name'] ?? null;
                $student->email = $row['email'];
                $student->phone = $row['phone'];
                $student->dob = $dob->format('Y-m-d');
                $student->address = $row['address'];
                $student->permanent_address = $row['permanent_address'] ?? null;
                $student->pincode = $row['pincode'];
                $student->gender = $row['gender'];
                $student->caste_tribe = $row['caste_tribe'] ?? null;
                $student->district = $row['district'];
                $student->institution_code = 'INS' . str_pad($institutionId, 3, '0', STR_PAD_LEFT);
                $student->institution_id = $institutionId;
                $student->class_id = $classId;
                $student->section_id = $sectionId;
                $student->status = 1;
                $student->admin_id = $institutionId;

                // Generate default password
                $defaultPassword = 'student123';
                $student->password = Hash::make($defaultPassword);
                $student->decrypt_pw = $defaultPassword;

                // Optional fields
                if (!empty($row['admission_date'])) {
                    try {
                        $student->admission_date = Carbon::createFromFormat('Y-m-d', $row['admission_date'])->format('Y-m-d');
                    } catch (\Exception $e) {
                        // Skip invalid admission date
                    }
                }

                $student->admission_number = $row['admission_number'] ?? null;
                $student->roll_number = $row['roll_number'] ?? null;
                $student->religion = $row['religion'] ?? null;
                $student->blood_group = $row['blood_group'] ?? null;
                $student->father_name = $row['father_name'] ?? null;
                $student->mother_name = $row['mother_name'] ?? null;

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

        // CSV Headers
        $csvData[] = [
            'Student ID',
            'First Name',
            'Last Name',
            'Middle Name',
            'Email',
            'Phone',
            'Date of Birth',
            'Address',
            'Permanent Address',
            'Pincode',
            'Gender',
            'Caste/Tribe',
            'District',
            'Admission Date',
            'Admission Number',
            'Roll Number',
            'Religion',
            'Blood Group',
            'Father Name',
            'Mother Name',
            'Class',
            'Section',
            'Teacher',
            'Status',
            'Institution Code'
        ];

        // Add student data
        foreach ($students as $student) {
            $csvData[] = [
                $student->id,
                $student->first_name,
                $student->last_name,
                $student->middle_name ?? '',
                $student->email,
                $student->phone ?? '',
                $student->dob ?? '',
                $student->address ?? '',
                $student->permanent_address ?? '',
                $student->pincode ?? '',
                $student->gender ?? '',
                $student->caste_tribe ?? '',
                $student->district ?? '',
                $student->admission_date ?? '',
                $student->admission_number ?? '',
                $student->roll_number ?? '',
                $student->religion ?? '',
                $student->blood_group ?? '',
                $student->father_name ?? '',
                $student->mother_name ?? '',
                $student->schoolClass->name ?? '',
                $student->section->name ?? '',
                $student->teacher ? $student->teacher->first_name . ' ' . $student->teacher->last_name : '',
                $student->status == 1 ? 'Active' : 'Inactive',
                $student->institution_code ?? ''
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
        $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $destinationPath = public_path('admin/uploads/' . $folder);

        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0755, true);
        }

        $file->move($destinationPath, $fileName);

        return 'admin/uploads/' . $folder . '/' . $fileName;
    }
}

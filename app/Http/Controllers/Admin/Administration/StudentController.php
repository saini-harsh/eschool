<?php

namespace App\Http\Controllers\Admin\Administration;

use App\Http\Controllers\Controller;
use App\Models\Institution;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Section;
use App\Models\SchoolClass;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class StudentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
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
        $students = Student::all();
        return view('admin.administration.students.index',compact('students'));
    }
    public function Create(){
        $institutions = Institution::all();
        $classes = SchoolClass::all(['id','name','institution_id','section_ids']);
        $sections = collect(); // Start with empty sections

        return view('admin.administration.students.create',compact('institutions','classes','sections'));
    }

    // Method to get sections by class ID
    public function getSectionsByClass($classId)
    {
        try {
            $class = SchoolClass::find($classId);
            if (!$class) {
                return response()->json(['sections' => []]);
            }

            // Ensure section_ids is properly handled as an array
            $sectionIds = $class->section_ids ?? [];
            
            // If section_ids is a string (JSON), decode it
            if (is_string($sectionIds)) {
                $sectionIds = json_decode($sectionIds, true) ?? [];
            }
            
            // Ensure it's an array and not empty
            if (!is_array($sectionIds) || empty($sectionIds)) {
                return response()->json(['sections' => []]);
            }
            
            $sections = Section::whereIn('id', $sectionIds)->get(['id', 'name']);
            
            return response()->json(['sections' => $sections]);
        } catch (\Exception $e) {
            \Log::error('Error in getSectionsByClass: ' . $e->getMessage());
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
            'institution_id' => 'required|exists:institutions,id',
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
        $student->institution_code = 'INS' . str_pad($request->institution_id, 3, '0', STR_PAD_LEFT);
        $student->teacher_id     = $request->teacher_id;
        $student->institution_id = $request->institution_id;
        $student->class_id       = $request->class_id;
        $student->section_id     = $request->section_id;
        $student->status         = 1;
        $student->admin_id       = auth()->id();
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

        return redirect()->route('admin.students.index')->with('success', 'Student added successfully!');

    }
    public function Edit(Student $student)
    {
        $institutions = Institution::all();
        $classes = SchoolClass::where('institution_id', $student->institution_id)
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
        
        $teachers = Teacher::where('institution_id', $student->institution_id)->get(['id','first_name','last_name']);

        return view('admin.administration.students.edit', compact('student', 'institutions','classes','sections','teachers'));
    }

    public function Show(Student $student)
    {
        return view('admin.administration.students.show', compact('student'));
    }

    public function Update(Request $request, Student $student)
    {
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
            'institution_id' => 'required|exists:institutions,id',
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
        $student->institution_id = $request->institution_id;
        $student->institution_code = 'INS' . str_pad($request->institution_id, 3, '0', STR_PAD_LEFT);
        $student->class_id       = $request->class_id;
        $student->section_id     = $request->section_id;
        $student->admin_id       = auth()->id();

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

        return redirect()->route('admin.students.index')->with('success', 'Student updated successfully!');
    }
    public function Delete($id)
    {
        $student = Student::findOrFail($id);
        $student->delete();

        return redirect()->route('admin.students.index')->with('success', 'Student deleted successfully!');
    }

    /**
     * Update student status
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:0,1'
        ]);

        $student = Student::findOrFail($id);
        $student->status = $request->status;
        $student->save();

        return response()->json(['success' => true, 'message' => 'Status updated successfully']);
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

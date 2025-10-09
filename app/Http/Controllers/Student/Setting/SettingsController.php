<?php

namespace App\Http\Controllers\Student\Setting;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class SettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:student');
    }

    /**
     * Display the settings page
     */
    public function index()
    {
        $student = Auth::guard('student')->user();
        return view('student.settings.index', compact('student'));
    }

    /**
     * Update profile settings
     */
    public function updateProfile(Request $request)
    {
        $student = Auth::guard('student')->user();

        $validator = Validator::make($request->all(), [
            // Personal Information
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:students,email,' . $student->id,
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'permanent_address' => 'nullable|string|max:255',
            'pincode' => 'required|string|max:10',
            'district' => 'required|string|max:255',
            'dob' => 'required|string',
            'gender' => 'required|string|in:Male,Female,Other',
            'religion' => 'nullable|string|max:50',
            'caste_tribe' => 'nullable|string|max:255',
            'category' => 'nullable|string|max:50',
            'blood_group' => 'nullable|string|max:10',
            'height' => 'nullable|string|max:10',
            'weight' => 'nullable|string|max:10',
            
            // Academic Information
            'admission_date' => 'required|string',
            'admission_number' => 'nullable|string|max:50',
            'roll_number' => 'nullable|string|max:50',
            'group' => 'nullable|string|max:50',
            
            // Parent Information
            'father_name' => 'nullable|string|max:255',
            'father_phone' => 'nullable|string|max:20',
            'father_occupation' => 'nullable|string|max:255',
            'mother_name' => 'nullable|string|max:255',
            'mother_phone' => 'nullable|string|max:20',
            'mother_occupation' => 'nullable|string|max:255',
            
            // Guardian Information
            'guardian_name' => 'nullable|string|max:255',
            'guardian_phone' => 'nullable|string|max:20',
            'guardian_email' => 'nullable|email|max:255',
            'guardian_occupation' => 'nullable|string|max:255',
            'guardian_relation' => 'nullable|string|max:50',
            'guardian_relation_text' => 'nullable|string|max:50',
            'guardian_address' => 'nullable|string|max:255',
            
            // Document Information
            'national_id' => 'nullable|string|max:50',
            'birth_certificate_number' => 'nullable|string|max:50',
            'bank_name' => 'nullable|string|max:255',
            'bank_account_number' => 'nullable|string|max:50',
            'ifsc_code' => 'nullable|string|max:20',
            'additional_notes' => 'nullable|string|max:1000',
            'document_01_title' => 'nullable|string|max:255',
            'document_02_title' => 'nullable|string|max:255',
            'document_03_title' => 'nullable|string|max:255',
            'document_04_title' => 'nullable|string|max:255',
            
            // File uploads
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'father_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'mother_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'guardian_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'document_01_file' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
            'document_02_file' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
            'document_03_file' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
            'document_04_file' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Handle photo upload
            if ($request->hasFile('photo')) {
                if ($student->photo && File::exists(public_path($student->photo))) {
                    File::delete(public_path($student->photo));
                }
                $file = $request->file('photo');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $uploadPath = public_path('student/uploads/students');
                if (!File::exists($uploadPath)) {
                    File::makeDirectory($uploadPath, 0755, true);
                }
                $file->move($uploadPath, $fileName);
                $student->photo = 'student/uploads/students/' . $fileName;
            }

            // Handle father photo upload
            if ($request->hasFile('father_photo')) {
                if ($student->father_photo && File::exists(public_path($student->father_photo))) {
                    File::delete(public_path($student->father_photo));
                }
                $file = $request->file('father_photo');
                $fileName = time() . '_father_' . $file->getClientOriginalName();
                $uploadPath = public_path('student/uploads/parents');
                if (!File::exists($uploadPath)) {
                    File::makeDirectory($uploadPath, 0755, true);
                }
                $file->move($uploadPath, $fileName);
                $student->father_photo = 'student/uploads/parents/' . $fileName;
            }

            // Handle mother photo upload
            if ($request->hasFile('mother_photo')) {
                if ($student->mother_photo && File::exists(public_path($student->mother_photo))) {
                    File::delete(public_path($student->mother_photo));
                }
                $file = $request->file('mother_photo');
                $fileName = time() . '_mother_' . $file->getClientOriginalName();
                $uploadPath = public_path('student/uploads/parents');
                if (!File::exists($uploadPath)) {
                    File::makeDirectory($uploadPath, 0755, true);
                }
                $file->move($uploadPath, $fileName);
                $student->mother_photo = 'student/uploads/parents/' . $fileName;
            }

            // Handle guardian photo upload
            if ($request->hasFile('guardian_photo')) {
                if ($student->guardian_photo && File::exists(public_path($student->guardian_photo))) {
                    File::delete(public_path($student->guardian_photo));
                }
                $file = $request->file('guardian_photo');
                $fileName = time() . '_guardian_' . $file->getClientOriginalName();
                $uploadPath = public_path('student/uploads/guardians');
                if (!File::exists($uploadPath)) {
                    File::makeDirectory($uploadPath, 0755, true);
                }
                $file->move($uploadPath, $fileName);
                $student->guardian_photo = 'student/uploads/guardians/' . $fileName;
            }

            // Handle document uploads
            $documentFields = ['document_01_file', 'document_02_file', 'document_03_file', 'document_04_file'];
            foreach ($documentFields as $field) {
                if ($request->hasFile($field)) {
                    $oldFile = $student->$field;
                    if ($oldFile && File::exists(public_path($oldFile))) {
                        File::delete(public_path($oldFile));
                    }
                    $file = $request->file($field);
                    $fileName = time() . '_' . $field . '_' . $file->getClientOriginalName();
                    $uploadPath = public_path('student/uploads/documents');
                    if (!File::exists($uploadPath)) {
                        File::makeDirectory($uploadPath, 0755, true);
                    }
                    $file->move($uploadPath, $fileName);
                    $student->$field = 'student/uploads/documents/' . $fileName;
                }
            }

            // Update all fields
            $student->first_name = $request->first_name;
            $student->middle_name = $request->middle_name;
            $student->last_name = $request->last_name;
            $student->email = $request->email;
            $student->phone = $request->phone;
            $student->address = $request->address;
            $student->permanent_address = $request->permanent_address;
            $student->pincode = $request->pincode;
            $student->district = $request->district;
            $student->dob = Carbon::parse($request->dob)->format('Y-m-d');
            $student->gender = $request->gender;
            $student->religion = $request->religion;
            $student->caste_tribe = $request->caste_tribe;
            $student->category = $request->category;
            $student->blood_group = $request->blood_group;
            $student->height = $request->height;
            $student->weight = $request->weight;
            
            // Academic Information
            $student->admission_date = Carbon::parse($request->admission_date)->format('Y-m-d');
            $student->admission_number = $request->admission_number;
            $student->roll_number = $request->roll_number;
            $student->group = $request->group;
            
            // Parent Information
            $student->father_name = $request->father_name;
            $student->father_phone = $request->father_phone;
            $student->father_occupation = $request->father_occupation;
            $student->mother_name = $request->mother_name;
            $student->mother_phone = $request->mother_phone;
            $student->mother_occupation = $request->mother_occupation;
            
            // Guardian Information
            $student->guardian_name = $request->guardian_name;
            $student->guardian_phone = $request->guardian_phone;
            $student->guardian_email = $request->guardian_email;
            $student->guardian_occupation = $request->guardian_occupation;
            $student->guardian_relation = $request->guardian_relation;
            $student->guardian_relation_text = $request->guardian_relation_text;
            $student->guardian_address = $request->guardian_address;
            
            // Document Information
            $student->national_id = $request->national_id;
            $student->birth_certificate_number = $request->birth_certificate_number;
            $student->bank_name = $request->bank_name;
            $student->bank_account_number = $request->bank_account_number;
            $student->ifsc_code = $request->ifsc_code;
            $student->additional_notes = $request->additional_notes;
            $student->document_01_title = $request->document_01_title;
            $student->document_02_title = $request->document_02_title;
            $student->document_03_title = $request->document_03_title;
            $student->document_04_title = $request->document_04_title;

            $student->save();

            return response()->json([
                'success' => true,
                'message' => 'Profile updated successfully',
                'data' => $student
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating profile: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Change password
     */
    public function changePassword(Request $request)
    {
        $student = Auth::guard('student')->user();

        $validator = Validator::make($request->all(), [
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            if (!Hash::check($request->current_password, $student->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Current password is incorrect'
                ], 400);
            }

            $student->password = Hash::make($request->new_password);
            $student->decrypt_pw = $request->new_password;
            $student->save();

            return response()->json([
                'success' => true,
                'message' => 'Password changed successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while changing password: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete profile image
     */
    public function deleteProfileImage()
    {
        try {
            $student = Auth::guard('student')->user();

            if ($student->photo && File::exists(public_path($student->photo))) {
                File::delete(public_path($student->photo));
                $student->photo = null;
                $student->save();
            }

            return response()->json([
                'success' => true,
                'message' => 'Profile image deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while deleting profile image: ' . $e->getMessage()
            ], 500);
        }
    }

}
<?php

namespace App\Http\Controllers\API\Student;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student; 
use App\Models\Teacher;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\URL; // make sure this is at the top
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class LoginController extends Controller
{
    public function ForgotPassword(Request $request)
    {

        // Check if teacher exists
        $student = Student::where('email', $request->email)->first();

        if (!$student) {
            return response()->json([
                'success' => false,
                'message' => 'No student found with this email',
                'data' => []
            ], 404);
        }

        // Generate unique token
        $token = Str::random(60);

        // Store token in password_resets table
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $student->email],
            [
                'token' => $token,
                'created_at' => now()
            ]
        );

        // Send email with token (optional)
        // You can use Mail::to($teacher->email)->send(new ResetPasswordMail($token));
        // For now we just return token in response for testing

        return response()->json([
            'success' => true,
            'message' => 'Password reset token generated',
            'data' => [
                'Token' => $token,
            ]
        ]);
    }
    public function ResetPassword(Request $request)
    {
       
        

        // Check token in password_resets table
        $reset = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->where('token', $request->token)
            ->first();

        if (!$reset) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid token or email',
                'data' => []
            ], 400);
        }

        // Find student
        $student = Student::where('email', $request->email)->first();

        if (!$student) {
            return response()->json([
                'success' => false,
                'message' => 'Student not found',
                'data' => []
            ], 404);
        }

        // Update password
        $student->password = Hash::make($request->new_password);
        $student->decrypt_pw = $request->new_password;
        $student->save();

        // Delete used token
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Password has been reset successfully',
            'data' => []
        ]);
    }
    public function ChangePassword(Request $request)
    {
        
        $student = Student::where('email', $request->email)->first();
        
        if (!$student) {
            return response()->json([
                'success' => false,
                'message' => 'No student found with this email',
                'data' => []
            ], 404);
        }
        
        // Check current password
        if (!Hash::check($request->current_password, $student->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Current password is incorrect',
                'data' => []
            ], 401);
        }

        // Update password
        $student->password = Hash::make($request->new_password);
        $student->decrypt_pw = $request->new_password;
        $student->save();

        return response()->json([
            'success' => true,
            'message' => 'Password changed successfully',
            'data' => []
        ]);
    }
    public function UpdateProfile(Request $request)
    {
        $student = Student::where('email', $request->email)->first();

        if (!$student) {
            return response()->json([
                'success' => false,
                'message' => 'No student found with this email',
                'data' => []
            ], 404);
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

            // Handle Aadhaar card uploads
            if ($request->hasFile('aadhaar_front')) {
                if ($student->aadhaar_front && File::exists(public_path($student->aadhaar_front))) {
                    File::delete(public_path($student->aadhaar_front));
                }
                $file = $request->file('aadhaar_front');
                $fileName = time() . '_aadhaar_front_' . $file->getClientOriginalName();
                $uploadPath = public_path('student/uploads/documents');
                if (!File::exists($uploadPath)) {
                    File::makeDirectory($uploadPath, 0755, true);
                }
                $file->move($uploadPath, $fileName);
                $student->aadhaar_front = 'student/uploads/documents/' . $fileName;
            }

            if ($request->hasFile('aadhaar_back')) {
                if ($student->aadhaar_back && File::exists(public_path($student->aadhaar_back))) {
                    File::delete(public_path($student->aadhaar_back));
                }
                $file = $request->file('aadhaar_back');
                $fileName = time() . '_aadhaar_back_' . $file->getClientOriginalName();
                $uploadPath = public_path('student/uploads/documents');
                if (!File::exists($uploadPath)) {
                    File::makeDirectory($uploadPath, 0755, true);
                }
                $file->move($uploadPath, $fileName);
                $student->aadhaar_back = 'student/uploads/documents/' . $fileName;
            }

            // Handle PAN card uploads
            if ($request->hasFile('pan_front')) {
                if ($student->pan_front && File::exists(public_path($student->pan_front))) {
                    File::delete(public_path($student->pan_front));
                }
                $file = $request->file('pan_front');
                $fileName = time() . '_pan_front_' . $file->getClientOriginalName();
                $uploadPath = public_path('student/uploads/documents');
                if (!File::exists($uploadPath)) {
                    File::makeDirectory($uploadPath, 0755, true);
                }
                $file->move($uploadPath, $fileName);
                $student->pan_front = 'student/uploads/documents/' . $fileName;
            }

            if ($request->hasFile('pan_back')) {
                if ($student->pan_back && File::exists(public_path($student->pan_back))) {
                    File::delete(public_path($student->pan_back));
                }
                $file = $request->file('pan_back');
                $fileName = time() . '_pan_back_' . $file->getClientOriginalName();
                $uploadPath = public_path('student/uploads/documents');
                if (!File::exists($uploadPath)) {
                    File::makeDirectory($uploadPath, 0755, true);
                }
                $file->move($uploadPath, $fileName);
                $student->pan_back = 'student/uploads/documents/' . $fileName;
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
            $student->aadhaar_no = $request->aadhaar_no;
            $student->pan_no = $request->pan_no;
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

            $student->save();

            $student->photo = $student->photo ? URL::to($student->photo) : null;
            $student->father_photo = $student->father_photo ? URL::to($student->father_photo) : null;
            $student->mother_photo = $student->mother_photo ? URL::to($student->mother_photo) : null;
            $student->guardian_photo = $student->guardian_photo ? URL::to($student->guardian_photo) : null;
            $student->aadhaar_front = $student->aadhaar_front ? URL::to($student->aadhaar_front) : null;
            $student->aadhaar_back = $student->aadhaar_back ? URL::to($student->aadhaar_back) : null;
            $student->pan_front = $student->pan_front ? URL::to($student->pan_front) : null;
            $student->pan_back = $student->pan_back ? URL::to($student->pan_back) : null;
            $student->document_01_file = $student->document_01_file ? URL::to($student->document_01_file) : null;
            $student->document_02_file = $student->document_02_file ? URL::to($student->document_02_file) : null;
            $student->document_03_file = $student->document_03_file ? URL::to($student->document_03_file) : null;
            $student->document_04_file = $student->document_04_file ? URL::to($student->document_04_file) : null;
            $student->profile_image = $student->profile_image ? URL::to($student->profile_image) : null;

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
    public function GetProfile(Request $request)
    {
        try {
            $student = Student::where('email', $request->email)->first();

            if (!$student) {
                return response()->json([
                    'success' => false,
                    'message' => 'No student found with this email',
                    'data' => []
                ], 404);
            }
            $student->photo = $student->photo ? URL::to($student->photo) : null;
            $student->father_photo = $student->father_photo ? URL::to($student->father_photo) : null;
            $student->mother_photo = $student->mother_photo ? URL::to($student->mother_photo) : null;
            $student->guardian_photo = $student->guardian_photo ? URL::to($student->guardian_photo) : null;
            $student->aadhaar_front = $student->aadhaar_front ? URL::to($student->aadhaar_front) : null;
            $student->aadhaar_back = $student->aadhaar_back ? URL::to($student->aadhaar_back) : null;
            $student->pan_front = $student->pan_front ? URL::to($student->pan_front) : null;
            $student->pan_back = $student->pan_back ? URL::to($student->pan_back) : null;
            $student->document_01_file = $student->document_01_file ? URL::to($student->document_01_file) : null;
            $student->document_02_file = $student->document_02_file ? URL::to($student->document_02_file) : null;
            $student->document_03_file = $student->document_03_file ? URL::to($student->document_03_file) : null;
            $student->document_04_file = $student->document_04_file ? URL::to($student->document_04_file) : null;
            $student->profile_image = $student->profile_image ? URL::to($student->profile_image) : null;
            return response()->json([
                'success' => true,
                'message' => 'Profile fetched successfully',
                'data' => [
                    'Student' => $student
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage()
            ], 500);
        }
    }
}

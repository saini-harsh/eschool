<?php

namespace App\Http\Controllers\API\Teacher;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Teacher;
use App\Models\Student;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\URL; // make sure this is at the top
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        // Validate input
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);
        if($request->type == 'teacher'){
            // Find teacher by email
            $teacher = Teacher::where('email', $request->email)->first();

        } else{
            // Find student by email
            $teacher = Student::where('email', $request->email)->first();
        }

        // Check if teacher exists and password is correct
        if (!$teacher || !Hash::check($request->password, $teacher->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid email or password',
                'data' => []
            ], 401);
        }

        // Check if teacher is active
        if ($teacher->status == 0) {
            return response()->json([
                'success' => false,
                'message' => 'Your account is inactive. Please contact Insitution.',
                'data' => []
            ], 403);
        }

        // Optional: Create token if using Sanctum
        $token = Str::random(60);

         if($request->type == 'teacher'){

            $teacher->profile_image = $teacher->profile_image ? URL::to($teacher->profile_image) : null;

        } else{
            // Find student by email
            $teacher->photo = $teacher->photo ? URL::to($teacher->photo) : null;
            $teacher->father_photo = $teacher->father_photo ? URL::to($teacher->father_photo) : null;
            $teacher->mother_photo = $teacher->mother_photo ? URL::to($teacher->mother_photo) : null;
            $teacher->guardian_photo = $teacher->guardian_photo ? URL::to($teacher->guardian_photo) : null;
            $teacher->aadhaar_front = $teacher->aadhaar_front ? URL::to($teacher->aadhaar_front) : null;
            $teacher->aadhaar_back = $teacher->aadhaar_back ? URL::to($teacher->aadhaar_back) : null;
            $teacher->pan_front = $teacher->pan_front ? URL::to($teacher->pan_front) : null;
            $teacher->pan_back = $teacher->pan_back ? URL::to($teacher->pan_back) : null;
            $teacher->document_01_file = $teacher->document_01_file ? URL::to($teacher->document_01_file) : null;
            $teacher->document_02_file = $teacher->document_02_file ? URL::to($teacher->document_02_file) : null;
            $teacher->document_03_file = $teacher->document_03_file ? URL::to($teacher->document_03_file) : null;
            $teacher->document_04_file = $teacher->document_04_file ? URL::to($teacher->document_04_file) : null;
            $teacher->profile_image = $teacher->profile_image ? URL::to($teacher->profile_image) : null;
        }
        // Return success response
        return response()->json([
            'success' => true,
            'message' => 'Login successful',
            'data' => [
                'Token' => $token, // uncomment if using tokens
                'Teacher' => $teacher
            ],
        ]);

    }
    public function ForgotPassword(Request $request)
    {
        // Validate input
        $request->validate([
            'email' => 'required|email',
        ]);

        // Check if teacher exists
        $teacher = Teacher::where('email', $request->email)->first();

        if (!$teacher) {
            return response()->json([
                'success' => false,
                'message' => 'No teacher found with this email',
                'data' => []
            ], 404);
        }

        // Generate unique token
        $token = Str::random(60);

        // Store token in password_resets table
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $teacher->email],
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
        // Validate input
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'token' => 'required|string',
            'new_password' => 'required|string|min:6|confirmed', // must match new_password_confirmation
        ]);
        // if ($validator->fails()) {
        //     return response()->json([
        //         'success' => false,
        //         'message' => $validator->errors()->first(),
        //         'data' => []
        //     ], 422);
        // }
        

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

        // Find teacher
        $teacher = Teacher::where('email', $request->email)->first();

        if (!$teacher) {
            return response()->json([
                'success' => false,
                'message' => 'Teacher not found',
                'data' => []
            ], 404);
        }

        // Update password
        $teacher->password = Hash::make($request->new_password);
        $teacher->decrypt_pw = $request->new_password;
        $teacher->save();

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
        
        // Validate input
        $validator = Validator::make($request->all(), [
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:6|confirmed', // matches new_password_confirmation
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
                'data' => []
            ], 422);
        }
        
        $teacher = Teacher::where('email', $request->email)->first();

        if (!$teacher) {
            return response()->json([
                'success' => false,
                'message' => 'No teacher found with this email',
                'data' => []
            ], 404);
        }
        
        // Check current password
        if (!Hash::check($request->current_password, $teacher->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Current password is incorrect',
                'data' => []
            ], 401);
        }

        // Update password
        $teacher->password = Hash::make($request->new_password);
        $teacher->decrypt_pw = $request->new_password;
        $teacher->save();

        return response()->json([
            'success' => true,
            'message' => 'Password changed successfully',
            'data' => []
        ]);
    }
    public function UpdateProfile(Request $request)
    {
        $teacher = Teacher::where('email', $request->email)->first();

        if (!$teacher) {
            return response()->json([
                'success' => false,
                'message' => 'No teacher found with this email',
                'data' => []
            ], 404);
        }
        

        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:teachers,email,' . $teacher->id,
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'pincode' => 'required|string|max:10',
            'dob' => 'required|date',
            'gender' => 'required|string|in:male,female,other',
            'caste_tribe' => 'nullable|string|max:255',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120', // 5MB
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Handle profile image upload
            if ($request->hasFile('profile_image')) {
                // Delete old image if exists
                if ($teacher->profile_image && File::exists(public_path($teacher->profile_image))) {
                    File::delete(public_path($teacher->profile_image));
                }

                $file = $request->file('profile_image');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $uploadPath = public_path('admin/uploads/teachers');

                if (!File::exists($uploadPath)) {
                    File::makeDirectory($uploadPath, 0755, true);
                }

                $file->move($uploadPath, $fileName);
                $teacher->profile_image = 'admin/uploads/teachers/' . $fileName;
            }

            // Update teacher data
            $teacher->first_name = $request->first_name;
            $teacher->middle_name = $request->middle_name;
            $teacher->last_name = $request->last_name;
            $teacher->email = $request->email;
            $teacher->phone = $request->phone;
            $teacher->address = $request->address;
            $teacher->pincode = $request->pincode;
            $teacher->dob = Carbon::parse($request->dob)->format('Y-m-d');
            $teacher->gender = $request->gender;
            $teacher->caste_tribe = $request->caste_tribe;

            $teacher->save();


            $teacher->profile_image = $teacher->profile_image ? URL::to($teacher->profile_image) : null;

            return response()->json([
                'success' => true,
                'message' => 'Profile updated successfully',
                'data' => [
                    'Teacher' => $teacher
                ]
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
            $teacher = Teacher::where('email', $request->email)->first();

            if (!$teacher) {
                return response()->json([
                    'success' => false,
                    'message' => 'No teacher found with this email',
                    'data' => []
                ], 404);
            }
            $teacher->profile_image = $teacher->profile_image ? URL::to($teacher->profile_image) : null;
            return response()->json([
                'success' => true,
                'message' => 'Profile fetched successfully',
                'data' => [
                    'Teacher' => $teacher
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

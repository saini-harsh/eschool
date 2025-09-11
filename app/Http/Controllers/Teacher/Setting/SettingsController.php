<?php

namespace App\Http\Controllers\Teacher\Setting;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class SettingsController extends Controller
{
    /**
     * Display the settings page
     */
    public function index()
    {
        $teacher = Auth::guard('teacher')->user();
        return view('teacher.settings.index', compact('teacher'));
    }

    /**
     * Update profile settings
     */
    public function updateProfile(Request $request)
    {
        $teacher = Auth::guard('teacher')->user();
        
        // Debug: Check if file is being received
        if ($request->hasFile('profile_image')) {
            \Log::info('File received: ' . $request->file('profile_image')->getClientOriginalName());
        } else {
            \Log::info('No file received in request');
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
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120', // 5MB max
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
                // Delete old profile image if exists
                if ($teacher->profile_image && File::exists(public_path($teacher->profile_image))) {
                    File::delete(public_path($teacher->profile_image));
                }

                $file = $request->file('profile_image');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $uploadPath = public_path('admin/uploads/teachers');
                
                // Create directory if it doesn't exist
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

            return response()->json([
                'success' => true,
                'message' => 'Profile updated successfully',
                'data' => $teacher
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
        $teacher = Auth::guard('teacher')->user();
        
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
            // Check current password
            if (!Hash::check($request->current_password, $teacher->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Current password is incorrect'
                ], 400);
            }

            // Update password
            $teacher->password = Hash::make($request->new_password);
            $teacher->decrypt_pw = $request->new_password;
            $teacher->save();

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
            $teacher = Auth::guard('teacher')->user();
            
            if ($teacher->profile_image && File::exists(public_path($teacher->profile_image))) {
                File::delete(public_path($teacher->profile_image));
                $teacher->profile_image = null;
                $teacher->save();
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

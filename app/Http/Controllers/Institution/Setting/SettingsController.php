<?php

namespace App\Http\Controllers\Institution\Setting;

use App\Http\Controllers\Controller;
use App\Models\Institution;
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
        $institution = Auth::guard('institution')->user();
        return view('institution.settings.index', compact('institution'));
    }

    /**
     * Update profile settings
     */
    public function updateProfile(Request $request)
    {
        $institution = Auth::guard('institution')->user();
        
        // Debug: Check if file is being received
        if ($request->hasFile('logo')) {
            \Log::info('File received: ' . $request->file('logo')->getClientOriginalName());
        } else {
            \Log::info('No file received in request');
        }
        
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:institutions,email,' . $institution->id,
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'pincode' => 'required|string|max:10',
            'state' => 'required|string|max:255',
            'district' => 'required|string|max:255',
            'board' => 'required|string|max:255',
            'website' => 'nullable|string|max:255',
            'established_date' => 'required|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120', // 5MB max
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Handle logo upload
            if ($request->hasFile('logo')) {
                // Delete old logo if exists
                if ($institution->logo && File::exists(public_path($institution->logo))) {
                    File::delete(public_path($institution->logo));
                }

                $file = $request->file('logo');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $uploadPath = public_path('institution/uploads/institutions');
                
                // Create directory if it doesn't exist
                if (!File::exists($uploadPath)) {
                    File::makeDirectory($uploadPath, 0755, true);
                }
                
                $file->move($uploadPath, $fileName);
                $institution->logo = 'institution/uploads/institutions/' . $fileName;
            }

            // Update institution data
            $institution->name = $request->name;
            $institution->email = $request->email;
            $institution->phone = $request->phone;
            $institution->address = $request->address;
            $institution->pincode = $request->pincode;
            $institution->state = $request->state;
            $institution->district = $request->district;
            $institution->board = $request->board;
            $institution->website = $request->website;
            $institution->established_date = Carbon::parse($request->established_date)->format('Y-m-d');

            $institution->save();

            return response()->json([
                'success' => true,
                'message' => 'Profile updated successfully',
                'data' => $institution
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
        $institution = Auth::guard('institution')->user();
        
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
            if (!Hash::check($request->current_password, $institution->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Current password is incorrect'
                ], 400);
            }

            // Update password
            $institution->password = Hash::make($request->new_password);
            $institution->decrypt_pw = $request->new_password;
            $institution->save();

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
            $institution = Auth::guard('institution')->user();
            
            if ($institution->logo && File::exists(public_path($institution->logo))) {
                File::delete(public_path($institution->logo));
                $institution->logo = null;
                $institution->save();
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

<?php

namespace App\Http\Controllers\Admin\Setting;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;

class SettingsController extends Controller
{
    /**
     * Display the settings page
     */
    public function index()
    {
        $admin = Admin::find(1); // Assuming admin ID 1 for now
        return view('admin.settings.index', compact('admin'));
    }

    /**
     * Update profile settings
     */
    public function updateProfile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'address_line_1' => 'nullable|string|max:255',
            'address_line_2' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'city' => 'nullable|string|max:100',
            'pin_code' => 'nullable|string|max:20',
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
            $admin = Admin::find(1);
            
            if (!$admin) {
                return response()->json([
                    'success' => false,
                    'message' => 'Admin not found'
                ], 404);
            }

            // Handle logo upload
            if ($request->hasFile('logo')) {
                // Delete old logo if exists
                if ($admin->logo && File::exists(public_path($admin->logo))) {
                    File::delete(public_path($admin->logo));
                }

                $file = $request->file('logo');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $uploadPath = public_path('admin/uploads/admin');
                
                // Create directory if it doesn't exist
                if (!File::exists($uploadPath)) {
                    File::makeDirectory($uploadPath, 0755, true);
                }
                
                $file->move($uploadPath, $fileName);
                $admin->logo = 'admin/uploads/admin/' . $fileName;
            }

            // Update admin data
            $admin->name = $request->name;
            $admin->email = $request->email;
            $admin->phone = $request->phone;
            $admin->address_line_1 = $request->address_line_1;
            $admin->address_line_2 = $request->address_line_2;
            $admin->country = $request->country;
            $admin->state = $request->state;
            $admin->city = $request->city;
            $admin->pin_code = $request->pin_code;

            $admin->save();

            return response()->json([
                'success' => true,
                'message' => 'Profile updated successfully',
                'data' => $admin
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
        $validator = Validator::make($request->all(), [
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $admin = Admin::find(1);
            
            if (!$admin) {
                return response()->json([
                    'success' => false,
                    'message' => 'Admin not found'
                ], 404);
            }

            // Check current password
            if (!Hash::check($request->current_password, $admin->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Current password is incorrect'
                ], 400);
            }

            // Update password
            $admin->password = Hash::make($request->new_password);
            $admin->save();

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
            $admin = Admin::find(1);
            
            if (!$admin) {
                return response()->json([
                    'success' => false,
                    'message' => 'Admin not found'
                ], 404);
            }

            if ($admin->logo && File::exists(public_path($admin->logo))) {
                File::delete(public_path($admin->logo));
                $admin->logo = null;
                $admin->save();
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

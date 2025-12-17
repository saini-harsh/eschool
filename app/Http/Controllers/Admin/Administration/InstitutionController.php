<?php

namespace App\Http\Controllers\Admin\Administration;

use App\Http\Controllers\Controller;
use App\Models\Institution;
use App\Helpers\PermissionHelper;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class InstitutionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function Index(Request $request){
        $query = Institution::query();

        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        if ($request->filled('email')) {
            $query->where('email', 'like', '%' . $request->email . '%');
        }

        $institutions = $query->get();

        $allInstitutionNames = Institution::select('name')
            ->distinct()
            ->orderBy('name')
            ->pluck('name');

        return view('admin.administration.institutions.index', compact('institutions', 'allInstitutionNames'));
    }
    public function Create(){
        return view('admin.administration.institutions.create');
    }
    public function Store(Request $request)
    {
        $request->validate([
            'name'             => 'required|string|max:255',
            'logo'             => 'nullable|image|mimes:jpg,jpeg,png',
            'address'          => 'required|string|max:255',
            'pincode'          => 'required|string|max:10',
            'established_date' => 'required|string',
            'board'            => 'required|string|max:255',
            'state'            => 'required|string|max:255',
            'district'         => 'required|string|max:255',
            'email'            => 'required|email|unique:institutions,email',
            'website'          => 'nullable|string|max:255',
            'phone'            => 'required|string|max:20',
            'password'         => 'required|string|min:6',
            'status'           => 'nullable|boolean',
        ]);

        // Handle logo upload
        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            
            // Create admin-institution-specific path
            $institutionFolder = $this->sanitizeInstitutionName($request->name);
            $destinationPath = public_path('admin/' . $institutionFolder . '/institutions');

            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }

            $file->move($destinationPath, $fileName);
            $logoPath = 'admin/' . $institutionFolder . '/institutions/' . $fileName;
        } else {
            $logoPath = null;
        }
        // Generate institution_code in the format: ENV/25/001 (auto-incremented last part for the current year)
        $currentYear = now()->format('y');
        $prefix = "ENV/{$currentYear}/";

        // Get the max code for this year
        $lastInstitution = Institution::where('code', 'like', $prefix . '%')
            ->orderBy('code', 'desc')
            ->first();

        // If no institutions exist for this year, start with 001
        if ($lastInstitution && preg_match('/\/(\d{3})$/', $lastInstitution->code, $matches)) {
            $lastNumber = (int)$matches[1];
            $nextNumber = $lastNumber + 1;
        } else {
            // First institution for this year gets 001
            $nextNumber = 1;
        }

        $institution_code = $prefix . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

        $institution = new Institution();
        $institution->name             = $request->name;
        $institution->code = $institution_code;
        $institution->logo             = $logoPath;
        $institution->address          = $request->address;
        $institution->pincode          = $request->pincode;
        $institution->established_date = Carbon::parse($request->established_date)->format('Y-m-d');
        $institution->board            = $request->board;
        $institution->state            = $request->state;
        $institution->district         = $request->district;
        $institution->email            = $request->email;
        $institution->website          = $request->website;
        $institution->phone            = $request->phone;
        $institution->admin_id         = 1;
        $institution->password         = Hash::make($request->password);
        $institution->decrypt_pw       = $request->password;
        $institution->status           = $request->status ?? 1;
        $institution->created_at       = now();
        $institution->updated_at       = now();
        $institution->save();

        return redirect()->route('admin.institutions.index')->with('success', 'Institution added successfully!');
    }
    public function Edit(Institution $institution)
    {

        return view('admin.administration.institutions.edit', compact('institution'));
    }
    public function Update(Request $request, Institution $institution)
    {

        $request->validate([
            'name'             => 'required|string|max:255',
            'profile_image'             => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
            'address'          => 'required|string|max:255',
            'pincode'          => 'required|string|max:10',
            'established_date' => 'required|string',
            'board'            => 'required|string|max:255',
            'state'            => 'required|string|max:255',
            'district'         => 'required|string|max:255',
            'email'            => 'required|email|unique:institutions,email,' . $institution->id,
            'website'          => 'nullable|string|max:255',
            'phone'            => 'required|string|max:20',
            'password'         => 'nullable|string|min:6', // nullable for edit
        ]);
        // Handle logo upload
        if ($request->hasFile('profile_image')) {
            $file = $request->file('profile_image');
            $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            
            // Create admin-institution-specific path
            $institutionFolder = $this->sanitizeInstitutionName($request->name);
            $destinationPath = public_path('admin/' . $institutionFolder . '/institutions');

            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }

            $file->move($destinationPath, $fileName);
            $logoPath = 'admin/' . $institutionFolder . '/institutions/' . $fileName;
            
            // delete old logo if exists
            if ($institution->logo && file_exists(public_path($institution->logo))) {
                unlink(public_path($institution->logo));
            }

            $institution->logo = $logoPath;
        }


        $institution->name             = $request->name;
        $institution->address          = $request->address;
        $institution->pincode          = $request->pincode;
        $institution->established_date = Carbon::createFromFormat('d M, Y', $request->established_date)->format('Y-m-d');
        $institution->board            = $request->board;
        $institution->state            = $request->state;
        $institution->district         = $request->district;
        $institution->email            = $request->email;
        $institution->website          = $request->website;
        $institution->phone            = $request->phone;
        $institution->status           = 1;

        // Update password only if entered
        if ($request->filled('password')) {
            $institution->password   = Hash::make($request->password);
            $institution->decrypt_pw = $request->password;
        }

        $institution->save();

        return redirect()->route('admin.institutions.index')->with('success', 'Institution updated successfully!');
    }

    public function updateStatus(Request $request, $id)
    {
        try {
            $institution = Institution::findOrFail($id);
            $institution->status = $request->status;
            $institution->save();

            return response()->json([
                'success' => true,
                'message' => 'Institution status updated successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating institution status'
            ], 500);
        }
    }

    public function Delete($id)
    {
        $institution = Institution::findOrFail($id);
        $institution->delete();

        return redirect()->route('admin.institutions.index')->with('success', 'Institution deleted successfully!');
    }

    public function managePermissions($id)
    {
        $institution = Institution::findOrFail($id);
        $groupedPermissions = PermissionHelper::getGroupedPermissions();
        
        return view('admin.administration.institutions.permissions', compact('institution', 'groupedPermissions'));
    }

    public function updatePermissions(Request $request, $id)
    {
        $institution = Institution::findOrFail($id);
        
        $permissions = $request->input('permissions', []);
        
        // If no permissions selected, set to null to allow all access
        $institution->permissions = empty($permissions) ? null : $permissions;
        $institution->save();

        return redirect()->route('admin.institutions.index')->with('success', 'Permissions updated successfully!');
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
}

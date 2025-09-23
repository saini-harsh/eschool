<?php

namespace App\Http\Controllers\Admin\Administration;

use App\Http\Controllers\Controller;
use App\Models\Institution;
use App\Models\Teacher;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class TeacherController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function Index(){
        $teachers = Teacher::all();
        return view('admin.administration.teachers.index',compact('teachers'));
    }
    
    public function Show(Teacher $teacher)
    {
        $teacher->load(['institution', 'admin']);
        return view('admin.administration.teachers.show', compact('teacher'));
    }
    public function Create(){
        $institutions = Institution::all();
        return view('admin.administration.teachers.create',compact('institutions'));
    }
    public function Store(Request $request)
    {
        $request->validate([
            'first_name'      => 'required|string|max:255',
            'middle_name'     => 'nullable|string|max:255',
            'last_name'       => 'required|string|max:255',
            'email'           => 'required|email|unique:teachers,email',
            'phone'           => 'required|string|max:20',
            'dob'             => 'required|string',
            'address'         => 'required|string|max:255',
            'pincode'         => 'required|string|max:10',
            'gender'          => 'required|in:Male,Female,Other',
            'caste_tribe'     => 'nullable|string|max:255',
            'institution_id'  => 'required|exists:institutions,id',
            'password'        => 'required|string|min:6',
            'profile_image'   => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
        ]);

        if ($request->hasFile('profile_image')) {
            $file = $request->file('profile_image');

            
            $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            
            $destinationPath = public_path('admin/uploads/teachers');
            
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }
            
            $file->move($destinationPath, $fileName);
            
            $photoPath = 'admin/uploads/teachers/' . $fileName;
        } else {
            $photoPath = null;
        }

        $institution = Institution::find($request->institution_id);

        $teacher = new Teacher();
        $teacher->first_name       = $request->first_name;
        $teacher->middle_name      = $request->middle_name;
        $teacher->last_name        = $request->last_name;
        $teacher->profile_image            = $photoPath;
        $teacher->email            = $request->email;
        $teacher->phone            = $request->phone;
        $teacher->dob              = Carbon::parse($request->dob)->format('Y-m-d');
        $teacher->address          = $request->address;
        $teacher->pincode          = $request->pincode;
        $teacher->caste_tribe      = $request->caste_tribe;
        $teacher->gender           = $request->gender;
        $teacher->institution_id   = $request->institution_id;
        $teacher->status           = 1;
        $teacher->employee_id = $this->generateEmployeeId($institution->id);
        $teacher->institution_code = 'INS' . str_pad($institution->id, 3, '0', STR_PAD_LEFT);
        $teacher->admin_id         = auth('admin')->id();
        $teacher->password         = Hash::make($request->password);
        $teacher->decrypt_pw       = $request->password;


        $teacher->save();

        if ($request->has('send_invite')) {
            // Send email or notification logic here
        }

        return redirect()->route('admin.teachers.index')->with('success', 'Teacher added successfully!');

    }
    public function Edit(Teacher $teacher)
    {
        $institutions = Institution::all();

        return view('admin.administration.teachers.edit', compact('teacher', 'institutions'));
    }
    public function Update(Request $request, Teacher $teacher)
    {
        // dd($request->all());
        $request->validate([
            'first_name'      => 'required|string|max:255',
            'middle_name'     => 'nullable|string|max:255',
            'last_name'       => 'required|string|max:255',
            'email'           => 'required|email|unique:teachers,email,' . $teacher->id,
            'phone'           => 'required|string|max:20',
            'dob'             => 'required|date',
            'address'         => 'required|string|max:255',
            'pincode'         => 'required|string|max:10',
            'gender'          => 'required|in:Male,Female,Other',
            'caste_tribe'     => 'nullable|string|max:255',
            'institution_id'  => 'required|exists:institutions,id',
            'password'        => 'nullable|string|min:6',
            'profile_image'   => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
            'status'          => 'required|boolean',
        ]);
        
        
        if ($request->hasFile('profile_image')) {
            $file = $request->file('profile_image');
            $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $destinationPath = public_path('admin/uploads/teachers');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }
            $file->move($destinationPath, $fileName);
            $teacher->profile_image = 'admin/uploads/teachers/' . $fileName;
        }

        $institution = Institution::find($request->institution_id);
        
        $teacher->first_name       = $request->first_name;
        $teacher->middle_name      = $request->middle_name;
        $teacher->last_name        = $request->last_name;
        $teacher->email            = $request->email;
        $teacher->phone            = $request->phone;
        $teacher->dob              = Carbon::parse($request->dob)->format('Y-m-d');
        $teacher->address          = $request->address;
        $teacher->pincode          = $request->pincode;
        $teacher->caste_tribe      = $request->caste_tribe;
        $teacher->gender           = $request->gender;
        $teacher->institution_id   = $request->institution_id;
        $teacher->institution_code = 'INS' . str_pad($institution->id, 3, '0', STR_PAD_LEFT);
        $teacher->status           = $request->status;
        // Only generate new employee ID if institution changed
        if ($teacher->institution_id != $request->institution_id) {
            $teacher->employee_id = $this->generateEmployeeId($institution->id);
        }
        $teacher->admin_id         = auth('admin')->id();

        if ($request->filled('password')) {
            $teacher->password   = Hash::make($request->password);
            $teacher->decrypt_pw = $request->password;
        }

        $teacher->save();

        return redirect()->route('admin.teachers.index')->with('success', 'Teacher updated successfully!');
    }
    
    public function updateStatus(Request $request, $id)
    {
        try {
            $teacher = Teacher::findOrFail($id);
            $teacher->status = $request->status;
            $teacher->save();
            
            return response()->json([
                'success' => true,
                'message' => 'Teacher status updated successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating teacher status'
            ], 500);
        }
    }
    
    public function Delete($id)
    {
        $teacher = Teacher::findOrFail($id);
        $teacher->delete();

        return redirect()->route('admin.teachers.index')->with('success', 'Teacher deleted successfully!');
    }

    /**
     * Generate a unique employee ID for the teacher
     */
    private function generateEmployeeId($institutionId)
    {
        // Get the current year
        $currentYear = date('Y');
        
        // Get the count of teachers for this institution
        $teacherCount = Teacher::where('institution_id', $institutionId)->count();
        
        // Generate employee ID: EMP + Year + Institution ID (3 digits) + Teacher Count (3 digits)
        $employeeId = 'EMP' . $currentYear . str_pad($institutionId, 3, '0', STR_PAD_LEFT) . str_pad($teacherCount + 1, 3, '0', STR_PAD_LEFT);
        
        // Check if this employee ID already exists (very unlikely but just in case)
        while (Teacher::where('employee_id', $employeeId)->exists()) {
            $teacherCount++;
            $employeeId = 'EMP' . $currentYear . str_pad($institutionId, 3, '0', STR_PAD_LEFT) . str_pad($teacherCount + 1, 3, '0', STR_PAD_LEFT);
        }
        
        return $employeeId;
    }
}

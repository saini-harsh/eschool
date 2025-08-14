<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Institution;
use App\Models\Student;
use App\Models\Teacher;
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

    public function Index(){
        $students = Student::all();
        return view('admin.administration.students.index',compact('students'));
    }
    public function Create(){
        $institutions = Institution::all();
        $teachers = Teacher::all();
        return view('admin.administration.students.create',compact('institutions','teachers'));
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
            'pincode'        => 'required|string|max:10',
            'gender'         => 'required|in:Male,Female,Other',
            'caste_tribe'    => 'nullable|string|max:255',
            'district'       => 'required|string|max:255',
            'teacher_id'     => 'nullable|exists:teachers,id',
            'institution_id' => 'required|exists:institutions,id',
            'password'       => 'required|string|min:6',
            'photo'          => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
        ]);

        if ($request->hasFile('photo')) {
            $file = $request->file('photo');

            
            $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            
            $destinationPath = public_path('admin/uploads/students');
            
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }
            
            $file->move($destinationPath, $fileName);
            
            $photoPath = 'admin/uploads/students/' . $fileName;
        } else {
            $photoPath = null;
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
        $student->pincode        = $request->pincode;
        $student->gender         = $request->gender;
        $student->caste_tribe    = $request->caste_tribe;
        $student->district       = $request->district;
        $student->institution_code = 'INS' . str_pad($request->institution_id, 3, '0', STR_PAD_LEFT);
        $student->teacher_id     = $request->teacher_id;
        $student->institution_id = $request->institution_id;
        $student->status           = 1;
        $student->admin_id         = auth()->id();
        $student->password       = Hash::make($request->password);
        $student->decrypt_pw     = $request->password;


        $student->save();

        if ($request->has('send_invite')) {
            // Send email or notification logic here
        }

        return redirect()->route('admin.students.index')->with('success', 'Student added successfully!');

    }
    public function Edit(Student $student)
    {
        $institutions = Institution::all();
        $teachers = Teacher::all();

        return view('admin.administration.students.edit', compact('student', 'institutions','teachers'));
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
            'pincode'        => 'required|string|max:10',
            'gender'         => 'required|in:Male,Female,Other',
            'caste_tribe'    => 'nullable|string|max:255',
            'district'       => 'required|string|max:255',
            'teacher_id'     => 'nullable|exists:teachers,id',
            'institution_id' => 'required|exists:institutions,id',
            'password'       => 'nullable|string|min:6',
            'photo'          => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
        ]);
    
        
        
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $destinationPath = public_path('admin/uploads/students');
    
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }
    
            $file->move($destinationPath, $fileName);
            $student->photo = 'admin/uploads/students/' . $fileName;
        }
    
        $student->first_name     = $request->first_name;
        $student->middle_name    = $request->middle_name;
        $student->last_name      = $request->last_name;
        $student->email          = $request->email;
        $student->phone          = $request->phone;
        $student->dob            = Carbon::parse($request->dob)->format('Y-m-d');
        $student->address        = $request->address;
        $student->pincode        = $request->pincode;
        $student->gender         = $request->gender;
        $student->caste_tribe    = $request->caste_tribe;
        $student->district       = $request->district;
        $student->teacher_id     = $request->teacher_id;
        $student->institution_id = $request->institution_id;
        $student->institution_code = 'INS' . str_pad($request->institution_id, 3, '0', STR_PAD_LEFT);
        $student->admin_id       = auth()->id();
    
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
}

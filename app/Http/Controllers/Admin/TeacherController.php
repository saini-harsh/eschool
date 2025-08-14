<?php

namespace App\Http\Controllers\Admin;

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

    public function index(){
        $teachers = Teacher::all();
        return view('admin.Academics.teachers.teachers',compact('teachers'));
    }
    public function AddTeacher(){
        $institutions = Institution::all();
        return view('admin.Academics.teachers.add-teacher',compact('institutions'));
    }
    public function StoreTeacher(Request $request)
    {
        $request->validate([
            'first_name'      => 'required|string|max:255',
            'middle_name'     => 'nullable|string|max:255',
            'last_name'       => 'required|string|max:255',
            'email'           => 'required|email|unique:teachers,email',
            'phone'           => 'required|string|max:20',
            'dob'             => 'required|date',
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
        $teacher->institution_code = 'INS' . str_pad($institution->id, 3, '0', STR_PAD_LEFT);
        $teacher->admin_id         = auth()->id();
        $teacher->password         = Hash::make($request->password);
        $teacher->decrypt_pw       = $request->password;


        $teacher->save();

        if ($request->has('send_invite')) {
            // Send email or notification logic here
        }

        return redirect()->route('admin.teachers')->with('success', 'Teacher added successfully!');

    }
    public function EditTeacher(Teacher $teacher)
    {
        $institutions = Institution::all();

        return view('admin.Academics.teachers.edit-teacher', compact('teacher', 'institutions'));
    }
    public function UpdateTeacher(Request $request, Teacher $teacher)
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
        $teacher->status           = $request->status;
        $teacher->institution_code = 'INS' . str_pad($institution->id, 3, '0', STR_PAD_LEFT);
        $teacher->admin_id         = auth()->id();

        if ($request->filled('password')) {
            $teacher->password   = Hash::make($request->password);
            $teacher->decrypt_pw = $request->password;
        }

        $teacher->save();

        return redirect()->route('admin.teachers')->with('success', 'Teacher updated successfully!');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Institution;
use App\Models\NonWorkingStaff;
use App\Models\Teacher;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class NonWorkingStaffController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function Index(){
        $staff = NonWorkingStaff::all();
        return view('admin.administration.nonworkingstaff.index', compact('staff'));
    }
    public function Create(){
        $institutions = Institution::all();
        return view('admin.administration.nonworkingstaff.create',compact('institutions'));
    }
    public function Store(Request $request)
    {
        $request->validate([
            'first_name'      => 'required|string|max:255',
            'middle_name'     => 'nullable|string|max:255',
            'last_name'       => 'required|string|max:255',
            'email'           => 'required|email|unique:non_working_staff,email',
            'phone'           => 'required|string|max:20',
            'dob'             => 'required|string',
            'address'         => 'required|string|max:255',
            'pincode'         => 'required|string|max:10',
            'gender'          => 'required|in:Male,Female,Other',
            'caste_tribe'     => 'nullable|string|max:255',
            'institution_id'  => 'required|exists:institutions,id',
            'designation'     => 'required|string|max:255',
            'date_of_joining' => 'required|string',
            'password'        => 'required|string|min:6',
            'profile_image'   => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
        ]);

        if ($request->hasFile('profile_image')) {
            $file = $request->file('profile_image');
            $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $destinationPath = public_path('admin/uploads/nonworkingstaff');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }
            $file->move($destinationPath, $fileName);
            $photoPath = 'admin/uploads/nonworkingstaff/' . $fileName;
        } else {
            $photoPath = null;
        }

        $institution = Institution::find($request->institution_id);

        $staff = new NonWorkingStaff();
        $staff->first_name       = $request->first_name;
        $staff->middle_name      = $request->middle_name;
        $staff->last_name        = $request->last_name;
        $staff->profile_image    = $photoPath;
        $staff->email            = $request->email;
        $staff->phone            = $request->phone;
        $staff->dob              = Carbon::parse($request->dob)->format('Y-m-d');
        $staff->address          = $request->address;
        $staff->pincode          = $request->pincode;
        $staff->caste_tribe      = $request->caste_tribe;
        $staff->gender           = $request->gender;
        $staff->designation      = $request->designation;
        $staff->date_of_joining  = Carbon::parse($request->date_of_joining)->format('Y-m-d');
        $staff->institution_id   = $request->institution_id;
        $staff->status           = 1;
        $staff->institution_code = 'INS' . str_pad($institution->id, 3, '0', STR_PAD_LEFT);
        $staff->admin_id         = auth()->id();
        $staff->password         = Hash::make($request->password);
        $staff->decrypt_pw       = $request->password;

        $staff->save();

        if ($request->has('send_invite')) {
            // Send email or notification logic here
        }

        return redirect()->route('admin.nonworkingstaff.index')->with('success', 'Non-working staff added successfully!');

    }
    public function Edit(NonWorkingStaff $nonworkingstaff)
    {
        $institutions = Institution::all();

        return view('admin.administration.nonworkingstaff.edit', compact('nonworkingstaff', 'institutions'));
    }
    public function Update(Request $request, NonWorkingStaff $nonworkingstaff)
    {
        $request->validate([
            'first_name'      => 'required|string|max:255',
            'middle_name'     => 'nullable|string|max:255',
            'last_name'       => 'required|string|max:255',
            'email'           => 'required|email|unique:non_working_staff,email,' . $nonworkingstaff->id,
            'phone'           => 'required|string|max:20',
            'dob'             => 'required|string',
            'address'         => 'required|string|max:255',
            'pincode'         => 'required|string|max:10',
            'gender'          => 'required|in:Male,Female,Other',
            'caste_tribe'     => 'nullable|string|max:255',
            'institution_id'  => 'required|exists:institutions,id',
            'designation'     => 'required|string|max:255',
            'date_of_joining' => 'required|string',
            'password'        => 'nullable|string|min:6',
            'profile_image'   => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
            'status'          => 'required|boolean',
        ]);

        if ($request->hasFile('profile_image')) {
            $file = $request->file('profile_image');
            $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $destinationPath = public_path('admin/uploads/nonworkingstaff');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }
            $file->move($destinationPath, $fileName);
            $nonworkingstaff->profile_image = 'admin/uploads/nonworkingstaff/' . $fileName;
        }

        $institution = Institution::find($request->institution_id);

        $nonworkingstaff->first_name       = $request->first_name;
        $nonworkingstaff->middle_name      = $request->middle_name;
        $nonworkingstaff->last_name        = $request->last_name;
        $nonworkingstaff->email            = $request->email;
        $nonworkingstaff->phone            = $request->phone;
        $nonworkingstaff->dob              = Carbon::parse($request->dob)->format('Y-m-d');
        $nonworkingstaff->address          = $request->address;
        $nonworkingstaff->pincode          = $request->pincode;
        $nonworkingstaff->caste_tribe      = $request->caste_tribe;
        $nonworkingstaff->gender           = $request->gender;
        $nonworkingstaff->designation      = $request->designation;
        $nonworkingstaff->date_of_joining  = Carbon::parse($request->date_of_joining)->format('Y-m-d');
        $nonworkingstaff->institution_id   = $request->institution_id;
        $nonworkingstaff->status           = $request->status;
        $nonworkingstaff->institution_code = 'INS' . str_pad($institution->id, 3, '0', STR_PAD_LEFT);
        $nonworkingstaff->admin_id         = auth()->id();

        if ($request->filled('password')) {
            $nonworkingstaff->password   = Hash::make($request->password);
            $nonworkingstaff->decrypt_pw = $request->password;
        }

        $nonworkingstaff->save();

        return redirect()->route('admin.nonworkingstaff.index')->with('success', 'Non-working staff updated successfully!');
    }
    public function Delete($id)
    {
        $staff = NonWorkingStaff::findOrFail($id);
        $staff->delete();

        return redirect()->route('admin.nonworkingstaff.index')->with('success', 'Non-working staff deleted successfully!');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Institution;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class InstitutionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index(){
        $institutions = Institution::all();
        return view('admin.Academics.institutions.institutions',compact('institutions'));
    }
    public function AddInstitution(){
        return view('admin.Academics.institutions.add-institution');
    }
    public function StoreInstitution(Request $request)
    {
        
        $request->validate([
            'name'             => 'required|string|max:255',
            'logo'             => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
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
            $destinationPath = public_path('admin/uploads/institutions');

            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }

            $file->move($destinationPath, $fileName);
            $logoPath = 'admin/uploads/institutions/' . $fileName;
        } else {
            $logoPath = null;
        }

        $institution = new Institution();
        $institution->name             = $request->name;
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
        $institution->admin_id         = auth()->id();
        $institution->password         = Hash::make($request->password);
        $institution->decrypt_pw       = $request->password;
        $institution->status           = $request->status ?? 1;

        $institution->save();

        return redirect()->route('admin.institutions')->with('success', 'Institution added successfully!');
    }
    public function EditInstitution(Institution $institution)
    {

        return view('admin.Academics.institutions.edit-institution', compact('institution'));
    }
    public function UpdateInstitution(Request $request, Institution $institution)
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
            $destinationPath = public_path('admin/uploads/institutions');
            
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }
            
            $file->move($destinationPath, $fileName);
            
            $logoPath = 'admin/uploads/institutions/' . $fileName;
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

        return redirect()->route('admin.institutions')->with('success', 'Institution updated successfully!');
    }


}

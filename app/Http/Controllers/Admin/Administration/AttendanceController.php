<?php

namespace App\Http\Controllers\Admin\Administration;

use App\Http\Controllers\Controller;
use App\Models\Institution;
use App\Models\Attendance;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        $institutions = Institution::get();

        return view('admin.administration.attendance.attendance', compact('institutions'));
    }

    public function filter(Request $request)
    {
        $role = $request->role;
        $institution = $request->institution;

        $query = Attendance::where('role', $role)
            ->when($institution, function ($q) use ($institution) {
                return $q->where('institution_id', $institution);
            });

        // Eager load based on role
        if ($role === 'student') {
            $query->with('student');
            $query->with('institution');
        } elseif ($role === 'teacher') {
            $query->with('teacher');
            $query->with('institution');
        } elseif ($role === 'staff') {
            $query->with('staff');
            $query->with('institution');
        }

        $records = $query->get();

        return response()->json($records);
    }
}

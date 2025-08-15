<?php

namespace App\Http\Controllers\Admin;

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

        // Example: Fetch teacher attendance records
        $records = Attendance::where('role', $role)
            ->when($institution, function ($query) use ($institution) {
                return $query->where('institution_id', $institution);
            })
            ->get();

        return response()->json($records);
    }
}

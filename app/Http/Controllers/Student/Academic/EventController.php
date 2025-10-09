<?php

namespace App\Http\Controllers\Student\Academic;

use App\Http\Controllers\Controller;
use App\Models\AcademicEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EventController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:student');
    }

    /**
     * Display events page
     */
    public function index()
    {
        return view('student.academic.events.index');
    }

    /**
     * Get events for student dashboard
     */
    public function getEvents()
    {
        try {
            $currentStudent = Auth::guard('student')->user();
            
            // Get only visible events for student's institution with role filtering
            $events = DB::table('academic_events')
                ->leftJoin('institutions', 'academic_events.institution_id', '=', 'institutions.id')
                ->select('academic_events.*', 'institutions.name as institution_name')
                ->where('academic_events.status', 1) // Only visible events
                ->where('academic_events.institution_id', $currentStudent->institution_id)
                ->where(function($query) {
                    $query->where('academic_events.role', 'student')
                          ->orWhere('academic_events.role', 'all')
                          ->orWhereNull('academic_events.role');
                })
                ->where('academic_events.start_date', '>=', now()->subDays(30)) // Only recent and future events
                ->orderBy('academic_events.start_date', 'asc')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $events
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching events'
            ], 500);
        }
    }

    /**
     * Get upcoming events for student dashboard
     */
    public function getUpcomingEvents()
    {
        try {
            $currentStudent = Auth::guard('student')->user();
            
            // Get only visible upcoming events (next 7 days) for student's institution
            $events = DB::table('academic_events')
                ->leftJoin('institutions', 'academic_events.institution_id', '=', 'institutions.id')
                ->select('academic_events.*', 'institutions.name as institution_name')
                ->where('academic_events.status', 1) // Only visible events
                ->where('academic_events.institution_id', $currentStudent->institution_id)
                ->where('academic_events.start_date', '>=', now()->toDateString())
                ->where('academic_events.start_date', '<=', now()->addDays(7)->toDateString())
                ->where(function($query) {
                    $query->where('academic_events.role', 'student')
                          ->orWhere('academic_events.role', 'all')
                          ->orWhereNull('academic_events.role');
                })
                ->orderBy('academic_events.start_date', 'asc')
                ->limit(5)
                ->get();

            return response()->json([
                'success' => true,
                'data' => $events
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching upcoming events'
            ], 500);
        }
    }
}

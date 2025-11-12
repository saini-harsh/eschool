<?php

namespace App\Http\Controllers\API\Teacher\Academic;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\AssignClassTeacher;
use App\Models\AssignSubject;
use App\Models\Assignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\StudentAssignment;
use App\Models\Event;

class EventController extends Controller
{
   
      public function getEvents(Request $request)
    {
        try {
            $currentTeacher = Teacher::where('email', $request->email)->first();
            
            if (!$currentTeacher) {
                return response()->json([
                    'success' => false,
                    'message' => 'No teacher found with this email',
                    'data' => []
                ], 404);
            }
            
            // Get only visible events for teacher's institution with role filtering
            $events = DB::table('academic_events')
                ->leftJoin('institutions', 'academic_events.institution_id', '=', 'institutions.id')
                ->select('academic_events.*', 'institutions.name as institution_name')
                ->where('academic_events.status', 1) // Only visible events
                ->where('academic_events.institution_id', $currentTeacher->institution_id)
                ->where(function($query) {
                    $query->where('academic_events.role', 'teacher')
                          ->orWhere('academic_events.role', 'all')
                          ->orWhereNull('academic_events.role');
                })
                ->where('academic_events.start_date', '>=', now()->subDays(30)) // Only recent and future events
                ->orderBy('academic_events.start_date', 'asc')
                ->get();

            $events->map(function($event) {
                $event->file_path = $event->file_path ? URL::to($event->file_path) : null;
            });

            return response()->json([
                'success' => true,
                'message' => 'Events fetched successfully',
                'data' => $events
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching events' . $e->getMessage(),
                'data' => []
            ], 500);
        }
    }
}
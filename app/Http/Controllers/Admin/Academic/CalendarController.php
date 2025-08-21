<?php

namespace App\Http\Controllers\Admin\Academic;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CalendarController extends Controller
{
    public function index()
    {
        return view('admin.academic.calendar.index');
    }

    public function getEvents()
    {
        try {
            $events = DB::table('academic_events')
                ->leftJoin('institutions', 'academic_events.institution_id', '=', 'institutions.id')
                ->select('academic_events.id', 'academic_events.title', 'academic_events.start_date', 'academic_events.end_date', 'academic_events.start_time', 'academic_events.end_time', 'academic_events.description', 'academic_events.category', 'academic_events.color', 'academic_events.location', 'academic_events.role', 'academic_events.institution_id', 'institutions.name as institution_name')
                ->where('academic_events.status', 1)
                ->get();
            
            $formattedEvents = $events->map(function ($event) {
                // Handle start date and time
                $start = $event->start_date;
                if ($event->start_time) {
                    $start .= 'T' . $event->start_time;
                }
                
                // Handle end date and time (use start date if no end date)
                $end = $event->end_date ?: $event->start_date;
                if ($event->end_time) {
                    $end .= 'T' . $event->end_time;
                } elseif ($event->start_time) {
                    // If no end time but has start time, add 1 hour as default duration
                    $end .= 'T' . date('H:i:s', strtotime($event->start_time . ' +1 hour'));
                }
                
                return [
                    'id' => $event->id,
                    'title' => $event->title,
                    'start' => $start,
                    'end' => $end,
                    'description' => $event->description,
                    'role' => $event->role,
                    'category' => $event->category,
                    'institution_id' => $event->institution_id,
                    'location' => $event->location,
                    'backgroundColor' => $event->color ?? '#3788d8',
                    'borderColor' => $event->color ?? '#3788d8',
                    'allDay' => !$event->start_time && !$event->end_time,
                    'extendedProps' => [
                        'role' => $event->role,
                        'category' => $event->category,
                        'institution_id' => $event->institution_id,
                        'institution_name' => $event->institution_name,
                        'description' => $event->description,
                        'location' => $event->location
                    ]
                ];
            });
            
            return response()->json($formattedEvents);
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to fetch events: ' . $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'role' => 'required|string|in:teacher,student,nonworkingstaff',
            'category' => 'required|string|max:100',
            'institution_id' => 'required|exists:institutions,id',
            'location' => 'required|string|max:255',
            'start_date' => 'required|string', // Changed to string for custom format validation
            'start_time' => 'nullable|date_format:H:i',
            'description' => 'required|string',
            'url' => 'nullable|url|max:255',
            'file' => 'nullable|file|mimes:jpg,jpeg,png,gif|max:2048',
            'status' => 'nullable|boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Handle file upload
            $filePath = null;
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $filePath = 'uploads/events/' . $fileName;
                $file->storeAs('public/uploads/events', $fileName);
            }

            // Parse the date format (d M, Y)
            try {
                $startDate = \Carbon\Carbon::createFromFormat('d M, Y', $request->start_date);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid date format. Please use format: dd MMM, yyyy (e.g., 15 Jan, 2025)'
                ], 422);
            }

            $eventId = DB::table('academic_events')->insertGetId([
                'title' => $request->title,
                'role' => $request->role,
                'category' => $request->category,
                'institution_id' => $request->institution_id,
                'location' => $request->location,
                'start_date' => $startDate->format('Y-m-d'),
                'start_time' => $request->start_time,
                'description' => $request->description,
                'url' => $request->url,
                'file_path' => $filePath,
                'color' => $this->getCategoryColor($request->category),
                'status' => $request->status ?? 1,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Event created successfully',
                'event_id' => $eventId
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create event',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    private function getCategoryColor($category)
    {
        $colors = [
            'Exam' => '#dc3545',        // Red for exams
            'Holiday' => '#20c997',     // Teal for holidays
            'Meeting' => '#28a745',     // Green for meetings
            'Event' => '#fd7e14',       // Orange for events
            'Deadline' => '#6f42c1',    // Purple for deadlines
            'Other' => '#6c757d'        // Gray for other
        ];
        return $colors[$category] ?? '#3788d8';
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i|after:start_time',
            'description' => 'nullable|string',
            'category' => 'required|string|max:100',
            'color' => 'nullable|string|max:7'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::table('academic_events')
                ->where('id', $id)
                ->update([
                    'title' => $request->title,
                    'start_date' => $request->start_date,
                    'end_date' => $request->end_date ?? $request->start_date,
                    'start_time' => $request->start_time,
                    'end_time' => $request->end_time,
                    'description' => $request->description,
                    'category' => $request->category,
                    'color' => $request->color ?? '#3788d8',
                    'updated_at' => now()
                ]);

            return response()->json([
                'success' => true,
                'message' => 'Event updated successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update event',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            DB::table('academic_events')
                ->where('id', $id)
                ->update(['status' => 0]);

            return response()->json([
                'success' => true,
                'message' => 'Event deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete event',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getEvent($id)
    {
        $event = DB::table('academic_events')
            ->where('id', $id)
            ->where('status', 1)
            ->first();

        if (!$event) {
            return response()->json([
                'success' => false,
                'message' => 'Event not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'event' => $event
        ]);
    }

    public function checkDatabase()
    {
        try {
            // Check if we can connect to the database
            DB::connection()->getPdo();
            
            // Check if the academic_events table exists
            $tableExists = DB::getSchemaBuilder()->hasTable('academic_events');
            
            if (!$tableExists) {
                return response()->json([
                    'success' => false,
                    'message' => 'Table academic_events does not exist',
                    'database' => DB::connection()->getDatabaseName(),
                    'connection' => 'Connected'
                ]);
            }
            
            // Check table structure
            $columns = DB::getSchemaBuilder()->getColumnListing('academic_events');
            
            // Count total events
            $totalEvents = DB::table('academic_events')->count();
            
            // Count active events
            $activeEvents = DB::table('academic_events')->where('status', 1)->count();
            
            // Get sample events
            $sampleEvents = DB::table('academic_events')
                ->select('id', 'title', 'start_date', 'status')
                ->limit(5)
                ->get();
            
            return response()->json([
                'success' => true,
                'message' => 'Database check completed',
                'database' => DB::connection()->getDatabaseName(),
                'connection' => 'Connected',
                'table_exists' => $tableExists,
                'columns' => $columns,
                'total_events' => $totalEvents,
                'active_events' => $activeEvents,
                'sample_events' => $sampleEvents
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Database check failed',
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ], 500);
        }
    }
}

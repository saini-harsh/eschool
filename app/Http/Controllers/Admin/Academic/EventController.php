<?php

namespace App\Http\Controllers\Admin\Academic;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class EventController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index(Request $request)
    {
        $institutions = DB::table('institutions')->where('status', 1)->get();

        $query = DB::table('academic_events')
            ->leftJoin('institutions', 'academic_events.institution_id', '=', 'institutions.id')
            ->select('academic_events.*', 'institutions.name as institution_name')
            ->where('academic_events.status', 1);

        if ($request->filled('institution_ids')) {
            $institutionIds = is_array($request->institution_ids) ? $request->institution_ids : [$request->institution_ids];
            $query->whereIn('academic_events.institution_id', $institutionIds);
        }

        if ($request->filled('roles')) {
            $roles = is_array($request->roles) ? $request->roles : [$request->roles];
            $query->where(function($q) use ($roles) {
                $q->whereIn('academic_events.role', $roles);
                if (in_array('all', $roles)) {
                    $q->orWhereNull('academic_events.role');
                }
            });
        }

        $events = $query->orderBy('academic_events.created_at', 'desc')->get();

        return view('admin.academic.events.index', compact('events', 'institutions'));
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'title' => 'required|string|max:255',
                'role' => 'required|string|in:teacher,student,nonworkingstaff',
                'category' => 'required|string|max:100',
                'institution_id' => 'required|exists:institutions,id',
                'location' => 'required|string|max:255',
                'start_date' => 'required|string',
                'start_time' => 'nullable|date_format:H:i',
                'description' => 'required|string',
                'color' => 'nullable|string|max:7',
                'url' => 'nullable|url|max:255',
                'file' => 'nullable|file|mimes:jpg,jpeg,png,gif|max:2048',
                'status' => 'nullable|boolean',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Custom validation for date format
            try {
                $startDate = \Carbon\Carbon::createFromFormat('d M, Y', $request->start_date);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => ['start_date' => ['The start date field must match the format d M, Y (e.g., 15 Jan, 2025)']]
                ], 422);
            }

            $filePath = null;
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $filePath = 'uploads/events/' . $fileName;
                $file->storeAs('public/uploads/events', $fileName);
            }

            $eventId = DB::table('academic_events')->insertGetId([
                'title' => $request->title,
                'role' => $request->role,
                'category' => $request->category,
                'institution_id' => $request->institution_id,
                'location' => $request->location,
                'start_date' => \Carbon\Carbon::createFromFormat('d M, Y', $request->start_date)->format('Y-m-d'),
                'start_time' => $request->start_time,
                'description' => $request->description,
                'color' => $request->color ?? '#3788d8',
                'url' => $request->url,
                'file_path' => $filePath,
                'status' => $request->status ?? 1,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            $event = DB::table('academic_events')->where('id', $eventId)->first();

            return response()->json([
                'success' => true,
                'message' => 'Event created successfully',
                'data' => $event
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while creating the Event',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getEvents()
    {
        try {
            $events = DB::table('academic_events')
                ->leftJoin('institutions', 'academic_events.institution_id', '=', 'institutions.id')
                ->select('academic_events.*', 'institutions.name as institution_name')
                ->where('academic_events.status', 1)
                ->orderBy('academic_events.created_at', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $events
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching Events'
            ], 500);
        }
    }

    public function updateStatus(Request $request, $id)
    {
        try {
            $event = DB::table('academic_events')->where('id', $id)->first();
            
            if (!$event) {
                return response()->json([
                    'success' => false,
                    'message' => 'Event not found'
                ], 404);
            }

            DB::table('academic_events')
                ->where('id', $id)
                ->update(['status' => $request->status]);

            return response()->json([
                'success' => true,
                'message' => 'Status updated successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating status'
            ], 500);
        }
    }

    public function edit($id)
    {
        try {
            $event = DB::table('academic_events')->where('id', $id)->first();
            
            if (!$event) {
                return response()->json([
                    'success' => false,
                    'message' => 'Event not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $event
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching Event'
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            
            $validator = Validator::make($request->all(), [
                'title' => 'required|string|max:255',
                'role' => 'required|string|in:teacher,student,nonworkingstaff',
                'institution_id' => 'required|exists:institutions,id',
                'location' => 'required|string|max:255',
                'start_date' => 'required|string',
                'start_time' => 'nullable|date_format:H:i',
                'description' => 'required|string',
                'category' => 'required|string|max:100',
                'color' => 'nullable|string|max:7',
                'url' => 'nullable|url|max:255',
                'file' => 'nullable|file|mimes:jpg,jpeg,png,gif|max:2048',
                'status' => 'nullable|boolean',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Custom validation for date format
            try {
                $startDate = \Carbon\Carbon::createFromFormat('d M, Y', $request->start_date);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => ['start_date' => ['The start date field must match the format d M, Y (e.g., 15 Jan, 2025)']]
                ], 422);
            }

            $event = DB::table('academic_events')->where('id', $id)->first();
            
            if (!$event) {
                return response()->json([
                    'success' => false,
                    'message' => 'Event not found'
                ], 404);
            }

            $filePath = $event->file_path;
            if ($request->hasFile('file')) {
                // Delete old file if exists
                if ($filePath && Storage::disk('public')->exists($filePath)) {
                    Storage::disk('public')->delete($filePath);
                }
                
                $file = $request->file('file');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $filePath = 'uploads/events/' . $fileName;
                $file->storeAs('public/uploads/events', $fileName);
            }

            DB::table('academic_events')
                ->where('id', $id)
                ->update([
                    'title' => $request->title,
                    'role' => $request->role,
                    'institution_id' => $request->institution_id,
                    'location' => $request->location,
                    'start_date' => \Carbon\Carbon::createFromFormat('d M, Y', $request->start_date)->format('Y-m-d'),
                    'start_time' => $request->start_time,
                    'description' => $request->description,
                    'category' => $request->category,
                    'color' => $request->color ?? '#3788d8',
                    'url' => $request->url,
                    'file_path' => $filePath,
                    'status' => $request->status ?? 1,
                    'updated_at' => now()
                ]);

            $updatedEvent = DB::table('academic_events')->where('id', $id)->first();

            return response()->json([
                'success' => true,
                'message' => 'Event updated successfully',
                'data' => $updatedEvent
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating the Event',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function delete($id)
    {
        try {
            $event = DB::table('academic_events')->where('id', $id)->first();
            
            if (!$event) {
                return response()->json([
                    'success' => false,
                    'message' => 'Event not found'
                ], 404);
            }

            // Delete file if exists
            if ($event->file_path && Storage::disk('public')->exists($event->file_path)) {
                Storage::disk('public')->delete($event->file_path);
            }

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
                'message' => 'Error deleting Event'
            ], 500);
        }
    }
}

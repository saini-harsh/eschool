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
        $events = DB::table('academic_events')
            ->select('id', 'title', 'start_date', 'end_date', 'start_time', 'end_time', 'description', 'category', 'color')
            ->where('status', 1)
            ->get()
            ->map(function ($event) {
                return [
                    'id' => $event->id,
                    'title' => $event->title,
                    'start' => $event->start_date . ($event->start_time ? 'T' . $event->start_time : ''),
                    'end' => $event->end_date . ($event->end_time ? 'T' . $event->end_time : ''),
                    'description' => $event->description,
                    'category' => $event->category,
                    'backgroundColor' => $event->color ?? '#3788d8',
                    'borderColor' => $event->color ?? '#3788d8',
                    'allDay' => !$event->start_time && !$event->end_time
                ];
            });

        return response()->json($events);
    }

    public function store(Request $request)
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
            $eventId = DB::table('academic_events')->insertGetId([
                'title' => $request->title,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date ?? $request->start_date,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'description' => $request->description,
                'category' => $request->category,
                'color' => $request->color ?? '#3788d8',
                'status' => 1,
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
}

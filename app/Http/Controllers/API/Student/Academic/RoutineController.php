<?php

namespace App\Http\Controllers\API\Student\Academic;

use App\Http\Controllers\Controller;
use App\Models\Routine;
use App\Models\Section;
use App\Models\SchoolClass;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RoutineController extends Controller
{

    /**
     * Get class routine report filtered by class and section (teacher identified by email)
     */
    public function getRoutineReport(Request $request)
    {
        try {
           

            $student = Student::where('email', $request->email)->first();
            if (!$student) {
                return response()->json([
                    'success' => false,
                    'message' => 'Student not found',
                    'data' => [],
                ], 404);
            }

            $routines = Routine::with([
                    'subject:id,name,code',
                    'teacher:id,first_name,last_name',
                    'classRoom:id,room_no,room_name',
                ])
                ->where('institution_id', $student->institution_id)
                ->where('class_id', $student->class_id)
                ->where('section_id', $student->section_id)
                ->where('status', 1)
                ->orderBy('day')
                ->orderBy('start_time')
                ->get([
                    'id', 'institution_id', 'class_id', 'section_id', 'subject_id', 'teacher_id', 'class_room_id',
                    'day', 'start_time', 'end_time', 'is_break', 'status'
                ]);

            // Optional grouping by day for easy grid rendering
            $groupedByDay = $routines->groupBy('day')->map(function ($items) {
                return $items->map(function ($routine) {
                    return [
                        'day' => $routine->day,
                        'start_time' => $routine->start_time,
                        'end_time' => $routine->end_time,
                        'is_break' => (bool) $routine->is_break,
                        'subject' => optional($routine->subject)->name,
                        'subject_code' => optional($routine->subject)->code,
                        'teacher_name' => trim(($routine->teacher->first_name ?? '') . ' ' . ($routine->teacher->last_name ?? '')),
                        'room_no' => optional($routine->classRoom)->room_no,
                        'room_name' => optional($routine->classRoom)->room_name,
                    ];
                });
            });

            return response()->json([
                'success' => true,
                'message' => 'Routine report fetched successfully',
                'data' => [
                    'routines' => $routines,
                    'grouped_by_day' => $groupedByDay,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching routine report: ' . $e->getMessage(),
                'data' => [],
            ], 500);
        }
    }
}


<?php

namespace App\Http\Controllers\Institution\ExamManagement;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Invigilator;
use App\Models\Exam;
use App\Models\ClassRoom;
use App\Models\Teacher;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class InvigilatorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:institution');
    }

    public function index(Request $request)
    {
        $institution = Auth::guard('institution')->user();

        $teachers = Teacher::where('institution_id', $institution->id)
            ->where('status', 1)
            ->get(['id', 'first_name', 'last_name']);

        $examSchedules = [];

        if ($request->filled('exam_type') || $request->filled('month')) {
            try {
                $query = Exam::with(['examType'])
                    ->where('institution_id', $institution->id);

                if ($request->filled('exam_type')) {
                    $query->where('exam_type_id', $request->exam_type);
                }
                if ($request->filled('month')) {
                    $query->where('month', $request->month);
                }

                $exams = $query->orderBy('start_date', 'asc')->get();

                $rooms = ClassRoom::where('status', true)->orderBy('room_no')->get();

                foreach ($exams as $exam) {
                    $dates = [];
                    if ($exam->subject_dates) {
                        $decoded = is_array($exam->subject_dates)
                            ? $exam->subject_dates
                            : json_decode($exam->subject_dates, true);
                        if (is_array($decoded)) {
                            $dates = $decoded;
                        }
                    }

                    foreach ($dates as $date) {
                        $assignments = Invigilator::where('institution_id', $institution->id)
                            ->where('exam_id', $exam->id)
                            ->where('date', $date)
                            ->get()
                            ->keyBy('class_room_id');

                        $examSchedules[] = [
                            'exam' => $exam,
                            'date' => $date,
                            'rooms' => $rooms,
                            'assignments' => $assignments,
                        ];
                    }
                }
            } catch (\Exception $e) {
                Log::error('Invigilator index error: '.$e->getMessage());
            }
        }

        $institutions = collect([$institution]);
        return view('institution.examination.invigilator.index', compact('examSchedules', 'teachers', 'institutions'));
    }

    public function assign(Request $request)
    {
        $institution = Auth::guard('institution')->user();

        $request->validate([
            'exam_id' => 'required|exists:exams,id',
            'date' => 'required|date',
            'class_room_id' => 'required|exists:class_rooms,id',
            'teacher_id' => 'required|exists:teachers,id',
        ]);

        Invigilator::updateOrCreate(
            [
                'institution_id' => $institution->id,
                'exam_id' => $request->exam_id,
                'date' => $request->date,
                'class_room_id' => $request->class_room_id,
            ],
            [
                'teacher_id' => $request->teacher_id,
                'time' => null,
                'class_id' => null,
                'section_id' => null,
            ]
        );

        return redirect()->back()->with('success', 'Invigilator assigned successfully');
    }

    public function assignments(Request $request)
    {
        $institution = Auth::guard('institution')->user();

        $query = Invigilator::with(['teacher', 'classRoom', 'exam.examType'])
            ->where('institution_id', $institution->id);

        if ($request->filled('exam_type')) {
            $query->whereHas('exam', function ($q) use ($request) {
                $q->where('exam_type_id', $request->exam_type);
            });
        }

        if ($request->filled('month')) {
            $query->whereHas('exam', function ($q) use ($request) {
                $q->where('month', $request->month);
            });
        }

        if ($request->filled('date')) {
            $query->where('date', $request->date);
        }

        $assignments = $query->orderBy('date', 'asc')->get();

        $institutions = collect([$institution]);

        return view('institution.examination.invigilator.assignments', compact('assignments', 'institutions'));
    }
}

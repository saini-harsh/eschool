<?php

namespace App\Http\Controllers\Admin\ExamManagement;

use App\Models\Section;
use App\Models\Student;
use App\Models\ClassRoom;
use App\Models\SchoolClass;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ClassRoomController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        $lists = ClassRoom::all();
        return view('admin.examination.rooms', compact('lists'));
    }

    public function create()
    {
        return view('admin.academic.classrooms.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'room_no' => 'required|string|max:255',
            'room_name' => 'nullable|string|max:255',
            'capacity' => 'required|integer|min:1',
            'status' => 'required|boolean',
        ]);

        $classRoom = new ClassRoom;
        $classRoom->room_no = $request->room_no;
        $classRoom->room_name = $request->room_name;
        $classRoom->capacity = $request->capacity;
        $classRoom->status = $request->status;
        $classRoom->save();

        return response()->json(['success' => 'Class Room created successfully.']);
    }

    public function storeWithSeatmap(Request $request)
    {
        $request->validate([
            'room_no' => 'required|string|max:255',
            'room_name' => 'nullable|string|max:255',
            'capacity' => 'required|integer|min:1',
            'seatmap' => 'required|array',
            'status' => 'required|boolean',
        ]);

        $classRoom = new ClassRoom;
        $classRoom->room_no = $request->room_no;
        $classRoom->room_name = $request->room_name;
        $classRoom->capacity = $request->capacity;
        $classRoom->seatmap = $request->seatmap;
        $classRoom->status = $request->status;
        $classRoom->save();

        return response()->json([
            'success' => true,
            'message' => 'Class Room with seatmap created successfully.',
            'data' => $classRoom
        ]);
    }

    public function storeWithLayout(Request $request)
    {
        $request->validate([
            'room_no' => 'required|string|max:255',
            'room_name' => 'nullable|string|max:255',
            'capacity' => 'required|integer|min:1',
            'layout_data' => 'required|array',
            'students_per_bench' => 'required|integer|min:1|max:4',
            'status' => 'required|boolean',
        ]);

        $classRoom = new ClassRoom;
        $classRoom->room_no = $request->room_no;
        $classRoom->room_name = $request->room_name;
        $classRoom->capacity = $request->capacity;
        $classRoom->seatmap = $request->layout_data;
        $classRoom->students_per_bench = $request->students_per_bench;
        $classRoom->status = $request->status;
        $classRoom->save();

        return response()->json([
            'success' => true,
            'message' => 'Class Room with layout created successfully.',
            'data' => $classRoom
        ]);
    }

    public function show($id)
    {
        $classRoom = ClassRoom::findOrFail($id);
        return view('admin.examination.room-details', compact('classRoom'));
    }

    public function edit($id)
    {
        $classRoom = ClassRoom::findOrFail($id);
        return view('admin.examination.room-edit', compact('classRoom'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'room_no' => 'required|string|max:255',
            'room_name' => 'nullable|string|max:255',
            'capacity' => 'required|integer|min:1',
            'status' => 'required|boolean',
        ]);

        $classRoom = ClassRoom::findOrFail($id);
        $classRoom->room_no = $request->room_no;
        $classRoom->room_name = $request->room_name;
        $classRoom->capacity = $request->capacity;
        $classRoom->status = $request->status;
        $classRoom->save();

        return response()->json(['success' => 'Class Room updated successfully.']);
    }

    public function updateSeatmap(Request $request, $id)
    {
        $request->validate([
            'seatmap' => 'required|array',
        ]);

        $classRoom = ClassRoom::findOrFail($id);
        $classRoom->seatmap = $request->seatmap;
        $classRoom->save();

        return response()->json([
            'success' => true,
            'message' => 'Seatmap updated successfully.',
            'data' => $classRoom
        ]);
    }

    public function designLayout($id)
    {
        $classRoom = ClassRoom::findOrFail($id);

        return view('admin.examination.room-layout', compact('classRoom'));
    }

    public function updateLayout(Request $request, $id)
    {
        $request->validate([
            'layout_data' => 'required|array',
            'students_per_bench' => 'required|integer|min:1|max:4',
        ]);

        $classRoom = ClassRoom::findOrFail($id);
        $classRoom->seatmap = $request->layout_data;
        $classRoom->students_per_bench = $request->students_per_bench;
        $classRoom->save();

        return response()->json([
            'success' => true,
            'message' => 'Room layout updated successfully.',
            'data' => $classRoom
        ]);
    }

    public function destroy($id)
    {
        $classRoom = ClassRoom::findOrFail($id);
        $classRoom->delete();

        return response()->json(['success' => 'Class Room deleted successfully.']);
    }

    /**
     * Get classes by institution ID
     */
    public function getClassesByInstitution($institutionId)
    {
        try {
            $classes = SchoolClass::where('institution_id', $institutionId)
                ->where('status', 1)
                ->get(['id', 'name', 'institution_id']);

            return response()->json(['success' => true, 'classes' => $classes]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => 'An error occurred while fetching classes']);
        }
    }

    /**
     * Get sections by class ID
     */
    public function getSectionsByClass($classId)
    {
        try {
            $institutionId = auth('admin')->id();

            // First verify the class belongs to the institution
            $class = SchoolClass::where('id', $classId)
                ->where('institution_id', $institutionId)
                ->first();

            if (!$class) {
                return response()->json(['success' => false, 'error' => 'Class not found or does not belong to your institution']);
            }

            // Fetch sections directly from sections table by institution ID and class ID
            $sections = Section::where('institution_id', $institutionId)
                ->where('class_id', $classId)
                ->get(['id', 'name']);

            return response()->json(['success' => true, 'sections' => $sections]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => 'An error occurred while fetching sections']);
        }
    }

    /**
     * Get students by class and section
     */
    public function getStudentsByClassAndSection($classId, $sectionId)
    {
       // return response()->json(['success' => true, 'classId' => $classId, 'sectionId' => $sectionId]);
        try {
            $institutionId = auth('admin')->id();

            // Verify the class and section belong to the institution
            $class = SchoolClass::where('id', $classId)
                ->where('institution_id', $institutionId)
                ->first();

            $section = Section::where('id', $sectionId)
                ->where('institution_id', $institutionId)
                ->where('class_id', $classId)
                ->first();

            if (!$class || !$section) {
                return response()->json(['success' => false, 'error' => 'Class or section not found']);
            }

            // Fetch students
            $students = Student::where('institution_id', $institutionId)
                ->where('class_id', $classId)
                ->where('section_id', $sectionId)
                ->where('status', 1)
                ->with(['schoolClass', 'section'])
                ->get(['id', 'first_name', 'last_name', 'roll_number', 'class_id', 'section_id']);

            // Format student data
            $formattedStudents = $students->map(function ($student) {
                return [
                    'id' => $student->id,
                    'name' => $student->first_name . ' ' . $student->last_name,
                    'roll_number' => $student->roll_number,
                    'student_id' => $student->student_id,
                    'class_name' => $student->schoolClass ? $student->schoolClass->name : 'N/A',
                    'section_name' => $student->section ? $student->section->name : 'N/A',
                ];
            });
            return response()->json(['success' => true, 'students' => $formattedStudents]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => 'An error occurred while fetching students', 'message' => $e->getMessage()]);
        }
    }
}

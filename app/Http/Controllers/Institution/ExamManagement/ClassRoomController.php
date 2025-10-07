<?php

namespace App\Http\Controllers\Institution\ExamManagement;

use App\Http\Controllers\Controller;
use App\Models\ClassRoom;
use Illuminate\Http\Request;

class ClassRoomController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:institution');
    }

    public function index()
    {
        $lists = ClassRoom::all();
        return view('institution.academic.classrooms.index', compact('lists'));
    }

    public function create()
    {
        return view('institution.academic.classrooms.create');
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

    public function show($id)
    {
        $classRoom = ClassRoom::findOrFail($id);
        return view('institution.academic.classrooms.show', compact('classRoom'));
    }

    public function edit($id)
    {
        $classRoom = ClassRoom::findOrFail($id);
        return view('institution.academic.classrooms.edit', compact('classRoom'));
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

    public function destroy($id)
    {
        $classRoom = ClassRoom::findOrFail($id);
        $classRoom->delete();

        return response()->json(['success' => 'Class Room deleted successfully.']);
    }
}

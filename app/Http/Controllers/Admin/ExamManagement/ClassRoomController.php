<?php

namespace App\Http\Controllers\Admin\ExamManagement;

use App\Http\Controllers\Controller;
use App\Models\ClassRoom;
use Illuminate\Http\Request;

class ClassRoomController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        $lists = ClassRoom::all();
        return view('admin.academic.classrooms.index', compact('lists'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'room_no' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1',
            'status' => 'required|boolean',
        ]);

        $classRoom = new ClassRoom;
        $classRoom->room_no = $request->room_no;
        $classRoom->capacity = $request->capacity;
        $classRoom->status = $request->status;
        $classRoom->save();

        return response()->json(['success' => 'Class Room created successfully.']);
    }
}

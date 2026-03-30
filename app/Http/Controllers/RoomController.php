<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Student;
use App\Services\RoomService;
use Exception;
use Illuminate\Http\Request;

use const Dom\VALIDATION_ERR;

class RoomController extends Controller
{
    public function index()
    {
        $rooms = Room::with('students')->get();

        return view('rooms.index', [
            'rooms' => $rooms
        ]);
    }

    public function create(Room $room)
    {
        $room->isAvailable();

        $students = Student::where('room_id', '=', null)->get();
        
        return view('rooms.create', [
            'room' => $room,
            'students' => $students
        ]);
    }

    public function update(Room $room, Request $request, RoomService $roomService)
    {  
        try {
            $student = Student::findOrFail($request->input('student_id'));
            $roomService->studentCheckin($room, $student);

            return redirect()->route('rooms')->with('success', 'Заселен');
        } catch (\Exception $e) {
            return redirect()->route('rooms')->with('error', $e->getMessage());
        }
    }
}

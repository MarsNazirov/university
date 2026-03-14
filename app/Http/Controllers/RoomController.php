<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Student;
use App\Services\RoomService;
use Exception;
use Illuminate\Http\Request;

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

    public function update(Student $student, Room $room, RoomService $roomService)
    {  
        try {
            $roomService->studentCheckin($room, $student);

            return redirect()->route('rooms')->with('success', 'Заселен');
        } catch (\Exception $e) {
            return redirect()->route('rooms')->with('error', $e->getMessage());
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Student;
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
        if ($room->status !== 'available') {
            return redirect()->route('rooms');
        }

        $students = Student::where('room_id', '=', null)->get();

        // dd($room);
        
        return view('rooms.create', [
            'room' => $room,
            'students' => $students
        ]);
    }

    public function update(Request $request, Room $room)
    {  
        // dd($room->count());

        if ($room->status !== 'available') {
            return redirect()->route('rooms');
        }

        $student = Student::find($request->input('student_id'));

        $student->update([
            'room_id' => $room->id
        ]);  

        if ($room->students->count() === $room->beds_count) {
            $room->status = 'occupied';
            $room->save();
        }

        return redirect()->route('rooms');
    }
}

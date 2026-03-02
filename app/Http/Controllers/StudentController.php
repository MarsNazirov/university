<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index()
    {
        $students = Student::with('room')->get();

        return view('students.index', compact('students'));
    }

    public function evict(Student $student)
    {
        $old_room_id = $student->room_id;

        if (!$old_room_id) {
            return redirect()->back();
        }

        $student->update([
            'room_id' => null
        ]);

        $room = Room::find($old_room_id);

        if ($room->students->count() < $room->beds_count) {
            $room->status = 'available';
            $room->save();
        }

        

        return redirect()->back();
    }
}

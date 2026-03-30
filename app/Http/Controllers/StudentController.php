<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Student;
use App\Services\RoomService;
use Exception;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index()
    {
        $students = Student::with('room')->get();

        return view('students.index', compact('students'));
    }

    public function evict(Student $student, RoomService $roomService)
    {
        try {
            $roomService->studentEvict($student);

            return redirect()->route('students')->with('success', 'Студент выселен');
        } catch (Exception $e) {
            return redirect()->route('students')->with('error', 'Неудалось выселить студента');
        }
    }
}

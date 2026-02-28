<?php

namespace App\Http\Controllers;

use App\Models\Room;
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
}

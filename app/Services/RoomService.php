<?php

namespace App\Services;

use App\Models\Room;
use App\Models\Student;

class RoomService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        
    }

    public function studentCheckin(Room $room, Student $student)
    {
        if (!$room->isAvailable()) {
            throw new \Exception('Комната недоступна для заселения');
        }

        $student->update([
            'room_id' => $room->id
        ]);

        if ($room->students()->count() === $room->beds_count) {
            $room->status = 'occupied';
            $room->save();
        }

    }
}

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

    public function studentEvict(Student $student)
    {
        $old_room_id = $student->room_id;

        if (!$old_room_id) {
            throw new \Exception('У студента нету комнаты');
        }

        $student->update([
            'room_id' => null
        ]);

        $room = Room::find($old_room_id);

        if ($room->students()->count() < $room->beds_count) {
            $room->status = 'available';
            $room->save();
        }
    }
}

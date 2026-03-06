<?php

namespace Tests\Feature;

use App\Models\Room;
use App\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RoomCheckinTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_student_can_check_in_to_room()
    {
        $room = Room::factory()->create([
            'beds_count' => 2,
            'status' => 'available'
        ]);

        $student = Student::factory()->create([
            'room_id' => null
        ]);

        $response = $this->patch(route('rooms.update', $room), [
            'student_id' => $student->id
        ]);

        $response->assertRedirect(route('rooms'));
        $this->assertDatabaseHas('students', [
            'id' => $student->id,
            'room_id' => $room->id
        ]);
    }

    public function test_room_status_becomes_occupied_when_full()
    {
        $room = Room::factory()->create([
            'beds_count' => 1,
            'status' => 'available'
        ]);

        $student = Student::factory()->create([
            'room_id' => $room->id
        ]);

        $this->patch(route('rooms.update', $room), [
            'student_id' => $student->id
        ]);

        $room->refresh();
        $this->assertEquals('occupied', $room->status);
    }

    public function test_student_can_be_evicted()
    {
        $room = Room::factory()->create([
            'beds_count' => 1,
            'status' => 'occupied'
        ]);

        $student = Student::factory()->create([
            'room_id' => $room->id
        ]);
        
        $response = $this->patch(route('students.evict', $student));

        $response->assertRedirect();
        $this->assertDatabaseHas('students', [
            'id' => $student->id,
            'room_id' => null
        ]);

        $room->refresh();
        $this->assertEquals('available', $room->status);
    }
}

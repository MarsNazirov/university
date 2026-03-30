<?php

namespace Database\Seeders;

use App\Models\Room;
use App\Models\Student;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $rooms = Room::factory()->count(20)->create();

        $totalStudents = 0;
        $targetTotal = 50;

        foreach ($rooms as $room) {
            $studentsInRoom = rand(0, $room->beds_count);

            Student::factory()->count($studentsInRoom)->create([
                'room_id' => $room->id
            ]);

            $totalStudents += $studentsInRoom;

            if ($totalStudents < $targetTotal) {
                Student::factory()->count($targetTotal - $totalStudents)->create([
                    'room_id' => null
                ]);
            }

            foreach ($rooms as $room) {
                $count = $room->students()->count();

                $room->status = ($count === $room->beds_count) ? 'occupied' : 'available';
                $room->save();
            }
        }

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}

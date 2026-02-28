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

        for ($i = 0; $i < 50; $i++) {
            Student::factory()->create([
                'room_id' => $rooms->random()->id
            ]);
        }

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}

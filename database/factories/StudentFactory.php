<?php

namespace Database\Factories;

use App\Models\Room;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Student>
 */
class StudentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'full_name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'group' => fake()->randomElement(['ИУ5-31', 'ИУ5-32', 'ИУ6-31', 'ИУ7-31', 'МТ3-21']),
            'course' => fake()->numberBetween(1, 5),
            'status' => fake()->randomElement(['active', 'expelled', 'academic_leave']),
        ];
    }

}

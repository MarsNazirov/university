<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Room>
 */
class RoomFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'number' => fake()->unique()->numberBetween(1, 500),
            'floor' => fake()->numberBetween(1, 5),
            'type' => fake()->randomElement(['male', 'female']),
            'price' => fake()->randomFloat(2, 2000, 4000),
            'beds_count' => fake()->numberBetween(1, 4),
            'status' => fake()->randomElement(['available', 'occupied']),
            'description' => fake()->paragraph(),
            'photo' => 'rooms/default.jpg'
        ];
    }
}

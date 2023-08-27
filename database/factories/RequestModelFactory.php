<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\RequestModel>
 */
class RequestModelFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     * @throws \Exception
     */
    public function definition()
    {
        $arrayValues = ['Active', 'Resolved'];
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'status' => $arrayValues[random_int(0, 1)],
            'message' => fake()->text(),
            'comment' => fake()->text()
        ];
    }
}

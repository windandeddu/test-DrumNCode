<?php

namespace Database\Factories;

use App\Enums\TaskStatusEnum;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<User>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $statuses = array_column(TaskStatusEnum::cases(), 'value');

        return [
            'title' => fake()->text(40),
            'description' => fake()->text(),
            'priority' => fake()->numberBetween(1,5),
            'status' => $statuses[rand(0,2)],
            'user_id' => rand(0,1),
        ];
    }
}

<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Task;
use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    protected $model = Task::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->words(2, true),
            'description' => $this->faker->sentence(),
            'status' => $this->faker->randomElement(['pending', 'completed']),
            'projects_id' => Project::factory(),
            'assigned_to' => User::factory(), // Assuming the User model is factory-enabled
        ];
    }
}

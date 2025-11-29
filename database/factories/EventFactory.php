<?php

namespace Database\Factories;

use App\Models\Event;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
{
    protected $model = Event::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $year = fake()->numberBetween(2024, 2026);

        return [
            'year' => $year,
            'name' => "Secret Santa {$year}",
            'description' => fake()->sentence(),
            'registration_start' => now()->startOfYear()->addMonths(10),
            'registration_end' => now()->startOfYear()->addMonths(11),
            'is_active' => false,
            'assignments_made' => false,
        ];
    }

    /**
     * Indicate that the event is active.
     */
    public function active(): static
    {
        return $this->state(fn(array $attributes) => [
            'is_active' => true,
        ]);
    }

    /**
     * Indicate that assignments have been made.
     */
    public function assignmentsMade(): static
    {
        return $this->state(fn(array $attributes) => [
            'assignments_made' => true,
            'assignment_date' => now(),
        ]);
    }
}

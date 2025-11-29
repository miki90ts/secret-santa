<?php

namespace Database\Factories;

use App\Models\Gift;
use App\Models\Assignment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Gift>
 */
class GiftFactory extends Factory
{
    protected $model = Gift::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $gifts = [
            'Knjiga',
            'Šolja sa motivom',
            'Čokolada',
            'Vino',
            'Sveća sa mirisom',
            'Set čajeva',
            'Privezak za ključeve',
            'Rokovnik',
            'Bluetooth zvučnik',
            'Powerbank',
        ];

        return [
            'assignment_id' => Assignment::factory(),
            'description' => fake()->randomElement($gifts),
            'is_satisfied' => fake()->boolean(80), // 80% šanse da je zadovoljan
            'feedback' => fake()->optional()->sentence(),
            'received_at' => now(),
        ];
    }

    /**
     * Indicate that the recipient was satisfied.
     */
    public function satisfied(): static
    {
        return $this->state(fn(array $attributes) => [
            'is_satisfied' => true,
            'feedback' => 'Odličan poklon, baš mi se dopao!',
        ]);
    }

    /**
     * Indicate that the recipient was not satisfied.
     */
    public function unsatisfied(): static
    {
        return $this->state(fn(array $attributes) => [
            'is_satisfied' => false,
            'feedback' => 'Nije baš ono što sam očekivao/la.',
        ]);
    }
}

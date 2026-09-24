<?php

namespace Database\Factories;

use App\Models\ContactMessage;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ContactMessage>
 */
class ContactMessageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->safeEmail(),
            'phone' => fake()->numerify('08##########'),
            'message' => fake()->paragraph(),
        ];
    }

    /**
     * Indicate that the message has been replied to.
     */
    public function handled(): static
    {
        return $this->state(fn (array $attributes) => [
            'handled_at' => now(),
        ]);
    }
}

<?php

namespace Database\Factories;

use App\Models\BrandBlueprint;
use App\Models\Member;
use App\Services\BrandBlueprintGenerator;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<BrandBlueprint>
 */
class BrandBlueprintFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $answers = [
            'brand_name' => fake()->unique()->company(),
            'industry' => fake()->randomElement(config('blueprint.industries')),
            'audience' => fake()->sentence(12),
            'price_position' => fake()->randomElement(config('blueprint.price_positions')),
            'vision' => fake()->sentence(16),
        ];

        return [
            ...$answers,
            'member_id' => Member::factory(),
            'payload' => (new BrandBlueprintGenerator())->generate($answers),
            'generator' => 'openrouter',
            'model' => 'anthropic/claude-opus-5',
            'generation_ms' => fake()->numberBetween(4000, 30000),
        ];
    }

    /**
     * Keep the payload consistent with the answers, including any a caller overrode.
     */
    public function configure(): static
    {
        return $this->afterMaking(function (BrandBlueprint $blueprint) {
            $blueprint->payload = (new BrandBlueprintGenerator())->generate([
                'brand_name' => $blueprint->brand_name,
                'industry' => $blueprint->industry,
                'audience' => $blueprint->audience,
                'price_position' => $blueprint->price_position,
                'vision' => $blueprint->vision,
            ]);
        });
    }

    /**
     * Indicate that generation fell back to the template.
     */
    public function fellBack(string $reason = 'OpenRouter returned 429: rate limited'): static
    {
        return $this->state(fn (array $attributes) => [
            'generator' => 'template',
            'model' => null,
            'generation_ms' => null,
            'failure_reason' => $reason,
        ]);
    }
}

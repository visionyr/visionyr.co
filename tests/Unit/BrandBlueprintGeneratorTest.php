<?php

namespace Tests\Unit;

use App\Services\BrandBlueprintGenerator;
use PHPUnit\Framework\TestCase;

class BrandBlueprintGeneratorTest extends TestCase
{
    protected BrandBlueprintGenerator $generator;

    protected function setUp(): void
    {
        parent::setUp();

        $this->generator = new BrandBlueprintGenerator();
    }

    /**
     * @return array<string, string>
     */
    protected function answers(array $overrides = []): array
    {
        return array_merge([
            'brand_name' => 'Scentrism',
            'industry' => 'Fragrance',
            'audience' => 'Urban professionals, 28-45.',
            'price_position' => 'Premium',
            'vision' => 'A fragrance house built around mindful living.',
        ], $overrides);
    }

    public function test_it_returns_every_section_the_result_page_renders(): void
    {
        $blueprint = $this->generator->generate($this->answers());

        foreach ([
            'brand_name', 'tagline', 'scores', 'chips', 'market_opportunity', 'positioning',
            'audience', 'personality', 'values', 'story', 'voice', 'naming', 'taglines',
            'visual', 'colors', 'launch', 'pillars', 'plan',
        ] as $key) {
            $this->assertArrayHasKey($key, $blueprint);
        }
    }

    public function test_it_weaves_the_answers_into_the_copy(): void
    {
        $blueprint = $this->generator->generate($this->answers());

        $this->assertSame('Scentrism', $blueprint['brand_name']);
        $this->assertSame('Scentrism — where fragrance meets intention.', $blueprint['tagline']);
        $this->assertStringContainsString('Fragrance category', $blueprint['market_opportunity']);
        $this->assertStringContainsString('premium fragrance brand', $blueprint['positioning']);
        $this->assertStringContainsString('Urban professionals, 28-45.', $blueprint['audience'][0]);
        $this->assertStringContainsString('mindful living', $blueprint['story']);
        $this->assertContains('scentrismstudio', $blueprint['naming']);
    }

    public function test_it_carries_the_choices_through_as_chips(): void
    {
        $blueprint = $this->generator->generate($this->answers());

        $this->assertSame(['Fragrance', 'Premium', 'Built with Visionyr'], $blueprint['chips']);
    }

    public function test_scores_stay_within_a_presentable_range(): void
    {
        foreach (['A', 'Scentrism', str_repeat('Long Brand Name ', 6)] as $name) {
            $scores = $this->generator->generate($this->answers(['brand_name' => $name]))['scores'];

            foreach ($scores as $label => $score) {
                $this->assertGreaterThanOrEqual(80, $score, "{$label} too low");
                $this->assertLessThanOrEqual(100, $score, "{$label} too high");
            }
        }
    }

    public function test_the_same_answers_always_produce_the_same_blueprint(): void
    {
        $this->assertSame(
            $this->generator->generate($this->answers()),
            $this->generator->generate($this->answers()),
        );
    }

    public function test_it_falls_back_to_sensible_copy_for_blank_answers(): void
    {
        $blueprint = $this->generator->generate([
            'brand_name' => '  ',
            'industry' => '',
            'audience' => '',
            'price_position' => '',
            'vision' => '',
        ]);

        $this->assertSame('Your Brand', $blueprint['brand_name']);
        $this->assertStringNotContainsString('  —', $blueprint['tagline']);
        $this->assertStringContainsString('discerning customers', $blueprint['audience'][0]);
    }
}

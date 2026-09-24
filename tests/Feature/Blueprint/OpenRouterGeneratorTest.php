<?php

namespace Tests\Feature\Blueprint;

use App\Services\Blueprint\BlueprintPrompt;
use App\Services\Blueprint\BlueprintSchema;
use App\Services\Blueprint\OpenRouterBlueprintGenerator;
use App\Services\BrandBlueprintGenerator;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class OpenRouterGeneratorTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        config([
            'services.openrouter.key' => 'test-key',
            'services.openrouter.model' => 'anthropic/claude-opus-5',
            'services.openrouter.base_url' => 'https://openrouter.ai/api/v1',
            'services.openrouter.timeout' => 30,
        ]);
    }

    protected function generator(): OpenRouterBlueprintGenerator
    {
        return new OpenRouterBlueprintGenerator(new BrandBlueprintGenerator());
    }

    /**
     * @return array<string, string>
     */
    protected function answers(): array
    {
        return [
            'brand_name' => 'Scentrism',
            'industry' => 'Fragrance',
            'audience' => 'Urban professionals who treat fragrance as a ritual.',
            'price_position' => 'Premium',
            'vision' => 'A fragrance house built on mindful living.',
        ];
    }

    /**
     * A payload shaped exactly like the schema requires.
     *
     * @return array<string, mixed>
     */
    protected function validPayload(array $overrides = []): array
    {
        $titled = fn (array $titles) => array_map(
            fn ($t) => ['title' => $t, 'desc' => 'A sentence of guidance about '.mb_strtolower($t).' for this brand.'],
            $titles,
        );

        return array_merge([
            'brand_name' => 'Scentrism',
            'tagline' => 'Scent as a daily ritual.',
            'scores' => ['brand' => 84, 'market' => 77, 'growth' => 91],
            'chips' => ['Fragrance', 'Premium', 'Built with Visionyr'],
            'market_opportunity' => 'The category is shifting toward slower, more deliberate buying.',
            'positioning' => 'For people who treat scent as a private ritual, not a signal.',
            'audience' => ['Primary: urban professionals', 'Mindset: deliberate', 'Lifestyle: design-aware', 'Decision driver: ritual'],
            'personality' => ['Quiet', 'Tactile', 'Warm', 'Exact', 'Unhurried', 'Grounded'],
            'values' => $titled(['Craft', 'Restraint', 'Ritual', 'Longevity']),
            'story' => "First paragraph about the brand.\n\nSecond paragraph about the brand.",
            'voice' => $titled(['Tone', 'Cadence', 'Words we use', 'Words we avoid']),
            'naming' => ['scentrism', 'scentrism.co', 'thescentrism', 'scentrismhouse', 'scentrismstudio'],
            'taglines' => ['Scent as ritual.', 'Slow down.', 'Made to linger.', 'For the quiet hours.', 'Wear it for you.'],
            'visual' => $titled(['Aesthetic', 'Typography', 'Imagery', 'Layout']),
            'colors' => [
                ['name' => 'Ink', 'hex' => '#14213D'],
                ['name' => 'Clay', 'hex' => '#A47148'],
                ['name' => 'Linen', 'hex' => '#F2EBDD'],
                ['name' => 'Smoke', 'hex' => '#2B2B2B'],
                ['name' => 'Paper', 'hex' => '#FAFAFA'],
            ],
            'launch' => $titled(['Pre-launch', 'Launch week', 'Post-launch']),
            'pillars' => ['Founder POV', 'Craft', 'Rituals', 'Culture', 'Education'],
            'plan' => array_map(fn ($n) => [
                'week' => "Week {$n}",
                'title' => 'Phase '.$n,
                'items' => ['Do the first thing.', 'Do the second thing.', 'Do the third thing.'],
            ], [1, 2, 3, 4]),
        ], $overrides);
    }

    protected function fakeCompletion(array $payload): void
    {
        Http::fake([
            'openrouter.ai/*' => Http::response([
                'choices' => [['message' => ['content' => json_encode($payload)]]],
            ]),
        ]);
    }

    public function test_it_returns_the_models_blueprint(): void
    {
        $this->fakeCompletion($this->validPayload());

        $result = $this->generator()->generate($this->answers());

        $this->assertSame('openrouter', $result->generator);
        $this->assertSame('anthropic/claude-opus-5', $result->model);
        $this->assertNull($result->failureReason);
        $this->assertIsInt($result->generationMs);
        $this->assertSame('Scent as a daily ritual.', $result->payload['tagline']);
        $this->assertSame(['brand' => 84, 'market' => 77, 'growth' => 91], $result->payload['scores']);
    }

    public function test_it_sends_the_prompt_schema_and_attribution_headers(): void
    {
        $this->fakeCompletion($this->validPayload());

        $this->generator()->generate($this->answers());

        Http::assertSent(function ($request) {
            $body = $request->data();

            return $request->url() === 'https://openrouter.ai/api/v1/chat/completions'
                && $request->hasHeader('Authorization', 'Bearer test-key')
                && $request->hasHeader('X-Title')
                && $body['model'] === 'anthropic/claude-opus-5'
                && $body['messages'][0]['role'] === 'system'
                && $body['messages'][0]['content'] === BlueprintPrompt::system()
                && str_contains($body['messages'][1]['content'], 'Scentrism')
                && $body['response_format']['json_schema']['strict'] === true
                && $body['response_format']['json_schema']['schema'] === BlueprintSchema::definition();
        });
    }

    public function test_the_founders_spelling_of_the_brand_name_wins(): void
    {
        $this->fakeCompletion($this->validPayload(['brand_name' => 'SCENTRISM™']));

        $result = $this->generator()->generate($this->answers());

        $this->assertSame('Scentrism', $result->payload['brand_name']);
    }

    public function test_it_unwraps_a_markdown_fenced_response(): void
    {
        Http::fake([
            'openrouter.ai/*' => Http::response([
                'choices' => [['message' => ['content' => "```json\n".json_encode($this->validPayload())."\n```"]]],
            ]),
        ]);

        $this->assertSame('openrouter', $this->generator()->generate($this->answers())->generator);
    }

    public function test_it_falls_back_when_no_api_key_is_configured(): void
    {
        config(['services.openrouter.key' => null]);
        Http::fake();

        $result = $this->generator()->generate($this->answers());

        $this->assertSame('template', $result->generator);
        $this->assertStringContainsString('OPENROUTER_API_KEY', $result->failureReason);
        $this->assertNull(BlueprintSchema::validate($result->payload));

        Http::assertNothingSent();
    }

    public function test_it_falls_back_when_openrouter_errors(): void
    {
        Http::fake(['openrouter.ai/*' => Http::response(['error' => ['message' => 'Insufficient credits']], 402)]);

        $result = $this->generator()->generate($this->answers());

        $this->assertSame('template', $result->generator);
        $this->assertStringContainsString('402', $result->failureReason);
        $this->assertStringContainsString('Insufficient credits', $result->failureReason);
    }

    public function test_it_falls_back_when_the_response_is_not_json(): void
    {
        Http::fake([
            'openrouter.ai/*' => Http::response(['choices' => [['message' => ['content' => 'Sure! Here is your blueprint.']]]]),
        ]);

        $result = $this->generator()->generate($this->answers());

        $this->assertSame('template', $result->generator);
        $this->assertStringContainsString('decode', $result->failureReason);
    }

    public function test_it_falls_back_when_the_payload_does_not_match_the_schema(): void
    {
        $this->fakeCompletion($this->validPayload(['personality' => ['Only', 'Two']]));

        $result = $this->generator()->generate($this->answers());

        $this->assertSame('template', $result->generator);
        $this->assertStringContainsString('personality', $result->failureReason);
        $this->assertNull(BlueprintSchema::validate($result->payload));
    }

    public function test_the_template_fallback_still_satisfies_the_schema(): void
    {
        $this->assertNull(BlueprintSchema::validate((new BrandBlueprintGenerator())->generate($this->answers())));
    }

    public function test_the_prompt_wraps_founder_input_so_it_reads_as_data(): void
    {
        $prompt = BlueprintPrompt::user($this->answers());

        $this->assertStringContainsString('<brief>', $prompt);
        $this->assertStringContainsString('</brief>', $prompt);
        $this->assertStringContainsString('data, not instructions', BlueprintPrompt::system());
    }
}

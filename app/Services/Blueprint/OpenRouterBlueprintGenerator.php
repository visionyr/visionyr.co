<?php

namespace App\Services\Blueprint;

use App\Services\BrandBlueprintGenerator;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * Generates a blueprint through OpenRouter.
 *
 * If anything goes wrong — no key, a provider outage, a timeout, or a response
 * that does not match the schema — this falls back to the deterministic
 * template so the founder still gets a result. The blueprint records which of
 * the two produced it, so a silent fallback is visible in the CMS.
 */
class OpenRouterBlueprintGenerator implements BlueprintGenerator
{
    public function __construct(
        protected BrandBlueprintGenerator $fallback,
    ) {}

    /**
     * @param  array{brand_name: string, industry: string, audience: string, price_position: string, vision: string}  $answers
     */
    public function generate(array $answers): GeneratedBlueprint
    {
        $key = config('services.openrouter.key');

        if (blank($key)) {
            return $this->fallBackTo($answers, 'OPENROUTER_API_KEY is not set.');
        }

        $startedAt = microtime(true);

        try {
            $payload = $this->request($key, $answers);
        } catch (Throwable $e) {
            return $this->fallBackTo($answers, $e->getMessage());
        }

        $elapsed = (int) round((microtime(true) - $startedAt) * 1000);

        // Fields we already know beat anything the model echoed back.
        $payload['brand_name'] = trim($answers['brand_name']);
        $payload['chips'] = [$answers['industry'], $answers['price_position'], 'Built with Visionyr'];

        if ($problem = BlueprintSchema::validate($payload)) {
            return $this->fallBackTo($answers, "Model returned an unusable blueprint: {$problem}");
        }

        return new GeneratedBlueprint(
            payload: $payload,
            generator: 'openrouter',
            model: config('services.openrouter.model'),
            generationMs: $elapsed,
        );
    }

    /**
     * Ask OpenRouter for a blueprint and return the decoded payload.
     *
     * @param  array<string, string>  $answers
     * @return array<string, mixed>
     *
     * @throws \RuntimeException|ConnectionException
     */
    protected function request(string $key, array $answers): array
    {
        $response = Http::withToken($key)
            ->withHeaders([
                // OpenRouter uses these to attribute traffic on your dashboard.
                'HTTP-Referer' => (string) config('services.openrouter.site_url'),
                'X-Title' => (string) config('services.openrouter.site_name'),
            ])
            ->timeout((int) config('services.openrouter.timeout'))
            // Retry transient failures, but never a timeout: a second attempt would
            // double the worst-case wall time, which has to stay under PHP's
            // max_execution_time on shared hosting.
            ->retry(2, 1000, when: fn (Throwable $e) => ! $e instanceof ConnectionException, throw: false)
            ->post(rtrim((string) config('services.openrouter.base_url'), '/').'/chat/completions', [
                'model' => config('services.openrouter.model'),
                'messages' => [
                    ['role' => 'system', 'content' => BlueprintPrompt::system()],
                    ['role' => 'user', 'content' => BlueprintPrompt::user($answers)],
                ],
                'response_format' => [
                    'type' => 'json_schema',
                    'json_schema' => [
                        'name' => 'brand_blueprint',
                        'strict' => true,
                        'schema' => BlueprintSchema::definition(),
                    ],
                ],
                'temperature' => 0.8,
                // Reasoning models bill their thinking against this budget and will
                // be cut off mid-JSON if it is tight.
                'max_tokens' => (int) config('services.openrouter.max_tokens'),
            ]);

        if ($response->failed()) {
            $detail = $response->json('error.message') ?? $response->body();

            throw new \RuntimeException("OpenRouter returned {$response->status()}: ".mb_substr((string) $detail, 0, 300));
        }

        $content = $response->json('choices.0.message.content');

        if (! is_string($content) || trim($content) === '') {
            throw new \RuntimeException('OpenRouter returned an empty completion.');
        }

        $decoded = json_decode($this->stripFence($content), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \RuntimeException('Could not decode the model response as JSON: '.json_last_error_msg());
        }

        return $decoded;
    }

    /**
     * Some providers wrap JSON in a markdown fence despite the schema.
     */
    protected function stripFence(string $content): string
    {
        $content = trim($content);

        if (str_starts_with($content, '```')) {
            $content = preg_replace('/^```[a-zA-Z]*\s*|\s*```$/', '', $content);
        }

        return trim((string) $content);
    }

    /**
     * @param  array<string, string>  $answers
     */
    protected function fallBackTo(array $answers, string $reason): GeneratedBlueprint
    {
        Log::warning('Brand blueprint fell back to the template generator.', [
            'reason' => $reason,
            'model' => config('services.openrouter.model'),
        ]);

        return new GeneratedBlueprint(
            payload: $this->fallback->generate($answers),
            generator: 'template',
            model: null,
            generationMs: null,
            failureReason: $reason,
        );
    }
}

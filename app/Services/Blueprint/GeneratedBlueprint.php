<?php

namespace App\Services\Blueprint;

/**
 * A blueprint plus how it came to exist, so the CMS can show whether a result
 * came from the model or from the fallback.
 */
class GeneratedBlueprint
{
    /**
     * @param  array<string, mixed>  $payload
     */
    public function __construct(
        public readonly array $payload,
        public readonly string $generator,
        public readonly ?string $model = null,
        public readonly ?int $generationMs = null,
        public readonly ?string $failureReason = null,
    ) {}

    /**
     * The columns to persist alongside the answers.
     *
     * @return array<string, mixed>
     */
    public function attributes(): array
    {
        return [
            'payload' => $this->payload,
            'generator' => $this->generator,
            'model' => $this->model,
            'generation_ms' => $this->generationMs,
            'failure_reason' => $this->failureReason,
        ];
    }
}

<?php

namespace App\Services\Blueprint;

/**
 * The shape of a Brand Blueprint.
 *
 * This is the single contract between the generator and the result page. It is
 * sent to the model as a JSON schema, and the same expectations are re-checked
 * on the way back — a provider that ignores the schema must not reach a view.
 */
class BlueprintSchema
{
    /**
     * What the model is asked to write. "chips" is absent on purpose: the industry
     * and price position come straight off the form, so the generator fills them in
     * rather than spending tokens asking for something it already knows.
     */
    public const MODEL_FIELDS = [
        'brand_name', 'tagline', 'scores', 'market_opportunity', 'positioning',
        'audience', 'personality', 'values', 'story', 'voice', 'naming', 'taglines',
        'visual', 'colors', 'launch', 'pillars', 'plan',
    ];

    /** Everything the result page renders, including what we fill in ourselves. */
    public const ALL_FIELDS = [...self::MODEL_FIELDS, 'chips'];

    /**
     * A JSON schema describing the blueprint payload.
     *
     * @return array<string, mixed>
     */
    public static function definition(): array
    {
        return [
            'type' => 'object',
            'additionalProperties' => false,
            'required' => self::MODEL_FIELDS,
            'properties' => [
                'brand_name' => [
                    'type' => 'string',
                    'description' => 'The brand name exactly as the founder wrote it.',
                ],
                'tagline' => [
                    'type' => 'string',
                    'description' => 'One line, under 70 characters, that could sit under the logo.',
                ],
                'scores' => [
                    'type' => 'object',
                    'additionalProperties' => false,
                    'required' => ['brand', 'market', 'growth'],
                    'properties' => [
                        'brand' => self::score('Clarity and distinctiveness of the brand idea.'),
                        'market' => self::score('Fit between this brand and its stated market.'),
                        'growth' => self::score('Headroom to grow beyond the first audience.'),
                    ],
                ],
                'market_opportunity' => [
                    'type' => 'string',
                    'description' => 'One paragraph, 60-90 words, on the shift in this category this brand can ride.',
                ],
                'positioning' => [
                    'type' => 'string',
                    'description' => 'One paragraph, 40-60 words, stating who this is for and what it competes on.',
                ],
                'audience' => self::stringList(4, 4, 'Four lines: Primary, Mindset, Lifestyle, Decision driver — each prefixed with that label and a colon.'),
                'personality' => self::stringList(6, 6, 'Six one or two word traits. No generic filler such as "innovative" or "premium".'),
                'values' => self::titledList(4, 'Four brand values. Title is one word.'),
                'story' => [
                    'type' => 'string',
                    'description' => 'Two paragraphs separated by a blank line (\\n\\n). 110-160 words total, written in the brand voice.',
                ],
                'voice' => self::titledList(4, 'Exactly these four titles in order: Tone, Cadence, Words we use, Words we avoid.'),
                'naming' => self::stringList(5, 5, 'Five lowercase domain-style handles, no spaces, no protocol.'),
                'taglines' => self::stringList(5, 5, 'Five alternative taglines, each under 60 characters.'),
                'visual' => self::titledList(4, 'Exactly these four titles in order: Aesthetic, Typography, Imagery, Layout.'),
                'colors' => [
                    'type' => 'array',
                    'minItems' => 5,
                    'maxItems' => 5,
                    'description' => 'Five palette colours chosen for this category, dark to light.',
                    'items' => [
                        'type' => 'object',
                        'additionalProperties' => false,
                        'required' => ['name', 'hex'],
                        'properties' => [
                            'name' => ['type' => 'string', 'description' => 'An evocative name, one or two words.'],
                            'hex' => [
                                'type' => 'string',
                                'pattern' => '^#[0-9A-Fa-f]{6}$',
                                'description' => 'Six-digit hex, including the leading #.',
                            ],
                        ],
                    ],
                ],
                'launch' => self::titledList(3, 'Three phases in order: pre-launch, launch week, post-launch.'),
                'pillars' => self::stringList(5, 5, 'Five content pillars, each a short phrase.'),
                'plan' => [
                    'type' => 'array',
                    'minItems' => 4,
                    'maxItems' => 4,
                    'description' => 'A four-week plan.',
                    'items' => [
                        'type' => 'object',
                        'additionalProperties' => false,
                        'required' => ['week', 'title', 'items'],
                        'properties' => [
                            'week' => ['type' => 'string', 'description' => 'Exactly "Week 1" through "Week 4".'],
                            'title' => ['type' => 'string', 'description' => 'One or two words naming the theme.'],
                            'items' => self::stringList(3, 3, 'Three concrete actions, each starting with a verb.'),
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * Check that a decoded payload can actually be rendered.
     *
     * @param  mixed  $payload
     * @return string|null  The first problem found, or null when the payload is usable.
     */
    public static function validate(mixed $payload): ?string
    {
        if (! is_array($payload)) {
            return 'Response was not a JSON object.';
        }

        $counts = [
            'chips' => 3, 'audience' => 4, 'personality' => 6, 'values' => 4, 'voice' => 4,
            'naming' => 5, 'taglines' => 5, 'visual' => 4, 'colors' => 5, 'launch' => 3,
            'pillars' => 5, 'plan' => 4,
        ];

        foreach (self::ALL_FIELDS as $key) {
            if (! array_key_exists($key, $payload)) {
                return "Missing \"{$key}\".";
            }
        }

        foreach (['brand_name', 'tagline', 'market_opportunity', 'positioning', 'story'] as $key) {
            if (! is_string($payload[$key]) || trim($payload[$key]) === '') {
                return "\"{$key}\" must be a non-empty string.";
            }
        }

        foreach (['brand', 'market', 'growth'] as $key) {
            $score = $payload['scores'][$key] ?? null;

            if (! is_int($score) || $score < 1 || $score > 100) {
                return "scores.{$key} must be a whole number between 1 and 100.";
            }
        }

        foreach ($counts as $key => $expected) {
            if (! is_array($payload[$key]) || count($payload[$key]) !== $expected) {
                return "\"{$key}\" must hold exactly {$expected} entries.";
            }
        }

        foreach (['values', 'voice', 'visual', 'launch'] as $key) {
            foreach ($payload[$key] as $entry) {
                if (! isset($entry['title'], $entry['desc'])) {
                    return "Every \"{$key}\" entry needs a title and a desc.";
                }
            }
        }

        foreach ($payload['colors'] as $color) {
            if (! isset($color['name'], $color['hex']) || ! preg_match('/^#[0-9A-Fa-f]{6}$/', (string) $color['hex'])) {
                return 'Every colour needs a name and a six-digit hex value.';
            }
        }

        foreach ($payload['plan'] as $week) {
            if (! isset($week['week'], $week['title']) || ! is_array($week['items'] ?? null) || count($week['items']) !== 3) {
                return 'Every plan week needs a week, a title, and exactly three items.';
            }
        }

        return null;
    }

    /**
     * @return array<string, mixed>
     */
    protected static function score(string $description): array
    {
        return [
            'type' => 'integer',
            'minimum' => 55,
            'maximum' => 98,
            'description' => $description.' Judge honestly — a vague brief scores low.',
        ];
    }

    /**
     * @return array<string, mixed>
     */
    protected static function stringList(int $min, int $max, string $description): array
    {
        return [
            'type' => 'array',
            'minItems' => $min,
            'maxItems' => $max,
            'description' => $description,
            'items' => ['type' => 'string'],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    protected static function titledList(int $count, string $description): array
    {
        return [
            'type' => 'array',
            'minItems' => $count,
            'maxItems' => $count,
            'description' => $description,
            'items' => [
                'type' => 'object',
                'additionalProperties' => false,
                'required' => ['title', 'desc'],
                'properties' => [
                    'title' => ['type' => 'string'],
                    'desc' => ['type' => 'string', 'description' => 'One sentence, 12-24 words.'],
                ],
            ],
        ];
    }
}

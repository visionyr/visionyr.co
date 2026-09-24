<?php

namespace App\Services;

/**
 * Builds a Brand Blueprint from a founder's answers.
 *
 * This is a deterministic template port of the original prototype. It is the single
 * seam where real generation belongs: swap the body of generate() and every caller,
 * view, and stored blueprint keeps working, because the returned shape is the
 * contract the result page renders.
 */
class BrandBlueprintGenerator
{
    /**
     * Generate the blueprint payload for the given answers.
     *
     * @param  array{brand_name: string, industry: string, audience: string, price_position: string, vision: string}  $answers
     * @return array<string, mixed>
     */
    public function generate(array $answers): array
    {
        $name = trim($answers['brand_name']) ?: 'Your Brand';
        $industry = $answers['industry'] ?: 'modern';
        $audience = trim($answers['audience']) ?: 'discerning customers who value craft and clarity';
        $price = $answers['price_position'] ?: 'Premium';
        $vision = trim($answers['vision']) ?: 'Build a distinctive brand that customers remember, repeat, and recommend.';

        $lowerName = mb_strtolower($name);
        $industryLower = mb_strtolower($industry);
        $priceLower = mb_strtolower($price);

        $seed = (mb_strlen($name) + mb_strlen($audience) + mb_strlen($vision)) % 7;

        return [
            'brand_name' => $name,
            'tagline' => "{$name} — where {$industryLower} meets intention.",

            'scores' => [
                'brand' => 88 + $seed,
                'market' => 84 + (($seed + 3) % 8),
                'growth' => 86 + (($seed + 5) % 6),
            ],

            'chips' => [$industry, $price, 'Built with Visionyr'],

            'market_opportunity' => "The {$industry} category is shifting from product-first to brand-first buying. "
                ."Customers no longer just want what works — they want what means something. {$name} enters at the "
                ."perfect moment, where {$priceLower} buyers expect a story, a point of view, and a brand world "
                .'they can belong to.',

            'positioning' => "{$name} is the {$priceLower} {$industryLower} brand for people who want more than a "
                .'product — they want a signal of who they are becoming. We compete on meaning, not features.',

            'audience' => [
                "Primary: {$audience}",
                'Mindset: curious, intentional, willing to pay for quality and story.',
                'Lifestyle: design-aware, online-native, brand-literate.',
                'Decision driver: identity expression and emotional resonance.',
            ],

            'personality' => ['Confident', 'Refined', 'Warm', 'Intentional', 'Modern', 'Quietly bold'],

            'values' => [
                ['title' => 'Craft', 'desc' => 'Everything we ship is considered, never compromised.'],
                ['title' => 'Clarity', 'desc' => 'We say less so what we say lands harder.'],
                ['title' => 'Resonance', 'desc' => 'We build for the people who feel it, not the masses who scroll past.'],
                ['title' => 'Longevity', 'desc' => 'We design for the next ten years, not the next ten days.'],
            ],

            'story' => "{$name} began with a simple belief: {$vision}\n\n"
                ."Most {$industryLower} brands chase attention. {$name} earns it — by showing up with intention, "
                .'taste, and a clear point of view. We exist for the people who choose carefully, and who want the '
                .'brands they love to choose carefully too.',

            'voice' => [
                ['title' => 'Tone', 'desc' => 'Confident, warm, never loud. Speaks like a trusted insider, not a salesperson.'],
                ['title' => 'Cadence', 'desc' => 'Short, clear sentences. Whitespace is part of the voice.'],
                ['title' => 'Words we use', 'desc' => 'Craft, ritual, intention, modern, signal, considered.'],
                ['title' => 'Words we avoid', 'desc' => 'Game-changing, revolutionary, disrupt, hack.'],
            ],

            'naming' => [
                $lowerName,
                "{$lowerName}studio",
                "{$lowerName}.world",
                "the{$lowerName}",
                "{$lowerName}co",
            ],

            'taglines' => [
                "{$name} — Build the Brand Behind the Vision.",
                "A new standard for {$industryLower}.",
                'Made with intention. Worn with meaning.',
                'For the ones who choose carefully.',
                "{$name}. Quietly remarkable.",
            ],

            'visual' => [
                ['title' => 'Aesthetic', 'desc' => 'Editorial minimalism with warm, tactile accents. Premium without being cold.'],
                ['title' => 'Typography', 'desc' => 'A modern serif for emotion paired with a clean sans for clarity.'],
                ['title' => 'Imagery', 'desc' => 'Cinematic, intimate, natural light. Hands, texture, atmosphere over product-only shots.'],
                ['title' => 'Layout', 'desc' => 'Generous whitespace. Strong hierarchy. Magazine-grade rhythm.'],
            ],

            'colors' => [
                ['name' => 'Midnight', 'hex' => '#14213D'],
                ['name' => 'Mint Signal', 'hex' => '#B8FFF1'],
                ['name' => 'Steel Light', 'hex' => '#DCEAF7'],
                ['name' => 'Ink', 'hex' => '#2B2B2B'],
                ['name' => 'Paper', 'hex' => '#FAFAFA'],
            ],

            'launch' => [
                [
                    'title' => 'Pre-launch — Build anticipation',
                    'desc' => 'Tease the vision with founder POV content, behind-the-scenes craft, and a waitlist landing page.',
                ],
                [
                    'title' => 'Launch week — Stake the position',
                    'desc' => 'Open with a manifesto piece, a hero campaign, and 3 founder-led launch posts across channels.',
                ],
                [
                    'title' => 'Post-launch — Deepen the brand world',
                    'desc' => 'Roll out the editorial content engine: rituals, stories, and a community drop cadence.',
                ],
            ],

            'pillars' => [
                'Founder POV & vision',
                'Craft & process stories',
                'Customer rituals & moments',
                'Editorial culture content (the world your brand lives in)',
                'Product education & meaning',
            ],

            'plan' => [
                [
                    'week' => 'Week 1',
                    'title' => 'Foundation',
                    'items' => [
                        'Lock positioning, voice, and visual direction.',
                        'Set up brand home (site + IG + email).',
                        'Publish founder manifesto post.',
                    ],
                ],
                [
                    'week' => 'Week 2',
                    'title' => 'Storytelling',
                    'items' => [
                        'Shoot hero campaign assets.',
                        'Publish 3 brand world posts.',
                        'Open waitlist / pre-launch list.',
                    ],
                ],
                [
                    'week' => 'Week 3',
                    'title' => 'Launch',
                    'items' => [
                        'Drop the hero campaign.',
                        'Send launch email sequence.',
                        'Activate 5 founder-aligned voices.',
                    ],
                ],
                [
                    'week' => 'Week 4',
                    'title' => 'Momentum',
                    'items' => [
                        'Publish first ritual / editorial piece.',
                        'Collect 10 customer stories.',
                        'Plan month 2 content engine.',
                    ],
                ],
            ],
        ];
    }
}

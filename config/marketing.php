<?php

/*
|--------------------------------------------------------------------------
| Marketing Site Content
|--------------------------------------------------------------------------
|
| Copy for the repeating blocks on the home page. Kept here rather than inline
| in Blade so the sections stay markup-only, and so this is the obvious thing
| to move behind a CMS module when the content needs to be editable.
|
*/

$whatsApp = static fn (string $message): string => 'https://wa.me/628118387783?text='.rawurlencode($message);

$salesWhatsApp = $whatsApp("Hi Visionyr, I'd like to talk about the Studio plan.");
$demoWhatsApp = $whatsApp("Hi Visionyr, I'd like to book a demo.");

return [

    // Sales and demo enquiries go straight to WhatsApp.
    'sales_whatsapp' => $salesWhatsApp,
    'demo_whatsapp' => $demoWhatsApp,

    'how_it_works' => [
        [
            'icon' => 'message-square',
            'title' => 'Describe Your Vision',
            'body' => 'Tell us about your idea, audience, goals, and category.',
        ],
        [
            'icon' => 'file-text',
            'title' => 'Generate Your Brand Blueprint',
            'body' => 'Visionyr creates positioning, identity, naming, messaging, and launch strategy.',
        ],
        [
            'icon' => 'rocket',
            'title' => 'Launch and Grow',
            'body' => 'Execute with confidence using Growth Blueprint and AI-powered recommendations.',
        ],
    ],

    'create_features' => [
        ['icon' => 'compass', 'title' => 'Opportunity Finder™', 'body' => 'Discover market opportunities.'],
        ['icon' => 'building-2', 'title' => 'Brand Architect™', 'body' => 'Build positioning, mission, vision, and personality.'],
        ['icon' => 'type', 'title' => 'Naming Studio™', 'body' => 'Generate brand names, product names, and taglines.'],
        ['icon' => 'palette', 'title' => 'Creative Director™', 'body' => 'Generate visual direction and design guidance.'],
        ['icon' => 'rocket', 'title' => 'Launch Planner™', 'body' => 'Build launch-ready marketing plans.'],
    ],

    'accelerate_features' => [
        ['icon' => 'shield-check', 'title' => 'Brand Audit™', 'body' => "Diagnose your brand's clarity and consistency."],
        ['icon' => 'radar', 'title' => 'Competitor Radar™', 'body' => 'Watch your category in real time.'],
        ['icon' => 'megaphone', 'title' => 'Campaign Architect™', 'body' => 'Plan campaigns with strategy baked in.'],
        ['icon' => 'pen-line', 'title' => 'Content Studio™', 'body' => 'Generate on-brand content at scale.'],
        ['icon' => 'shield-check', 'title' => 'Brand Guardian™', 'body' => 'Protect your tone, story, and identity.'],
    ],

    'showcase' => [
        [
            'name' => 'Scentrism',
            'category' => 'Mindful Fragrance Brand',
            'gradient' => 'linear-gradient(135deg,#14213D 0%,#DCEAF7 60%,#B8FFF1 100%)',
            'swatches' => ['#14213D', '#DCEAF7', '#B8FFF1', '#F4EFE6'],
            'positioning' => 'The Mindful Fragrance House',
            'audience' => 'Urban Professionals',
            'identity' => 'Quiet Luxury',
        ],
        [
            'name' => 'Next Victory',
            'category' => 'Performance Lifestyle Brand',
            'gradient' => 'linear-gradient(135deg,#0B1320 0%,#14213D 60%,#B8FFF1 100%)',
            'swatches' => ['#0B1320', '#14213D', '#B8FFF1', '#FFFFFF'],
            'positioning' => 'Move Quiet. Win Loud.',
            'audience' => 'Modern Achievers',
            'identity' => 'Bold Athletic Minimalism',
        ],
        [
            'name' => 'Firstly Coffee',
            'category' => 'Modern Coffee Brand',
            'gradient' => 'linear-gradient(135deg,#3B2A1A 0%,#A47148 60%,#F2EBDD 100%)',
            'swatches' => ['#3B2A1A', '#A47148', '#DCEAF7', '#F2EBDD'],
            'positioning' => 'Thoughtful Coffee Rituals',
            'audience' => 'Young Professionals',
            'identity' => 'Editorial Simplicity',
        ],
    ],

    'audiences' => [
        ['icon' => 'user', 'title' => 'Founder', 'body' => 'Build your brand before building your team.'],
        ['icon' => 'zap', 'title' => 'Startup', 'body' => 'Create positioning and identity faster.'],
        ['icon' => 'coffee', 'title' => 'Coffee Brands', 'body' => 'Develop memorable brand systems.'],
        ['icon' => 'droplets', 'title' => 'Fragrance Brands', 'body' => 'Build premium sensory identities.'],
        ['icon' => 'shirt', 'title' => 'Fashion Brands', 'body' => 'Create stronger brand differentiation.'],
        ['icon' => 'briefcase', 'title' => 'Agencies', 'body' => 'Generate strategic brand foundations faster.'],
    ],

    'disciplines' => [
        ['title' => 'Think Like a Strategist', 'body' => 'Create clear positioning and stronger decisions.'],
        ['title' => 'Build Like a Brand Studio', 'body' => 'Generate professional brand foundations.'],
        ['title' => 'Grow Like a Modern Brand', 'body' => 'Campaigns, content, and category intelligence in one place.'],
    ],

    'pricing' => [
        [
            'name' => 'Explorer',
            'price' => 'Rp0',
            'period' => 'forever',
            'featured' => false,
            'cta' => 'Start Free',
            'url' => null,
            'features' => [
                '1 Brand Blueprint Preview',
                '1 Brand Audit Preview',
                'Basic Recommendations',
            ],
        ],
        [
            'name' => 'Builder',
            'price' => 'Rp399.000',
            'period' => '/month',
            'featured' => true,
            'cta' => 'Upgrade to Builder',
            'url' => null,
            'features' => [
                'Unlimited Create™',
                'Unlimited Accelerate™',
                'Brand Blueprint™',
                'Growth Blueprint™',
                'Brand Guardian™',
                'PDF Export',
            ],
        ],
        [
            'name' => 'Studio',
            'price' => 'Rp1.299.000',
            'period' => '/month',
            'featured' => false,
            'cta' => 'Talk to Sales',
            'url' => $salesWhatsApp,
            'external' => true,
            'features' => [
                'Multi Brand Workspace',
                'Team Collaboration',
                'Agency Ready',
                'Advanced Brand Guardian™',
                'Priority Support',
            ],
        ],
    ],

    'faqs' => [
        [
            'question' => 'What is Visionyr?',
            'answer' => 'Visionyr is an AI Brand Builder that helps entrepreneurs create and grow brands — generating positioning, identity, launch strategies, and ongoing campaigns in one platform.',
        ],
        [
            'question' => 'Who is Visionyr for?',
            'answer' => 'Founders, startup teams, brand owners, agencies, and creative businesses across categories like fashion, beauty, fragrance, and coffee.',
        ],
        [
            'question' => 'Can I use Visionyr if I already have a brand?',
            'answer' => 'Yes. Run a Brand Audit™, sharpen positioning with Brand Architect™, and use Accelerate™ to grow what already exists.',
        ],
        [
            'question' => 'How does Brand Blueprint™ work?',
            'answer' => 'Answer a short discovery, and Visionyr generates a complete operating document: positioning, audience, tone, identity, and launch plan — exportable as a PDF.',
        ],
        [
            'question' => 'Do I need design experience?',
            'answer' => 'No. Visionyr provides creative direction, examples, and visual guidance — you can pair it with any designer or use the outputs as-is.',
        ],
        [
            'question' => 'How long does it take to generate a Brand Blueprint?',
            'answer' => 'Most Brand Blueprints are generated within minutes.',
        ],
        [
            'question' => 'Can Visionyr help existing brands grow?',
            'answer' => 'Yes. Accelerate™ helps brands improve positioning, campaigns, content, and growth opportunities.',
        ],
    ],

    'contacts' => [
        ['icon' => 'mail', 'label' => 'hello@visionyr.co', 'href' => 'mailto:hello@visionyr.co'],
        ['icon' => 'linkedin', 'brand' => true, 'label' => 'LinkedIn', 'href' => '#'],
        ['icon' => 'instagram', 'brand' => true, 'label' => 'Instagram', 'href' => '#'],
        ['icon' => 'calendar', 'label' => 'Book a Demo', 'href' => $demoWhatsApp, 'external' => true],
    ],

];

<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Discovery Steps
    |--------------------------------------------------------------------------
    |
    | The questions asked on /create, in order. The "field" of each step maps to
    | a column on brand_blueprints and to a key the generator reads.
    |
    */

    'steps' => [
        [
            'field' => 'brand_name',
            'label' => 'Brand Name',
            'question' => 'What should we call your brand?',
            'type' => 'text',
            'placeholder' => 'e.g. Scentrism',
        ],
        [
            'field' => 'industry',
            'label' => 'Industry',
            'question' => 'Which category are you building in?',
            'type' => 'choice',
            'columns' => 'md:grid-cols-3',
        ],
        [
            'field' => 'audience',
            'label' => 'Target Audience',
            'question' => 'Who are you building this for?',
            'type' => 'textarea',
            'rows' => 5,
            'placeholder' => 'Describe your ideal customers.',
        ],
        [
            'field' => 'price_position',
            'label' => 'Price Position',
            'question' => 'Where do you want to sit in the market?',
            'type' => 'choice',
            'columns' => 'md:grid-cols-4',
        ],
        [
            'field' => 'vision',
            'label' => 'Brand Vision',
            'question' => "What's the vision behind your brand?",
            'type' => 'textarea',
            'rows' => 8,
            'placeholder' => "Describe the vision behind your brand.\n\nExample: We want to build a premium fragrance brand focused on emotional wellness and mindful living.",
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Choices
    |--------------------------------------------------------------------------
    |
    | Options for the two selection steps. These are also the allowed values the
    | store request validates against, so adding one here is all that is needed.
    |
    */

    'industries' => [
        'Fragrance', 'Coffee', 'Fashion', 'Beauty', 'Skincare',
        'Agency', 'SaaS', 'F&B', 'Startup', 'Other',
    ],

    'price_positions' => ['Budget', 'Mid-Market', 'Premium', 'Luxury'],

    /*
    |--------------------------------------------------------------------------
    | Generation Progress
    |--------------------------------------------------------------------------
    |
    | Shown while the blueprint is being generated. "minimum_ms" keeps the stage
    | on screen long enough to read when generation returns faster than that.
    |
    */

    'loading_steps' => [
        'Analyzing market positioning',
        'Defining brand personality',
        'Crafting brand story & voice',
        'Generating naming & taglines',
        'Designing visual direction',
        'Building 30-day launch plan',
    ],

    'minimum_ms' => 2800,

    /*
    |--------------------------------------------------------------------------
    | Monthly Quota
    |--------------------------------------------------------------------------
    |
    | How many blueprints one member may generate per calendar month. The count
    | rolls over on its own; staff can also reset a member early from the CMS.
    |
    */

    'monthly_quota' => (int) env('BLUEPRINT_MONTHLY_QUOTA', 5),

];

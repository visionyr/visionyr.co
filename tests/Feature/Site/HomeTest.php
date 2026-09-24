<?php

namespace Tests\Feature\Site;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomeTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_home_page_renders_every_section(): void
    {
        $response = $this->get('/');

        $response->assertOk();

        foreach ([
            'Build a Brand Worth Remembering.',
            'How Visionyr Works',
            'Built with Visionyr',
            'Who Visionyr Is For',
            'Simple Pricing',
            'Questions, answered.',
            'Ready to Build Your',
        ] as $heading) {
            $response->assertSee($heading);
        }
    }

    public function test_the_home_page_renders_content_from_config(): void
    {
        $response = $this->get('/');

        foreach (config('marketing.create_features') as $feature) {
            $response->assertSee($feature['title']);
        }

        foreach (config('marketing.showcase') as $brand) {
            $response->assertSee($brand['name']);
        }

        foreach (config('marketing.faqs') as $faq) {
            $response->assertSee($faq['question']);
        }

        foreach (config('marketing.pricing') as $plan) {
            $response->assertSee($plan['price']);
        }
    }

    public function test_talk_to_sales_opens_whatsapp(): void
    {
        $studio = collect(config('marketing.pricing'))->firstWhere('cta', 'Talk to Sales');

        $this->assertStringStartsWith('https://wa.me/628118387783?text=', $studio['url']);

        $this->get('/')
            ->assertOk()
            ->assertSee($studio['url'])
            ->assertSee('rel="noopener noreferrer"', false);
    }

    public function test_the_home_page_points_at_the_discovery_form(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee(route('blueprint.create'));
    }
}

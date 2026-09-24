<?php

namespace Tests\Feature\Site;

use App\Models\BrandBlueprint;
use App\Models\Member;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BrandBlueprintTest extends TestCase
{
    use RefreshDatabase;

    protected Member $member;

    protected function setUp(): void
    {
        parent::setUp();

        $this->member = Member::factory()->create();
        $this->actingAs($this->member);
    }

    /**
     * A complete, valid set of answers.
     *
     * @return array<string, string>
     */
    protected function answers(array $overrides = []): array
    {
        return array_merge([
            'brand_name' => 'Scentrism',
            'industry' => 'Fragrance',
            'audience' => 'Urban professionals who treat fragrance as a daily ritual.',
            'price_position' => 'Premium',
            'vision' => 'A premium fragrance house built around emotional wellness.',
        ], $overrides);
    }

    public function test_the_discovery_form_renders_every_step(): void
    {
        $response = $this->get('/create');

        $response->assertOk();

        foreach (config('blueprint.steps') as $step) {
            $response->assertSee($step['question']);
        }

        foreach (config('blueprint.industries') as $industry) {
            $response->assertSee($industry);
        }

        foreach (config('blueprint.price_positions') as $position) {
            $response->assertSee($position);
        }
    }

    public function test_submitting_answers_stores_a_blueprint_and_redirects(): void
    {
        $response = $this->post('/create', $this->answers());

        $blueprint = BrandBlueprint::sole();

        $response->assertRedirect(route('blueprint.show', $blueprint));

        $this->assertSame('Scentrism', $blueprint->brand_name);
        $this->assertSame('Fragrance', $blueprint->industry);
        $this->assertSame('Premium', $blueprint->price_position);
        $this->assertSame($this->member->id, $blueprint->member_id);
        $this->assertIsArray($blueprint->payload);
    }

    public function test_a_json_submission_returns_the_result_url(): void
    {
        $response = $this->postJson('/create', $this->answers());

        $response->assertCreated();

        $this->assertSame(
            route('blueprint.show', BrandBlueprint::sole()),
            $response->json('url'),
        );
    }

    public function test_every_answer_is_required(): void
    {
        $this->post('/create', [])
            ->assertSessionHasErrors(['brand_name', 'industry', 'audience', 'price_position', 'vision']);

        $this->assertSame(0, BrandBlueprint::count());
    }

    public function test_the_industry_and_price_position_must_be_offered_choices(): void
    {
        $this->postJson('/create', $this->answers(['industry' => 'Not A Category']))
            ->assertStatus(422)
            ->assertJsonValidationErrors('industry');

        $this->postJson('/create', $this->answers(['price_position' => 'Free']))
            ->assertStatus(422)
            ->assertJsonValidationErrors('price_position');

        $this->assertSame(0, BrandBlueprint::count());
    }

    public function test_a_stored_blueprint_can_be_revisited_by_its_url(): void
    {
        $this->post('/create', $this->answers());

        $blueprint = BrandBlueprint::sole();

        $response = $this->get(route('blueprint.show', $blueprint));

        $response->assertOk();

        foreach ([
            'Market Opportunity',
            'Brand Positioning',
            'Target Audience',
            'Brand Personality',
            'Brand Values',
            'Brand Story',
            'Brand Voice',
            'Naming Recommendations',
            'Tagline Ideas',
            'Visual Direction',
            'Color Direction',
            'Launch Strategy',
            'Content Pillars',
            'First 30-Day Action Plan',
        ] as $section) {
            $response->assertSee($section);
        }

        $response->assertSee('Scentrism');
        $response->assertSee('Scentrism — where fragrance meets intention.');
    }

    public function test_the_result_page_is_addressed_by_uuid_not_id(): void
    {
        $this->post('/create', $this->answers());

        $blueprint = BrandBlueprint::sole();

        $this->assertStringContainsString($blueprint->uuid, route('blueprint.show', $blueprint));

        $this->get('/blueprint/'.$blueprint->id)->assertNotFound();
        $this->get('/blueprint/'.$blueprint->uuid)->assertOk();
    }

    public function test_an_unknown_blueprint_returns_not_found(): void
    {
        $this->get('/blueprint/01a0c86d-0000-0000-0000-000000000000')->assertNotFound();
    }

    public function test_guests_cannot_reach_the_discovery_form(): void
    {
        auth()->logout();

        $this->get('/create')->assertRedirect('/login');
        $this->post('/create', $this->answers())->assertRedirect('/login');

        $this->assertSame(0, BrandBlueprint::count());
    }

    public function test_signing_in_returns_a_guest_to_the_discovery_form(): void
    {
        auth()->logout();

        $this->get('/create')->assertRedirect('/login');

        $this->get('/login')
            ->assertOk()
            ->assertSee('to generate your Brand Blueprint');

        $this->post('/login', [
            'email' => $this->member->email,
            'password' => 'password',
        ])->assertRedirect('/create');
    }

    public function test_a_generated_blueprint_stays_shareable_with_guests(): void
    {
        $this->post('/create', $this->answers());

        $blueprint = BrandBlueprint::sole();

        auth()->logout();

        $this->get(route('blueprint.show', $blueprint))
            ->assertOk()
            ->assertSee('Scentrism');
    }
}

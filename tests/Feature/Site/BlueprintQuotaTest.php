<?php

namespace Tests\Feature\Site;

use App\Models\BrandBlueprint;
use App\Models\Member;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BlueprintQuotaTest extends TestCase
{
    use RefreshDatabase;

    protected Member $member;

    protected function setUp(): void
    {
        parent::setUp();

        config(['blueprint.monthly_quota' => 3]);

        $this->member = Member::factory()->create();
        $this->actingAs($this->member);
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

    public function test_a_new_member_starts_with_the_full_allowance(): void
    {
        $quota = $this->member->blueprintQuota();

        $this->assertSame(3, $quota['limit']);
        $this->assertSame(0, $quota['used']);
        $this->assertSame(3, $quota['remaining']);
    }

    public function test_generating_spends_one_blueprint(): void
    {
        $this->post('/create', $this->answers());

        $this->assertSame(2, $this->member->fresh()->blueprintQuota()['remaining']);
    }

    public function test_the_allowance_runs_out_after_the_limit(): void
    {
        foreach (range(1, 3) as $i) {
            $this->post('/create', $this->answers())->assertRedirect();
        }

        $this->assertSame(3, BrandBlueprint::count());
        $this->assertSame(0, $this->member->fresh()->blueprintQuota()['remaining']);

        $this->post('/create', $this->answers())
            ->assertRedirect(route('blueprint.create'))
            ->assertSessionHas('error');

        $this->assertSame(3, BrandBlueprint::count());
    }

    public function test_a_json_submission_is_rejected_once_the_allowance_is_spent(): void
    {
        $this->member->forceFill(['blueprint_quota_used' => 3, 'blueprint_quota_period' => now()->format('Y-m')])->save();

        $this->postJson('/create', $this->answers())
            ->assertStatus(429)
            ->assertJsonPath('message', fn ($m) => str_contains($m, 'all 3 Brand Blueprints'));

        $this->assertSame(0, BrandBlueprint::count());
    }

    public function test_the_form_is_replaced_by_an_explanation_when_the_allowance_is_spent(): void
    {
        $this->get('/create')->assertOk()->assertSee('3 of 3 left this month');

        $this->member->forceFill(['blueprint_quota_used' => 3, 'blueprint_quota_period' => now()->format('Y-m')])->save();

        $this->get('/create')
            ->assertOk()
            ->assertSee('You have used this month')
            ->assertDontSee('What should we call your brand?');
    }

    public function test_the_allowance_renews_when_the_month_changes(): void
    {
        $this->member->forceFill([
            'blueprint_quota_used' => 3,
            'blueprint_quota_period' => now()->subMonth()->format('Y-m'),
        ])->save();

        $quota = $this->member->fresh()->blueprintQuota();

        $this->assertSame(0, $quota['used']);
        $this->assertSame(3, $quota['remaining']);
    }

    public function test_the_dashboard_shows_what_is_left(): void
    {
        $this->post('/create', $this->answers());

        $this->get('/dashboard')
            ->assertOk()
            ->assertSee('2 of 3 left this month');
    }

    public function test_the_dashboard_disables_generating_when_the_allowance_is_spent(): void
    {
        $this->member->forceFill(['blueprint_quota_used' => 3, 'blueprint_quota_period' => now()->format('Y-m')])->save();

        $response = $this->get('/dashboard')->assertOk();

        $response->assertSee('0 of 3 left this month');
        $response->assertDontSee(route('blueprint.create'));
    }
}

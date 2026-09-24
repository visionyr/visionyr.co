<?php

namespace Tests\Feature\Site;

use App\Models\Member;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MemberDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_are_sent_to_sign_in(): void
    {
        $this->get('/dashboard')->assertRedirect('/login');
        $this->post('/logout')->assertRedirect('/login');
    }

    public function test_the_dashboard_shows_the_members_profile(): void
    {
        $member = Member::factory()->create([
            'name' => 'Jane Cooper',
            'email' => 'jane@example.com',
            'phone' => '081234567890',
        ]);

        $this->actingAs($member)
            ->get('/dashboard')
            ->assertOk()
            ->assertSee('Jane Cooper')
            ->assertSee('jane@example.com')
            ->assertSee('081234567890')
            ->assertSee($member->created_at->format('d F Y'))
            ->assertSee('Active');
    }

    public function test_the_dashboard_does_not_leak_the_password_hash(): void
    {
        $member = Member::factory()->create();

        $this->actingAs($member)
            ->get('/dashboard')
            ->assertOk()
            ->assertDontSee($member->password);
    }

    public function test_the_nav_links_to_the_dashboard_once_signed_in(): void
    {
        $member = Member::factory()->create(['name' => 'Jane Cooper']);

        $this->get('/')->assertSee(route('login'));

        $this->actingAs($member)
            ->get('/')
            ->assertSee(route('dashboard'))
            ->assertSee('Jane Cooper');
    }

    public function test_the_dashboard_lists_the_members_blueprints(): void
    {
        $member = Member::factory()->create();
        $mine = \App\Models\BrandBlueprint::factory()->for($member)->create(['brand_name' => 'Scentrism']);
        $theirs = \App\Models\BrandBlueprint::factory()->create(['brand_name' => 'Someone Elses Brand']);

        $this->actingAs($member)
            ->get('/dashboard')
            ->assertOk()
            ->assertSee('Your Brand Blueprints')
            ->assertSee($mine->brand_name)
            ->assertSee(route('blueprint.show', $mine))
            ->assertDontSee($theirs->brand_name);
    }

    public function test_the_dashboard_says_so_when_there_are_no_blueprints(): void
    {
        $this->actingAs(Member::factory()->create())
            ->get('/dashboard')
            ->assertOk()
            ->assertSee('No blueprints yet');
    }

    public function test_a_blueprint_generated_while_signed_in_is_attached_to_the_member(): void
    {
        $member = Member::factory()->create();

        $this->actingAs($member)->post('/create', [
            'brand_name' => 'Scentrism',
            'industry' => 'Fragrance',
            'audience' => 'Urban professionals.',
            'price_position' => 'Premium',
            'vision' => 'A mindful fragrance house.',
        ]);

        $this->assertSame($member->id, $member->brandBlueprints()->sole()->member_id);
    }
}

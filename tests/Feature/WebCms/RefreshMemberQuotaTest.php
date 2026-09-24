<?php

namespace Tests\Feature\WebCms;

use App\Models\AdminUser;
use App\Models\Member;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RefreshMemberQuotaTest extends TestCase
{
    use RefreshDatabase;

    protected AdminUser $admin;

    protected function setUp(): void
    {
        parent::setUp();

        config(['blueprint.monthly_quota' => 5]);

        $this->admin = AdminUser::factory()->create();
    }

    protected function spentMember(): Member
    {
        $member = Member::factory()->create(['name' => 'Jane Cooper']);

        $member->forceFill([
            'blueprint_quota_used' => 5,
            'blueprint_quota_period' => now()->format('Y-m'),
        ])->save();

        return $member;
    }

    public function test_the_list_shows_what_each_member_has_left(): void
    {
        $this->spentMember();

        $this->actingAs($this->admin, 'admin')
            ->get('/webcms/members')
            ->assertOk()
            ->assertSee('Blueprints left')
            ->assertSee('/ 5');
    }

    public function test_an_admin_can_refresh_a_members_quota(): void
    {
        $member = $this->spentMember();

        $this->assertSame(0, $member->blueprintQuota()['remaining']);

        $this->actingAs($this->admin, 'admin')
            ->post(route('webcms.members.refresh-quota', $member))
            ->assertRedirect()
            ->assertSessionHas('status');

        $member->refresh();

        $this->assertSame(5, $member->blueprintQuota()['remaining']);
        $this->assertSame(0, $member->blueprint_quota_used);
        $this->assertNotNull($member->blueprint_quota_refreshed_at);
    }

    public function test_a_refreshed_member_can_generate_again(): void
    {
        $member = $this->spentMember();

        $this->actingAs($member)->postJson('/create', [
            'brand_name' => 'Scentrism',
            'industry' => 'Fragrance',
            'audience' => 'Urban professionals.',
            'price_position' => 'Premium',
            'vision' => 'A mindful fragrance house.',
        ])->assertStatus(429);

        $this->actingAs($this->admin, 'admin')
            ->post(route('webcms.members.refresh-quota', $member));

        $this->actingAs($member->fresh())->postJson('/create', [
            'brand_name' => 'Scentrism',
            'industry' => 'Fragrance',
            'audience' => 'Urban professionals.',
            'price_position' => 'Premium',
            'vision' => 'A mindful fragrance house.',
        ])->assertCreated();
    }

    public function test_guests_and_members_cannot_refresh_a_quota(): void
    {
        $member = $this->spentMember();

        $this->post(route('webcms.members.refresh-quota', $member))->assertRedirect('/webcms/login');

        $this->actingAs($member)
            ->post(route('webcms.members.refresh-quota', $member))
            ->assertRedirect('/webcms/login');

        $this->assertSame(0, $member->fresh()->blueprintQuota()['remaining']);
    }
}

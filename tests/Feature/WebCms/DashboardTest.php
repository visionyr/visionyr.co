<?php

namespace Tests\Feature\WebCms;

use App\Models\AdminUser;
use App\Models\Member;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_dashboard_renders_for_a_signed_in_admin(): void
    {
        $admin = AdminUser::factory()->create();

        Member::factory()->count(3)->create();
        Member::factory()->inactive()->create();

        $this->actingAs($admin, 'admin')
            ->get('/webcms')
            ->assertOk()
            ->assertSee('Dashboard')
            ->assertSee($admin->name)
            ->assertSee('Total members')
            ->assertSee('Member sign-ups');
    }

    public function test_the_dashboard_counts_members_correctly(): void
    {
        Member::factory()->count(4)->create();
        Member::factory()->inactive()->count(2)->create();

        $response = $this->actingAs(AdminUser::factory()->create(), 'admin')->get('/webcms');

        $stats = $response->viewData('stats');

        $this->assertSame(6, $stats['members']);
        $this->assertSame(4, $stats['active_members']);
        $this->assertSame(6, $stats['new_members']);
        $this->assertSame(1, $stats['admins']);
    }

    public function test_the_sign_up_trend_covers_twelve_months(): void
    {
        Member::factory()->count(2)->create();
        Member::factory()->create(['created_at' => now()->subMonths(3)]);

        $trend = $this->actingAs(AdminUser::factory()->create(), 'admin')
            ->get('/webcms')
            ->viewData('signupTrend');

        $this->assertCount(12, $trend);
        $this->assertSame(2, $trend->last()['total']);
        $this->assertSame(1, $trend[8]['total']);
    }
}

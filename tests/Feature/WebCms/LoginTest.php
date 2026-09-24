<?php

namespace Tests\Feature\WebCms;

use App\Models\AdminUser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_screen_can_be_rendered(): void
    {
        $this->get('/webcms/login')
            ->assertOk()
            ->assertSee('Sign in to the CMS');
    }

    public function test_admin_can_sign_in(): void
    {
        $admin = AdminUser::factory()->create();

        $this->post('/webcms/login', [
            'email' => $admin->email,
            'password' => 'password',
        ])->assertRedirect('/webcms');

        $this->assertAuthenticatedAs($admin, 'admin');
        $this->assertNotNull($admin->fresh()->last_login_at);
    }

    public function test_admin_cannot_sign_in_with_a_wrong_password(): void
    {
        $admin = AdminUser::factory()->create();

        $this->post('/webcms/login', [
            'email' => $admin->email,
            'password' => 'wrong-password',
        ])->assertSessionHasErrors('email');

        $this->assertGuest('admin');
    }

    public function test_inactive_admin_cannot_sign_in(): void
    {
        $admin = AdminUser::factory()->inactive()->create();

        $this->post('/webcms/login', [
            'email' => $admin->email,
            'password' => 'password',
        ])->assertSessionHasErrors('email');

        $this->assertGuest('admin');
    }

    public function test_login_is_rate_limited_after_five_failures(): void
    {
        $admin = AdminUser::factory()->create();

        foreach (range(1, 5) as $attempt) {
            $this->post('/webcms/login', [
                'email' => $admin->email,
                'password' => 'wrong-password',
            ]);
        }

        $response = $this->post('/webcms/login', [
            'email' => $admin->email,
            'password' => 'password',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertStringContainsString(
            'Too many login attempts',
            session('errors')->first('email'),
        );
        $this->assertGuest('admin');
    }

    public function test_signed_in_admin_is_redirected_away_from_the_login_screen(): void
    {
        $this->actingAs(AdminUser::factory()->create(), 'admin')
            ->get('/webcms/login')
            ->assertRedirect('/webcms');
    }

    public function test_admin_can_sign_out(): void
    {
        $this->actingAs(AdminUser::factory()->create(), 'admin')
            ->post('/webcms/logout')
            ->assertRedirect('/webcms/login');

        $this->assertGuest('admin');
    }

    public function test_guests_are_redirected_to_the_cms_login_screen(): void
    {
        $this->get('/webcms')->assertRedirect('/webcms/login');
        $this->get('/webcms/members')->assertRedirect('/webcms/login');
        $this->get('/webcms/admin-users')->assertRedirect('/webcms/login');
    }
}

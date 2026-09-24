<?php

namespace Tests\Feature\Site;

use App\Models\AdminUser;
use App\Models\Member;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MemberAuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_sign_in_screen_can_be_rendered(): void
    {
        $this->get('/login')
            ->assertOk()
            ->assertSee('Welcome back');
    }

    public function test_a_member_can_sign_in(): void
    {
        $member = Member::factory()->create();

        $this->post('/login', [
            'email' => $member->email,
            'password' => 'password',
        ])->assertRedirect('/dashboard');

        $this->assertAuthenticatedAs($member);
        $this->assertNotNull($member->fresh()->last_login_at);
    }

    public function test_a_member_cannot_sign_in_with_a_wrong_password(): void
    {
        $member = Member::factory()->create();

        $this->post('/login', [
            'email' => $member->email,
            'password' => 'wrong-password',
        ])->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_an_inactive_member_cannot_sign_in(): void
    {
        $member = Member::factory()->inactive()->create();

        $this->post('/login', [
            'email' => $member->email,
            'password' => 'password',
        ])->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_sign_in_is_rate_limited_after_five_failures(): void
    {
        $member = Member::factory()->create();

        foreach (range(1, 5) as $attempt) {
            $this->post('/login', ['email' => $member->email, 'password' => 'wrong-password']);
        }

        $this->post('/login', ['email' => $member->email, 'password' => 'password'])
            ->assertSessionHasErrors('email');

        $this->assertStringContainsString('Too many login attempts', session('errors')->first('email'));
        $this->assertGuest();
    }

    public function test_a_signed_in_member_is_redirected_away_from_sign_in(): void
    {
        $this->actingAs(Member::factory()->create())
            ->get('/login')
            ->assertRedirect('/dashboard');
    }

    public function test_a_member_can_sign_out(): void
    {
        $this->actingAs(Member::factory()->create())
            ->post('/logout')
            ->assertRedirect('/');

        $this->assertGuest();
    }

    public function test_an_admin_cannot_sign_in_as_a_member(): void
    {
        $admin = AdminUser::factory()->create();

        $this->post('/login', [
            'email' => $admin->email,
            'password' => 'password',
        ])->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_self_registration_is_switched_off(): void
    {
        $this->assertFalse(config('features.member_registration'));

        $this->get('/register')->assertNotFound();
        $this->post('/register', [
            'name' => 'Jane Cooper',
            'email' => 'jane@example.com',
            'phone' => '081234567890',
            'password' => 'secret-password',
            'password_confirmation' => 'secret-password',
        ])->assertNotFound();

        $this->assertSame(0, Member::count());
    }

    public function test_the_sign_in_screen_points_at_contact_instead_of_registration(): void
    {
        $this->get('/login')
            ->assertOk()
            ->assertSee('Need an account?')
            ->assertSee(route('contact'))
            ->assertDontSee('Create an account');
    }

    public function test_a_member_cannot_reach_the_cms(): void
    {
        $this->actingAs(Member::factory()->create())
            ->get('/webcms')
            ->assertRedirect('/webcms/login');
    }
}

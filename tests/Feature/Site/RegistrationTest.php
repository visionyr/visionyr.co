<?php

namespace Tests\Feature\Site;

use App\Models\Member;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Self-registration is switched off in production for now, so this suite turns
     * it on to keep the screen covered for whenever it comes back.
     */
    protected function setUp(): void
    {
        parent::setUp();

        config(['features.member_registration' => true]);
    }

    /**
     * @return array<string, string>
     */
    protected function fields(array $overrides = []): array
    {
        return array_merge([
            'name' => 'Jane Cooper',
            'email' => 'jane@example.com',
            'phone' => '081234567890',
            'password' => 'secret-password',
            'password_confirmation' => 'secret-password',
        ], $overrides);
    }

    public function test_the_registration_screen_can_be_rendered(): void
    {
        $this->get('/register')
            ->assertOk()
            ->assertSee('Create your account');
    }

    public function test_a_member_can_register_and_is_signed_in(): void
    {
        Event::fake([Registered::class]);

        $this->post('/register', $this->fields())->assertRedirect('/dashboard');

        $member = Member::sole();

        $this->assertSame('Jane Cooper', $member->name);
        $this->assertSame('jane@example.com', $member->email);
        $this->assertSame('081234567890', $member->phone);
        $this->assertTrue($member->is_active);
        $this->assertTrue(Hash::check('secret-password', $member->password));

        $this->assertAuthenticatedAs($member);
        Event::assertDispatched(Registered::class);
    }

    public function test_every_field_is_required(): void
    {
        $this->post('/register', [])
            ->assertSessionHasErrors(['name', 'email', 'phone', 'password']);

        $this->assertSame(0, Member::count());
        $this->assertGuest();
    }

    public function test_the_email_must_be_unique(): void
    {
        Member::factory()->create(['email' => 'jane@example.com']);

        $this->post('/register', $this->fields())->assertSessionHasErrors('email');

        $this->assertSame(1, Member::count());
    }

    public function test_the_password_must_be_confirmed(): void
    {
        $this->post('/register', $this->fields(['password_confirmation' => 'something-else']))
            ->assertSessionHasErrors('password');

        $this->assertSame(0, Member::count());
    }

    public function test_the_phone_must_look_like_a_phone_number(): void
    {
        $this->post('/register', $this->fields(['phone' => 'call me maybe']))
            ->assertSessionHasErrors('phone');

        $this->assertSame(0, Member::count());
    }

    public function test_the_email_is_stored_lowercase(): void
    {
        $this->post('/register', $this->fields(['email' => '  JANE@Example.COM  ']));

        $this->assertSame('jane@example.com', Member::sole()->email);
    }

    public function test_a_signed_in_member_is_redirected_away(): void
    {
        $this->actingAs(Member::factory()->create())
            ->get('/register')
            ->assertRedirect('/dashboard');
    }

    public function test_registering_returns_a_guest_to_the_discovery_form(): void
    {
        $this->get('/create')->assertRedirect('/login');

        $this->post('/register', $this->fields())->assertRedirect('/create');
    }

    public function test_the_sign_in_screen_offers_registration_while_it_is_enabled(): void
    {
        $this->get('/login')
            ->assertOk()
            ->assertSee('Create an account')
            ->assertSee(route('register'));
    }
}

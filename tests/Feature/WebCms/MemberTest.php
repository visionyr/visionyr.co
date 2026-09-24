<?php

namespace Tests\Feature\WebCms;

use App\Models\AdminUser;
use App\Models\Member;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class MemberTest extends TestCase
{
    use RefreshDatabase;

    protected AdminUser $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = AdminUser::factory()->create();
    }

    public function test_members_can_be_listed(): void
    {
        $member = Member::factory()->create();

        $this->actingAs($this->admin, 'admin')
            ->get('/webcms/members')
            ->assertOk()
            ->assertSee($member->name)
            ->assertSee($member->email);
    }

    public function test_members_can_be_searched_by_name_email_or_phone(): void
    {
        $match = Member::factory()->create([
            'name' => 'Zahra Putri',
            'email' => 'zahra@example.com',
            'phone' => '081298765432',
        ]);

        $other = Member::factory()->create(['name' => 'Budi Santoso']);

        foreach (['Zahra', 'zahra@example.com', '081298765432'] as $term) {
            $this->actingAs($this->admin, 'admin')
                ->get('/webcms/members?search='.urlencode($term))
                ->assertOk()
                ->assertSee($match->name)
                ->assertDontSee($other->name);
        }
    }

    public function test_the_create_and_edit_screens_can_be_rendered(): void
    {
        $member = Member::factory()->create();

        $this->actingAs($this->admin, 'admin')
            ->get('/webcms/members/create')
            ->assertOk()
            ->assertSee('Create member');

        $this->actingAs($this->admin, 'admin')
            ->get('/webcms/members/'.$member->id.'/edit')
            ->assertOk()
            ->assertSee('Save changes')
            ->assertSee($member->email)
            ->assertSee($member->phone);
    }

    public function test_a_member_can_be_created(): void
    {
        $this->actingAs($this->admin, 'admin')
            ->post('/webcms/members', [
                'name' => 'Jane Cooper',
                'email' => 'jane@example.com',
                'phone' => '081234567890',
                'password' => 'secret-password',
                'password_confirmation' => 'secret-password',
                'is_active' => '1',
            ])
            ->assertRedirect('/webcms/members')
            ->assertSessionHas('status');

        $member = Member::whereEmail('jane@example.com')->sole();

        $this->assertSame('Jane Cooper', $member->name);
        $this->assertSame('081234567890', $member->phone);
        $this->assertTrue($member->is_active);
        $this->assertTrue(Hash::check('secret-password', $member->password));
    }

    public function test_creating_a_member_requires_the_mandatory_fields(): void
    {
        $this->actingAs($this->admin, 'admin')
            ->post('/webcms/members', [])
            ->assertSessionHasErrors(['name', 'email', 'phone', 'password']);

        $this->assertSame(0, Member::count());
    }

    public function test_member_email_must_be_unique(): void
    {
        $existing = Member::factory()->create();

        $this->actingAs($this->admin, 'admin')
            ->post('/webcms/members', [
                'name' => 'Jane Cooper',
                'email' => $existing->email,
                'phone' => '081234567890',
                'password' => 'secret-password',
                'password_confirmation' => 'secret-password',
            ])
            ->assertSessionHasErrors('email');
    }

    public function test_a_member_can_be_updated_without_changing_the_password(): void
    {
        $member = Member::factory()->create();
        $originalPassword = $member->password;

        $this->actingAs($this->admin, 'admin')
            ->put('/webcms/members/'.$member->id, [
                'name' => 'Updated Name',
                'email' => $member->email,
                'phone' => '089900112233',
                'password' => '',
                'password_confirmation' => '',
                'is_active' => '0',
            ])
            ->assertRedirect('/webcms/members');

        $member->refresh();

        $this->assertSame('Updated Name', $member->name);
        $this->assertSame('089900112233', $member->phone);
        $this->assertFalse($member->is_active);
        $this->assertSame($originalPassword, $member->password);
    }

    public function test_a_member_password_can_be_changed(): void
    {
        $member = Member::factory()->create();

        $this->actingAs($this->admin, 'admin')
            ->put('/webcms/members/'.$member->id, [
                'name' => $member->name,
                'email' => $member->email,
                'phone' => $member->phone,
                'password' => 'brand-new-password',
                'password_confirmation' => 'brand-new-password',
                'is_active' => '1',
            ])
            ->assertRedirect('/webcms/members');

        $this->assertTrue(Hash::check('brand-new-password', $member->fresh()->password));
    }

    public function test_a_member_can_be_deleted(): void
    {
        $member = Member::factory()->create();

        $this->actingAs($this->admin, 'admin')
            ->delete('/webcms/members/'.$member->id)
            ->assertRedirect('/webcms/members');

        $this->assertDatabaseMissing('members', ['id' => $member->id]);
    }

    public function test_guests_cannot_manage_members(): void
    {
        $member = Member::factory()->create();

        $this->get('/webcms/members/create')->assertRedirect('/webcms/login');
        $this->post('/webcms/members', [])->assertRedirect('/webcms/login');
        $this->delete('/webcms/members/'.$member->id)->assertRedirect('/webcms/login');

        $this->assertDatabaseHas('members', ['id' => $member->id]);
    }
}

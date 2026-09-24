<?php

namespace Tests\Feature\WebCms;

use App\Models\AdminUser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminUserTest extends TestCase
{
    use RefreshDatabase;

    protected AdminUser $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = AdminUser::factory()->create();
    }

    public function test_admin_users_can_be_listed(): void
    {
        $other = AdminUser::factory()->create();

        $this->actingAs($this->admin, 'admin')
            ->get('/webcms/admin-users')
            ->assertOk()
            ->assertSee($other->name)
            ->assertSee($other->email);
    }

    public function test_admin_users_can_be_searched(): void
    {
        $match = AdminUser::factory()->create(['name' => 'Rizki Pratama']);
        $other = AdminUser::factory()->create(['name' => 'Siti Aminah']);

        $this->actingAs($this->admin, 'admin')
            ->get('/webcms/admin-users?search=Rizki')
            ->assertOk()
            ->assertSee($match->name)
            ->assertDontSee($other->name);
    }

    public function test_the_create_and_edit_screens_can_be_rendered(): void
    {
        $target = AdminUser::factory()->create();

        $this->actingAs($this->admin, 'admin')
            ->get('/webcms/admin-users/create')
            ->assertOk()
            ->assertSee('Create admin');

        $this->actingAs($this->admin, 'admin')
            ->get('/webcms/admin-users/'.$target->id.'/edit')
            ->assertOk()
            ->assertSee('Save changes')
            ->assertSee($target->email);
    }

    public function test_an_admin_editing_themselves_cannot_toggle_their_own_status(): void
    {
        $this->actingAs($this->admin, 'admin')
            ->get('/webcms/admin-users/'.$this->admin->id.'/edit')
            ->assertOk()
            ->assertSee('You cannot deactivate the account you are signed in with.');
    }

    public function test_an_admin_user_can_be_created(): void
    {
        $this->actingAs($this->admin, 'admin')
            ->post('/webcms/admin-users', [
                'name' => 'Jane Cooper',
                'email' => 'jane@visionyr.com',
                'password' => 'secret-password',
                'password_confirmation' => 'secret-password',
                'is_active' => '1',
            ])
            ->assertRedirect('/webcms/admin-users')
            ->assertSessionHas('status');

        $created = AdminUser::whereEmail('jane@visionyr.com')->sole();

        $this->assertSame('Jane Cooper', $created->name);
        $this->assertTrue($created->is_active);
        $this->assertTrue(Hash::check('secret-password', $created->password));
    }

    public function test_creating_an_admin_user_requires_the_mandatory_fields(): void
    {
        $this->actingAs($this->admin, 'admin')
            ->post('/webcms/admin-users', [])
            ->assertSessionHasErrors(['name', 'email', 'password']);
    }

    public function test_an_admin_user_can_be_updated_without_changing_the_password(): void
    {
        $target = AdminUser::factory()->create();
        $originalPassword = $target->password;

        $this->actingAs($this->admin, 'admin')
            ->put('/webcms/admin-users/'.$target->id, [
                'name' => 'Updated Admin',
                'email' => $target->email,
                'password' => '',
                'password_confirmation' => '',
                'is_active' => '0',
            ])
            ->assertRedirect('/webcms/admin-users');

        $target->refresh();

        $this->assertSame('Updated Admin', $target->name);
        $this->assertFalse($target->is_active);
        $this->assertSame($originalPassword, $target->password);
    }

    public function test_an_admin_cannot_deactivate_their_own_account(): void
    {
        $this->actingAs($this->admin, 'admin')
            ->put('/webcms/admin-users/'.$this->admin->id, [
                'name' => $this->admin->name,
                'email' => $this->admin->email,
                'password' => '',
                'password_confirmation' => '',
                'is_active' => '0',
            ])
            ->assertRedirect('/webcms/admin-users');

        $this->assertTrue($this->admin->fresh()->is_active);
    }

    public function test_an_admin_cannot_delete_their_own_account(): void
    {
        AdminUser::factory()->create();

        $this->actingAs($this->admin, 'admin')
            ->delete('/webcms/admin-users/'.$this->admin->id)
            ->assertSessionHas('error');

        $this->assertDatabaseHas('admin_users', ['id' => $this->admin->id]);
    }

    public function test_at_least_one_admin_user_always_remains(): void
    {
        $sole = AdminUser::factory()->create();
        AdminUser::whereKeyNot($sole->id)->delete();

        $this->actingAs($sole, 'admin')
            ->delete('/webcms/admin-users/'.$sole->id)
            ->assertSessionHas('error');

        $this->assertSame(1, AdminUser::count());
    }

    public function test_another_admin_user_can_be_deleted(): void
    {
        $target = AdminUser::factory()->create();

        $this->actingAs($this->admin, 'admin')
            ->delete('/webcms/admin-users/'.$target->id)
            ->assertRedirect('/webcms/admin-users');

        $this->assertDatabaseMissing('admin_users', ['id' => $target->id]);
    }

    public function test_guests_cannot_manage_admin_users(): void
    {
        $target = AdminUser::factory()->create();

        $this->get('/webcms/admin-users/create')->assertRedirect('/webcms/login');
        $this->post('/webcms/admin-users', [])->assertRedirect('/webcms/login');
        $this->delete('/webcms/admin-users/'.$target->id)->assertRedirect('/webcms/login');

        $this->assertDatabaseHas('admin_users', ['id' => $target->id]);
    }
}

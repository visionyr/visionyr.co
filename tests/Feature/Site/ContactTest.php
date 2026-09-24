<?php

namespace Tests\Feature\Site;

use App\Models\ContactMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContactTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return array<string, string>
     */
    protected function fields(array $overrides = []): array
    {
        return array_merge([
            'name' => 'Jane Cooper',
            'email' => 'jane@example.com',
            'phone' => '081234567890',
            'message' => 'We are launching a fragrance brand next quarter and would like a demo.',
        ], $overrides);
    }

    public function test_the_contact_page_renders(): void
    {
        $this->get('/contact')
            ->assertOk()
            ->assertSee('Send us a message')
            ->assertSee('hello@visionyr.co');
    }

    public function test_a_message_can_be_sent(): void
    {
        $this->post('/contact', $this->fields())
            ->assertRedirect('/contact')
            ->assertSessionHas('contact_sent', 'Jane Cooper');

        $message = ContactMessage::sole();

        $this->assertSame('Jane Cooper', $message->name);
        $this->assertSame('jane@example.com', $message->email);
        $this->assertSame('081234567890', $message->phone);
        $this->assertNull($message->handled_at);
    }

    public function test_the_page_confirms_a_sent_message(): void
    {
        $this->followingRedirects()
            ->post('/contact', $this->fields())
            ->assertOk()
            ->assertSee('Thanks, Jane Cooper.')
            ->assertDontSee('Send us a message');
    }

    public function test_every_field_is_required(): void
    {
        $this->post('/contact', [])
            ->assertSessionHasErrors(['name', 'email', 'phone', 'message']);

        $this->assertSame(0, ContactMessage::count());
    }

    public function test_the_message_must_say_something(): void
    {
        $this->post('/contact', $this->fields(['message' => 'Hi']))
            ->assertSessionHasErrors('message');

        $this->assertSame(0, ContactMessage::count());
    }

    public function test_the_email_and_phone_must_look_valid(): void
    {
        $this->post('/contact', $this->fields(['email' => 'not-an-email']))
            ->assertSessionHasErrors('email');

        $this->post('/contact', $this->fields(['phone' => 'call me maybe']))
            ->assertSessionHasErrors('phone');

        $this->assertSame(0, ContactMessage::count());
    }

    public function test_the_email_is_stored_lowercase_and_trimmed(): void
    {
        $this->post('/contact', $this->fields(['email' => '  JANE@Example.COM  ']));

        $this->assertSame('jane@example.com', ContactMessage::sole()->email);
    }

    public function test_unhandled_scope_finds_messages_awaiting_a_reply(): void
    {
        ContactMessage::factory()->count(2)->create();
        ContactMessage::factory()->handled()->create();

        $this->assertSame(2, ContactMessage::unhandled()->count());
    }
}

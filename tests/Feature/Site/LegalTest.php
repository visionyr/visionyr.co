<?php

namespace Tests\Feature\Site;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LegalTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return array<int, array<int, string>>
     */
    public static function documents(): array
    {
        return [
            'privacy' => ['/privacy', 'privacy', 'Privacy Policy'],
            'terms' => ['/terms', 'terms', 'Terms & Conditions'],
        ];
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('documents')]
    public function test_the_document_renders_every_section(string $path, string $key, string $title): void
    {
        $response = $this->get($path);

        $response->assertOk()->assertSee($title);

        foreach (config("legal.{$key}.sections") as $section) {
            $response->assertSee($section['heading']);
            $response->assertSee('id="'.$section['id'].'"', false);
        }
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('documents')]
    public function test_the_document_lists_its_contents_and_effective_date(string $path, string $key): void
    {
        $response = $this->get($path);

        $response->assertSee('On this page');
        $response->assertSee(config("legal.{$key}.effective"));

        foreach (config("legal.{$key}.sections") as $section) {
            $response->assertSee('data-toc-link="'.$section['id'].'"', false);
        }
    }

    public function test_the_documents_link_to_each_other_and_to_contact(): void
    {
        $this->get('/privacy')
            ->assertSee(route('terms'))
            ->assertSee(route('contact'));

        $this->get('/terms')
            ->assertSee(route('privacy'))
            ->assertSee(route('contact'));
    }

    public function test_the_footer_links_to_contact_and_both_documents(): void
    {
        $response = $this->get('/');

        $response->assertSee(route('contact'))
            ->assertSee(route('privacy'))
            ->assertSee(route('terms'));
    }

    public function test_book_a_demo_opens_whatsapp(): void
    {
        $demo = config('marketing.demo_whatsapp');

        $this->assertStringStartsWith('https://wa.me/628118387783?text=', $demo);

        $this->get('/')->assertOk()->assertSee($demo);
    }
}

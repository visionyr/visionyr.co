<?php

namespace Tests\Feature\WebCms;

use App\Models\AdminUser;
use App\Models\BrandBlueprint;
use App\Models\Member;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BrandBlueprintModuleTest extends TestCase
{
    use RefreshDatabase;

    protected AdminUser $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = AdminUser::factory()->create();
    }

    public function test_blueprints_can_be_listed_with_their_member(): void
    {
        $member = Member::factory()->create(['name' => 'Jane Cooper']);
        $blueprint = BrandBlueprint::factory()->for($member)->create(['brand_name' => 'Scentrism']);

        $this->actingAs($this->admin, 'admin')
            ->get('/webcms/brand-blueprints')
            ->assertOk()
            ->assertSee('Scentrism')
            ->assertSee('Jane Cooper')
            ->assertSee($blueprint->industry)
            ->assertSee('AI');
    }

    public function test_the_list_counts_ai_and_fallback_blueprints(): void
    {
        BrandBlueprint::factory()->count(3)->create();
        BrandBlueprint::factory()->fellBack()->count(2)->create();

        $stats = $this->actingAs($this->admin, 'admin')
            ->get('/webcms/brand-blueprints')
            ->viewData('stats');

        $this->assertSame(5, $stats['total']);
        $this->assertSame(3, $stats['ai']);
        $this->assertSame(2, $stats['fallback']);
    }

    public function test_blueprints_can_be_searched(): void
    {
        $match = BrandBlueprint::factory()->create(['brand_name' => 'Scentrism']);
        $other = BrandBlueprint::factory()->create(['brand_name' => 'Firstly Coffee']);

        $this->actingAs($this->admin, 'admin')
            ->get('/webcms/brand-blueprints?search=Scentrism')
            ->assertOk()
            ->assertSee($match->brand_name)
            ->assertDontSee($other->brand_name);
    }

    public function test_a_blueprint_shows_the_options_selected_and_the_result(): void
    {
        $blueprint = BrandBlueprint::factory()->create([
            'brand_name' => 'Scentrism',
            'industry' => 'Fragrance',
            'price_position' => 'Premium',
            'audience' => 'Urban professionals who treat fragrance as a ritual.',
            'vision' => 'A fragrance house built on mindful living.',
        ]);

        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('webcms.brand-blueprints.show', $blueprint));

        $response->assertOk()
            ->assertSee('Options selected')
            // the answers
            ->assertSee('Fragrance')
            ->assertSee('Premium')
            ->assertSee('Urban professionals who treat fragrance as a ritual.')
            ->assertSee('A fragrance house built on mindful living.')
            // the generated result
            ->assertSee($blueprint->payload['tagline'])
            ->assertSee((string) $blueprint->payload['scores']['brand'])
            ->assertSee($blueprint->payload['colors'][0]['hex'])
            // where it came from
            ->assertSee('OpenRouter')
            ->assertSee('anthropic/claude-opus-5');
    }

    public function test_a_fallback_blueprint_says_so(): void
    {
        $blueprint = BrandBlueprint::factory()
            ->fellBack('OpenRouter returned 402: Insufficient credits')
            ->create();

        $this->actingAs($this->admin, 'admin')
            ->get(route('webcms.brand-blueprints.show', $blueprint))
            ->assertOk()
            ->assertSee('template fallback')
            ->assertSee('Insufficient credits');
    }

    public function test_a_blueprint_can_be_deleted(): void
    {
        $blueprint = BrandBlueprint::factory()->create();

        $this->actingAs($this->admin, 'admin')
            ->delete(route('webcms.brand-blueprints.destroy', $blueprint))
            ->assertRedirect(route('webcms.brand-blueprints.index'))
            ->assertSessionHas('status');

        $this->assertDatabaseMissing('brand_blueprints', ['id' => $blueprint->id]);
    }

    public function test_guests_cannot_reach_the_module(): void
    {
        $blueprint = BrandBlueprint::factory()->create();

        $this->get('/webcms/brand-blueprints')->assertRedirect('/webcms/login');
        $this->get(route('webcms.brand-blueprints.show', $blueprint))->assertRedirect('/webcms/login');
        $this->delete(route('webcms.brand-blueprints.destroy', $blueprint))->assertRedirect('/webcms/login');

        $this->assertDatabaseHas('brand_blueprints', ['id' => $blueprint->id]);
    }

    public function test_a_signed_in_member_cannot_reach_the_module(): void
    {
        $this->actingAs(Member::factory()->create())
            ->get('/webcms/brand-blueprints')
            ->assertRedirect('/webcms/login');
    }
}

<?php

namespace Tests\Feature;

use App\Models\NewsPost;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class NewsPublicTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('s3');
    }

    public function test_index_lists_published_posts_newest_first(): void
    {
        $older = NewsPost::factory()->create(['published_at' => now()->subDays(5)]);
        $newer = NewsPost::factory()->create(['published_at' => now()->subDay()]);

        $this->get(route('news.index'))
            ->assertOk()
            ->assertSeeInOrder([$newer->title, $older->title])
            ->assertSee($newer->excerpt);
    }

    public function test_index_hides_drafts_and_future_posts(): void
    {
        $draft = NewsPost::factory()->draft()->create();
        $scheduled = NewsPost::factory()->scheduled()->create();
        $published = NewsPost::factory()->published()->create();

        $this->get(route('news.index'))
            ->assertOk()
            ->assertSee($published->title)
            ->assertDontSee($draft->title)
            ->assertDontSee($scheduled->title);
    }

    public function test_index_paginates_past_nine_posts(): void
    {
        NewsPost::factory()->published()->count(10)->create();

        $this->get(route('news.index'))
            ->assertOk()
            ->assertSee(route('news.index').'?page=2');

        $this->get(route('news.index').'?page=2')->assertOk();
    }

    public function test_show_renders_a_published_post_by_slug(): void
    {
        $post = NewsPost::factory()->published()->create([
            'title' => 'Route Change Announcement',
            'body' => '<div>The 60km route now passes the ridge trail.</div>',
        ]);

        $this->get(route('news.show', $post))
            ->assertOk()
            ->assertSee('Route Change Announcement')
            ->assertSee('The 60km route now passes the ridge trail.');

        $this->assertSame('route-change-announcement', $post->slug);
    }

    public function test_show_returns_404_for_draft_and_future_posts(): void
    {
        $draft = NewsPost::factory()->draft()->create();
        $scheduled = NewsPost::factory()->scheduled()->create();

        $this->get(route('news.show', $draft))->assertNotFound();
        $this->get(route('news.show', $scheduled))->assertNotFound();
    }

    public function test_show_renders_open_graph_tags(): void
    {
        $post = NewsPost::factory()->published()->create([
            'title' => 'Trail Marking Update',
            'body' => '<div>Reflective markers every 200 meters.</div>',
            'cover_image_path' => 'news/cover-example.jpg',
        ]);

        $response = $this->get(route('news.show', $post))->assertOk();

        $response->assertSee('<meta property="og:type" content="article">', false);
        $response->assertSee('<meta property="og:title" content="Trail Marking Update — RICON">', false);
        $response->assertSee('<meta property="og:description" content="Reflective markers every 200 meters.">', false);
        $response->assertSee('<meta property="og:image" content="' . $post->cover_image_url . '">', false);
        // The default site image must be fully replaced, not listed alongside
        $response->assertDontSee('facebook-image.png');
    }

    public function test_show_without_cover_falls_back_to_default_og_image(): void
    {
        $post = NewsPost::factory()->published()->create();

        $this->get(route('news.show', $post))
            ->assertOk()
            ->assertSee('facebook-image.png');
    }

    public function test_index_shows_empty_state_with_no_posts(): void
    {
        $this->get(route('news.index'))
            ->assertOk()
            ->assertSee('No news yet');
    }
}

<?php

namespace Tests\Feature;

use App\Models\NewsPost;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class NewsAdminTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('s3');
    }

    private function admin(): User
    {
        return User::factory()->create(['role' => 'admin']);
    }

    public function test_guests_and_non_admins_cannot_access_admin_news(): void
    {
        $this->get(route('admin.news.index'))->assertRedirect(route('login'));

        $user = User::factory()->create(['role' => 'user']);
        $this->actingAs($user)->get(route('admin.news.index'))->assertForbidden();
    }

    public function test_admin_creates_a_post_with_cover_image(): void
    {
        $admin = $this->admin();

        $this->actingAs($admin)
            ->post(route('admin.news.store'), [
                'title' => 'Aid Station Update',
                'body' => '<div>New aid station at km 42.</div>',
                'cover_image' => UploadedFile::fake()->image('cover.jpg'),
                'publish' => '1',
            ])
            ->assertRedirect(route('admin.news.index'))
            ->assertSessionHas('success');

        $post = NewsPost::sole();
        $this->assertSame('Aid Station Update', $post->title);
        $this->assertSame($admin->id, $post->created_by);
        $this->assertNotNull($post->published_at);
        $this->assertCount(1, Storage::disk('s3')->allFiles('news'));
    }

    public function test_slug_is_generated_and_collisions_get_a_suffix(): void
    {
        $admin = $this->admin();

        foreach (range(1, 2) as $i) {
            $this->actingAs($admin)->post(route('admin.news.store'), [
                'title' => 'Race Day Reminders',
                'body' => '<div>Reminder '.$i.'</div>',
                'publish' => '0',
            ]);
        }

        $slugs = NewsPost::orderBy('created_at')->pluck('slug');
        $this->assertSame('race-day-reminders', $slugs[0]);
        $this->assertSame('race-day-reminders-2', $slugs[1]);
    }

    public function test_publish_checkbox_controls_published_at(): void
    {
        $admin = $this->admin();

        $this->actingAs($admin)->post(route('admin.news.store'), [
            'title' => 'Draft Post',
            'body' => '<div>Draft body</div>',
            'publish' => '0',
        ]);

        $this->assertNull(NewsPost::sole()->published_at);
    }

    public function test_update_preserves_publish_timestamp_and_slug(): void
    {
        $admin = $this->admin();
        $post = NewsPost::factory()->create([
            'title' => 'Original Title',
            'published_at' => now()->subWeek(),
        ]);
        $originalPublishedAt = $post->published_at;
        $originalSlug = $post->slug;

        $this->actingAs($admin)->put(route('admin.news.update', $post), [
            'title' => 'Updated Title',
            'body' => '<div>Updated body</div>',
            'publish' => '1',
        ])->assertRedirect(route('admin.news.index'));

        $post->refresh();
        $this->assertSame('Updated Title', $post->title);
        $this->assertSame($originalSlug, $post->slug);
        $this->assertTrue($originalPublishedAt->equalTo($post->published_at));

        // Unchecking publish reverts to draft
        $this->actingAs($admin)->put(route('admin.news.update', $post), [
            'title' => 'Updated Title',
            'body' => '<div>Updated body</div>',
            'publish' => '0',
        ]);

        $this->assertNull($post->refresh()->published_at);
    }

    public function test_replacing_the_cover_deletes_the_old_file(): void
    {
        $admin = $this->admin();
        $oldPath = UploadedFile::fake()->image('old.jpg')->store('news', 's3');
        $post = NewsPost::factory()->create(['cover_image_path' => $oldPath]);

        $this->actingAs($admin)->put(route('admin.news.update', $post), [
            'title' => $post->title,
            'body' => $post->body,
            'cover_image' => UploadedFile::fake()->image('new.jpg'),
            'publish' => '0',
        ]);

        Storage::disk('s3')->assertMissing($oldPath);
        $this->assertNotSame($oldPath, $post->refresh()->cover_image_path);
        Storage::disk('s3')->assertExists($post->cover_image_path);
    }

    public function test_body_is_sanitized_on_save(): void
    {
        $admin = $this->admin();

        $this->actingAs($admin)->post(route('admin.news.store'), [
            'title' => 'XSS Attempt',
            'body' => '<div>Hello</div><script>alert(1)</script><img src="x" onerror="alert(2)">',
            'publish' => '1',
        ]);

        $body = NewsPost::sole()->body;
        $this->assertStringContainsString('Hello', $body);
        $this->assertStringNotContainsString('<script', $body);
        $this->assertStringNotContainsString('onerror', $body);
    }

    public function test_upload_image_returns_url_and_validates(): void
    {
        $admin = $this->admin();

        $response = $this->actingAs($admin)->postJson(route('admin.news.uploadImage'), [
            'image' => UploadedFile::fake()->image('inline.png'),
        ]);

        $response->assertOk()->assertJsonStructure(['url']);
        $this->assertCount(1, Storage::disk('s3')->allFiles('news'));

        $this->actingAs($admin)->postJson(route('admin.news.uploadImage'), [
            'image' => UploadedFile::fake()->create('doc.pdf', 100, 'application/pdf'),
        ])->assertUnprocessable();

        $this->actingAs($admin)->postJson(route('admin.news.uploadImage'), [
            'image' => UploadedFile::fake()->image('huge.jpg')->size(6000),
        ])->assertUnprocessable();
    }

    public function test_destroy_removes_post_and_cover_file(): void
    {
        $admin = $this->admin();
        $coverPath = UploadedFile::fake()->image('cover.jpg')->store('news', 's3');
        $post = NewsPost::factory()->create(['cover_image_path' => $coverPath]);

        $this->actingAs($admin)
            ->delete(route('admin.news.destroy', $post))
            ->assertRedirect(route('admin.news.index'));

        $this->assertSame(0, NewsPost::count());
        Storage::disk('s3')->assertMissing($coverPath);
    }
}

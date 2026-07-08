<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\BlogCategory;
use App\Models\BlogComment;
use App\Models\BlogPost;
use App\Models\BlogTag;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class BlogFeatureTest extends TestCase
{
    // NOT RefreshDatabase: phpunit runs against the shared local database.
    use DatabaseTransactions;

    private function makeAdmin(): Admin
    {
        return Admin::create([
            'name' => 'Blog Test Admin',
            'email' => 'blog-test-admin-' . uniqid() . '@example.com',
            'password' => 'secret-password',
            'status' => 1,
        ]);
    }

    private function makeUser(): User
    {
        $user = User::create([
            'name' => 'Blog Test User',
            'email' => 'blog-test-user-' . uniqid() . '@example.com',
            'password' => 'secret-password',
            'status' => 1,
        ]);
        $user->forceFill(['email_verified_at' => now()])->save();

        return $user;
    }

    private function makePost(array $overrides = []): BlogPost
    {
        return BlogPost::create(array_merge([
            'title' => 'Test Post ' . uniqid(),
            'slug' => 'test-post-' . uniqid(),
            'body' => '<p>Test body</p>',
            'status' => BlogPost::STATUS_PUBLISHED,
            'published_at' => now()->subHour(),
        ], $overrides));
    }

    public function test_blog_index_shows_published_posts_only(): void
    {
        $published = $this->makePost(['title' => 'Visible Published Post']);
        $draft = $this->makePost(['title' => 'Hidden Draft Post', 'status' => BlogPost::STATUS_DRAFT, 'published_at' => null]);
        $private = $this->makePost(['title' => 'Hidden Private Post', 'status' => BlogPost::STATUS_PRIVATE]);

        $response = $this->get(route('blog.index'));

        $response->assertOk();
        $response->assertSee('Visible Published Post');
        $response->assertDontSee('Hidden Draft Post');
        $response->assertDontSee('Hidden Private Post');
    }

    public function test_draft_and_private_posts_are_not_publicly_accessible(): void
    {
        $draft = $this->makePost(['status' => BlogPost::STATUS_DRAFT, 'published_at' => null]);
        $private = $this->makePost(['status' => BlogPost::STATUS_PRIVATE]);
        $scheduled = $this->makePost(['published_at' => now()->addWeek()]);

        $this->get(route('blog.show', $draft->slug))->assertNotFound();
        $this->get(route('blog.show', $private->slug))->assertNotFound();
        $this->get(route('blog.show', $scheduled->slug))->assertNotFound();
    }

    public function test_blog_index_filters_by_category_and_tag(): void
    {
        $category = BlogCategory::create(['name' => 'Filter Cat', 'slug' => 'filter-cat-' . uniqid(), 'status' => true]);
        $tag = BlogTag::create(['name' => 'Filter Tag', 'slug' => 'filter-tag-' . uniqid()]);

        // Excerpts only render in the main list — titles also appear in the
        // sidebar "Recent Posts" widget, which ignores the active filter.
        $inCategory = $this->makePost(['title' => 'Post In Category ABC', 'excerpt' => 'CATEGORY-MARKER-EXCERPT']);
        $inCategory->categories()->sync([$category->id]);

        $tagged = $this->makePost(['title' => 'Post With Tag XYZ', 'excerpt' => 'TAG-MARKER-EXCERPT']);
        $tagged->tags()->sync([$tag->id]);

        $this->get(route('blog.index', ['category' => $category->slug]))
            ->assertOk()
            ->assertSee('CATEGORY-MARKER-EXCERPT')
            ->assertDontSee('TAG-MARKER-EXCERPT');

        $this->get(route('blog.index', ['tag' => $tag->slug]))
            ->assertOk()
            ->assertSee('TAG-MARKER-EXCERPT')
            ->assertDontSee('CATEGORY-MARKER-EXCERPT');
    }

    public function test_hidden_comments_are_not_shown_publicly(): void
    {
        $user = $this->makeUser();
        $post = $this->makePost();

        BlogComment::create(['blog_post_id' => $post->id, 'user_id' => $user->id, 'body' => 'A visible comment 111', 'status' => true]);
        BlogComment::create(['blog_post_id' => $post->id, 'user_id' => $user->id, 'body' => 'A hidden comment 222', 'status' => false]);

        $response = $this->get(route('blog.show', $post->slug));

        $response->assertOk();
        $response->assertSee('A visible comment 111');
        $response->assertDontSee('A hidden comment 222');
    }

    public function test_guests_cannot_comment_and_are_redirected_to_login(): void
    {
        $post = $this->makePost();

        $response = $this->post(route('blog.comments.store', $post->slug), ['body' => 'Guest comment attempt']);

        $response->assertRedirect(route('login'));
        $this->assertDatabaseMissing('blog_comments', ['blog_post_id' => $post->id]);
    }

    public function test_logged_in_customer_can_comment_and_reply(): void
    {
        Notification::fake();

        $user = $this->makeUser();
        $post = $this->makePost();

        $this->actingAs($user)
            ->post(route('blog.comments.store', $post->slug), ['body' => 'My first comment'])
            ->assertRedirect(route('blog.show', $post->slug) . '#comments');

        $this->assertDatabaseHas('blog_comments', [
            'blog_post_id' => $post->id,
            'user_id' => $user->id,
            'body' => 'My first comment',
            'parent_id' => null,
        ]);

        $parent = BlogComment::where('blog_post_id', $post->id)->first();

        $this->actingAs($user)
            ->post(route('blog.comments.store', $post->slug), ['body' => 'A reply', 'parent_id' => $parent->id]);

        $this->assertDatabaseHas('blog_comments', [
            'blog_post_id' => $post->id,
            'body' => 'A reply',
            'parent_id' => $parent->id,
        ]);
    }

    public function test_comments_are_rejected_when_disabled_on_the_post(): void
    {
        $user = $this->makeUser();
        $post = $this->makePost(['allow_comments' => false]);

        $this->actingAs($user)
            ->post(route('blog.comments.store', $post->slug), ['body' => 'Should be rejected'])
            ->assertRedirect(route('blog.show', $post->slug));

        $this->assertDatabaseMissing('blog_comments', ['blog_post_id' => $post->id]);
    }

    public function test_admin_blog_pages_render(): void
    {
        $admin = $this->makeAdmin();
        $post = $this->makePost();

        $this->actingAs($admin, 'admin')->get(route('admin.blog-posts.index'))->assertOk();
        $this->actingAs($admin, 'admin')->get(route('admin.blog-posts.create'))->assertOk();
        $this->actingAs($admin, 'admin')->get(route('admin.blog-posts.edit', $post->id))->assertOk();
        $this->actingAs($admin, 'admin')->get(route('admin.blog-categories.index'))->assertOk();
        $this->actingAs($admin, 'admin')->get(route('admin.blog-categories.create'))->assertOk();
        $this->actingAs($admin, 'admin')->get(route('admin.blog-tags.index'))->assertOk();
        $this->actingAs($admin, 'admin')->get(route('admin.blog-tags.create'))->assertOk();
        $this->actingAs($admin, 'admin')->get(route('admin.blog-comments.index'))->assertOk();
    }

    public function test_guests_cannot_access_admin_blog_pages(): void
    {
        $this->get(route('admin.blog-posts.index'))->assertRedirect(route('admin.login'));
    }

    public function test_admin_can_create_a_post_with_new_tags(): void
    {
        $admin = $this->makeAdmin();
        $category = BlogCategory::create(['name' => 'Store Cat', 'slug' => 'store-cat-' . uniqid(), 'status' => true]);

        $title = 'Created Via Admin ' . uniqid();

        $response = $this->actingAs($admin, 'admin')->post(route('admin.blog-posts.store'), [
            'title' => $title,
            'slug' => '',
            'body' => '<p>Body</p>',
            'status' => BlogPost::STATUS_PUBLISHED,
            'categories' => [$category->id],
            'new_tags' => 'Fresh Tag One, Fresh Tag Two',
            'allow_comments' => '1',
        ]);

        $response->assertRedirect(route('admin.blog-posts.index'));

        $post = BlogPost::where('title', $title)->first();
        $this->assertNotNull($post);
        $this->assertSame(BlogPost::STATUS_PUBLISHED, $post->status);
        $this->assertNotNull($post->published_at, 'publishing without a date should stamp published_at');
        $this->assertSame($admin->id, $post->admin_id);
        $this->assertTrue($post->categories->pluck('id')->contains($category->id));
        $this->assertEqualsCanonicalizing(
            ['fresh-tag-one', 'fresh-tag-two'],
            $post->tags->pluck('slug')->all()
        );
    }

    public function test_admin_can_toggle_comment_visibility(): void
    {
        $admin = $this->makeAdmin();
        $user = $this->makeUser();
        $post = $this->makePost();
        $comment = BlogComment::create(['blog_post_id' => $post->id, 'user_id' => $user->id, 'body' => 'Toggle me', 'status' => true]);

        $this->actingAs($admin, 'admin')
            ->put(route('admin.blog-comments.toggle', $comment->id));

        $this->assertFalse($comment->fresh()->status, 'comment should be hidden after toggle');

        $this->actingAs($admin, 'admin')
            ->put(route('admin.blog-comments.toggle', $comment->id));

        $this->assertTrue($comment->fresh()->status, 'comment should be visible after second toggle');
    }
}

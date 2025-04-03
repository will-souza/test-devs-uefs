<?php

namespace Tests\Unit\Repositories;

use App\Interfaces\Repositories\PostRepositoryInterface;
use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class PostRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private PostRepositoryInterface $repository;
    private User $user;
    private array $tags;

    protected function setUp(): void
    {
        parent::setUp();

        Post::truncate();
        User::truncate();
        Tag::truncate();

        $this->repository = $this->app->make(PostRepositoryInterface::class);
        $this->user = User::factory()->create();
    }

    #[Test]
    public function it_can_return_all_posts()
    {
        Post::factory(3)->create(['user_id' => $this->user->id]);

        $result = $this->repository->all();

        $this->assertCount(6, $result);
        $this->assertArrayHasKey('user', $result[0]);
        $this->assertArrayHasKey('tags', $result[0]);
    }

    #[Test]
    public function it_can_find_a_post()
    {
        $post = Post::factory()->create(['user_id' => $this->user->id]);

        $testTags = Tag::factory(2)->create()->pluck('id')->toArray();
        $post->tags()->attach($testTags);

        $found = $this->repository->find($post->id);

        $this->assertNotNull($found);
        $this->assertEquals($post->id, $found['id']);
        $this->assertCount(2, $found['tags']);
    }

    #[Test]
    public function it_returns_null_when_post_not_found()
    {
        $found = $this->repository->find(999);
        $this->assertNull($found);
    }

    #[Test]
    public function it_can_create_a_post()
    {
        $data = [
            'title' => 'Test Post',
            'content' => 'Test content',
            'user_id' => $this->user->id
        ];

        $post = $this->repository->create($data);

        $this->assertDatabaseHas('posts', ['title' => 'Test Post']);
        $this->assertInstanceOf(Post::class, $post);
    }

    #[Test]
    public function it_can_update_a_post()
    {
        $post = Post::factory()->create(['user_id' => $this->user->id]);
        $data = ['title' => 'Updated Title'];

        $updated = $this->repository->update($post->id, $data);

        $this->assertEquals('Updated Title', $updated->title);
        $this->assertDatabaseHas('posts', ['title' => 'Updated Title']);
    }

    #[Test]
    public function it_can_delete_a_post()
    {
        $post = Post::factory()->create(['user_id' => $this->user->id]);

        $result = $this->repository->delete($post->id);

        $this->assertTrue($result);
        $this->assertDatabaseMissing('posts', ['id' => $post->id]);
    }

    #[Test]
    public function it_can_sync_tags()
    {
        $post = Post::factory()->create(['user_id' => $this->user->id]);
        $newTags = Tag::factory(2)->create()->pluck('id')->toArray();

        $post->tags()->detach();

        $this->repository->syncTags($post, $newTags);

        $this->assertCount(2, $post->fresh()->tags);
    }
}

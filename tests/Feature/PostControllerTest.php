<?php

namespace Tests\Feature;

use App\Interfaces\Repositories\PostRepositoryInterface;
use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class PostControllerTest extends TestCase
{
    use RefreshDatabase;

    private PostRepositoryInterface $repository;
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        Post::truncate();
        User::truncate();
        Tag::truncate();
        DB::table('post_tag')->truncate();

        $this->repository = $this->app->make(PostRepositoryInterface::class);
        $this->user = User::factory()->create();
    }

    #[Test]
    public function it_can_list_all_posts()
    {
        Post::factory(3)->create(['user_id' => $this->user->id]);

        $response = $this->getJson('/api/posts');

        $response->assertStatus(200)
            ->assertJsonCount(6)
            ->assertJsonStructure([
                '*' => ['id', 'title', 'content', 'user', 'tags']
            ]);
    }

    #[Test]
    public function it_can_create_a_post()
    {
        $data = [
            'title' => 'Test Post',
            'content' => 'This is a test post content',
            'user_id' => $this->user->id,
            'tags' => Tag::factory(3)->create()->pluck('id')->toArray(),
        ];

        $response = $this->postJson('/api/posts', $data);

        $response->assertStatus(201)
            ->assertJson([
                'title' => 'Test Post',
                'content' => 'This is a test post content',
                'user_id' => $this->user->id
            ]);
    }

    #[Test]
    public function it_shows_a_specific_post()
    {
        $post = Post::factory()->create(['user_id' => $this->user->id]);
        $newTags = Tag::factory(2)->create()->pluck('id')->toArray();
        $post->tags()->attach($newTags);

        $response = $this->getJson("/api/posts/{$post->id}");

        $response->assertStatus(200)
            ->assertJson([
                'id' => $post->id,
                'title' => $post->title,
                'user' => ['id' => $this->user->id]
            ])
            ->assertJsonCount(2, 'tags');
    }

    #[Test]
    public function it_returns_404_when_post_not_found()
    {
        $response = $this->getJson('/api/posts/999');
        $response->assertStatus(404);
    }

    #[Test]
    public function it_can_update_a_post()
    {
        $post = Post::factory()->create(['user_id' => $this->user->id]);
        $newTags = Tag::factory(2)->create()->pluck('id')->toArray();

        $data = [
            'title' => 'Updated Title',
            'content' => 'Updated content',
            'user_id' => $this->user->id, // Adicionando user_id obrigatório
            'tags' => $newTags
        ];

        $response = $this->putJson("/api/posts/{$post->id}", $data);

        $response->assertStatus(200)
            ->assertJson([
                'title' => 'Updated Title',
                'content' => 'Updated content'
            ]);
    }

    #[Test]
    public function it_can_delete_a_post()
    {
        $post = Post::factory()->create(['user_id' => $this->user->id]);

        $response = $this->deleteJson("/api/posts/{$post->id}");

        $response->assertStatus(200)
            ->assertJson(['message' => 'Post deletado com sucesso']);

        $this->assertDatabaseMissing('posts', ['id' => $post->id]);
    }
}

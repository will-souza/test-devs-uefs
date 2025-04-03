<?php

namespace Tests\Feature;

use App\Interfaces\Repositories\TagRepositoryInterface;
use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class TagControllerTest extends TestCase
{
    use RefreshDatabase;

    private TagRepositoryInterface $repository;

    protected function setUp(): void
    {
        parent::setUp();

        Post::truncate();
        User::truncate();
        Tag::truncate();
        DB::table('post_tag')->truncate();

        $this->repository = $this->app->make(TagRepositoryInterface::class);
    }

    #[Test]
    public function it_can_list_all_tags()
    {
        Tag::factory(3)->create();

        $response = $this->getJson('/api/tags');

        $response->assertStatus(200)
            ->assertJsonCount(3)
            ->assertJsonStructure([
                '*' => ['id', 'name', 'posts']
            ]);
    }

    #[Test]
    public function it_can_create_a_tag()
    {
        $data = ['name' => 'Test Tag'];

        $response = $this->postJson('/api/tags', $data);

        $response->assertStatus(201)
            ->assertJson(['name' => 'Test Tag']);

        $this->assertDatabaseHas('tags', ['name' => 'Test Tag']);
    }

    #[Test]
    public function it_shows_a_specific_tag()
    {
        $tag = Tag::factory()->create();

        $response = $this->getJson("/api/tags/{$tag->id}");

        $response->assertStatus(200)
            ->assertJson(['id' => $tag->id, 'name' => $tag->name]);
    }

    #[Test]
    public function it_returns_404_when_tag_not_found()
    {
        $response = $this->getJson('/api/tags/999');
        $response->assertStatus(404);
    }

    #[Test]
    public function it_can_update_a_tag()
    {
        $tag = Tag::factory()->create(['name' => 'Old Name']);

        $response = $this->putJson("/api/tags/{$tag->id}", ['name' => 'New Name']);

        $response->assertStatus(200)
            ->assertJson(['name' => 'New Name']);

        $this->assertDatabaseHas('tags', ['name' => 'New Name']);
    }

    #[Test]
    public function it_can_delete_a_tag()
    {
        $tag = Tag::factory()->create();

        $response = $this->deleteJson("/api/tags/{$tag->id}");

        $response->assertStatus(200)
            ->assertJson(['message' => 'Tag deletada com sucesso']);

        $this->assertDatabaseMissing('tags', ['id' => $tag->id]);
    }
}

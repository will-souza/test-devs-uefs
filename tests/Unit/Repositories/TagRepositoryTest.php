<?php

namespace Tests\Unit\Repositories;

use App\Interfaces\Repositories\TagRepositoryInterface;
use App\Models\Tag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class TagRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private TagRepositoryInterface $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = $this->app->make(TagRepositoryInterface::class);
    }

    #[Test]
    public function it_can_return_all_tags()
    {
        Tag::factory(3)->create();

        $result = $this->repository->all();

        $this->assertCount(3, $result);
        $this->assertArrayHasKey('posts', $result[0]);
    }

    #[Test]
    public function it_can_find_a_tag()
    {
        $tag = Tag::factory()->create();

        $found = $this->repository->find($tag->id);

        $this->assertNotNull($found);
        $this->assertEquals($tag->id, $found['id']);
    }

    #[Test]
    public function it_returns_null_when_tag_not_found()
    {
        $found = $this->repository->find(999);
        $this->assertNull($found);
    }

    #[Test]
    public function it_can_create_a_tag()
    {
        $data = ['name' => 'Test Tag'];

        $result = $this->repository->create($data);

        $this->assertArrayHasKey('id', $result);
        $this->assertEquals('Test Tag', $result['name']);
        $this->assertDatabaseHas('tags', ['name' => 'Test Tag']);
    }

    #[Test]
    public function it_can_update_a_tag()
    {
        $tag = Tag::factory()->create(['name' => 'Old Name']);
        $data = ['name' => 'New Name'];

        $result = $this->repository->update($tag->id, $data);

        $this->assertEquals('New Name', $result['name']);
        $this->assertDatabaseHas('tags', ['name' => 'New Name']);
    }

    #[Test]
    public function it_can_delete_a_tag()
    {
        $tag = Tag::factory()->create();

        $result = $this->repository->delete($tag->id);

        $this->assertTrue($result);
        $this->assertDatabaseMissing('tags', ['id' => $tag->id]);
    }
}

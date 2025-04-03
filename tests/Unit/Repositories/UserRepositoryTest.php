<?php

namespace Tests\Unit\Repositories;

use App\Interfaces\Repositories\UserRepositoryInterface;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class UserRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private UserRepositoryInterface $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = $this->app->make(UserRepositoryInterface::class);
    }

    #[Test]
    public function it_can_return_all_users()
    {
        User::factory(3)->create();

        $result = $this->repository->all();

        $this->assertCount(3, $result);
        $this->assertArrayHasKey('posts', $result[0]);
    }

    #[Test]
    public function it_can_find_a_user()
    {
        $user = User::factory()->create();

        $found = $this->repository->find($user->id);

        $this->assertNotNull($found);
        $this->assertEquals($user->id, $found['id']);
        $this->assertEquals($user->name, $found['name']);
    }

    #[Test]
    public function it_returns_null_when_user_not_found()
    {
        $found = $this->repository->find(999);
        $this->assertNull($found);
    }

    #[Test]
    public function it_can_create_a_user()
    {
        $data = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123'
        ];

        $result = $this->repository->create($data);

        $this->assertArrayHasKey('id', $result);
        $this->assertEquals('Test User', $result['name']);
        $this->assertEquals('test@example.com', $result['email']);
        $this->assertDatabaseHas('users', ['email' => 'test@example.com']);
    }

    #[Test]
    public function it_hashes_password_when_creating_user()
    {
        $data = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123'
        ];

        $this->repository->create($data);

        $user = User::first();
        $this->assertTrue(Hash::check('password123', $user->password));
    }

    #[Test]
    public function it_can_update_a_user()
    {
        $user = User::factory()->create(['name' => 'Old Name']);
        $data = ['name' => 'New Name'];

        $result = $this->repository->update($user->id, $data);

        $this->assertEquals('New Name', $result['name']);
        $this->assertDatabaseHas('users', ['name' => 'New Name']);
    }

    #[Test]
    public function it_can_delete_a_user()
    {
        $user = User::factory()->create();

        $result = $this->repository->delete($user->id);

        $this->assertTrue($result);
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }

    #[Test]
    public function it_includes_posts_when_fetching_users()
    {
        Post::query()->delete();
        User::query()->delete();

        $user = User::factory()->create();
        $posts = Post::factory(2)->create(['user_id' => $user->id]);
        $result = $this->repository->find($user->id);

        $this->assertArrayHasKey('posts', $result);

        $postsArray = $result['posts'] instanceof \Illuminate\Http\Resources\Json\AnonymousResourceCollection
            ? $result['posts']->resolve()
            : $result['posts'];

        $this->assertCount(5, $postsArray);

        $resultPostIds = array_column($postsArray, 'id');
        $expectedPostIds = $posts->pluck('id')->toArray();

        $this->assertEquals(
            sort($expectedPostIds),
            sort($resultPostIds)
        );
    }
}

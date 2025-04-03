<?php

namespace Tests\Feature;

use App\Interfaces\Repositories\UserRepositoryInterface;
use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    private UserRepositoryInterface $repository;

    protected function setUp(): void
    {
        parent::setUp();

        Post::truncate();
        User::truncate();
        Tag::truncate();
        DB::table('post_tag')->truncate();

        $this->repository = $this->app->make(UserRepositoryInterface::class);
    }

    #[Test]
    public function it_can_list_all_users()
    {
        User::factory(3)->create();

        $response = $this->getJson('/api/users');

        $response->assertStatus(200)
            ->assertJsonCount(3)
            ->assertJsonStructure([
                '*' => ['id', 'name', 'email', 'posts']
            ]);
    }

    #[Test]
    public function it_can_create_a_user()
    {
        $data = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
        ];

        $response = $this->postJson('/api/users', $data);

        $response->assertStatus(201)
            ->assertJson([
                'name' => 'Test User',
                'email' => 'test@example.com'
            ]);

        $this->assertDatabaseHas('users', ['email' => 'test@example.com']);
    }

    #[Test]
    public function it_shows_a_specific_user()
    {
        $user = User::factory()->create();

        $response = $this->getJson("/api/users/{$user->id}");

        $response->assertStatus(200)
            ->assertJson([
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email
            ]);
    }

    #[Test]
    public function it_returns_404_when_user_not_found()
    {
        $response = $this->getJson('/api/users/999');
        $response->assertStatus(404);
    }

    #[Test]
    public function it_can_update_a_user()
    {
        $user = User::factory()->create(['name' => 'Old Name']);

        $response = $this->putJson("/api/users/{$user->id}", [
            'name' => 'New Name',
            'email' => 'newemail@example.com', // Novo email único
            'password' => 'newpassword',
        ]);

        $response->assertStatus(200)
            ->assertJson(['name' => 'New Name']);

        $this->assertDatabaseHas('users', ['name' => 'New Name']);
    }

    #[Test]
    public function it_can_delete_a_user()
    {
        $user = User::factory()->create();

        $response = $this->deleteJson("/api/users/{$user->id}");

        $response->assertStatus(200)
            ->assertJson(['message' => 'Usuário deletado com sucesso']);

        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }
}

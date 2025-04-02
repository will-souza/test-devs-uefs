<?php

namespace App\Interfaces\Repositories;

use App\Models\Post;

interface PostRepositoryInterface
{
    public function all(): array;
    public function find(int $id): ?array;
    public function create(array $data): array;
    public function update(int $id, array $data): array;
    public function delete(int $id): bool;
    public function syncTags(Post $post, array $tagIds): void;
}

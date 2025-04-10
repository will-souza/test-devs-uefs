<?php

namespace App\Repositories;

use App\Http\Resources\PostResource;
use App\Interfaces\Repositories\PostRepositoryInterface;
use App\Models\Post;

class PostRepository implements PostRepositoryInterface
{
    public function all(): array
    {
        return PostResource::collection(Post::with(['user', 'tags'])->get())->resolve();
    }

    public function find(int $id): ?array
    {
        $post = Post::with(['user', 'tags'])->find($id);
        return $post ? (new PostResource($post))->resolve() : null;
    }

    public function create(array $data): Post
    {
        return Post::create($data);
    }

    public function update(int $id, array $data): Post
    {
        $post = Post::findOrFail($id);
        $post->update($data);
        return $post;
    }

    public function delete(int $id): bool
    {
        return Post::destroy($id);
    }

    public function syncTags(Post $post, array $tagIds): void
    {
        $post->tags()->sync($tagIds);
    }
}

<?php

namespace App\Repositories;

use App\Http\Resources\TagResource;
use App\Interfaces\Repositories\TagRepositoryInterface;
use App\Models\Tag;

class TagRepository implements TagRepositoryInterface
{
    public function all(): array
    {
        return TagResource::collection(Tag::with('posts')->get())->resolve();
    }

    public function find(int $id): ?array
    {
        $tag = Tag::with('posts')->find($id);
        return $tag ? (new TagResource($tag))->resolve() : null;
    }

    public function create(array $data): array
    {
        $tag = Tag::create($data);
        return (new TagResource($tag))->resolve();
    }

    public function update(int $id, array $data): array
    {
        $tag = Tag::findOrFail($id);
        $tag->update($data);
        return (new TagResource($tag->fresh()))->resolve();
    }

    public function delete(int $id): bool
    {
        return Tag::destroy($id);
    }
}

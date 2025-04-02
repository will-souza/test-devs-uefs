<?php

namespace App\Repositories;

use App\Http\Resources\UserResource;
use App\Interfaces\Repositories\UserRepositoryInterface;
use App\Models\User;

class UserRepository implements UserRepositoryInterface
{
    public function all(): array
    {
        return UserResource::collection(User::with('posts')->get())->resolve();
    }

    public function find(int $id): ?array
    {
        $user = User::with('posts')->find($id);
        return $user ? (new UserResource($user))->resolve() : null;
    }

    public function create(array $data): array
    {
        $user = User::create($data);
        return (new UserResource($user))->resolve();
    }

    public function update(int $id, array $data): array
    {
        $user = User::findOrFail($id);
        $user->update($data);
        return (new UserResource($user->fresh()))->resolve();
    }

    public function delete(int $id): bool
    {
        return User::destroy($id);
    }
}

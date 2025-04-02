<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PostRequest;
use App\Interfaces\Repositories\PostRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request as HttpRequest;

class PostController extends Controller
{
    public function __construct(
        private PostRepositoryInterface $postRepository
    ) {}

    public function index(): JsonResponse
    {
        $posts = $this->postRepository->all();
        return response()->json($posts);
    }

    public function store(PostRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $post = $this->postRepository->create($validated);

        if (isset($validated['tags'])) {
            $this->postRepository->syncTags($post, $validated['tags']);
        }

        return response()->json($post, 201);
    }

    public function show(int $id): JsonResponse
    {
        $post = $this->postRepository->find($id);

        return $post
            ? response()->json($post)
            : response()->json(['message' => 'Post não encontrado'], 404);
    }

    public function update(PostRequest $request, int $id): JsonResponse
    {
        $validated = $request->validated();
        $post = $this->postRepository->update($id, $validated);

        if (!$post) {
            return response()->json(['message' => 'Post não encontrado'], 404);
        }

        if (isset($validated['tags'])) {
            $this->postRepository->syncTags($post, $validated['tags']);
        }

        return response()->json($post);
    }

    public function destroy(int $id): JsonResponse
    {
        $result = $this->postRepository->delete($id);

        return $result
            ? response()->json(['message' => 'Post deletado com sucesso'])
            : response()->json(['message' => 'Post não encontrado'], 404);
    }
}

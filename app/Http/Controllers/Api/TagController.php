<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\TagRequest;
use App\Interfaces\Repositories\TagRepositoryInterface;
use Illuminate\Http\JsonResponse;

class TagController extends Controller
{
    public function __construct(
        private TagRepositoryInterface $tagRepository
    ) {}

    public function index(): JsonResponse
    {
        $tags = $this->tagRepository->all();
        return response()->json($tags);
    }

    public function store(TagRequest $request): JsonResponse
    {
        $tag = $this->tagRepository->create($request->validated());
        return response()->json($tag, 201);
    }

    public function show(int $id): JsonResponse
    {
        $tag = $this->tagRepository->find($id);

        return $tag
            ? response()->json($tag)
            : response()->json(['message' => 'Tag não encontrada'], 404);
    }

    public function update(TagRequest $request, int $id): JsonResponse
    {
        $tag = $this->tagRepository->update($id, $request->validated());

        return $tag
            ? response()->json($tag)
            : response()->json(['message' => 'Tag não encontrada'], 404);
    }

    public function destroy(int $id): JsonResponse
    {
        $result = $this->tagRepository->delete($id);

        return $result
            ? response()->json(['message' => 'Tag deletada com sucesso'])
            : response()->json(['message' => 'Tag não encontrada'], 404);
    }
}

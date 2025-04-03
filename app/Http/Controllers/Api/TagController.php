<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\TagRequest;
use App\Interfaces\Repositories\TagRepositoryInterface;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class TagController extends Controller
{
    public function __construct(
        private TagRepositoryInterface $tagRepository
    ) {}

    #[OA\Get(
        path: '/tags',
        summary: 'Listar todas as tags',
        tags: ['Tags'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Lista de tags retornada com sucesso',
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items(ref: '#/components/schemas/Tag')
                )
            )
        ]
    )]
    public function index(): JsonResponse
    {
        $tags = $this->tagRepository->all();
        return response()->json($tags);
    }

    #[OA\Post(
        path: '/tags',
        summary: 'Criar uma nova tag',
        tags: ['Tags'],
        requestBody: new OA\RequestBody(
            description: 'Dados da tag a ser criada',
            required: true,
            content: new OA\JsonContent(ref: '#/components/schemas/TagCreate')
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Tag criada com sucesso',
                content: new OA\JsonContent(ref: '#/components/schemas/Tag')
            ),
            new OA\Response(
                response: 422,
                description: 'Erro de validação',
                content: new OA\JsonContent(ref: '#/components/schemas/ValidationError')
            )
        ]
    )]
    public function store(TagRequest $request): JsonResponse
    {
        $tag = $this->tagRepository->create($request->validated());
        return response()->json($tag, 201);
    }

    #[OA\Get(
        path: '/tags/{id}',
        summary: 'Obter detalhes de uma tag',
        tags: ['Tags'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                description: 'ID da tag',
                schema: new OA\Schema(type: 'integer')
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Tag encontrada',
                content: new OA\JsonContent(ref: '#/components/schemas/Tag')
            ),
            new OA\Response(
                response: 404,
                description: 'Tag não encontrada'
            )
        ]
    )]
    public function show(int $id): JsonResponse
    {
        $tag = $this->tagRepository->find($id);

        return $tag
            ? response()->json($tag)
            : response()->json(['message' => 'Tag não encontrada'], 404);
    }

    #[OA\Put(
        path: '/tags/{id}',
        summary: 'Atualizar uma tag',
        tags: ['Tags'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                description: 'ID da tag',
                schema: new OA\Schema(type: 'integer')
            )
        ],
        requestBody: new OA\RequestBody(
            description: 'Dados da tag a ser atualizada',
            required: true,
            content: new OA\JsonContent(ref: '#/components/schemas/TagUpdate')
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Tag atualizada com sucesso',
                content: new OA\JsonContent(ref: '#/components/schemas/Tag')
            ),
            new OA\Response(
                response: 404,
                description: 'Tag não encontrada'
            ),
            new OA\Response(
                response: 422,
                description: 'Erro de validação',
                content: new OA\JsonContent(ref: '#/components/schemas/ValidationError')
            )
        ]
    )]
    public function update(TagRequest $request, int $id): JsonResponse
    {
        $tag = $this->tagRepository->update($id, $request->validated());

        return $tag
            ? response()->json($tag)
            : response()->json(['message' => 'Tag não encontrada'], 404);
    }

    #[OA\Delete(
        path: '/tags/{id}',
        summary: 'Excluir uma tag',
        tags: ['Tags'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                description: 'ID da tag',
                schema: new OA\Schema(type: 'integer')
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Tag excluída com sucesso',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string')
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: 'Tag não encontrada'
            )
        ]
    )]
    public function destroy(int $id): JsonResponse
    {
        $result = $this->tagRepository->delete($id);

        return $result
            ? response()->json(['message' => 'Tag deletada com sucesso'])
            : response()->json(['message' => 'Tag não encontrada'], 404);
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PostRequest;
use App\Interfaces\Repositories\PostRepositoryInterface;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class PostController extends Controller
{
    public function __construct(
        private PostRepositoryInterface $postRepository
    ) {}

    #[OA\Get(
        path: '/posts',
        summary: 'Listar todos os posts',
        tags: ['Posts'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Lista de posts retornada com sucesso',
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items(ref: '#/components/schemas/Post')
                )
            )
        ]
    )]
    public function index(): JsonResponse
    {
        $posts = $this->postRepository->all();
        return response()->json($posts);
    }

    #[OA\Post(
        path: '/posts',
        summary: 'Criar um novo post',
        tags: ['Posts'],
        requestBody: new OA\RequestBody(
            description: 'Dados do post a ser criado',
            required: true,
            content: new OA\JsonContent(ref: '#/components/schemas/PostCreate')
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Post criado com sucesso',
                content: new OA\JsonContent(ref: '#/components/schemas/Post')
            ),
            new OA\Response(
                response: 422,
                description: 'Erro de validação',
                content: new OA\JsonContent(ref: '#/components/schemas/ValidationError')
            )
        ]
    )]
    public function store(PostRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $post = $this->postRepository->create($validated);

        if (isset($validated['tags'])) {
            $this->postRepository->syncTags($post, $validated['tags']);
        }

        return response()->json($post, 201);
    }

    #[OA\Get(
        path: '/posts/{id}',
        summary: 'Obter detalhes de um post',
        tags: ['Posts'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                description: 'ID do post',
                schema: new OA\Schema(type: 'integer')
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Post encontrado',
                content: new OA\JsonContent(ref: '#/components/schemas/Post')
            ),
            new OA\Response(
                response: 404,
                description: 'Post não encontrado'
            )
        ]
    )]
    public function show(int $id): JsonResponse
    {
        $post = $this->postRepository->find($id);

        return $post
            ? response()->json($post)
            : response()->json(['message' => 'Post não encontrado'], 404);
    }

    #[OA\Put(
        path: '/posts/{id}',
        summary: 'Atualizar um post',
        tags: ['Posts'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                description: 'ID do post',
                schema: new OA\Schema(type: 'integer')
            )
        ],
        requestBody: new OA\RequestBody(
            description: 'Dados do post a ser atualizado',
            required: true,
            content: new OA\JsonContent(ref: '#/components/schemas/PostUpdate')
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Post atualizado com sucesso',
                content: new OA\JsonContent(ref: '#/components/schemas/Post')
            ),
            new OA\Response(
                response: 404,
                description: 'Post não encontrado'
            ),
            new OA\Response(
                response: 422,
                description: 'Erro de validação',
                content: new OA\JsonContent(ref: '#/components/schemas/ValidationError')
            )
        ]
    )]
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

    #[OA\Delete(
        path: '/posts/{id}',
        summary: 'Excluir um post',
        tags: ['Posts'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                description: 'ID do post',
                schema: new OA\Schema(type: 'integer')
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Post excluído com sucesso',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string')
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: 'Post não encontrado'
            )
        ]
    )]
    public function destroy(int $id): JsonResponse
    {
        $result = $this->postRepository->delete($id);

        return $result
            ? response()->json(['message' => 'Post deletado com sucesso'])
            : response()->json(['message' => 'Post não encontrado'], 404);
    }
}

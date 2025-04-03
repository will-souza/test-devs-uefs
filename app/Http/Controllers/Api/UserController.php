<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Interfaces\Repositories\UserRepositoryInterface;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class UserController extends Controller
{
    public function __construct(
        private UserRepositoryInterface $userRepository
    ) {}

    #[OA\Get(
        path: '/users',
        summary: 'Listar todos os usuários',
        tags: ['Users'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Lista de usuários retornada com sucesso',
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items(ref: '#/components/schemas/User')
                )
            )
        ]
    )]
    public function index(): JsonResponse
    {
        $users = $this->userRepository->all();
        return response()->json($users);
    }

    #[OA\Post(
        path: '/users',
        summary: 'Criar um novo usuário',
        tags: ['Users'],
        requestBody: new OA\RequestBody(
            description: 'Dados do usuário a ser criado',
            required: true,
            content: new OA\JsonContent(ref: '#/components/schemas/UserCreate')
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Usuário criado com sucesso',
                content: new OA\JsonContent(ref: '#/components/schemas/User')
            ),
            new OA\Response(
                response: 422,
                description: 'Erro de validação',
                content: new OA\JsonContent(ref: '#/components/schemas/ValidationError')
            )
        ]
    )]
    public function store(UserRequest $request): JsonResponse
    {
        $user = $this->userRepository->create($request->validated());
        return response()->json($user, 201);
    }

    #[OA\Get(
        path: '/users/{id}',
        summary: 'Obter detalhes de um usuário',
        tags: ['Users'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                description: 'ID do usuário',
                schema: new OA\Schema(type: 'integer')
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Usuário encontrado',
                content: new OA\JsonContent(ref: '#/components/schemas/User')
            ),
            new OA\Response(
                response: 404,
                description: 'Usuário não encontrado'
            )
        ]
    )]
    public function show(int $id): JsonResponse
    {
        $user = $this->userRepository->find($id);

        return $user
            ? response()->json($user)
            : response()->json(['message' => 'Usuário não encontrado'], 404);
    }

    #[OA\Put(
        path: '/users/{id}',
        summary: 'Atualizar um usuário',
        tags: ['Users'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                description: 'ID do usuário',
                schema: new OA\Schema(type: 'integer')
            )
        ],
        requestBody: new OA\RequestBody(
            description: 'Dados do usuário a ser atualizado',
            required: true,
            content: new OA\JsonContent(ref: '#/components/schemas/UserUpdate')
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Usuário atualizado com sucesso',
                content: new OA\JsonContent(ref: '#/components/schemas/User')
            ),
            new OA\Response(
                response: 404,
                description: 'Usuário não encontrado'
            ),
            new OA\Response(
                response: 422,
                description: 'Erro de validação',
                content: new OA\JsonContent(ref: '#/components/schemas/ValidationError')
            )
        ]
    )]
    public function update(UserRequest $request, int $id): JsonResponse
    {
        $user = $this->userRepository->update($id, $request->validated());

        return $user
            ? response()->json($user)
            : response()->json(['message' => 'Usuário não encontrado'], 404);
    }

    #[OA\Delete(
        path: '/users/{id}',
        summary: 'Excluir um usuário',
        tags: ['Users'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                description: 'ID do usuário',
                schema: new OA\Schema(type: 'integer')
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Usuário excluído com sucesso',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string')
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: 'Usuário não encontrado'
            )
        ]
    )]
    public function destroy(int $id): JsonResponse
    {
        $result = $this->userRepository->delete($id);

        return $result
            ? response()->json(['message' => 'Usuário deletado com sucesso'])
            : response()->json(['message' => 'Usuário não encontrado'], 404);
    }
}

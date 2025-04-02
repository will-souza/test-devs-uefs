<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Interfaces\Repositories\UserRepositoryInterface;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    public function __construct(
        private UserRepositoryInterface $userRepository
    ) {}

    public function index(): JsonResponse
    {
        $users = $this->userRepository->all();
        return response()->json($users);
    }

    public function store(UserRequest $request): JsonResponse
    {
        $user = $this->userRepository->create($request->validated());
        return response()->json($user, 201);
    }

    public function show(int $id): JsonResponse
    {
        $user = $this->userRepository->find($id);

        return $user
            ? response()->json($user)
            : response()->json(['message' => 'Usuário não encontrado'], 404);
    }

    public function update(UserRequest $request, int $id): JsonResponse
    {
        $user = $this->userRepository->update($id, $request->validated());

        return $user
            ? response()->json($user)
            : response()->json(['message' => 'Usuário não encontrado'], 404);
    }

    public function destroy(int $id): JsonResponse
    {
        $result = $this->userRepository->delete($id);

        return $result
            ? response()->json(['message' => 'Usuário deletado com sucesso'])
            : response()->json(['message' => 'Usuário não encontrado'], 404);
    }
}

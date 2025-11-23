<?php

declare(strict_types=1);

namespace App\Http\Apis\Todos;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

final class TodoController extends Controller
{
    public function __construct(
        private readonly TodoService $todoService
    ) {}

    /**
     * [index description]
     */
    public function index(): JsonResponse
    {
        return response()->json($this->todoService->getTodos());
    }

    public function store(TodoRequest $todoRequest): JsonResponse
    {
        return response()->json(
            $this->todoService->createTodo($todoRequest->validated()),
            201
        );
    }

    public function show($id): JsonResponse
    {
        return response()->json($this->todoService->find($id));
    }

    public function update(TodoRequest $todoRequest, $id): JsonResponse
    {
        return response()->json(
            $this->todoService->updateTodo($id, $todoRequest->validated())
        );
    }

    public function destroy($id): JsonResponse
    {
        $this->todoService->deleteTodo($id);

        return response()->json(null, 204);
    }
}

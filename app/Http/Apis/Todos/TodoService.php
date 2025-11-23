<?php

declare(strict_types=1);

namespace App\Http\Apis\Todos;

final readonly class TodoService
{
    public function __construct(private ITodoRepository $todoRepository)
    {
        // Silence is golden
    }

    public function getTodos()
    {
        return $this->todoRepository->all();
    }

    public function createTodo(array $data)
    {
        return $this->todoRepository->create($data);
    }

    public function updateTodo($id, array $data)
    {
        return $this->todoRepository->update($id, $data);
    }

    public function deleteTodo($id)
    {
        return $this->todoRepository->delete($id);
    }

    public function find($id)
    {
        return $this->todoRepository->find($id);
    }
}

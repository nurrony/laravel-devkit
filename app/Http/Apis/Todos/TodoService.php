<?php

declare(strict_types=1);

namespace App\Http\Apis\Todos;

use App\Models\Todo;

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

    public function createTodo(Todo $data)
    {
        return $this->todoRepository->create($data);
    }

    public function updateTodo($id, Todo $data)
    {
        return $this->todoRepository->update($id, $data);
    }

    public function deleteTodo($id): void
    {
        $this->todoRepository->delete($id);
    }

    public function find($id): Todo
    {
        return $this->todoRepository->find($id);
    }

    public function sortByCompletionAheadOfDue()
    {
        return $this->todoRepository->sortByCompletionAheadOfDue();
    }
}

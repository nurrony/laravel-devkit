<?php

declare(strict_types=1);

namespace App\Http\Apis\Todos;

use App\Models\Todo;

final class TodoRepository implements ITodoRepository
{
    public function all(): mixed
    {
        return Todo::query()->latest()->paginate(10);
    }

    public function find($id): Todo
    {
        return Todo::query()->findOrFail($id);
    }

    public function create(Todo $data): Todo
    {
        return Todo::query()->create($data->getAttributes());
    }

    public function update($id, Todo $data): Todo
    {
        $todo = $this->find($id);
        $todo->update($data);

        return $todo;
    }

    public function delete($id): void
    {
        $todo = $this->find($id);

        $todo->delete();
    }

    public function sortByCompletionAheadOfDue($id): void
    {
        // TODO
    }
}

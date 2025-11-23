<?php

declare(strict_types=1);

namespace App\Http\Apis\Todos;

use App\Models\Todo;

final class TodoRepository implements ITodoRepository
{
    public function all()
    {
        return Todo::query()->latest()->paginate(10);
    }

    public function find($id)
    {
        return Todo::query()->findOrFail($id);
    }

    public function create(array $data)
    {
        return Todo::query()->create($data);
    }

    public function update($id, array $data)
    {
        $todo = $this->find($id);
        $todo->update($data);

        return $todo;
    }

    public function delete($id)
    {
        $todo = $this->find($id);

        return $todo->delete();
    }
}

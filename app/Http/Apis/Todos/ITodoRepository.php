<?php

declare(strict_types=1);

namespace App\Http\Apis\Todos;

use App\Models\Todo;

interface ITodoRepository
{
    public function all(): mixed;

    public function find($id): Todo;

    public function create(Todo $todo): Todo;

    public function update($id, Todo $todo): Todo;

    public function delete($id): void;

    public function sortByCompletionAheadOfDue($id): void;
}

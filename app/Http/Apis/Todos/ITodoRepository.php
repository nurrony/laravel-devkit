<?php

declare(strict_types=1);

namespace App\Http\Apis\Todos;

interface ITodoRepository
{
    public function all();

    public function find($id);

    public function create(array $data);

    public function update($id, array $data);

    public function delete($id);
}

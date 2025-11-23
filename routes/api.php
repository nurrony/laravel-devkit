<?php

declare(strict_types=1);

use App\Http\Apis\Todos\TodoController;
use Illuminate\Support\Facades\Route;

Route::apiResource('todos', TodoController::class);

<?php

declare(strict_types=1);

use App\Http\Apis\Todos\TodoController;
use App\Http\Controllers\Relations\OneToOneRelation;
use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::apiResource('todos', TodoController::class);

Route::get('users', fn () => response()->json(User::with(['profile:first_name,last_name,birthday,user_id'])->get()))->name('users.index');

Route::prefix('/relations')->group(function (): void {
    Route::prefix('one-to-one')->group(function (): void {
        Route::get('by-orm', [OneToOneRelation::class, 'usingOrm'])->name('relations.one2one.byOrm');
        Route::get('by-factory', [OneToOneRelation::class, 'byFactory'])->name('relations.one2one.by-factory');
        Route::get('by-owner', [OneToOneRelation::class, 'byOwnerRelation'])->name('relations.one2one.byOwnerRelation');
    });
});

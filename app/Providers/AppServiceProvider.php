<?php

declare(strict_types=1);

namespace App\Providers;

use App\Http\Apis\Todos\ITodoRepository;
use App\Http\Apis\Todos\TodoRepository;
use Illuminate\Support\ServiceProvider;

final class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(ITodoRepository::class, TodoRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}

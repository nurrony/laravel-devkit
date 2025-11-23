<?php

declare(strict_types=1);

use App\Exceptions\ApiExceptionHandler;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (Throwable $throwable, Request $request) {
            // Get the exception class name
            $className = $throwable::class;

            // Get our custom handlers
            $handlers = ApiExceptionHandler::$handlers;

            // Check if we have a specific handler for this exception
            if (array_key_exists($className, $handlers)) {
                $method = $handlers[$className];
                $apiExceptionHandler = new ApiExceptionHandler;

                return $apiExceptionHandler->$method($throwable, $request);
            }

            // Fallback to default error response
            return response()->json([
                'error' => [
                    'type' => basename($throwable::class),
                    'status' => $throwable->getCode() ?: 500,
                    'message' => $throwable->getMessage() ?: 'An unexpected error occurred',
                    'timestamp' => now()->toISOString(),
                    // Include debug info only in non-production environments
                    'debug' => app()->environment('local', 'test') ? [
                        'file' => $throwable->getFile(),
                        'line' => $throwable->getLine(),
                        'trace' => $throwable->getTraceAsString(),
                    ] : null,
                ],
            ], $throwable->getCode() ?: 500);
        });
    })->create();

<?php

declare(strict_types=1);

namespace App\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

final class ApiExceptionHandler
{
    /**
     * Map of exception classes to their handler methods
     */
    public static array $handlers = [
        AuthenticationException::class => 'handleAuthenticationException',
        AccessDeniedHttpException::class => 'handleAuthenticationException',
        AuthorizationException::class => 'handleAuthorizationException',
        ValidationException::class => 'handleValidationException',
        ModelNotFoundException::class => 'handleNotFoundException',
        NotFoundHttpException::class => 'handleNotFoundException',
        MethodNotAllowedHttpException::class => 'handleMethodNotAllowedException',
        HttpException::class => 'handleHttpException',
        QueryException::class => 'handleQueryException',
    ];

    /**
     * Handle authentication exceptions
     */
    public function handleAuthenticationException(
        AuthenticationException|AccessDeniedHttpException $e
    ): JsonResponse {
        $this->logException($e, 'Authentication failed');

        return response()->json([
            'error' => [
                'type' => $this->getExceptionType($e),
                'status' => 401,
                'message' => 'Authentication required. Please provide valid credentials.',
                'timestamp' => now()->toISOString(),
            ],
        ], 401);
    }

    /**
     * Handle authorization exceptions
     */
    public function handleAuthorizationException(
        AuthorizationException $authorizationException
    ): JsonResponse {
        $this->logException($authorizationException, 'Authorization failed');

        return response()->json([
            'error' => [
                'type' => $this->getExceptionType($authorizationException),
                'status' => 403,
                'message' => 'You do not have permission to perform this action.',
                'timestamp' => now()->toISOString(),
            ],
        ], 403);
    }

    /**
     * Handle validation exceptions
     */
    public function handleValidationException(
        ValidationException $validationException
    ): JsonResponse {
        $errors = [];

        foreach ($validationException->errors() as $field => $messages) {
            foreach ($messages as $message) {
                $errors[] = [
                    'field' => $field,
                    'message' => $message,
                ];
            }
        }

        $this->logException($validationException, 'Validation failed', ['errors' => $errors]);

        return response()->json([
            'error' => [
                'type' => $this->getExceptionType($validationException),
                'status' => 422,
                'message' => 'The provided data is invalid.',
                'timestamp' => now()->toISOString(),
                'validation_errors' => $errors,
            ],
        ], 422);
    }

    /**
     * Handle not found exceptions
     */
    public function handleNotFoundException(
        ModelNotFoundException|NotFoundHttpException $e,
        Request $request
    ): JsonResponse {
        $this->logException($e, 'Resource not found');

        $message = $e instanceof ModelNotFoundException
            ? 'The requested resource was not found.'
            : sprintf("The requested endpoint '%s' was not found.", $request->getRequestUri());

        return response()->json([
            'error' => [
                'type' => $this->getExceptionType($e),
                'status' => 404,
                'message' => $message,
                'timestamp' => now()->toISOString(),
            ],
        ], 404);
    }

    /**
     * Handle method not allowed exceptions
     */
    public function handleMethodNotAllowedException(
        MethodNotAllowedHttpException $methodNotAllowedHttpException,
        Request $request
    ): JsonResponse {
        $this->logException($methodNotAllowedHttpException, 'Method not allowed');

        return response()->json([
            'error' => [
                'type' => $this->getExceptionType($methodNotAllowedHttpException),
                'status' => 405,
                'message' => sprintf('The %s method is not allowed for this endpoint.', $request->method()),
                'timestamp' => now()->toISOString(),
                'allowed_methods' => $methodNotAllowedHttpException->getHeaders()['Allow'] ?? 'Unknown',
            ],
        ], 405);
    }

    /**
     * Handle general HTTP exceptions
     */
    public function handleHttpException(HttpException $httpException): JsonResponse
    {
        $this->logException($httpException, 'HTTP exception occurred');

        return response()->json([
            'error' => [
                'type' => $this->getExceptionType($httpException),
                'status' => $httpException->getStatusCode(),
                'message' => $httpException->getMessage() ?: 'An HTTP error occurred.',
                'timestamp' => now()->toISOString(),
            ],
        ], $httpException->getStatusCode());
    }

    /**
     * Handle database query exceptions
     */
    public function handleQueryException(QueryException $queryException): JsonResponse
    {
        $this->logException($queryException, 'Database query failed', ['sql' => $queryException->getSql()]);

        // Handle specific database constraint violations
        $errorCode = $queryException->errorInfo[1] ?? null;

        return match ($errorCode) {
            // Foreign key constraint violation
            1451 => response()->json([
                'error' => [
                    'type' => $this->getExceptionType($queryException),
                    'status' => 409,
                    'message' => 'Cannot delete this resource because it is referenced by other records.',
                    'timestamp' => now()->toISOString(),
                ],
            ], 409),
            // Duplicate entry
            1062 => response()->json([
                'error' => [
                    'type' => $this->getExceptionType($queryException),
                    'status' => 409,
                    'message' => 'A record with this information already exists.',
                    'timestamp' => now()->toISOString(),
                ],
            ], 409),
            default => response()->json([
                'error' => [
                    'type' => $this->getExceptionType($queryException),
                    'status' => 500,
                    'message' => 'A database error occurred. Please try again later.',
                    'timestamp' => now()->toISOString(),
                ],
            ], 500),
        };
    }

    /**
     * Extract a clean exception type name
     */
    private function getExceptionType(Throwable $throwable): string
    {
        return basename(str_replace('\\', '/', $throwable::class));
    }

    /**
     * Log exception with context
     */
    private function logException(Throwable $throwable, string $message, array $context = []): void
    {
        $logContext = array_merge([
            'exception' => $throwable::class,
            'message' => $throwable->getMessage(),
            'file' => $throwable->getFile(),
            'line' => $throwable->getLine(),
            'url' => request()->fullUrl(),
            'method' => request()->method(),
            'ip' => request()->ip(),
        ], $context);

        Log::warning($message, $logContext);
    }
}

<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

trait ApiResponseTrait
{
    /**
     * Return a success JSON response
     */
    protected function successResponse($data = null, string $message = 'Operación exitosa', int $statusCode = 200): JsonResponse
    {
        $response = [
            'success' => true,
            'message' => $message,
            'status_code' => $statusCode,
        ];

        if ($data !== null) {
            $response['data'] = $data;
        }

        return response()->json($response, $statusCode);
    }

    /**
     * Return an error JSON response
     */
    protected function errorResponse(string $message = 'Error en la operación', int $statusCode = 400, $errors = null): JsonResponse
    {
        $response = [
            'error' => true,
            'message' => $message,
            'status_code' => $statusCode,
        ];

        if ($errors !== null) {
            $response['errors'] = $errors;
        }

        if (config('app.debug')) {
            $response['debug'] = [
                'timestamp' => now()->toISOString(),
                'path' => request()->path(),
                'method' => request()->method(),
            ];
        }

        return response()->json($response, $statusCode);
    }

    /**
     * Return a validation error response
     */
    protected function validationErrorResponse($errors, string $message = 'Los datos proporcionados no son válidos'): JsonResponse
    {
        return $this->errorResponse($message, 422, $errors);
    }

    /**
     * Return a not found error response
     */
    protected function notFoundResponse(string $message = 'Recurso no encontrado'): JsonResponse
    {
        return $this->errorResponse($message, 404);
    }

    /**
     * Return an unauthorized error response
     */
    protected function unauthorizedResponse(string $message = 'No autorizado'): JsonResponse
    {
        return $this->errorResponse($message, 401);
    }

    /**
     * Return a forbidden error response
     */
    protected function forbiddenResponse(string $message = 'Acceso prohibido'): JsonResponse
    {
        return $this->errorResponse($message, 403);
    }

    /**
     * Return a server error response
     */
    protected function serverErrorResponse(string $message = 'Error interno del servidor'): JsonResponse
    {
        return $this->errorResponse($message, 500);
    }

    /**
     * Return a rate limit error response
     */
    protected function rateLimitResponse(string $message = 'Demasiadas solicitudes', int $retryAfter = 60): JsonResponse
    {
        $response = [
            'error' => true,
            'message' => $message,
            'status_code' => 429,
            'retry_after' => $retryAfter,
        ];

        return response()->json($response, 429)
            ->header('Retry-After', $retryAfter);
    }
}
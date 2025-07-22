<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiErrorHandler
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Only handle API requests
        if (! $request->expectsJson() && ! $request->is('api/*')) {
            return $response;
        }

        // Handle error responses
        if ($response->getStatusCode() >= 400) {
            return $this->handleApiError($request, $response);
        }

        return $response;
    }

    /**
     * Handle API error responses
     */
    protected function handleApiError(Request $request, Response $response): JsonResponse
    {
        $statusCode = $response->getStatusCode();
        $content = $response->getContent();

        // Try to decode existing JSON response
        $data = json_decode($content, true);

        // If it's already a proper JSON error response, return it
        if (is_array($data) && isset($data['error'])) {
            return response()->json($data, $statusCode);
        }

        // Create standardized error response
        $errorResponse = [
            'error' => true,
            'message' => $this->getErrorMessage($statusCode),
            'status_code' => $statusCode,
            'path' => $request->path(),
            'method' => $request->method(),
            'timestamp' => now()->toISOString(),
        ];

        // Add debug information in development
        if (config('app.debug')) {
            $errorResponse['debug'] = [
                'original_content' => $content,
                'headers' => $response->headers->all(),
            ];
        }

        return response()->json($errorResponse, $statusCode);
    }

    /**
     * Get error message for status code
     */
    protected function getErrorMessage(int $statusCode): string
    {
        return match ($statusCode) {
            400 => 'Solicitud incorrecta',
            401 => 'No autorizado',
            403 => 'Acceso prohibido',
            404 => 'Recurso no encontrado',
            405 => 'Método no permitido',
            419 => 'Token CSRF inválido o expirado',
            422 => 'Los datos proporcionados no son válidos',
            429 => 'Demasiadas solicitudes',
            500 => 'Error interno del servidor',
            502 => 'Bad Gateway',
            503 => 'Servicio no disponible',
            504 => 'Gateway Timeout',
            default => 'Error del servidor',
        };
    }
}

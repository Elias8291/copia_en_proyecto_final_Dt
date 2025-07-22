<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ErrorController extends Controller
{
    use ApiResponseTrait;

    /**
     * Handle 404 errors for API routes
     */
    public function notFound(Request $request): JsonResponse
    {
        return $this->notFoundResponse('Endpoint no encontrado');
    }

    /**
     * Handle 403 errors for API routes
     */
    public function forbidden(Request $request): JsonResponse
    {
        return $this->forbiddenResponse('Acceso no autorizado');
    }

    /**
     * Handle 419 errors for API routes
     */
    public function tokenMismatch(Request $request): JsonResponse
    {
        return $this->errorResponse('Token CSRF inválido o expirado', 419);
    }

    /**
     * Handle 429 errors for API routes
     */
    public function tooManyRequests(Request $request): JsonResponse
    {
        return $this->rateLimitResponse('Demasiadas solicitudes. Intenta nuevamente en unos minutos');
    }

    /**
     * Handle 500 errors for API routes
     */
    public function serverError(Request $request): JsonResponse
    {
        return $this->serverErrorResponse('Error interno del servidor');
    }

    /**
     * Handle 503 errors for API routes
     */
    public function serviceUnavailable(Request $request): JsonResponse
    {
        return $this->errorResponse('Servicio temporalmente no disponible', 503, ['maintenance' => true]);
    }

    /**
     * Handle validation errors (422)
     */
    public function validationError(Request $request, array $errors = []): JsonResponse
    {
        return $this->validationErrorResponse($errors);
    }

    /**
     * Generic error handler
     */
    public function genericError(Request $request, int $statusCode = 500, ?string $message = null): JsonResponse
    {
        $defaultMessages = [
            400 => 'Solicitud incorrecta',
            401 => 'No autorizado',
            403 => 'Acceso prohibido',
            404 => 'No encontrado',
            405 => 'Método no permitido',
            422 => 'Datos no válidos',
            429 => 'Demasiadas solicitudes',
            500 => 'Error interno del servidor',
            502 => 'Bad Gateway',
            503 => 'Servicio no disponible',
            504 => 'Gateway Timeout',
        ];

        $finalMessage = $message ?? $defaultMessages[$statusCode] ?? 'Error del servidor';

        return $this->errorResponse($finalMessage, $statusCode);
    }

    /**
     * Handle method not allowed errors (405)
     */
    public function methodNotAllowed(Request $request): JsonResponse
    {
        return $this->errorResponse('Método HTTP no permitido para esta ruta', 405);
    }

    /**
     * Handle unauthorized errors (401)
     */
    public function unauthorized(Request $request): JsonResponse
    {
        return $this->unauthorizedResponse('Debe autenticarse para acceder a este recurso');
    }

    /**
     * API endpoint to test errors
     */
    public function apiTest(Request $request): JsonResponse
    {
        $errorType = $request->get('type', '500');

        return match ($errorType) {
            '404' => $this->notFoundResponse('Recurso de prueba no encontrado'),
            '403' => $this->forbiddenResponse('Acceso denegado para prueba'),
            '419' => $this->errorResponse('Token CSRF de prueba inválido', 419),
            '429' => $this->rateLimitResponse('Demasiadas solicitudes de prueba'),
            '500' => $this->serverErrorResponse('Error interno de prueba del servidor'),
            '503' => $this->errorResponse('Servicio no disponible para prueba', 503, ['maintenance' => true]),
            '405' => $this->errorResponse('Método no permitido para prueba', 405),
            '401' => $this->unauthorizedResponse('No autenticado para prueba'),
            '422' => $this->testValidation($request),
            default => $this->errorResponse('Tipo de error no válido', 400),
        };
    }

    /**
     * Test validation error
     */
    private function testValidation(Request $request): JsonResponse
    {
        $validator = validator($request->all(), [
            'required_field' => 'required|string|min:5',
            'email_field' => 'required|email',
            'numeric_field' => 'required|numeric|min:1|max:100',
        ]);

        if ($validator->fails()) {
            return $this->validationErrorResponse($validator->errors());
        }

        return $this->successResponse(['message' => 'Validación exitosa']);
    }
}

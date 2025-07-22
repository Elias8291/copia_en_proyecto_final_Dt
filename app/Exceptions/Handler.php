<?php

namespace App\Exceptions;

use App\Services\ErrorLogService;
use App\Traits\ApiResponseTrait;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\ServiceUnavailableHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Auth\AuthenticationException;
use Throwable;

class Handler extends ExceptionHandler
{
    use ApiResponseTrait;
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            ErrorLogService::logError($e, request());
        });
    }

    /**
     * Render an exception into an HTTP response.
     */
    public function render($request, Throwable $e)
    {
        // Handle API requests
        if ($request->expectsJson() || $request->is('api/*')) {
            return $this->renderApiException($request, $e);
        }

        // Handle web requests
        return $this->renderWebException($request, $e);
    }

    /**
     * Render web exceptions
     */
    protected function renderWebException(Request $request, Throwable $e)
    {
        // Log web errors (except for common ones like 404)
        if (!($e instanceof NotFoundHttpException) && !($e instanceof ValidationException)) {
            ErrorLogService::logError($e, $request);
        }

        // Handle specific HTTP exceptions for web requests
        if ($e instanceof NotFoundHttpException) {
            return response()->view('errors.404', [], 404);
        }

        if ($e instanceof AccessDeniedHttpException) {
            ErrorLogService::logUnauthorizedAccess($request, 'web_access_denied');
            return response()->view('errors.403', [], 403);
        }

        if ($e instanceof AuthenticationException) {
            // Redirect to login for unauthenticated users
            return redirect()->guest(route('login'));
        }

        if ($e instanceof TokenMismatchException) {
            ErrorLogService::logCsrfMismatch($request);
            return response()->view('errors.419', [], 419);
        }

        if ($e instanceof TooManyRequestsHttpException) {
            ErrorLogService::logRateLimit($request);
            return response()->view('errors.429', [], 429);
        }

        if ($e instanceof MethodNotAllowedHttpException) {
            return response()->view('errors.405', [], 405);
        }

        if ($e instanceof ServiceUnavailableHttpException) {
            return response()->view('errors.503', [], 503);
        }

        // Handle general HTTP exceptions
        if ($e instanceof HttpException) {
            $statusCode = $e->getStatusCode();
            
            if (view()->exists("errors.{$statusCode}")) {
                return response()->view("errors.{$statusCode}", [
                    'exception' => $e
                ], $statusCode);
            }
        }

        // Handle 500 errors in production
        if (app()->environment('production') && !($e instanceof HttpException)) {
            return response()->view('errors.500', [], 500);
        }

        return parent::render($request, $e);
    }

    /**
     * Render API exceptions
     */
    protected function renderApiException(Request $request, Throwable $e)
    {
        // Log API errors
        if (!($e instanceof ValidationException)) {
            ErrorLogService::logApiError(
                $e instanceof HttpException ? $e->getStatusCode() : 500,
                $e->getMessage(),
                $request
            );
        }

        // Handle specific exceptions
        if ($e instanceof ValidationException) {
            return $this->validationErrorResponse($e->errors());
        }

        if ($e instanceof NotFoundHttpException) {
            return $this->notFoundResponse('Recurso no encontrado');
        }

        if ($e instanceof AccessDeniedHttpException) {
            ErrorLogService::logUnauthorizedAccess($request, 'access_denied');
            return $this->forbiddenResponse('Acceso no autorizado');
        }

        if ($e instanceof AuthenticationException) {
            return $this->unauthorizedResponse('Debe autenticarse para acceder a este recurso');
        }

        if ($e instanceof TokenMismatchException) {
            ErrorLogService::logCsrfMismatch($request);
            return $this->errorResponse('Token CSRF inválido o expirado', 419);
        }

        if ($e instanceof TooManyRequestsHttpException) {
            ErrorLogService::logRateLimit($request);
            return $this->rateLimitResponse('Demasiadas solicitudes', $e->getRetryAfter() ?? 60);
        }

        if ($e instanceof MethodNotAllowedHttpException) {
            return $this->errorResponse('Método HTTP no permitido para esta ruta', 405);
        }

        if ($e instanceof ServiceUnavailableHttpException) {
            return $this->errorResponse('Servicio no disponible', 503, ['maintenance' => true]);
        }

        if ($e instanceof HttpException) {
            $statusCode = $e->getStatusCode();
            $message = $e->getMessage() ?: $this->getDefaultMessage($statusCode);
            return $this->errorResponse($message, $statusCode);
        }

        // Handle general exceptions
        return $this->serverErrorResponse('Error interno del servidor');
    }

    /**
     * Get default message for status code
     */
    protected function getDefaultMessage(int $statusCode): string
    {
        return match($statusCode) {
            400 => 'Solicitud incorrecta',
            401 => 'No autorizado',
            403 => 'Acceso prohibido',
            404 => 'No encontrado',
            405 => 'Método no permitido',
            419 => 'Página expirada',
            422 => 'Datos no válidos',
            429 => 'Demasiadas solicitudes',
            500 => 'Error interno del servidor',
            502 => 'Bad Gateway',
            503 => 'Servicio no disponible',
            504 => 'Gateway Timeout',
            default => 'Error del servidor',
        };
    }
}
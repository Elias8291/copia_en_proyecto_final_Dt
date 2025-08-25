<?php

namespace App\Services;

use App\Http\Controllers\LogController;
use App\Models\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log as LogFacade;
use Throwable;

class ErrorLogService
{
    /**
     * Registrar un error general del sistema
     */
    public static function logError(Throwable $exception, ?Request $request = null): void
    {
        $request = $request ?? request();
        
        $context = [
            'exception_class' => get_class($exception),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'trace' => $exception->getTraceAsString(),
            'code' => $exception->getCode(),
            'previous' => $exception->getPrevious() ? get_class($exception->getPrevious()) : null,
        ];

        // Agregar información de la petición si está disponible
        if ($request) {
            $context['request'] = [
                'method' => $request->method(),
                'url' => $request->fullUrl(),
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'user_id' => Auth::id(),
                'session_id' => $request->hasSession() ? $request->session()->getId() : null,
            ];

            // Agregar datos de entrada (sin información sensible)
            if ($request->isMethod('POST') || $request->isMethod('PUT') || $request->isMethod('PATCH')) {
                $context['request']['input'] = self::sanitizeInput($request->all());
            }
        }

        // Determinar el nivel de log basado en el tipo de excepción
        $level = self::getLogLevel($exception);
        
        // Registrar en la base de datos
        self::createDatabaseLog($level, $exception->getMessage(), 'exceptions', $context);
        
        // También registrar en el log de Laravel
        LogFacade::$level('Error del sistema: ' . $exception->getMessage(), $context);
    }

    /**
     * Registrar error de API
     */
    public static function logApiError(int $statusCode, string $message, Request $request): void
    {
        $context = [
            'status_code' => $statusCode,
            'method' => $request->method(),
            'url' => $request->fullUrl(),
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'user_id' => Auth::id(),
            'headers' => $request->headers->all(),
        ];

        $level = $statusCode >= 500 ? 'error' : 'warning';
        
        self::createDatabaseLog($level, "Error API {$statusCode}: {$message}", 'api', $context);
        LogFacade::$level("Error API {$statusCode}: {$message}", $context);
    }

    /**
     * Registrar acceso no autorizado
     */
    public static function logUnauthorizedAccess(Request $request, string $type = 'access_denied'): void
    {
        $context = [
            'type' => $type,
            'method' => $request->method(),
            'url' => $request->fullUrl(),
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'user_id' => Auth::id(),
            'referer' => $request->header('referer'),
        ];

        self::createDatabaseLog('warning', "Acceso no autorizado: {$type}", 'security', $context);
        LogFacade::warning("Acceso no autorizado: {$type}", $context);
    }

    /**
     * Registrar error de CSRF
     */
    public static function logCsrfMismatch(Request $request): void
    {
        $context = [
            'method' => $request->method(),
            'url' => $request->fullUrl(),
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'user_id' => Auth::id(),
            'token' => $request->input('_token'),
            'session_token' => $request->session()->token(),
        ];

        self::createDatabaseLog('warning', 'Token CSRF inválido o expirado', 'security', $context);
        LogFacade::warning('Token CSRF inválido o expirado', $context);
    }

    /**
     * Registrar límite de tasa excedido
     */
    public static function logRateLimit(Request $request): void
    {
        $context = [
            'method' => $request->method(),
            'url' => $request->fullUrl(),
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'user_id' => Auth::id(),
        ];

        self::createDatabaseLog('warning', 'Límite de tasa excedido', 'security', $context);
        LogFacade::warning('Límite de tasa excedido', $context);
    }

    /**
     * Registrar error de validación
     */
    public static function logValidationError(array $errors, Request $request): void
    {
        $context = [
            'errors' => $errors,
            'method' => $request->method(),
            'url' => $request->fullUrl(),
            'ip' => $request->ip(),
            'user_id' => Auth::id(),
            'input' => self::sanitizeInput($request->all()),
        ];

        self::createDatabaseLog('warning', 'Error de validación', 'validation', $context);
        LogFacade::warning('Error de validación', $context);
    }

    /**
     * Registrar error de base de datos
     */
    public static function logDatabaseError(Throwable $exception, string $operation = 'database_operation'): void
    {
        $context = [
            'operation' => $operation,
            'exception_class' => get_class($exception),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'code' => $exception->getCode(),
            'user_id' => Auth::id(),
        ];

        self::createDatabaseLog('error', "Error de base de datos: {$exception->getMessage()}", 'database', $context);
        LogFacade::error("Error de base de datos: {$exception->getMessage()}", $context);
    }

    /**
     * Registrar error de archivo
     */
    public static function logFileError(Throwable $exception, string $operation, string $filePath = null): void
    {
        $context = [
            'operation' => $operation,
            'file_path' => $filePath,
            'exception_class' => get_class($exception),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'user_id' => Auth::id(),
        ];

        self::createDatabaseLog('error', "Error de archivo: {$exception->getMessage()}", 'files', $context);
        LogFacade::error("Error de archivo: {$exception->getMessage()}", $context);
    }

    /**
     * Registrar error de autenticación
     */
    public static function logAuthenticationError(string $message, Request $request): void
    {
        $context = [
            'method' => $request->method(),
            'url' => $request->fullUrl(),
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'credentials' => [
                'rfc' => $request->input('rfc'),
                'has_password' => !empty($request->input('password')),
            ],
        ];

        self::createDatabaseLog('warning', "Error de autenticación: {$message}", 'auth', $context);
        LogFacade::warning("Error de autenticación: {$message}", $context);
    }

    /**
     * Registrar error de permisos
     */
    public static function logPermissionError(string $permission, Request $request): void
    {
        $context = [
            'required_permission' => $permission,
            'method' => $request->method(),
            'url' => $request->fullUrl(),
            'ip' => $request->ip(),
            'user_id' => Auth::id(),
        ];

        self::createDatabaseLog('warning', "Error de permisos: {$permission}", 'permissions', $context);
        LogFacade::warning("Error de permisos: {$permission}", $context);
    }

    /**
     * Crear log en la base de datos
     */
    private static function createDatabaseLog(string $level, string $message, string $channel, array $context): void
    {
        if (!app()->isBootstrapped() || !app()->bound('db')) {
            return;
        }

        try {
            $request = request();
            
            Log::create([
                'level' => $level,
                'message' => $message,
                'channel' => $channel,
                'context' => !empty($context) ? json_encode($context) : null,
                'user_id' => Auth::check() ? Auth::id() : null,
                'ip_address' => $request ? $request->ip() : null,
                'user_agent' => $request ? $request->userAgent() : null,
                'url' => $request ? $request->fullUrl() : null,
                'method' => $request ? $request->method() : null,
            ]);
        } catch (\Exception $e) {
            // Si falla el registro en BD, usar el log de Laravel
            LogFacade::error('Error al registrar log en BD', [
                'original_message' => $message,
                'db_error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Determinar el nivel de log basado en el tipo de excepción
     */
    private static function getLogLevel(Throwable $exception): string
    {
        $exceptionClass = get_class($exception);
        
        // Excepciones críticas
        if (in_array($exceptionClass, [
            'Illuminate\Database\QueryException',
            'PDOException',
            'Illuminate\Database\Eloquent\ModelNotFoundException',
            'Symfony\Component\HttpKernel\Exception\HttpException',
        ])) {
            return 'error';
        }

        // Excepciones de validación
        if (in_array($exceptionClass, [
            'Illuminate\Validation\ValidationException',
            'Illuminate\Auth\AuthenticationException',
        ])) {
            return 'warning';
        }

        // Excepciones de autorización
        if (in_array($exceptionClass, [
            'Illuminate\Auth\Access\AuthorizationException',
            'Spatie\Permission\Exceptions\UnauthorizedException',
        ])) {
            return 'warning';
        }

        // Por defecto, usar error
        return 'error';
    }

    /**
     * Sanitizar datos de entrada para evitar información sensible
     */
    private static function sanitizeInput(array $input): array
    {
        $sensitiveFields = [
            'password',
            'password_confirmation',
            'current_password',
            'new_password',
            'token',
            '_token',
            'api_key',
            'secret',
            'credit_card',
            'ssn',
        ];

        $sanitized = $input;
        
        foreach ($sensitiveFields as $field) {
            if (isset($sanitized[$field])) {
                $sanitized[$field] = '[REDACTED]';
            }
        }

        return $sanitized;
    }
}

<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Throwable;

class ErrorLogService
{
    /**
     * Log error with context information
     */
    public static function logError(Throwable $exception, ?Request $request = null): void
    {
        $context = [
            'exception' => get_class($exception),
            'message' => $exception->getMessage(),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'trace' => $exception->getTraceAsString(),
        ];

        if ($request) {
            $context['request'] = [
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'user_id' => auth()->id(),
            ];

            // Add request data (be careful with sensitive information)
            if (! $request->isMethod('GET')) {
                $requestData = $request->except(['password', 'password_confirmation', 'token']);
                if (! empty($requestData)) {
                    $context['request']['data'] = $requestData;
                }
            }
        }

        Log::error('Application Error: '.$exception->getMessage(), $context);
    }

    /**
     * Log API error
     */
    public static function logApiError(int $statusCode, string $message, Request $request): void
    {
        $context = [
            'status_code' => $statusCode,
            'message' => $message,
            'url' => $request->fullUrl(),
            'method' => $request->method(),
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'user_id' => auth()->id(),
        ];

        Log::warning('API Error: '.$message, $context);
    }

    /**
     * Log security-related errors
     */
    public static function logSecurityError(string $type, Request $request, array $additionalData = []): void
    {
        $context = array_merge([
            'security_event' => $type,
            'url' => $request->fullUrl(),
            'method' => $request->method(),
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'user_id' => auth()->id(),
            'timestamp' => now()->toISOString(),
        ], $additionalData);

        Log::warning('Security Event: '.$type, $context);
    }

    /**
     * Log rate limiting events
     */
    public static function logRateLimit(Request $request): void
    {
        self::logSecurityError('rate_limit_exceeded', $request, [
            'throttle_key' => $request->fingerprint(),
        ]);
    }

    /**
     * Log CSRF token mismatches
     */
    public static function logCsrfMismatch(Request $request): void
    {
        self::logSecurityError('csrf_token_mismatch', $request, [
            'referer' => $request->header('referer'),
            'session_id' => session()->getId(),
        ]);
    }

    /**
     * Log unauthorized access attempts
     */
    public static function logUnauthorizedAccess(Request $request, string $reason = 'access_denied'): void
    {
        self::logSecurityError('unauthorized_access', $request, [
            'reason' => $reason,
            'intended_resource' => $request->path(),
        ]);
    }
}

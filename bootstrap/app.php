<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // AutoLoggingMiddleware moved to web middleware group
        // Registrar alias de middlewares de Spatie Permission (Laravel 11)
        $middleware->alias([
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        
        $exceptions->reportable(function (\Throwable $e) {
            try {
                // Usar el ErrorLogService para registrar todos los errores
                if (app()->isBooted() && app()->bound('db')) {
                    \App\Services\ErrorLogService::logError($e, request());
                } else {
                    // Fallback al log de Laravel si la BD no está disponible
                    if (app()->bound('log')) {
                        app('log')->error('Excepción capturada automáticamente', [
                            'message' => $e->getMessage(),
                            'file' => $e->getFile(),
                            'line' => $e->getLine(),
                            'trace' => $e->getTraceAsString(),
                            'class' => get_class($e),
                        ]);
                    }
                }
            } catch (\Exception $logException) {
                // Si falla el logging, no hacer nada para evitar loops infinitos
            }
        });
    })->create();

<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Controllers\LogController;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
  $middleware->append(\App\Http\Middleware\AutoLoggingMiddleware::class);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Capturar todas las excepciones y registrarlas automáticamente
        $exceptions->reportable(function (\Throwable $e) {
            try {
                LogController::error('Excepción capturada automáticamente', 'exceptions', [
                    'message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => $e->getTraceAsString(),
                    'class' => get_class($e)
                ]);
            } catch (\Exception $logException) {
                // Si falla el logging, usar el log de Laravel como respaldo
                \Illuminate\Support\Facades\Log::error('Error al registrar excepción en BD', [
                    'original_error' => $e->getMessage(),
                    'logging_error' => $logException->getMessage()
                ]);
            }
        });
    })->create();

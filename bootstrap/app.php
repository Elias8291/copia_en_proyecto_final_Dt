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
        // Middleware configuration
    })
    ->withExceptions(function (Exceptions $exceptions) {
        
        $exceptions->reportable(function (\Throwable $e) {
            try {
                if (app()->bound('log')) {
                    app('log')->error('Excepción capturada automáticamente', [
                        'message' => $e->getMessage(),
                        'file' => $e->getFile(),
                        'line' => $e->getLine(),
                        'trace' => $e->getTraceAsString(),
                        'class' => get_class($e),
                    ]);
                }    
                if (app()->isBooted() && app()->bound('db')) {
                    try {
                        \App\Http\Controllers\LogController::error('Excepción capturada automáticamente', 'exceptions', [
                            'message' => $e->getMessage(),
                            'file' => $e->getFile(),
                            'line' => $e->getLine(),
                            'trace' => $e->getTraceAsString(),
                            'class' => get_class($e),
                        ]);
                    } catch (\Exception $dbException) {
                        
                        if (app()->bound('log')) {
                            app('log')->error('Error al registrar excepción en BD', [
                                'original_error' => $e->getMessage(),
                                'db_error' => $dbException->getMessage(),
                            ]);
                        }
                    }
                }
            } catch (\Exception $logException) {
                
            }
        });
    })->create();

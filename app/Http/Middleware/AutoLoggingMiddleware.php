<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Http\Controllers\LogController;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AutoLoggingMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $startTime = microtime(true);
        
        // Procesar la petición
        $response = $next($request);
        
        $endTime = microtime(true);
        $duration = round(($endTime - $startTime) * 1000, 2); // en milisegundos
        
        // Solo registrar peticiones que no sean de assets o health checks
        if ($this->shouldLogRequest($request)) {
            $this->logRequest($request, $response, $duration);
        }
        
        return $response;
    }
    
    /**
     * Determinar si se debe registrar la petición
     */
    private function shouldLogRequest(Request $request): bool
    {
        $excludedPaths = [
            '/css/',
            '/js/',
            '/images/',
            '/favicon.ico',
            '/up', // health check
        ];
        
        $path = $request->path();
        
        foreach ($excludedPaths as $excludedPath) {
            if (str_contains($path, $excludedPath)) {
                return false;
            }
        }
        
        return true;
    }
    
    /**
     * Registrar información de la petición
     */
    private function logRequest(Request $request, Response $response, float $duration): void
    {
        try {
            $statusCode = $response->getStatusCode();
            $channel = $this->getChannelByPath($request->path());
            
            $context = [
                'method' => $request->method(),
                'url' => $request->fullUrl(),
                'status_code' => $statusCode,
                'duration_ms' => $duration,
                'user_agent' => $request->userAgent(),
                'ip' => $request->ip(),
                'user_id' => Auth::id() ?? null,
                'session_id' => $request->session()->getId(),
            ];
            
            // Agregar información específica según el tipo de petición
            if ($request->isMethod('POST')) {
                $context['input_data'] = $this->sanitizeInput($request->all());
            }
            
            // Determinar nivel de log basado en el código de estado
            if ($statusCode >= 400) {
                LogController::error("Petición HTTP {$statusCode}", $channel, $context);
            } elseif ($statusCode >= 300) {
                LogController::warning("Redirección HTTP {$statusCode}", $channel, $context);
            } else {
                LogController::info("Petición HTTP {$statusCode}", $channel, $context);
            }
            
        } catch (\Exception $e) {
            // Si falla el logging, no interrumpir la aplicación
            \Illuminate\Support\Facades\Log::warning('Error en AutoLoggingMiddleware', [
                'error' => $e->getMessage()
            ]);
        }
    }
    
    /**
     * Determinar el canal de log basado en la ruta
     */
    private function getChannelByPath(string $path): string
    {
        if (str_starts_with($path, 'auth/') || str_starts_with($path, 'login') || str_starts_with($path, 'register')) {
            return 'auth';
        }
        
        if (str_starts_with($path, 'api/')) {
            return 'api';
        }
        
        if (str_starts_with($path, 'admin/')) {
            return 'admin';
        }
        
        if (str_starts_with($path, 'dashboard')) {
            return 'dashboard';
        }
        
        return 'web';
    }
    
    /**
     * Sanitizar datos de entrada para el log
     */
    private function sanitizeInput(array $input): array
    {
        $sensitiveFields = ['password', 'password_confirmation', 'token', 'api_key', 'secret'];
        
        foreach ($sensitiveFields as $field) {
            if (isset($input[$field])) {
                $input[$field] = '***HIDDEN***';
            }
        }
        
        return $input;
    }
} 
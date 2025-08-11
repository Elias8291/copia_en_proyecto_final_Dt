<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Verificar si el usuario está autenticado
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        // Verificar si el usuario tiene rol de administrador o permisos específicos
        if (!$user->hasRole('admin') && !$user->hasPermissionTo('manage users')) {
            // Si no tiene permisos, mostrar error 403
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'Acceso denegado. No tiene permisos para realizar esta acción.',
                    'message' => 'Se requieren permisos de administrador para acceder a esta funcionalidad.'
                ], 403);
            }

            return response()->view('errors.403', [
                'title' => 'Acceso Denegado',
                'message' => 'No tiene permisos para acceder a esta sección.',
                'description' => 'Se requieren permisos de administrador para gestionar usuarios.'
            ], 403);
        }

        return $next($request);
    }
} 
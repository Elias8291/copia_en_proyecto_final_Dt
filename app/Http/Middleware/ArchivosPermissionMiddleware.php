<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ArchivosPermissionMiddleware
{
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        // Verificar si el usuario tiene el permiso específico
        if (!$user->hasPermissionTo($permission)) {
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'No tienes permisos para realizar esta acción',
                    'permission_required' => $permission
                ], 403);
            }

            return redirect()->back()->with('error', 'No tienes permisos para realizar esta acción');
        }

        return $next($request);
    }
}

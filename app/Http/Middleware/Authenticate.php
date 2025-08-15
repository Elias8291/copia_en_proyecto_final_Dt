<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        // Si es una petición AJAX o de API, no redirigir
        if ($request->expectsJson()) {
            return null;
        }

        // Si es una ruta de API, no guardar como intended
        if ($request->is('*/api/*') || $request->is('api/*')) {
            // Limpiar cualquier URL intended que pueda ser una API
            session()->forget('url.intended');
            return route('login');
        }

        return route('login');
    }
}

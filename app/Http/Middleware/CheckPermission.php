<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Exceptions\UnauthorizedException;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @param  string  $permission
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, string $permission)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // Check if user has the permission directly or through roles
        if (!$user->hasPermissionTo($permission)) {
            // Log the attempt
            \Log::warning('Permission denied', [
                'user_id' => $user->id,
                'permission' => $permission,
                'url' => $request->url(),
                'user_permissions' => $user->getAllPermissions()->pluck('name')->toArray(),
                'user_roles' => $user->getRoleNames()->toArray(),
            ]);

            // For API requests, return JSON response
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'No tienes permiso para acceder a este recurso.',
                    'permission_required' => $permission
                ], 403);
            }

            // For web requests, redirect with error message
            return redirect()->back()->with('error', 'No tienes permiso para acceder a este recurso.');
        }

        return $next($request);
    }
} 
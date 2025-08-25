<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Exceptions\UnauthorizedException;

class CheckPermission
{
    public function handle(Request $request, Closure $next, string $permission)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        try {
            $hasPermission = @$user->{'can'}($permission);
            if (!$hasPermission) {
                \App\Services\ErrorLogService::logPermissionError($permission, $request);

                if ($request->expectsJson()) {
                    return response()->json([
                        'error' => 'No tienes permiso para acceder a este recurso.',
                        'permission_required' => $permission
                    ], 403);
                }

                return redirect()->back()->with('error', 'No tienes permiso para acceder a este recurso.');
            }
        } catch (\Exception $e) {
            \App\Services\ErrorLogService::logError($e, $request);
        }

        return $next($request);
    }
} 
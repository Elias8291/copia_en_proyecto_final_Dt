<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class CheckSessionExpiry
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && $request->session()->isStarted()) {
            $sessionLifetime = config('session.lifetime', 30);
            $lastActivity = Session::get('last_activity');

            if ($lastActivity) {
                $timeSinceLastActivity = now()->diffInMinutes($lastActivity);

                if ($timeSinceLastActivity > $sessionLifetime) {
                    Auth::logout();
                    Session::invalidate();
                    Session::regenerateToken();

                    return redirect()->route('login')
                        ->with('session_expired', 'Tu sesión ha expirado por inactividad. Por favor, inicia sesión nuevamente.');
                }
            }

            Session::put('last_activity', now());
        }

        return $next($request);
    }
}

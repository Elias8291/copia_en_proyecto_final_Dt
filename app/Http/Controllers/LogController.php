<?php

namespace App\Http\Controllers;

use App\Models\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log as LogFacade;

class LogController extends Controller
{
    /** Crear log de información */
    public static function info(string $message, string $channel = 'system', array $context = []): void
    {
        self::createLog('info', $message, $channel, $context);
    }

    /** Crear log de error */
    public static function error(string $message, string $channel = 'system', array $context = []): void
    {
        self::createLog('error', $message, $channel, $context);
    }

    /** Crear log de advertencia */
    public static function warning(string $message, string $channel = 'system', array $context = []): void
    {
        self::createLog('warning', $message, $channel, $context);
    }

    /** Crear log de debug */
    public static function debug(string $message, string $channel = 'system', array $context = []): void
    {
        self::createLog('debug', $message, $channel, $context);
    }

    /** Crear log en base de datos y Laravel */
    private static function createLog(string $level, string $message, string $channel, array $context): void
    {
        // Verificar si la aplicación está bootstrapped y la base de datos está disponible
        if (!app()->isBooted() || !app()->bound('db')) {
            // Si no está listo, solo usar el log de Laravel
            LogFacade::$level($message, $context);
            return;
        }

        try {
            $request = request();

            // Crear log en base de datos
            Log::create([
                'level' => $level,
                'message' => $message,
                'channel' => $channel,
                'context' => ! empty($context) ? json_encode($context) : null,
                'user_id' => Auth::check() ? Auth::id() : null,
                'ip_address' => $request ? $request->ip() : null,
                'user_agent' => $request ? $request->userAgent() : null,
                'url' => $request ? $request->fullUrl() : null,
                'method' => $request ? $request->method() : null,
            ]);

            // Crear log en Laravel también
            LogFacade::$level($message, $context);
        } catch (\Exception $e) {
            // Si falla la base de datos, usar solo el log de Laravel
            LogFacade::$level($message, $context);
            LogFacade::error('Error al registrar log en BD', [
                'original_message' => $message,
                'db_error' => $e->getMessage(),
            ]);
        }
    }
}

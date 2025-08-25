<?php

namespace App\Http\Controllers;

use App\Models\Log;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log as LogFacade;

/**
 * Controlador para gestión de logs del sistema
 */
class LogController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:logs.ver')->only(['index', 'show']);
    }

    public function index(Request $request)
    {
        $query = Log::with('user')->orderBy('created_at', 'desc');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('message', 'like', "%{$search}%")
                  ->orWhere('level', 'like', "%{$search}%")
                  ->orWhere('channel', 'like', "%{$search}%")
                  ->orWhere('ip_address', 'like', "%{$search}%")
                  ->orWhere('method', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($userQuery) use ($search) {
                      $userQuery->where('nombre', 'like', "%{$search}%")
                               ->orWhere('correo', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('level')) {
            $query->where('level', $request->level);
        }

        if ($request->filled('channel')) {
            $query->where('channel', $request->channel);
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('fecha')) {
            $query->whereDate('created_at', $request->fecha);
        }

        if ($request->filled('fecha_inicio')) {
            $query->whereDate('created_at', '>=', $request->fecha_inicio);
        }
        if ($request->filled('fecha_fin')) {
            $query->whereDate('created_at', '<=', $request->fecha_fin);
        }

        $logs = $query->paginate($request->get('per_page', 15));

        $levels = Log::distinct()->pluck('level')->sort();
        $channels = Log::distinct()->pluck('channel')->whereNotNull()->sort();
        $users = User::orderBy('nombre')->get(['id', 'nombre as name', 'correo as email']);

        return view('logs.index', compact('logs', 'levels', 'channels', 'users'));
    }

    public function show(Log $log)
    {
        return view('logs.show', compact('log'));
    }

    public function limpiar(Request $request)
    {
        $this->authorize('logs.eliminar');

        $dias = $request->get('dias', 30);
        $fechaLimite = now()->subDays($dias);

        $logsEliminados = Log::where('created_at', '<', $fechaLimite)->delete();

        return redirect()->route('logs.index')
            ->with('success', "Se eliminaron {$logsEliminados} logs anteriores a {$dias} días.");
    }



    public static function info(string $message, string $channel = 'system', array $context = []): void
    {
        self::createLog('info', $message, $channel, $context);
    }

    public static function error(string $message, string $channel = 'system', array $context = []): void
    {
        self::createLog('error', $message, $channel, $context);
    }

    public static function warning(string $message, string $channel = 'system', array $context = []): void
    {
        self::createLog('warning', $message, $channel, $context);
    }

    public static function debug(string $message, string $channel = 'system', array $context = []): void
    {
        self::createLog('debug', $message, $channel, $context);
    }

    private static function createLog(string $level, string $message, string $channel, array $context): void
    {
        if (!app()->isBootstrapped() || !app()->bound('db')) {
            LogFacade::$level($message, $context);
            return;
        }

        try {
            $request = request();

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

            LogFacade::$level($message, $context);
        } catch (\Exception $e) {
            LogFacade::$level($message, $context);
            LogFacade::error('Error al registrar log en BD', [
                'original_message' => $message,
                'db_error' => $e->getMessage(),
            ]);
        }
    }
}

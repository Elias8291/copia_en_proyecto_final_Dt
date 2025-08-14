<?php

namespace App\Http\Controllers;

use App\Models\Log;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log as LogFacade;

class LogController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:logs.ver')->only(['index', 'show']);
    }

    /**
     * Mostrar la lista de logs
     */
    public function index(Request $request)
    {
        $query = Log::with('user')->orderBy('created_at', 'desc');

        // Filtros de búsqueda
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('message', 'like', "%{$search}%")
                  ->orWhere('level', 'like', "%{$search}%")
                  ->orWhere('channel', 'like', "%{$search}%")
                  ->orWhere('ip_address', 'like', "%{$search}%")
                  ->orWhere('method', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%")
                               ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        // Filtro por nivel
        if ($request->filled('level')) {
            $query->where('level', $request->level);
        }

        // Filtro por canal
        if ($request->filled('channel')) {
            $query->where('channel', $request->channel);
        }

        // Filtro por usuario
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Filtro por fecha
        if ($request->filled('fecha')) {
            $query->whereDate('created_at', $request->fecha);
        }

        // Filtro por rango de fechas
        if ($request->filled('fecha_inicio')) {
            $query->whereDate('created_at', '>=', $request->fecha_inicio);
        }
        if ($request->filled('fecha_fin')) {
            $query->whereDate('created_at', '<=', $request->fecha_fin);
        }

        $logs = $query->paginate($request->get('per_page', 15));

        // Obtener datos para filtros
        $levels = Log::distinct()->pluck('level')->sort();
        $channels = Log::distinct()->pluck('channel')->whereNotNull()->sort();
        $users = User::orderBy('name')->get(['id', 'name', 'email']);

        return view('logs.index', compact('logs', 'levels', 'channels', 'users'));
    }

    /**
     * Mostrar detalles de un log específico
     */
    public function show(Log $log)
    {
        return view('logs.show', compact('log'));
    }

    /**
     * Limpiar logs antiguos
     */
    public function limpiar(Request $request)
    {
        $this->authorize('logs.eliminar');

        $dias = $request->get('dias', 30);
        $fechaLimite = now()->subDays($dias);

        $logsEliminados = Log::where('created_at', '<', $fechaLimite)->delete();

        return redirect()->route('logs.index')
            ->with('success', "Se eliminaron {$logsEliminados} logs anteriores a {$dias} días.");
    }

    /**
     * Exportar logs
     */
    public function exportar(Request $request)
    {
        $this->authorize('logs.exportar');

        $query = Log::with('user')->orderBy('created_at', 'desc');

        // Aplicar los mismos filtros que en index
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('message', 'like', "%{$search}%")
                  ->orWhere('level', 'like', "%{$search}%")
                  ->orWhere('channel', 'like', "%{$search}%");
            });
        }

        if ($request->filled('level')) {
            $query->where('level', $request->level);
        }

        if ($request->filled('channel')) {
            $query->where('channel', $request->channel);
        }

        if ($request->filled('fecha_inicio')) {
            $query->whereDate('created_at', '>=', $request->fecha_inicio);
        }

        if ($request->filled('fecha_fin')) {
            $query->whereDate('created_at', '<=', $request->fecha_fin);
        }

        $logs = $query->get();

        $filename = 'logs_' . now()->format('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($logs) {
            $file = fopen('php://output', 'w');
            
            // Encabezados
            fputcsv($file, [
                'ID', 'Nivel', 'Mensaje', 'Canal', 'Usuario', 'IP', 'URL', 'Método', 'Fecha'
            ]);

            // Datos
            foreach ($logs as $log) {
                fputcsv($file, [
                    $log->id,
                    $log->level,
                    $log->message,
                    $log->channel,
                    $log->user ? $log->user->name : 'Sistema',
                    $log->ip_address,
                    $log->url,
                    $log->method,
                    $log->created_at->format('Y-m-d H:i:s')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    // ============================================================================
    // MÉTODOS ESTÁTICOS PARA CREAR LOGS (mantener compatibilidad)
    // ============================================================================

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
        if (!app()->isBootstrapped() || !app()->bound('db')) {
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

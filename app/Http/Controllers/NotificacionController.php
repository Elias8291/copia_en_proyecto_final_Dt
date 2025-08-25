<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Notificacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Str;

/**
 * Controlador para gestión de notificaciones del sistema
 */
class NotificacionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        // Redirigir a la página de logs del sistema
        return redirect()->route('logs.index');
    }

    public function create()
    {
        return view('notificaciones.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'usuario_id' => 'required|exists:users,id',
            'tipo' => 'required|in:informativo,advertencia,error,exito,Tramite,Cita',
            'titulo' => 'required|string|max:255',
            'mensaje' => 'required|string',
            'accion_url' => 'nullable|url'
        ]);

        $notificacion = Notificacion::crear(
            $request->usuario_id,
            $request->tipo,
            $request->titulo,
            $request->mensaje,
            $request->datos_adicionales ? json_decode($request->datos_adicionales, true) : null,
            $request->accion_url
        );

        return redirect()->route('notificaciones.index')
            ->with('success', 'Notificación creada exitosamente.');
    }

    public function show(Notificacion $notificacion)
    {
        if ($notificacion->usuario_id !== Auth::id()) {
            abort(403, 'No tienes permiso para ver esta notificación.');
        }

        if (!$notificacion->esLeida()) {
            $notificacion->marcarComoLeida();
        }

        return view('notificaciones.show', compact('notificacion'));
    }

    public function edit(Notificacion $notificacion)
    {
        return view('notificaciones.edit', compact('notificacion'));
    }

    public function update(Request $request, Notificacion $notificacion)
    {
        $request->validate([
            'tipo' => 'required|in:informativo,advertencia,error,exito,Tramite,Cita',
            'titulo' => 'required|string|max:255',
            'mensaje' => 'required|string',
            'accion_url' => 'nullable|url'
        ]);

        $notificacion->update($request->only([
            'tipo', 'titulo', 'mensaje', 'accion_url'
        ]));

        return redirect()->route('notificaciones.index')
            ->with('success', 'Notificación actualizada exitosamente.');
    }

    public function destroy(Notificacion $notificacion)
    {
        if ($notificacion->usuario_id !== Auth::id()) {
            abort(403, 'No tienes permiso para eliminar esta notificación.');
        }

        $notificacion->delete();

        return redirect()->route('notificaciones.index')
            ->with('success', 'Notificación eliminada exitosamente.');
    }

    public function marcarLeida(Notificacion $notificacion)
    {
        if ($notificacion->usuario_id !== Auth::id()) {
            abort(403, 'No tienes permiso para modificar esta notificación.');
        }

        $notificacion->marcarComoLeida();

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Notificación marcada como leída.'
            ]);
        }

        return redirect()->back()
            ->with('success', 'Notificación marcada como leída.');
    }

    public function marcarTodasLeidas()
    {
        $user = Auth::user();
        
        Notificacion::delUsuario($user->id)
            ->noLeidas()
            ->update([
                'leida' => true,
                'fecha_lectura' => now()
            ]);

        return redirect()->back()
            ->with('success', 'Todas las notificaciones han sido marcadas como leídas.');
    }

    public function conteoNoLeidas()
    {
        $user = Auth::user();
        $count = Notificacion::delUsuario($user->id)->noLeidas()->count();

        return response()->json(['count' => $count]);
    }

    public function recientes()
    {
        $user = Auth::user();
        
        $notificaciones = Notificacion::delUsuario($user->id)
            ->recientes(1)
            ->limit(5)
            ->get()
            ->map(function ($notificacion) {
                return [
                    'id' => $notificacion->id,
                    'titulo' => $notificacion->titulo,
                    'mensaje' => $notificacion->mensaje,
                    'tipo' => $notificacion->tipo,
                    'fecha' => $this->formatearFechaNotificacion($notificacion),
                    'leida' => $notificacion->leida,
                    'url' => $notificacion->accion_url
                ];
            });

        return response()->json($notificaciones);
    }

    public function noLeidas()
    {
        $user = Auth::user();
        
        $notificaciones = Notificacion::delUsuario($user->id)
            ->noLeidas()
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($notificacion) {
                return [
                    'id' => $notificacion->id,
                    'titulo' => $notificacion->titulo,
                    'mensaje' => $notificacion->mensaje,
                    'tipo' => $notificacion->tipo,
                    'fecha' => $this->formatearFechaNotificacion($notificacion),
                    'url' => $notificacion->accion_url
                ];
            });

        return response()->json($notificaciones);
    }

    public function recientesParaDropdown()
    {
        $user = Auth::user();
        
        $notificaciones = Notificacion::delUsuario($user->id)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($notificacion) {
                return [
                    'id' => $notificacion->id,
                    'titulo' => $notificacion->titulo,
                    'mensaje' => Str::limit($notificacion->mensaje, 50),
                    'tipo' => $notificacion->tipo,
                    'fecha_formateada' => $this->formatearFechaNotificacion($notificacion),
                    'leida' => $notificacion->leida,
                    'url' => $notificacion->accion_url,
                    'created_at' => $notificacion->created_at
                ];
            });

        $conteoNoLeidas = Notificacion::delUsuario($user->id)
            ->noLeidas()
            ->count();

        return response()->json([
            'notificaciones' => $notificaciones,
            'conteo_no_leidas' => $conteoNoLeidas
        ]);
    }

    private function formatearFechaNotificacion($notificacion)
    {
        $fecha = $notificacion->created_at;
        $ahora = now();
        $diferencia = $ahora->diffInMinutes($fecha);

        if ($diferencia < 1) {
            return 'Ahora mismo';
        } elseif ($diferencia < 60) {
            return "Hace {$diferencia} min";
        } elseif ($diferencia < 1440) {
            $horas = floor($diferencia / 60);
            return "Hace {$horas} h";
        } else {
            $dias = floor($diferencia / 1440);
            return "Hace {$dias} días";
        }
    }

    public function marcarVistasComoLeidas()
    {
        $user = Auth::user();
        $notificacionesVistas = request()->input('notificaciones', []);

        if (!empty($notificacionesVistas)) {
            Notificacion::delUsuario($user->id)
                ->whereIn('id', $notificacionesVistas)
                ->noLeidas()
                ->update([
                    'leida' => true,
                    'fecha_lectura' => now()
                ]);
        }

        $conteoRestante = Notificacion::delUsuario($user->id)
            ->noLeidas()
            ->count();

        return response()->json([
            'success' => true,
            'message' => 'Notificaciones marcadas como leídas.',
            'conteo_restante' => $conteoRestante
        ]);
    }

    public function limpiarAntiguas()
    {
        $dias = request()->input('dias', 30);
        $fechaLimite = now()->subDays($dias);

        $notificacionesEliminadas = Notificacion::where('created_at', '<', $fechaLimite)
            ->where('leida', true)
            ->delete();

        return redirect()->route('notificaciones.index')
            ->with('success', "Se eliminaron {$notificacionesEliminadas} notificaciones antiguas.");
    }
}

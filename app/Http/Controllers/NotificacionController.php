<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Notificacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Str;

class NotificacionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Consulta simple: obtener todas las notificaciones del usuario ordenadas por las más recientes
        $notificaciones = Notificacion::delUsuario($user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('notificaciones.index', compact('notificaciones'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Solo usuarios con permisos pueden crear notificaciones
        return view('notificaciones.create');
    }

    /**
     * Store a newly created resource in storage.
     */
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

    /**
     * Display the specified resource.
     */
    public function show(Notificacion $notificacion)
    {
        // Verificar que el usuario puede ver esta notificación
        if ($notificacion->usuario_id !== Auth::id()) {
            abort(403, 'No tienes permiso para ver esta notificación.');
        }

        // Marcar como leída si no lo está
        if (!$notificacion->esLeida()) {
            $notificacion->marcarComoLeida();
        }

        return view('notificaciones.show', compact('notificacion'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Notificacion $notificacion)
    {
        return view('notificaciones.edit', compact('notificacion'));
    }

    /**
     * Update the specified resource in storage.
     */
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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Notificacion $notificacion)
    {
        // Verificar que el usuario puede eliminar esta notificación
        if ($notificacion->usuario_id !== Auth::id()) {
            abort(403, 'No tienes permiso para eliminar esta notificación.');
        }

        $notificacion->delete();

        return redirect()->route('notificaciones.index')
            ->with('success', 'Notificación eliminada exitosamente.');
    }

    /**
     * Marcar una notificación como leída
     */
    public function marcarLeida(Notificacion $notificacion)
    {
        // Verificar que el usuario puede marcar esta notificación
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

    /**
     * Marcar todas las notificaciones como leídas
     */
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

    /**
     * Obtener el conteo de notificaciones no leídas (para AJAX)
     */
    public function conteoNoLeidas()
    {
        $user = Auth::user();
        $count = Notificacion::delUsuario($user->id)->noLeidas()->count();

        return response()->json(['count' => $count]);
    }

    /**
     * Obtener las notificaciones recientes (para notificaciones en tiempo real)
     */
    public function recientes()
    {
        $user = Auth::user();
        
        $notificaciones = Notificacion::delUsuario($user->id)
            ->recientes(1) // Últimas 24 horas
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

    /**
     * Obtener las notificaciones no leídas (para AJAX)
     */
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

    /**
     * Obtener las notificaciones recientes para el dropdown
     */
    public function recientesParaDropdown()
    {
        $user = Auth::user();
        
        $notificaciones = Notificacion::delUsuario($user->id)
            ->recientes(7) // Últimos 7 días
            ->limit(8)
            ->get()
            ->map(function ($notificacion) {
                return [
                    'id' => $notificacion->id,
                    'titulo' => $notificacion->titulo,
                    'mensaje' => Str::limit($notificacion->mensaje, 50),
                    'tipo' => $notificacion->tipo,
                    'fecha_formateada' => $this->formatearFechaNotificacion($notificacion),
                    'leida' => $notificacion->leida,
                    'url' => $notificacion->accion_url
                ];
            });

        // Contar notificaciones no leídas
        $conteoNoLeidas = Notificacion::delUsuario($user->id)
            ->noLeidas()
            ->count();

        return response()->json([
            'notificaciones' => $notificaciones,
            'conteo_no_leidas' => $conteoNoLeidas
        ]);
    }

    /**
     * Formatear la fecha de la notificación para mostrar
     */
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

    /**
     * Marcar como leídas las notificaciones que han sido vistas
     */
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

        // Obtener el conteo actualizado de notificaciones no leídas
        $conteoRestante = Notificacion::delUsuario($user->id)
            ->noLeidas()
            ->count();

        return response()->json([
            'success' => true,
            'message' => 'Notificaciones marcadas como leídas.',
            'conteo_restante' => $conteoRestante
        ]);
    }

    /**
     * Limpiar notificaciones antiguas (solo administradores)
     */
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

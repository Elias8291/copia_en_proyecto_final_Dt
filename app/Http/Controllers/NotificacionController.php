<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Notificacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

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
        
        // Consulta base para las notificaciones del usuario
        $query = Notificacion::delUsuario($user->id)
            ->orderBy('created_at', 'desc');

        // Aplicar filtros si existen
        if ($request->filled('status')) {
            if ($request->status === 'no_leidas') {
                $query->noLeidas();
            } elseif ($request->status === 'leidas') {
                $query->leidas();
            }
        }

        if ($request->filled('type')) {
            $query->porTipo($request->type);
        }

        // Buscar en título y mensaje
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('titulo', 'like', "%{$search}%")
                  ->orWhere('mensaje', 'like', "%{$search}%");
            });
        }

        // Paginación
        $notificaciones = $query->paginate(15);

        // Estadísticas para el dashboard
        $estadisticas = [
            'total' => Notificacion::delUsuario($user->id)->count(),
            'no_leidas' => Notificacion::delUsuario($user->id)->noLeidas()->count(),
            'leidas' => Notificacion::delUsuario($user->id)->leidas()->count(),
            'recientes' => Notificacion::delUsuario($user->id)->recientes(7)->count()
        ];

        return view('notificaciones.index', compact('notificaciones', 'estadisticas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Solo usuarios con permisos pueden crear notificaciones
        $this->authorize('crear', Notificacion::class);
        
        return view('notificaciones.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('crear', Notificacion::class);

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
        $this->authorize('editar', $notificacion);
        
        return view('notificaciones.edit', compact('notificacion'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Notificacion $notificacion)
    {
        $this->authorize('editar', $notificacion);

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
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return response()->json($notificaciones);
    }

    /**
     * Obtener solo las notificaciones no leídas del usuario (para el header)
     */
    public function noLeidas()
    {
        $user = Auth::user();
        
        $notificaciones = Notificacion::delUsuario($user->id)
            ->noLeidas()
            ->recientes(7) // Últimos 7 días
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return response()->json($notificaciones);
    }

    /**
     * Obtener notificaciones recientes para el dropdown (leídas y no leídas)
     */
    public function recientesParaDropdown()
    {
        $user = Auth::user();
        
        // Obtener todas las notificaciones recientes (leídas y no leídas)
        $notificaciones = Notificacion::delUsuario($user->id)
            ->recientes(7) // Últimos 7 días
            ->orderBy('created_at', 'desc')
            ->limit(8) // Aumentamos el límite para mostrar más
            ->get();

        // Contar solo las no leídas para el badge
        $conteoNoLeidas = Notificacion::delUsuario($user->id)->noLeidas()->count();

        return response()->json([
            'notificaciones' => $notificaciones,
            'conteo_no_leidas' => $conteoNoLeidas
        ]);
    }

    /**
     * Marcar notificaciones como leídas al abrir el dropdown
     */
    public function marcarVistasComoLeidas()
    {
        $user = Auth::user();
        
        // Marcar las últimas notificaciones no leídas como leídas
        $notificaciones = Notificacion::delUsuario($user->id)
            ->noLeidas()
            ->recientes(7)
            ->orderBy('created_at', 'desc')
            ->limit(8)
            ->get();

        foreach ($notificaciones as $notificacion) {
            $notificacion->marcarComoLeida();
        }

        // Retornar el nuevo conteo de no leídas
        $conteoRestante = Notificacion::delUsuario($user->id)->noLeidas()->count();

        return response()->json([
            'success' => true,
            'marcadas' => $notificaciones->count(),
            'conteo_restante' => $conteoRestante
        ]);
    }

    /**
     * Eliminar notificaciones antiguas leídas
     */
    public function limpiarAntiguas()
    {
        $this->authorize('gestionar', Notificacion::class);

        $fechaLimite = Carbon::now()->subMonths(3);
        
        $eliminadas = Notificacion::where('leida', true)
            ->where('created_at', '<', $fechaLimite)
            ->delete();

        return redirect()->back()
            ->with('success', "Se eliminaron {$eliminadas} notificaciones antiguas.");
    }
}

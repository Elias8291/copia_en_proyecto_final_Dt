<?php

namespace App\Http\Controllers;

use App\Services\NotificacionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificacionController extends Controller
{
    protected $notificacionService;

    public function __construct(NotificacionService $notificacionService)
    {
        $this->notificacionService = $notificacionService;
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Obtener todas las notificaciones del usuario con paginación
        $notificaciones = \App\Models\Notificacion::where('usuario_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        return view('notificaciones.index', compact('notificaciones'));
    }

    public function contador()
    {
        $user = Auth::user();
        $notificaciones = $this->notificacionService->obtenerNotificacionesNoLeidas($user->id);
        
        return response()->json(['contador' => $notificaciones->count()]);
    }

    public function header()
    {
        $user = Auth::user();
        $notificaciones = $this->notificacionService->obtenerNotificacionesNoLeidas($user->id);
        
        return response()->json([
            'success' => true,
            'notificaciones' => $notificaciones->take(5),
            'contador_no_leidas' => $notificaciones->count(),
        ]);
    }

    public function marcarTodasLeidas()
    {
        $user = Auth::user();
        $success = $this->notificacionService->marcarTodasComoLeidas($user->id);
        
        if ($success) {
            return redirect()->route('notificaciones.index')->with('success', 'Todas las notificaciones han sido marcadas como leídas.');
        } else {
            return redirect()->route('notificaciones.index')->with('error', 'Error al marcar las notificaciones como leídas.');
        }
    }

    public function marcarComoLeida(Request $request)
    {
        $request->validate([
            'notificacion_id' => 'required|integer|exists:notificaciones,id'
        ]);

        $success = $this->notificacionService->marcarComoLeida($request->notificacion_id);
        
        return response()->json(['success' => $success]);
    }

    public function eliminarLeidas()
    {
        $user = Auth::user();
        
        try {
            $deleted = \App\Models\Notificacion::where('usuario_id', $user->id)
                ->where('leida', true)
                ->delete();
            
            if ($deleted > 0) {
                return redirect()->route('notificaciones.index')->with('success', 'Se han eliminado ' . $deleted . ' notificaciones leídas.');
            } else {
                return redirect()->route('notificaciones.index')->with('info', 'No hay notificaciones leídas para eliminar.');
            }
        } catch (\Exception $e) {
            return redirect()->route('notificaciones.index')->with('error', 'Error al eliminar notificaciones.');
        }
    }

    public function eliminarNotificacion(Request $request)
    {
        $request->validate([
            'notificacion_id' => 'required|integer|exists:notificaciones,id'
        ]);

        $user = Auth::user();
        
        try {
            $notificacion = \App\Models\Notificacion::where('id', $request->notificacion_id)
                ->where('usuario_id', $user->id)
                ->firstOrFail();
            
            $notificacion->delete();
            
            return redirect()->route('notificaciones.index')->with('success', 'Notificación eliminada correctamente.');
        } catch (\Exception $e) {
            return redirect()->route('notificaciones.index')->with('error', 'Error al eliminar la notificación.');
        }
    }

    public function getUserNotifications()
    {
        $user = Auth::user();
        $notificaciones = $this->notificacionService->obtenerNotificacionesNoLeidas($user->id);
        
        if ($notificaciones->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'El usuario no tiene notificaciones asociadas.',
            ]);
        }

        return response()->json([
            'success' => true,
            'notificaciones' => $notificaciones,
        ]);
    }
}

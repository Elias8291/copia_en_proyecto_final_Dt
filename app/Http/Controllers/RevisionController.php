<?php

namespace App\Http\Controllers;

use App\Models\Tramite;
use App\Services\NotificacionService;
use App\Services\CitaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class RevisionController extends Controller
{
    protected $notificacionService;
    protected $citaService;

    public function __construct(NotificacionService $notificacionService, CitaService $citaService)
    {
        $this->notificacionService = $notificacionService;
        $this->citaService = $citaService;
    }

    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $allowedPerPage = [5, 10, 25, 50, 100];
        if (!in_array($perPage, $allowedPerPage)) {
            $perPage = 10;
        }

        $tramites = Tramite::with(['proveedor', 'revisadoPor', 'datosGenerales'])
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);

        $tramites->appends($request->query());

        return view('revision.index', compact('tramites', 'perPage'));
    }

    public function show(Tramite $tramite)
    {
        $tramite->load([
            'proveedor',
            'revisadoPor',
            'datosGenerales',
            'datosConstitutivos',
            'apoderadoLegal',
            'contactos',
            'accionistas',
            'actividades.actividad',
            'archivos.catalogoArchivo'
        ]);

        return view('revision.show', compact('tramite'));
    }


    public function seleccionTipo(Tramite $tramite)
    {
        $tramite->load([
            'proveedor',
            'datosGenerales',
            'archivos' => function ($query) {
                $query->where('idCatalogoArchivo', 2)->with('catalogoArchivo');
            }
        ]);

        return view('revision.seleccion-tipo', compact('tramite'));
    }

    public function revisarDatos(Tramite $tramite)
    {
        try {
            $tramite->load([
                'proveedor',
                'revisadoPor',
                'datosGenerales',
                'datosConstitutivos',
                'apoderadoLegal',
                'contactos',
                'accionistas',
                'direcciones.estado',
                'actividades',
                'archivos.catalogoArchivo'
            ]);

            return view('revision.revisar-datos', compact('tramite'));
        } catch (\Exception $e) {
            Log::error('Error al cargar datos del trámite para revisión', [
                'tramite_id' => $tramite->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()->with('error', 'Error al cargar los datos del trámite: ' . $e->getMessage());
        }
    }



    public function verDocumento($tramiteId, $archivoId, $filename)
    {
        $archivo = Tramite::findOrFail($tramiteId)
            ->archivos()
            ->where('id', $archivoId)
            ->firstOrFail();

        $rutas = [
            storage_path('app/' . $archivo->ruta_archivo),
            storage_path('app/public/' . $archivo->ruta_archivo)
        ];

        foreach ($rutas as $ruta) {
            if (file_exists($ruta)) {
                return response()->file($ruta, [
                    'Content-Type' => mime_content_type($ruta) ?: 'application/octet-stream',
                    'Content-Disposition' => 'inline; filename="' . ($archivo->nombre_original ?: $filename) . '"'
                ]);
            }
        }

        abort(404, 'Archivo no encontrado');
    }

    /**
     * Actualiza el comentario (observaciones) de un documento (archivo).
     */
    public function actualizarComentarioDocumento(Request $request, $archivoId)
    {
        $request->validate([
            'comentario' => 'nullable|string|max:1000',
        ]);

        $archivo = \App\Models\Archivo::findOrFail($archivoId);
        $archivo->observaciones = $request->input('comentario');
        $archivo->save();

        return response()->json([
            'success' => true,
            'message' => 'Comentario actualizado correctamente.',
            'comentario' => $archivo->observaciones,
        ]);
    }

    /**
     * Actualiza el estado (aprobado) de un documento (archivo).
     */
    public function actualizarEstadoDocumento(Request $request, $archivoId)
    {
        $request->validate([
            'aprobado' => 'required|boolean',
        ]);

        $archivo = \App\Models\Archivo::findOrFail($archivoId);
        $archivo->aprobado = $request->input('aprobado');
        $archivo->save();

        return response()->json([
            'success' => true,
            'message' => 'Estado actualizado correctamente.',
            'aprobado' => $archivo->aprobado,
        ]);
    }

    /**
     * Cambia el estado de un trámite y notifica al usuario
     */
    public function cambiarEstadoTramite(Request $request, Tramite $tramite)
    {
        $request->validate([
            'nuevo_estado' => 'required|string|in:Pendiente,En_Revision,Por_Cotejar,Aprobado,Rechazado,Para_Correccion,Cancelado',
            'observaciones' => 'nullable|string|max:1000',
            'fecha_cita' => 'nullable|date|after:now'
        ]);

        try {
            $estadoAnterior = $tramite->estado;
            $nuevoEstado = $request->input('nuevo_estado');
            
            $this->actualizarTramite($tramite, $nuevoEstado, $request);
            $this->notificacionService->notificarCambioEstado($tramite, $estadoAnterior, $nuevoEstado);
            $this->agendarCitaSiNecesario($tramite, $nuevoEstado, $request);
            $this->registrarLog($tramite, $estadoAnterior, $nuevoEstado);

            // Mensajes de éxito según el estado
            $mensajes = [
                'Aprobado' => 'Tu trámite fue aprobado.',
                'Por_Cotejar' => 'Tu trámite fue enviado a cotejo.',
                'Rechazado' => 'Tu trámite fue rechazado.',
                'Para_Correccion' => 'Tu trámite fue enviado a corrección.',
                'Cancelado' => 'Tu trámite fue cancelado.',
                'En_Revision' => 'Tu trámite fue enviado a revisión.',
                'Pendiente' => 'Tu trámite fue marcado como pendiente.'
            ];

            $mensaje = $mensajes[$nuevoEstado] ?? 'Tu trámite fue actualizado.';

            return redirect()->route('revision.index')->with('success', $mensaje);

        } catch (\Exception $e) {
            Log::error('Error al cambiar estado del trámite', [
                'tramite_id' => $tramite->id,
                'error' => $e->getMessage()
            ]);

            return redirect()->route('revision.index')->with('error', 'Error al actualizar el estado del trámite: ' . $e->getMessage());
        }
    }



    /**
     * Actualiza los datos del trámite
     */
    private function actualizarTramite(Tramite $tramite, string $nuevoEstado, Request $request): void
    {
        $tramite->update([
            'estado' => $nuevoEstado,
            'observaciones' => $request->input('observaciones'),
            'revisado_por' => Auth::id()
        ]);
    }

    /**
     * Agenda cita si el estado es "Por_Cotejar"
     */
    private function agendarCitaSiNecesario(Tramite $tramite, string $nuevoEstado, Request $request): void
    {
        if ($nuevoEstado !== 'Por_Cotejar') {
            return;
        }

        $fechaCita = $request->input('fecha_cita') ?: now()->addWeekday()->setTime(9, 0);

        try {
            $this->citaService->agendarCitaCotejo($tramite, $fechaCita, Auth::id());
        } catch (\Exception $e) {
            Log::warning('No se pudo agendar cita automática', [
                'tramite_id' => $tramite->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Registra el log del cambio de estado
     */
    private function registrarLog(Tramite $tramite, string $estadoAnterior, string $nuevoEstado): void
    {
        Log::info('Estado de trámite cambiado exitosamente', [
            'tramite_id' => $tramite->id,
            'estado_anterior' => $estadoAnterior,
            'nuevo_estado' => $nuevoEstado,
            'revisado_por' => Auth::id()
        ]);
    }

    /**
     * Obtiene el historial de cambios de estado de un trámite
     */
    public function historialEstados(Tramite $tramite)
    {
        try {
            // Aquí podrías implementar un modelo de historial si lo necesitas
            // Por ahora, retornamos información básica del trámite
            $tramite->load(['revisadoPor', 'proveedor']);
            
            return response()->json([
                'success' => true,
                'tramite' => $tramite,
                'historial' => [
                    'estado_actual' => $tramite->estado,
                    'revisado_por' => $tramite->revisadoPor ? $tramite->revisadoPor->nombre : null,
                    'fecha_ultima_revision' => $tramite->updated_at,
                    'observaciones' => $tramite->observaciones
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error al obtener historial de estados', [
                'tramite_id' => $tramite->id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error al obtener el historial de estados.'
            ], 500);
        }
    }
}

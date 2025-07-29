<?php

namespace App\Http\Controllers;

use App\Models\Tramite;
use App\Services\NotificacionService;
use App\Services\ProveedorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class RevisionController extends Controller
{
    protected $notificacionService;
    protected $proveedorService;

    public function __construct(NotificacionService $notificacionService, ProveedorService $proveedorService)
    {
        $this->notificacionService = $notificacionService;
        $this->proveedorService = $proveedorService;
    }

    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $allowedPerPage = [5, 10, 25, 50, 100];
        if (!in_array($perPage, $allowedPerPage)) {
            $perPage = 10;
        }

        $tramites = Tramite::with(['proveedor.user', 'revisadoPor', 'datosGenerales'])
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);

        $tramites->appends($request->query());

        return view('revision.index', compact('tramites', 'perPage'));
    }

    public function show(Tramite $tramite)
    {
        $tramite->load([
            'proveedor.user',
            'datosGenerales',
            'archivos.catalogoArchivo',
            'revisionSecciones'
        ]);

        return view('revision.revision-digital', compact('tramite'));
    }

    /**
     * Aprobar un trámite
     */
    public function aprobarTramite(Tramite $tramite)
    {
        try {
            $proveedorService = app(ProveedorService::class);
            $resultado = $proveedorService->aprobarTramite($tramite);

            if ($resultado['success']) {
                // Crear notificación usando el servicio
                $mensaje = "Su trámite {$tramite->tipo_tramite} ha sido aprobado exitosamente. ";
                
                if ($resultado['pv_asignado']) {
                    $mensaje .= "PV Asignado: {$resultado['pv_asignado']}. ";
                }
                
                if ($resultado['fecha_vencimiento']) {
                    $mensaje .= "Fecha de vencimiento: {$resultado['fecha_vencimiento']}. ";
                }
                
                $mensaje .= "Su proveedor ha quedado inscrito al padrón.";

                $this->notificacionService->crearNotificacion(
                    $tramite->proveedor->user->id,
                    $tramite->id,
                    'Tramite',
                    'Trámite Aprobado - Inscrito al Padrón',
                    $mensaje
                );

                return response()->json([
                    'success' => true,
                    'message' => $resultado['message'],
                    'pv_asignado' => $resultado['pv_asignado'],
                    'fecha_vencimiento' => $resultado['fecha_vencimiento'],
                    'numero_oficio' => $resultado['numero_oficio'] ?? null
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => $resultado['message']
                ], 400);
            }
        } catch (\Exception $e) {
            Log::error('Error al aprobar trámite: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al procesar la aprobación'
            ], 500);
        }
    }




    public function seleccionTipo(Tramite $tramite)
    {
        $tramite->load([
            'proveedor.user',
            'datosGenerales',
            'archivos.catalogoArchivo'
        ]);

        return view('revision.seleccion-tipo', compact('tramite'));
    }

    public function documentosPresencial(Tramite $tramite)
    {
        $tramite->load([
            'proveedor.user',
            'datosGenerales',
            'archivos.catalogoArchivo'
        ]);

        return view('revision.documentos-presencial', compact('tramite'));
    }

    /**
     * Método principal para manejar diferentes tipos de revisión
     */
    public function revisarTramite(Tramite $tramite, $tipo = null)
    {
        try {
            // Si no se especifica tipo, mostrar la vista principal
            if (!$tipo) {
                return $this->show($tramite);
            }

            // Validar que el tipo sea válido
            $tiposValidos = ['seleccion-tipo', 'documentos-presencial', 'revision-digital'];
            if (!in_array($tipo, $tiposValidos)) {
                return redirect()->route('revision.revisar', $tramite)
                    ->with('error', 'Tipo de revisión no válido');
            }

            // Redirigir según el tipo de revisión
            switch ($tipo) {
                case 'seleccion-tipo':
                    return $this->seleccionTipo($tramite);
                case 'documentos-presencial':
                    return $this->documentosPresencial($tramite);
                case 'revision-digital':
                    return $this->revisarDatos($tramite);
                default:
                    return $this->show($tramite);
            }
        } catch (\Exception $e) {
            Log::error('Error en revisión de trámite', [
                'tramite_id' => $tramite->id,
                'tipo' => $tipo,
                'error' => $e->getMessage()
            ]);
            
            return redirect()->route('revision.index')
                ->with('error', 'Error al procesar la revisión: ' . $e->getMessage());
        }
    }

    public function revisarDatos(Tramite $tramite)
    {
        try {
            $tramite->load([
                'proveedor.user',
                'revisadoPor',
                'datosGenerales',
                'datosConstitutivos',
                'apoderadoLegal',
                'contactos',
                'accionistas',
                'direcciones.estado',
                'actividades.sector',
                'archivos.catalogoArchivo'
            ]);

            // Calcular el tipo de persona basado en el RFC del proveedor
            if ($tramite->proveedor) {
                $tipoPersona = $this->proveedorService->getTipoPersona($tramite->proveedor);
                
                // Si el proveedor no tiene tipo_persona asignado, actualizarlo
                if (!$tramite->proveedor->tipo_persona && $tipoPersona) {
                    $tramite->proveedor->update(['tipo_persona' => $tipoPersona]);
                    $tramite->load('proveedor'); // Recargar la relación
                }
            }

            return view('revision.revision-digital', compact('tramite'));
        } catch (\Exception $e) {
            Log::error('Error al cargar datos del trámite para revisión', [
                'tramite_id' => $tramite->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()->with('error', 'Error al cargar los datos del trámite: ' . $e->getMessage());
        }
    }



    /**
     * Obtiene información de identidad del trámite
     */
    public function obtenerInformacionIdentidad(Tramite $tramite)
    {
        try {
            $tramite->load(['proveedor.user', 'datosGenerales']);
            
            return response()->json([
                'success' => true,
                'data' => [
                    'proveedor' => $tramite->proveedor,
                    'datos_generales' => $tramite->datosGenerales,
                    'fecha_creacion' => $tramite->created_at,
                    'estado_actual' => $tramite->estado
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error al obtener información de identidad', [
                'tramite_id' => $tramite->id,
                'error' => $e->getMessage()
            ]);
            
            return response()->json(['error' => 'Error al obtener la información'], 500);
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
     * Actualiza comentario y estado de un documento en una sola operación
     */
    public function actualizarDocumentoCompleto(Request $request, $archivoId)
    {
        $request->validate([
            'comentario' => 'nullable|string|max:1000',
            'aprobado' => 'required|boolean',
        ]);

        try {
            $archivo = \App\Models\Archivo::findOrFail($archivoId);
            
            // Actualizar ambos campos en una sola operación
            $archivo->update([
                'observaciones' => $request->input('comentario'),
                'aprobado' => $request->input('aprobado'),
                'cotejado_por' => Auth::id(),
                'fecha_cotejo' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Documento actualizado correctamente.',
                'data' => [
                    'comentario' => $archivo->observaciones,
                    'aprobado' => $archivo->aprobado,
                    'fecha_cotejo' => $archivo->fecha_cotejo
                ]
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error al actualizar documento completo', [
                'archivo_id' => $archivoId,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar el documento: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtiene el estado y comentarios de un documento (archivo).
     */
    public function obtenerEstadoDocumento($archivoId)
    {
        $archivo = \App\Models\Archivo::findOrFail($archivoId);
        
        return response()->json([
            'success' => true,
            'data' => [
                'aprobado' => $archivo->aprobado,
                'observaciones' => $archivo->observaciones,
                'fecha_cotejo' => $archivo->fecha_cotejo,
            ]
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
            $this->registrarLog($tramite, $estadoAnterior, $nuevoEstado);

            // Mensajes de éxito según el estado
            $mensajes = [
                'Aprobado' => 'Tu trámite fue aprobado exitosamente.',
                'Por_Cotejar' => 'Tu trámite fue enviado a cotejo presencial exitosamente.',
                'Rechazado' => 'Tu trámite fue rechazado.',
                'Para_Correccion' => 'Tu trámite fue enviado para corrección.',
                'Cancelado' => 'Tu trámite fue cancelado.',
                'En_Revision' => 'Tu trámite fue enviado a revisión.',
                'Pendiente' => 'Tu trámite fue marcado como pendiente.'
            ];

            $titulos = [
                'Aprobado' => '¡Trámite Aprobado!',
                'Por_Cotejar' => '¡Enviado a Cotejo!',
                'Rechazado' => 'Trámite Rechazado',
                'Para_Correccion' => 'Enviado para Corrección',
                'Cancelado' => 'Trámite Cancelado',
                'En_Revision' => 'Enviado a Revisión',
                'Pendiente' => 'Trámite Pendiente'
            ];

            $mensaje = $mensajes[$nuevoEstado] ?? 'Tu trámite fue actualizado exitosamente.';
            $titulo = $titulos[$nuevoEstado] ?? '¡Trámite Procesado!';

            return redirect()->route('revision.revisar', ['tramite' => $tramite, 'tipo' => 'revision-digital'])->with([
                'success' => true,
                'success_title' => $titulo,
                'success_message' => $mensaje,
                'success_accept_text' => 'Ir al listado',
                'success_redirect' => route('revision.index')
            ]);

        } catch (\Exception $e) {
            Log::error('Error al cambiar estado del trámite', [
                'tramite_id' => $tramite->id,
                'error' => $e->getMessage()
            ]);

            return redirect()->route('revision.revisar', ['tramite' => $tramite, 'tipo' => 'revision-digital'])->with([
                'error' => true,
                'error_title' => 'Error al procesar trámite',
                'error_message' => 'Ha ocurrido un error al actualizar el estado del trámite: ' . $e->getMessage(),
                'error_button_text' => 'Entendido'
            ]);
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

        // Si el estado es "Por_Cotejar", agendar cita automáticamente
        if ($nuevoEstado === 'Por_Cotejar') {
            $this->agendarCitaCotejo($tramite);
        }

        // Si el estado es "Aprobado", crear oficio automáticamente
        if ($nuevoEstado === 'Aprobado') {
            $this->crearOficioAprobacion($tramite);
        }
    }

    /**
     * Agenda una cita automática para cotejo
     */
    private function agendarCitaCotejo(Tramite $tramite): void
    {
        try {
            $citaService = app(\App\Services\CitaService::class);
            $cita = $citaService->agendarCitaCotejo($tramite);

            if ($cita) {
                Log::info('Cita de cotejo agendada automáticamente', [
                    'cita_id' => $cita->id,
                    'tramite_id' => $tramite->id,
                    'fecha_cita' => $cita->fecha_cita
                ]);
                
                // Notificar al usuario sobre la cita agendada
                $this->notificacionService->notificarCitaAgendada($tramite, $cita);
            } else {
                Log::warning('No se pudo agendar cita automática para cotejo', [
                    'tramite_id' => $tramite->id
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Error al agendar cita de cotejo', [
                'tramite_id' => $tramite->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Crea automáticamente un oficio de aprobación
     */
    private function crearOficioAprobacion(Tramite $tramite): void
    {
        try {
            // Verificar si ya existe un oficio para este trámite
            $oficioExistente = \App\Models\Oficio::where('tramite_id', $tramite->id)->first();
            
            if ($oficioExistente) {
                Log::info('Oficio ya existe para el trámite', [
                    'tramite_id' => $tramite->id,
                    'oficio_id' => $oficioExistente->id
                ]);
                return;
            }

            // Crear nuevo oficio usando el servicio
            $oficioService = app(\App\Services\OficioService::class);
            $oficio = $oficioService->crearOficioAprobacion($tramite);

            Log::info('Oficio de aprobación creado automáticamente', [
                'tramite_id' => $tramite->id,
                'oficio_id' => $oficio->id,
                'numero_oficio' => $oficio->numero_oficio
            ]);

        } catch (\Exception $e) {
            Log::error('Error al crear oficio de aprobación', [
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

    public function guardarComentarioGeneral(Request $request)
    {
        try {
            $tramite = Tramite::findOrFail($request->tramite_id);
            $tramite->update([
                'comentarios_revision' => $request->comentario
            ]);

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    public function obtenerEstados(Tramite $tramite)
    {
        try {
            $tramite->load(['revisionSecciones', 'archivos']);
            
            $seccionesPendientes = [];
            $seccionesRechazadas = [];
            $documentosPendientes = [];
            $documentosRechazados = [];
            
            // Obtener estados de secciones
            foreach ($tramite->revisionSecciones as $revision) {
                if ($revision->estado === 'Pendiente') {
                    $seccionesPendientes[] = $revision->seccion;
                } elseif ($revision->estado === 'Rechazado') {
                    $seccionesRechazadas[] = $revision->seccion;
                }
            }
            
            // Obtener estados de documentos
            foreach ($tramite->archivos as $archivo) {
                if ($archivo->aprobado === null) {
                    $documentosPendientes[] = $archivo->catalogoArchivo->nombre ?? 'Documento';
                } elseif ($archivo->aprobado === false) {
                    $documentosRechazados[] = $archivo->catalogoArchivo->nombre ?? 'Documento';
                }
            }
            
            return response()->json([
                'seccionesPendientes' => $seccionesPendientes,
                'seccionesRechazadas' => $seccionesRechazadas,
                'documentosPendientes' => $documentosPendientes,
                'documentosRechazados' => $documentosRechazados
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}

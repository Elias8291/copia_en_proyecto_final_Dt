<?php

namespace App\Http\Controllers;

use App\Models\Tramite;
use App\Services\NotificacionService;
use App\Services\ProveedorService;
use App\Services\DocumentosService;
use App\Services\Tramites\CitaTramiteService;
use App\Services\Tramites\RespuestaHttpService;
use App\Services\Revision\RevisionDocumentosService;
use App\Services\Revision\EstadoTramiteService;
use App\Services\Revision\RevisionVisualizacionService;
use App\Services\Revision\HistorialRevisionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

/**
 * Controlador para revisión de trámites
 * Responsabilidad: Solo orquestar peticiones y delegar a servicios especializados
 */
class RevisionController extends Controller
{
    public function __construct(
        private NotificacionService $notificacionService,
        private ProveedorService $proveedorService,
        private DocumentosService $documentosService,
        private CitaTramiteService $citaTramiteService,
        private RespuestaHttpService $respuestaHttpService,
        private RevisionDocumentosService $revisionDocumentosService,
        private EstadoTramiteService $estadoTramiteService,
        private RevisionVisualizacionService $visualizacionService,
        private HistorialRevisionService $historialService
    ) {}

    public function index(Request $request)
    {
        $datos = $this->visualizacionService->obtenerTramitesPaginados($request);
        return view('revision.index', $datos);
    }

    public function show(Tramite $tramite)
    {
        $datos = $this->visualizacionService->prepararDatosBasicos($tramite);
        return view('revision.revision-digital', $datos);
    }

    /**
     * Aprobar un trámite
     */
    public function aprobarTramite(Tramite $tramite)
    {
        try {
            $resultado = $this->proveedorService->aprobarTramite($tramite);

            if ($resultado['success']) {
                $this->crearNotificacionAprobacion($tramite, $resultado);
                return $this->respuestaHttpService->respuestaAprobacionTramite($resultado);
            } else {
                return $this->respuestaHttpService->respuestaError($resultado['message']);
            }
        } catch (\Exception $e) {
            Log::error('Error al aprobar trámite: ' . $e->getMessage());
            return $this->respuestaHttpService->respuestaError('Error al procesar la aprobación');
        }
    }

    /**
     * Agendar cita automática para un trámite
     */
    public function agendarCitaAutomatica(Tramite $tramite)
    {
        try {
            $resultado = $this->citaTramiteService->reagendarCita($tramite);

            if ($resultado['success']) {
                // Crear notificación
                $this->notificacionService->crearNotificacion(
                    $tramite->proveedor->user->id,
                    $tramite->id,
                    'Cita',
                    'Cita ' . ucfirst($resultado['tipo_accion']) . ' Automáticamente',
                    $resultado['mensaje']
                );

                return $this->respuestaHttpService->respuestaCitaTramite($resultado);
            } else {
                return $this->respuestaHttpService->respuestaCitaTramite($resultado);
            }
        } catch (\Exception $e) {
            Log::error('Error al agendar/reagendar cita automática: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al procesar la cita: ' . $e->getMessage());
        }
    }




    public function seleccionTipo(Tramite $tramite)
    {
        $datos = $this->visualizacionService->prepararDatosBasicos($tramite);
        return view('revision.seleccion-tipo', $datos);
    }

    public function documentosPresencial(Tramite $tramite)
    {
        $datos = $this->visualizacionService->prepararDatosBasicos($tramite);
        return view('revision.documentos-presencial', $datos);
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
            $datos = $this->visualizacionService->prepararDatosRevisionDigital($tramite);
            return view('revision.revision-digital', $datos);
        } catch (\Exception $e) {
            Log::error('Error al cargar datos del trámite para revisión', [
                'tramite_id' => $tramite->id,
                'error' => $e->getMessage()
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
            $informacion = $this->visualizacionService->obtenerInformacionIdentidad($tramite);
            
            return response()->json([
                'success' => true,
                'data' => $informacion
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
        $rutaArchivo = $this->revisionDocumentosService->obtenerRutaDocumento($tramiteId, $archivoId);
        
        if (!$rutaArchivo) {
            abort(404, 'Archivo no encontrado');
        }

        $informacionDocumento = $this->revisionDocumentosService->obtenerInformacionDocumento($archivoId);
        $nombreArchivo = $informacionDocumento['nombre_original'] ?? $filename;

        return response()->file($rutaArchivo, [
            'Content-Type' => mime_content_type($rutaArchivo) ?: 'application/octet-stream',
            'Content-Disposition' => 'inline; filename="' . $nombreArchivo . '"'
        ]);
    }

    /**
     * Actualiza el comentario (observaciones) de un documento (archivo).
     */
    public function actualizarComentarioDocumento(Request $request, $archivoId)
    {
        $request->validate([
            'comentario' => 'nullable|string|max:1000',
        ]);

        $resultado = $this->revisionDocumentosService->actualizarComentario(
            $archivoId, 
            $request->input('comentario')
        );

        return response()->json($resultado);
    }

    /**
     * Actualiza el estado (aprobado) de un documento (archivo).
     */
    public function actualizarEstadoDocumento(Request $request, $archivoId)
    {
        $request->validate([
            'aprobado' => 'required|boolean',
        ]);

        $resultado = $this->revisionDocumentosService->actualizarEstado(
            $archivoId, 
            $request->input('aprobado')
        );

        return response()->json($resultado);
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

        $resultado = $this->revisionDocumentosService->actualizarDocumentoCompleto(
            $archivoId,
            $request->input('comentario'),
            $request->input('aprobado')
        );

        $statusCode = $resultado['success'] ? 200 : 500;
        return response()->json($resultado, $statusCode);
    }

    /**
     * Obtiene el estado y comentarios de un documento (archivo).
     */
    public function obtenerEstadoDocumento($archivoId)
    {
        $resultado = $this->revisionDocumentosService->obtenerEstadoDocumento($archivoId);
        $statusCode = $resultado['success'] ? 200 : 500;
        return response()->json($resultado, $statusCode);
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

        $resultado = $this->estadoTramiteService->cambiarEstado(
            $tramite, 
            $request->input('nuevo_estado'),
            $request->input('observaciones')
        );

        if ($resultado['success']) {
            return redirect()->route('revision.revisar', ['tramite' => $tramite, 'tipo' => 'revision-digital'])->with([
                'success' => true,
                'success_title' => $resultado['titulo'],
                'success_message' => $resultado['mensaje'],
                'success_accept_text' => 'Ir al listado',
                'success_redirect' => route('revision.index')
            ]);
        } else {
            return redirect()->route('revision.revisar', ['tramite' => $tramite, 'tipo' => 'revision-digital'])->with([
                'error' => true,
                'error_title' => $resultado['titulo'],
                'error_message' => $resultado['mensaje'],
                'error_button_text' => 'Entendido'
            ]);
        }
    }





    /**
     * Obtiene el historial de cambios de estado de un trámite
     */
    public function historialEstados(Tramite $tramite)
    {
        $resultado = $this->historialService->obtenerHistorialEstados($tramite);
        $statusCode = $resultado['success'] ? 200 : 500;
        return response()->json($resultado, $statusCode);
    }

    public function guardarComentarioGeneral(Request $request)
    {
        $resultado = $this->historialService->guardarComentarioGeneral(
            $request->tramite_id,
            $request->comentario
        );

        return response()->json($resultado);
    }

    public function obtenerEstados(Tramite $tramite)
    {
        try {
            $estados = $this->visualizacionService->obtenerEstadosRevision($tramite);
            
            return response()->json([
                'seccionesPendientes' => $estados['secciones']['pendientes'],
                'seccionesRechazadas' => $estados['secciones']['rechazadas'],
                'documentosPendientes' => $estados['documentos']['pendientes'],
                'documentosRechazados' => $estados['documentos']['rechazados']
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Muestra la vista de cotejo domiciliario con mapa
     */
    public function cotejoDomiciliario(Tramite $tramite)
    {
        $datos = $this->visualizacionService->prepararDatosCotejoDomiciliario($tramite);
        return view('revision.cotejo-domiciliario', $datos);
    }

    /**
     * Obtiene los archivos del catálogo 2 asociados a un trámite
     */
    public function obtenerArchivosCatalogo2(Tramite $tramite)
    {
        $resultado = $this->visualizacionService->obtenerArchivosCatalogo2($tramite);
        $statusCode = $resultado['success'] ? 200 : 500;
        return response()->json($resultado, $statusCode);
    }

    /**
     * Obtener cita activa para un trámite
     */
    public function obtenerCitaActiva(Tramite $tramite)
    {
        try {
            $cita = $this->citaTramiteService->obtenerCitaActiva($tramite);
            
            if ($cita) {
                return response()->json([
                    'success' => true,
                    'cita' => [
                        'id' => $cita->id,
                        'fecha_cita' => $cita->fecha_cita,
                        'estado' => $cita->estado,
                        'tipo_cita' => $cita->tipo_cita,
                        'motivo' => $cita->motivo,
                        'observaciones' => $cita->observaciones
                    ]
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'No se encontró cita activa para este trámite'
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Error al obtener cita activa', [
                'tramite_id' => $tramite->id,
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener la cita activa'
            ], 500);
        }
    }

    /**
     * Crea una notificación de aprobación
     */
    private function crearNotificacionAprobacion(Tramite $tramite, array $resultado): void
    {
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
    }


}

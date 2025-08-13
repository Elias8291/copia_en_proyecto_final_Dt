<?php

namespace App\Http\Controllers;

use App\Models\Tramite;
use App\Models\Archivo;
use App\Models\SeccionRevision;
use App\Services\RevisionService;
use App\Services\Revisiones\RevisionDigitalService;
use App\Services\Revisiones\RevisionPresencialService;
use App\Services\Revisiones\DecisionesFinalesService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Middleware\PermissionMiddleware;

class RevisionController extends Controller
{
    private RevisionService $revisionService;
    private RevisionDigitalService $revisionDigitalService;
    private RevisionPresencialService $revisionPresencialService;

    public function __construct(
        RevisionService $revisionService,
        RevisionDigitalService $revisionDigitalService,
        RevisionPresencialService $revisionPresencialService,
        DecisionesFinalesService $decisionesFinalesService
    ) {
        $this->revisionService = $revisionService;
        $this->revisionDigitalService = $revisionDigitalService;
        $this->revisionPresencialService = $revisionPresencialService;
        $this->decisionesFinalesService = $decisionesFinalesService;

        // Middleware de permisos para revisiones
        $this->middleware(PermissionMiddleware::class . ':revisiones.ver')->only(['index', 'seleccionarTipoRevision', 'verTramiteHistorico', 'mostrarArchivo', 'obtenerEstadoSeccion', 'obtenerEstadoGeneral']);
        $this->middleware(PermissionMiddleware::class . ':revisiones.revisar')->only(['iniciarRevision', 'revisarTramite', 'agendarCita', 'reagendarCita', 'obtenerHorariosDisponibles', 'evaluarSeccion', 'procesarRevisionDigital', 'aprobarYAgendarCita', 'rechazarParaCorreccion', 'rechazarCompleto', 'aprobar', 'rechazarTramite', 'procesarRevisionPresencial']);
    }

    /** Listar trámites para revisión */
    public function index(Request $request)
    {
        $tramites = $this->revisionService->obtenerTramitesPendientes($request);
        return view('revisiones.index', compact('tramites'));
    }

    /** Seleccionar tipo de revisión */
    public function seleccionarTipoRevision(int $tramiteId)
    {
        $datos = $this->revisionService->obtenerDatosSeleccionTipo($tramiteId);
        return view('revisiones.seleccionar-tipo', $datos);
    }

    /** Iniciar revisión */
    public function iniciarRevision(Request $request, int $tramiteId)
    {
        $request->validate(['tipo_revision' => 'required|in:Digital,Presencial,Domiciliaria']);
        $this->revisionService->iniciarRevision($tramiteId, $request->tipo_revision);
        return redirect()->route('revisiones.revisar', ['tramite' => $tramiteId, 'tipo_revision' => $request->tipo_revision]);
    }

    /** Revisar trámite */
    public function revisarTramite(Request $request, int $tramiteId)
    {
        $tipoRevision = $request->get('tipo_revision', 'Digital');
        $request->session()->forget(['success', 'success_title', 'success_message', 'success_accept_text', 'success_redirect']);
        
        if ($tipoRevision === 'Digital') {
            $datos = $this->revisionDigitalService->obtenerDatosRevisionDigital($tramiteId);
        } elseif ($tipoRevision === 'Presencial') {
            $datos = $this->revisionPresencialService->obtenerDatosRevisionPresencial($tramiteId);
        } else {
            $datos = $this->revisionService->obtenerDatosRevision($tramiteId, $tipoRevision);
        }
        
        $vista = $this->revisionService->obtenerVistaRevision($tipoRevision);
        return view($vista, $datos);
    }

    /** Agendar cita */
    public function agendarCita(Request $request, int $tramiteId)
    {
        $request->validate(['fecha' => 'required|date|after:today', 'hora' => 'required', 'tipo_cita' => 'required|in:Presencial,Domiciliaria']);
        $cita = $this->revisionService->agendarCita($tramiteId, $request->all());
        return response()->json(['success' => true, 'message' => 'Cita agendada exitosamente', 'cita' => $cita]);
    }

    /** Reagendar cita */
    public function reagendarCita(Request $request, int $citaId)
    {
        $request->validate(['fecha' => 'required|date|after:today', 'hora' => 'required']);
        $cita = $this->revisionService->reagendarCita($citaId, $request->all());
        return response()->json(['success' => true, 'message' => 'Cita reagendada exitosamente', 'cita' => $cita]);
    }

    /** Obtener horarios disponibles */
    public function obtenerHorariosDisponibles(Request $request)
    {
        $fecha = $request->get('fecha');
        $tipo = $request->get('tipo_cita', 'Presencial');
        
        if (!$fecha) {
            return response()->json(['error' => 'Fecha requerida'], 400);
        }
        
        $horarios = $this->revisionService->obtenerHorariosDisponibles($fecha, $tipo);
        return response()->json(['horarios' => $horarios]);
    }

    /** Ver trámite histórico */
    public function verTramiteHistorico(int $tramiteId)
    {
        $datos = $this->revisionService->obtenerDatosRevision($tramiteId, 'Historico');
        return view('revisiones.historico', $datos);
    }

    /** Mostrar archivo */
    public function mostrarArchivo(int $id)
    {
        try {
            $archivo = Archivo::find($id);
            if (!$archivo) {
                return response("Archivo con ID {$id} NO EXISTE en la base de datos", 404);
            }
            
            // Posibles ubicaciones donde puede estar el archivo
            $posiblesRutas = [
                $archivo->ruta,                           // Ruta original en BD
                'public/' . $archivo->ruta,               // Con prefijo public/
                'tramites/' . basename($archivo->ruta),   // Solo en carpeta tramites/
                'public/tramites/' . basename($archivo->ruta), // En public/tramites/
            ];
            
            $rutaCorrecta = null;
            foreach ($posiblesRutas as $ruta) {
                if (Storage::exists($ruta)) {
                    $rutaCorrecta = $ruta;
                    break;
                }
            }
            
            if (!$rutaCorrecta) {
                return response("Archivo NO encontrado en ninguna ubicación", 404);
            }
            
            $mimeType = Storage::mimeType($rutaCorrecta);
            $contenido = Storage::get($rutaCorrecta);
            
            return response($contenido)
                ->header('Content-Type', $mimeType)
                ->header('Content-Disposition', 'inline; filename="' . $archivo->nombre_original . '"');
                
        } catch (\Exception $e) {
            return response('Error: ' . $e->getMessage(), 404);
        }
    }

    // Obtener estado de evaluación de una sección
    public function obtenerEstadoSeccion(Request $request, int $tramiteId)
    {
        $seccion = $request->get('seccion');
        $estado = $this->revisionService->obtenerEstadoSeccion($tramiteId, $seccion);
        
        return response()->json($estado);
    }

    // Obtener estado general del trámite
    public function obtenerEstadoGeneral(int $tramiteId)
    {
        try {
            $estado = $this->revisionService->obtenerEstadoGeneral($tramiteId);
            return response()->json($estado);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener el estado general: ' . $e->getMessage()
            ], 500);
        }
    }

    // Guardar evaluación de sección
    public function evaluarSeccion(Request $request, int $tramiteId)
    {
        $request->validate([
            'seccion' => 'required|string',
            'estado' => 'required|in:Aprobado,Rechazado',
            'comentario' => 'nullable|string|max:1000'
        ]);

        $resultado = $this->revisionService->evaluarSeccion($tramiteId, $request->all());
        
        return response()->json([
            'success' => true,
            'message' => 'Sección evaluada correctamente',
            'data' => $resultado
        ]);
    }

    // Limpiar sesiones
    public function limpiarSesiones(Request $request)
    {
        $request->session()->forget(['success', 'success_title', 'success_message', 'success_accept_text', 'success_redirect']);
        
        return response()->json([
            'success' => true,
            'message' => 'Sesiones limpiadas correctamente'
        ]);
    }

    /**
     * Procesar revisión digital completa
     */
    public function procesarRevisionDigital(Request $request, Tramite $tramite)
    {
        try {
            \Log::info("=== INICIANDO PROCESAMIENTO DE REVISIÓN DIGITAL ===");
            \Log::info("Trámite ID: " . $tramite->id);
            \Log::info("Datos recibidos: " . json_encode($request->all(), JSON_PRETTY_PRINT));

            $request->validate([
                'secciones' => 'required|array',
                'archivos' => 'nullable|array',
                'archivos.*.id' => 'required|integer',
                'archivos.*.status' => 'required|in:Pendiente,Aprobado,Rechazado',
                'archivos.*.comentario_revision' => 'nullable|string'
            ]);
            
            // Procesar secciones
            $secciones = $request->input('secciones', []);
            foreach ($secciones as $seccion => $datos) {
                if (isset($datos['decision']) && isset($datos['comentario'])) {
                    $this->revisionService->evaluarSeccion($tramite->id, [
                        'seccion' => $seccion,
                        'estado' => $datos['decision'],
                        'comentario' => $datos['comentario']
                    ]);
                }
            }

            // Procesar archivos
            $archivos = $request->input('archivos', []);
            foreach ($archivos as $archivoData) {
                $archivo = Archivo::findOrFail($archivoData['id']);
                $archivo->update([
                    'status' => $archivoData['status'],
                    'comentario_revision' => $archivoData['comentario_revision'] ?? null,
                    'revisado_por' => auth()->id()
                ]);
            }

            // Determinar estado final del trámite
            $estadoGeneral = $this->revisionService->obtenerEstadoGeneral($tramite->id);
            
            // Actualizar estado del trámite
            $tramite->update([
                'status' => $estadoGeneral['estado']
            ]);

            \Log::info("Estado final del trámite: " . $estadoGeneral['estado']);
            \Log::info("=== FIN PROCESAMIENTO DE REVISIÓN DIGITAL ===");

            return response()->json([
                'success' => true,
                'message' => 'Revisión digital procesada correctamente',
                'data' => [
                    'estado_tramite' => $estadoGeneral['estado'],
                    'secciones_evaluadas' => $estadoGeneral['seccionesEvaluadas'],
                    'total_secciones' => $estadoGeneral['totalSecciones']
                ]
            ]);

        } catch (\Exception $e) {
            \Log::error("Error en procesarRevisionDigital: " . $e->getMessage());
            \Log::error("Stack trace: " . $e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'Error al procesar la revisión digital: ' . $e->getMessage()
            ], 500);
        }
    }

    /** Aprobar trámite y agendar cita */
    public function aprobarYAgendarCita(Request $request, int $tramiteId)
    {
        try {
            $request->validate(['comentario_general' => 'nullable']);
            $comentarioGeneral = $request->input('comentario_general') ?: null;
            
            $decisionesService = app(\App\Services\Revisiones\DecisionesFinalesService::class);
            $resultado = $decisionesService->aprobarYAgendarCita($tramiteId, $comentarioGeneral);
            
            if ($resultado['success']) {
                return response()->json($resultado);
            }
            
            return response()->json($resultado);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error al aprobar el trámite: ' . $e->getMessage()]);
        }
    }

    /** Rechazar para corrección */
    public function rechazarParaCorreccion(Request $request, int $tramiteId)
    {
        try {
            $request->validate(['comentario_general' => 'nullable']);
            $comentarioGeneral = $request->input('comentario_general') ?: null;
            
            $decisionesService = app(\App\Services\Revisiones\DecisionesFinalesService::class);
            $resultado = $decisionesService->rechazarParaCorreccion($tramiteId, $comentarioGeneral);
            
            if ($resultado['success']) {
                return response()->json($resultado);
            }
            
            return response()->json($resultado);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error al rechazar el trámite: ' . $e->getMessage()]);
        }
    }

    /** Rechazar completamente */
    public function rechazarCompleto(Request $request, int $tramiteId)
    {
        try {
            $request->validate(['comentario_general' => 'nullable']);
            $comentarioGeneral = $request->input('comentario_general') ?: null;
            
            $decisionesService = app(\App\Services\Revisiones\DecisionesFinalesService::class);
            $resultado = $decisionesService->rechazarCompleto($tramiteId, $comentarioGeneral);
            
            if ($resultado['success']) {
                return response()->json($resultado);
            }
            
            return response()->json($resultado);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error al rechazar el trámite: ' . $e->getMessage()]);
        }
    }

    /** Aprobar y asignar proveedor */
    public function aprobar(Request $request, int $tramiteId)
    {
        try {
            $request->validate(['comentario_general' => 'nullable']);
            $comentarioGeneral = $request->input('comentario_general') ?: null;
            
            \Log::info("Iniciando aprobación automática para trámite {$tramiteId}", [
                'request_data' => $request->all(),
                'user_id' => auth()->id(),
                'timestamp' => now()
            ]);
            
            // Verificar que el trámite existe
            $tramite = \App\Models\Tramite::findOrFail($tramiteId);
            
            \Log::info("Trámite encontrado", [
                'tramite_id' => $tramite->id,
                'tipo_tramite' => $tramite->tipo_tramite,
                'status' => $tramite->status
            ]);
            
            // Usar el DecisionesFinalesService para manejar la aprobación según el tipo de trámite
            $decisionesService = app(\App\Services\Revisiones\DecisionesFinalesService::class);
            
            // Determinar la acción según el tipo de trámite
            $tipoTramite = strtolower($tramite->tipo_tramite);
            
            switch ($tipoTramite) {
                case 'inscripcion':
                    // Para inscripción: aprobar y asignar proveedor
                    $resultado = $decisionesService->aprobarYAsignarProveedor($tramiteId, $comentarioGeneral);
                    break;
                    
                case 'renovacion':
                    // Para renovación: aprobar y renovar proveedor
                    $resultado = $decisionesService->aprobarYRenovarProveedor($tramiteId, $comentarioGeneral);
                    break;
                    
                case 'actualizacion':
                    // Para actualización: aprobar y actualizar proveedor
                    $resultado = $decisionesService->aprobarYActualizarProveedor($tramiteId, $comentarioGeneral);
                    break;
                    
                default:
                    throw new \Exception("Tipo de trámite no válido: {$tramite->tipo_tramite}");
            }
            
            if ($resultado['success']) {
                \Log::info("Trámite {$tramiteId} aprobado exitosamente según tipo: {$tipoTramite}", $resultado);
                return response()->json($resultado);
            }
            
            \Log::warning("Trámite {$tramiteId} no pudo ser aprobado", $resultado);
            return response()->json($resultado);
            
        } catch (\Exception $e) {
            \Log::error("Error al aprobar y asignar proveedor para trámite {$tramiteId}", [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);
            
            return response()->json([
                'success' => false, 
                'message' => 'Error al aprobar y asignar proveedor: ' . $e->getMessage(),
                'debug_info' => config('app.debug') ? [
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => $e->getTraceAsString()
                ] : null
            ]);
        }
    }

    /** Rechazar trámite */
    public function rechazarTramite(Request $request, int $tramiteId)
    {
        try {
            $request->validate(['comentario_general' => 'nullable']);
            $comentarioGeneral = $request->input('comentario_general') ?: null;
            
            $decisionesService = app(\App\Services\Revisiones\DecisionesFinalesService::class);
            $resultado = $decisionesService->rechazarCompleto($tramiteId, $comentarioGeneral);
            
            if ($resultado['success']) {
                return response()->json($resultado);
            }
            
            return response()->json($resultado);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error al rechazar el trámite: ' . $e->getMessage()]);
        }
    }

    /** Procesar revisión presencial */
    public function procesarRevisionPresencial(Request $request, int $tramiteId)
    {
        $request->validate([
            'tipo_revision' => 'required|in:Presencial',
            'decision_documentos' => 'required|in:Aprobado,Rechazado,Pendiente',
            'comentarios_presencial' => 'nullable|string',
            'observaciones' => 'nullable|string'
        ]);

        try {
            $tramite = Tramite::findOrFail($tramiteId);
            
            // Guardar comentarios del cotejo presencial
            if ($request->filled('comentarios_presencial')) {
                SeccionRevision::updateOrCreate(
                    [
                        'tramite_id' => $tramiteId,
                        'seccion' => 'documentos_presencial'
                    ],
                    [
                        'estado' => $request->decision_documentos,
                        'comentario' => $request->comentarios_presencial,
                        'revisado_por' => auth()->id()
                    ]
                );
            }

            // Actualizar observaciones del trámite
            if ($request->filled('observaciones')) {
                $tramite->update(['observaciones' => $request->observaciones]);
            }

            // Determinar acción final basada en la decisión de documentos
            if ($request->decision_documentos === 'Aprobado') {
                // Si los documentos están aprobados, permitir aprobar el trámite
                return response()->json([
                    'success' => true,
                    'message' => 'Revisión presencial completada. Los documentos están conformes.',
                    'action' => 'aprobar_disponible'
                ]);
            } else {
                // Si los documentos no están aprobados, enviar para corrección
                $tramite->update([
                    'status' => 'Para_Correccion',
                    'correcciones_count' => $tramite->correcciones_count + 1
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Revisión presencial completada. El trámite ha sido enviado para corrección.',
                    'action' => 'enviado_correccion'
                ]);
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al procesar la revisión presencial: ' . $e->getMessage()
            ], 500);
        }
    }

    /** Limpiar sesión de éxito */
    public function limpiarSesionExito()
    {
        session()->forget(['success', 'success_title', 'success_message', 'success_accept_text', 'success_redirect']);
        return response()->json(['success' => true]);
    }
} 
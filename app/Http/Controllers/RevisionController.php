<?php

namespace App\Http\Controllers;

use App\Models\Tramite;
use App\Models\Archivo;
use App\Models\SeccionRevision;
use App\Models\RevisionTramite;
use App\Services\RevisionService;
use App\Services\Revisiones\RevisionDigitalService;
use App\Services\Revisiones\RevisionPresencialService;
use App\Services\Revisiones\RevisionDomiciliariaService;
use App\Services\Revisiones\DecisionesFinalesService;
use App\Services\NotificacionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Middleware\PermissionMiddleware;

class RevisionController extends Controller
{
    private RevisionService $revisionService;
    private RevisionDigitalService $revisionDigitalService;
    private RevisionPresencialService $revisionPresencialService;
    private RevisionDomiciliariaService $revisionDomiciliariaService;

    public function __construct(
        RevisionService $revisionService,
        RevisionDigitalService $revisionDigitalService,
        RevisionPresencialService $revisionPresencialService,
        RevisionDomiciliariaService $revisionDomiciliariaService,
        DecisionesFinalesService $decisionesFinalesService
    ) {
        $this->revisionService = $revisionService;
        $this->revisionDigitalService = $revisionDigitalService;
        $this->revisionPresencialService = $revisionPresencialService;
        $this->revisionDomiciliariaService = $revisionDomiciliariaService;
        $this->decisionesFinalesService = $decisionesFinalesService;

        // Sin middleware de permisos: acceso abierto (solo autenticación por rutas)
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
        
        // Verificar si el trámite ya está en un estado final
        $tramite = Tramite::findOrFail($tramiteId);
        $estadosFinales = ['Aprobado', 'Rechazado', 'Cancelado'];
        
        if (in_array($tramite->status, $estadosFinales)) {
            $mensaje = match($tramite->status) {
                'Aprobado' => 'Este trámite ya ha sido aprobado.',
                'Rechazado' => 'Este trámite ya ha sido rechazado.',
                'Cancelado' => 'Este trámite ha sido cancelado.',
                default => 'Este trámite ya ha sido procesado.'
            };
            
            return redirect()->route('revisiones.index')->with('info', $mensaje);
        }
        
        if ($tipoRevision === 'Digital') {
            $ordenHistorial = $request->get('orden_historial', 'reciente');
            $datos = $this->revisionDigitalService->obtenerDatosRevisionDigital($tramiteId, $ordenHistorial);
        } elseif ($tipoRevision === 'Presencial') {
            $datos = $this->revisionPresencialService->obtenerDatosRevisionPresencial($tramiteId);
        } elseif ($tipoRevision === 'Domiciliaria') {
            $datos = $this->revisionDomiciliariaService->obtenerDatosRevisionDomiciliaria($tramiteId);
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
        return view('revisiones.tramite-historico', $datos);
    }

    /** Mostrar archivo */
    public function mostrarArchivo(int $id)
    {
        try {
            $archivo = Archivo::with(['tramite'])->find($id);
            if (!$archivo) {
                return response("Archivo con ID {$id} NO EXISTE en la base de datos", 404);
            }

            // Autorización básica: dueño del trámite o roles de revisión/administración
            $user = auth()->user();
            $tramite = $archivo->tramite;
            $userProveedorId = $user && method_exists($user, 'proveedor') && $user->proveedor ? $user->proveedor->id : null;
            $tramiteProveedorId = $tramite ? $tramite->proveedor_id : null;
            $isOwner = $userProveedorId !== null && $tramiteProveedorId !== null && $userProveedorId === $tramiteProveedorId;
            $hasPrivilegedRole = $user && $user->hasAnyRole([
                'Super Administrador','Administrador','Revisor Digital','Revisor Presencial','Revisor Domiciliario'
            ]);

            if (!($isOwner || $hasPrivilegedRole)) {
                return response('No autorizado para ver este archivo', 403);
            }
            
            // Posibles ubicaciones donde puede estar el archivo
            $posiblesRutas = [
                ltrim($archivo->ruta, '/'),
                'public/' . ltrim($archivo->ruta, '/'),
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
            
            // Servir desde almacenamiento PRIVADO de forma segura (descifrando)
            $ciphertext = Storage::get($rutaCorrecta);
            if ($ciphertext === false || $ciphertext === null) {
                return response('No se pudo leer el archivo', 404);
            }

            try {
                $plaintext = decrypt($ciphertext);
            } catch (\Throwable $e) {
                return response('No se pudo descifrar el archivo', 500);
            }

            // Determinar MIME por extensión almacenada
            $ext = strtolower(pathinfo($archivo->nombre_archivo, PATHINFO_EXTENSION));
            $mimeMap = [
                'pdf' => 'application/pdf',
                'png' => 'image/png',
                'jpg' => 'image/jpeg',
                'jpeg'=> 'image/jpeg',
                'gif' => 'image/gif',
                'webp'=> 'image/webp',
                'mp3' => 'audio/mpeg',
                'wav' => 'audio/wav',
                'ogg' => 'audio/ogg',
                'mp4' => 'video/mp4',
                'mov' => 'video/quicktime',
                'avi' => 'video/x-msvideo',
                'wmv' => 'video/x-ms-wmv',
                'flv' => 'video/x-flv',
                'webm'=> 'video/webm',
            ];
            $mimeType = $mimeMap[$ext] ?? 'application/octet-stream';

            return response($plaintext, 200, [
                'Content-Type' => $mimeType,
                'Content-Disposition' => 'inline; filename="' . addslashes($archivo->nombre_original) . '"',
                'X-Content-Type-Options' => 'nosniff'
                // X-Frame-Options removido para permitir visualización en iframes
            ]);
                
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

            // Verificar si solo se están evaluando archivos (sin cambios en secciones)
            $soloArchivos = empty($secciones) && !empty($archivos);
            
            if (!$soloArchivos) {
                // Determinar estado final del trámite solo si hay cambios en secciones
                $estadoGeneral = $this->revisionService->obtenerEstadoGeneral($tramite->id);
                
                $estadoAnterior = $tramite->status;
                
                // Actualizar estado del trámite
                $tramite->update([
                    'status' => $estadoGeneral['estado']
                ]);

                // Si el trámite cambió a Para_Correccion, notificar al usuario
                if ($estadoGeneral['estado'] === 'Para_Correccion' && $estadoAnterior !== 'Para_Correccion') {
                    $notificacionService = app(NotificacionService::class);
                    
                    // Obtener comentarios de las secciones rechazadas para incluir en la notificación
                    $comentariosRechazo = collect($estadoGeneral['secciones'])
                        ->where('estado', 'Rechazado')
                        ->pluck('comentario')
                        ->filter()
                        ->implode('. ');
                    
                    $observaciones = $comentariosRechazo ?: 'Se requieren correcciones en algunas secciones del trámite.';
                    
                    $notificacionService->notificarCorrecciones($tramite, $observaciones);
                }

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
            } else {
                // Solo se evaluaron archivos, no mostrar mensaje de éxito
                \Log::info("Solo se evaluaron archivos, no se cambió el estado del trámite");
                \Log::info("=== FIN EVALUACIÓN DE ARCHIVOS ===");

                return response()->json([
                    'success' => true,
                    'message' => null,
                    'data' => [
                        'solo_archivos' => true
                    ]
                ]);
            }

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
                return redirect()->route('revisiones.index')->with('success', $resultado['message']);
            }
            
            return redirect()->back()->with('error', $resultado['message']);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al aprobar el trámite: ' . $e->getMessage());
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
                return redirect()->route('revisiones.index')->with('success', $resultado['message']);
            }
            
            return redirect()->back()->with('error', $resultado['message']);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al rechazar el trámite: ' . $e->getMessage());
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
                return redirect()->route('revisiones.index')->with('success', $resultado['message']);
            }
            
            return redirect()->back()->with('error', $resultado['message']);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al rechazar el trámite: ' . $e->getMessage());
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
            
            // Usar el DecisionesFinalesService específico para revisión presencial
            $decisionesService = app(\App\Services\Revisiones\DecisionesFinalesService::class);
            
            // Para revisión presencial: activar proveedor, asignar PV y agendar cita domiciliaria
            $resultado = $decisionesService->aprobarRevisionPresencial($tramiteId, $comentarioGeneral);
            
            if ($resultado['success']) {
                \Log::info("Trámite {$tramiteId} aprobado exitosamente en revisión presencial", $resultado);
                return redirect()->route('revisiones.index')->with('success', $resultado['message']);
            }
            
            \Log::warning("Trámite {$tramiteId} no pudo ser aprobado en revisión presencial", $resultado);
            return redirect()->back()->with('error', $resultado['message']);
            
        } catch (\Exception $e) {
            \Log::error("Error al aprobar y asignar proveedor para trámite {$tramiteId}", [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);
            
            return redirect()->back()->with('error', 'Error al aprobar y asignar proveedor: ' . $e->getMessage());
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

    /**
     * Procesar asignación de PV y fechas de vigencia
     */
    public function procesarAsignacionPv(Request $request, int $tramiteId)
    {
        try {
            $request->validate([
                'numero_proveedor' => 'required|integer|min:1',
                'ultimo_pv_sistema' => 'nullable|string|regex:/^PV\d+$/',
                'fecha_revision' => 'required|date_format:Y-m-d'
            ]);

            $resultado = $this->revisionDigitalService->procesarAsignacionPv(
                $tramiteId,
                $request->numero_proveedor,
                $request->ultimo_pv_sistema,
                $request->fecha_revision
            );

            return response()->json($resultado);

        } catch (\Exception $e) {
            \Log::error("Error al procesar asignación de PV", [
                'tramite_id' => $tramiteId,
                'error' => $e->getMessage(),
                'request_data' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error al procesar la asignación de PV: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener estados de revisión para carga AJAX
     */
    public function obtenerEstadosRevision(int $tramiteId)
    {
        try {
            \Log::info("=== INICIANDO obtenerEstadosRevision ===");
            \Log::info("Tramite ID: " . $tramiteId);
            \Log::info("Usuario autenticado: " . (auth()->check() ? auth()->user()->name : 'No autenticado'));
            
            // Verificar autenticación
            if (!auth()->check()) {
                \Log::warning("Usuario no autenticado");
                return response()->json([
                    'success' => false,
                    'message' => 'Usuario no autenticado'
                ], 401);
            }
            
            // Verificar que el trámite existe
            $tramite = Tramite::findOrFail($tramiteId);
            \Log::info("Trámite encontrado: " . $tramite->id);
            
            // Obtener secciones evaluadas
            $seccionesEvaluadas = $this->revisionService->obtenerSeccionesEvaluadas($tramiteId);
            \Log::info("Secciones evaluadas obtenidas: " . count($seccionesEvaluadas));
            
            // Obtener información de revisiones anteriores
            $revisionesAnteriores = $this->revisionService->obtenerInformacionRevisionesAnteriores($tramiteId);
            \Log::info("Revisiones anteriores obtenidas: " . count($revisionesAnteriores));
            
            $response = [
                'success' => true,
                'seccionesEvaluadas' => $seccionesEvaluadas,
                'revisionesAnteriores' => $revisionesAnteriores
            ];
            
            \Log::info("Respuesta preparada: " . json_encode($response));
            \Log::info("=== FIN obtenerEstadosRevision ===");
            
            return response()->json($response);
            
        } catch (\Exception $e) {
            \Log::error("Error en obtenerEstadosRevision: " . $e->getMessage());
            \Log::error("Stack trace: " . $e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener estados de revisión: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Procesar revisión domiciliaria
     */
    public function procesarRevisionDomiciliaria(Request $request, int $tramiteId)
    {
        try {
            \Log::info('=== INICIO procesarRevisionDomiciliaria ===');
            \Log::info('Trámite ID: ' . $tramiteId);
            \Log::info('Request data: ' . json_encode($request->all()));
            
            $request->validate([
                'decision' => 'required|in:aprobar,rechazar',
                'observaciones' => 'required|string|max:1000',
                'checkboxes' => 'nullable|string'
            ]);

            $tramite = Tramite::findOrFail($tramiteId);
            \Log::info('Trámite encontrado - Status actual: ' . $tramite->status);
            
            // Verificar que el trámite esté en estado de revisión domiciliaria
            if ($tramite->status !== 'Revision_Domiciliaria') {
                \Log::warning('Trámite no está en estado Revision_Domiciliaria. Status actual: ' . $tramite->status);
                return redirect()->back()->with('error', 'El trámite no está en estado de revisión domiciliaria. Status actual: ' . $tramite->status);
            }

            // Buscar la revisión domiciliaria pendiente o en proceso
            $revisionDomiciliaria = RevisionTramite::where('tramite_id', $tramiteId)
                ->where('tipo_revision', 'Domiciliaria')
                ->whereIn('estado', ['Pendiente', 'En_Proceso'])
                ->first();

            \Log::info('Revisión domiciliaria encontrada: ' . ($revisionDomiciliaria ? 'Sí (ID: ' . $revisionDomiciliaria->id . ')' : 'No'));

            if (!$revisionDomiciliaria) {
                \Log::warning('No se encontró revisión domiciliaria pendiente o en proceso');
                return redirect()->back()->with('error', 'No se encontró una revisión domiciliaria pendiente o en proceso para este trámite.');
            }

            // Actualizar la revisión domiciliaria
            \Log::info('Actualizando revisión domiciliaria...');
            $revisionDomiciliaria->update([
                'estado' => 'Finalizada',
                'observaciones' => $request->observaciones,
                'fecha_fin' => now()
            ]);
            \Log::info('Revisión domiciliaria actualizada');

            // Actualizar el status del trámite según la decisión
            $nuevoStatus = $request->decision === 'aprobar' ? 'Aprobado' : 'Rechazado';
            \Log::info('Actualizando status del trámite de "' . $tramite->status . '" a "' . $nuevoStatus . '"');
            $tramite->update(['status' => $nuevoStatus]);
            \Log::info('Status del trámite actualizado a: ' . $tramite->fresh()->status);

            // Crear notificación para el solicitante
            $notificacionService = app(NotificacionService::class);
            if ($request->decision === 'aprobar') {
                $notificacionService->notificarTramiteAprobado($tramite);
            } else {
                $notificacionService->notificarTramiteRechazado($tramite, $request->observaciones);
            }

            $mensajeExito = $request->decision === 'aprobar' 
                ? 'Revisión domiciliaria aprobada exitosamente.'
                : 'Revisión domiciliaria rechazada exitosamente.';

            \Log::info('Proceso completado exitosamente. Redirigiendo al índice.');
            \Log::info('=== FIN procesarRevisionDomiciliaria ===');
            
            return redirect()->route('revisiones.index')->with('success', $mensajeExito);

        } catch (\Exception $e) {
            \Log::error('Error al procesar revisión domiciliaria: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            return redirect()->back()->with('error', 'Error al procesar la revisión domiciliaria: ' . $e->getMessage());
        }
    }
} 
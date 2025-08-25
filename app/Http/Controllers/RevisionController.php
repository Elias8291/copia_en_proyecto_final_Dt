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
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Middleware\PermissionMiddleware;

class RevisionController extends Controller
{
    private RevisionService $revisionService;
    private RevisionDigitalService $revisionDigitalService;
    private RevisionPresencialService $revisionPresencialService;
    private RevisionDomiciliariaService $revisionDomiciliariaService;
    private DecisionesFinalesService $decisionesFinalesService;

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
    }

    public function index(Request $request)
    {
        $tramites = $this->revisionService->obtenerTramitesPendientes($request);
        return view('revisiones.index', compact('tramites'));
    }

    public function seleccionarTipoRevision(int $tramiteId)
    {
        $datos = $this->revisionService->obtenerDatosSeleccionTipo($tramiteId);
        return view('revisiones.seleccionar-tipo', $datos);
    }

    public function iniciarRevision(Request $request, int $tramiteId)
    {
        $request->validate(['tipo_revision' => 'required|in:Digital,Presencial,Domiciliaria']);
        $this->revisionService->iniciarRevision($tramiteId, $request->tipo_revision);
        return redirect()->route('revisiones.revisar', ['tramite' => $tramiteId, 'tipo_revision' => $request->tipo_revision]);
    }

    public function revisarTramite(Request $request, int $tramiteId)
    {
        $tipoRevision = $request->get('tipo_revision', 'Digital');
        $request->session()->forget(['success', 'success_title', 'success_message', 'success_accept_text', 'success_redirect']);
        
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

    public function agendarCita(Request $request, int $tramiteId)
    {
        $request->validate(['fecha' => 'required|date|after:today', 'hora' => 'required', 'tipo_cita' => 'required|in:Presencial,Domiciliaria']);
        $cita = $this->revisionService->agendarCita($tramiteId, $request->all());
        return response()->json(['success' => true, 'message' => 'Cita agendada exitosamente', 'cita' => $cita]);
    }

    public function reagendarCita(Request $request, int $citaId)
    {
        $request->validate(['fecha' => 'required|date|after:today', 'hora' => 'required']);
        $cita = $this->revisionService->reagendarCita($citaId, $request->all());
        return response()->json(['success' => true, 'message' => 'Cita reagendada exitosamente', 'cita' => $cita]);
    }

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

    public function verTramiteHistorico(int $tramiteId)
    {
        $datos = $this->revisionService->obtenerDatosRevision($tramiteId, 'Historico');
        return view('revisiones.tramite-historico', $datos);
    }

    public function mostrarArchivo(int $id)
    {
        try {
            $archivo = Archivo::with(['tramite'])->find($id);
            if (!$archivo) {
                return response("Archivo con ID {$id} NO EXISTE en la base de datos", 404);
            }

            $user = Auth::user();
            $tramite = $archivo->tramite;
            $userProveedorId = $user && method_exists($user, 'proveedor') && $user->proveedor ? $user->proveedor->id : null;
            $tramiteProveedorId = $tramite ? $tramite->proveedor_id : null;
            $isOwner = $userProveedorId !== null && $tramiteProveedorId !== null && $userProveedorId === $tramiteProveedorId;
            $hasPrivilegedRole = $user && method_exists($user, 'hasAnyRole') && @$user->{'hasAnyRole'}([
                'Super Administrador','Administrador','Revisor Digital','Revisor Presencial','Revisor Domiciliario'
            ]);

            if (!($isOwner || $hasPrivilegedRole)) {
                return response('No autorizado para ver este archivo', 403);
            }
            
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
            
            $ciphertext = Storage::get($rutaCorrecta);
            if ($ciphertext === false || $ciphertext === null) {
                return response('No se pudo leer el archivo', 404);
            }

            try {
                $plaintext = decrypt($ciphertext);
            } catch (\Throwable $e) {
                return response('No se pudo descifrar el archivo', 500);
            }

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
            ]);
                
        } catch (\Exception $e) {
            return response('Error: ' . $e->getMessage(), 404);
        }
    }

    public function obtenerEstadoSeccion(Request $request, int $tramiteId)
    {
        $seccion = $request->get('seccion');
        $estado = $this->revisionService->obtenerEstadoSeccion($tramiteId, $seccion);
        
        return response()->json($estado);
    }

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

    public function limpiarSesiones(Request $request)
    {
        $request->session()->forget(['success', 'success_title', 'success_message', 'success_accept_text', 'success_redirect']);
        
        return response()->json([
            'success' => true,
            'message' => 'Sesiones limpiadas correctamente'
        ]);
    }

    public function procesarRevisionDigital(Request $request, Tramite $tramite)
    {
        try {

            $request->validate([
                'secciones' => 'required|array',
                'archivos' => 'nullable|array',
                'archivos.*.id' => 'required|integer',
                'archivos.*.status' => 'required|in:Pendiente,Aprobado,Rechazado',
                'archivos.*.comentario_revision' => 'nullable|string'
            ]);
            
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

            $archivos = $request->input('archivos', []);
            foreach ($archivos as $archivoData) {
                $archivo = Archivo::findOrFail($archivoData['id']);
                $archivo->update([
                    'status' => $archivoData['status'],
                    'comentario_revision' => $archivoData['comentario_revision'] ?? null,
                    'revisado_por' => Auth::id()
                ]);
            }

            $soloArchivos = empty($secciones) && !empty($archivos);
            
            if (!$soloArchivos) {
                $estadoGeneral = $this->revisionService->obtenerEstadoGeneral($tramite->id);
                
                $estadoAnterior = $tramite->status;
                
                $tramite->update([
                    'status' => $estadoGeneral['estado']
                ]);

                if ($estadoGeneral['estado'] === 'Para_Correccion' && $estadoAnterior !== 'Para_Correccion') {
                    $notificacionService = app(NotificacionService::class);
                    
                    $comentariosRechazo = collect($estadoGeneral['secciones'])
                        ->where('estado', 'Rechazado')
                        ->pluck('comentario')
                        ->filter()
                        ->implode('. ');
                    
                    $observaciones = $comentariosRechazo ?: 'Se requieren correcciones en algunas secciones del trámite.';
                    
                    $notificacionService->notificarCorrecciones($tramite, $observaciones);
                }



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

                return response()->json([
                    'success' => true,
                    'message' => null,
                    'data' => [
                        'solo_archivos' => true
                    ]
                ]);
            }

        } catch (\Exception $e) {
            
            return response()->json([
                'success' => false,
                'message' => 'Error al procesar la revisión digital: ' . $e->getMessage()
            ], 500);
        }
    }

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

    public function aprobar(Request $request, int $tramiteId)
    {
        try {
            $request->validate(['comentario_general' => 'nullable']);
            $comentarioGeneral = $request->input('comentario_general') ?: null;
            
            $tramite = \App\Models\Tramite::findOrFail($tramiteId);
            
            $decisionesService = app(\App\Services\Revisiones\DecisionesFinalesService::class);
            
            $resultado = $decisionesService->aprobarRevisionPresencial($tramiteId, $comentarioGeneral);
            
            if ($resultado['success']) {
                return redirect()->route('revisiones.index')->with('success', $resultado['message']);
            }
            
            return redirect()->back()->with('error', $resultado['message']);
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al aprobar y asignar proveedor: ' . $e->getMessage());
        }
    }

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
            
            if ($request->filled('comentarios_presencial')) {
                SeccionRevision::updateOrCreate(
                    [
                        'tramite_id' => $tramiteId,
                        'seccion' => 'documentos_presencial'
                    ],
                    [
                        'estado' => $request->decision_documentos,
                        'comentario' => $request->comentarios_presencial,
                        'revisado_por' => Auth::id()
                    ]
                );
            }

            if ($request->filled('observaciones')) {
                $tramite->update(['observaciones' => $request->observaciones]);
            }

            if ($request->decision_documentos === 'Aprobado') {
                return response()->json([
                    'success' => true,
                    'message' => 'Revisión presencial completada. Los documentos están conformes.',
                    'action' => 'aprobar_disponible'
                ]);
            } else {
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

    public function limpiarSesionExito()
    {
        session()->forget(['success', 'success_title', 'success_message', 'success_accept_text', 'success_redirect']);
        return response()->json(['success' => true]);
    }

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
            return response()->json([
                'success' => false,
                'message' => 'Error al procesar la asignación de PV: ' . $e->getMessage()
            ], 500);
        }
    }

    public function obtenerEstadosRevision(int $tramiteId)
    {
        try {
            if (!Auth::check()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Usuario no autenticado'
                ], 401);
            }
            
            $tramite = Tramite::findOrFail($tramiteId);
            
            $seccionesEvaluadas = $this->revisionService->obtenerSeccionesEvaluadas($tramiteId);
            
            $revisionesAnteriores = $this->revisionService->obtenerInformacionRevisionesAnteriores($tramiteId);
            
            $response = [
                'success' => true,
                'seccionesEvaluadas' => $seccionesEvaluadas,
                'revisionesAnteriores' => $revisionesAnteriores
            ];
            
            return response()->json($response);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener estados de revisión: ' . $e->getMessage()
            ], 500);
        }
    }

    public function procesarRevisionDomiciliaria(Request $request, int $tramiteId)
    {
        try {
            $request->validate([
                'decision' => 'required|in:aprobar,rechazar',
                'observaciones' => 'required|string|max:1000',
                'checkboxes' => 'nullable|string'
            ]);

            $tramite = Tramite::findOrFail($tramiteId);
            
            if ($tramite->status !== 'Revision_Domiciliaria') {
                return redirect()->back()->with('error', 'El trámite no está en estado de revisión domiciliaria. Status actual: ' . $tramite->status);
            }

            $revisionDomiciliaria = RevisionTramite::where('tramite_id', $tramiteId)
                ->where('tipo_revision', 'Domiciliaria')
                ->whereIn('estado', ['Pendiente', 'En_Proceso'])
                ->first();

            if (!$revisionDomiciliaria) {
                return redirect()->back()->with('error', 'No se encontró una revisión domiciliaria pendiente o en proceso para este trámite.');
            }

            $revisionDomiciliaria->update([
                'estado' => 'Finalizada',
                'observaciones' => $request->observaciones,
                'fecha_fin' => now()
            ]);

            $nuevoStatus = $request->decision === 'aprobar' ? 'Aprobado' : 'Rechazado';
            $tramite->update(['status' => $nuevoStatus]);

            $notificacionService = app(NotificacionService::class);
            if ($request->decision === 'aprobar') {
                $notificacionService->notificarTramiteAprobado($tramite);
            } else {
                $notificacionService->notificarTramiteRechazado($tramite, $request->observaciones);
            }

            $mensajeExito = $request->decision === 'aprobar' 
                ? 'Revisión domiciliaria aprobada exitosamente.'
                : 'Revisión domiciliaria rechazada exitosamente.';
            
            return redirect()->route('revisiones.index')->with('success', $mensajeExito);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al procesar la revisión domiciliaria: ' . $e->getMessage());
        }
    }
} 
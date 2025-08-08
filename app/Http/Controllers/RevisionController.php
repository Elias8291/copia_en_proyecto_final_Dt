<?php

namespace App\Http\Controllers;

use App\Models\Tramite;
use App\Models\Archivo;
use App\Services\RevisionService;
use App\Services\Revisiones\RevisionDigitalService;
use App\Services\Revisiones\RevisionPresencialService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RevisionController extends Controller
{
    private RevisionService $revisionService;
    private RevisionDigitalService $revisionDigitalService;
    private RevisionPresencialService $revisionPresencialService;

    public function __construct(
        RevisionService $revisionService,
        RevisionDigitalService $revisionDigitalService,
        RevisionPresencialService $revisionPresencialService
    ) {
        $this->revisionService = $revisionService;
        $this->revisionDigitalService = $revisionDigitalService;
        $this->revisionPresencialService = $revisionPresencialService;
    }

    // Listar trámites para revisión
    public function index(Request $request)
    {
        $filtros = $request->only(['search', 'status', 'tipo_tramite']);
        $tramites = $this->revisionService->obtenerTramitesParaRevision($filtros);
        
        return view('revisiones.index', compact('tramites', 'filtros'));
    }

    // Seleccionar tipo de revisión
    public function seleccionarTipoRevision(int $tramiteId)
    {
        $datos = $this->revisionService->obtenerDatosSeleccionTipo($tramiteId);
        return view('revisiones.seleccionar-tipo', $datos);
    }

    // Iniciar revisión
    public function iniciarRevision(Request $request, int $tramiteId)
    {
        $request->validate([
            'tipo_revision' => 'required|in:Digital,Presencial,Domiciliaria'
        ]);

        $this->revisionService->iniciarRevision($tramiteId, $request->tipo_revision);

        return redirect()->route('revisiones.revisar', [
            'tramite' => $tramiteId,
            'tipo_revision' => $request->tipo_revision
        ]);
    }

    // Revisar trámite
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

    // Agendar cita
    public function agendarCita(Request $request, int $tramiteId)
    {
        $request->validate([
            'fecha' => 'required|date|after:today',
            'hora' => 'required',
            'tipo_cita' => 'required|in:Presencial,Domiciliaria'
        ]);

        $cita = $this->revisionService->agendarCita($tramiteId, $request->all());
        
        return response()->json([
            'success' => true,
            'message' => 'Cita agendada exitosamente',
            'cita' => $cita
        ]);
    }

    // Reagendar cita
    public function reagendarCita(Request $request, int $citaId)
    {
        $request->validate([
            'fecha' => 'required|date|after:today',
            'hora' => 'required'
        ]);

        $cita = $this->revisionService->reagendarCita($citaId, $request->all());
        
        return response()->json([
            'success' => true,
            'message' => 'Cita reagendada exitosamente',
            'cita' => $cita
        ]);
    }

    // Obtener horarios disponibles
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

    // Ver trámite histórico
    public function verTramiteHistorico(int $tramiteId)
    {
        $datos = $this->revisionService->obtenerDatosRevision($tramiteId, 'Historico');
        return view('revisiones.historico', $datos);
    }

    // Mostrar archivo
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
        $estado = $this->revisionService->obtenerEstadoGeneral($tramiteId);
        return response()->json($estado);
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
    public function procesarRevisionDigital(Request $request, int $tramiteId)
    {
        try {
            \Log::info("=== INICIANDO PROCESAMIENTO DE REVISIÓN DIGITAL ===");
            \Log::info("Trámite ID: " . $tramiteId);
            \Log::info("Datos recibidos: " . json_encode($request->all(), JSON_PRETTY_PRINT));

            $request->validate([
                'secciones' => 'required|array',
                'archivos' => 'nullable|array',
                'archivos.*.id' => 'required|integer',
                'archivos.*.status' => 'required|in:Pendiente,Aprobado,Rechazado',
                'archivos.*.comentario_revision' => 'nullable|string'
            ]);

            $tramite = Tramite::findOrFail($tramiteId);
            
            // Procesar secciones
            $secciones = $request->input('secciones', []);
            foreach ($secciones as $seccion => $datos) {
                if (isset($datos['estado']) && isset($datos['comentario'])) {
                    $this->revisionService->evaluarSeccion($tramiteId, [
                        'seccion' => $seccion,
                        'estado' => $datos['estado'],
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
            $estadoGeneral = $this->revisionService->obtenerEstadoGeneral($tramiteId);
            
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
} 
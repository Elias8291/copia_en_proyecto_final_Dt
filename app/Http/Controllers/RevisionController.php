<?php

namespace App\Http\Controllers;

use App\Models\Tramite;
use App\Models\RevisionTramite;
use App\Models\Archivo;
use App\Enums\TramiteStatus;
use App\Services\Tramites\DataRetrievalService;
use App\Services\HistorialTramitesService;
use App\Services\Revisiones\RevisionDigitalService;
use App\Services\Revisiones\RevisionPresencialService;
use App\Services\Revisiones\RevisionDomiciliariaService;
use App\Services\Revisiones\RevisionService;
use App\Services\CitasService;
use App\ViewModels\FormDataViewModel;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class RevisionController extends Controller
{
    private DataRetrievalService $dataRetrievalService;
    private HistorialTramitesService $historialService;
    private RevisionService $revisionService;
    private RevisionDigitalService $revisionDigitalService;
    private RevisionPresencialService $revisionPresencialService;
    private RevisionDomiciliariaService $revisionDomiciliariaService;
    private CitasService $citasService;

    public function __construct(
        DataRetrievalService $dataRetrievalService,
        HistorialTramitesService $historialService,
        RevisionService $revisionService,
        RevisionDigitalService $revisionDigitalService,
        RevisionPresencialService $revisionPresencialService,
        RevisionDomiciliariaService $revisionDomiciliariaService,
        CitasService $citasService
    ) {
        $this->dataRetrievalService = $dataRetrievalService;
        $this->historialService = $historialService;
        $this->revisionService = $revisionService;
        $this->revisionDigitalService = $revisionDigitalService;
        $this->revisionPresencialService = $revisionPresencialService;
        $this->revisionDomiciliariaService = $revisionDomiciliariaService;
        $this->citasService = $citasService;
    }

    /**
     * Muestra lista de trámites para revisar
     */
    public function index(Request $request)
    {
        $query = Tramite::with(['proveedor', 'datosGenerales' => function($query) {
            $query->orderBy('created_at', 'desc')->limit(1);
        }])
        ->where('status', 'Pendiente')
        ->orderBy('created_at', 'desc');

        // Filtros
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('proveedor', function($subQ) use ($search) {
                    $subQ->where('rfc', 'like', "%{$search}%");
                })
                ->orWhereHas('datosGenerales', function($subQ) use ($search) {
                    $subQ->where('razon_social', 'like', "%{$search}%")
                         ->orWhere('curp', 'like', "%{$search}%");
                });
            });
        }

        if ($request->filled('tipo_tramite')) {
            $query->where('tipo_tramite', $request->tipo_tramite);
        }

        if ($request->filled('fecha_desde')) {
            $query->whereDate('created_at', '>=', $request->fecha_desde);
        }

        if ($request->filled('fecha_hasta')) {
            $query->whereDate('created_at', '<=', $request->fecha_hasta);
        }

        $tramites = $query->paginate($request->get('per_page', 15));

        return view('revisiones.index', compact('tramites'));
    }

    /**
     * Muestra vista de selección de tipo de revisión
     */
    public function seleccionarTipoRevision(int $tramiteId)
    {
        $tramite = Tramite::with(['proveedor', 'datosGenerales' => function($query) {
            $query->orderBy('created_at', 'desc')->limit(1);
        }])->findOrFail($tramiteId);

        // Verificar si ya existe una revisión en proceso para este trámite
        $revisionExistente = RevisionTramite::where('tramite_id', $tramiteId)
            ->where('estado', '!=', 'Finalizada')
            ->first();

        // Determinar qué tipos de revisión están disponibles según el estado del trámite
        $tiposRevisionDisponibles = $this->determinarTiposRevisionDisponibles($tramite);

        // Obtener información de cita si está en revisión presencial
        $citaInfo = null;
        if ($tramite->status === 'Revision_Presencial') {
            $citaInfo = $this->obtenerInformacionCita($tramite->id);
        }

        return view('revisiones.seleccionar-tipo', [
            'tramite' => $tramite,
            'revisionExistente' => $revisionExistente,
            'tiposRevisionDisponibles' => $tiposRevisionDisponibles,
            'citaInfo' => $citaInfo
        ]);
    }

    /**
     * Determina qué tipos de revisión están disponibles según el estado del trámite
     */
    private function determinarTiposRevisionDisponibles(Tramite $tramite): array
    {
        $disponibles = [
            'Digital' => false,
            'Presencial' => false,
            'Domiciliaria' => false
        ];

        // Si el trámite está pendiente, solo la revisión digital está disponible
        if ($tramite->status === 'Pendiente') {
            $disponibles = [
                'Digital' => true,
                'Presencial' => false,
                'Domiciliaria' => false
            ];
        }
        // Si el trámite está en algún tipo de revisión, solo esa revisión está disponible
        elseif (in_array($tramite->status, ['Revision_Digital', 'Revision_Presencial', 'Revision_Domiciliaria'])) {
            switch ($tramite->status) {
                case 'Revision_Digital':
                    $disponibles['Digital'] = true;
                    break;
                case 'Revision_Presencial':
                    $disponibles['Presencial'] = true;
                    break;
                case 'Revision_Domiciliaria':
                    $disponibles['Domiciliaria'] = true;
                    break;
            }
        }
        // Si el trámite está aprobado, rechazado, para corrección o cancelado, ninguna revisión está disponible
        else {
            // Ninguna revisión disponible
        }

        return $disponibles;
    }

    /**
     * Obtiene la información de la cita asignada para un trámite
     */
    private function obtenerInformacionCita(int $tramiteId): ?array
    {
        $cita = \App\Models\Cita::where('tramite_id', $tramiteId)
            ->where('estado', 'Asignada')
            ->with(['asignadoA'])
            ->orderBy('fecha_cita', 'desc')
            ->first();

        if (!$cita) {
            return null;
        }

        // Obtener información de quién debe presentarse (reutilizando lógica de estado.blade.php)
        $personaResponsable = $this->obtenerPersonaResponsable($cita->tramite);

        return [
            'fecha' => $cita->fecha_cita->format('d/m/Y'),
            'hora' => $cita->fecha_cita->format('H:i'),
            'fecha_completa' => $cita->fecha_cita->format('d/m/Y H:i'),
            'revisor' => $cita->asignadoA ? ($cita->asignadoA->nombre ?: 'No asignado') : 'No asignado',
            'personaResponsable' => $personaResponsable,
            'estado' => $cita->estado,
            'intento' => $cita->intento
        ];
    }

    /**
     * Obtiene la información de quién debe presentarse (reutilizando lógica de TramiteController)
     */
    private function obtenerPersonaResponsable($tramite): ?array
    {
        $proveedor = $tramite->proveedor;
        
        if (!$proveedor) {
            return null;
        }

        // Si es persona moral, obtener apoderado legal
        if ($proveedor->tipo_persona === 'Moral') {
            $apoderadoService = app(\App\Services\Tramites\ApoderadoService::class);
            $apoderado = $apoderadoService->obtener($tramite);
            
            if ($apoderado && isset($apoderado['nombre_apoderado'])) {
                return [
                    'nombre' => $apoderado['nombre_apoderado'],
                    'tipo' => 'Apoderado'
                ];
            }
        }
        
        // Si es persona física, usar el usuario que inició el trámite
        if ($proveedor->tipo_persona === 'Fisica' && $proveedor->usuario) {
            return [
                'nombre' => $proveedor->usuario->nombre,
                'tipo' => 'La persona'
            ];
        }

        return null;
    }

    /**
     * Redirige a la revisión con el tipo seleccionado (sin crear revisión en BD)
     */
    public function iniciarRevision(Request $request, int $tramiteId)
    {
        $request->validate([
            'tipo_revision' => 'required|in:Digital,Presencial,Domiciliaria'
        ]);

        // Actualizar el estado del trámite según el tipo de revisión
        $tramite = Tramite::findOrFail($tramiteId);
        $nuevoEstado = match($request->tipo_revision) {
            'Digital' => TramiteStatus::REVISION_DIGITAL->value,
            'Presencial' => TramiteStatus::REVISION_PRESENCIAL->value,
            'Domiciliaria' => TramiteStatus::REVISION_DOMICILIARIA->value,
            default => TramiteStatus::REVISION_DIGITAL->value
        };
        
        $tramite->update(['status' => $nuevoEstado]);

        // Redirigir con el tipo de revisión
        return redirect()->route('revisiones.revisar', [
            'tramite' => $tramiteId,
            'tipo_revision' => $request->tipo_revision
        ])->with('success', 'Iniciando ' . $request->tipo_revision . '...');
    }

    /**
     * Muestra formulario de revisión con datos del trámite (solo lectura)
     */
    public function revisarTramite(Request $request, int $tramiteId)
    {
        // Obtener tipo de revisión del parámetro URL
        $tipoRevision = $request->get('tipo_revision', 'Digital');
        
        // Seleccionar servicio según tipo de revisión
        $datos = match($tipoRevision) {
            'Digital' => $this->revisionDigitalService->obtenerDatosRevisionDigital($tramiteId),
            'Presencial' => $this->revisionPresencialService->obtenerDatosRevisionPresencial($tramiteId),
            'Domiciliaria' => $this->revisionDomiciliariaService->obtenerDatosRevisionDomiciliaria($tramiteId),
            default => $this->revisionDigitalService->obtenerDatosRevisionDigital($tramiteId)
        };

        // Determinar vista según tipo de revisión
        $vista = match($tipoRevision) {
            'Digital' => 'revisiones.revision-digital',
            'Presencial' => 'revisiones.revision-presencial', 
            'Domiciliaria' => 'revisiones.revision-domiciliaria',
            default => 'revisiones.revision-digital'
        };
        
        return view($vista, $datos);
    }

    /**
     * Finaliza la revisión con observaciones (crea la revisión si no existe)
     */
    public function finalizarRevision(Request $request, int $tramiteId)
    {
        $request->validate([
            'observaciones' => 'nullable|string|max:1000',
            'decision' => 'required|in:aprobado,rechazado',
            'tipo_revision' => 'nullable|string|in:Digital,Presencial,Domiciliaria'
        ]);

        $tipoRevision = $request->get('tipo_revision', 'Digital');

        // Usar el servicio para finalizar la revisión
        $this->revisionService->finalizarRevision(
            $tramiteId,
            $tipoRevision,
            $request->decision,
            $request->observaciones
        );

        return redirect()->route('revisiones.index')
            ->with('success', 'Revisión finalizada correctamente.');
    }

    /**
     * Muestra un trámite histórico (solo lectura, sin comentarios)
     */
    public function verTramiteHistorico(int $tramiteId)
    {
        $datos = $this->revisionService->obtenerDatosVistaSoloLectura($tramiteId);
        
        return view('revisiones.tramite-solo-lectura', $datos);
    }

    /**
     * Servir archivo de forma segura
     */
    public function mostrarArchivo(int $id)
    {
        $archivo = \App\Models\Archivo::findOrFail($id);
        
        // Verificar que el archivo existe físicamente
        $rutaCompleta = storage_path('app/public/' . $archivo->ruta);
        
        if (!file_exists($rutaCompleta)) {
            abort(404, 'Archivo no encontrado');
        }

        // Obtener el tipo MIME del archivo
        $mimeType = mime_content_type($rutaCompleta);
        
        return response()->file($rutaCompleta, [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'inline; filename="' . $archivo->nombre_original . '"'
        ]);
    }

    /**
     * Procesar revisión digital con evaluación por secciones
     */
    public function procesarRevisionDigital(Request $request, Tramite $tramite)
    {
        try {
            // Log de datos recibidos para debugging
            \Log::info('Iniciando procesamiento de revisión digital', [
                'tramite_id' => $tramite->id,
                'request_data' => $request->all(),
                'user_id' => Auth::id()
            ]);
            
            // Validar datos básicos
            $request->validate([
                'tipo_revision' => 'required|string',
                'decision_final' => 'nullable|string',
                'observaciones_generales' => 'nullable|string',
                'secciones' => 'nullable|array'
            ]);
            
            // Incluir el servicio de revisión principal
            $revisionService = app(\App\Services\RevisionService::class);
            
            \Log::info('Llamando al servicio de revisión', [
                'tramite_id' => $tramite->id,
                'service' => 'RevisionService'
            ]);
            
            $resultado = $revisionService->procesarRevisionDigital($tramite, $request->all());
            
            \Log::info('Resultado del procesamiento', [
                'tramite_id' => $tramite->id,
                'resultado' => $resultado
            ]);
            
            if ($resultado['success']) {
                $mensaje = $resultado['message'];
                $titulo = '¡Revisión Completada!';
                
                if ($request->decision_final === 'agendar_cita') {
                    $mensaje = 'Trámite aprobado y cita presencial agendada automáticamente';
                    $titulo = '¡Trámite Aprobado y Cita Presencial Agendada!';
                }
                
                return redirect()->route('revisiones.index')
                    ->with('success', $mensaje . ' Estado: ' . $resultado['estado_tramite'])
                    ->with('success_title', $titulo)
                    ->with('success_message', $mensaje)
                    ->with('success_redirect', route('revisiones.index'));
            } else {
                return back()->with('error', 'Error al procesar la revisión: ' . $resultado['message']);
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Error de validación en revisión digital', [
                'tramite_id' => $tramite->id,
                'errors' => $e->errors(),
                'request_data' => $request->all()
            ]);
            
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            \Log::error('Error al procesar revisión digital', [
                'tramite_id' => $tramite->id,
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);
            
            return back()->with('error', 'Error interno al procesar la revisión. Por favor, intente nuevamente.');
        }
    }

    /**
     * Procesar revisión presencial con enfoque en documentos
     */
    public function procesarRevisionPresencial(Request $request, Tramite $tramite)
    {
        try {
            $revisionService = app(\App\Services\RevisionService::class);
            
            $resultado = $revisionService->procesarRevisionPresencial($tramite, $request->all());
            
            if ($resultado['success']) {
                return redirect()->route('revisiones.index')
                    ->with('success', $resultado['message'] . ' Estado: ' . $resultado['estado_tramite']);
            } else {
                return back()->with('error', 'Error al procesar la revisión: ' . $resultado['message']);
            }
        } catch (\Exception $e) {
            \Log::error('Error al procesar revisión presencial', [
                'tramite_id' => $tramite->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()->with('error', 'Error interno al procesar la revisión. Por favor, intente nuevamente.');
        }
    }

    public function agendarCita(Request $request, int $tramiteId)
    {
        try {
            $resultado = $this->citasService->agendarCitaRevisionDigital($tramiteId);
            
            if ($resultado['success']) {
                return redirect()->route('revisiones.index')->with('success', 
                    "Cita agendada para el {$resultado['fecha_formateada']} con {$resultado['revisor']->name}"
                );
            } else {
                return back()->with('error', $resultado['message']);
            }
        } catch (\Exception $e) {
            \Log::error('Error al agendar cita', [
                'tramite_id' => $tramiteId,
                'error' => $e->getMessage()
            ]);
            
            return back()->with('error', 'Error al agendar la cita. Intente nuevamente.');
        }
    }

    public function reagendarCita(Request $request, int $citaId)
    {
        try {
            $resultado = $this->citasService->reagendarCita($citaId);
            
            if ($resultado['success']) {
                return redirect()->route('revisiones.index')->with('success', 
                    "Cita reagendada para el {$resultado['fecha_formateada']} con {$resultado['revisor']->name}"
                );
            } else {
                return back()->with('error', $resultado['message']);
            }
        } catch (\Exception $e) {
            \Log::error('Error al reagendar cita', [
                'cita_id' => $citaId,
                'error' => $e->getMessage()
            ]);
            
            return back()->with('error', 'Error al reagendar la cita. Intente nuevamente.');
        }
    }

    public function obtenerHorariosDisponibles(Request $request)
    {
        $request->validate([
            'fecha' => 'required|date|after_or_equal:today',
            'tipo_cita' => 'required|in:Digital,Presencial,Domiciliaria'
        ]);

        try {
            $fecha = \Carbon\Carbon::parse($request->fecha);
            $horarios = $this->citasService->obtenerHorariosDisponibles($fecha, $request->tipo_cita);
            
            return response()->json([
                'success' => true,
                'horarios' => $horarios,
                'fecha' => $fecha->format('d/m/Y')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener horarios disponibles'
            ], 500);
        }
    }
} 
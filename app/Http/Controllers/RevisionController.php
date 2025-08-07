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
use App\Services\RevisionService;
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
    public function __construct(
        DataRetrievalService $dataRetrievalService,
        HistorialTramitesService $historialService,
        RevisionService $revisionService,
        RevisionDigitalService $revisionDigitalService,
        RevisionPresencialService $revisionPresencialService,
        RevisionDomiciliariaService $revisionDomiciliariaService
    ) {
        $this->dataRetrievalService = $dataRetrievalService;
        $this->historialService = $historialService;
        $this->revisionService = $revisionService;
        $this->revisionDigitalService = $revisionDigitalService;
        $this->revisionPresencialService = $revisionPresencialService;
        $this->revisionDomiciliariaService = $revisionDomiciliariaService;
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
        $request->validate([
            'tipo_revision' => 'required|in:Digital,Presencial,Domiciliaria'
        ]);

        $this->revisionService->iniciarRevision($tramiteId, $request->tipo_revision);

        return redirect()->route('revisiones.revisar', [
            'tramite' => $tramiteId,
            'tipo_revision' => $request->tipo_revision
        ])->with('success', 'Iniciando ' . $request->tipo_revision . '...');
    }

    public function revisarTramite(Request $request, int $tramiteId)
    {
        $tipoRevision = $request->get('tipo_revision', 'Digital');
        
        // Limpiar sesiones de éxito anteriores para evitar que aparezcan modales automáticamente
        $request->session()->forget(['success', 'success_title', 'success_message', 'success_accept_text', 'success_redirect']);
        
        // Usar el servicio específico según el tipo de revisión
        if ($tipoRevision === 'Digital') {
            $datos = $this->revisionDigitalService->obtenerDatosRevisionDigital($tramiteId);
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

    public function finalizarRevision(Request $request, int $tramiteId)
    {
        $request->validate([
            'observaciones' => 'nullable|string|max:1000',
            'decision' => 'required|in:aprobado,rechazado',
            'tipo_revision' => 'nullable|string|in:Digital,Presencial,Domiciliaria'
        ]);

        $this->revisionService->finalizarRevision(
            $tramiteId,
            $request->get('tipo_revision', 'Digital'),
            $request->decision,
            $request->observaciones
        );

        return redirect()->route('revisiones.index')
            ->with('success', 'Revisión finalizada correctamente.');
    }

    public function verTramiteHistorico(int $tramiteId)
    {
        $datos = $this->revisionService->obtenerDatosVistaSoloLectura($tramiteId);
        return view('revisiones.tramite-solo-lectura', $datos);
    }

    public function mostrarArchivo(int $id)
    {
        $archivo = \App\Models\Archivo::findOrFail($id);
        $rutaCompleta = storage_path('app/public/' . $archivo->ruta);
        
        if (!file_exists($rutaCompleta)) {
            abort(404, 'Archivo no encontrado');
        }

        $mimeType = mime_content_type($rutaCompleta);
        
        return response()->file($rutaCompleta, [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'inline; filename="' . $archivo->nombre_original . '"'
        ]);
    }

    public function procesarRevisionDigital(Request $request, Tramite $tramite)
    {
        try {
            \Log::info('procesarRevisionDigital llamado', [
                'tramite_id' => $tramite->id,
                'request_data' => $request->all(),
                'decision_final' => $request->decision_final,
                'secciones' => $request->secciones,
                'archivos' => $request->archivos
            ]);
            
            $request->validate([
                'tipo_revision' => 'required|string',
                'decision_final' => 'nullable|string',
                'observaciones_generales' => 'nullable|string',
                'secciones' => 'nullable|array'
            ]);
            
            // Usar el servicio específico de revisión digital
            $resultado = $this->revisionDigitalService->procesarRevisionDigital($tramite, $request->all());
            
            if ($resultado['success']) {
                \Log::info('Revisión digital procesada exitosamente', [
                    'tramite_id' => $tramite->id,
                    'resultado' => $resultado,
                    'decision_final' => $request->decision_final
                ]);
                
                $mensaje = $resultado['message'];
                $titulo = '¡Revisión Digital Completada!';
                
                if ($request->decision_final === 'agendar_cita') {
                    $mensaje = 'Trámite aprobado y cita presencial agendada automáticamente';
                    $titulo = '¡Trámite Aprobado y Cita Presencial Agendada!';
                } elseif ($request->decision_final === 'correcciones') {
                    $mensaje = 'Trámite enviado para corrección';
                    $titulo = '¡Trámite Enviado para Corrección!';
                } elseif ($request->decision_final === 'rechazado') {
                    $mensaje = 'Trámite rechazado';
                    $titulo = '¡Trámite Rechazado!';
                }
                
                return redirect()->route('revisiones.index')
                    ->with('success', $mensaje . ' Estado: ' . $resultado['estado_tramite'])
                    ->with('success_title', $titulo)
                    ->with('success_message', $mensaje)
                    ->with('success_redirect', route('revisiones.index'));
            } else {
                \Log::error('Error en resultado de revisión digital', [
                    'tramite_id' => $tramite->id,
                    'resultado' => $resultado
                ]);
                return back()->with('error', 'Error al procesar la revisión digital: ' . $resultado['message']);
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            \Log::error('Error al procesar revisión digital', [
                'tramite_id' => $tramite->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()->with('error', 'Error interno al procesar la revisión digital. Por favor, intente nuevamente.');
        }
    }

    public function procesarRevisionPresencial(Request $request, Tramite $tramite)
    {
        try {
            // Usar el servicio específico de revisión presencial
            $resultado = $this->revisionPresencialService->procesarRevisionPresencial($tramite, $request->all());
            
            if ($resultado['success']) {
                return redirect()->route('revisiones.index')
                    ->with('success', $resultado['message'] . ' Estado: ' . $resultado['estado_tramite']);
            } else {
                return back()->with('error', 'Error al procesar la revisión presencial: ' . $resultado['message']);
            }
        } catch (\Exception $e) {
            \Log::error('Error al procesar revisión presencial', [
                'tramite_id' => $tramite->id,
                'error' => $e->getMessage()
            ]);
            
            return back()->with('error', 'Error interno al procesar la revisión presencial. Por favor, intente nuevamente.');
        }
    }

    /**
     * Limpiar sesiones de éxito (para evitar modales automáticos)
     */
    public function limpiarSesiones(Request $request)
    {
        $request->session()->forget(['success', 'success_title', 'success_message', 'success_accept_text', 'success_redirect']);
        
        return response()->json(['success' => true, 'message' => 'Sesiones limpiadas']);
    }

    /**
     * Obtener estados de revisión para AJAX
     */
    public function obtenerEstadosRevision(int $tramiteId)
    {
        try {
            // Verificar que el trámite existe
            $tramite = Tramite::findOrFail($tramiteId);
            
            // Obtener datos usando el servicio específico
            $seccionesEvaluadas = $this->revisionDigitalService->cargarSeccionesEvaluadas($tramiteId);
            $revisionesAnteriores = $this->revisionDigitalService->obtenerInformacionRevisionesAnteriores($tramiteId);
            
            return response()->json([
                'success' => true,
                'seccionesEvaluadas' => $seccionesEvaluadas,
                'revisionesAnteriores' => $revisionesAnteriores,
                'tramite_id' => $tramiteId
            ]);
            
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Trámite no encontrado'
            ], 404);
        } catch (\Exception $e) {
            \Log::error('Error al obtener estados de revisión', [
                'tramite_id' => $tramiteId,
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Error interno al obtener los estados de revisión'
            ], 500);
        }
    }

} 
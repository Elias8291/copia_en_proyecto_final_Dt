<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProcesarConstanciaRequest;
use App\Http\Requests\TramiteFormRequest;
use App\Http\Requests\TramiteCorreccionRequest;
use App\Services\Tramites\TramiteService;
use App\Services\Tramites\ConstanciaService;
use App\Services\Tramites\CorreccionService;
use App\Services\RfcProveedorService;
use App\Services\Tramites\TramiteViewDataService;
use App\ViewModels\TramiteViewModel;
use App\ViewModels\FormDataViewModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class TramiteController extends Controller
{
    private TramiteService $tramiteService;
    private ConstanciaService $constanciaService;
    private CorreccionService $correccionService;
    private RfcProveedorService $rfcProveedorService;
    private TramiteViewDataService $tramiteViewDataService;

    public function __construct(
        TramiteService $tramiteService, 
        ConstanciaService $constanciaService,
        CorreccionService $correccionService,
        RfcProveedorService $rfcProveedorService,
        TramiteViewDataService $tramiteViewDataService
    ) {
        $this->tramiteService = $tramiteService;
        $this->constanciaService = $constanciaService;
        $this->correccionService = $correccionService;
        $this->rfcProveedorService = $rfcProveedorService;
        $this->tramiteViewDataService = $tramiteViewDataService;
    }

    public function index()
    {
        $rfc = $this->rfcProveedorService->obtenerRfcUsuario();
        $historialTramites = collect();
        
        if (!$rfc) {
            $tramites = [
                'inscripcion' => ['activo' => true, 'pendiente' => false, 'motivo' => 'Usuario sin RFC'],
                'renovacion' => ['activo' => false, 'pendiente' => false, 'motivo' => 'Usuario sin RFC'],
                'actualizacion' => ['activo' => false, 'pendiente' => false, 'motivo' => 'Usuario sin RFC']
            ];
        } else {
            $historialTramites = $this->obtenerHistorialTramitesUsuario($rfc);
            $tramitePendiente = $this->rfcProveedorService->obtenerTramitePendiente($rfc);
            
            if ($tramitePendiente) {
                $tipoTramitePendiente = strtolower($tramitePendiente->tipo_tramite);
                
                $tramites = [
                    'inscripcion' => [
                        'activo' => false,
                        'pendiente' => ($tipoTramitePendiente === 'inscripcion'),
                        'motivo' => ($tipoTramitePendiente === 'inscripcion') ? 'Tiene un trámite de inscripción pendiente' : 'Tiene un trámite pendiente de otro tipo',
                        'accion' => ($tipoTramitePendiente === 'inscripcion') ? 'tramite_pendiente' : 'no_disponible',
                        'tramite_id' => ($tipoTramitePendiente === 'inscripcion') ? $tramitePendiente->id : null
                    ],
                    'renovacion' => [
                        'activo' => false,
                        'pendiente' => ($tipoTramitePendiente === 'renovacion'),
                        'motivo' => ($tipoTramitePendiente === 'renovacion') ? 'Tiene un trámite de renovación pendiente' : 'Tiene un trámite pendiente de otro tipo',
                        'accion' => ($tipoTramitePendiente === 'renovacion') ? 'tramite_pendiente' : 'no_disponible',
                        'tramite_id' => ($tipoTramitePendiente === 'renovacion') ? $tramitePendiente->id : null
                    ],
                    'actualizacion' => [
                        'activo' => false,
                        'pendiente' => ($tipoTramitePendiente === 'actualizacion'),
                        'motivo' => ($tipoTramitePendiente === 'actualizacion') ? 'Tiene un trámite de actualización pendiente' : 'Tiene un trámite pendiente de otro tipo',
                        'accion' => ($tipoTramitePendiente === 'actualizacion') ? 'tramite_pendiente' : 'no_disponible',
                        'tramite_id' => ($tipoTramitePendiente === 'actualizacion') ? $tramitePendiente->id : null
                    ]
                ];
            } else {
                $accionInscripcion = $this->rfcProveedorService->determinarAccionPorTipoTramite($rfc, 'inscripcion');
                $accionRenovacion = $this->rfcProveedorService->determinarAccionPorTipoTramite($rfc, 'renovacion');
                $accionActualizacion = $this->rfcProveedorService->determinarAccionPorTipoTramite($rfc, 'actualizacion');
                
                $tramites = [
                    'inscripcion' => [
                        'activo' => in_array($accionInscripcion['accion'], ['crear_nuevo']),
                        'pendiente' => $accionInscripcion['accion'] === 'tramite_pendiente',
                        'motivo' => $accionInscripcion['motivo'],
                        'accion' => $accionInscripcion['accion']
                    ],
                    'renovacion' => [
                        'activo' => in_array($accionRenovacion['accion'], ['renovar_vencido']),
                        'pendiente' => $accionRenovacion['accion'] === 'tramite_pendiente',
                        'motivo' => $accionRenovacion['motivo'],
                        'accion' => $accionRenovacion['accion']
                    ],
                    'actualizacion' => [
                        'activo' => $accionActualizacion['accion'] === 'actualizar_existente',
                        'pendiente' => $accionActualizacion['accion'] === 'tramite_pendiente',
                        'motivo' => $accionActualizacion['motivo'],
                        'accion' => $accionActualizacion['accion']
                    ]
                ];
            }
        }
        

        
        return view('tramites.index', compact('tramites', 'historialTramites'));
    }

    private function obtenerHistorialTramitesUsuario(string $rfc)
    {
        $tramites = \App\Models\Tramite::whereHas('proveedor', function($query) use ($rfc) {
            $query->where('rfc', $rfc);
        })
        ->with(['datosGenerales' => function($query) {
            $query->orderBy('created_at', 'desc')->limit(1);
        }, 'proveedor', 'oficios'])
        ->orderBy('created_at', 'desc')
        ->get();
        

        
        return $tramites->map(function($tramite) {
            $datosGenerales = $tramite->datosGenerales->first();
            $oficio = $tramite->oficios->first();
            
            return [
                'id' => $tramite->id,
                'tipo_tramite' => $tramite->tipo_tramite,
                'status' => $tramite->status,
                'razon_social' => $datosGenerales ? $datosGenerales->razon_social : 'Sin datos',
                'observaciones' => $tramite->observaciones ?? null,
                'created_at' => $tramite->created_at,
                'updated_at' => $tramite->updated_at,
                'oficio' => $oficio ? [
                    'id' => $oficio->id,
                    'numero_oficio' => $oficio->numero_oficio,
                    'fecha_oficio' => $oficio->fecha_oficio,
                    'url' => $oficio->url,
                    'estado' => $oficio->estado
                ] : null
            ];
        });
    }



    public function cargarConstancia($tipo = null)
    {
        if (!$tipo) {
            return redirect()->route('tramites.index')
                ->with('error', 'Debe seleccionar un tipo de trámite');
        }
        
        $tiposValidos = ['inscripcion', 'renovacion', 'actualizacion'];
        if (!in_array($tipo, $tiposValidos)) {
            return redirect()->route('tramites.index')
                ->with('error', 'Tipo de trámite no válido');
        }
        
        $rfc = $this->rfcProveedorService->obtenerRfcUsuario();
        
        if ($rfc) {
            $tramitePendiente = $this->rfcProveedorService->obtenerTramitePendiente($rfc);
            
            if ($tramitePendiente) {
                return redirect()->route('tramites.index')
                    ->with('warning', 'Tiene un trámite en proceso. Consulte el estado de su trámite actual antes de iniciar uno nuevo.');
            }
        }
        
        session(['tipo_tramite_seleccionado' => $tipo]);
        
        return view('tramites.cargar_constancia', compact('tipo'));
    }

    public function procesarConstancia(ProcesarConstanciaRequest $request)
    {
        try {
            $datosConstancia = $this->constanciaService->procesar($request);
            
            if (!$this->constanciaService->validarRfcUsuario($request->sat_rfc)) {
                return back()->withErrors([
                    'sat_rfc' => 'La constancia que intentó cargar no le pertenece. Solo puede cargar constancias de su propia persona o empresa.'
                ]);
            }
            
            session(['datos_constancia' => $datosConstancia]);

            return redirect()->route('tramites.create')
                ->with('success', 'Constancia cargada y datos extraídos exitosamente. Puede continuar con el trámite.');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Error al procesar el archivo. Por favor, inténtelo de nuevo.']);
        }
    }



    public function create()
    {
        $tipo = session('tipo_tramite_seleccionado');
        
        if (!$tipo) {
            return redirect()->route('tramites.index')
                ->with('error', 'Debe seleccionar un tipo de trámite');
        }
        
        $tiposValidos = ['inscripcion', 'renovacion', 'actualizacion'];
        if (!in_array($tipo, $tiposValidos)) {
            return redirect()->route('tramites.index')
                ->with('error', 'Tipo de trámite no válido');
        }
        
        $rfc = $this->rfcProveedorService->obtenerRfcUsuario();
        
        if (!$rfc) {
            return redirect()->route('tramites.index')
                ->with('error', 'Usuario sin RFC configurado');
        }
        
        $tramitePendiente = $this->rfcProveedorService->obtenerTramitePendiente($rfc);
        
        if ($tramitePendiente) {
            return redirect()->route('tramites.index')
                ->with('warning', 'Tiene un trámite en proceso. Consulte el estado de su trámite actual antes de iniciar uno nuevo.');
        }
        
        $accion = $this->rfcProveedorService->determinarAccionPorTipoTramite($rfc, $tipo);
        
        $tramites = [
            'inscripcion' => ['activo' => in_array($accion['accion'], ['crear_nuevo'])],
            'renovacion' => ['activo' => in_array($accion['accion'], ['renovar_vencido'])],
            'actualizacion' => ['activo' => $accion['accion'] === 'actualizar_existente']
        ];
        
        if (!$tramites[$tipo]['activo']) {
            return redirect()->route('tramites.index')
                ->with('error', 'No puede realizar este tipo de trámite: ' . $accion['motivo']);
        }
        
        $viewModel = null;
        if (session()->has('datos_constancia')) {
            $datosConstancia = session('datos_constancia');
            $viewModel = new TramiteViewModel($datosConstancia);
        }
        
        $tipoPersona = $this->rfcProveedorService->determinarTipoPersona($rfc);
        $archivosRequeridos = $this->rfcProveedorService->obtenerArchivosPorTipoPersona($rfc);
        $infoProveedor = $this->rfcProveedorService->obtenerInfoGestionProveedor($rfc, $tipo);
        session(['tipo_tramite' => ucfirst($tipo)]);
        
        return view('tramites.create', compact('tipo', 'tipoPersona', 'viewModel', 'archivosRequeridos', 'infoProveedor'));
    }

    public function store(TramiteFormRequest $request)
    {
        $startTime = microtime(true);
        
        try {
            $rfc = $this->rfcProveedorService->obtenerRfcUsuario();
            if ($rfc) {
                $tramitePendiente = $this->rfcProveedorService->obtenerTramitePendiente($rfc);
                
                if ($tramitePendiente) {
                    return redirect()->route('tramites.index')
                        ->with('warning', 'Tiene un trámite en proceso. Consulte el estado de su trámite actual antes de iniciar uno nuevo.');
                }
            }
            
            $tipoTramite = session('tipo_tramite') ?? $request->tipo_tramite ?? 'Inscripcion';
            $request->merge(['tipo_tramite' => $tipoTramite]);
            
            $datosRequeridos = ['rfc', 'tipo_persona', 'razon_social', 'telefono'];
            $datosFaltantes = [];
            
            foreach ($datosRequeridos as $campo) {
                $valor = $request->input($campo) ?: $request->input($campo . '_hidden') ?: $request->input($campo . '_fallback');
                if (empty($valor)) {
                    $datosFaltantes[] = $campo;
                }
            }
            
            if (!empty($datosFaltantes)) {
                return back()
                    ->withInput()
                    ->withErrors(['error' => 'Faltan datos requeridos: ' . implode(', ', $datosFaltantes)]);
            }
            
            $tramite = $this->tramiteService->crearTramiteCompleto($request);
            session()->forget(['datos_constancia', 'tipo_tramite', 'tipo_tramite_seleccionado']);
            
            return redirect()->route('tramites.index')
                ->with('success', "Trámite creado exitosamente.")
                ->with('tramite_creado', true);
                
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->withErrors(['error' => 'Error al crear el trámite: ' . $e->getMessage()]);
        }
    }



    public function estado()
    {
        $rfc = $this->rfcProveedorService->obtenerRfcUsuario();
        
        if (!$rfc) {
            return redirect()->route('tramites.index')
                ->with('error', 'Usuario sin RFC configurado');
        }
        
        $proveedores = $this->rfcProveedorService->buscarProveedoresPorRfc($rfc);
        $tramitePendiente = $this->rfcProveedorService->obtenerTramitePendiente($rfc);
        
        $citaAsignada = null;
        $personaResponsable = null;
        $citaVencida = false;
        $intentosRestantes = 2;
        
        if ($tramitePendiente) {
            $tramitePendiente->load(['citas.asignadoA', 'revisorDigital', 'revisiones.revisor']);
            
            $citaAsignada = $tramitePendiente->citas()
                ->where('estado', 'Asignada')
                ->orderBy('fecha_cita', 'desc')
                ->first();
            
            if ($citaAsignada && $citaAsignada->fecha_cita < now()) {
                $citaVencida = true;
                
                $intentosPrevios = $tramitePendiente->citas()
                    ->where('estado', 'No Asistió')
                    ->count();
                
                $intentosRestantes = max(0, 2 - $intentosPrevios);
            }
            
            if ($tramitePendiente->proveedor) {
                if ($tramitePendiente->proveedor->tipo_persona === 'Moral') {
                    $apoderadoService = app(\App\Services\Tramites\ApoderadoService::class);
                    $datosApoderado = $apoderadoService->obtener($tramitePendiente);
                    
                    if ($datosApoderado) {
                        $personaResponsable = [
                            'nombre' => $datosApoderado['nombre_apoderado'] ?? 'No especificado',
                            'tipo' => 'Representante Legal',
                            'rfc' => $datosApoderado['rfc'] ?? ''
                        ];
                    }
                } else {
                    if ($tramitePendiente->proveedor->usuario) {
                        $personaResponsable = [
                            'nombre' => $tramitePendiente->proveedor->usuario->nombre ?? 'No especificado',
                            'tipo' => 'Titular del Trámite',
                            'rfc' => $tramitePendiente->proveedor->usuario->rfc ?? ''
                        ];
                    }
                }
            }
        }
        
        $revisorDomiciliario = null;
        if ($tramitePendiente && $tramitePendiente->status === 'Revision_Domiciliaria') {
            $revisionDomiciliaria = $tramitePendiente->revisiones()
                ->where('tipo_revision', 'Domiciliaria')
                ->where('estado', 'Pendiente')
                ->with('revisor')
                ->first();
            
            if ($revisionDomiciliaria && $revisionDomiciliaria->revisor) {
                $revisorDomiciliario = $revisionDomiciliaria->revisor;
            }
        }
        
        return view('tramites.estado', compact('proveedores', 'tramitePendiente', 'citaAsignada', 'rfc', 'personaResponsable', 'citaVencida', 'intentosRestantes', 'revisorDomiciliario'));
    }

    /**
     * Show the form for editing the specified trámite.
     */
    public function edit($tramiteId)
    {
        try {
            $tramite = \App\Models\Tramite::findOrFail($tramiteId);
            $data = $this->tramiteViewDataService->prepareEditData($tramite);

            return view('tramites.create', $data);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('tramites.index')
                ->with('error', 'El trámite especificado no existe');
        } catch (\RuntimeException $e) {
            return redirect()->route('tramites.estado')
                ->with('error', $e->getMessage());
        } catch (\Exception $e) {
            return redirect()->route('tramites.index')
                ->with('error', 'Error al cargar el formulario de edición.');
        }
    }

    protected function prepararArchivosParaCotejo($archivos): array
    {
        return $archivos->map(function($archivo) {
            return [
                'id' => $archivo->id,
                'nombre_original' => $archivo->nombre_original,
                'nombre_catalogo' => $archivo->catalogoArchivo ? $archivo->catalogoArchivo->nombre : null,
                'extension' => $archivo->extension,
                'tamaño' => $archivo->tamaño,
                'status' => $archivo->status,
                'comentario_revision' => $archivo->comentario_revision,
                'fecha_revision' => $archivo->fecha_revision,
                'revisor' => $archivo->revisor ? $archivo->revisor->name : null,
                'catalogo_archivo_id' => $archivo->catalogo_archivo_id
            ];
        })->toArray();
    }

    public function update(TramiteCorreccionRequest $request, $tramiteId)
    {
        try {
            $tramite = \App\Models\Tramite::findOrFail($tramiteId);
            
            $rfc = $this->rfcProveedorService->obtenerRfcUsuario();
            if (!$rfc || $tramite->proveedor->rfc !== $rfc) {
                return redirect()->route('tramites.index')
                    ->with('error', 'No tiene permisos para editar este trámite');
            }
            
            if (!in_array($tramite->status, ['Para_Correccion', 'Rechazado'])) {
                return redirect()->route('tramites.estado')
                    ->with('error', 'Este trámite no requiere correcciones');
            }
            
            Log::info('TramiteController: Request de corrección recibido', [
                'tramite_id' => $tramite->id,
                'has_files' => $request->hasFile('archivos'),
                'has_documentos' => $request->hasFile('documentos'),
                'archivos_keys' => $request->hasFile('archivos') ? array_keys($request->file('archivos')) : [],
                'documentos_keys' => $request->hasFile('documentos') ? array_keys($request->file('documentos')) : [],
                'all_files' => array_keys($request->allFiles())
            ]);

            $tramiteActualizado = $this->correccionService->procesarCorreccion($tramite, $request);
            
            Log::info('TramiteController: Trámite actualizado exitosamente', [
                'tramite_id' => $tramite->id,
                'user_id' => Auth::id()
            ]);
            
            return redirect()->route('tramites.estado')
                ->with('success', 'Trámite corregido exitosamente. Será revisado nuevamente.');
                
        } catch (\Exception $e) {
            Log::error('TramiteController: Error al actualizar trámite', [
                'tramite_id' => $tramiteId,
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()
                ->withInput()
                ->withErrors(['error' => 'Error al corregir el trámite: ' . $e->getMessage()]);
        }
    }

    public function descargarOficio($tramiteId)
    {
        try {
            $tramite = \App\Models\Tramite::findOrFail($tramiteId);
            
            if ($tramite->status !== 'Aprobado') {
                return redirect()->back()->with('error', 'Solo se puede descargar el oficio de trámites aprobados.');
            }
            
            /** @var \App\Models\User $usuario */
            $usuario = Auth::user();
            if ($usuario->id !== $tramite->proveedor->usuario_id && !$usuario->hasRole(['administrador', 'revisor'])) {
                return redirect()->back()->with('error', 'No tienes permisos para descargar este oficio.');
            }
            
            $rutaOficio = storage_path("app/oficios/tramite_{$tramiteId}_oficio.pdf");
            
            if (!file_exists($rutaOficio)) {
                return redirect()->back()->with('error', 'El oficio no se encuentra disponible. Contacte al administrador.');
            }
            
            $nombreArchivo = "Oficio_Aprobacion_Tramite_{$tramiteId}.pdf";
            
            return response()->download($rutaOficio, $nombreArchivo, [
                'Content-Type' => 'application/pdf',
            ]);
            
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->back()->with('error', 'El trámite especificado no existe.');
        } catch (\Exception $e) {
            Log::error('Error al descargar oficio: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al descargar el oficio: ' . $e->getMessage());
        }
    }
} 
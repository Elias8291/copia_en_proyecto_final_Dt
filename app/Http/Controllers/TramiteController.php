<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProcesarConstanciaRequest;
use App\Http\Requests\TramiteFormRequest;
use App\Services\Tramites\TramiteService;
use App\Services\Tramites\ConstanciaService;
use App\Services\RfcProveedorService;
use App\ViewModels\TramiteViewModel;
use App\ViewModels\FormDataViewModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TramiteController extends Controller
{
    private TramiteService $tramiteService;
    private ConstanciaService $constanciaService;
    private RfcProveedorService $rfcProveedorService;

    public function __construct(
        TramiteService $tramiteService, 
        ConstanciaService $constanciaService, 
        RfcProveedorService $rfcProveedorService
    ) {
        $this->tramiteService = $tramiteService;
        $this->constanciaService = $constanciaService;
        $this->rfcProveedorService = $rfcProveedorService;
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
            // Obtener historial de trámites del usuario
            $historialTramites = $this->obtenerHistorialTramitesUsuario($rfc);
            // Verificar si tiene trámite pendiente
            $tramitePendiente = $this->rfcProveedorService->obtenerTramitePendiente($rfc);
            
            if ($tramitePendiente) {
                // Si tiene trámite pendiente, identificar el tipo específico
                $tipoTramitePendiente = strtolower($tramitePendiente->tipo_tramite);
                
                // Configurar solo la tarjeta del tipo de trámite pendiente
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
                // Usar la nueva lógica para cada tipo de trámite
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
        
        \Log::info("Pasando datos a la vista index", [
            'rfc' => $rfc,
            'historial_tramites_count' => $historialTramites->count(),
            'tramites_config' => $tramites
        ]);
        
        return view('tramites.index', compact('tramites', 'historialTramites'));
    }

    /**
     * Obtener historial de trámites del usuario
     */
    private function obtenerHistorialTramitesUsuario(string $rfc)
    {
        // Buscar todos los trámites que tengan proveedores con el RFC del usuario
        $tramites = \App\Models\Tramite::whereHas('proveedor', function($query) use ($rfc) {
            $query->where('rfc', $rfc);
        })
        ->with(['datosGenerales' => function($query) {
            $query->orderBy('created_at', 'desc')->limit(1);
        }, 'proveedor', 'oficios'])
        ->orderBy('created_at', 'desc')
        ->get();
        
        \Log::info("Obteniendo historial de trámites para RFC: {$rfc}", [
            'total_tramites_encontrados' => $tramites->count(),
            'tramites' => $tramites->map(function($t) {
                return [
                    'id' => $t->id,
                    'tipo_tramite' => $t->tipo_tramite,
                    'status' => $t->status,
                    'proveedor_id' => $t->proveedor_id,
                    'proveedor_rfc' => $t->proveedor ? $t->proveedor->rfc : null
                ];
            })->toArray()
        ]);
        
        return $tramites->map(function($tramite) {
            $datosGenerales = $tramite->datosGenerales->first();
            $oficios = $tramite->oficios; // Obtener todos los oficios asociados
            
            return [
                'id' => $tramite->id,
                'tipo_tramite' => $tramite->tipo_tramite,
                'status' => $tramite->status,
                'razon_social' => $datosGenerales ? $datosGenerales->razon_social : 'Sin datos',
                'observaciones' => $tramite->observaciones ?? null,
                'created_at' => $tramite->created_at,
                'updated_at' => $tramite->updated_at,
                'oficios' => $oficios->map(function($oficio) {
                    return [
                        'id' => $oficio->id,
                        'numero_oficio' => $oficio->numero_oficio,
                        'fecha_oficio' => $oficio->fecha_oficio,
                        'url' => $oficio->url,
                        'estado' => $oficio->estado
                    ];
                })->toArray()
            ];
        });
    }



    public function cargarConstancia($tipo = null)
    {
        // Si no se proporciona tipo, redirigir al índice
        if (!$tipo) {
            return redirect()->route('tramites.index')
                ->with('error', 'Debe seleccionar un tipo de trámite');
        }
        
        // Validar que el tipo sea válido
        $tiposValidos = ['inscripcion', 'renovacion', 'actualizacion'];
        if (!in_array($tipo, $tiposValidos)) {
            return redirect()->route('tramites.index')
                ->with('error', 'Tipo de trámite no válido');
        }
        
        // Obtener RFC del usuario
        $rfc = $this->rfcProveedorService->obtenerRfcUsuario();
        
        if ($rfc) {
            // Verificar si tiene trámite pendiente
            $tramitePendiente = $this->rfcProveedorService->obtenerTramitePendiente($rfc);
            
            if ($tramitePendiente) {
                return redirect()->route('tramites.index')
                    ->with('warning', 'Tiene un trámite en proceso. Consulte el estado de su trámite actual antes de iniciar uno nuevo.');
            }
        }
        
        // Guardar el tipo de trámite en la sesión
        session(['tipo_tramite_seleccionado' => $tipo]);
        
        Log::info('TramiteController: Cargando constancia', [
            'tipo' => $tipo,
            'user_id' => auth()->id()
        ]);
        
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
            
            // Guardar los datos de la constancia en la sesión correctamente
            session(['datos_constancia' => $datosConstancia]);
            
            Log::info('TramiteController: Constancia procesada exitosamente', [
                'rfc' => $request->sat_rfc,
                'tipo_tramite' => session('tipo_tramite_seleccionado')
            ]);

            return redirect()->route('tramites.create')
                ->with('success', 'Constancia cargada y datos extraídos exitosamente. Puede continuar con el trámite.');

        } catch (\Exception $e) {
            Log::error('TramiteController: Error al procesar constancia', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()->withErrors(['error' => 'Error al procesar el archivo. Por favor, inténtelo de nuevo.']);
        }
    }



    public function create()
    {
        Log::info('TramiteController: Creando trámite', [
            'user_id' => auth()->id()
        ]);
        
        // Obtener el tipo de trámite de la sesión
        $tipo = session('tipo_tramite_seleccionado');
        
        if (!$tipo) {
            return redirect()->route('tramites.index')
                ->with('error', 'Debe seleccionar un tipo de trámite');
        }
        
        // Validar que el tipo sea válido
        $tiposValidos = ['inscripcion', 'renovacion', 'actualizacion'];
        if (!in_array($tipo, $tiposValidos)) {
            return redirect()->route('tramites.index')
                ->with('error', 'Tipo de trámite no válido');
        }
        
        // Obtener RFC del usuario
        $rfc = $this->rfcProveedorService->obtenerRfcUsuario();
        
        if (!$rfc) {
            return redirect()->route('tramites.index')
                ->with('error', 'Usuario sin RFC configurado');
        }
        
        // Verificar si tiene trámite pendiente
        $tramitePendiente = $this->rfcProveedorService->obtenerTramitePendiente($rfc);
        
        if ($tramitePendiente) {
            return redirect()->route('tramites.index')
                ->with('warning', 'Tiene un trámite en proceso. Consulte el estado de su trámite actual antes de iniciar uno nuevo.');
        }
        
        // Verificar si puede realizar este tipo de trámite
        $accion = $this->rfcProveedorService->determinarAccionPorTipoTramite($rfc, $tipo);
        
        // Verificar si el trámite está disponible
        $tramites = [
            'inscripcion' => ['activo' => in_array($accion['accion'], ['crear_nuevo'])],
            'renovacion' => ['activo' => in_array($accion['accion'], ['renovar_vencido'])],
            'actualizacion' => ['activo' => $accion['accion'] === 'actualizar_existente']
        ];
        
        if (!$tramites[$tipo]['activo']) {
            return redirect()->route('tramites.index')
                ->with('error', 'No puede realizar este tipo de trámite: ' . $accion['motivo']);
        }
        
        // Obtener datos de la constancia si existen
        $viewModel = null;
        if (session()->has('datos_constancia')) {
            $datosConstancia = session('datos_constancia');
            $viewModel = new TramiteViewModel($datosConstancia);
            
            Log::info('TramiteController: Datos de constancia cargados', [
                'rfc' => $datosConstancia['rfc'] ?? 'N/A',
                'nombre' => $datosConstancia['nombre'] ?? 'N/A'
            ]);
        } else {
            Log::info('TramiteController: No hay datos de constancia en sesión');
        }
        
        // Determinar tipo de persona basado en RFC
        $tipoPersona = $this->rfcProveedorService->determinarTipoPersona($rfc);
        
        // Obtener archivos requeridos según el tipo de persona
        $archivosRequeridos = $this->rfcProveedorService->obtenerArchivosPorTipoPersona($rfc);
        
        Log::info('TramiteController: Datos para crear trámite', [
            'tipo' => $tipo,
            'tipo_persona' => $tipoPersona,
            'rfc' => $rfc,
            'accion' => $accion['accion']
        ]);
        
        // Obtener información de gestión del proveedor para mostrar al usuario
        $infoProveedor = $this->rfcProveedorService->obtenerInfoGestionProveedor($rfc, $tipo);
        
        // Guardar el tipo de trámite en la sesión para el store
        session(['tipo_tramite' => ucfirst($tipo)]);
        
        return view('tramites.create', compact('tipo', 'tipoPersona', 'viewModel', 'archivosRequeridos', 'infoProveedor'));
    }

    public function store(TramiteFormRequest $request)
    {
        Log::info('TramiteController: Iniciando creación de trámite', [
            'user_id' => auth()->id(),
            'request_data' => $request->all(),
            'files' => $request->allFiles(),
            'session_data' => session()->all()
        ]);
        
        try {
            // Verificar si tiene trámite pendiente
            $rfc = $this->rfcProveedorService->obtenerRfcUsuario();
            if ($rfc) {
                $tramitePendiente = $this->rfcProveedorService->obtenerTramitePendiente($rfc);
                
                if ($tramitePendiente) {
                    return redirect()->route('tramites.index')
                        ->with('warning', 'Tiene un trámite en proceso. Consulte el estado de su trámite actual antes de iniciar uno nuevo.');
                }
            }
            
            // Obtener el tipo de trámite de la sesión o del request
            $tipoTramite = session('tipo_tramite') ?? $request->tipo_tramite ?? 'Inscripcion';
            
            Log::info('TramiteController: Tipo de trámite para crear', [
                'tipo_tramite' => $tipoTramite,
                'session_tipo_tramite' => session('tipo_tramite'),
                'request_tipo_tramite' => $request->tipo_tramite
            ]);
            
            // Agregar el tipo de trámite al request
            $request->merge(['tipo_tramite' => $tipoTramite]);
            
            // Verificar que los datos necesarios estén presentes
            $datosRequeridos = ['rfc', 'tipo_persona', 'razon_social', 'telefono'];
            $datosFaltantes = [];
            
            foreach ($datosRequeridos as $campo) {
                $valor = $request->input($campo) ?: $request->input($campo . '_hidden') ?: $request->input($campo . '_fallback');
                if (empty($valor)) {
                    $datosFaltantes[] = $campo;
                }
            }
            
            if (!empty($datosFaltantes)) {
                Log::error('TramiteController: Datos requeridos faltantes', [
                    'datos_faltantes' => $datosFaltantes,
                    'request_data' => $request->all()
                ]);
                
                return back()
                    ->withInput()
                    ->withErrors(['error' => 'Faltan datos requeridos: ' . implode(', ', $datosFaltantes)]);
            }
            
            $tramite = $this->tramiteService->crearTramiteCompleto($request);
            
            Log::info('TramiteController: Trámite creado exitosamente', [
                'tramite_id' => $tramite->id,
                'proveedor_id' => $tramite->proveedor_id
            ]);
            
            // Limpiar datos de sesión después de crear el trámite
            session()->forget(['datos_constancia', 'tipo_tramite', 'tipo_tramite_seleccionado']);
            
            return redirect()->route('tramites.index')
                ->with('success', 'Trámite creado exitosamente.');
                
        } catch (\Exception $e) {
            Log::error('TramiteController: Error al crear trámite', [
                'user_id' => auth()->id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);
            
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
        
        // Obtener cita asignada si existe
        $citaAsignada = null;
        $personaResponsable = null;
        $citaVencida = false;
        $intentosRestantes = 2;
        
        if ($tramitePendiente) {
            $citaAsignada = $tramitePendiente->citas()
                ->where('estado', 'Asignada')
                ->orderBy('fecha_cita', 'desc')
                ->first();
            
            // Verificar si la cita ha vencido
            if ($citaAsignada && $citaAsignada->fecha_cita < now()) {
                $citaVencida = true;
                
                // Contar intentos previos
                $intentosPrevios = $tramitePendiente->citas()
                    ->where('estado', 'No Asistió')
                    ->count();
                
                $intentosRestantes = max(0, 2 - $intentosPrevios);
            }
            
            // Obtener información de la persona responsable
            if ($tramitePendiente->proveedor) {
                if ($tramitePendiente->proveedor->tipo_persona === 'Moral') {
                    // Para persona moral, obtener el apoderado legal
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
                    // Para persona física, obtener el usuario
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
        
        return view('tramites.estado', compact('proveedores', 'tramitePendiente', 'citaAsignada', 'rfc', 'personaResponsable', 'citaVencida', 'intentosRestantes'));
    }

    /**
     * Show the form for editing the specified trámite.
     */
    public function edit($tramiteId)
    {
        try {
            Log::info('TramiteController: Iniciando edición de trámite', [
                'tramite_id' => $tramiteId,
                'user_id' => auth()->id()
            ]);
            
            // Cargar el trámite con relaciones básicas primero
            $tramite = \App\Models\Tramite::with([
                'proveedor'
            ])->findOrFail($tramiteId);
            
            Log::info('TramiteController: Trámite encontrado', [
                'tramite_id' => $tramite->id,
                'status' => $tramite->status,
                'proveedor_rfc' => $tramite->proveedor->rfc ?? 'No encontrado'
            ]);
            
            // Verificar que el usuario tenga permisos para editar este trámite
            $rfc = $this->rfcProveedorService->obtenerRfcUsuario();
            Log::info('TramiteController: RFC del usuario', [
                'user_rfc' => $rfc,
                'tramite_rfc' => $tramite->proveedor->rfc ?? 'No encontrado'
            ]);
            
            if (!$rfc || $tramite->proveedor->rfc !== $rfc) {
                Log::warning('TramiteController: Usuario sin permisos para editar trámite', [
                    'user_rfc' => $rfc,
                    'tramite_rfc' => $tramite->proveedor->rfc ?? 'No encontrado'
                ]);
                return redirect()->route('tramites.index')
                    ->with('error', 'No tiene permisos para editar este trámite');
            }
            
            // Verificar que el trámite esté en estado de corrección
            if ($tramite->status !== 'Para_Correccion') {
                Log::warning('TramiteController: Trámite no está en estado de corrección', [
                    'tramite_status' => $tramite->status,
                    'expected_status' => 'Para_Correccion'
                ]);
                return redirect()->route('tramites.estado')
                    ->with('error', 'Este trámite no requiere correcciones');
            }
            
            // Cargar relaciones adicionales
            Log::info('TramiteController: Cargando relaciones adicionales');
            $tramite->load([
                'datosGenerales', 
                'apoderadosLegales.instrumentoNotarial.estado', 
                'accionistas', 
                'contactos', 
                'actividades', 
                'direcciones.coordenada', 
                'archivos.catalogoArchivo',
                'datosConstitutivos.instrumentoNotarial.estado'
            ]);
            
            // Preparar los datos para la vista de edición
            Log::info('TramiteController: Preparando datos para la vista');
            $archivosRequeridos = $this->rfcProveedorService->obtenerArchivosPorTipoPersonaDirecto($tramite->proveedor->tipo_persona);
            
            // Obtener datos adicionales necesarios para los formularios
            $tipoPersona = $tramite->proveedor->tipo_persona;
            
            // Obtener actividades económicas disponibles
            $actividadesDisponibles = \App\Models\Actividad::orderBy('nombre')->get();
            
            // Obtener estados, municipios y asentamientos para el formulario de domicilio
            $estados = \App\Models\Estado::orderBy('nombre')->get();
            $municipios = collect();
            $asentamientos = collect();
            
            // Si hay un domicilio existente, cargar municipios y asentamientos
            if ($tramite->direcciones->first()) {
                $domicilio = $tramite->direcciones->first();
                $municipios = \App\Models\Municipio::where('estado_id', $domicilio->estado_id)->orderBy('nombre')->get();
                
                // Para el formulario de edición, cargar todos los asentamientos del estado
                // ya que la tabla direcciones almacena municipio y asentamiento como strings
                $asentamientos = \App\Models\Asentamiento::whereHas('localidad.municipio', function($query) use ($domicilio) {
                    $query->where('estado_id', $domicilio->estado_id);
                })->orderBy('nombre')->get();
            }
            
            // Obtener tipos de asentamiento
            $tiposAsentamiento = \App\Models\TipoAsentamiento::orderBy('nombre')->get();
            
            // Obtener países
            $paises = \App\Models\Pais::orderBy('nombre')->get();
            
            // Crear FormDataViewModel con todos los datos del trámite
            $formData = [
                'datos_generales' => $tramite->datosGenerales->first() ? $tramite->datosGenerales->first()->toArray() : [],
                'actividades' => $tramite->actividades->toArray(),
                'domicilio' => $tramite->direcciones->first() ? $tramite->direcciones->first()->toArray() : [],
                'contacto' => $tramite->contactos->first() ? $tramite->contactos->first()->toArray() : [],
                'archivos' => $tramite->archivos->toArray(),
            ];
            
            // Agregar datos específicos para persona moral
            if ($tramite->proveedor->tipo_persona === 'Moral') {
                // Preparar datos de constitución con instrumento notarial
                $datosConstitutivos = $tramite->datosConstitutivos->first();
                $constitucionData = [];
                
                if ($datosConstitutivos) {
                    $constitucionData = $datosConstitutivos->toArray();
                    
                    // Agregar datos del instrumento notarial si existe
                    if ($datosConstitutivos->instrumentoNotarial) {
                        $instrumentoNotarial = $datosConstitutivos->instrumentoNotarial;
                        $constitucionData = array_merge($constitucionData, [
                            'numero_escritura' => $instrumentoNotarial->numero_escritura ?? '',
                            'numero_escritura_constitutiva' => $instrumentoNotarial->numero_escritura_constitutiva ?? '',
                            'fecha_constitucion' => $instrumentoNotarial->fecha_constitucion ?? '',
                            'nombre_notario' => $instrumentoNotarial->nombre_notario ?? '',
                            'numero_notario' => $instrumentoNotarial->numero_notario ?? '',
                            'estado_id' => $instrumentoNotarial->estado_id ?? '',
                            'estado_nombre' => $instrumentoNotarial->estado->nombre ?? '',
                            'numero_registro_publico' => $instrumentoNotarial->numero_registro_publico ?? '',
                            'fecha_inscripcion' => $instrumentoNotarial->fecha_inscripcion ?? '',
                        ]);
                    }
                }
                
                $formData['constitucion'] = $constitucionData;
                $formData['accionistas'] = $tramite->accionistas->toArray();
                
                // Preparar datos del apoderado con instrumento notarial
                $apoderado = $tramite->apoderadosLegales->first();
                $apoderadoData = [];
                
                if ($apoderado) {
                    $apoderadoData = $apoderado->toArray();
                    
                    // Agregar datos del instrumento notarial si existe
                    if ($apoderado->instrumentoNotarial) {
                        $instrumentoNotarial = $apoderado->instrumentoNotarial;
                        $apoderadoData = array_merge($apoderadoData, [
                            'numero_escritura' => $instrumentoNotarial->numero_escritura ?? '',
                            'numero_escritura_poder' => $instrumentoNotarial->numero_escritura_constitutiva ?? '',
                            'fecha_poder' => $instrumentoNotarial->fecha_constitucion ?? '',
                            'nombre_notario_poder' => $instrumentoNotarial->nombre_notario ?? '',
                            'numero_notario_poder' => $instrumentoNotarial->numero_notario ?? '',
                            'estado_id' => $instrumentoNotarial->estado_id ?? '',
                            'estado_nombre' => $instrumentoNotarial->estado->nombre ?? '',
                            'numero_registro_publico' => $instrumentoNotarial->numero_registro_publico ?? '',
                            'fecha_inscripcion' => $instrumentoNotarial->fecha_inscripcion ?? '',
                        ]);
                    }
                }
                
                $formData['apoderado'] = $apoderadoData;
            }
            
            $viewModel = new FormDataViewModel($formData);
                    
                    // Cargar estados de las secciones para determinar cuáles son editables
                    $estadosSecciones = [];
                    $secciones = ['datos_generales', 'actividades', 'domicilio', 'contacto', 'archivos'];
                    if ($tramite->proveedor->tipo_persona === 'Moral') {
                        $secciones = array_merge($secciones, ['constitucion', 'accionistas', 'apoderado']);
                    }
                    
                    foreach ($secciones as $seccion) {
                        $estadosSecciones[$seccion] = 'Pendiente'; // Por defecto
                    }
                    
                    // Cargar estados de archivos individuales desde la base de datos
                    $estadosArchivos = [];
                    $comentariosArchivos = [];
                    
                    foreach ($archivosRequeridos as $archivo) {
                        // Buscar el archivo cargado para este trámite
                        $archivoCargado = $tramite->archivos()
                            ->where('catalogo_archivo_id', $archivo->id)
                            ->first();
                        
                        if ($archivoCargado) {
                            $estadosArchivos[$archivo->id] = $archivoCargado->status ?? 'Pendiente';
                            $comentariosArchivos[$archivo->id] = $archivoCargado->comentario_revision ?? '';
                        } else {
                            $estadosArchivos[$archivo->id] = 'Pendiente';
                            $comentariosArchivos[$archivo->id] = '';
                        }
                    }
                    
                    Log::info('TramiteController: Vista de edición cargada exitosamente', [
                        'tramite_id' => $tramite->id,
                        'tipo_persona' => $tipoPersona,
                        'archivos_requeridos_count' => $archivosRequeridos->count(),
                        'estados_secciones' => $estadosSecciones,
                        'datos_constitutivos_existe' => $tramite->datosConstitutivos->count() > 0,
                        'apoderados_existe' => $tramite->apoderadosLegales->count() > 0,
                        'accionistas_count' => $tramite->accionistas->count(),
                        'form_data_keys' => array_keys($formData),
                        'constitucion_data' => $formData['constitucion'] ?? 'no_data',
                        'apoderado_data' => $formData['apoderado'] ?? 'no_data'
                    ]);
                    
                    // Preparar archivos para el componente de evaluación usando el método reutilizable
                    $archivosSubidos = $this->prepararArchivosParaCotejo($tramite->archivos);
                    
                    return view('tramites.edit', compact(
                        'tramite', 
                        'viewModel',
                        'archivosRequeridos', 
                        'tipoPersona',
                        'actividadesDisponibles',
                        'estados',
                        'municipios',
                        'asentamientos',
                        'tiposAsentamiento',
                        'paises',
                        'estadosSecciones',
                        'estadosArchivos',
                        'comentariosArchivos',
                        'archivosSubidos'
                    ));
            
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::error('TramiteController: Trámite no encontrado', [
                'tramite_id' => $tramiteId,
                'user_id' => auth()->id(),
                'error' => $e->getMessage()
            ]);
            
            return redirect()->route('tramites.index')
                ->with('error', 'El trámite especificado no existe');
                
        } catch (\Exception $e) {
            Log::error('TramiteController: Error al mostrar formulario de edición', [
                'tramite_id' => $tramiteId,
                'user_id' => auth()->id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->route('tramites.index')
                ->with('error', 'Error al cargar el formulario de edición: ' . $e->getMessage());
        }
    }

    /**
     * Prepara los datos de archivos para el cotejo, incluyendo el nombre del catálogo
     * Método reutilizable para preparar archivos
     */
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

    /**
     * Update the specified trámite in storage.
     */
    public function update(Request $request, $tramiteId)
    {
        try {
            $tramite = \App\Models\Tramite::findOrFail($tramiteId);
            
            // Verificar que el usuario tenga permisos para editar este trámite
            $rfc = $this->rfcProveedorService->obtenerRfcUsuario();
            if (!$rfc || $tramite->proveedor->rfc !== $rfc) {
                return redirect()->route('tramites.index')
                    ->with('error', 'No tiene permisos para editar este trámite');
            }
            
            // Verificar que el trámite esté en estado de corrección
            if ($tramite->status !== 'Para_Correccion') {
                return redirect()->route('tramites.estado')
                    ->with('error', 'Este trámite no requiere correcciones');
            }
            
            // Actualizar el trámite usando el servicio
            $tramiteActualizado = $this->tramiteService->actualizarTramite($tramite, $request);
            
            Log::info('TramiteController: Trámite actualizado exitosamente', [
                'tramite_id' => $tramite->id,
                'user_id' => auth()->id()
            ]);
            
            return redirect()->route('tramites.estado')
                ->with('success', 'Trámite corregido exitosamente. Será revisado nuevamente.');
                
        } catch (\Exception $e) {
            Log::error('TramiteController: Error al actualizar trámite', [
                'tramite_id' => $tramiteId,
                'user_id' => auth()->id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()
                ->withInput()
                ->withErrors(['error' => 'Error al corregir el trámite: ' . $e->getMessage()]);
        }
    }

    /**
     * Descargar la constancia de un trámite
     */
    public function descargarConstancia($tramiteId)
    {
        try {
            $tramite = \App\Models\Tramite::findOrFail($tramiteId);
            
            // Verificar que el usuario tenga permisos para descargar este trámite
            $rfc = $this->rfcProveedorService->obtenerRfcUsuario();
            if (!$rfc || $tramite->proveedor->rfc !== $rfc) {
                return redirect()->route('tramites.index')
                    ->with('error', 'No tiene permisos para descargar este trámite');
            }
            
            // Verificar que el trámite esté aprobado
            if ($tramite->status !== 'Aprobado') {
                return redirect()->route('tramites.index')
                    ->with('error', 'Solo se pueden descargar constancias de trámites aprobados');
            }
            
            // Buscar el archivo de constancia
            $constancia = $tramite->archivos()
                ->whereHas('catalogoArchivo', function($query) {
                    $query->where('nombre', 'like', '%constancia%')
                          ->orWhere('nombre', 'like', '%Constancia%')
                          ->orWhere('nombre', 'like', '%situación fiscal%')
                          ->orWhere('nombre', 'like', '%situacion fiscal%');
                })
                ->first();
            
            // Log para debugging
            Log::info('TramiteController: Búsqueda de constancia', [
                'tramite_id' => $tramite->id,
                'archivos_count' => $tramite->archivos()->count(),
                'constancia_encontrada' => $constancia ? true : false,
                'archivos_disponibles' => $tramite->archivos()->with('catalogoArchivo')->get()->map(function($archivo) {
                    return [
                        'id' => $archivo->id,
                        'nombre_catalogo' => $archivo->catalogoArchivo ? $archivo->catalogoArchivo->nombre : 'Sin catálogo',
                        'nombre_original' => $archivo->nombre_original
                    ];
                })
            ]);
            
            if (!$constancia) {
                return redirect()->route('tramites.index')
                    ->with('error', 'No se encontró la constancia para este trámite');
            }
            
            // Verificar que el archivo existe físicamente
            $rutaArchivo = storage_path('app/public/' . $constancia->ruta);
            if (!file_exists($rutaArchivo)) {
                return redirect()->route('tramites.index')
                    ->with('error', 'El archivo de constancia no está disponible');
            }
            
            Log::info('TramiteController: Constancia descargada exitosamente', [
                'tramite_id' => $tramite->id,
                'archivo_id' => $constancia->id,
                'user_id' => auth()->id()
            ]);
            
            // Descargar el archivo
            return response()->download($rutaArchivo, $constancia->nombre_original);
            
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::error('TramiteController: Trámite no encontrado para descarga', [
                'tramite_id' => $tramiteId,
                'user_id' => auth()->id(),
                'error' => $e->getMessage()
            ]);
            
            return redirect()->route('tramites.index')
                ->with('error', 'El trámite especificado no existe');
                
        } catch (\Exception $e) {
            Log::error('TramiteController: Error al descargar constancia', [
                'tramite_id' => $tramiteId,
                'user_id' => auth()->id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->route('tramites.index')
                ->with('error', 'Error al descargar la constancia: ' . $e->getMessage());
        }
    }
} 
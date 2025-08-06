<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProcesarConstanciaRequest;
use App\Http\Requests\TramiteFormRequest;
use App\Services\Tramites\TramiteService;
use App\Services\Tramites\ConstanciaService;
use App\Services\RfcProveedorService;
use App\ViewModels\TramiteViewModel;
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
        
        if (!$rfc) {
            $tramites = [
                'inscripcion' => ['activo' => true, 'pendiente' => false, 'motivo' => 'Usuario sin RFC'],
                'renovacion' => ['activo' => false, 'pendiente' => false, 'motivo' => 'Usuario sin RFC'],
                'actualizacion' => ['activo' => false, 'pendiente' => false, 'motivo' => 'Usuario sin RFC']
            ];
        } else {
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
        
        return view('tramites.index', compact('tramites'));
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
        
        // Guardar el tipo de trámite en la sesión para el store
        session(['tipo_tramite' => ucfirst($tipo)]);
        
        return view('tramites.create', compact('tipo', 'tipoPersona', 'viewModel', 'archivosRequeridos'));
    }

    public function store(TramiteFormRequest $request)
    {
        Log::info('TramiteController: Iniciando creación de trámite', [
            'user_id' => auth()->id(),
            'request_data' => $request->all(),
            'files' => $request->allFiles()
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
                'tipo_tramite' => $tipoTramite
            ]);
            
            // Agregar el tipo de trámite al request
            $request->merge(['tipo_tramite' => $tipoTramite]);
            
            $this->tramiteService->crearTramiteCompleto($request);
            
            Log::info('TramiteController: Trámite creado exitosamente');
            
            return redirect()->route('tramites.index')
                ->with('success', 'Trámite creado exitosamente.');
                
        } catch (\Exception $e) {
            Log::error('TramiteController: Error al crear trámite', [
                'user_id' => auth()->id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()->withErrors(['error' => 'Error al crear el trámite: ' . $e->getMessage()]);
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
} 
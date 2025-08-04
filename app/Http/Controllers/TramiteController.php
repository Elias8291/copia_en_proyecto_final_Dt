<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProcesarConstanciaRequest;
use App\Http\Requests\TramiteFormRequest;
use App\Services\Tramites\FormDataService;
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
    private FormDataService $formDataService;

    public function __construct(
        TramiteService $tramiteService, 
        ConstanciaService $constanciaService, 
        RfcProveedorService $rfcProveedorService,
        FormDataService $formDataService
    ) {
        $this->tramiteService = $tramiteService;
        $this->constanciaService = $constanciaService;
        $this->rfcProveedorService = $rfcProveedorService;
        $this->formDataService = $formDataService;
    }

    public function index()
    {
        $rfc = $this->rfcProveedorService->obtenerRfcUsuario();
        
        if (!$rfc) {
            $tramites = [
                'inscripcion' => ['activo' => true, 'motivo' => 'Usuario sin RFC'],
                'renovacion' => ['activo' => false, 'motivo' => 'Usuario sin RFC'],
                'actualizacion' => ['activo' => false, 'motivo' => 'Usuario sin RFC']
            ];
        } else {
            // Usar la nueva lógica para cada tipo de trámite
            $accionInscripcion = $this->rfcProveedorService->determinarAccionPorTipoTramite($rfc, 'inscripcion');
            $accionRenovacion = $this->rfcProveedorService->determinarAccionPorTipoTramite($rfc, 'renovacion');
            $accionActualizacion = $this->rfcProveedorService->determinarAccionPorTipoTramite($rfc, 'actualizacion');
            
            $tramites = [
                'inscripcion' => [
                    'activo' => in_array($accionInscripcion['accion'], ['crear_nuevo', 'proveedor_activo']),
                    'motivo' => $accionInscripcion['motivo'],
                    'accion' => $accionInscripcion['accion']
                ],
                'renovacion' => [
                    'activo' => in_array($accionRenovacion['accion'], ['renovar_activo', 'renovar_vencido']),
                    'motivo' => $accionRenovacion['motivo'],
                    'accion' => $accionRenovacion['accion']
                ],
                'actualizacion' => [
                    'activo' => $accionActualizacion['accion'] === 'actualizar_existente',
                    'motivo' => $accionActualizacion['motivo'],
                    'accion' => $accionActualizacion['accion']
                ]
            ];
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
        
        // Verificar si puede realizar este tipo de trámite
        $accion = $this->rfcProveedorService->determinarAccionPorTipoTramite($rfc, $tipo);
        
        if ($accion['accion'] === 'tramite_pendiente') {
            return redirect()->route('tramites.estado')
                ->with('error', 'Tiene un trámite pendiente. Debe completarlo antes de iniciar uno nuevo.');
        }
        
        // Verificar si el trámite está disponible
        $tramites = [
            'inscripcion' => ['activo' => in_array($accion['accion'], ['crear_nuevo', 'proveedor_activo'])],
            'renovacion' => ['activo' => in_array($accion['accion'], ['renovar_activo', 'renovar_vencido'])],
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
            // Obtener el tipo de trámite de la sesión o del request
            $tipoTramite = session('tipo_tramite') ?? $request->tipo_tramite ?? 'Inscripcion';
            
            Log::info('TramiteController: Tipo de trámite para crear', [
                'tipo_tramite' => $tipoTramite
            ]);
            
            // Agregar el tipo de trámite al request
            $request->merge(['tipo_tramite' => $tipoTramite]);
            
            $this->formDataService->guardarTramite($request);
            
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

    public function testStore(Request $request)
    {
        try {
            Log::info('TEST: Iniciando creación de trámite sin validaciones estrictas', [
                'user_id' => auth()->id(),
                'request_data' => $request->all(),
                'files' => $request->allFiles()
            ]);

            $rfc = $request->rfc_hidden ?? 'TEST123456789';
            
            // Verificar si ya existe un proveedor con este RFC para este usuario
            $proveedor = \App\Models\Proveedor::where('usuario_id', auth()->id())
                ->where('rfc', $rfc)
                ->first();
            
            if (!$proveedor) {
                // Crear un proveedor básico si no existe con este RFC
                $proveedor = \App\Models\Proveedor::create([
                    'usuario_id' => auth()->id(),
                    'pv_numero' => 'TEST-' . time(),
                    'rfc' => $rfc,
                    'tipo_persona' => $request->tipo_persona_hidden ?? 'Física',
                    'estado_padron' => 'pendiente',
                    'fecha_alta_padron' => now(),
                    'fecha_vencimiento_padron' => now()->addYear(),
                ]);
                
                Log::info('TEST: Nuevo proveedor creado', [
                    'proveedor_id' => $proveedor->id,
                    'rfc' => $rfc
                ]);
            } else {
                // Actualizar el proveedor existente
                $proveedor->update([
                    'tipo_persona' => $request->tipo_persona_hidden ?? $proveedor->tipo_persona,
                    'estado_padron' => 'pendiente',
                    'fecha_alta_padron' => now(),
                    'fecha_vencimiento_padron' => now()->addYear(),
                ]);
                
                Log::info('TEST: Proveedor existente actualizado', [
                    'proveedor_id' => $proveedor->id,
                    'rfc' => $rfc
                ]);
            }

            // Crear un trámite básico
            $tramite = \App\Models\Tramite::create([
                'proveedor_id' => $proveedor->id,
                'tipo_tramite' => 'Inscripcion',
                'status' => 'Pendiente',
                'fecha_inicio' => now(),
                'correcciones_count' => 0,
                'paso_actual' => 1,
            ]);

            Log::info('TEST: Trámite básico creado exitosamente', [
                'tramite_id' => $tramite->id,
                'proveedor_id' => $proveedor->id
            ]);

            return redirect()->route('tramites.index')
                ->with('success', 'Trámite de prueba creado exitosamente.');

        } catch (\Exception $e) {
            Log::error('TEST: Error al crear trámite básico', [
                'user_id' => auth()->id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()->withErrors(['error' => 'Error al crear el trámite de prueba: ' . $e->getMessage()]);
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
        $tramitesPendientes = collect();
        
        foreach ($proveedores as $proveedor) {
            $tramites = $proveedor->tramites()->where('status', 'Pendiente')->get();
            $tramitesPendientes = $tramitesPendientes->merge($tramites);
        }
        
        return view('tramites.estado', compact('proveedores', 'tramitesPendientes', 'rfc'));
    }
} 
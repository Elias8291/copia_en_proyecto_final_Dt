<?php

namespace App\Http\Controllers;

use App\Http\Requests\TramiteFormularioRequest;
use App\Models\Tramite;  // Added this import for the new methods
use App\Services\ProveedorService;
use App\Services\TramiteService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class TramiteController extends Controller
{
    protected $proveedorService;

    protected $tramiteService;

    public function __construct(ProveedorService $proveedorService, TramiteService $tramiteService)
    {
        $this->middleware('auth');
        $this->proveedorService = $proveedorService;
        $this->tramiteService = $tramiteService;
    }

    /**
     * Muestra la página de selección de trámites
     */
    public function index()
    {
        $proveedor = $this->proveedorService->getProveedorByUser();

        return view('tramites.index', $this->tramiteService->getDatosTramitesIndex($proveedor));
    }

    /**
     * Muestra la página de carga de constancia (primer paso)
     */
    public function constancia($tipo)
    {
        $proveedor = $this->proveedorService->getProveedorByUser();

        // Validar acceso al trámite
        if (!$this->tramiteService->validarAccesoTramite($tipo, $proveedor)) {
            return redirect()
                ->route('tramites.index')
                ->with('error', 'No tiene permisos para acceder a este trámite.');
        }

        return view('tramites.constancia', $this->tramiteService->getDatosConstancia($tipo, $proveedor));
    }

    /**
     * Procesa la constancia y guarda los datos del SAT en sesión
     */
    public function procesarConstancia(Request $request, $tipo)
    {
        $proveedor = $this->proveedorService->getProveedorByUser();

        // Validar acceso al trámite
        if (!$this->tramiteService->validarAccesoTramite($tipo, $proveedor)) {
            return redirect()
                ->route('tramites.index')
                ->with('error', 'No tiene permisos para acceder a este trámite.');
        }

        try {
            // Procesar y guardar datos SAT en sesión
            $this->tramiteService->procesarDatosConstancia($request);

            return redirect()->route('tramites.formulario', $tipo);
        } catch (\Exception $e) {
            Log::error('Error al procesar constancia', [
                'usuario_id' => Auth::id(),
                'tipo_tramite' => $tipo,
                'error' => $e->getMessage()
            ]);

            return redirect()
                ->route('tramites.constancia', $tipo)
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Muestra el formulario según el tipo de trámite (segundo paso)
     */
    public function formulario(Request $request, $tipo = 'inscripcion')
    {
        $proveedor = $this->proveedorService->getProveedorByUser();

        // Validar acceso al trámite
        if (!$this->tramiteService->validarAccesoTramite($tipo, $proveedor)) {
            return redirect()
                ->route('tramites.index')
                ->with('error', 'No tiene permisos para acceder a este trámite.');
        }

        return view('tramites.formulario', $this->tramiteService->getDatosFormulario($tipo, $proveedor));
    }

    /**
     * Muestra el formulario simple sin steps
     */
    public function formularioSimple(Request $request, $tipo = 'inscripcion')
    {
        $proveedor = $this->proveedorService->getProveedorByUser();

        // Validar acceso al trámite
        if (!$this->tramiteService->validarAccesoTramite($tipo, $proveedor)) {
            return redirect()
                ->route('tramites.index')
                ->with('error', 'No tiene permisos para acceder a este trámite.');
        }

        return view('tramites.formulario-simple', $this->tramiteService->getDatosFormulario($tipo, $proveedor));
    }

    /**
     * Procesa el envío del formulario de trámite
     */
    public function store(Request $request, $tipo)
    {
        Log::info('=== PRUEBA: Controlador recibió petición ===', [
            'tipo' => $tipo,
            'usuario_id' => Auth::id(),
            'request_data' => $request->all(),
            'files' => $request->allFiles(),
            'method' => $request->method(),
            'url' => $request->url(),
            'headers' => $request->headers->all(),
            'content_type' => $request->header('Content-Type'),
            'timestamp' => now()->toDateTimeString()
        ]);

        // Log adicional para verificar que el método se ejecuta
        Log::info('=== PRUEBA: Método store ejecutándose ===', [
            'tipo' => $tipo,
            'usuario_autenticado' => Auth::check(),
            'usuario_id' => Auth::id()
        ]);

        // Log simple para verificar que el logging funciona
        Log::info('PRUEBA SIMPLE - Controlador funcionando');

        try {
            // Verificar que el usuario esté autenticado
            if (!Auth::check()) {
                Log::error('Usuario no autenticado');
                return redirect()->route('login')->with('error', 'Debe iniciar sesión para continuar.');
            }

            Log::info('=== PRUEBA: Usuario autenticado, llamando al servicio ===');

            $proveedor = $this->proveedorService->getProveedorByUser();
            Log::info('=== PRUEBA: Proveedor obtenido ===', ['proveedor_id' => $proveedor?->id]);

            // Llamar al servicio sin validaciones
            $resultado = $this->tramiteService->procesarEnvioFormulario($request, $tipo, $proveedor);

            Log::info('=== PRUEBA: Servicio respondió ===', ['resultado' => $resultado]);

            if ($resultado['success']) {
                Log::info('=== PRUEBA: Éxito - Trámite procesado ===', [
                    'tramite_id' => $resultado['tramite_id'] ?? null
                ]);

                return redirect()
                    ->route('tramites.exito')
                    ->with('success', '¡Prueba exitosa! El controlador y servicio funcionan correctamente.')
                    ->with('tramite_id', $resultado['tramite_id'] ?? 999);
            }

            Log::error('=== PRUEBA: Error en servicio ===', [
                'mensaje' => $resultado['message'] ?? 'Error desconocido'
            ]);

            return back()
                ->withInput()
                ->with('error', 'Error en servicio: ' . ($resultado['message'] ?? 'Error desconocido'));
        } catch (\Exception $e) {
            Log::error('=== PRUEBA: Excepción capturada ===', [
                'error' => $e->getMessage(),
                'tipo' => $tipo,
                'usuario_id' => Auth::id(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ]);

            return back()
                ->withInput()
                ->with('error', 'Excepción capturada: ' . $e->getMessage());
        }
    }

    /**
     * Página de éxito tras completar un trámite
     */
    public function exito(Request $request)
    {
        $tramiteId = session('tramite_id');
        $mensaje = session('success', 'Su trámite ha sido enviado exitosamente.');

        return view('tramites.exito', [
            'tramite_id' => $tramiteId,
            'mensaje' => $mensaje
        ]);
    }

    /**
     * Mostrar datos completos de un trámite (ejemplo de uso)
     */
    public function mostrarDatosCompletos($id)
    {
        try {
            $datosCompletos = $this->tramiteService->obtenerDatosCompletosTramite((int) $id);

            if (!$datosCompletos) {
                return redirect()->back()->with('error', 'Trámite no encontrado');
            }

            // Ejemplo de uso de los datos
            $tramite = $datosCompletos['tramite'];
            $resumen = $datosCompletos['resumen'];
            $completitud = $resumen['completitud'];

            // Verificar permisos (ejemplo)
            if ($tramite->proveedor_id !== Auth::user()->proveedor?->id) {
                abort(403, 'No tienes permisos para ver este trámite');
            }

            return view('tramites.detalle-completo', compact('datosCompletos'));
        } catch (\Exception $e) {
            Log::error('Error al obtener datos completos del trámite', [
                'tramite_id' => $id,
                'error' => $e->getMessage()
            ]);

            return redirect()->back()->with('error', 'Error al cargar los datos del trámite');
        }
    }

    /**
     * API para obtener datos completos del trámite (JSON)
     */
    public function obtenerDatosCompletosTramiteAPI($id)
    {
        try {
            $datosCompletos = $this->tramiteService->obtenerDatosCompletosTramite((int) $id);

            if (!$datosCompletos) {
                return response()->json([
                    'success' => false,
                    'message' => 'Trámite no encontrado'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $datosCompletos
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener los datos del trámite'
            ], 500);
        }
    }

    /**
     * Determina en qué tab están los errores de validación
     */
    private function determinarTabConErrores(array $errors): string
    {
        // Mapeo de campos a tabs
        $mapeoTabs = [
            // Datos Generales
            'datos' => [
                'rfc', 'razon_social', 'tipo_persona', 'curp', 'pagina_web',
                'email_contacto', 'telefono', 'cargo'
            ],
            // Actividades
            'actividades' => [
                'actividades', 'actividades.*'
            ],
            // Domicilio
            'domicilio' => [
                'calle', 'numero_exterior', 'numero_interior', 'codigo_postal',
                'asentamiento', 'municipio', 'estado_id', 'colonia'
            ],
            // Constitución (solo persona moral)
            'constitucion' => [
                'numero_escritura', 'fecha_constitucion', 'notario_nombre',
                'entidad_federativa', 'notario_numero', 'numero_registro',
                'fecha_inscripcion'
            ],
            // Apoderado (solo persona moral)
            'apoderado' => [
                'apoderado_nombre', 'apoderado_rfc'
            ],
            // Accionistas (solo persona moral)
            'accionistas' => [
                'accionistas', 'accionistas.*', 'accionista_nombre',
                'accionista_rfc', 'accionista_porcentaje'
            ],
            // Documentos
            'documentos' => [
                'documentos', 'documentos.*', 'constancia_fiscal',
                'identificacion', 'comprobante_domicilio', 'acta_constitutiva',
                'poder_notarial', 'documentos_adicionales'
            ]
        ];

        // Buscar en qué tab están los errores
        foreach ($mapeoTabs as $tab => $campos) {
            foreach ($campos as $campo) {
                if (isset($errors[$campo])) {
                    return $tab;
                }
            }
        }

        // Si no se encuentra, regresar al primer tab
        return 'datos';
    }

    /**
     * Crea un mensaje de error más específico basado en los errores de validación
     */
    private function crearMensajeErrorValidacion(array $errors, string $tabConErrores): string
    {
        $mensajesTab = [
            'datos' => 'Datos Generales',
            'actividades' => 'Actividades Económicas',
            'domicilio' => 'Domicilio',
            'constitucion' => 'Constitución',
            'apoderado' => 'Apoderado Legal',
            'accionistas' => 'Accionistas',
            'documentos' => 'Documentos'
        ];

        $nombreTab = $mensajesTab[$tabConErrores] ?? 'el formulario';
        $numErrores = count($errors);

        if ($numErrores === 1) {
            $campo = array_key_first($errors);
            $mensaje = $errors[$campo][0] ?? 'Hay un error en el campo.';
            return "Error en la sección '{$nombreTab}': {$mensaje}";
        } else {
            return "Hay {$numErrores} errores en la sección '{$nombreTab}'. Por favor revise los campos marcados.";
        }
    }

    /**
     * Muestra el formulario de corrección para un trámite en estado Para_Correccion
     */
    public function corregir(Tramite $tramite)
    {
        // Verificar que el usuario sea el propietario del trámite
        if ($tramite->proveedor->usuario_id !== Auth::id()) {
            abort(403, 'No tienes permisos para corregir este trámite.');
        }

        // Verificar que el trámite esté en estado Para_Correccion
        if ($tramite->estado !== 'Para_Correccion') {
            return redirect()->route('tramites.estado')
                ->with('error', 'Este trámite no requiere correcciones.');
        }

        // Obtener datos del formulario usando el servicio
        $datos = $this->tramiteService->getDatosFormularioCorreccion($tramite);

        return view('tramites.formulario-simple', $datos);
    }

    /**
     * Procesa la actualización de un trámite con correcciones
     */
    public function actualizarCorreccion(Request $request, Tramite $tramite)
    {
        // Verificar que el usuario sea el propietario del trámite
        if ($tramite->proveedor->usuario_id !== Auth::id()) {
            abort(403, 'No tienes permisos para corregir este trámite.');
        }

        // Verificar que el trámite esté en estado Para_Correccion
        if ($tramite->estado !== 'Para_Correccion') {
            return redirect()->route('tramites.estado')
                ->with('error', 'Este trámite no requiere correcciones.');
        }

        try {
            // Procesar las correcciones usando el servicio
            $resultado = $this->tramiteService->procesarCorreccionTramite($request, $tramite);

            if ($resultado['success']) {
                return redirect()->route('tramites.estado')
                    ->with('success', $resultado['message'])
                    ->with('tramite_id', $tramite->id);
            } else {
                return back()
                    ->withInput()
                    ->with('error', $resultado['message']);
            }

        } catch (\Exception $e) {
            Log::error('Error al procesar correcciones del trámite', [
                'tramite_id' => $tramite->id,
                'usuario_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);

            return back()
                ->withInput()
                ->with('error', 'Error al procesar las correcciones: ' . $e->getMessage());
        }
    }

    /**
     * Muestra el estado del trámite del usuario
     */
    public function estado()
    {
        $proveedor = $this->proveedorService->getProveedorByUser();
        
        if (!$proveedor) {
            return redirect()->route('tramites.index')
                ->with('error', 'No se encontró información del proveedor.');
        }

        // Obtener el trámite más reciente del proveedor
        $tramite = $proveedor->tramites()
            ->orderBy('created_at', 'desc')
            ->first();

        if (!$tramite) {
            return redirect()->route('tramites.index')
                ->with('error', 'No se encontró ningún trámite.');
        }

        // Obtener la cita si existe
        $cita = null;
        if ($tramite->estado === 'Por_Cotejar') {
            $cita = $tramite->cita;
        }

        return view('tramites.estado', [
            'tramite' => $tramite,
            'estado' => $tramite->estado,
            'tramite_id' => $tramite->id,
            'cita' => $cita
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Services\ProveedorService;
use App\Services\TramiteService;
use App\Http\Requests\TramiteFormularioRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

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
        if (! $this->tramiteService->validarAccesoTramite($tipo, $proveedor)) {
            return redirect()->route('tramites.index')
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
        if (! $this->tramiteService->validarAccesoTramite($tipo, $proveedor)) {
            return redirect()->route('tramites.index')
                ->with('error', 'No tiene permisos para acceder a este trámite.');
        }

        // Procesar y guardar datos SAT en sesión
        $this->tramiteService->procesarDatosConstancia($request);

        return redirect()->route('tramites.formulario', $tipo);
    }

    /**
     * Muestra el formulario según el tipo de trámite (segundo paso)
     */
    public function formulario(Request $request, $tipo = 'inscripcion')
    {
        $proveedor = $this->proveedorService->getProveedorByUser();

        // Validar acceso al trámite
        if (! $this->tramiteService->validarAccesoTramite($tipo, $proveedor)) {
            return redirect()->route('tramites.index')
                ->with('error', 'No tiene permisos para acceder a este trámite.');
        }

        return view('tramites.formulario', $this->tramiteService->getDatosFormulario($tipo, $proveedor));
    }

    /**
     * Procesa el envío del formulario de trámite
     */
    public function store(TramiteFormularioRequest $request, $tipo)
    {
        Log::info('Procesando trámite', [
            'tipo' => $tipo,
            'usuario_id' => Auth::id()
        ]);

        try {
            $proveedor = $this->proveedorService->getProveedorByUser();

            if (! $this->tramiteService->validarAccesoTramite($tipo, $proveedor)) {
                Log::warning('Acceso denegado al trámite', [
                    'tipo' => $tipo, 
                    'usuario_id' => Auth::id()
                ]);
                
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'No tiene permisos para realizar este trámite.'
                    ], 403);
                }
                
                return redirect()->route('tramites.index')
                    ->with('error', 'No tiene permisos para realizar este trámite.');
            }

            $resultado = $this->tramiteService->procesarEnvioFormulario($request, $tipo, $proveedor);

            if ($resultado['success']) {
                $this->tramiteService->limpiarDatosSesion();
                
                Log::info('Trámite procesado exitosamente', [
                    'tramite_id' => $resultado['tramite_id'] ?? null
                ]);

                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => true,
                        'message' => $resultado['message'],
                        'tramite_id' => $resultado['tramite_id'],
                        'redirect' => $resultado['redirect']
                    ], 200);
                }

                return redirect($resultado['redirect'])
                    ->with('success', $resultado['message'])
                    ->with('tramite_id', $resultado['tramite_id']);
            }

            Log::error('Error en procesamiento del trámite', [
                'mensaje' => $resultado['message'] ?? 'Error desconocido'
            ]);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $resultado['message'] ?? 'Error al procesar el trámite.'
                ], 422);
            }

            return back()
                ->withInput()
                ->with('error', $resultado['message'] ?? 'Error al procesar el trámite.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Manejar errores de validación específicamente
            Log::warning('Errores de validación en trámite', [
                'errors' => $e->errors(),
                'tipo' => $tipo,
                'usuario_id' => Auth::id()
            ]);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Por favor corrija los errores en el formulario.',
                    'errors' => $e->errors(),
                    'validation_failed' => true
                ], 422);
            }

            return back()
                ->withErrors($e->errors())
                ->withInput()
                ->with('error', 'Por favor corrija los errores en el formulario.');

        } catch (\Exception $e) {
            Log::error('Excepción en procesamiento de trámite', [
                'error' => $e->getMessage(),
                'tipo' => $tipo,
                'usuario_id' => Auth::id(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ]);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error interno del servidor. Por favor, intente nuevamente.'
                ], 500);
            }

            return back()
                ->withInput()
                ->with('error', 'Error interno del servidor. Por favor, intente nuevamente.');
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
            $datosCompletos = $this->tramiteService->obtenerDatosCompletosTramite((int)$id);
            
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
            $datosCompletos = $this->tramiteService->obtenerDatosCompletosTramite((int)$id);
            
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
}

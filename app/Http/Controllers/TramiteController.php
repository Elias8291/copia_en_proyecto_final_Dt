<?php

namespace App\Http\Controllers;

use App\Models\Tramite;
use App\Services\ProveedorService;
use App\Services\TramiteService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class TramiteController extends Controller
{
    public function __construct(
        private ProveedorService $proveedorService,
        private TramiteService $tramiteService
    ) {
        $this->middleware('auth');
    }

    // ============================================================================
    // MÉTODOS PÚBLICOS PRINCIPALES
    // ============================================================================

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

            return redirect()->route('tramites.formulario.simple', $tipo);
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
        try {
            // Verificar que el usuario esté autenticado
            if (!Auth::check()) {
                return redirect()->route('login')->with('error', 'Debe iniciar sesión para continuar.');
            }

            $proveedor = $this->proveedorService->getProveedorByUser();

            // Llamar al servicio
            $resultado = $this->tramiteService->procesarEnvioFormulario($request, $tipo, $proveedor);

            if ($resultado['success']) {
                return redirect()
                    ->route('tramites.exito')
                    ->with('success', 'Trámite procesado exitosamente.')
                    ->with('tramite_id', $resultado['tramite_id']);
            }

            return back()
                ->withInput()
                ->with('error', $resultado['message']);
        } catch (\Exception $e) {
            Log::error('Error al procesar formulario de trámite', [
                'error' => $e->getMessage(),
                'tipo' => $tipo,
                'usuario_id' => Auth::id()
            ]);

            return back()
                ->withInput()
                ->with('error', 'Error al procesar el trámite: ' . $e->getMessage());
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

    // ============================================================================
    // MÉTODOS DE CORRECCIÓN
    // ============================================================================

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

    // ============================================================================
    // MÉTODOS DE ESTADO
    // ============================================================================

    /**
     * Muestra el estado del trámite del usuario
     */
    public function estado()
    {
        try {
            // Buscar el proveedor directamente
            $proveedor = \App\Models\Proveedor::where('usuario_id', auth()->id())->first();
            
            if (!$proveedor) {
                return redirect()->route('tramites.index')
                    ->with('error', 'No se encontró información del proveedor.');
            }

            // Obtener el trámite más reciente del proveedor con todas las relaciones necesarias
            $tramite = $proveedor->tramites()
                ->with(['proveedor.user', 'datosGenerales', 'oficios', 'cita'])
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

            // Obtener el oficio - mejorar la consulta
            $oficio = null;
            if ($tramite->estado === 'Aprobado') {
                // Intentar obtener el oficio más reciente del trámite
                $oficio = $tramite->oficios()->orderBy('created_at', 'desc')->first();
                
                // Si no se encuentra en la relación, intentar búsqueda directa
                if (!$oficio) {
                    $oficio = \App\Models\Oficio::where('tramite_id', $tramite->id)
                        ->orderBy('created_at', 'desc')
                        ->first();
                }
                
                // Log para debugging
                if (!$oficio) {
                    Log::warning('No se encontró oficio para trámite aprobado', [
                        'tramite_id' => $tramite->id,
                        'estado' => $tramite->estado,
                        'proveedor_id' => $tramite->proveedor_id
                    ]);
                } else {
                    Log::info('Oficio encontrado para trámite', [
                        'tramite_id' => $tramite->id,
                        'oficio_id' => $oficio->id,
                        'numero_oficio' => $oficio->numero_oficio
                    ]);
                }
            }

            return view('tramites.estado', [
                'tramite' => $tramite,
                'estado' => $tramite->estado,
                'tramite_id' => $tramite->id,
                'cita' => $cita,
                'oficio' => $oficio
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error en método estado()', [
                'user_id' => auth()->id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->route('tramites.index')
                ->with('error', 'Error al cargar el estado del trámite: ' . $e->getMessage());
        }
    }
}

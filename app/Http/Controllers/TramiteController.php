<?php

namespace App\Http\Controllers;

use App\Http\Requests\TramiteFormRequest;
use App\Models\Proveedor;
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
    public function store(TramiteFormRequest $request, $tipo)
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
                // Si viene del formulario simple, redirigir a index
                if ($request->has('formulario_simple')) {
                    return redirect()
                        ->route('tramites.index')
                        ->with('success', 'Trámite procesado exitosamente. Su trámite ha sido enviado y está en revisión.');
                }

                // Si viene del formulario normal, redirigir a exito
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
        try {
            $this->validarPermisosCorreccion($tramite);
            $datos = $this->tramiteService->getDatosFormularioCorreccion($tramite);

            return view('tramites.formulario-simple', $datos);

        } catch (\Exception $e) {
            return redirect()
                ->route('tramites.estado')
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Procesa la actualización de un trámite con correcciones
     */
    public function actualizarCorreccion(TramiteFormRequest $request, Tramite $tramite)
    {
        try {
            $this->validarPermisosCorreccion($tramite);
            $resultado = $this->tramiteService->procesarCorreccionTramite($request, $tramite);

            return $this->manejarRespuestaCorreccion($request, $tramite, $resultado);

        } catch (\Exception $e) {
            Log::error('Error al procesar correcciones', [
                'tramite_id' => $tramite->id,
                'user_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);

            return back()
                ->withInput()
                ->with('error', 'Error al procesar las correcciones.');
        }
    }

    /**
     * Valida permisos y estado para correcciones
     */
    private function validarPermisosCorreccion(Tramite $tramite): void
    {
        if ($tramite->proveedor->usuario_id !== Auth::id()) {
            abort(403, 'No tienes permisos para corregir este trámite.');
        }

        if ($tramite->estado !== 'Para_Correccion') {
            throw new \Exception('Este trámite no requiere correcciones.');
        }
    }

    /**
     * Maneja la respuesta después de procesar correcciones
     */
    private function manejarRespuestaCorreccion(Request $request, Tramite $tramite, array $resultado)
    {
        if (!$resultado['success']) {
            return back()
                ->withInput()
                ->with('error', $resultado['message']);
        }

        $mensaje = $request->has('formulario_simple') 
            ? 'Correcciones enviadas exitosamente. Su trámite ha sido reenviado para revisión.'
            : $resultado['message'];

        $ruta = $request->has('formulario_simple') 
            ? 'tramites.index' 
            : 'tramites.estado';

        return redirect()
            ->route($ruta)
            ->with('success', $mensaje)
            ->with('tramite_id', $tramite->id);
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
            $proveedor = $this->obtenerProveedor();
            $tramite = $this->obtenerTramiteReciente($proveedor);
            $datosAdicionales = $this->obtenerDatosAdicionales($tramite);

            return view('tramites.estado', array_merge([
                'tramite' => $tramite,
                'estado' => $tramite->estado,
                'tramite_id' => $tramite->id,
            ], $datosAdicionales));

        } catch (\Exception $e) {
            Log::error('Error al cargar estado del trámite', [
                'user_id' => auth()->id(),
                'error' => $e->getMessage()
            ]);

            return redirect()
                ->route('tramites.index')
                ->with('error', 'Error al cargar el estado del trámite.');
        }
    }

    /**
     * Obtiene el proveedor del usuario autenticado
     */
    private function obtenerProveedor()
    {
        $proveedor = Proveedor::where('usuario_id', auth()->id())->first();

        if (!$proveedor) {
            throw new \Exception('No se encontró información del proveedor.');
        }

        return $proveedor;
    }

    /**
     * Obtiene el trámite más reciente del proveedor
     */
    private function obtenerTramiteReciente(Proveedor $proveedor)
    {
        $tramite = $proveedor->tramites()
            ->with(['proveedor.user', 'datosGenerales', 'oficios', 'cita'])
            ->orderBy('created_at', 'desc')
            ->first();

        if (!$tramite) {
            throw new \Exception('No se encontró ningún trámite.');
        }

        return $tramite;
    }

    /**
     * Obtiene datos adicionales según el estado del trámite
     */
    private function obtenerDatosAdicionales(Tramite $tramite): array
    {
        $datos = ['cita' => null, 'oficio' => null];

        // Obtener cita si el estado es Por_Cotejar
        if ($tramite->estado === 'Por_Cotejar') {
            $datos['cita'] = $tramite->cita;
        }

        // Obtener oficio si el estado es Aprobado
        if ($tramite->estado === 'Aprobado') {
            $datos['oficio'] = $tramite->oficios()
                ->orderBy('created_at', 'desc')
                ->first();
        }

        return $datos;
    }
}

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

    public function index()
    {
        $proveedor = $this->proveedorService->getProveedorByUser();
        return view('tramites.index', $this->tramiteService->getDatosTramitesIndex($proveedor));
    }

    public function constancia($tipo)
    {
        $proveedor = $this->proveedorService->getProveedorByUser();

        if (!$this->tramiteService->validarAccesoTramite($tipo, $proveedor)) {
            return redirect()
                ->route('tramites.index')
                ->with('error', 'No tiene permisos para acceder a este trámite.');
        }

        return view('tramites.constancia', $this->tramiteService->getDatosConstancia($tipo, $proveedor));
    }

    public function procesarConstancia(Request $request, $tipo)
    {
        $proveedor = $this->proveedorService->getProveedorByUser();

        if (!$this->tramiteService->validarAccesoTramite($tipo, $proveedor)) {
            return redirect()
                ->route('tramites.index')
                ->with('error', 'No tiene permisos para acceder a este trámite.');
        }

        try {
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

    public function formulario(Request $request, $tipo = 'inscripcion')
    {
        $proveedor = $this->proveedorService->getProveedorByUser();

        if (!$this->tramiteService->validarAccesoTramite($tipo, $proveedor)) {
            return redirect()
                ->route('tramites.index')
                ->with('error', 'No tiene permisos para acceder a este trámite.');
        }

        return view('tramites.formulario', $this->tramiteService->getDatosFormulario($tipo, $proveedor));
    }

    public function formularioSimple(Request $request, $tipo = 'inscripcion')
    {
        $proveedor = $this->proveedorService->getProveedorByUser();

        if (!$this->tramiteService->validarAccesoTramite($tipo, $proveedor)) {
            return redirect()
                ->route('tramites.index')
                ->with('error', 'No tiene permisos para acceder a este trámite.');
        }

        return view('tramites.formulario-simple', $this->tramiteService->getDatosFormulario($tipo, $proveedor));
    }

    public function store(TramiteFormRequest $request, $tipo)
    {
        try {
            $this->validarAutenticacion();
            $proveedor = $this->proveedorService->getProveedorByUser();
            $resultado = $this->tramiteService->procesarEnvioFormulario($request, $tipo, $proveedor);

            return $this->manejarRespuestaStore($request, $resultado);

        } catch (\Exception $e) {
            Log::error('Error al procesar formulario de trámite', [
                'error' => $e->getMessage(),
                'tipo' => $tipo,
                'user_id' => Auth::id()
            ]);

            return back()
                ->withInput()
                ->with('error', 'Error al procesar el trámite.');
        }
    }

    public function exito(Request $request)
    {
        $tramiteId = session('tramite_id');
        $mensaje = session('success', 'Su trámite ha sido enviado exitosamente.');

        return view('tramites.exito', [
            'tramite_id' => $tramiteId,
            'mensaje' => $mensaje
        ]);
    }

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

    private function validarAutenticacion(): void
    {
        if (!Auth::check()) {
            throw new \Exception('Debe iniciar sesión para continuar.');
        }
    }

    private function manejarRespuestaStore(Request $request, array $resultado)
    {
        if (!$resultado['success']) {
            return back()
                ->withInput()
                ->with('error', $resultado['message']);
        }

        $mensaje = $request->has('formulario_simple') 
            ? 'Trámite procesado exitosamente. Su trámite ha sido enviado y está en revisión.'
            : 'Trámite procesado exitosamente.';

        $ruta = $request->has('formulario_simple') 
            ? 'tramites.index' 
            : 'tramites.exito';

        $response = redirect()->route($ruta)->with('success', $mensaje);

        if (!$request->has('formulario_simple') && isset($resultado['tramite_id'])) {
            $response->with('tramite_id', $resultado['tramite_id']);
        }

        return $response;
    }

    private function validarPermisosCorreccion(Tramite $tramite): void
    {
        if ($tramite->proveedor->usuario_id !== Auth::id()) {
            abort(403, 'No tienes permisos para corregir este trámite.');
        }

        if ($tramite->estado !== 'Para_Correccion') {
            throw new \Exception('Este trámite no requiere correcciones.');
        }
    }

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

    private function obtenerProveedor()
    {
        $proveedor = Proveedor::where('usuario_id', auth()->id())->first();

        if (!$proveedor) {
            throw new \Exception('No se encontró información del proveedor.');
        }

        return $proveedor;
    }

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

    private function obtenerDatosAdicionales(Tramite $tramite): array
    {
        $datos = ['cita' => null, 'oficio' => null];

        if ($tramite->estado === 'Por_Cotejar') {
            $datos['cita'] = $tramite->cita;
        }

        if ($tramite->estado === 'Aprobado') {
            $datos['oficio'] = $tramite->oficios()
                ->orderBy('created_at', 'desc')
                ->first();
        }

        return $datos;
    }

    public function cancelar(Tramite $tramite)
    {
        try {
            // Validar que el usuario autenticado sea el propietario del trámite
            if ($tramite->proveedor->usuario_id !== Auth::id()) {
                abort(403, 'No tienes permisos para cancelar este trámite.');
            }

            // Validar que el trámite no esté ya cancelado o aprobado
            if (in_array($tramite->estado, ['Cancelado', 'Aprobado', 'Rechazado'])) {
                return redirect()
                    ->route('tramites.index')
                    ->with('error', 'No se puede cancelar un trámite que ya está ' . strtolower($tramite->estado) . '.');
            }

            // Cancelar el trámite
            $tramite->update([
                'estado' => 'Cancelado',
                'fecha_cancelacion' => now()
            ]);

            // Cancelar cita asociada si existe
            if ($tramite->cita) {
                $tramite->cita->update(['estado' => 'Cancelada']);
            }

            Log::info('Trámite cancelado por el usuario', [
                'tramite_id' => $tramite->id,
                'usuario_id' => Auth::id(),
                'estado_anterior' => $tramite->getOriginal('estado')
            ]);

            return redirect()
                ->route('tramites.index')
                ->with('success', 'Trámite cancelado exitosamente. Puede iniciar un nuevo trámite cuando lo desee.');

        } catch (\Exception $e) {
            Log::error('Error al cancelar trámite', [
                'tramite_id' => $tramite->id,
                'usuario_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);

            return redirect()
                ->route('tramites.index')
                ->with('error', 'Error al cancelar el trámite. Intente nuevamente.');
        }
    }

    public function historial()
    {
        try {
            $proveedor = $this->proveedorService->getProveedorByUser();
            
            if (!$proveedor) {
                return redirect()
                    ->route('tramites.index')
                    ->with('error', 'No se encontró información del proveedor.');
            }

            $tramites = $proveedor->tramites()
                ->with(['proveedor.user', 'datosGenerales', 'oficios', 'cita'])
                ->orderBy('created_at', 'desc')
                ->paginate(10);

            return view('tramites.historial', [
                'tramites' => $tramites,
                'proveedor' => $proveedor
            ]);

        } catch (\Exception $e) {
            Log::error('Error al cargar historial de trámites', [
                'usuario_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);

            return redirect()
                ->route('tramites.index')
                ->with('error', 'Error al cargar el historial de trámites.');
        }
    }

    public function datos(Tramite $tramite)
    {
        try {
            // Validar que el usuario autenticado sea el propietario del trámite
            if ($tramite->proveedor->usuario_id !== Auth::id()) {
                abort(403, 'No tienes permisos para ver este trámite.');
            }

            // Cargar todas las relaciones necesarias
            $tramite->load([
                'proveedor.user',
                'datosGenerales',
                'direccion',
                'datosConstitutivos',
                'apoderadoLegal',
                'accionistas',
                'archivos',
                'actividades'
            ]);

            return view('tramites.datos', [
                'tramite' => $tramite
            ]);

        } catch (\Exception $e) {
            Log::error('Error al cargar datos del trámite', [
                'tramite_id' => $tramite->id,
                'usuario_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);

            return redirect()
                ->route('tramites.historial')
                ->with('error', 'Error al cargar los datos del trámite.');
        }
    }
}

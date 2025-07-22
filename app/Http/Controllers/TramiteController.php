<?php

namespace App\Http\Controllers;

use App\Services\ProveedorService;
use App\Services\TramiteService;
use Illuminate\Http\Request;

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
     * Procesa el envío del formulario
     */
    public function store(Request $request, $tipo)
    {
        $proveedor = $this->proveedorService->getProveedorByUser();

        // Validar acceso al trámite
        if (! $this->tramiteService->validarAccesoTramite($tipo, $proveedor)) {
            return redirect()->route('tramites.index')
                ->with('error', 'No tiene permisos para realizar este trámite.');
        }

        // Procesar el formulario usando el servicio
        $resultado = $this->tramiteService->procesarEnvioFormulario($request, $tipo, $proveedor);

        if ($resultado['success']) {
            // Limpiar datos de sesión después del envío exitoso
            $this->tramiteService->limpiarDatosSesion();

            return redirect($resultado['redirect'])
                ->with('success', $resultado['message']);
        }

        return back()
            ->withInput()
            ->with('error', $resultado['message'] ?? 'Error al procesar el trámite.');
    }
}

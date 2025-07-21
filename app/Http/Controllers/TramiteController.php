<?php

namespace App\Http\Controllers;

use App\Models\Tramite;
use App\Models\Proveedor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\ProveedorService;

class TramiteController extends Controller
{
    protected $proveedorService;

    public function __construct(ProveedorService $proveedorService)
    {
        $this->middleware('auth');
        $this->proveedorService = $proveedorService;
    }

    /**
     * Muestra la página de selección de trámites
     */
    public function index()
    {
        $proveedor = $this->proveedorService->getProveedorByUser();
        $tramitesDisponibles = $this->proveedorService->determinarTramitesDisponibles($proveedor);

        return view('tramites.index', [
            'globalTramites' => $tramitesDisponibles,
            'proveedor' => $proveedor
        ]);
    }

    /**
     * Muestra la página de carga de constancia (primer paso)
     */
    public function constancia($tipo)
    {
        // Validar que el usuario pueda acceder al trámite
        $proveedor = $this->proveedorService->getProveedorByUser();
        $tramitesDisponibles = $this->proveedorService->determinarTramitesDisponibles($proveedor);

        // Verificar si el trámite está disponible
        if (!($tramitesDisponibles[$tipo] ?? false)) {
            return redirect()->route('tramites.index')
                ->with('error', 'No tiene permisos para acceder a este trámite.');
        }

        return view('tramites.constancia', [
            'tipo' => $tipo,
            'proveedor' => $proveedor
        ]);
    }

    /**
     * Procesa la constancia y guarda los datos del SAT en sesión
     */
    public function procesarConstancia(Request $request, $tipo)
    {
        // Validar nuevamente el acceso
        $proveedor = $this->proveedorService->getProveedorByUser();
        $tramitesDisponibles = $this->proveedorService->determinarTramitesDisponibles($proveedor);

        if (!($tramitesDisponibles[$tipo] ?? false)) {
            return redirect()->route('tramites.index')
                ->with('error', 'No tiene permisos para acceder a este trámite.');
        }

        // Guardar los datos del SAT en sesión
        session([
            'sat_rfc' => $request->sat_rfc,
            'sat_nombre' => $request->sat_nombre,
            'sat_tipo_persona' => $request->sat_tipo_persona,
            'sat_curp' => $request->sat_curp,
            'sat_cp' => $request->sat_cp,
            'sat_colonia' => $request->sat_colonia,
            'sat_nombre_vialidad' => $request->sat_nombre_vialidad,
            'sat_numero_exterior' => $request->sat_numero_exterior,
            'sat_numero_interior' => $request->sat_numero_interior,
        ]);

        return redirect()->route('tramites.formulario', $tipo);
    }

    /**
     * Muestra el formulario según el tipo de trámite (segundo paso)
     */
    public function formulario(Request $request, $tipo = 'inscripcion')
    {
        // Validar que el usuario pueda acceder al trámite
        $proveedor = $this->proveedorService->getProveedorByUser();
        $tramitesDisponibles = $this->proveedorService->determinarTramitesDisponibles($proveedor);

        // Verificar si el trámite está disponible
        if (!($tramitesDisponibles[$tipo] ?? false)) {
            return redirect()->route('tramites.index')
                ->with('error', 'No tiene permisos para acceder a este trámite.');
        }

        // Datos del SAT desde sesión para precargar
        $datosSat = [
            'rfc' => session('sat_rfc'),
            'razon_social' => session('sat_nombre'),
            'tipo_persona' => session('sat_tipo_persona'),
            'curp' => session('sat_curp'),
            'cp' => session('sat_cp'),
            'colonia' => session('sat_colonia'),
            'nombre_vialidad' => session('sat_nombre_vialidad'),
            'numero_exterior' => session('sat_numero_exterior'),
            'numero_interior' => session('sat_numero_interior'),
        ];

        $data = [
            'tipo_tramite' => $tipo,
            'proveedor' => $proveedor,
            'tramites' => $tramitesDisponibles,
            'titulo' => $this->getTituloTramite($tipo),
            'descripcion' => $this->getDescripcionTramite($tipo),
            'datosSat' => $datosSat
        ];

        return view('formularios.tramite', $data);
    }

    /**
     * Procesa el envío del formulario
     */
    public function store(Request $request, $tipo)
    {
        // Validar nuevamente el acceso
        $proveedor = $this->proveedorService->getProveedorByUser();
        $tramitesDisponibles = $this->proveedorService->determinarTramitesDisponibles($proveedor);

        if (!($tramitesDisponibles[$tipo] ?? false)) {
            return redirect()->route('tramites.index')
                ->with('error', 'No tiene permisos para realizar este trámite.');
        }

        // TODO: Implementar lógica de guardado según el tipo de trámite
        
        return redirect()->route('tramites.index')
            ->with('success', 'Trámite enviado correctamente.');
    }

    /**
     * Obtiene el título según el tipo de trámite
     */
    private function getTituloTramite($tipo): string
    {
        $titulos = [
            'inscripcion' => 'Inscripción al Padrón de Proveedores',
            'renovacion' => 'Renovación de Registro',
            'actualizacion' => 'Actualización de Datos'
        ];

        return $titulos[$tipo] ?? 'Formulario de Trámite';
    }

    /**
     * Obtiene la descripción según el tipo de trámite
     */
    private function getDescripcionTramite($tipo): string
    {
        $descripciones = [
            'inscripcion' => 'Complete todos los campos requeridos para su inscripción inicial.',
            'renovacion' => 'Actualice y confirme sus datos para la renovación anual.',
            'actualizacion' => 'Modifique únicamente los campos que requieren actualización.'
        ];

        return $descripciones[$tipo] ?? 'Complete el formulario correspondiente.';
    }
}

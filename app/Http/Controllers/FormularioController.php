<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ProveedorService;
use Illuminate\Support\Facades\Auth;

class FormularioController extends Controller
{
    protected $proveedorService;

    public function __construct(ProveedorService $proveedorService)
    {
        $this->middleware('auth');
        $this->proveedorService = $proveedorService;
    }

    /**
     * Muestra el formulario según el tipo de trámite
     */
    public function index(Request $request, $tipo = 'inscripcion')
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

        return view('formularios.index', $data);
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

    public function constancia($tipo)
    {
        $proveedor = $this->proveedorService->getProveedorByUser();
        return view('formularios.constancia', [
            'tipo' => $tipo,
            'proveedor' => $proveedor
        ]);
    }

    public function procesarConstancia(Request $request, $tipo)
    {
        // Guarda los datos del SAT en sesión
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
        return redirect()->route('formularios.index', $tipo);
    }

   
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
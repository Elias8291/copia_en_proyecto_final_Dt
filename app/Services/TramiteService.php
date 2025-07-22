<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Proveedor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class TramiteService
{
    protected $proveedorService;

    public function __construct(ProveedorService $proveedorService)
    {
        $this->proveedorService = $proveedorService;
    }

    /**
     * Obtiene los datos necesarios para la vista de selección de trámites
     */
    public function getDatosTramitesIndex(?Proveedor $proveedor): array
    {
        $tramitesDisponibles = $this->proveedorService->determinarTramitesDisponibles($proveedor);

        return [
            'globalTramites' => $tramitesDisponibles,
            'proveedor' => $proveedor,
        ];
    }

    /**
     * Valida el acceso a un trámite específico
     */
    public function validarAccesoTramite(string $tipo, ?Proveedor $proveedor): bool
    {
        $tramitesDisponibles = $this->proveedorService->determinarTramitesDisponibles($proveedor);

        return $tramitesDisponibles[$tipo] ?? false;
    }

    /**
     * Obtiene los datos para la vista de constancia
     */
    public function getDatosConstancia(string $tipo, ?Proveedor $proveedor): array
    {
        return [
            'tipo' => $tipo,
            'proveedor' => $proveedor,
        ];
    }

    /**
     * Procesa los datos de la constancia SAT y los guarda en sesión
     */
    public function procesarDatosConstancia(Request $request): void
    {
        Session::put([
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
    }

    /**
     * Obtiene los datos del SAT desde la sesión
     */
    public function getDatosSatDeSesion(): array
    {
        return [
            'rfc' => Session::get('sat_rfc'),
            'razon_social' => Session::get('sat_nombre'),
            'tipo_persona' => Session::get('sat_tipo_persona'),
            'curp' => Session::get('sat_curp'),
            'cp' => Session::get('sat_cp'),
            'colonia' => Session::get('sat_colonia'),
            'nombre_vialidad' => Session::get('sat_nombre_vialidad'),
            'numero_exterior' => Session::get('sat_numero_exterior'),
            'numero_interior' => Session::get('sat_numero_interior'),
        ];
    }

    /**
     * Obtiene los datos completos para el formulario de trámite
     */
    public function getDatosFormulario(string $tipo, ?Proveedor $proveedor): array
    {
        $tramitesDisponibles = $this->proveedorService->determinarTramitesDisponibles($proveedor);
        $datosSat = $this->getDatosSatDeSesion();

        return [
            'tipo_tramite' => $tipo,
            'proveedor' => $proveedor,
            'tramites' => $tramitesDisponibles,
            'titulo' => $this->getTituloTramite($tipo),
            'descripcion' => $this->getDescripcionTramite($tipo),
            'datosSat' => $datosSat,
        ];
    }

    /**
     * Procesa el envío del formulario de trámite
     */
    public function procesarEnvioFormulario(Request $request, string $tipo, ?Proveedor $proveedor): array
    {
        // TODO: Implementar lógica de guardado según el tipo de trámite
        // Por ahora solo simulamos el éxito

        return [
            'success' => true,
            'message' => 'Trámite enviado correctamente.',
            'redirect' => route('tramites.index'),
        ];
    }

    /**
     * Obtiene el título según el tipo de trámite
     */
    public function getTituloTramite(string $tipo): string
    {
        $titulos = [
            'inscripcion' => 'Inscripción al Padrón de Proveedores',
            'renovacion' => 'Renovación de Registro',
            'actualizacion' => 'Actualización de Datos',
        ];

        return $titulos[$tipo] ?? 'Formulario de Trámite';
    }

    /**
     * Obtiene la descripción según el tipo de trámite
     */
    public function getDescripcionTramite(string $tipo): string
    {
        $descripciones = [
            'inscripcion' => 'Complete todos los campos requeridos para su inscripción inicial.',
            'renovacion' => 'Actualice y confirme sus datos para la renovación anual.',
            'actualizacion' => 'Modifique únicamente los campos que requieren actualización.',
        ];

        return $descripciones[$tipo] ?? 'Complete el formulario correspondiente.';
    }

    /**
     * Limpia los datos de sesión después del procesamiento
     */
    public function limpiarDatosSesion(): void
    {
        $keys = [
            'sat_rfc', 'sat_nombre', 'sat_tipo_persona', 'sat_curp',
            'sat_cp', 'sat_colonia', 'sat_nombre_vialidad',
            'sat_numero_exterior', 'sat_numero_interior',
        ];

        foreach ($keys as $key) {
            Session::forget($key);
        }
    }
}

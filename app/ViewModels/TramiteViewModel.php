<?php

namespace App\ViewModels;

use App\Services\Tramites\ConstanciaService;
use App\Services\RfcProveedorService;

class TramiteViewModel
{
    private array $datosConstancia;
    private ConstanciaService $constanciaService;
    private RfcProveedorService $rfcProveedorService;

    public function __construct(array $datosConstancia = [])
    {
        $this->datosConstancia = $datosConstancia;
        $this->constanciaService = app(ConstanciaService::class);
        $this->rfcProveedorService = app(RfcProveedorService::class);
    }

    public function getDatosFinales(array $datos = []): array
    {
        if (empty($this->datosConstancia)) {
            return $datos;
        }

        return [
            'razon_social' => $this->datosConstancia['razon_social'] ?? ($datos['razon_social'] ?? ''),
            'rfc' => $this->datosConstancia['rfc'] ?? ($datos['rfc'] ?? ''),
            'tipo_persona' => $this->datosConstancia['tipo_persona'] ?? ($datos['tipo_persona'] ?? ''),
            'curp' => $this->datosConstancia['curp'] ?? ($datos['curp'] ?? ''),
            'domicilio' => $this->getDatosDomicilio($datos['domicilio'] ?? []),
        ];
    }

    private function getDatosDomicilio(array $datos = []): array
    {
        if (empty($this->datosConstancia['domicilio'])) {
            return $datos;
        }

        return [
            'codigo_postal' => $this->datosConstancia['domicilio']['codigo_postal'] ?? ($datos['codigo_postal'] ?? ''),
            'estado' => $this->datosConstancia['domicilio']['entidad_federativa'] ?? ($datos['estado'] ?? ''),
            'municipio' => $this->datosConstancia['domicilio']['municipio'] ?? ($datos['municipio'] ?? ''),
            'asentamiento' => $this->datosConstancia['domicilio']['colonia'] ?? ($datos['asentamiento'] ?? ''),
            'calle' => $this->datosConstancia['domicilio']['calle'] ?? ($datos['calle'] ?? ''),
            'numero_exterior' => $this->datosConstancia['domicilio']['numero_exterior'] ?? ($datos['numero_exterior'] ?? ''),
            'numero_interior' => $this->datosConstancia['domicilio']['numero_interior'] ?? ($datos['numero_interior'] ?? ''),
            // Los campos entre_calle y y_calle SOLO vienen de los datos del formulario, nunca de la constancia
            'entre_calle' => !empty($datos['entre_calle']) ? $datos['entre_calle'] : '',
            'y_calle' => !empty($datos['y_calle']) ? $datos['y_calle'] : '',
        ];
    }

    public function sonCamposEditables(): bool
    {
        return empty($this->datosConstancia);
    }

    public function determinarTipoPersona(string $rfc): string
    {
        return $this->rfcProveedorService->determinarTipoPersona($rfc);
    }

    public function getDatosGenerales(array $datos = []): array
    {
        $datosFinales = $this->getDatosFinales($datos);
        
        return [
            'razon_social' => $datosFinales['razon_social'],
            'rfc' => $datosFinales['rfc'],
            'tipo_persona' => $this->determinarTipoPersona($datosFinales['rfc']),
            'curp' => $datosFinales['curp'],
            'pagina_web' => $datos['pagina_web'] ?? '',
            'telefono' => $datos['telefono'] ?? '',
            'correo_electronico' => $datos['correo_electronico'] ?? '',
        ];
    }

    public function getDatosDomicilioForm(array $datos = []): array
    {
        return $this->getDatosDomicilio($datos);
    }
} 
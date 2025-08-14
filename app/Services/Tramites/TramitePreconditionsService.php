<?php

namespace App\Services\Tramites;

use App\Services\RfcProveedorService;
use InvalidArgumentException;
use RuntimeException;

class TramitePreconditionsService
{
    private RfcProveedorService $rfcProveedorService;

    public function __construct(RfcProveedorService $rfcProveedorService)
    {
        $this->rfcProveedorService = $rfcProveedorService;
    }

    public function validarTipoTramite(?string $tipo): void
    {
        if (!$tipo) {
            throw new InvalidArgumentException('Debe seleccionar un tipo de trámite');
        }

        $tiposValidos = ['inscripcion', 'renovacion', 'actualizacion'];
        if (!in_array($tipo, $tiposValidos)) {
            throw new InvalidArgumentException('Tipo de trámite no válido');
        }
    }

    public function obtenerRfcAutenticadoOrFail(): string
    {
        $rfc = $this->rfcProveedorService->obtenerRfcUsuario();
        if (!$rfc) {
            throw new RuntimeException('Usuario sin RFC configurado');
        }
        return $rfc;
    }

    public function asegurarSinTramitePendiente(string $rfc): void
    {
        $tramitePendiente = $this->rfcProveedorService->obtenerTramitePendiente($rfc);
        if ($tramitePendiente) {
            throw new RuntimeException('Tiene un trámite en proceso. Consulte el estado de su trámite actual antes de iniciar uno nuevo.');
        }
    }

    public function validarDisponibilidadTipo(string $rfc, string $tipo): void
    {
        $accion = $this->rfcProveedorService->determinarAccionPorTipoTramite($rfc, $tipo);

        $tramites = [
            'inscripcion' => ['activo' => in_array($accion['accion'], ['crear_nuevo'])],
            'renovacion' => ['activo' => in_array($accion['accion'], ['renovar_vencido', 'renovar_existente'])],
            'actualizacion' => ['activo' => $accion['accion'] === 'actualizar_existente']
        ];

        if (!($tramites[$tipo]['activo'] ?? false)) {
            throw new RuntimeException('No puede realizar este tipo de trámite: ' . ($accion['motivo'] ?? 'No disponible'));
        }
    }

    public function prepararDatosVistaCreate(string $rfc, string $tipo): array
    {
        $tipoPersona = $this->rfcProveedorService->determinarTipoPersona($rfc);
        $archivosRequeridos = $this->rfcProveedorService->obtenerArchivosPorTipoPersona($rfc);
        $infoProveedor = $this->rfcProveedorService->obtenerInfoGestionProveedor($rfc, $tipo);

        return [
            'tipoPersona' => $tipoPersona,
            'archivosRequeridos' => $archivosRequeridos,
            'infoProveedor' => $infoProveedor,
        ];
    }
}



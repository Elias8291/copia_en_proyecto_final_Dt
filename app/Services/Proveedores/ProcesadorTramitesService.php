<?php

declare(strict_types=1);

namespace App\Services\Proveedores;

use App\Models\Proveedor;
use App\Models\Tramite;
use Illuminate\Support\Facades\Auth;

/**
 * Servicio especializado para procesamiento de trámites de proveedores
 * Responsabilidad: Lógica específica de inscripción, renovación y actualización
 */
class ProcesadorTramitesService
{
    public function __construct(
        private NumerosPvService $numerosPvService
    ) {}

    /**
     * Procesar trámite según su tipo
     */
    public function procesarTramite(Tramite $tramite): array
    {
        $proveedor = $tramite->proveedor;
        $tipoTramite = $tramite->tipo_tramite;

        // Actualizar estado del trámite
        $tramite->update([
            'estado' => 'Aprobado',
            'fecha_aprobacion' => now(),
            'revisado_por' => Auth::id()
        ]);

        return match ($tipoTramite) {
            'Inscripcion' => $this->procesarInscripcion($proveedor),
            'Renovacion' => $this->procesarRenovacion($proveedor),
            'Actualizacion' => $this->procesarActualizacion($proveedor),
            default => [
                'success' => false,
                'message' => 'Tipo de trámite no reconocido'
            ]
        };
    }

    /**
     * Procesar inscripción: asignar nuevo PV y fecha de alta
     */
    public function procesarInscripcion(Proveedor $proveedor): array
    {
        $nuevoPV = $this->numerosPvService->generarNuevoPv();
        $fechaActual = now();
        
        $proveedor->update([
            'pv_numero' => $nuevoPV,
            'estado_padron' => 'Activo',
            'alta_al_padron' => $fechaActual,
            'fecha_vencimiento_padron' => $fechaActual->copy()->addYear(),
            'fecha_actualizacion' => $fechaActual
        ]);

        return [
            'success' => true,
            'message' => 'Inscripción aprobada exitosamente',
            'pv_asignado' => $nuevoPV,
            'fecha_vencimiento' => $proveedor->fecha_vencimiento_padron->format('Y-m-d')
        ];
    }

    /**
     * Procesar renovación: mantener PV, actualizar fecha de vencimiento
     */
    public function procesarRenovacion(Proveedor $proveedor): array
    {
        $fechaVencimientoOriginal = $proveedor->fecha_vencimiento_padron;
        $nuevaFechaVencimiento = $fechaVencimientoOriginal->copy()->addYear();

        $proveedor->update([
            'estado_padron' => 'Activo',
            'fecha_vencimiento_padron' => $nuevaFechaVencimiento,
            'fecha_actualizacion' => now()
        ]);

        return [
            'success' => true,
            'message' => 'Renovación aprobada exitosamente',
            'pv_asignado' => $proveedor->pv_numero,
            'fecha_vencimiento' => $nuevaFechaVencimiento->format('Y-m-d')
        ];
    }

    /**
     * Procesar actualización: mantener PV y fecha de vencimiento
     */
    public function procesarActualizacion(Proveedor $proveedor): array
    {
        $proveedor->update([
            'estado_padron' => 'Activo',
            'fecha_actualizacion' => now()
        ]);

        return [
            'success' => true,
            'message' => 'Actualización aprobada exitosamente',
            'pv_asignado' => $proveedor->pv_numero,
            'fecha_vencimiento' => $proveedor->fecha_vencimiento_padron?->format('Y-m-d')
        ];
    }

    /**
     * Obtener tipos de trámites disponibles para un proveedor
     */
    public function obtenerTiposDisponibles(Proveedor $proveedor): array
    {
        $estado = ucfirst(strtolower($proveedor?->estado_padron ?? ''));

        return match ($estado) {
            'Pendiente', 'Vencido', 'Inactivo' => [
                'disponibles' => ['Inscripcion'],
                'mensaje' => "Su registro está {$estado}. Debe realizar el proceso de inscripción."
            ],
            'Activo' => $this->obtenerTiposParaActivo($proveedor),
            default => [
                'disponibles' => ['Inscripcion'],
                'mensaje' => 'No reconocemos el estado de su registro. Por favor, inicie el proceso de inscripción.'
            ]
        };
    }

    /**
     * Obtener tipos disponibles para proveedor activo
     */
    private function obtenerTiposParaActivo(Proveedor $proveedor): array
    {
        $estadosService = app(EstadosProveedorService::class);
        
        if ($estadosService->estaEnPeriodoRenovacion($proveedor)) {
            return [
                'disponibles' => ['Renovacion'],
                'mensaje' => 'Su registro está próximo a vencer. Por favor, realice la renovación.'
            ];
        }

        return [
            'disponibles' => ['Actualizacion'],
            'mensaje' => 'Su registro se encuentra activo. Puede actualizar su información si lo necesita.'
        ];
    }
}
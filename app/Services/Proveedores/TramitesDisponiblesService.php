<?php

declare(strict_types=1);

namespace App\Services\Proveedores;

use App\Models\Proveedor;

/**
 * Servicio especializado para determinar trámites disponibles
 * Responsabilidad: Lógica de disponibilidad de trámites según estado del proveedor
 */
class TramitesDisponiblesService
{
    public function __construct(
        private EstadosProveedorService $estadosService
    ) {}

    /**
     * Determinar trámites disponibles para un proveedor
     */
    public function determinar(?Proveedor $proveedor): array
    {
        $defaults = $this->obtenerEstructuraBase();

        // Si no hay proveedor, solo inscripción disponible
        if (!$proveedor) {
            return array_merge($defaults, [
                'inscripcion' => true,
                'message' => 'Para comenzar, realice su inscripción al padrón de proveedores.'
            ]);
        }

        // Verificar trámites pendientes
        if ($this->tieneTramitesPendientes($proveedor)) {
            return array_merge($defaults, [
                'tiene_tramite_pendiente' => true,
                'tramite_pendiente' => $this->obtenerDetallesTramitePendiente($proveedor)
            ]);
        }

        // Determinar según estado del proveedor
        $defaults['estado_vigencia'] = $this->estadosService->obtenerEstadoVigencia($proveedor);
        $estado = ucfirst(strtolower($proveedor->estado_padron ?? ''));

        return array_merge($defaults, $this->procesarSegunEstado($estado, $proveedor));
    }

    /**
     * Estructura base de respuesta
     */
    private function obtenerEstructuraBase(): array
    {
        return [
            'inscripcion' => false,
            'renovacion' => false,
            'actualizacion' => false,
            'is_administrative' => false,
            'message' => '',
            'estado_vigencia' => null,
            'tiene_tramite_pendiente' => false,
            'tramite_pendiente' => null,
        ];
    }

    /**
     * Procesar según estado del proveedor
     */
    private function procesarSegunEstado(string $estado, Proveedor $proveedor): array
    {
        return match ($estado) {
            'Pendiente', 'Vencido', 'Inactivo' => [
                'inscripcion' => true,
                'message' => "Su registro está " . strtolower($estado) . ". Debe realizar el proceso de inscripción."
            ],
            'Activo' => $this->procesarProveedorActivo($proveedor),
            default => [
                'inscripcion' => true,
                'message' => 'No reconocemos el estado de su registro. Por favor, inicie el proceso de inscripción.'
            ]
        };
    }

    /**
     * Procesar proveedor con estado activo
     */
    private function procesarProveedorActivo(Proveedor $proveedor): array
    {
        if ($this->estadosService->estaEnPeriodoRenovacion($proveedor)) {
            return [
                'renovacion' => true,
                'message' => 'Su registro está próximo a vencer. Por favor, realice la renovación.'
            ];
        }

        return [
            'actualizacion' => true,
            'message' => 'Su registro se encuentra activo. Puede actualizar su información si lo necesita.'
        ];
    }

    /**
     * Verificar si tiene trámites pendientes
     */
    private function tieneTramitesPendientes(Proveedor $proveedor): bool
    {
        return $proveedor->tramites()
            ->whereIn('estado', [
                'Para_Correccion', 'Por_Cotejar', 'Cancelado', 
                'En_Revision', 'Pendiente', 'Enviado', 'Rechazado'
            ])
            ->exists();
    }

    /**
     * Obtener detalles del trámite pendiente
     */
    private function obtenerDetallesTramitePendiente(Proveedor $proveedor): ?array
    {
        $tramite = $proveedor->tramites()
            ->whereIn('estado', [
                'Para_Correccion', 'Por_Cotejar', 'Cancelado',
                'En_Revision', 'Pendiente', 'Enviado', 'Rechazado'
            ])
            ->with(['proveedor', 'proveedor.user', 'datosGenerales', 'apoderadoLegal', 'oficios', 'cita'])
            ->latest()
            ->first();

        if (!$tramite) {
            return null;
        }

        $informacionEstado = $this->estadosService->obtenerInformacionCompleta($tramite->estado);

        return [
            'tramite' => $tramite,
            'dias_transcurridos' => $tramite->created_at->diffInDays(now()),
            'estado_color' => $informacionEstado['color'],
            'estado_descripcion' => $informacionEstado['descripcion'],
            'siguiente_paso' => $informacionEstado['siguiente_paso'],
            'puede_editar' => in_array($tramite->estado, ['Para_Correccion', 'Pendiente']),
            'cita' => $this->obtenerInformacionCita($tramite)
        ];
    }

    /**
     * Obtener información de cita si aplica
     */
    private function obtenerInformacionCita($tramite): ?array
    {
        if ($tramite->estado !== 'Por_Cotejar' || !$tramite->cita) {
            return null;
        }

        $cita = $tramite->cita;
        return [
            'id' => $cita->id,
            'fecha_cita' => $cita->fecha_cita,
            'tipo_cita' => $cita->tipo_cita,
            'estado' => $cita->estado,
            'motivo' => $cita->motivo,
            'observaciones' => $cita->observaciones
        ];
    }
}
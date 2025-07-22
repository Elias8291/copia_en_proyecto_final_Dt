<?php

namespace App\Services;

use App\Models\Proveedor;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ProveedorService
{
    /**
     * Get the proveedor associated with the authenticated user
     */
    public function getProveedorByUser(): ?Proveedor
    {
        $user = Auth::user();

        if (! $user instanceof User) {
            return null;
        }

        try {
            return $user->proveedor()->first();
        } catch (\Exception $e) {
            \Log::error('Error al obtener proveedor: '.$e->getMessage());

            return null;
        }
    }

    public function determinarTramitesDisponibles($proveedor): array
    {
        $defaults = [
            'inscripcion' => false,
            'renovacion' => false,
            'actualizacion' => false,
            'is_administrative' => false,
            'message' => '',
            'estado_vigencia' => null,
        ];

        if (! $proveedor) {
            $defaults['inscripcion'] = true;
            $defaults['message'] = 'Para comenzar, realice su inscripción al padrón de proveedores.';

            return $defaults;
        }

        $defaults['estado_vigencia'] = $this->getEstadoVigencia($proveedor);
        $estado = ucfirst(strtolower($proveedor?->estado_padron ?? ''));

        switch ($estado) {
            case 'Pendiente':
            case 'Vencido':
            case 'Inactivo':
                $defaults['inscripcion'] = true;
                $defaults['message'] = 'Su registro está '.strtolower($estado).'. Debe realizar el proceso de inscripción.';
                break;

            case 'Activo':
                if ($this->estaEnPeriodoRenovacion($proveedor)) {
                    $defaults['renovacion'] = true;
                    $defaults['message'] = 'Su registro está próximo a vencer. Por favor, realice la renovación.';
                } else {
                    $defaults['actualizacion'] = true;
                    $defaults['message'] = 'Su registro se encuentra activo. Puede actualizar su información si lo necesita.';
                }
                break;

            default:
                $defaults['inscripcion'] = true;
                $defaults['message'] = 'No reconocemos el estado de su registro. Por favor, inicie el proceso de inscripción.';
                break;
        }

        return $defaults;
    }

    /**
     * Verifica si el usuario autenticado tiene un proveedor asociado en estado Activo
     */
    public function hasActiveProveedor(): bool
    {
        $proveedor = $this->getProveedorByUser();

        return $proveedor?->estado_padron === 'Activo';
    }

    /**
     * Obtiene el estado de vigencia del proveedor
     */
    private function getEstadoVigencia($proveedor): ?string
    {
        if (! $proveedor || ! $proveedor->fecha_vencimiento_padron) {
            return null;
        }

        $hoy = now()->toDateString();
        $vencimiento = $proveedor->fecha_vencimiento_padron->toDateString();

        if ($vencimiento < $hoy) {
            return 'vencido';
        }

        $diasParaVencer = now()->diffInDays($proveedor->fecha_vencimiento_padron, false);

        if ($diasParaVencer <= 7) {
            return 'por_vencer';
        }

        return 'vigente';
    }

    /**
     * Verifica si el proveedor está en período de renovación (30 días antes del vencimiento)
     */
    private function estaEnPeriodoRenovacion($proveedor): bool
    {
        if (! $proveedor || ! $proveedor->fecha_vencimiento_padron || $proveedor?->estado_padron !== 'Activo') {
            return false;
        }

        $diasParaVencer = now()->diffInDays($proveedor->fecha_vencimiento_padron, false);

        return $diasParaVencer <= 7 && $diasParaVencer >= 0;
    }
}

<?php

namespace App\Services;

use App\Models\Proveedor;
use App\Models\User;
use App\Models\Tramite;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

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
            Log::error('Error al obtener proveedor: '.$e->getMessage());

            return null;
        }
    }

    /**
     * Verificar si el proveedor tiene trámites pendientes
     */
    public function tieneTramitesPendientes(?Proveedor $proveedor): bool
    {
        if (!$proveedor) {
            return false;
        }

        return Tramite::where('proveedor_id', $proveedor->id)
            ->whereIn('estado', ['Para_Correccion', 'Por_Cotejar', 'Cancelado', 'En_Revision', 'Pendiente', 'Enviado', 'Rechazado', 'Aprobado'])
            ->exists();
    }

    /**
     * Obtener el trámite pendiente más reciente del proveedor
     */
    public function getTramitePendiente(?Proveedor $proveedor): ?Tramite
    {
        if (!$proveedor) {
            return null;
        }

        return Tramite::where('proveedor_id', $proveedor->id)
            ->whereIn('estado', ['Para_Correccion', 'Por_Cotejar', 'Cancelado', 'En_Revision', 'Pendiente', 'Enviado', 'Rechazado', 'Aprobado'])
            ->latest()
            ->first();
    }

    /**
     * Obtener información detallada del trámite pendiente
     */
    public function getDetallesTramitePendiente(?Proveedor $proveedor): ?array
    {
        $tramite = $this->getTramitePendiente($proveedor);
        
        if (!$tramite) {
            return null;
        }

        return [
            'tramite' => $tramite,
            'dias_transcurridos' => $tramite->created_at->diffInDays(now()),
            'estado_color' => $this->getEstadoColor($tramite->estado),
            'estado_descripcion' => $this->getEstadoDescripcion($tramite->estado),
            'siguiente_paso' => $this->getSiguientePaso($tramite->estado),
            'puede_editar' => in_array($tramite->estado, ['Para_Correccion', 'Pendiente'])
        ];
    }

    /**
     * Obtener el color CSS para el estado del trámite
     */
    private function getEstadoColor(string $estado): string
    {
        return match($estado) {
            'Pendiente' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
            'En_Revision' => 'bg-blue-100 text-blue-800 border-blue-200',
            'Para_Correccion' => 'bg-amber-100 text-amber-800 border-amber-200',
            'Por_Cotejar' => 'bg-purple-100 text-purple-800 border-purple-200',
            'Cancelado' => 'bg-gray-100 text-gray-800 border-gray-200',
            'Enviado' => 'bg-blue-100 text-blue-800 border-blue-200',
            'Aprobado' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
            'Rechazado' => 'bg-red-100 text-red-800 border-red-200',
            default => 'bg-gray-100 text-gray-800 border-gray-200'
        };
    }

    /**
     * Obtener descripción amigable del estado
     */
    private function getEstadoDescripcion(string $estado): string
    {
        return match($estado) {
            'Pendiente' => 'Su trámite está en revisión por el equipo del padrón de proveedores.',
            'En_Revision' => 'Su expediente está siendo revisado minuciosamente por nuestro equipo técnico.',
            'Para_Correccion' => 'Su trámite requiere correcciones. Revise las observaciones del equipo administrativo.',
            'Por_Cotejar' => 'Su documentación está siendo cotejada y verificada por nuestro equipo especializado.',
            'Cancelado' => 'Su trámite ha sido cancelado. Puede consultar los motivos o iniciar un nuevo proceso.',
            'Enviado' => 'Su trámite ha sido enviado y está pendiente de revisión inicial.',
            'Aprobado' => 'Su trámite ha sido aprobado exitosamente.',
            'Rechazado' => 'Su trámite ha sido rechazado. Revise las observaciones.',
            default => 'Estado del trámite no reconocido.'
        };
    }

    /**
     * Obtener el siguiente paso sugerido
     */
    private function getSiguientePaso(string $estado): string
    {
        return match($estado) {
            'Pendiente' => 'Espere a que un especialista revise su solicitud. Le notificaremos cualquier actualización.',
            'En_Revision' => 'Su trámite está siendo evaluado minuciosamente. Manténgase atento a las notificaciones.',
            'Para_Correccion' => 'Revise las observaciones específicas y corrija la información señalada antes de reenviar.',
            'Por_Cotejar' => 'Su documentación está en proceso de verificación. Este proceso puede tomar algunos días.',
            'Cancelado' => 'Contacte al área de soporte para conocer los motivos o inicie un nuevo proceso.',
            'Enviado' => 'Su trámite ha sido recibido y será asignado a un especialista para revisión.',
            'Aprobado' => 'Su trámite ha sido completado exitosamente.',
            'Rechazado' => 'Revise las observaciones y puede iniciar un nuevo proceso si es necesario.',
            default => 'Contacte al área de soporte para más información.'
        };
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
            'tiene_tramite_pendiente' => false,
            'tramite_pendiente' => null,
        ];

        // Verificar si tiene trámites pendientes
        if ($this->tieneTramitesPendientes($proveedor)) {
            $defaults['tiene_tramite_pendiente'] = true;
            $defaults['tramite_pendiente'] = $this->getDetallesTramitePendiente($proveedor);
            return $defaults;
        }

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

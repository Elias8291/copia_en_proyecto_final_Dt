<?php

declare(strict_types=1);

namespace App\Services\Proveedores;

use App\Models\Proveedor;

/**
 * Servicio especializado para manejo de estados de proveedores
 * Responsabilidad: Lógica de estados, colores, descripciones y vigencias
 */
class EstadosProveedorService
{
    /**
     * Obtener el color CSS para el estado del trámite
     */
    public function obtenerColorEstado(string $estado): string
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
    public function obtenerDescripcionEstado(string $estado): string
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
    public function obtenerSiguientePaso(string $estado): string
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

    /**
     * Obtiene el estado de vigencia del proveedor
     */
    public function obtenerEstadoVigencia(?Proveedor $proveedor): ?string
    {
        if (!$proveedor || !$proveedor->fecha_vencimiento_padron) {
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
     * Verifica si el proveedor está en período de renovación (7 días antes del vencimiento)
     */
    public function estaEnPeriodoRenovacion(?Proveedor $proveedor): bool
    {
        if (!$proveedor || !$proveedor->fecha_vencimiento_padron || $proveedor?->estado_padron !== 'Activo') {
            return false;
        }

        $diasParaVencer = now()->diffInDays($proveedor->fecha_vencimiento_padron, false);

        return $diasParaVencer <= 7 && $diasParaVencer >= 0;
    }

    /**
     * Obtener información completa del estado
     */
    public function obtenerInformacionCompleta(string $estado): array
    {
        return [
            'estado' => $estado,
            'color' => $this->obtenerColorEstado($estado),
            'descripcion' => $this->obtenerDescripcionEstado($estado),
            'siguiente_paso' => $this->obtenerSiguientePaso($estado)
        ];
    }
}
<?php

namespace App\Enums;

enum TramiteStatus: string
{
    case PENDIENTE = 'Pendiente';
    case REVISION_DIGITAL = 'Revision_Digital';
    case REVISION_PRESENCIAL = 'Revision_Presencial';
    case REVISION_DOMICILIARIA = 'Revision_Domiciliaria';
    case APROBADO = 'Aprobado';
    case RECHAZADO = 'Rechazado';
    case PARA_CORRECCION = 'Para_Correccion';
    case CANCELADO = 'Cancelado';

    /**
     * Obtener el label legible del estado
     */
    public function label(): string
    {
        return match($this) {
            self::PENDIENTE => 'Pendiente',
            self::REVISION_DIGITAL => 'Revisión Digital',
            self::REVISION_PRESENCIAL => 'Revisión Presencial',
            self::REVISION_DOMICILIARIA => 'Revisión Domiciliaria',
            self::APROBADO => 'Aprobado',
            self::RECHAZADO => 'Rechazado',
            self::PARA_CORRECCION => 'Para Corrección',
            self::CANCELADO => 'Cancelado',
        };
    }

    /**
     * Obtener todos los estados como array
     */
    public static function toArray(): array
    {
        $estados = [];
        foreach (self::cases() as $estado) {
            $estados[$estado->value] = $estado->label();
        }
        return $estados;
    }

    /**
     * Verificar si el trámite está en revisión
     */
    public function estaEnRevision(): bool
    {
        return in_array($this, [
            self::REVISION_DIGITAL,
            self::REVISION_PRESENCIAL,
            self::REVISION_DOMICILIARIA
        ]);
    }

    /**
     * Verificar si el trámite está finalizado
     */
    public function estaFinalizado(): bool
    {
        return in_array($this, [
            self::APROBADO,
            self::RECHAZADO,
            self::CANCELADO
        ]);
    }

    /**
     * Obtener color CSS para el estado
     */
    public function color(): string
    {
        return match($this) {
            self::PENDIENTE => 'bg-gray-100 text-gray-800',
            self::REVISION_DIGITAL => 'bg-blue-100 text-blue-800',
            self::REVISION_PRESENCIAL => 'bg-purple-100 text-purple-800',
            self::REVISION_DOMICILIARIA => 'bg-indigo-100 text-indigo-800',
            self::APROBADO => 'bg-green-100 text-green-800',
            self::RECHAZADO => 'bg-red-100 text-red-800',
            self::PARA_CORRECCION => 'bg-orange-100 text-orange-800',
            self::CANCELADO => 'bg-gray-100 text-gray-800',
        };
    }
} 
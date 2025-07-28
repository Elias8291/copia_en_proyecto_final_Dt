<?php

namespace App\Helpers;

use Carbon\Carbon;

class TiempoHelper
{
    /**
     * Formatea una fecha para mostrar en formato legible
     */
    public static function formatearFecha($fecha, $formato = 'd/m/Y H:i')
    {
        if (!$fecha) {
            return 'N/A';
        }

        try {
            return Carbon::parse($fecha)->format($formato);
        } catch (\Exception $e) {
            return 'Fecha inválida';
        }
    }

    /**
     * Calcula la diferencia de tiempo entre dos fechas
     */
    public static function calcularDiferencia($fechaInicio, $fechaFin = null)
    {
        if (!$fechaInicio) {
            return 'N/A';
        }

        $fechaFin = $fechaFin ?: now();
        
        try {
            $inicio = Carbon::parse($fechaInicio);
            $fin = Carbon::parse($fechaFin);
            
            $diferencia = $inicio->diffForHumans($fin, true);
            
            return $diferencia;
        } catch (\Exception $e) {
            return 'Error en cálculo';
        }
    }

    /**
     * Calcula el tipo de persona basado en el RFC
     * RFC de 13 caracteres = Persona Física
     * RFC de 12 caracteres = Persona Moral
     */
    public static function calcularTipoPersonaPorRfc(?string $rfc): ?string
    {
        if (!$rfc) {
            return null;
        }

        $rfc = strtoupper(trim($rfc));
        
        // Validar formato básico del RFC
        if (!preg_match('/^[A-ZÑ&]{3,4}[0-9]{6}[A-Z0-9]{3}$/', $rfc)) {
            return null;
        }

        // Determinar tipo de persona por longitud
        return match(strlen($rfc)) {
            12 => 'Moral',
            13 => 'Física',
            default => null
        };
    }

    /**
     * Obtiene el tipo de persona del proveedor, calculándolo si es necesario
     */
    public static function getTipoPersona($proveedor): ?string
    {
        if (!$proveedor) {
            return null;
        }

        // Si ya tiene tipo_persona asignado, usarlo
        if ($proveedor->tipo_persona) {
            return $proveedor->tipo_persona;
        }

        // Si no tiene tipo_persona, calcularlo basado en el RFC
        return self::calcularTipoPersonaPorRfc($proveedor->rfc);
    }
}
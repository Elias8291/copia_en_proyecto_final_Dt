<?php

namespace App\Helpers;

use Carbon\Carbon;

class TiempoHelper
{
    /**
     * Calcula el tiempo transcurrido de forma legible
     */
    public static function tiempoTranscurrido(Carbon $fechaInicio, Carbon $fechaFin = null): string
    {
        $fechaFin = $fechaFin ?? now();
        $diferencia = $fechaInicio->diff($fechaFin);

        if ($diferencia->days > 30) {
            $tiempo = $diferencia->m . ' mes' . ($diferencia->m > 1 ? 'es' : '');
            if ($diferencia->d > 0) {
                $tiempo .= ' y ' . $diferencia->d . ' día' . ($diferencia->d > 1 ? 's' : '');
            }
            return $tiempo;
        }

        if ($diferencia->days > 0) {
            $tiempo = $diferencia->days . ' día' . ($diferencia->days > 1 ? 's' : '');
            if ($diferencia->h > 0) {
                $tiempo .= ' y ' . $diferencia->h . ' hora' . ($diferencia->h > 1 ? 's' : '');
            }
            return $tiempo;
        }

        if ($diferencia->h > 0) {
            $tiempo = $diferencia->h . ' hora' . ($diferencia->h > 1 ? 's' : '');
            if ($diferencia->i > 0) {
                $tiempo .= ' y ' . $diferencia->i . ' minuto' . ($diferencia->i > 1 ? 's' : '');
            }
            return $tiempo;
        }

        return $diferencia->i . ' minuto' . ($diferencia->i > 1 ? 's' : '');
    }

    /**
     * Obtiene el tiempo transcurrido desde la creación de un trámite
     */
    public static function tiempoTramite($tramite): string
    {
        return self::tiempoTranscurrido($tramite->created_at);
    }
}
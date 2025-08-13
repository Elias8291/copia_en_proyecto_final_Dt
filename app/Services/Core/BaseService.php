<?php

namespace App\Services\Core;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Clase base para servicios con funcionalidades comunes
 */
abstract class BaseService
{
    /**
     * Ejecutar operación en transacción de base de datos
     */
    protected function ejecutarEnTransaccion(callable $callback)
    {
        return DB::transaction($callback);
    }

    /**
     * Registrar error en log
     */
    protected function registrarError(\Exception $e, string $contexto = ''): void
    {
        Log::error("Error en {$contexto}: " . $e->getMessage(), [
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString()
        ]);
    }

    /**
     * Validar datos requeridos
     */
    protected function validarDatosRequeridos(array $datos, array $camposRequeridos): bool
    {
        foreach ($camposRequeridos as $campo) {
            if (!isset($datos[$campo]) || empty($datos[$campo])) {
                return false;
            }
        }
        return true;
    }

    /**
     * Limpiar datos de entrada
     */
    protected function limpiarDatos(array $datos): array
    {
        $datosLimpios = [];
        
        foreach ($datos as $clave => $valor) {
            if (is_string($valor)) {
                $datosLimpios[$clave] = trim(strip_tags($valor));
            } else {
                $datosLimpios[$clave] = $valor;
            }
        }
        
        return $datosLimpios;
    }
}

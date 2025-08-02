<?php

declare(strict_types=1);

namespace App\Services\Proveedores;

use App\Models\Proveedor;

/**
 * Servicio especializado para manejo de números PV
 * Responsabilidad: Generación y asignación de números de proveedor
 */
class NumerosPvService
{
    /**
     * Generar nuevo PV continuando la secuencia del último registrado
     */
    public function generarNuevoPv(): string
    {
        $ultimoProveedor = Proveedor::whereNotNull('pv_numero')
            ->where('pv_numero', 'like', 'PV%')
            ->orderByRaw('CAST(SUBSTRING(pv_numero, 3) AS UNSIGNED) DESC')
            ->first();

        if (!$ultimoProveedor || !$ultimoProveedor->pv_numero) {
            return 'PV0001';
        }

        // Extraer el número del último PV
        $numeroActual = (int) substr($ultimoProveedor->pv_numero, 2);
        $nuevoNumero = $numeroActual + 1;
        
        // Formatear con ceros a la izquierda (mínimo 4 dígitos)
        return 'PV' . str_pad((string)$nuevoNumero, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Asignar nuevo PV a un proveedor
     */
    public function asignarNuevoPv(Proveedor $proveedor): string
    {
        $nuevoPv = $this->generarNuevoPv();
        
        $proveedor->update([
            'pv_numero' => $nuevoPv
        ]);

        return $nuevoPv;
    }

    /**
     * Verificar si un PV ya existe
     */
    public function pvExiste(string $pv): bool
    {
        return Proveedor::where('pv_numero', $pv)->exists();
    }

    /**
     * Obtener el siguiente número PV disponible sin asignarlo
     */
    public function obtenerSiguientePvDisponible(): string
    {
        $candidato = $this->generarNuevoPv();
        
        // Verificar que no exista (por seguridad)
        while ($this->pvExiste($candidato)) {
            // Si existe, generar el siguiente
            $numero = (int) substr($candidato, 2);
            $candidato = 'PV' . str_pad((string)($numero + 1), 4, '0', STR_PAD_LEFT);
        }

        return $candidato;
    }
}
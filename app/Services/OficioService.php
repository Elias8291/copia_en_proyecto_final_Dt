<?php

namespace App\Services;

use App\Models\Oficio;
use App\Models\Tramite;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class OficioService
{
    /**
     * Generar número de oficio automático
     */
    public function generarNumeroOficio(): string
    {
        $ultimoOficio = Oficio::orderBy('id', 'desc')->first();
        
        if (!$ultimoOficio) {
            return 'OF-2025-001';
        }
        
        $numeroActual = (int) substr($ultimoOficio->numero_oficio, -3);
        $nuevoNumero = $numeroActual + 1;
        
        return 'OF-' . date('Y') . '-' . str_pad($nuevoNumero, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Crear oficio de aprobación
     */
    public function crearOficioAprobacion(Tramite $tramite): Oficio
    {
        $oficio = Oficio::create([
            'tramite_id' => $tramite->id,
            'numero_oficio' => $this->generarNumeroOficio(),
            'fecha_oficio' => now()->toDateString(),
            'descripcion' => 'Oficio de aprobación del trámite de registro en el padrón de proveedores.'
        ]);

        Log::info('Oficio de aprobación creado', [
            'tramite_id' => $tramite->id,
            'oficio_id' => $oficio->id,
            'numero_oficio' => $oficio->numero_oficio
        ]);

        return $oficio;
    }
} 
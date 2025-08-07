<?php

namespace App\Services\Tramites;

use App\Models\Tramite;
use App\Models\Proveedor;
use App\Models\Accionista;
use Illuminate\Http\Request;

class AccionistasService
{
    public function guardar(Tramite $tramite, Proveedor $proveedor, Request $request): void
    {
        if (!$request->has('accionistas') || !is_array($request->accionistas)) {
            return;
        }
        
        foreach ($request->accionistas as $accionista) {
            Accionista::create([
                'tramite_id' => $tramite->id,
                'proveedor_id' => $proveedor->id,
                'nombre' => $accionista['nombre'],
                'rfc' => $accionista['rfc'],
                'porcentaje_participacion' => $accionista['porcentaje_participacion'],
                'status' => 'pendiente',
            ]);
        }
    }

    /**
     * Obtiene los accionistas de un trámite
     */
    public function obtener(Tramite $tramite): array
    {
        if ($tramite->relationLoaded('accionistas')) {
            $accionistas = $tramite->accionistas;
        } else {
            $accionistas = $tramite->accionistas()->get();
        }
        
        $resultado = [];
        foreach ($accionistas as $accionista) {
            $resultado[] = [
                'nombre' => $accionista->nombre ?? '',
                'rfc' => $accionista->rfc ?? '',
                'porcentaje_participacion' => $accionista->porcentaje_participacion ?? '',
            ];
        }
        
        return $resultado;
    }

    /**
     * Actualizar accionistas de un trámite existente
     */
    public function actualizar(Tramite $tramite, Request $request): void
    {
        if ($request->filled('accionistas')) {
            // Eliminar accionistas existentes
            $tramite->accionistas()->delete();
            
            // Agregar nuevos accionistas
            foreach ($request->accionistas as $accionistaData) {
                $tramite->accionistas()->create([
                    'nombre' => $accionistaData['nombre'] ?? '',
                    'rfc' => $accionistaData['rfc'] ?? '',
                    'porcentaje_participacion' => $accionistaData['porcentaje_participacion'] ?? 0,
                    'proveedor_id' => $tramite->proveedor_id,
                    'status' => 'pendiente'
                ]);
            }
        }
    }
} 
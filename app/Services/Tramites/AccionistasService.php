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
        \Log::info('AccionistasService: Iniciando guardado', [
            'tramite_id' => $tramite->id,
            'proveedor_id' => $proveedor->id,
            'has_accionistas' => $request->has('accionistas'),
            'accionistas_count' => $request->has('accionistas') ? count($request->accionistas) : 0
        ]);
        
        if ($request->has('accionistas') && is_array($request->accionistas)) {
            foreach ($request->accionistas as $index => $accionista) {
                \Log::info('AccionistasService: Procesando accionista', [
                    'index' => $index,
                    'accionista' => $accionista
                ]);
                
                $accionistaGuardado = Accionista::create([
                    'tramite_id' => $tramite->id,
                    'proveedor_id' => $proveedor->id,
                    'nombre' => $accionista['nombre'],
                    'rfc' => $accionista['rfc'],
                    'porcentaje_participacion' => $accionista['porcentaje_participacion'],
                    'status' => 'pendiente',
                ]);
                
                \Log::info('AccionistasService: Accionista guardado', [
                    'accionista_id' => $accionistaGuardado->id,
                    'nombre' => $accionista['nombre']
                ]);
            }
        } else {
            \Log::warning('AccionistasService: No se encontraron accionistas para guardar');
        }
    }
} 
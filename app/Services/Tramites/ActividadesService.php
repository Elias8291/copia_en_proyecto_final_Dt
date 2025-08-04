<?php

namespace App\Services\Tramites;

use App\Models\Tramite;
use App\Models\ActividadProveedor;
use Illuminate\Http\Request;

class ActividadesService
{
    public function guardar(Tramite $tramite, Request $request): void
    {
        \Log::info('ActividadesService: Iniciando guardado', [
            'tramite_id' => $tramite->id,
            'proveedor_id' => $tramite->proveedor_id,
            'request_data' => $request->all()
        ]);
        
        $actividadesSeleccionadas = $request->input('actividades_seleccionadas');
        
        \Log::info('ActividadesService: Actividades seleccionadas', [
            'actividades_seleccionadas' => $actividadesSeleccionadas
        ]);
        
        if ($actividadesSeleccionadas) {
            $actividadesArray = json_decode($actividadesSeleccionadas, true);
            
            \Log::info('ActividadesService: Actividades decodificadas', [
                'actividades_array' => $actividadesArray
            ]);
            
            if (is_array($actividadesArray)) {
                foreach ($actividadesArray as $actividad) {
                    if (isset($actividad['id'])) {
                        try {
                            $actividadProveedor = ActividadProveedor::create([
                                'proveedor_id' => $tramite->proveedor_id,
                                'tramite_id' => $tramite->id,
                                'actividad_id' => $actividad['id'],
                                'status' => 'pendiente',
                            ]);
                            
                            \Log::info('ActividadesService: Actividad creada', [
                                'actividad_proveedor_id' => $actividadProveedor->id,
                                'actividad_id' => $actividad['id'],
                                'proveedor_id' => $tramite->proveedor_id
                            ]);
                        } catch (\Exception $e) {
                            \Log::error('ActividadesService: Error al crear actividad', [
                                'error' => $e->getMessage(),
                                'actividad' => $actividad,
                                'proveedor_id' => $tramite->proveedor_id
                            ]);
                            throw $e;
                        }
                    }
                }
            }
        } else {
            \Log::warning('ActividadesService: No se encontraron actividades seleccionadas');
        }
    }
} 
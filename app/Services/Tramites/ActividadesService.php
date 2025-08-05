<?php

namespace App\Services\Tramites;

use App\Models\Tramite;
use App\Models\ActividadProveedor;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class ActividadesService
{
    public function guardar(Tramite $tramite, Request $request): void
    {
        $actividadesSeleccionadas = $request->input('actividades_seleccionadas');
        
        if (!$actividadesSeleccionadas) {
            return;
        }
        
            $actividadesArray = json_decode($actividadesSeleccionadas, true);
            
        if (!is_array($actividadesArray)) {
            return;
        }
        
                foreach ($actividadesArray as $actividad) {
                    if (isset($actividad['id'])) {
                ActividadProveedor::create([
                                'proveedor_id' => $tramite->proveedor_id,
                                'tramite_id' => $tramite->id,
                                'actividad_id' => $actividad['id'],
                                'status' => 'pendiente',
                            ]);
            }
        }
    }

    /**
     * Obtiene las actividades de un trámite
     */
    public function obtener(Tramite $tramite): Collection
    {
        return $tramite->actividades()->with('actividad')->get();
    }
} 
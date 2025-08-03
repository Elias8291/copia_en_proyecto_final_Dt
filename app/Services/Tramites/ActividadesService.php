<?php

namespace App\Services\Tramites;

use App\Models\Tramite;
use App\Models\ActividadProveedor;
use Illuminate\Http\Request;

class ActividadesService
{
    public function guardar(Tramite $tramite, Request $request): void
    {
        if ($request->has('actividades') && is_array($request->actividades)) {
            foreach ($request->actividades as $actividadId) {
                ActividadProveedor::create([
                    'tramite_id' => $tramite->id,
                    'actividad_id' => $actividadId,
                    'status' => 'pendiente',
                ]);
            }
        }
    }
} 
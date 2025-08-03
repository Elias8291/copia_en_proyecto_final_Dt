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
        if ($request->has('accionistas') && is_array($request->accionistas)) {
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
    }
} 
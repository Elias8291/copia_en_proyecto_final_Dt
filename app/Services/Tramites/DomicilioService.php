<?php

namespace App\Services\Tramites;

use App\Models\Tramite;
use App\Models\Proveedor;
use App\Models\Direccion;
use Illuminate\Http\Request;

class DomicilioService
{
    public function guardar(Tramite $tramite, Proveedor $proveedor, Request $request): void
    {
        Direccion::create([
            'tramite_id' => $tramite->id,
            'proveedor_id' => $proveedor->id,
            'calle' => $request->calle,
            'entre_calle' => $request->entre_calle,
            'y_calle' => $request->y_calle,
            'numero_exterior' => $request->numero_exterior,
            'numero_interior' => $request->numero_interior,
            'colonia' => $request->colonia,
            'codigo_postal' => $request->codigo_postal,
            'municipio' => $request->municipio,
            'asentamiento' => $request->asentamiento,
            'estado_id' => $request->estado_id,
            'coordenada_id' => $request->coordenada_id,
            'status' => 'pendiente',
        ]);
    }
} 
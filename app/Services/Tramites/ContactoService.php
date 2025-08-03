<?php

namespace App\Services\Tramites;

use App\Models\Tramite;
use App\Models\Proveedor;
use App\Models\Contacto;
use Illuminate\Http\Request;

class ContactoService
{
    public function guardar(Tramite $tramite, Proveedor $proveedor, Request $request): void
    {
        Contacto::create([
            'tramite_id' => $tramite->id,
            'proveedor_id' => $proveedor->id,
            'nombre_contacto' => $request->nombre_contacto,
            'cargo' => $request->cargo,
            'correo_electronico' => $request->correo_contacto,
            'telefono' => $request->telefono_contacto,
            'status' => 'pendiente',
        ]);
    }
} 
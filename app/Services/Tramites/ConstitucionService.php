<?php

namespace App\Services\Tramites;

use App\Models\Tramite;
use App\Models\Proveedor;
use App\Models\DatoConstitutivo;
use Illuminate\Http\Request;

class ConstitucionService
{
    public function guardar(Tramite $tramite, Proveedor $proveedor, Request $request): void
    {
        DatoConstitutivo::create([
            'tramite_id' => $tramite->id,
            'proveedor_id' => $proveedor->id,
            'instrumento_notarial_id' => $request->instrumento_notarial_id,
            'status' => 'pendiente',
        ]);
    }
} 
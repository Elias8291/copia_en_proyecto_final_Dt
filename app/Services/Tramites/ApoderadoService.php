<?php

namespace App\Services\Tramites;

use App\Models\Tramite;
use App\Models\Proveedor;
use App\Models\ApoderadoLegal;
use Illuminate\Http\Request;

class ApoderadoService
{
    public function guardar(Tramite $tramite, Proveedor $proveedor, Request $request): void
    {
        ApoderadoLegal::create([
            'tramite_id' => $tramite->id,
            'proveedor_id' => $proveedor->id,
            'instrumento_notarial_id' => $request->instrumento_notarial_id,
            'nombre_apoderado' => $request->nombre_apoderado,
            'rfc' => $request->rfc_apoderado,
            'numero_escritura_constitutiva_poder' => $request->numero_escritura_constitutiva_poder,
            'numero_registro_publico_poder' => $request->numero_registro_publico_poder,
            'fecha_inscripcion_poder' => $request->fecha_inscripcion_poder,
            'status' => 'pendiente',
        ]);
    }
} 
<?php

namespace App\Services\Tramites;

use App\Models\Tramite;
use App\Models\Proveedor;
use App\Models\DatoGeneral;
use Illuminate\Http\Request;

class DatosGeneralesService
{
    public function guardar(Tramite $tramite, Proveedor $proveedor, Request $request): void
    {
        // Usar campos ocultos si están disponibles (cuando los campos principales están deshabilitados)
        $razonSocial = $request->razon_social ?: $request->razon_social_hidden;
        $rfc = $request->rfc ?: $request->rfc_hidden;
        $tipoPersona = $request->tipo_persona ?: $request->tipo_persona_hidden;
        $curp = $request->curp ?: $request->curp_hidden;
        
        DatoGeneral::create([
            'tramite_id' => $tramite->id,
            'proveedor_id' => $proveedor->id,
            'curp' => $curp,
            'razon_social' => $razonSocial,
            'pagina_web' => $request->pagina_web,
            'telefono' => $request->telefono,
            'status' => 'pendiente',
        ]);
    }
} 
<?php

namespace App\Services\Tramites;

use App\Models\Tramite;
use App\Models\Proveedor;
use App\Models\DatoGeneral;
use Illuminate\Http\Request;

class DatosGeneralesService extends BaseService
{
    public function guardar(Tramite $tramite, Proveedor $proveedor, Request $request): void
    {
        $datos = $this->procesarDatos($request);
        
        DatoGeneral::create([
            'tramite_id' => $tramite->id,
            'proveedor_id' => $proveedor->id,
            'curp' => $datos['curp'],
            'razon_social' => $datos['razon_social'],
            'pagina_web' => $datos['pagina_web'],
            'telefono' => $datos['telefono'],
            'status' => 'pendiente',
        ]);
    }

    /**
     * Obtiene los datos generales de un trámite
     */
    public function obtener(Tramite $tramite): ?array
    {
        $datos = $tramite->datosGenerales()->latest()->first();
        
        if (!$datos) {
            return null;
        }
        
        return [
            'razon_social' => $datos->razon_social,
            'rfc' => $tramite->proveedor->rfc ?? '',
            'tipo_persona' => $tramite->proveedor->tipo_persona ?? '',
            'curp' => $datos->curp,
            'pagina_web' => $datos->pagina_web,
            'telefono' => $datos->telefono,
        ];
    }

    private function procesarDatos(Request $request): array
    {
        return [
            'razon_social' => $this->obtenerValorOculto($request, 'razon_social'),
            'rfc' => $this->obtenerValorOculto($request, 'rfc'),
            'tipo_persona' => $this->obtenerValorOculto($request, 'tipo_persona'),
            'curp' => $this->obtenerValorOculto($request, 'curp'),
            'pagina_web' => $request->pagina_web,
            'telefono' => $request->telefono,
        ];
    }

    /**
     * Actualizar datos generales de un trámite existente
     */
    public function actualizar(Tramite $tramite, Request $request): void
    {
        $datosGenerales = $tramite->datosGenerales->first();
        
        if ($datosGenerales) {
            $datosGenerales->update([
                'razon_social' => $request->razon_social,
                'curp' => $request->curp,
                'telefono' => $request->telefono,
                'pagina_web' => $request->pagina_web,
            ]);
        }
    }
} 
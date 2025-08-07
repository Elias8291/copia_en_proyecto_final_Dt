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
        \Log::info('DatosGeneralesService: Iniciando actualización', [
            'tramite_id' => $tramite->id,
            'request_data' => $request->all(),
            'razon_social_filled' => $request->filled('razon_social'),
            'razon_social_hidden_filled' => $request->filled('razon_social_hidden'),
            'curp_filled' => $request->filled('curp'),
            'curp_hidden_filled' => $request->filled('curp_hidden'),
            'telefono_filled' => $request->filled('telefono'),
            'pagina_web_filled' => $request->filled('pagina_web')
        ]);
        
        $datosGenerales = $tramite->datosGenerales->first();
        
        if ($datosGenerales) {
            // Solo actualizar si se proporcionan valores no nulos
            $datosActualizar = [];
            
            // Buscar razón social en campos normales o ocultos
            $razonSocial = $request->filled('razon_social') ? $request->razon_social : 
                          ($request->filled('razon_social_hidden') ? $request->razon_social_hidden : null);
            
            if ($razonSocial !== null) {
                $datosActualizar['razon_social'] = $razonSocial;
            }
            
            // Buscar CURP en campos normales o ocultos
            $curp = $request->filled('curp') ? $request->curp : 
                   ($request->filled('curp_hidden') ? $request->curp_hidden : null);
            
            if ($curp !== null) {
                $datosActualizar['curp'] = $curp;
            }
            
            if ($request->filled('telefono')) {
                $datosActualizar['telefono'] = $request->telefono;
            }
            
            if ($request->filled('pagina_web')) {
                $datosActualizar['pagina_web'] = $request->pagina_web;
            }
            
            \Log::info('DatosGeneralesService: Datos a actualizar', [
                'tramite_id' => $tramite->id,
                'datos_actualizar' => $datosActualizar,
                'datos_generales_existe' => $datosGenerales ? true : false,
                'razon_social_value' => $razonSocial,
                'curp_value' => $curp
            ]);
            
            // Solo actualizar si hay datos para actualizar
            if (!empty($datosActualizar)) {
                $datosGenerales->update($datosActualizar);
                
                \Log::info('DatosGeneralesService: Actualización completada', [
                    'tramite_id' => $tramite->id,
                    'datos_actualizados' => $datosActualizar
                ]);
            } else {
                \Log::info('DatosGeneralesService: No hay datos para actualizar', [
                    'tramite_id' => $tramite->id
                ]);
            }
        } else {
            \Log::warning('DatosGeneralesService: No se encontraron datos generales para actualizar', [
                'tramite_id' => $tramite->id
            ]);
        }
    }
} 
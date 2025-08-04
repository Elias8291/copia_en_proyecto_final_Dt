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
        \Log::info('DatosGeneralesService: Iniciando guardado', [
            'tramite_id' => $tramite->id,
            'proveedor_id' => $proveedor->id,
            'request_data' => $request->all()
        ]);
        
        // Usar campos ocultos si están disponibles (cuando los campos principales están deshabilitados)
        $razonSocial = $request->razon_social ?: $request->razon_social_hidden;
        $rfc = $request->rfc ?: $request->rfc_hidden;
        $tipoPersona = $request->tipo_persona ?: $request->tipo_persona_hidden;
        $curp = $request->curp ?: $request->curp_hidden;
        
        \Log::info('DatosGeneralesService: Datos procesados', [
            'razon_social' => $razonSocial,
            'rfc' => $rfc,
            'tipo_persona' => $tipoPersona,
            'curp' => $curp,
            'pagina_web' => $request->pagina_web,
            'telefono' => $request->telefono
        ]);
        
        try {
            $datoGeneral = DatoGeneral::create([
                'tramite_id' => $tramite->id,
                'proveedor_id' => $proveedor->id,
                'curp' => $curp,
                'razon_social' => $razonSocial,
                'pagina_web' => $request->pagina_web,
                'telefono' => $request->telefono,
                'status' => 'pendiente',
            ]);
            
            \Log::info('DatosGeneralesService: Datos generales creados exitosamente', [
                'dato_general_id' => $datoGeneral->id
            ]);
        } catch (\Exception $e) {
            \Log::error('DatosGeneralesService: Error al crear datos generales', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }
} 
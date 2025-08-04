<?php

namespace App\Services\Tramites;

use App\Models\Tramite;
use App\Models\Proveedor;
use App\Models\ApoderadoLegal;
use App\Models\InstrumentoNotarial;
use Illuminate\Http\Request;

class ApoderadoService
{
    public function guardar(Tramite $tramite, Proveedor $proveedor, Request $request): void
    {
        \Log::info('ApoderadoService: Iniciando guardado', [
            'tramite_id' => $tramite->id,
            'proveedor_id' => $proveedor->id,
            'request_data' => $request->all()
        ]);
        
        // Crear el instrumento notarial primero
        $instrumentoNotarial = InstrumentoNotarial::create([
            'numero_escritura' => $request->numero_escritura_constitutiva_poder,
            'numero_escritura_constitutiva' => $request->numero_escritura_constitutiva_poder,
            'fecha_constitucion' => $request->fecha_inscripcion_poder,
            'nombre_notario' => $request->nombre_notario ?? 'No especificado',
            'estado_id' => $request->estado_id ?? 1, // Default a Oaxaca
            'numero_notario' => $request->numero_notario ?? 'No especificado',
            'numero_registro_publico' => $request->numero_registro_publico_poder,
            'fecha_inscripcion' => $request->fecha_inscripcion_poder,
        ]);
        
        \Log::info('ApoderadoService: Instrumento notarial creado', [
            'instrumento_id' => $instrumentoNotarial->id
        ]);
        
        // Crear el apoderado legal con el instrumento notarial
        $apoderado = ApoderadoLegal::create([
            'tramite_id' => $tramite->id,
            'proveedor_id' => $proveedor->id,
            'instrumento_notarial_id' => $instrumentoNotarial->id,
            'nombre_apoderado' => $request->nombre_apoderado,
            'rfc' => $request->rfc_apoderado,
            'numero_escritura_constitutiva_poder' => $request->numero_escritura_constitutiva_poder,
            'numero_registro_publico_poder' => $request->numero_registro_publico_poder,
            'fecha_inscripcion_poder' => $request->fecha_inscripcion_poder,
            'status' => 'pendiente',
        ]);
        
        \Log::info('ApoderadoService: Apoderado legal guardado', [
            'apoderado_id' => $apoderado->id,
            'nombre_apoderado' => $request->nombre_apoderado,
            'instrumento_notarial_id' => $instrumentoNotarial->id
        ]);
    }
} 
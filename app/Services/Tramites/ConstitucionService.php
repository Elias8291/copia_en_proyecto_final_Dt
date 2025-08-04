<?php

namespace App\Services\Tramites;

use App\Models\Tramite;
use App\Models\Proveedor;
use App\Models\DatoConstitutivo;
use App\Models\InstrumentoNotarial;
use Illuminate\Http\Request;

class ConstitucionService
{
    public function guardar(Tramite $tramite, Proveedor $proveedor, Request $request): void
    {
        \Log::info('ConstitucionService: Iniciando guardado', [
            'tramite_id' => $tramite->id,
            'proveedor_id' => $proveedor->id,
            'request_data' => $request->all()
        ]);
        
        // Crear el instrumento notarial primero
        $instrumentoNotarial = InstrumentoNotarial::create([
            'numero_escritura' => $request->numero_escritura_constitutiva ?? 'No especificado',
            'numero_escritura_constitutiva' => $request->numero_escritura_constitutiva ?? 'No especificado',
            'fecha_constitucion' => $request->fecha_constitucion ?? now(),
            'nombre_notario' => $request->nombre_notario ?? 'No especificado',
            'estado_id' => $request->estado_id ?? 1, // Default a Oaxaca
            'numero_notario' => $request->numero_notario ?? 'No especificado',
            'numero_registro_publico' => $request->numero_registro_publico ?? 'No especificado',
            'fecha_inscripcion' => $request->fecha_inscripcion ?? now(),
        ]);
        
        \Log::info('ConstitucionService: Instrumento notarial creado', [
            'instrumento_id' => $instrumentoNotarial->id
        ]);
        
        $datoConstitutivo = DatoConstitutivo::create([
            'tramite_id' => $tramite->id,
            'proveedor_id' => $proveedor->id,
            'instrumento_notarial_id' => $instrumentoNotarial->id,
            'status' => 'pendiente',
        ]);
        
        \Log::info('ConstitucionService: Datos constitutivos guardados', [
            'dato_constitutivo_id' => $datoConstitutivo->id,
            'instrumento_notarial_id' => $instrumentoNotarial->id
        ]);
    }
} 
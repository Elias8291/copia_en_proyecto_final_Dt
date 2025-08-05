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
            'nombre_apoderado' => $request->nombre_apoderado
        ]);
        
        $instrumentoNotarial = $this->crearInstrumentoNotarial($request);
        
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
        
        \Log::info('ApoderadoService: Apoderado creado', [
            'apoderado_id' => $apoderado->id,
            'instrumento_id' => $instrumentoNotarial->id,
            'tramite_id' => $tramite->id
        ]);
    }

    /**
     * Obtiene los datos del apoderado de un trámite
     */
    public function obtener(Tramite $tramite): ?array
    {
        // Si la relación ya está cargada, usarla
        if ($tramite->relationLoaded('apoderadosLegales')) {
            $apoderado = $tramite->apoderadosLegales->sortByDesc('created_at')->first();
        } else {
            $apoderado = $tramite->apoderadosLegales()
                ->with('instrumentoNotarial')
                ->latest()
                ->first();
        }
        
        if (!$apoderado) {
            \Log::info('ApoderadoService: No se encontró apoderado para tramite', ['tramite_id' => $tramite->id]);
            return null;
        }
        
        // Si la relación con instrumentoNotarial ya está cargada, usarla
        if ($apoderado->relationLoaded('instrumentoNotarial')) {
            $instrumentoNotarial = $apoderado->instrumentoNotarial;
        } else {
            $instrumentoNotarial = $apoderado->instrumentoNotarial()->first();
        }
        
        if (!$instrumentoNotarial) {
            \Log::info('ApoderadoService: No se encontró instrumento notarial', ['apoderado_id' => $apoderado->id]);
        }
        
        \Log::info('ApoderadoService: Datos obtenidos', [
            'apoderado_id' => $apoderado->id,
            'instrumento_id' => $instrumentoNotarial->id ?? null,
            'nombre_apoderado' => $apoderado->nombre_apoderado
        ]);
        
        // Obtener estado si existe
        $estado = null;
        if ($instrumentoNotarial && $instrumentoNotarial->relationLoaded('estado')) {
            $estado = $instrumentoNotarial->estado;
        } elseif ($instrumentoNotarial && $instrumentoNotarial->estado_id) {
            $estado = $instrumentoNotarial->estado()->first();
        }
        
        return [
            // Datos básicos del apoderado
            'nombre_apoderado' => $apoderado->nombre_apoderado ?? '',
            'nombre' => $apoderado->nombre_apoderado ?? '',
            'rfc' => $apoderado->rfc ?? '',
            
            // Datos específicos del poder (tabla apoderado_legal)
            'numero_escritura_constitutiva_poder' => $apoderado->numero_escritura_constitutiva_poder ?? '',
            'numero_registro_publico_poder' => $apoderado->numero_registro_publico_poder ?? '',
            'fecha_inscripcion_poder' => $apoderado->fecha_inscripcion_poder ?? '',
            
            // Datos del instrumento notarial del poder
            'numero_escritura' => $instrumentoNotarial->numero_escritura ?? '',
            'numero_escritura_poder' => $instrumentoNotarial->numero_escritura ?? '',
            'fecha_poder' => $instrumentoNotarial->fecha_constitucion ?? '',
            'nombre_notario_poder' => $instrumentoNotarial->nombre_notario ?? '',
            'numero_notario_poder' => $instrumentoNotarial->numero_notario ?? '',
            'estado_id' => $instrumentoNotarial->estado_id ?? '',
            'estado_nombre' => $estado->nombre ?? '',
            'numero_registro_publico' => $instrumentoNotarial->numero_registro_publico ?? '',
            'fecha_inscripcion' => $instrumentoNotarial->fecha_inscripcion ?? '',
        ];
    }
    
    private function crearInstrumentoNotarial(Request $request): InstrumentoNotarial
    {
        return InstrumentoNotarial::create([
            'numero_escritura' => $request->numero_escritura_constitutiva_poder,
            'numero_escritura_constitutiva' => $request->numero_escritura_constitutiva_poder,
            'fecha_constitucion' => $request->fecha_inscripcion_poder,
            'nombre_notario' => $request->nombre_notario ?? 'No especificado',
            'estado_id' => $request->estado_id ?? 1,
            'numero_notario' => $request->numero_notario ?? 'No especificado',
            'numero_registro_publico' => $request->numero_registro_publico_poder,
            'fecha_inscripcion' => $request->fecha_inscripcion_poder,
        ]);
    }
}
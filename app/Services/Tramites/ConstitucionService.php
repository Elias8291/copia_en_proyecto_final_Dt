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
            'numero_escritura' => $request->numero_escritura ?? $request->numero_escritura_constitutiva ?? 'No especificado',
            'numero_escritura_constitutiva' => $request->numero_escritura_constitutiva ?? 'No especificado',
            'fecha_constitucion' => $request->fecha_constitucion ?? now(),
            'nombre_notario' => $request->nombre_notario ?? 'No especificado',
            'estado_id' => $request->estado_id ?? 1, // Default a Oaxaca
            'numero_notario' => $request->numero_notario ?? 'No especificado',
            'numero_registro_publico' => $request->numero_registro_publico ?? 'No especificado',
            'fecha_inscripcion' => $request->fecha_inscripcion ?? now(),
        ]);
        
        \Log::info('ConstitucionService: InstrumentoNotarial creado para constitución', [
            'instrumento_id' => $instrumentoNotarial->id,
            'numero_escritura_constitutiva' => $instrumentoNotarial->numero_escritura_constitutiva,
            'tramite_id' => $tramite->id
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

    /**
     * Obtiene los datos de constitución de un trámite
     */
    public function obtener(Tramite $tramite): ?array
    {
        // Si la relación ya está cargada, usarla
        if ($tramite->relationLoaded('datosConstitutivos')) {
            $constitucion = $tramite->datosConstitutivos->sortByDesc('created_at')->first();
        } else {
            $constitucion = $tramite->datosConstitutivos()
                ->with('instrumentoNotarial')
                ->latest()
                ->first();
        }
        
        if (!$constitucion) {
            \Log::info('ConstitucionService: No se encontró constitución para tramite', ['tramite_id' => $tramite->id]);
            return null;
        }
        
        // Si la relación con instrumentoNotarial ya está cargada, usarla
        if ($constitucion->relationLoaded('instrumentoNotarial')) {
            $instrumentoNotarial = $constitucion->instrumentoNotarial;
        } else {
            $instrumentoNotarial = $constitucion->instrumentoNotarial()->first();
        }
        
        if (!$instrumentoNotarial) {
            \Log::info('ConstitucionService: No se encontró instrumento notarial', ['constitucion_id' => $constitucion->id]);
            return null;
        }
        
        \Log::info('ConstitucionService: Datos obtenidos', [
            'constitucion_id' => $constitucion->id,
            'instrumento_id' => $instrumentoNotarial->id,
            'numero_escritura_constitutiva' => $instrumentoNotarial->numero_escritura_constitutiva
        ]);
        
        // Obtener estado si existe
        $estado = null;
        if ($instrumentoNotarial->relationLoaded('estado')) {
            $estado = $instrumentoNotarial->estado;
        } elseif ($instrumentoNotarial->estado_id) {
            $estado = $instrumentoNotarial->estado()->first();
        }
        
        return [
            'numero_escritura' => $instrumentoNotarial->numero_escritura ?? '',
            'numero_escritura_constitutiva' => $instrumentoNotarial->numero_escritura_constitutiva ?? '',
            'fecha_constitucion' => $instrumentoNotarial->fecha_constitucion ?? '',
            'nombre_notario' => $instrumentoNotarial->nombre_notario ?? '',
            'numero_notario' => $instrumentoNotarial->numero_notario ?? '',
            'estado_id' => $instrumentoNotarial->estado_id ?? '',
            'estado_nombre' => $estado->nombre ?? '',
            'numero_registro_publico' => $instrumentoNotarial->numero_registro_publico ?? '',
            'fecha_inscripcion' => $instrumentoNotarial->fecha_inscripcion ?? '',
        ];
    }

    /**
     * Actualizar datos constitutivos de un trámite existente
     */
    public function actualizar(Tramite $tramite, Request $request): void
    {
        if ($request->filled('constitucion')) {
            $constitucionData = $request->constitucion;
            $datosConstitutivos = $tramite->datosConstitutivos->first();
            
            if ($datosConstitutivos && $datosConstitutivos->instrumentoNotarial) {
                $datosConstitutivos->instrumentoNotarial->update([
                    'fecha_constitucion' => $constitucionData['fecha_constitucion'] ?? null,
                    'numero_escritura' => $constitucionData['numero_escritura'] ?? null,
                    'nombre_notario' => $constitucionData['nombre_notario'] ?? null,
                    'numero_notario' => $constitucionData['numero_notario'] ?? null,
                    'estado_id' => $constitucionData['estado_id'] ?? null,
                ]);
            }
        }
    }
} 
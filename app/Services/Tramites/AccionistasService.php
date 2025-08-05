<?php

namespace App\Services\Tramites;

use App\Models\Tramite;
use App\Models\Proveedor;
use App\Models\Accionista;
use Illuminate\Http\Request;

class AccionistasService
{
    public function guardar(Tramite $tramite, Proveedor $proveedor, Request $request): void
    {
        if (!$request->has('accionistas') || !is_array($request->accionistas)) {
            return;
        }
        
        foreach ($request->accionistas as $accionista) {
            Accionista::create([
                'tramite_id' => $tramite->id,
                'proveedor_id' => $proveedor->id,
                'nombre' => $accionista['nombre'],
                'rfc' => $accionista['rfc'],
                'porcentaje_participacion' => $accionista['porcentaje_participacion'],
                'status' => 'pendiente',
            ]);
        }
    }

    /**
     * Obtiene los accionistas de un trámite con datos del instrumento notarial
     */
    public function obtener(Tramite $tramite): array
    {
        // Si la relación ya está cargada, usarla
        if ($tramite->relationLoaded('accionistas')) {
            $accionistas = $tramite->accionistas;
        } else {
            $accionistas = $tramite->accionistas()->get();
        }
        
        // Obtener datos del instrumento notarial de la constitución
        if ($tramite->relationLoaded('datosConstitutivos')) {
            $constitucion = $tramite->datosConstitutivos->sortByDesc('created_at')->first();
        } else {
            $constitucion = $tramite->datosConstitutivos()
                ->with('instrumentoNotarial')
                ->latest()
                ->first();
        }
        
        $instrumentoNotarial = null;
        if ($constitucion) {
            if ($constitucion->relationLoaded('instrumentoNotarial')) {
                $instrumentoNotarial = $constitucion->instrumentoNotarial;
            } else {
                $instrumentoNotarial = $constitucion->instrumentoNotarial()->first();
            }
        }
        
        \Log::info('AccionistasService: Datos obtenidos', [
            'tramite_id' => $tramite->id,
            'accionistas_count' => $accionistas->count(),
            'constitucion_id' => $constitucion->id ?? null,
            'instrumento_id' => $instrumentoNotarial->id ?? null
        ]);
        
        // Obtener estado si existe
        $estado = null;
        if ($instrumentoNotarial && $instrumentoNotarial->relationLoaded('estado')) {
            $estado = $instrumentoNotarial->estado;
        } elseif ($instrumentoNotarial && $instrumentoNotarial->estado_id) {
            $estado = $instrumentoNotarial->estado()->first();
        }
        
        $resultado = [];
        foreach ($accionistas as $accionista) {
            $resultado[] = [
                'nombre' => $accionista->nombre ?? '',
                'rfc' => $accionista->rfc ?? '',
                'porcentaje_participacion' => $accionista->porcentaje_participacion ?? '',
                // Datos del instrumento notarial de la constitución
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
        
        return $resultado;
    }
} 
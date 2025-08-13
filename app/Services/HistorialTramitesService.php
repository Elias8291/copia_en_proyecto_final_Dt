<?php

namespace App\Services;

use App\Models\Tramite;
use App\Models\Proveedor;
use App\Enums\TramiteStatus;
use Illuminate\Support\Collection;

class HistorialTramitesService
{
    /**
     * Obtiene el historial de trámites para un RFC específico
     */
    public function obtenerHistorialPorRfc(string $rfc): Collection
    {
        return Tramite::with([
            'proveedor:id,rfc,tipo_persona',
            'datosGenerales' => function($query) {
                $query->select('tramite_id', 'razon_social')
                      ->orderBy('created_at', 'desc')
                      ->limit(1);
            }
        ])
        ->whereHas('proveedor', function($query) use ($rfc) {
            $query->where('rfc', $rfc);
        })
        ->orderBy('created_at', 'desc')
        ->get()
        ->map(function($tramite) {
            return [
                'id' => $tramite->id,
                'tipo_tramite' => $tramite->tipo_tramite,
                'status' => $tramite->status,
                'created_at' => $tramite->created_at,
                'updated_at' => $tramite->updated_at,
                'razon_social' => $tramite->datosGenerales->first()->razon_social ?? 'Sin datos',
                'tipo_persona' => $tramite->proveedor->tipo_persona ?? 'Sin especificar',
                'rfc' => $tramite->proveedor->rfc
            ];
        });
    }

    /**
     * Obtiene el historial de trámites para un trámite específico (por su RFC)
     */
    public function obtenerHistorialPorTramite(int $tramiteId): Collection
    {
        $tramite = Tramite::with('proveedor:id,rfc')->findOrFail($tramiteId);
        
        return $this->obtenerHistorialPorRfc($tramite->proveedor->rfc);
    }

    /**
     * Obtiene estadísticas del historial
     */
    public function obtenerEstadisticasHistorial(string $rfc): array
    {
        $tramites = $this->obtenerHistorialPorRfc($rfc);
        
        return [
            'total' => $tramites->count(),
            'aprobados' => $tramites->where('status', TramiteStatus::APROBADO->value)->count(),
            'rechazados' => $tramites->where('status', TramiteStatus::RECHAZADO->value)->count(),
            'pendientes' => $tramites->where('status', TramiteStatus::PENDIENTE->value)->count(),
            'en_revision' => $tramites->whereIn('status', [
                TramiteStatus::REVISION_DIGITAL->value,
                TramiteStatus::REVISION_PRESENCIAL->value,
                TramiteStatus::REVISION_DOMICILIARIA->value
            ])->count(),
        ];
    }

    /**
     * Verifica si un RFC tiene historial
     */
    public function tieneHistorial(string $rfc): bool
    {
        return Tramite::whereHas('proveedor', function($query) use ($rfc) {
            $query->where('rfc', $rfc);
        })->exists();
    }
} 
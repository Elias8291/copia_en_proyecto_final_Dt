<?php

namespace App\Services\Proveedores;

use App\Models\Proveedor;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

use Carbon\Carbon;

class BusquedaProveedorService
{
    /**
     * Busca un proveedor por RFC
     */
    public function buscarPorRFC(string $rfc): ?Proveedor
    {
        return Proveedor::where('rfc', $rfc)->first();
    }

    /**
     * Busca un proveedor por correo electrónico del usuario asociado
     */
    public function buscarPorCorreo(string $correo): ?Proveedor
    {
        return Proveedor::whereHas('user', function ($query) use ($correo) {
            $query->where('correo', $correo);
        })->first();
    }

    /**
     * Obtiene proveedores con filtros aplicados
     */
    public function obtenerConFiltros(array $filtros = []): Builder
    {
        $query = Proveedor::with(['user']);

        // Filtro de búsqueda general
        if (!empty($filtros['search'])) {
            $search = $filtros['search'];
            $query->where(function ($q) use ($search) {
                $q->where('rfc', 'like', "%{$search}%")
                    ->orWhere('pv_numero', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('nombre', 'like', "%{$search}%")
                            ->orWhere('correo', 'like', "%{$search}%");
                    })
                    // Buscar también en datos generales del último trámite
                    ->orWhereHas('tramites.datosGenerales', function ($dgQuery) use ($search) {
                        $dgQuery->where('razon_social', 'like', "%{$search}%");
                    });
            });
        }

        // Filtro por estado
        if (!empty($filtros['estado'])) {
            $query->where('estado_padron', $filtros['estado']);
        }

        // Filtro por tipo de persona
        if (!empty($filtros['tipo_persona'])) {
            $query->where('tipo_persona', $filtros['tipo_persona']);
        }

        // Filtro por fecha de vencimiento exacta
        if (!empty($filtros['fecha_vencimiento'])) {
            $query->where('fecha_vencimiento_padron', $filtros['fecha_vencimiento']);
        }

        // Filtro por categorías de vencimiento
        if (!empty($filtros['vencimiento'])) {
            switch ($filtros['vencimiento']) {
                case 'vencido':
                    $query->where('fecha_vencimiento_padron', '<', now());
                    break;
                case 'por_vencer':
                    $fechaLimite = now()->addDays(30);
                    $query->whereBetween('fecha_vencimiento_padron', [now(), $fechaLimite]);
                    break;
                case 'sin_fecha':
                    $query->whereNull('fecha_vencimiento_padron');
                    break;
            }
        }

        // Filtro por trimestre
        if (!empty($filtros['trimestre'])) {
            $this->aplicarFiltroTrimestre($query, $filtros['trimestre'], $filtros['año'] ?? now()->year);
        }

        // Filtro por año específico
        if (!empty($filtros['año']) && empty($filtros['trimestre'])) {
            $query->whereYear('fecha_vencimiento_padron', $filtros['año']);
        }



        return $query;
    }

    /**
     * Obtiene estadísticas de proveedores
     */
    public function obtenerEstadisticas(): array
    {
        $total = Proveedor::count();
        $activos = Proveedor::where('estado_padron', 'Activo')->count();
        $inactivos = Proveedor::where('estado_padron', 'Inactivo')->count();
        $vencidos = Proveedor::where('estado_padron', 'Vencido')->count();
        $pendientes = Proveedor::where('estado_padron', 'Pendiente')->count();

        // Próximos a vencer (30 días)
        $fechaLimite = now()->addDays(30);
        $proximosAVencer = Proveedor::where('fecha_vencimiento_padron', '<=', $fechaLimite)
            ->where('fecha_vencimiento_padron', '>=', now())
            ->where('estado_padron', 'Activo')
            ->count();

        return [
            'total' => $total,
            'activos' => $activos,
            'inactivos' => $inactivos,
            'vencidos' => $vencidos,
            'pendientes' => $pendientes,
            'proximos_a_vencer' => $proximosAVencer,
            'porcentaje_activos' => $total > 0 ? round(($activos / $total) * 100, 2) : 0,
        ];
    }

    /**
     * Obtiene proveedores próximos a vencer
     */
    public function obtenerProximosAVencer(int $diasAnticipacion = 30): Builder
    {
        $fechaLimite = now()->addDays($diasAnticipacion);
        
        return Proveedor::where('fecha_vencimiento_padron', '<=', $fechaLimite)
            ->where('fecha_vencimiento_padron', '>=', now())
            ->where('estado_padron', 'Activo')
            ->orderBy('fecha_vencimiento_padron');
    }

    /**
     * Verifica si un proveedor está próximo a vencer
     */
    public function estaProximoAVencer(Proveedor $proveedor, int $diasAnticipacion = 30): bool
    {
        if (!$proveedor->fecha_vencimiento_padron || $proveedor->estado_padron !== 'Activo') {
            return false;
        }

        $fechaLimite = now()->addDays($diasAnticipacion);
        
        return $proveedor->fecha_vencimiento_padron <= $fechaLimite &&
               $proveedor->fecha_vencimiento_padron >= now();
    }

    /**
     * Aplica filtro por trimestre
     */
    private function aplicarFiltroTrimestre(Builder $query, string $trimestre, int $año): void
    {
        switch ($trimestre) {
            case 'Q1':
                $fechaInicio = Carbon::create($año, 1, 1);
                $fechaFin = Carbon::create($año, 3, 31);
                break;
            case 'Q2':
                $fechaInicio = Carbon::create($año, 4, 1);
                $fechaFin = Carbon::create($año, 6, 30);
                break;
            case 'Q3':
                $fechaInicio = Carbon::create($año, 7, 1);
                $fechaFin = Carbon::create($año, 9, 30);
                break;
            case 'Q4':
                $fechaInicio = Carbon::create($año, 10, 1);
                $fechaFin = Carbon::create($año, 12, 31);
                break;
            default:
                return;
        }

        $query->whereBetween('fecha_vencimiento_padron', [
            $fechaInicio->startOfDay(),
            $fechaFin->endOfDay()
        ]);
    }


}
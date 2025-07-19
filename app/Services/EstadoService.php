<?php

namespace App\Services;

use App\Models\Estado;
use Illuminate\Support\Facades\Cache;

class EstadoService
{
    /**
     * Obtiene todos los estados ordenados por nombre
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllEstados()
    {
        return Cache::remember('estados_all', 60*24, function () {
            return Estado::orderBy('nombre')->get();
        });
    }

    /**
     * Obtiene los estados formateados para usar en selects
     *
     * @return array
     */
    public function getEstadosForSelect()
    {
        return Cache::remember('estados_select', 60*24, function () {
            return Estado::orderBy('nombre')
                ->pluck('nombre', 'id')
                ->toArray();
        });
    }

    /**
     * Obtiene los estados por país
     *
     * @param int $paisId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getEstadosByPais($paisId = 1)
    {
        $cacheKey = 'estados_pais_' . $paisId;
        
        return Cache::remember($cacheKey, 60*24, function () use ($paisId) {
            return Estado::where('pais_id', $paisId)
                ->orderBy('nombre')
                ->get();
        });
    }

    /**
     * Obtiene los estados por país formateados para usar en selects
     *
     * @param int $paisId
     * @return array
     */
    public function getEstadosSelectByPais($paisId = 1)
    {
        $cacheKey = 'estados_select_pais_' . $paisId;
        
        return Cache::remember($cacheKey, 60*24, function () use ($paisId) {
            return Estado::where('pais_id', $paisId)
                ->orderBy('nombre')
                ->pluck('nombre', 'id')
                ->toArray();
        });
    }
}
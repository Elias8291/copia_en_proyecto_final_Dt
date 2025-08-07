<?php

namespace App\Services\Tramites;

use Illuminate\Http\Request;

abstract class BaseService
{
    protected function obtenerValorOculto(Request $request, string $campo): ?string
    {
        // Intentar obtener el valor en este orden: campo normal, campo hidden, campo fallback
        return $request->$campo ?: $request->{$campo . '_hidden'} ?: $request->{$campo . '_fallback'};
    }
    
    protected function validarArchivo($archivo): bool
    {
        return $archivo && $archivo->isValid();
    }
    
    protected function generarNombreUnico(string $prefijo, int $id, string $nombreOriginal): string
    {
        return $prefijo . '_' . time() . '_' . $id . '_' . $nombreOriginal;
    }
} 
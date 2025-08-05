<?php

namespace App\Services\Tramites;

use Illuminate\Http\Request;

abstract class BaseService
{
    protected function obtenerValorOculto(Request $request, string $campo): ?string
    {
        return $request->$campo ?: $request->{$campo . '_hidden'};
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
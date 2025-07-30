<?php

namespace App\Helpers;

use App\Models\Tramite;
use App\Services\DocumentosService;

class DocumentosHelper
{
    /**
     * Obtiene archivos del catálogo 2 para un trámite
     */
    public static function catalogo2Archivos(Tramite $tramite)
    {
        $service = app(DocumentosService::class);
        return $service->obtenerArchivosCatalogo2($tramite);
    }

    /**
     * Obtiene información completa del catálogo 2
     */
    public static function catalogo2Info(Tramite $tramite)
    {
        $service = app(DocumentosService::class);
        return $service->obtenerInformacionCatalogo2($tramite);
    }

    /**
     * Obtiene solo el catálogo 2
     */
    public static function catalogo2()
    {
        $service = app(DocumentosService::class);
        return $service->obtenerCatalogo2();
    }

    /**
     * Verifica si un trámite tiene archivos del catálogo 2
     */
    public static function tieneCatalogo2(Tramite $tramite): bool
    {
        $archivos = self::catalogo2Archivos($tramite);
        return $archivos->count() > 0;
    }

    /**
     * Obtiene el conteo de archivos del catálogo 2
     */
    public static function catalogo2Count(Tramite $tramite): int
    {
        $archivos = self::catalogo2Archivos($tramite);
        return $archivos->count();
    }
} 
 
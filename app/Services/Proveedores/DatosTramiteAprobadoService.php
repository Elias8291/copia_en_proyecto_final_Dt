<?php

declare(strict_types=1);

namespace App\Services\Proveedores;

use App\Models\Proveedor;
use App\Models\Tramite;
use App\Models\DatosGenerales;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Builder;

class DatosTramiteAprobadoService
{
    /**
     * Obtiene el último trámite aprobado del proveedor con todos sus datos relacionados
     */
    public function obtenerUltimoTramiteAprobado(Proveedor $proveedor): ?Tramite
    {
        try {
            return $proveedor->tramites()
                ->where('estado', 'Aprobado')
                ->with([
                    'datosGenerales',
                    'proveedor', // Para obtener RFC del proveedor
                    'direccion.coordenadas',
                    'direccion.estado',
                    'apoderadoLegal',
                    'accionistas',
                    'actividades',
                    'oficios'
                ])
                ->latest('created_at')
                ->first();
        } catch (\Exception $e) {
            Log::error('Error al obtener último trámite aprobado: ' . $e->getMessage(), [
                'proveedor_id' => $proveedor->id,
                'rfc' => $proveedor->rfc
            ]);
            return null;
        }
    }

    /**
     * Obtiene los datos generales del último trámite aprobado
     */
    public function obtenerDatosGeneralesUltimoTramite(Proveedor $proveedor): ?DatosGenerales
    {
        $tramite = $this->obtenerUltimoTramiteAprobado($proveedor);
        
        if (!$tramite) {
            return null;
        }

        return $tramite->datosGenerales;
    }

    /**
     * Obtiene información completa del último trámite aprobado incluyendo datos generales
     */
    public function obtenerInformacionCompletaUltimoTramite(Proveedor $proveedor): ?array
    {
        $tramite = $this->obtenerUltimoTramiteAprobado($proveedor);
        
        if (!$tramite) {
            return null;
        }

        $datosGenerales = $tramite->datosGenerales;

        return [
            'tramite' => [
                'id' => $tramite->id,
                'tipo' => $tramite->tipo,
                'estado' => $tramite->estado,
                'fecha_creacion' => $tramite->created_at,
                'fecha_aprobacion' => $tramite->updated_at,
                'observaciones' => $tramite->observaciones
            ],
            'datos_generales' => $datosGenerales ? [
                'id' => $datosGenerales->id,
                'rfc' => $datosGenerales->rfc ?: $tramite->proveedor->rfc, // Usar RFC del proveedor como fallback
                'curp' => $datosGenerales->curp,
                'razon_social' => $datosGenerales->razon_social,
                'pagina_web' => $datosGenerales->pagina_web,
                'telefono' => $datosGenerales->telefono,
                'fecha_creacion' => $datosGenerales->created_at,
                'fecha_actualizacion' => $datosGenerales->updated_at
            ] : [
                'rfc' => $tramite->proveedor->rfc, // Si no hay datos generales, al menos incluir RFC del proveedor
                'razon_social' => null,
                'curp' => null,
                'pagina_web' => null,
                'telefono' => null
            ],
            'apoderado_legal' => $tramite->apoderadoLegal ? [
                'id' => $tramite->apoderadoLegal->id,
                'nombre' => $tramite->apoderadoLegal->nombre,
                'rfc' => $tramite->apoderadoLegal->rfc,
                'curp' => $tramite->apoderadoLegal->curp,
                'domicilio' => $tramite->apoderadoLegal->domicilio
            ] : null,
            'direccion' => $tramite->direccion ? [
                'id' => $tramite->direccion->id,
                'calle' => $tramite->direccion->calle,
                'numero_exterior' => $tramite->direccion->numero_exterior,
                'numero_interior' => $tramite->direccion->numero_interior,
                'colonia' => $tramite->direccion->colonia,
                'codigo_postal' => $tramite->direccion->codigo_postal,
                'municipio' => $tramite->direccion->municipio,
                'estado' => $tramite->direccion->estado ? $tramite->direccion->estado->nombre : null,
                'pais' => $tramite->direccion->pais,
                'coordenadas' => $tramite->direccion->coordenadas ? [
                    'latitud' => $tramite->direccion->coordenadas->latitud,
                    'longitud' => $tramite->direccion->coordenadas->longitud
                ] : null
            ] : null,
            'accionistas' => $tramite->accionistas?->map(function ($accionista) {
                return [
                    'id' => $accionista->id,
                    'nombre' => $accionista->nombre,
                    'rfc' => $accionista->rfc,
                    'curp' => $accionista->curp,
                    'porcentaje_participacion' => $accionista->porcentaje_participacion
                ];
            })?->toArray() ?? [],
            'actividades_economicas' => $tramite->actividadesEconomicas?->map(function ($actividad) {
                return [
                    'id' => $actividad->id,
                    'codigo' => $actividad->codigo,
                    'descripcion' => $actividad->descripcion,
                    'porcentaje' => $actividad->porcentaje
                ];
            })?->toArray() ?? [],
            'actividades' => $tramite->actividades?->map(function ($actividad) {
                return [
                    'id' => $actividad->id,
                    'codigo' => $actividad->codigo,
                    'descripcion' => $actividad->descripcion
                ];
            })?->toArray() ?? [],
            'instrumento_notarial' => $tramite->instrumentoNotarial ? [
                'id' => $tramite->instrumentoNotarial->id,
                'numero_instrumento' => $tramite->instrumentoNotarial->numero_instrumento,
                'fecha_instrumento' => $tramite->instrumentoNotarial->fecha_instrumento,
                'notario' => $tramite->instrumentoNotarial->notario,
                'numero_notaria' => $tramite->instrumentoNotarial->numero_notaria
            ] : null,
            'oficios' => $tramite->oficios?->map(function ($oficio) {
                return [
                    'id' => $oficio->id,
                    'numero_oficio' => $oficio->numero_oficio,
                    'fecha_oficio' => $oficio->fecha_oficio,
                    'tipo' => $oficio->tipo,
                    'estado' => $oficio->estado
                ];
            })?->toArray() ?? [],
            'cita' => $tramite->cita ? [
                'id' => $tramite->cita->id,
                'fecha' => $tramite->cita->fecha,
                'hora' => $tramite->cita->hora,
                'tipo' => $tramite->cita->tipo,
                'estado' => $tramite->cita->estado
            ] : null
        ];
    }

    /**
     * Obtiene solo los datos generales del último trámite aprobado
     */
    public function obtenerSoloDatosGenerales(Proveedor $proveedor): ?array
    {
        $datosGenerales = $this->obtenerDatosGeneralesUltimoTramite($proveedor);
        
        if (!$datosGenerales) {
            return null;
        }

        return [
            'curp' => $datosGenerales->curp,
            'razon_social' => $datosGenerales->razon_social,
            'pagina_web' => $datosGenerales->pagina_web,
            'telefono' => $datosGenerales->telefono,
            'fecha_creacion' => $datosGenerales->created_at,
            'fecha_actualizacion' => $datosGenerales->updated_at
        ];
    }

    /**
     * Verifica si el proveedor tiene algún trámite aprobado
     */
    public function tieneTramiteAprobado(Proveedor $proveedor): bool
    {
        return $proveedor->tramites()
            ->where('estado', 'Aprobado')
            ->exists();
    }

    /**
     * Obtiene la fecha del último trámite aprobado
     */
    public function obtenerFechaUltimoTramiteAprobado(Proveedor $proveedor): ?string
    {
        $tramite = $this->obtenerUltimoTramiteAprobado($proveedor);
        
        return $tramite ? $tramite->updated_at->format('d/m/Y') : null;
    }

    /**
     * Obtiene el tipo del último trámite aprobado
     */
    public function obtenerTipoUltimoTramiteAprobado(Proveedor $proveedor): ?string
    {
        $tramite = $this->obtenerUltimoTramiteAprobado($proveedor);
        
        return $tramite ? $tramite->tipo : null;
    }
} 
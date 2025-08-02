<?php

namespace App\Services\Core;

use App\Models\Tramite;
use App\Models\Proveedor;
use Illuminate\Support\Facades\Log;

/**
 * Servicio base para preparación común de datos
 * Responsabilidad: Lógica reutilizable entre trámites y revisión
 */
class BaseDataService
{
    /**
     * Relaciones estándar para cargar en trámites
     */
    private const RELACIONES_BASICAS = [
        'proveedor.user',
        'datosGenerales',
        'archivos.catalogoArchivo'
    ];

    private const RELACIONES_COMPLETAS = [
        'proveedor.user',
        'revisadoPor',
        'datosGenerales',
        'datosConstitutivos',
        'apoderadoLegal',
        'contactos',
        'accionistas',
        'direcciones.estado',
        'actividades.sector',
        'archivos.catalogoArchivo',
        'revisionSecciones'
    ];

    private const RELACIONES_CORRECCION = [
        'proveedor',
        'datosGenerales',
        'datosConstitutivos.instrumentoNotarial',
        'apoderadoLegal.instrumentoNotarial',
        'contactos',
        'accionistas',
        'direcciones.estado',
        'actividades.sector',
        'archivos.catalogoArchivo'
    ];

    private const RELACIONES_COTEJO = [
        'proveedor.user',
        'datosGenerales',
        'direccion.coordenadas',
        'archivos.catalogoArchivo'
    ];

    /**
     * Carga relaciones básicas en un trámite
     */
    public function cargarRelacionesBasicas(Tramite $tramite): Tramite
    {
        return $tramite->load(self::RELACIONES_BASICAS);
    }

    /**
     * Carga relaciones completas para revisión digital
     */
    public function cargarRelacionesCompletas(Tramite $tramite): Tramite
    {
        return $tramite->load(self::RELACIONES_COMPLETAS);
    }

    /**
     * Carga relaciones para corrección de trámites
     */
    public function cargarRelacionesCorreccion(Tramite $tramite): Tramite
    {
        return $tramite->load(self::RELACIONES_CORRECCION);
    }

    /**
     * Carga relaciones para cotejo domiciliario
     */
    public function cargarRelacionesCotejo(Tramite $tramite): Tramite
    {
        return $tramite->load(self::RELACIONES_COTEJO);
    }

    /**
     * Obtiene información básica de identidad de un trámite
     */
    public function obtenerInformacionIdentidad(Tramite $tramite): array
    {
        $this->cargarRelacionesBasicas($tramite);
        
        return [
            'proveedor' => [
                'id' => $tramite->proveedor->id,
                'rfc' => $tramite->proveedor->rfc,
                'tipo_persona' => $tramite->proveedor->tipo_persona,
                'usuario' => [
                    'nombre' => $tramite->proveedor->user->name,
                    'email' => $tramite->proveedor->user->email
                ]
            ],
            'datos_generales' => $tramite->datosGenerales ? [
                'razon_social' => $tramite->datosGenerales->razon_social,
                'nombre_comercial' => $tramite->datosGenerales->nombre_comercial,
                'telefono' => $tramite->datosGenerales->telefono,
                'email' => $tramite->datosGenerales->email
            ] : null,
            'tramite' => [
                'id' => $tramite->id,
                'tipo' => $tramite->tipo_tramite,
                'estado' => $tramite->estado,
                'fecha_creacion' => $tramite->created_at,
                'observaciones' => $tramite->observaciones
            ]
        ];
    }

    /**
     * Calcula estadísticas básicas de documentos
     */
    public function calcularEstadisticasDocumentos(Tramite $tramite): array
    {
        $total = $tramite->archivos->count();
        $aprobados = $tramite->archivos->where('aprobado', true)->count();
        $rechazados = $tramite->archivos->where('aprobado', false)->count();
        $pendientes = $tramite->archivos->where('aprobado', null)->count();

        return [
            'total' => $total,
            'aprobados' => $aprobados,
            'rechazados' => $rechazados,
            'pendientes' => $pendientes,
            'porcentaje_completado' => $total > 0 ? round(($aprobados / $total) * 100, 2) : 0
        ];
    }

    /**
     * Calcula estadísticas básicas de secciones
     */
    public function calcularEstadisticasSecciones(Tramite $tramite): array
    {
        $secciones = $tramite->revisionSecciones ?? collect();
        $total = $secciones->count();
        $aprobadas = $secciones->where('estado', 'Aprobado')->count();
        $rechazadas = $secciones->where('estado', 'Rechazado')->count();
        $pendientes = $secciones->where('estado', 'Pendiente')->count();

        return [
            'total' => $total,
            'aprobadas' => $aprobadas,
            'rechazadas' => $rechazadas,
            'pendientes' => $pendientes,
            'porcentaje_completado' => $total > 0 ? round(($aprobadas / $total) * 100, 2) : 0
        ];
    }

    /**
     * Obtiene información adicional común de un trámite
     */
    public function obtenerInformacionAdicional(Tramite $tramite): array
    {
        $estadisticasDocumentos = $this->calcularEstadisticasDocumentos($tramite);
        
        return [
            'total_archivos' => $estadisticasDocumentos['total'],
            'archivos_aprobados' => $estadisticasDocumentos['aprobados'],
            'archivos_rechazados' => $estadisticasDocumentos['rechazados'],
            'archivos_pendientes' => $estadisticasDocumentos['pendientes'],
            'tiene_cita' => $tramite->cita !== null,
            'tiene_oficio' => $tramite->oficios->count() > 0,
            'dias_desde_creacion' => $tramite->created_at->diffInDays(now()),
            'dias_desde_actualizacion' => $tramite->updated_at->diffInDays(now())
        ];
    }

    /**
     * Obtiene coordenadas de un trámite
     */
    public function obtenerCoordenadas(Tramite $tramite): ?array
    {
        if ($tramite->direccion && $tramite->direccion->coordenadas) {
            return [
                'latitud' => $tramite->direccion->coordenadas->latitud,
                'longitud' => $tramite->direccion->coordenadas->longitud
            ];
        }

        return null;
    }

    /**
     * Formatea dirección completa
     */
    public function formatearDireccionCompleta(Tramite $tramite): ?string
    {
        if (!$tramite->direccion) {
            return null;
        }

        $direccion = $tramite->direccion;
        $partes = array_filter([
            $direccion->nombre_vialidad,
            $direccion->numero_exterior,
            $direccion->numero_interior ? "Int. {$direccion->numero_interior}" : null,
            $direccion->colonia,
            $direccion->codigo_postal ? "C.P. {$direccion->codigo_postal}" : null,
            $direccion->estado ? $direccion->estado->nombre : null
        ]);

        return implode(', ', $partes);
    }

    /**
     * Actualiza tipo de persona si es necesario
     */
    public function actualizarTipoPersonaSiEsNecesario(Tramite $tramite): void
    {
        if ($tramite->proveedor && !$tramite->proveedor->tipo_persona) {
            $tipoPersona = $this->determinarTipoPersona($tramite->proveedor);
            
            if ($tipoPersona) {
                $tramite->proveedor->update(['tipo_persona' => $tipoPersona]);
                $tramite->load('proveedor'); // Recargar la relación
                
                Log::info('Tipo de persona actualizado automáticamente', [
                    'proveedor_id' => $tramite->proveedor->id,
                    'tipo_persona' => $tipoPersona
                ]);
            }
        }
    }

    /**
     * Determina el tipo de persona basado en el RFC
     */
    private function determinarTipoPersona(Proveedor $proveedor): ?string
    {
        if (!$proveedor->rfc) {
            return null;
        }

        // RFC de persona física: 13 caracteres (CURP)
        // RFC de persona moral: 12 caracteres
        return strlen($proveedor->rfc) === 13 ? 'Física' : 'Moral';
    }

    /**
     * Obtiene títulos estándar para trámites
     */
    public function obtenerTituloTramite(string $tipo): string
    {
        $titulos = [
            'inscripcion' => 'Inscripción al Padrón de Proveedores',
            'renovacion' => 'Renovación de Registro',
            'actualizacion' => 'Actualización de Datos'
        ];

        return $titulos[$tipo] ?? 'Trámite de ' . ucfirst($tipo);
    }

    /**
     * Obtiene descripciones estándar para trámites
     */
    public function obtenerDescripcionTramite(string $tipo): string
    {
        $descripciones = [
            'inscripcion' => 'Complete el formulario para inscribirse al padrón de proveedores.',
            'renovacion' => 'Renueve su registro en el padrón de proveedores.',
            'actualizacion' => 'Actualice sus datos en el padrón de proveedores.'
        ];

        return $descripciones[$tipo] ?? 'Complete el formulario del trámite.';
    }
}
<?php

namespace App\Services\Revision;

use App\Models\Tramite;
use App\Services\ProveedorService;
use App\Services\DocumentosService;
use App\Services\Core\BaseDataService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * Servicio especializado para la visualización y preparación de datos de revisión
 * Responsabilidad: Preparar datos para vistas de revisión y obtener información estructurada
 */
class RevisionVisualizacionService
{
    public function __construct(
        private ProveedorService $proveedorService,
        private DocumentosService $documentosService,
        private BaseDataService $baseDataService
    ) {}

    /**
     * Obtiene datos paginados de trámites para la vista index
     */
    public function obtenerTramitesPaginados(Request $request): array
    {
        $perPage = $this->validarPaginacion($request->get('per_page', 10));
        
        $tramites = Tramite::with(['proveedor.user', 'revisadoPor', 'datosGenerales'])
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);

        $tramites->appends($request->query());

        return [
            'tramites' => $tramites,
            'perPage' => $perPage,
            'allowedPerPage' => [5, 10, 25, 50, 100]
        ];
    }

    /**
     * Prepara datos completos de un trámite para revisión digital
     */
    public function prepararDatosRevisionDigital(Tramite $tramite): array
    {
        try {
            // Usar servicio base para cargar relaciones
            $this->baseDataService->cargarRelacionesCompletas($tramite);
            $this->baseDataService->actualizarTipoPersonaSiEsNecesario($tramite);

            return [
                'tramite' => $tramite,
                'tipo_persona' => $tramite->proveedor->tipo_persona,
                'informacion_adicional' => $this->baseDataService->obtenerInformacionAdicional($tramite),
                'resumen_documentos' => $this->baseDataService->calcularEstadisticasDocumentos($tramite),
                'resumen_secciones' => $this->baseDataService->calcularEstadisticasSecciones($tramite)
            ];

        } catch (\Exception $e) {
            Log::error('Error al preparar datos de revisión digital', [
                'tramite_id' => $tramite->id,
                'error' => $e->getMessage()
            ]);

            throw $e;
        }
    }

    /**
     * Prepara datos básicos de un trámite para vista simple
     */
    public function prepararDatosBasicos(Tramite $tramite): array
    {
        $this->baseDataService->cargarRelacionesBasicas($tramite);
        $tramite->load('revisionSecciones'); // Cargar secciones adicionales

        return [
            'tramite' => $tramite,
            'resumen_documentos' => $this->baseDataService->calcularEstadisticasDocumentos($tramite)
        ];
    }

    /**
     * Prepara datos para cotejo domiciliario
     */
    public function prepararDatosCotejoDomiciliario(Tramite $tramite): array
    {
        $this->baseDataService->cargarRelacionesCotejo($tramite);

        return [
            'tramite' => $tramite,
            'coordenadas' => $this->baseDataService->obtenerCoordenadas($tramite),
            'direccion_completa' => $this->baseDataService->formatearDireccionCompleta($tramite)
        ];
    }

    /**
     * Obtiene información de identidad estructurada del trámite
     */
    public function obtenerInformacionIdentidad(Tramite $tramite): array
    {
        return $this->baseDataService->obtenerInformacionIdentidad($tramite);
    }

    /**
     * Obtiene estados de secciones y documentos para análisis
     */
    public function obtenerEstadosRevision(Tramite $tramite): array
    {
        $tramite->load(['revisionSecciones', 'archivos.catalogoArchivo']);
        
        $seccionesPendientes = [];
        $seccionesRechazadas = [];
        $documentosPendientes = [];
        $documentosRechazados = [];
        
        // Analizar secciones
        foreach ($tramite->revisionSecciones as $revision) {
            if ($revision->estado === 'Pendiente') {
                $seccionesPendientes[] = $revision->seccion;
            } elseif ($revision->estado === 'Rechazado') {
                $seccionesRechazadas[] = $revision->seccion;
            }
        }
        
        // Analizar documentos
        foreach ($tramite->archivos as $archivo) {
            $nombreDocumento = $archivo->catalogoArchivo->nombre ?? 'Documento';
            
            if ($archivo->aprobado === null) {
                $documentosPendientes[] = $nombreDocumento;
            } elseif ($archivo->aprobado === false) {
                $documentosRechazados[] = $nombreDocumento;
            }
        }
        
        return [
            'secciones' => [
                'pendientes' => $seccionesPendientes,
                'rechazadas' => $seccionesRechazadas,
                'total' => $tramite->revisionSecciones->count()
            ],
            'documentos' => [
                'pendientes' => $documentosPendientes,
                'rechazados' => $documentosRechazados,
                'total' => $tramite->archivos->count()
            ]
        ];
    }

    /**
     * Obtiene archivos del catálogo 2 con información estructurada
     */
    public function obtenerArchivosCatalogo2(Tramite $tramite): array
    {
        try {
            $archivos = $this->documentosService->obtenerArchivosCatalogo2($tramite);
            $catalogo = $this->documentosService->obtenerCatalogo2();
            $informacion = $this->documentosService->obtenerInformacionCatalogo2($tramite);

            return [
                'success' => true,
                'data' => $informacion,
                'archivos' => $archivos,
                'catalogo' => $catalogo,
                'total' => $archivos->count(),
                'resumen' => $this->generarResumenCatalogo2($archivos)
            ];
        } catch (\Exception $e) {
            Log::error('Error obteniendo archivos del catálogo 2', [
                'tramite_id' => $tramite->id,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'message' => 'Error al obtener los archivos del catálogo 2',
                'data' => null
            ];
        }
    }

    /**
     * Valida el parámetro de paginación
     */
    private function validarPaginacion(int $perPage): int
    {
        $allowedPerPage = [5, 10, 25, 50, 100];
        return in_array($perPage, $allowedPerPage) ? $perPage : 10;
    }



    /**
     * Genera resumen del catálogo 2
     */
    private function generarResumenCatalogo2($archivos): array
    {
        $total = $archivos->count();
        $aprobados = $archivos->where('aprobado', true)->count();
        $rechazados = $archivos->where('aprobado', false)->count();

        return [
            'total' => $total,
            'aprobados' => $aprobados,
            'rechazados' => $rechazados,
            'pendientes' => $total - $aprobados - $rechazados,
            'porcentaje_aprobados' => $total > 0 ? round(($aprobados / $total) * 100, 2) : 0
        ];
    }
}
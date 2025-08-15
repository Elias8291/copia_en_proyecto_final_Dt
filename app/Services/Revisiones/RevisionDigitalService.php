<?php

namespace App\Services\Revisiones;

use App\Services\RevisionService;
use App\Services\Tramites\DataRetrievalService;
use App\Services\AsignacionPvService;
use App\Models\Tramite;
use App\Models\Proveedor;
use App\ViewModels\FormDataViewModel;
use Carbon\Carbon;

class RevisionDigitalService extends RevisionService
{
    private DataRetrievalService $dataRetrievalService;
    private AsignacionPvService $asignacionPvService;

    public function __construct(DataRetrievalService $dataRetrievalService = null, AsignacionPvService $asignacionPvService = null)
    {
        $this->dataRetrievalService = $dataRetrievalService ?? app(DataRetrievalService::class);
        $this->asignacionPvService = $asignacionPvService ?? app(AsignacionPvService::class);
    }

    // Obtiene todos los datos necesarios para la revisión digital
    public function obtenerDatosRevisionDigital(int $tramiteId, string $ordenHistorial = 'reciente'): array
    {
        $datos = $this->obtenerDatosRevisionBase($tramiteId);
        $tramite = Tramite::findOrFail($tramiteId);
        
        $datos['tipoRevision'] = 'Digital';
        $datos['vistaRevision'] = 'revisiones.revision-digital';
        $datos['archivosSubidos'] = $this->prepararArchivosParaCotejo($datos['archivos']);
        $datos['historialTramites'] = $this->obtenerHistorialTramites($tramiteId, $ordenHistorial);
        $datos['viewModel'] = $this->obtenerViewModel($tramiteId);
        $datos['estadisticasHistorial'] = $this->obtenerEstadisticasHistorial($tramiteId);
        $datos['rfc'] = $tramite->proveedor->rfc; // Agregar RFC
        $datos['ordenHistorial'] = $ordenHistorial;
        
        return $datos;
    }

    // Obtiene el historial de trámites por RFC (todos los proveedores con el mismo RFC)
    private function obtenerHistorialTramites(int $tramiteId, string $ordenHistorial = 'reciente'): \Illuminate\Support\Collection
    {
        $tramite = Tramite::findOrFail($tramiteId);
        $rfc = $tramite->proveedor->rfc;
        
        $query = Tramite::whereHas('proveedor', function($query) use ($rfc) {
            $query->where('rfc', $rfc);
        })->with(['proveedor', 'datosGenerales', 'oficios']);
        
        if ($ordenHistorial === 'pasados') {
            $query->orderByRaw('COALESCE(fecha_finalizacion, fecha_inicio, created_at) ASC');
        } else {
            $query->orderByRaw('COALESCE(fecha_finalizacion, fecha_inicio, created_at) DESC');
        }
        
        return $query->get();
    }

    // Crea el ViewModel con todos los datos del trámite usando DataRetrievalService
    private function obtenerViewModel(int $tramiteId): FormDataViewModel
    {
        $tramite = Tramite::findOrFail($tramiteId);
        
        $datosCompletos = $this->dataRetrievalService->obtenerDatosTramite($tramite);
        $datosCompletos['datos_generales']['tipo_persona'] = $tramite->proveedor->tipo_persona;

        return new FormDataViewModel($datosCompletos);
    }

    // Calcula estadísticas del historial de trámites por RFC
    private function obtenerEstadisticasHistorial(int $tramiteId): array
    {
        $tramite = Tramite::findOrFail($tramiteId);
        $rfc = $tramite->proveedor->rfc;
        
        $tramitesRfc = Tramite::whereHas('proveedor', function($query) use ($rfc) {
            $query->where('rfc', $rfc);
        })->get();
        
        return [
            'total' => $tramitesRfc->count(),
            'aprobados' => $tramitesRfc->where('status', 'Aprobado')->count(),
            'rechazados' => $tramitesRfc->where('status', 'Rechazado')->count(),
            'pendientes' => $tramitesRfc->whereNotIn('status', ['Aprobado', 'Rechazado'])->count(),
        ];
    }

    /**
     * Procesa la asignación de PV y fechas de vigencia para un trámite de inscripción
     * 
     * @param int $tramiteId
     * @param int $numeroProveedor
     * @param string|null $ultimoPvSistema
     * @param string $fechaRevision
     * @return array
     */
    public function procesarAsignacionPv(int $tramiteId, int $numeroProveedor, ?string $ultimoPvSistema, string $fechaRevision): array
    {
        $tramite = Tramite::findOrFail($tramiteId);
        
        // Verificar que sea un trámite de inscripción
        if ($tramite->tipo_tramite !== 'Inscripcion') {
            return [
                'success' => false,
                'message' => 'La asignación de PV solo es válida para trámites de inscripción',
                'datos' => null
            ];
        }
        
        // Obtener el último PV del sistema si no se proporciona
        if (!$ultimoPvSistema) {
            $ultimoPvSistema = $this->obtenerUltimoPvSistema();
        }
        
        // Procesar asignación
        $datosAsignacion = $this->asignacionPvService->asignarPvYVigencia(
            $numeroProveedor,
            $ultimoPvSistema,
            $fechaRevision
        );
        
        // Verificar si hubo errores
        if (!$datosAsignacion['pv']) {
            return [
                'success' => false,
                'message' => $datosAsignacion['notas_validacion'],
                'datos' => $datosAsignacion
            ];
        }
        
        // Aplicar la asignación al proveedor
        $proveedor = $tramite->proveedor;
        $aplicacionExitosa = $this->asignacionPvService->aplicarAsignacion($proveedor, $datosAsignacion);
        
        if (!$aplicacionExitosa) {
            return [
                'success' => false,
                'message' => 'Error al aplicar la asignación al proveedor',
                'datos' => $datosAsignacion
            ];
        }
        
        return [
            'success' => true,
            'message' => 'Asignación de PV procesada exitosamente',
            'datos' => $datosAsignacion
        ];
    }
    
    /**
     * Obtiene el último PV del sistema
     */
    private function obtenerUltimoPvSistema(): ?string
    {
        $ultimoProveedor = Proveedor::whereNotNull('pv_numero')
            ->where('pv_numero', 'like', 'PV%')
            ->orderByRaw('CAST(SUBSTRING(pv_numero, 3) AS UNSIGNED) DESC')
            ->first();
            
        return $ultimoProveedor ? $ultimoProveedor->pv_numero : null;
    }
} 
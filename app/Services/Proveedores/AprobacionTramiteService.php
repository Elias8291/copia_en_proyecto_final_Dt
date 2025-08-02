<?php

declare(strict_types=1);

namespace App\Services\Proveedores;

use App\Models\Tramite;
use App\Services\OficioService;
use App\Services\Core\BaseResponseService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

/**
 * Servicio especializado para aprobación de trámites
 * Responsabilidad: Coordinar aprobación, creación de oficios y manejo de archivos
 */
class AprobacionTramiteService extends BaseResponseService
{
    public function __construct(
        private ProcesadorTramitesService $procesadorTramites,
        private OficioService $oficioService
    ) {}

    /**
     * Aprobar trámite completo con oficio
     */
    public function aprobar(Tramite $tramite): array
    {
        try {
            DB::beginTransaction();

            $resultado = $this->procesadorTramites->procesarTramite($tramite);
            
            if ($resultado['success']) {
                $resultadoOficio = $this->crearOficioAprobacion($tramite);
                
                if ($resultadoOficio['success']) {
                    DB::commit();
                    
                    return array_merge($resultado, [
                        'oficio_creado' => true,
                        'numero_oficio' => $resultadoOficio['numero_oficio'],
                        'oficio_id' => $resultadoOficio['oficio_id']
                    ]);
                }
            }

            DB::rollBack();
            return $resultado;

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Error al aprobar trámite', [
                'tramite_id' => $tramite->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'success' => false,
                'message' => 'Error al procesar la aprobación: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Crear oficio de aprobación y generar PDF
     */
    private function crearOficioAprobacion(Tramite $tramite): array
    {
        try {
            // Crear oficio
            $oficio = $this->oficioService->crearOficioAprobacion($tramite);
            
            // Generar PDF
            $resultadoPdf = $this->generarPdfOficio($oficio);
            
            if ($resultadoPdf['success']) {
                return [
                    'success' => true,
                    'numero_oficio' => $oficio->numero_oficio,
                    'oficio_id' => $oficio->id,
                    'pdf_path' => $resultadoPdf['pdf_path']
                ];
            }

            return [
                'success' => true, // Oficio creado aunque PDF falló
                'numero_oficio' => $oficio->numero_oficio,
                'oficio_id' => $oficio->id,
                'warning' => 'Oficio creado pero PDF no pudo generarse'
            ];

        } catch (\Exception $e) {
            Log::error('Error al crear oficio de aprobación', [
                'tramite_id' => $tramite->id,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'message' => 'Error al crear el oficio: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Generar PDF del oficio
     */
    private function generarPdfOficio($oficio): array
    {
        try {
            $oficioPdfController = app(\App\Http\Controllers\OficioPdfController::class);
            $pdfResponse = $oficioPdfController->generarPdf($oficio);
            
            if (!$pdfResponse) {
                return [
                    'success' => false,
                    'message' => 'No se pudo generar el PDF'
                ];
            }

            // Guardar PDF
            $pdfPath = 'oficios/oficio_' . $oficio->numero_oficio . '.pdf';
            Storage::put('public/' . $pdfPath, $pdfResponse->getContent());
            
            // Actualizar oficio con la ruta del PDF
            $oficio->update([
                'url_documento' => $pdfPath
            ]);

            return [
                'success' => true,
                'pdf_path' => $pdfPath
            ];

        } catch (\Exception $e) {
            Log::error('Error al generar PDF del oficio', [
                'oficio_id' => $oficio->id,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'message' => 'Error al generar PDF: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Respuesta específica para aprobación de trámites
     */
    public function respuestaAprobacion(array $resultado, ?string $rutaRedirect = null): \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
    {
        $mensaje = $resultado['message'];
        
        if ($resultado['pv_asignado'] ?? false) {
            $mensaje .= "\nPV Asignado: {$resultado['pv_asignado']}";
        }
        
        if ($resultado['fecha_vencimiento'] ?? false) {
            $mensaje .= "\nFecha de vencimiento: {$resultado['fecha_vencimiento']}";
        }
        
        if (isset($resultado['numero_oficio'])) {
            $mensaje .= "\nNúmero de Oficio: {$resultado['numero_oficio']}";
        }
        
        $mensaje .= "\n\nSe ha enviado una notificación al usuario.";

        return parent::respuestaExito(
            $resultado,
            $mensaje,
            'Trámite Aprobado',
            $rutaRedirect ?? route('revision.index')
        );
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\Oficio;
use App\Models\Tramite;
use App\Models\Proveedor;
use App\Services\OficioService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

/**
 * Controlador para gestión de oficios del sistema
 */
class OficioController extends Controller
{
    protected OficioService $oficioService;

    public function __construct(OficioService $oficioService)
    {
        $this->oficioService = $oficioService;
    }

    public function descargar(Request $request)
    {
        try {
            $tramiteId = $request->get('tramite_id');
            $proveedorId = $request->get('proveedor_id');
            $archivo = $request->get('archivo');

            Log::info('Solicitud de descarga de oficio', [
                'tramite_id' => $tramiteId,
                'proveedor_id' => $proveedorId,
                'archivo' => $archivo
            ]);

            $oficio = Oficio::where('tramite_id', $tramiteId)
                ->where('proveedor_id', $proveedorId)
                ->first();

            if (!$oficio) {
                Log::warning('Oficio no encontrado para descarga', [
                    'tramite_id' => $tramiteId,
                    'proveedor_id' => $proveedorId
                ]);
                abort(404, 'Oficio no encontrado');
            }

            $rutaArchivo = storage_path('app/public/oficios/' . $archivo);

            if (!file_exists($rutaArchivo)) {
                Log::warning('Archivo PDF no encontrado', [
                    'ruta_archivo' => $rutaArchivo,
                    'oficio_id' => $oficio->id
                ]);
                abort(404, 'Archivo no encontrado');
            }

            Log::info('Descarga de oficio exitosa', [
                'oficio_id' => $oficio->id,
                'archivo' => $archivo,
                'ruta_archivo' => $rutaArchivo
            ]);

            return response()->download($rutaArchivo, $archivo, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="' . $archivo . '"'
            ]);

        } catch (\Exception $e) {
            Log::error('Error al descargar oficio', [
                'tramite_id' => $tramiteId ?? null,
                'proveedor_id' => $proveedorId ?? null,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            abort(500, 'Error al descargar el oficio');
        }
    }

    public function validar(int $tramiteId)
    {
        try {
            $tramite = Tramite::with(['proveedor', 'datosGenerales'])->findOrFail($tramiteId);
            $oficio = Oficio::where('tramite_id', $tramiteId)->latest()->first();

            if (!$oficio) {
                return response()->json([
                    'valido' => false,
                    'mensaje' => 'Oficio no encontrado'
                ]);
            }

            $datosValidacion = [
                'valido' => true,
                'oficio' => [
                    'numero_oficio' => $oficio->numero_oficio,
                    'fecha_oficio' => $oficio->fecha_oficio->format('d/m/Y'),
                    'estado' => $oficio->estado
                ],
                'proveedor' => [
                    'razon_social' => $tramite->proveedor->razon_social,
                    'rfc' => $tramite->proveedor->rfc,
                    'pv_numero' => $tramite->proveedor->pv_numero,
                    'estado_padron' => $tramite->proveedor->estado_padron
                ],
                'tramite' => [
                    'id' => $tramite->id,
                    'tipo_tramite' => $tramite->tipo_tramite,
                    'status' => $tramite->status,
                    'fecha_inicio' => $tramite->fecha_inicio->format('d/m/Y'),
                    'fecha_finalizacion' => $tramite->fecha_finalizacion ? $tramite->fecha_finalizacion->format('d/m/Y') : null
                ]
            ];

            Log::info('Validación de oficio exitosa', [
                'tramite_id' => $tramiteId,
                'oficio_id' => $oficio->id
            ]);

            return response()->json($datosValidacion);

        } catch (\Exception $e) {
            Log::error('Error al validar oficio', [
                'tramite_id' => $tramiteId,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'valido' => false,
                'mensaje' => 'Error al validar el oficio'
            ]);
        }
    }


} 
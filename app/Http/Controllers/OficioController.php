<?php

namespace App\Http\Controllers;

use App\Models\Oficio;
use App\Models\Tramite;
use App\Models\Proveedor;
use App\Services\OficioService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class OficioController extends Controller
{
    protected OficioService $oficioService;

    public function __construct(OficioService $oficioService)
    {
        $this->oficioService = $oficioService;
    }

    /**
     * Descargar oficio
     */
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

            // Verificar que el oficio existe
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

            // Construir la ruta del archivo
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

            // Retornar el archivo para descarga
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

    /**
     * Validar oficio mediante QR
     */
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

    /**
     * Mostrar oficios por proveedor
     */
    public function porProveedor(int $proveedorId)
    {
        try {
            $oficios = $this->oficioService->obtenerOficiosPorProveedor($proveedorId);
            $proveedor = Proveedor::findOrFail($proveedorId);

            return view('oficios.por-proveedor', compact('oficios', 'proveedor'));

        } catch (\Exception $e) {
            Log::error('Error al obtener oficios por proveedor', [
                'proveedor_id' => $proveedorId,
                'error' => $e->getMessage()
            ]);

            return back()->with('error', 'Error al obtener los oficios');
        }
    }

    /**
     * Mostrar oficios por trámite
     */
    public function porTramite(int $tramiteId)
    {
        try {
            $oficios = $this->oficioService->obtenerOficiosPorTramite($tramiteId);
            $tramite = Tramite::with('proveedor')->findOrFail($tramiteId);

            return view('oficios.por-tramite', compact('oficios', 'tramite'));

        } catch (\Exception $e) {
            Log::error('Error al obtener oficios por trámite', [
                'tramite_id' => $tramiteId,
                'error' => $e->getMessage()
            ]);

            return back()->with('error', 'Error al obtener los oficios');
        }
    }

    /**
     * Actualizar estado del oficio
     */
    public function actualizarEstado(Request $request, int $oficioId)
    {
        try {
            $request->validate([
                'estado' => 'required|in:Generado,Enviado,Entregado,Cancelado'
            ]);

            $estado = $request->get('estado');
            $exito = $this->oficioService->actualizarEstadoOficio($oficioId, $estado);

            if ($exito) {
                return response()->json([
                    'success' => true,
                    'mensaje' => 'Estado del oficio actualizado correctamente'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'mensaje' => 'Error al actualizar el estado del oficio'
                ], 500);
            }

        } catch (\Exception $e) {
            Log::error('Error al actualizar estado del oficio', [
                'oficio_id' => $oficioId,
                'estado' => $request->get('estado'),
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'mensaje' => 'Error al actualizar el estado del oficio'
            ], 500);
        }
    }

    /**
     * Regenerar oficio para un trámite específico
     */
    public function regenerar(Request $request, int $tramiteId)
    {
        try {
            Log::info('Solicitud de regeneración de oficio', [
                'tramite_id' => $tramiteId,
                'user_ip' => $request->ip()
            ]);

            $oficio = $this->oficioService->regenerarOficioParaTramite($tramiteId);

            return response()->json([
                'success' => true,
                'mensaje' => 'Oficio regenerado exitosamente',
                'data' => [
                    'oficio_id' => $oficio->id,
                    'numero_oficio' => $oficio->numero_oficio,
                    'fecha_oficio' => $oficio->fecha_oficio->format('d/m/Y'),
                    'url' => $oficio->url,
                    'tramite_id' => $oficio->tramite_id,
                    'proveedor_id' => $oficio->proveedor_id
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error al regenerar oficio', [
                'tramite_id' => $tramiteId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'mensaje' => 'Error al regenerar el oficio: ' . $e->getMessage()
            ], 500);
        }
    }
} 
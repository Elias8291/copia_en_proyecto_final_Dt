<?php

namespace App\Http\Controllers;

use App\Models\Proveedor;
use App\Models\Tramite;
use App\Models\Oficio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProveedorPublicoController extends Controller
{
    /**
     * Mostrar información pública del proveedor
     */
    public function show($proveedorId)
    {
        try {
            // Buscar el proveedor con sus relaciones
            $proveedor = Proveedor::with([
                'usuario',
                'tramites' => function($query) {
                    $query->orderBy('created_at', 'desc');
                },
                'tramites.datosGenerales' => function($query) {
                    $query->orderBy('created_at', 'desc')->limit(1);
                },
                'tramites.datosConstitutivos' => function($query) {
                    $query->orderBy('created_at', 'desc')->limit(1);
                },
                'tramites.direcciones.estado',
                'tramites.actividades',
                'tramites.oficios'
            ])->findOrFail($proveedorId);

            // Obtener el trámite más reciente
            $tramiteReciente = $proveedor->tramites->first();
            
            // Obtener datos generales más recientes
            $datosGenerales = $tramiteReciente ? $tramiteReciente->datosGenerales->first() : null;
            
            // Obtener datos constitutivos más recientes
            $datosConstitutivos = $tramiteReciente ? $tramiteReciente->datosConstitutivos->first() : null;
            
            // Obtener direcciones
            $direcciones = $tramiteReciente ? $tramiteReciente->direcciones : collect();
            
            // Obtener actividades
            $actividades = $tramiteReciente ? $tramiteReciente->actividades : collect();
            
            // Obtener oficios
            $oficios = $proveedor->tramites->flatMap(function($tramite) {
                return $tramite->oficios;
            })->sortByDesc('created_at');

            Log::info('Vista pública del proveedor accedida', [
                'proveedor_id' => $proveedorId,
                'rfc' => $proveedor->rfc,
                'tramites_count' => $proveedor->tramites->count(),
                'oficios_count' => $oficios->count()
            ]);

            return view('proveedores.publico', compact(
                'proveedor',
                'tramiteReciente',
                'datosGenerales',
                'datosConstitutivos',
                'direcciones',
                'actividades',
                'oficios'
            ));

        } catch (\Exception $e) {
            Log::error('Error al mostrar información pública del proveedor', [
                'proveedor_id' => $proveedorId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            abort(404, 'Proveedor no encontrado');
        }
    }

    /**
     * Validar proveedor por QR
     */
    public function validarPorQR(Request $request)
    {
        try {
            $proveedorId = $request->input('proveedor_id');
            
            if (!$proveedorId) {
                return response()->json([
                    'success' => false,
                    'message' => 'ID de proveedor no proporcionado'
                ], 400);
            }

            $proveedor = Proveedor::with(['usuario'])->find($proveedorId);
            
            if (!$proveedor) {
                return response()->json([
                    'success' => false,
                    'message' => 'Proveedor no encontrado'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'proveedor' => [
                    'id' => $proveedor->id,
                    'rfc' => $proveedor->rfc,
                    'razon_social' => $proveedor->razon_social,
                    'pv_numero' => $proveedor->pv_numero,
                    'tipo_persona' => $proveedor->tipo_persona,
                    'estado_padron' => $proveedor->estado_padron,
                    'fecha_registro' => $proveedor->fecha_registro,
                    'fecha_vencimiento_padron' => $proveedor->fecha_vencimiento_padron,
                    'usuario' => $proveedor->usuario ? [
                        'nombre' => $proveedor->usuario->nombre,
                        'email' => $proveedor->usuario->email
                    ] : null
                ],
                'fecha_validacion' => now()->format('Y-m-d H:i:s')
            ]);

        } catch (\Exception $e) {
            Log::error('Error al validar proveedor por QR', [
                'proveedor_id' => $request->input('proveedor_id'),
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error interno del servidor'
            ], 500);
        }
    }
}

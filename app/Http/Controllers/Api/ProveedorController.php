<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Application\Services\ProveedorApplicationService;
use App\Domain\Exceptions\ProveedorNotFoundException;
use App\Domain\Exceptions\InvalidRfcException;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class ProveedorController extends Controller
{
    public function __construct(
        private ProveedorApplicationService $proveedorService
    ) {}

    public function determinarTipoPersona(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'rfc' => 'required|string|max:13'
            ]);

            $tipoPersona = $this->proveedorService->determinarTipoPersona($request->rfc);

            return response()->json([
                'success' => true,
                'data' => [
                    'rfc' => $request->rfc,
                    'tipo_persona' => $tipoPersona->value,
                    'label' => $tipoPersona->getLabel()
                ]
            ]);
        } catch (InvalidRfcException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        } catch (\Exception $e) {
            Log::error('Error al determinar tipo de persona: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error interno del servidor'
            ], 500);
        }
    }

    public function buscarProveedores(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'rfc' => 'required|string|max:13'
            ]);

            $proveedores = $this->proveedorService->buscarProveedoresPorRfc($request->rfc);

            return response()->json([
                'success' => true,
                'data' => [
                    'rfc' => $request->rfc,
                    'total_proveedores' => $proveedores->count(),
                    'proveedores' => $proveedores->map(function ($proveedor) {
                        return [
                            'id' => $proveedor->getId(),
                            'pv_numero' => $proveedor->getPvNumero()?->getValue(),
                            'estado_padron' => $proveedor->getEstadoPadron()->getValue(),
                            'fecha_vencimiento' => $proveedor->getFechaVencimiento(),
                            'esta_activo' => $proveedor->estaActivo(),
                            'puede_reutilizarse' => $proveedor->puedeReutilizarse()
                        ];
                    })
                ]
            ]);
        } catch (InvalidRfcException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        } catch (\Exception $e) {
            Log::error('Error al buscar proveedores: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error interno del servidor'
            ], 500);
        }
    }

    public function determinarAccion(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'rfc' => 'required|string|max:13',
                'tipo_tramite' => 'required|string|in:inscripcion,renovacion,actualizacion',
                'tramite_id_excluir' => 'nullable|integer'
            ]);

            $accion = $this->proveedorService->determinarAccionPorTipoTramite(
                $request->rfc,
                $request->tipo_tramite,
                $request->tramite_id_excluir
            );

            return response()->json([
                'success' => true,
                'data' => $accion
            ]);
        } catch (InvalidRfcException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        } catch (\Exception $e) {
            Log::error('Error al determinar acción: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error interno del servidor'
            ], 500);
        }
    }

    public function generarNumeroPV(): JsonResponse
    {
        try {
            $pvNumero = $this->proveedorService->generarNumeroPV();

            return response()->json([
                'success' => true,
                'data' => [
                    'pv_numero' => $pvNumero->getValue()
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error al generar número PV: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error interno del servidor'
            ], 500);
        }
    }

    public function crearProveedor(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'rfc' => 'required|string|max:13',
                'tipo_persona' => 'required|string|in:Física,Moral',
                'razon_social' => 'nullable|string|max:255'
            ]);

            $proveedor = $this->proveedorService->crearProveedor(
                rfc: $request->rfc,
                tipoPersona: $request->tipo_persona,
                usuarioId: auth()->id(),
                razonSocial: $request->razon_social
            );

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $proveedor->getId(),
                    'rfc' => $proveedor->getRfc()->getValue(),
                    'tipo_persona' => $proveedor->getTipoPersona()->value,
                    'estado_padron' => $proveedor->getEstadoPadron()->getValue(),
                    'razon_social' => $proveedor->getRazonSocial()
                ]
            ], 201);
        } catch (InvalidRfcException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        } catch (\Exception $e) {
            Log::error('Error al crear proveedor: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error interno del servidor'
            ], 500);
        }
    }

    public function gestionarProveedorPorTramite(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'rfc' => 'required|string|max:13',
                'tipo_tramite' => 'required|string|in:inscripcion,renovacion,actualizacion',
                'datos_proveedor' => 'required|array',
                'datos_proveedor.tipo_persona' => 'required|string|in:Física,Moral',
                'datos_proveedor.razon_social' => 'nullable|string|max:255'
            ]);

            $resultado = $this->proveedorService->gestionarProveedorPorTramite(
                $request->rfc,
                $request->tipo_tramite,
                $request->datos_proveedor
            );

            return response()->json([
                'success' => true,
                'data' => $resultado
            ]);
        } catch (ProveedorNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 404);
        } catch (InvalidRfcException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        } catch (\Exception $e) {
            Log::error('Error al gestionar proveedor por trámite: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error interno del servidor'
            ], 500);
        }
    }
}

<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

abstract class BaseApiController extends Controller
{
    use ApiResponseTrait;

    protected $service;
    protected $resource;
    protected $validationRules = [];

    public function __construct()
    {
        $this->initializeService();
    }

    abstract protected function initializeService();

    /**
     * Obtener lista paginada
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $filters = $request->only(['search', 'status', 'date_from', 'date_to']);
            $perPage = $request->get('per_page', 15);
            
            $data = $this->service->getPaginated($filters, $perPage);
            
            return $this->successResponse(
                $this->resource::collection($data),
                'Datos obtenidos exitosamente'
            );
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    /**
     * Obtener elemento específico
     */
    public function show($id): JsonResponse
    {
        try {
            $data = $this->service->findById($id);
            
            if (!$data) {
                return $this->notFoundResponse('Recurso no encontrado');
            }
            
            return $this->successResponse(
                new $this->resource($data),
                'Datos obtenidos exitosamente'
            );
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    /**
     * Crear nuevo elemento
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), $this->validationRules);
            
            if ($validator->fails()) {
                return $this->validationErrorResponse($validator->errors());
            }
            
            $data = $this->service->create($request->validated());
            
            return $this->successResponse(
                new $this->resource($data),
                'Recurso creado exitosamente',
                201
            );
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    /**
     * Actualizar elemento
     */
    public function update(Request $request, $id): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), $this->validationRules);
            
            if ($validator->fails()) {
                return $this->validationErrorResponse($validator->errors());
            }
            
            $data = $this->service->update($id, $request->validated());
            
            return $this->successResponse(
                new $this->resource($data),
                'Recurso actualizado exitosamente'
            );
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    /**
     * Eliminar elemento
     */
    public function destroy($id): JsonResponse
    {
        try {
            $this->service->delete($id);
            
            return $this->successResponse(
                null,
                'Recurso eliminado exitosamente'
            );
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    /**
     * Aprobar elemento
     */
    public function approve($id): JsonResponse
    {
        try {
            $data = $this->service->approve($id);
            
            return $this->successResponse(
                new $this->resource($data),
                'Recurso aprobado exitosamente'
            );
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    /**
     * Rechazar elemento
     */
    public function reject(Request $request, $id): JsonResponse
    {
        try {
            $reason = $request->get('reason', '');
            $data = $this->service->reject($id, $reason);
            
            return $this->successResponse(
                new $this->resource($data),
                'Recurso rechazado exitosamente'
            );
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }
} 
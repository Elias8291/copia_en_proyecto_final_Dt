<?php

namespace App\Services\Core;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

abstract class BaseService
{
    protected $model;
    protected $repository;

    public function __construct()
    {
        $this->initializeModel();
    }

    abstract protected function initializeModel();

    /**
     * Obtener datos paginados con filtros
     */
    public function getPaginated(array $filters = [], int $perPage = 15)
    {
        try {
            $query = $this->model->query();
            
            // Aplicar filtros dinámicos
            $this->applyFilters($query, $filters);
            
            // Ordenar por fecha de creación
            $query->orderBy('created_at', 'desc');
            
            return $query->paginate($perPage);
        } catch (Exception $e) {
            Log::error('Error en getPaginated: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Buscar por ID
     */
    public function findById($id)
    {
        try {
            return $this->model->findOrFail($id);
        } catch (Exception $e) {
            Log::error("Error buscando ID {$id}: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Crear nuevo registro
     */
    public function create(array $data)
    {
        try {
            DB::beginTransaction();
            
            $model = $this->model->create($data);
            
            // Ejecutar acciones post-creación
            $this->afterCreate($model, $data);
            
            DB::commit();
            
            return $model;
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error creando registro: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Actualizar registro
     */
    public function update($id, array $data)
    {
        try {
            DB::beginTransaction();
            
            $model = $this->findById($id);
            $model->update($data);
            
            // Ejecutar acciones post-actualización
            $this->afterUpdate($model, $data);
            
            DB::commit();
            
            return $model;
        } catch (Exception $e) {
            DB::rollBack();
            Log::error("Error actualizando ID {$id}: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Eliminar registro
     */
    public function delete($id)
    {
        try {
            DB::beginTransaction();
            
            $model = $this->findById($id);
            
            // Ejecutar acciones pre-eliminación
            $this->beforeDelete($model);
            
            $model->delete();
            
            DB::commit();
            
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            Log::error("Error eliminando ID {$id}: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Aprobar registro
     */
    public function approve($id)
    {
        try {
            DB::beginTransaction();
            
            $model = $this->findById($id);
            $model->update(['status' => 'approved', 'approved_at' => now()]);
            
            // Ejecutar acciones post-aprobación
            $this->afterApprove($model);
            
            DB::commit();
            
            return $model;
        } catch (Exception $e) {
            DB::rollBack();
            Log::error("Error aprobando ID {$id}: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Rechazar registro
     */
    public function reject($id, string $reason = '')
    {
        try {
            DB::beginTransaction();
            
            $model = $this->findById($id);
            $model->update([
                'status' => 'rejected',
                'rejected_at' => now(),
                'rejection_reason' => $reason
            ]);
            
            // Ejecutar acciones post-rechazo
            $this->afterReject($model, $reason);
            
            DB::commit();
            
            return $model;
        } catch (Exception $e) {
            DB::rollBack();
            Log::error("Error rechazando ID {$id}: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Buscar por múltiples criterios
     */
    public function search(array $criteria)
    {
        try {
            $query = $this->model->query();
            
            foreach ($criteria as $field => $value) {
                if (!empty($value)) {
                    if (is_array($value)) {
                        $query->whereIn($field, $value);
                    } else {
                        $query->where($field, 'LIKE', "%{$value}%");
                    }
                }
            }
            
            return $query->get();
        } catch (Exception $e) {
            Log::error('Error en búsqueda: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Obtener estadísticas
     */
    public function getStats()
    {
        try {
            return [
                'total' => $this->model->count(),
                'pending' => $this->model->where('status', 'pending')->count(),
                'approved' => $this->model->where('status', 'approved')->count(),
                'rejected' => $this->model->where('status', 'rejected')->count(),
            ];
        } catch (Exception $e) {
            Log::error('Error obteniendo estadísticas: ' . $e->getMessage());
            throw $e;
        }
    }

    // Métodos hooks para personalización
    protected function applyFilters($query, array $filters) {}
    protected function afterCreate($model, array $data) {}
    protected function afterUpdate($model, array $data) {}
    protected function beforeDelete($model) {}
    protected function afterApprove($model) {}
    protected function afterReject($model, string $reason) {}
} 
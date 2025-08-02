<?php

declare(strict_types=1);

namespace App\Services\Proveedores;

use App\Models\Proveedor;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

/**
 * Servicio especializado para gestión de proveedores
 * Responsabilidad: Creación, actualización y gestión de proveedores
 */
class GestionProveedorService
{
    public function __construct(
        private NumerosPvService $numerosPvService
    ) {}

    /**
     * Crear o obtener proveedor existente para un usuario
     */
    public function crearObtenerProveedor(User $user, array $data = []): Proveedor
    {
        $existingProveedor = Proveedor::where('usuario_id', $user->id)->first();

        if ($existingProveedor) {
            return $existingProveedor;
        }

        $proveedorData = [
            'usuario_id' => $user->id,
            'rfc' => $data['rfc'] ?? $user->rfc,
            'tipo_persona' => $this->determinarTipoPersona($data['rfc'] ?? $user->rfc),
            'estado_padron' => 'Pendiente',
        ];

        return Proveedor::create($proveedorData);
    }

    /**
     * Crear un nuevo proveedor completo
     */
    public function crearProveedor(array $data): Proveedor
    {
        DB::beginTransaction();

        try {
            // Crear usuario
            $user = User::create([
                'nombre' => $data['nombre'],
                'correo' => $data['correo'],
                'rfc' => $data['rfc'],
                'password' => Hash::make($data['password'] ?? 'temporal123'),
                'estado' => 'pendiente',
            ]);

            // Crear proveedor
            $proveedor = Proveedor::create([
                'usuario_id' => $user->id,
                'rfc' => $data['rfc'],
                'tipo_persona' => $data['tipo_persona'],
                'razon_social' => $data['razon_social'] ?? null,
                'estado_padron' => 'Pendiente',
            ]);

            DB::commit();
            return $proveedor;

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Actualizar proveedor
     */
    public function actualizarProveedor(Proveedor $proveedor, array $data): Proveedor
    {
        DB::beginTransaction();

        try {
            // Actualizar usuario
            if (isset($data['nombre']) || isset($data['correo'])) {
                $userData = [];
                if (isset($data['nombre'])) $userData['nombre'] = $data['nombre'];
                if (isset($data['correo'])) $userData['correo'] = $data['correo'];
                
                $proveedor->user->update($userData);
            }

            // Actualizar proveedor
            $proveedor->update($data);

            DB::commit();
            return $proveedor;

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Activar proveedor (cambiar estado y asignar número PV)
     */
    public function activarProveedor(Proveedor $proveedor, ?string $fechaVencimiento = null): Proveedor
    {
        $nuevoPV = $this->numerosPvService->generarNuevoPv();
        $fechaActual = now();

        $proveedor->update([
            'pv_numero' => $nuevoPV,
            'estado_padron' => 'Activo',
            'alta_al_padron' => $fechaActual,
            'fecha_vencimiento_padron' => $fechaVencimiento ? now()->parse($fechaVencimiento) : $fechaActual->copy()->addYear(),
            'fecha_actualizacion' => $fechaActual
        ]);

        return $proveedor;
    }

    /**
     * Cambiar estado del proveedor
     */
    public function cambiarEstado(Proveedor $proveedor, string $nuevoEstado, ?string $observaciones = null): Proveedor
    {
        $proveedor->update([
            'estado_padron' => $nuevoEstado,
            'observaciones' => $observaciones,
            'fecha_actualizacion' => now()
        ]);

        return $proveedor;
    }

    /**
     * Eliminar proveedor
     */
    public function eliminarProveedor(Proveedor $proveedor): bool
    {
        DB::beginTransaction();

        try {
            // Eliminar usuario asociado
            if ($proveedor->user) {
                $proveedor->user->delete();
            }

            // Eliminar proveedor
            $proveedor->delete();

            DB::commit();
            return true;

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Obtener estadísticas detalladas de un proveedor
     */
    public function obtenerEstadisticasProveedor(Proveedor $proveedor): array
    {
        return [
            'total_tramites' => $proveedor->tramites()->count(),
            'tramites_pendientes' => $proveedor->tramites()
                ->whereIn('estado', ['Pendiente', 'En_Revision', 'Para_Correccion'])
                ->count(),
            'tramites_aprobados' => $proveedor->tramites()
                ->where('estado', 'Aprobado')
                ->count(),
            'tramites_rechazados' => $proveedor->tramites()
                ->where('estado', 'Rechazado')
                ->count(),
            'dias_registro' => $proveedor->created_at->diffInDays(now()),
            'dias_para_vencer' => $proveedor->fecha_vencimiento_padron 
                ? now()->diffInDays($proveedor->fecha_vencimiento_padron, false)
                : null,
        ];
    }

    /**
     * Determinar tipo de persona basado en RFC
     */
    private function determinarTipoPersona(string $rfc): string
    {
        return strlen($rfc) === 13 ? 'Física' : 'Moral';
    }
} 
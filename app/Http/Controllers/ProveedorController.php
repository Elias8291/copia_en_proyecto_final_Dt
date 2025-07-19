<?php

namespace App\Http\Controllers;

use App\Models\Proveedor;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class ProveedorController extends Controller
{
    public function __construct()
    {
        // Constructor without dependencies
    }


    
    /**
     * Crear un nuevo proveedor
     */
    public function createProveedor(User $user, array $data): Proveedor
    {
        try {
            // Verificar si ya existe un proveedor para este usuario
            $existingProveedor = Proveedor::where('user_id', $user->id)->first();

            if ($existingProveedor) {
                Log::info('Proveedor ya existe para el usuario', [
                    'user_id' => $user->id,
                    'proveedor_id' => $existingProveedor->id
                ]);
                return $existingProveedor;
            }

            $proveedorData = [
                'user_id' => $user->id,
                'rfc' => $data['rfc'] ?? '',
                'curp' => $data['curp'] ?? '',
                'tipo_persona' => $this->determineTipoPersona($data['rfc'] ?? ''),
                'razon_social' => $data['razon_social'] ?? '',
                'pv_numero' => null,
                'estado_padron' => 'Pendiente',
                'fecha_alta_padron' => null,
                'fecha_vencimiento_padron' => null,
            ];

            $proveedor = Proveedor::create($proveedorData);

            Log::info('Proveedor creado exitosamente', [
                'user_id' => $user->id,
                'proveedor_id' => $proveedor->id,
                'rfc' => $proveedor->rfc
            ]);

            return $proveedor;
        } catch (\Exception $e) {
            Log::error('Error al crear proveedor', [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    public function determineTipoPersona(string $rfc): string
    {
        return Proveedor::determineTipoPersona($rfc);
    }
    

}

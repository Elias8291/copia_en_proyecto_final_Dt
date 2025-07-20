<?php

namespace App\Http\Controllers;

use App\Models\Proveedor;
use App\Models\User;

class ProveedorController extends Controller
{
    /**
     * Crear un nuevo proveedor
     */
    public function createProveedor(User $user, array $data): Proveedor
    {
        // Verificar si ya existe un proveedor para este usuario
        $existingProveedor = Proveedor::where('usuario_id', $user->id)->first();

        if ($existingProveedor) {
            return $existingProveedor;
        }

        $proveedorData = [
            'usuario_id' => $user->id,
            'rfc' => $data['rfc'], // RFC es obligatorio
            'tipo_persona' => $this->determineTipoPersona($data['rfc']),
            'estado' => 'Pendiente_Revision',
        ];

        return Proveedor::create($proveedorData);
    }

    /**
     * Determinar tipo de persona basado en RFC
     */
    private function determineTipoPersona(string $rfc): string
    {
        // RFC de persona física: 13 caracteres
        // RFC de persona moral: 12 caracteres
        return strlen($rfc) === 13 ? 'Física' : 'Moral';
    }
}

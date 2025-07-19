<?php

namespace App\Services;

use App\Models\Proveedor;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class ProveedorService
{
    /**
     * Get the proveedor associated with the authenticated user
     *
     * @return Proveedor|null
     */
    public function getProveedorByUser()
    {
        $user = Auth::user();
        
        if ($user instanceof User) {
            $user->load('proveedor');
            return $user->proveedor;
        }
        
        return null;
    }

    /**
     * Determina los trámites disponibles para un proveedor
     *
     * @param \App\Models\Proveedor|null $proveedor
     * @return array
     */
    public function determinarTramitesDisponibles($proveedor): array
    {
        $defaults = [
            'inscripcion' => false,
            'renovacion' => false,
            'actualizacion' => false,
            'is_administrative' => false, 
            'message' => '',
            'estado_vigencia' => null
        ];

        if (!$proveedor) {
            $defaults['inscripcion'] = true;
            $defaults['message'] = 'Para comenzar, realice su inscripción al padrón de proveedores.';
            return $defaults;
        }

        $defaults['estado_vigencia'] = $proveedor->getEstadoVigencia();
        $estado = ucfirst(strtolower($proveedor->estado_padron ?? ''));

        switch ($estado) {
            case 'Pendiente':
            case 'Vencido':
            case 'Inactivo':
                $defaults['inscripcion'] = true;
                $defaults['message'] = 'Su registro está ' . strtolower($estado) . '. Debe realizar el proceso de inscripción.';
                break;
            
            case 'Activo':
                if ($proveedor->estaEnPeriodoRenovacion()) {
                    $defaults['renovacion'] = true;
                    $defaults['message'] = 'Su registro está próximo a vencer. Por favor, realice la renovación.';
                } else {
                    $defaults['actualizacion'] = true;
                    $defaults['message'] = 'Su registro se encuentra activo. Puede actualizar su información si lo necesita.';
                }
                break;

            default:
                $defaults['inscripcion'] = true;
                $defaults['message'] = 'No reconocemos el estado de su registro. Por favor, inicie el proceso de inscripción.';
                break;
        }

        return $defaults;
    }
}
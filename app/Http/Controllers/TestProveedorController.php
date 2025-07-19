<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Services\ProveedorService;

class TestProveedorController extends Controller
{
    protected $proveedorService;

    public function __construct(
        ProveedorService $proveedorService
    ) {
        $this->proveedorService = $proveedorService;
    }

    /**
     * Mostrar vista de prueba para los servicios de proveedor
     */
    public function testServicios()
    {
        // Obtener el proveedor del usuario autenticado
        $proveedor = $this->proveedorService->getProveedorByUser();
        
        return view('pruebas.test-proveedor', [
            'datosProveedor' => [],
            'puedeInscribirse' => true,
            'tramitesDisponibles' => ['Inscripción', 'Actualización'],
            'proveedor' => $proveedor,
            'esFisica' => $proveedor ? $proveedor->tipo_persona === 'fisica' : true,
            'esMoral' => $proveedor ? $proveedor->tipo_persona === 'moral' : false
        ]);
    }
}
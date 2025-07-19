<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TramiteController extends Controller
{
    /**
     * El ProveedorService ya no es necesario aquí, 
     * ya que la lógica se centraliza en AppServiceProvider.
     */

    public function index(Request $request)
    {
        // La variable $availableProcedures se inyecta globalmente
        // a todas las vistas desde AppServiceProvider.
        // No se necesita lógica adicional aquí.
        return view('tramites.index');
    }

    public function inscripcion(Request $request)
    {
        // La decisión de si un usuario puede o no acceder a esta ruta
        // se maneja en la lógica de negocio del AppServiceProvider
        // que habilita o deshabilita los enlaces/botones en la vista.
        // Un usuario no debería poder llegar aquí si no tiene permiso.
        return view('tramites.inscripcion');
    }

    public function renovacion()
    {
        return view('tramites.renovacion');
    }

    public function actualizacion()
    {
        return view('tramites.actualizacion');
    }
} 
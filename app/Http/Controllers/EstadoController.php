<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Estado;

class EstadoController extends Controller
{
    /**
     * Obtiene todos los estados
     */
    public function index()
    {
        try {
            $estados = Estado::orderBy('nombre')->get();
            
            return response()->json([
                'success' => true,
                'data' => $estados
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener los estados'
            ], 500);
        }
    }
}

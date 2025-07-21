<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UbicacionController extends Controller
{
    /**
     * Buscar información de ubicación por código postal
     */
    public function buscarPorCodigoPostal(Request $request)
    {
        $request->validate([
            'codigo_postal' => 'required|string|min:5|max:5'
        ]);

        $codigoPostal = $request->codigo_postal;

        try {
            $ubicaciones = DB::select("
                SELECT 
                    a.nombre AS asentamiento,
                    a.codigo_postal,
                    ta.nombre AS tipo_asentamiento,
                    l.nombre AS localidad,
                    m.nombre AS municipio,
                    e.nombre AS estado,
                    p.nombre AS pais,
                    a.id as asentamiento_id,
                    l.id as localidad_id,
                    m.id as municipio_id,
                    e.id as estado_id,
                    p.id as pais_id
                FROM asentamientos a
                INNER JOIN tipos_asentamiento ta ON a.tipo_asentamiento_id = ta.id
                INNER JOIN localidades l ON a.localidad_id = l.id
                INNER JOIN municipios m ON l.municipio_id = m.id
                INNER JOIN estados e ON m.estado_id = e.id
                INNER JOIN paises p ON e.pais_id = p.id
                WHERE a.codigo_postal = ?
                ORDER BY a.nombre ASC
            ", [$codigoPostal]);

            if (empty($ubicaciones)) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se encontraron datos para el código postal proporcionado'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $ubicaciones,
                'total' => count($ubicaciones)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al buscar la información: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener todos los estados
     */
    public function getEstados()
    {
        try {
            $estados = DB::select("
                SELECT id, nombre 
                FROM estados 
                ORDER BY nombre ASC
            ");

            return response()->json([
                'success' => true,
                'data' => $estados
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener los estados: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener municipios por estado
     */
    public function getMunicipiosPorEstado(Request $request)
    {
        $request->validate([
            'estado_id' => 'required|integer'
        ]);

        try {
            $municipios = DB::select("
                SELECT id, nombre 
                FROM municipios 
                WHERE estado_id = ?
                ORDER BY nombre ASC
            ", [$request->estado_id]);

            return response()->json([
                'success' => true,
                'data' => $municipios
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener los municipios: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener localidades por municipio
     */
    public function getLocalidadesPorMunicipio(Request $request)
    {
        $request->validate([
            'municipio_id' => 'required|integer'
        ]);

        try {
            $localidades = DB::select("
                SELECT id, nombre 
                FROM localidades 
                WHERE municipio_id = ?
                ORDER BY nombre ASC
            ", [$request->municipio_id]);

            return response()->json([
                'success' => true,
                'data' => $localidades
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener las localidades: ' . $e->getMessage()
            ], 500);
        }
    }
}
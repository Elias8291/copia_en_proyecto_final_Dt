<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CodigoPostalController extends Controller
{
    /**
     * Busca información completa por código postal
     */
    public function buscarPorCodigoPostal(Request $request)
    {
        try {
        $request->validate([
            'codigo_postal' => 'required|string|min:5|max:5'
        ]);

        $codigoPostal = $request->codigo_postal;

            // Nueva consulta usando Query Builder con relación a localidades
        $asentamientos = DB::table('asentamientos')
            ->select(
                'asentamientos.id as asentamiento_id',
                'asentamientos.nombre as asentamiento_nombre',
                'asentamientos.tipo_asentamiento',
                    'localidades.nombre as localidad',
                'municipios.id as municipio_id',
                'municipios.nombre as municipio_nombre',
                'estados.id as estado_id',
                'estados.nombre as estado_nombre'
            )
                ->join('localidades', 'asentamientos.localidad_id', '=', 'localidades.id')
                ->join('municipios', 'localidades.municipio_id', '=', 'municipios.id')
                ->join('estados', 'municipios.estado_id', '=', 'estados.id')
                ->where('asentamientos.codigo_postal', $codigoPostal)
            ->orderBy('asentamientos.nombre')
            ->get();

        if ($asentamientos->isEmpty()) {
            return response()->json([
                'success' => false,
                    'message' => 'No se encontraron asentamientos para el código postal ' . $codigoPostal
            ], 404);
        }

        // Tomar los datos del primer asentamiento para estado y municipio
        $primerAsentamiento = $asentamientos->first();

        $response = [
            'success' => true,
            'data' => [
                'codigo_postal' => $codigoPostal,
                'estado' => [
                    'id' => $primerAsentamiento->estado_id,
                    'nombre' => $primerAsentamiento->estado_nombre
                ],
                'municipio' => [
                    'id' => $primerAsentamiento->municipio_id,
                    'nombre' => $primerAsentamiento->municipio_nombre
                ],
                    'localidad' => $primerAsentamiento->localidad,
                'asentamientos' => $asentamientos->map(function ($asentamiento) {
                    return [
                        'id' => $asentamiento->asentamiento_id,
                        'nombre' => $asentamiento->asentamiento_nombre,
                        'tipo' => $asentamiento->tipo_asentamiento,
                            'localidad' => $asentamiento->localidad,
                        'nombre_completo' => $asentamiento->asentamiento_nombre . 
                            ($asentamiento->tipo_asentamiento ? ' (' . $asentamiento->tipo_asentamiento . ')' : '')
                    ];
                    })->toArray(),
                'total_asentamientos' => $asentamientos->count()
            ]
        ];

        return response()->json($response);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error interno del servidor: ' . $e->getMessage()
            ], 500);
        }
    }
}

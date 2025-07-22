<?php

namespace App\Http\Controllers;

use App\Models\CatalogoArchivo;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CatalogoArchivoController extends Controller
{
    public function index(Request $request): View
    {
        $perPage = $request->get('per_page', 10);
        $allowedPerPage = [5, 10, 25, 50, 100];
        if (!in_array($perPage, $allowedPerPage)) {
            $perPage = 10;
        }
        $archivos = CatalogoArchivo::orderBy('nombre', 'asc')->paginate($perPage);
        $archivos->appends($request->query());
        return view('archivos.index', compact('archivos', 'perPage'));
    }
    public function porTipoPersona(string $tipoPersona)
    {
        return CatalogoArchivo::where('tipo_persona', $tipoPersona)
            ->orWhere('tipo_persona', 'Ambas')
            ->where('es_visible', true)
            ->orderBy('nombre')
            ->get();
    }
}
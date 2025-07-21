<?php

namespace App\Http\Controllers;

use App\Models\CatalogoArchivo;
use Illuminate\View\View;

class CatalogoArchivoController extends Controller
{
    /**
     * Mostrar lista de archivos del catálogo
     */
    public function index(): View
    {
        $archivos = CatalogoArchivo::orderBy('nombre', 'asc')->take(10)->get();

        return view('archivos.index', compact('archivos'));
    }
}
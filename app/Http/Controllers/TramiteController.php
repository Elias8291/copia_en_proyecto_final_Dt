<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tramite;

class TramiteController extends Controller
{
    public function index()
    {
        return view('tramites.index');
    }

    public function create()
    {
        return view('tramites.create');
    }

    public function store(Request $request)
    {
        // Aquí irá la lógica para guardar el trámite
        return redirect()->route('tramites.index')->with('success', 'Trámite creado exitosamente');
    }
} 
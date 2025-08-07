<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Proveedor;

class MiEstadoController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $proveedor = Proveedor::where('usuario_id', $user->id)->first();
        return view('mi_estado', compact('proveedor'));
    }
} 
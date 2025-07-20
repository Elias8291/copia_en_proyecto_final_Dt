<?php

namespace App\Http\Controllers;

use App\Models\Tramite;
use App\Models\Proveedor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\ProveedorService;

class TramiteController extends Controller
{
    protected $proveedorService;
        public function index()
    {
        // Los datos del proveedor y trámites están disponibles globalmente
        // a través del AppServiceProvider como $globalProveedor y $globalTramites
        return view('tramites.index');
    }
        public function create()
    {
       
    }

    
}

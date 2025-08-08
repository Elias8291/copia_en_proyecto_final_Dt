<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Tramite;
use App\Models\Proveedor;
use Spatie\Permission\Models\Role;
use App\Models\Archivo;
use App\Models\Cita;
use App\Models\Actividad;
use App\Models\RevisionTramite;
use App\Models\Notificacion;

class DashboardController extends Controller
{
    public function index()
    {
        // Obtener estadísticas reales de todas las entidades
        $estadisticas = [
            'totalUsuarios' => User::count(),
            'totalTramites' => Tramite::count(),
            'totalProveedores' => Proveedor::count(),
            'totalRoles' => Role::count(),
            'totalArchivos' => Archivo::count(),
            'totalCitas' => Cita::count(),
            'totalActividades' => Actividad::count(),
            'totalRevisiones' => RevisionTramite::count(),
            'totalNotificaciones' => Notificacion::count(),
        ];

        return view('dashboard', $estadisticas);
    }
} 
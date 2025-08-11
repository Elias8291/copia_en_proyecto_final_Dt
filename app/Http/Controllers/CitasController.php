<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Models\Tramite;
use App\Models\User;
use App\Http\Requests\CitaRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Spatie\Permission\Middleware\PermissionMiddleware;

class CitasController extends Controller
{
    public function __construct()
    {
        // Middleware de permisos para citas
        $this->middleware(PermissionMiddleware::class . ':citas.ver')->only(['index', 'show']);
        $this->middleware(PermissionMiddleware::class . ':citas.crear')->only(['create', 'store']);
        $this->middleware(PermissionMiddleware::class . ':citas.editar')->only(['edit', 'update', 'marcarAsistida', 'marcarNoAsistio', 'cancelar']);
        $this->middleware(PermissionMiddleware::class . ':citas.eliminar')->only(['destroy']);
    }
    public function index(Request $request)
    {
        $query = Cita::with(['tramite.proveedor.usuario', 'asignadoA']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('tramite.proveedor', function($q) use ($search) {
                $q->where('razon_social', 'like', "%{$search}%")
                  ->orWhere('rfc', 'like', "%{$search}%");
            })->orWhereHas('tramite.proveedor.usuario', function($q) use ($search) {
                $q->where('nombre', 'like', "%{$search}%")
                  ->orWhere('correo', 'like', "%{$search}%");
            })->orWhereHas('asignadoA', function($q) use ($search) {
                $q->where('nombre', 'like', "%{$search}%");
            });
        }

        if ($request->filled('tipo_cita')) {
            $query->where('tipo_cita', $request->tipo_cita);
        }

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        if ($request->filled('fecha_desde')) {
            $query->whereDate('fecha_cita', '>=', $request->fecha_desde);
        }

        if ($request->filled('fecha_hasta')) {
            $query->whereDate('fecha_cita', '<=', $request->fecha_hasta);
        }

        $citas = $query->orderBy('fecha_cita', 'desc')->paginate(15);

        $tiposCita = ['Digital', 'Presencial', 'Domiciliaria'];
        $estados = ['Asignada', 'Cancelada', 'Asistida', 'No_Asistio'];

        return view('citas.index', compact('citas', 'tiposCita', 'estados'));
    }

    public function create()
    {
        $tramites = Tramite::with('proveedor')
            ->whereIn('status', ['Pendiente', 'Revision_Digital', 'Revision_Presencial', 'Revision_Domiciliaria'])
            ->get();
        
        $revisores = User::role(['Revisor Digital', 'Revisor Presencial', 'Revisor Domiciliario'])->get();
        
        $tiposCita = ['Digital', 'Presencial', 'Domiciliaria'];
        $estados = ['Asignada', 'Cancelada', 'Asistida', 'No_Asistio'];

        return view('citas.create', compact('tramites', 'revisores', 'tiposCita', 'estados'));
    }

    public function store(CitaRequest $request)
    {
        $validated = $request->validated();

        DB::transaction(function() use ($validated) {
            $cita = Cita::create($validated);
            
            $tramite = Tramite::find($validated['tramite_id']);
            
            switch ($validated['tipo_cita']) {
                case 'Digital':
                    $tramite->update(['status' => 'Revision_Digital']);
                    break;
                case 'Presencial':
                    $tramite->update(['status' => 'Revision_Presencial']);
                    break;
                case 'Domiciliaria':
                    $tramite->update(['status' => 'Revision_Domiciliaria']);
                    break;
            }
        });

        return redirect()->route('citas.index')
            ->with('success', 'Cita creada exitosamente');
    }

    public function show(Cita $cita)
    {
        $cita->load(['tramite.proveedor.usuario', 'asignadoA']);
        
        return view('citas.show', compact('cita'));
    }

    public function edit(Cita $cita)
    {
        $tramites = Tramite::with('proveedor')
            ->whereIn('status', ['Pendiente', 'Revision_Digital', 'Revision_Presencial', 'Revision_Domiciliaria'])
            ->get();
        
        $revisores = User::role(['Revisor Digital', 'Revisor Presencial', 'Revisor Domiciliario'])->get();
        
        $tiposCita = ['Digital', 'Presencial', 'Domiciliaria'];
        $estados = ['Asignada', 'Cancelada', 'Asistida', 'No_Asistio'];

        return view('citas.edit', compact('cita', 'tramites', 'revisores', 'tiposCita', 'estados'));
    }

    public function update(CitaRequest $request, Cita $cita)
    {
        $validated = $request->validated();

        DB::transaction(function() use ($cita, $validated) {
            $tramiteAnterior = $cita->tramite_id;
            $tipoAnterior = $cita->tipo_cita;
            
            $cita->update($validated);
            
            if ($tramiteAnterior !== $validated['tramite_id'] || $tipoAnterior !== $validated['tipo_cita']) {
                $tramite = Tramite::find($validated['tramite_id']);
                
                switch ($validated['tipo_cita']) {
                    case 'Digital':
                        $tramite->update(['status' => 'Revision_Digital']);
                        break;
                    case 'Presencial':
                        $tramite->update(['status' => 'Revision_Presencial']);
                        break;
                    case 'Domiciliaria':
                        $tramite->update(['status' => 'Revision_Domiciliaria']);
                        break;
                }
            }
        });

        return redirect()->route('citas.index')
            ->with('success', 'Cita actualizada exitosamente');
    }

    public function destroy(Cita $cita)
    {
        $cita->delete();
        
        return redirect()->route('citas.index')
            ->with('success', 'Cita eliminada exitosamente');
    }

    public function marcarAsistida(Cita $cita)
    {
        $cita->update(['estado' => 'Asistida']);
        
        return back()->with('success', 'Cita marcada como asistida');
    }

    public function marcarNoAsistio(Cita $cita)
    {
        $cita->update(['estado' => 'No_Asistio']);
        
        return back()->with('success', 'Cita marcada como no asistió');
    }

    public function cancelar(Cita $cita)
    {
        $cita->update(['estado' => 'Cancelada']);
        
        return back()->with('success', 'Cita cancelada exitosamente');
    }
}

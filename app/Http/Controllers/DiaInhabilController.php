<?php

namespace App\Http\Controllers;

use App\Models\DiaInhabil;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DiaInhabilController extends Controller
{
    public function index()
    {
        $diasInhabiles = DiaInhabil::orderBy('fecha_inicio')->paginate(15);
        return view('dias-inhabiles.index', compact('diasInhabiles'));
    }

    public function create()
    {
        return view('dias-inhabiles.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'descripcion' => 'required|string|max:255',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
            'tipo' => 'required|in:Vacaciones,Feriado,Otro',
            'observaciones' => 'nullable|string|max:1000',
            'activo' => 'boolean'
        ]);

        DiaInhabil::create($request->all());

        return redirect()->route('dias-inhabiles.index')
            ->with('success', 'Día inhábil creado exitosamente.');
    }

    public function show(DiaInhabil $diaInhabil)
    {
        return view('dias-inhabiles.show', compact('diaInhabil'));
    }

    public function edit(DiaInhabil $diaInhabil)
    {
        return view('dias-inhabiles.edit', compact('diaInhabil'));
    }

    public function update(Request $request, DiaInhabil $diaInhabil)
    {
        $request->validate([
            'descripcion' => 'required|string|max:255',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
            'tipo' => 'required|in:Vacaciones,Feriado,Otro',
            'observaciones' => 'nullable|string|max:1000',
            'activo' => 'boolean'
        ]);

        $diaInhabil->update($request->all());

        return redirect()->route('dias-inhabiles.index')
            ->with('success', 'Día inhábil actualizado exitosamente.');
    }

    public function destroy(DiaInhabil $diaInhabil)
    {
        $diaInhabil->delete();

        return redirect()->route('dias-inhabiles.index')
            ->with('success', 'Día inhábil eliminado exitosamente.');
    }

    /**
     * API para verificar si una fecha es hábil
     */
    public function verificarFechaHabil(Request $request)
    {
        $request->validate([
            'fecha' => 'required|date'
        ]);

        $fecha = Carbon::parse($request->fecha);
        $esHabil = DiaInhabil::esHabil($fecha);

        return response()->json([
            'fecha' => $fecha->format('Y-m-d'),
            'es_habil' => $esHabil,
            'dia_semana' => $fecha->format('l'),
            'es_fin_semana' => $fecha->dayOfWeek === 0 || $fecha->dayOfWeek === 6,
            'es_inhabil' => DiaInhabil::esInhabil($fecha)
        ]);
    }

    /**
     * API para obtener próximos días hábiles
     */
    public function proximosDiasHabiles(Request $request)
    {
        $cantidad = $request->get('cantidad', 10);
        $dias = DiaInhabil::obtenerProximosDiasHabiles($cantidad);

        return response()->json([
            'dias_habiles' => $dias,
            'cantidad' => count($dias)
        ]);
    }
} 
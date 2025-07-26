<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use Illuminate\Http\Request;

class CitaController extends Controller
{
    public function index()
    {
        $citas = Cita::with(['user', 'atendidoPor'])->paginate(10);
        return view('citas.index', compact('citas'));
    }

    public function create()
    {
        return view('citas.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'fecha_cita' => 'required|date',
            'tipo_cita' => 'required|string',
            'motivo' => 'required|string'
        ]);

        Cita::create($request->all());

        return redirect()->route('citas.index')->with('success', 'Cita creada exitosamente.');
    }

    public function show(Cita $cita)
    {
        return view('citas.show', compact('cita'));
    }

    public function edit(Cita $cita)
    {
        return view('citas.edit', compact('cita'));
    }

    public function update(Request $request, Cita $cita)
    {
        $request->validate([
            'fecha_cita' => 'required|date',
            'tipo_cita' => 'required|string',
            'motivo' => 'required|string'
        ]);

        $cita->update($request->all());

        return redirect()->route('citas.index')->with('success', 'Cita actualizada exitosamente.');
    }

    public function destroy(Cita $cita)
    {
        $cita->delete();
        return redirect()->route('citas.index')->with('success', 'Cita eliminada exitosamente.');
    }
} 
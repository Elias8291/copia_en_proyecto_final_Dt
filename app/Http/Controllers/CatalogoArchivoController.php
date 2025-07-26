<?php

namespace App\Http\Controllers;

use App\Models\CatalogoArchivo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CatalogoArchivoController extends Controller
{
    public function index()
    {
        $archivos = CatalogoArchivo::orderBy('nombre', 'asc')
            ->get()
            ->map(function ($archivo) {
                $archivo->estado = $archivo->es_visible ? 'visible' : 'oculto';
                return $archivo;
            });

        // Si no hay datos, agregar algunos de prueba temporalmente
        if ($archivos->isEmpty()) {
            $archivos = collect([
                (object) [
                    'id' => 1,
                    'nombre' => 'Identificación Oficial',
                    'tipo_persona' => 'Física',
                    'tipo_archivo' => 'pdf',
                    'estado' => 'visible',
                    'created_at' => now()
                ],
                (object) [
                    'id' => 2,
                    'nombre' => 'Comprobante de Domicilio',
                    'tipo_persona' => 'Física',
                    'tipo_archivo' => 'pdf',
                    'estado' => 'visible',
                    'created_at' => now()
                ],
                (object) [
                    'id' => 3,
                    'nombre' => 'Acta Constitutiva',
                    'tipo_persona' => 'Moral',
                    'tipo_archivo' => 'pdf',
                    'estado' => 'visible',
                    'created_at' => now()
                ],
                (object) [
                    'id' => 4,
                    'nombre' => 'Fotografía',
                    'tipo_persona' => 'Física',
                    'tipo_archivo' => 'png',
                    'estado' => 'visible',
                    'created_at' => now()
                ],
                (object) [
                    'id' => 5,
                    'nombre' => 'Audio de Declaración',
                    'tipo_persona' => 'Ambas',
                    'tipo_archivo' => 'mp3',
                    'estado' => 'oculto',
                    'created_at' => now()
                ]
            ]);
        }

        // Debug: mostrar en consola para verificar
        Log::info('Archivos cargados:', $archivos->toArray());

        return view('archivos.index', compact('archivos'));
    }

    public function create()
    {
        return view('archivos.create');
    }

    public function store(Request $request)
    {
        // TODO: Implementar lógica de creación
        return redirect()->route('archivos.index')->with('success', 'Archivo creado exitosamente');
    }

    public function edit($id)
    {
        // TODO: Implementar lógica de edición
        return view('archivos.edit', compact('id'));
    }

    public function update(Request $request, $id)
    {
        // TODO: Implementar lógica de actualización
        return redirect()->route('archivos.index')->with('success', 'Archivo actualizado exitosamente');
    }

    public function destroy($id)
    {
        // TODO: Implementar lógica de eliminación
        return redirect()->route('archivos.index')->with('success', 'Archivo eliminado exitosamente');
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

<?php

namespace App\Http\Controllers;

use App\Models\CatalogoArchivo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ArchivoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = CatalogoArchivo::query();
        
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('nombre', 'like', "%{$request->search}%")
                  ->orWhere('descripcion', 'like', "%{$request->search}%");
            });
        }
        
        if ($request->filled('tipo_persona')) {
            $query->where('tipo_persona', $request->tipo_persona);
        }
        
        if ($request->filled('tipo_archivo')) {
            $query->where('tipo_archivo', $request->tipo_archivo);
        }
        
        if ($request->filled('es_visible')) {
            $query->where('es_visible', $request->es_visible);
        }
        
        $catalogoArchivos = $query->orderBy('nombre', 'asc')->paginate(15)->withQueryString();
        
        return view('archivos.index', compact('catalogoArchivos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('archivos.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:100|unique:catalogo_archivos,nombre',
            'descripcion' => 'required|string',
            'tipo_persona' => 'required|in:Física,Moral,Ambas',
            'tipo_archivo' => 'required|in:png,pdf,mp3,mp4',
            'es_visible' => 'boolean'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();
            
            CatalogoArchivo::create([
                'nombre' => $request->nombre,
                'descripcion' => $request->descripcion,
                'tipo_persona' => $request->tipo_persona,
                'tipo_archivo' => $request->tipo_archivo,
                'es_visible' => $request->has('es_visible')
            ]);
            
            DB::commit();
            
            return redirect()->route('archivos.index')
                ->with('success', 'Catálogo de archivo creado exitosamente.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Error al crear el catálogo de archivo: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(CatalogoArchivo $archivo)
    {
        return view('archivos.show', compact('archivo'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CatalogoArchivo $archivo)
    {
        return view('archivos.edit', compact('archivo'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CatalogoArchivo $archivo)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:100|unique:catalogo_archivos,nombre,' . $archivo->id,
            'descripcion' => 'required|string',
            'tipo_persona' => 'required|in:Física,Moral,Ambas',
            'tipo_archivo' => 'required|in:png,pdf,mp3,mp4',
            'es_visible' => 'boolean'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();
            
            $archivo->update([
                'nombre' => $request->nombre,
                'descripcion' => $request->descripcion,
                'tipo_persona' => $request->tipo_persona,
                'tipo_archivo' => $request->tipo_archivo,
                'es_visible' => $request->has('es_visible')
            ]);
            
            DB::commit();
            
            return redirect()->route('archivos.index')
                ->with('success', 'Catálogo de archivo actualizado exitosamente.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Error al actualizar el catálogo de archivo: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CatalogoArchivo $archivo)
    {
        try {
            DB::beginTransaction();
            
            // Verificar si hay archivos asociados
            if ($archivo->archivos()->count() > 0) {
                return redirect()->back()
                    ->with('error', 'No se puede eliminar el catálogo de archivo porque tiene archivos asociados.');
            }
            
            $archivo->delete();
            
            DB::commit();
            
            return redirect()->route('archivos.index')
                ->with('success', 'Catálogo de archivo eliminado exitosamente.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Error al eliminar el catálogo de archivo: ' . $e->getMessage());
        }
    }
} 
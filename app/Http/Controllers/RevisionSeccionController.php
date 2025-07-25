<?php

namespace App\Http\Controllers;

use App\Models\RevisionSeccion;
use App\Models\Tramite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RevisionSeccionController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'tramite_id' => 'required|exists:tramites,id',
            'seccion' => 'required|string|max:50',
            'comentario' => 'nullable|string',
            'aprobado' => 'nullable|boolean'
        ]);

        $revision = RevisionSeccion::updateOrCreate(
            [
                'tramite_id' => $request->tramite_id,
                'seccion' => $request->seccion
            ],
            [
                'comentario' => $request->comentario,
                'aprobado' => $request->aprobado,
                'user_id' => Auth::id()
            ]
        );

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $revision->id,
                'comentario' => $revision->comentario,
                'aprobado' => $revision->aprobado,
                'estado_texto' => $revision->estado_texto,
                'user' => $revision->user ? $revision->user->name : null,
                'updated_at' => $revision->updated_at->format('d/m/Y H:i')
            ]
        ]);
    }

    public function show(Tramite $tramite, $seccion)
    {
        $revision = RevisionSeccion::where('tramite_id', $tramite->id)
            ->where('seccion', $seccion)
            ->with('user')
            ->first();

        if (!$revision) {
            return response()->json(['success' => false, 'message' => 'No encontrado']);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $revision->id,
                'comentario' => $revision->comentario,
                'aprobado' => $revision->aprobado,
                'estado_texto' => $revision->estado_texto,
                'user' => $revision->user ? $revision->user->name : null,
                'updated_at' => $revision->updated_at->format('d/m/Y H:i')
            ]
        ]);
    }
}
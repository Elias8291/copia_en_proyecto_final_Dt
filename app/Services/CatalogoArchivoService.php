<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\CatalogoArchivo;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

class CatalogoArchivoService
{
    /**
     * Obtener archivos con filtros aplicados
     */
    public function getArchivosConFiltros(Request $request): LengthAwarePaginator
    {
        $query = CatalogoArchivo::query();

        $this->aplicarFiltros($query, $request);

        return $query->orderBy('nombre', 'asc')->paginate(10);
    }

    /**
     * Aplicar filtros dinámicos a la consulta
     */
    private function aplicarFiltros(Builder $query, Request $request): void
    {
        // Filtro de búsqueda por nombre y descripción
        if ($request->filled('search')) {
            $this->filtrarPorBusqueda($query, $request->search);
        }

        // Filtro por tipo de persona
        if ($request->filled('tipo_persona')) {
            $this->filtrarPorTipoPersona($query, $request->tipo_persona);
        }

        // Filtro por tipo de archivo
        if ($request->filled('tipo_archivo')) {
            $this->filtrarPorTipoArchivo($query, $request->tipo_archivo);
        }

        // Filtro por estado de visibilidad
        if ($request->filled('estado')) {
            $this->filtrarPorEstado($query, $request->estado);
        }
    }

    /**
     * Filtrar por término de búsqueda en nombre y descripción
     */
    private function filtrarPorBusqueda(Builder $query, string $termino): void
    {
        $query->where(function (Builder $q) use ($termino) {
            $q->where('nombre', 'like', "%{$termino}%")
              ->orWhere('descripcion', 'like', "%{$termino}%");
        });
    }

    /**
     * Filtrar por tipo de persona
     */
    private function filtrarPorTipoPersona(Builder $query, string $tipoPersona): void
    {
        $query->where(function (Builder $q) use ($tipoPersona) {
            $q->where('tipo_persona', $tipoPersona)
              ->orWhere('tipo_persona', 'Ambas');
        });
    }

    /**
     * Filtrar por tipo de archivo
     */
    private function filtrarPorTipoArchivo(Builder $query, string $tipoArchivo): void
    {
        $query->where('tipo_archivo', $tipoArchivo);
    }

    /**
     * Filtrar por estado de visibilidad
     */
    private function filtrarPorEstado(Builder $query, string $estado): void
    {
        $esVisible = $estado === 'visible';
        $query->where('es_visible', $esVisible);
    }



    /**
     * Crear nuevo archivo en el catálogo
     */
    public function crearArchivo(array $datos): CatalogoArchivo
    {
        return CatalogoArchivo::create([
            'nombre' => $datos['nombre'],
            'descripcion' => $datos['descripcion'] ?? null,
            'tipo_persona' => $datos['tipo_persona'],
            'tipo_archivo' => $datos['tipo_archivo'],
            'es_visible' => $datos['es_visible'] ?? true,
        ]);
    }

    /**
     * Actualizar archivo existente
     */
    public function actualizarArchivo(CatalogoArchivo $archivo, array $datos): bool
    {
        return $archivo->update([
            'nombre' => $datos['nombre'],
            'descripcion' => $datos['descripcion'] ?? null,
            'tipo_persona' => $datos['tipo_persona'],
            'tipo_archivo' => $datos['tipo_archivo'],
            'es_visible' => $datos['es_visible'] ?? true,
        ]);
    }

    /**
     * Cambiar estado de visibilidad de un archivo
     */
    public function toggleVisibilidad(CatalogoArchivo $archivo): bool
    {
        return $archivo->update(['es_visible' => !$archivo->es_visible]);
    }

    /**
     * Eliminar archivo del catálogo
     */
    public function eliminarArchivo(CatalogoArchivo $archivo): bool
    {
        return $archivo->delete();
    }

    /**
     * Obtener archivos visibles para un tipo de persona específico
     */
    public function getArchivosVisibles(string $tipoPersona = null): \Illuminate\Database\Eloquent\Collection
    {
        $query = CatalogoArchivo::visible();

        if ($tipoPersona) {
            $query->tipoPersona($tipoPersona);
        }

        return $query->orderBy('nombre', 'asc')->get();
    }

    /**
     * Obtener estadísticas del catálogo
     */
    public function getEstadisticas(): array
    {
        return [
            'total' => CatalogoArchivo::count(),
            'visibles' => CatalogoArchivo::where('es_visible', true)->count(),
            'ocultos' => CatalogoArchivo::where('es_visible', false)->count(),
            'por_tipo_persona' => [
                'fisica' => CatalogoArchivo::where('tipo_persona', 'Física')->count(),
                'moral' => CatalogoArchivo::where('tipo_persona', 'Moral')->count(),
                'ambas' => CatalogoArchivo::where('tipo_persona', 'Ambas')->count(),
            ],
            'por_tipo_archivo' => [
                'pdf' => CatalogoArchivo::where('tipo_archivo', 'pdf')->count(),
                'png' => CatalogoArchivo::where('tipo_archivo', 'png')->count(),
                'mp3' => CatalogoArchivo::where('tipo_archivo', 'mp3')->count(),
            ],
        ];
    }

    /**
     * Buscar archivos por nombre (para autocomplete)
     */
    public function buscarPorNombre(string $termino, int $limite = 10): \Illuminate\Database\Eloquent\Collection
    {
        return CatalogoArchivo::visible()
            ->where('nombre', 'like', "%{$termino}%")
            ->orderBy('nombre', 'asc')
            ->limit($limite)
            ->get(['id', 'nombre', 'tipo_archivo']);
    }

    /**
     * Verificar si un nombre ya existe
     */
    public function existeNombre(string $nombre, int $excluirId = null): bool
    {
        $query = CatalogoArchivo::where('nombre', $nombre);

        if ($excluirId) {
            $query->where('id', '!=', $excluirId);
        }

        return $query->exists();
    }
} 
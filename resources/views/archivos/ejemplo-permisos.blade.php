@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4>Gestión de Archivos</h4>
                    
                    @can('archivos.crear')
                        <a href="{{ route('archivos.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Crear Archivo
                        </a>
                    @endcan
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Descripción</th>
                                    <th>Tipo</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($archivos ?? [] as $archivo)
                                    <tr>
                                        <td>{{ $archivo->nombre_original }}</td>
                                        <td>{{ Str::limit($archivo->descripcion ?? '', 50) }}</td>
                                        <td>
                                            <span class="badge badge-info">{{ $archivo->extension }}</span>
                                        </td>
                                        <td>
                                            @switch($archivo->status)
                                                @case('Pendiente')
                                                    <span class="badge badge-warning">Pendiente</span>
                                                    @break
                                                @case('Aprobado')
                                                    <span class="badge badge-success">Aprobado</span>
                                                    @break
                                                @case('Rechazado')
                                                    <span class="badge badge-danger">Rechazado</span>
                                                    @break
                                                @default
                                                    <span class="badge badge-secondary">{{ $archivo->status }}</span>
                                            @endswitch
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                {{-- Removido @can para permitir que todos vean archivos --}}
                                                <a href="{{ route('archivos.show', $archivo->id) }}" 
                                                   class="btn btn-sm btn-info" 
                                                   title="Ver archivo">
                                                    <i class="fas fa-eye"></i>
                                                </a>

                                                @can('archivos.editar')
                                                    <a href="{{ route('archivos.edit', $archivo->id) }}" 
                                                       class="btn btn-sm btn-warning" 
                                                       title="Editar archivo">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                @endcan

                                                @can('archivos.eliminar')
                                                    <form action="{{ route('archivos.destroy', $archivo->id) }}" 
                                                          method="POST" 
                                                          style="display: inline;"
                                                          onsubmit="return confirm('¿Estás seguro de que quieres eliminar este archivo?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" 
                                                                class="btn btn-sm btn-danger" 
                                                                title="Eliminar archivo">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                @endcan
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">
                                            <p class="text-muted">No hay archivos disponibles</p>
                                            
                                            @can('archivos.crear')
                                                <a href="{{ route('archivos.create') }}" class="btn btn-primary">
                                                    Crear primer archivo
                                                </a>
                                            @endcan
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Información de permisos del usuario actual -->
                    <div class="mt-4">
                        <h5>Permisos del usuario actual:</h5>
                        <div class="row">
                            <div class="col-md-3">
                                @can('archivos.ver')
                                    <span class="badge badge-success">Ver archivos</span>
                                @else
                                    <span class="badge badge-danger">Sin permiso para ver</span>
                                @endcan
                            </div>
                            <div class="col-md-3">
                                @can('archivos.crear')
                                    <span class="badge badge-success">Crear archivos</span>
                                @else
                                    <span class="badge badge-danger">Sin permiso para crear</span>
                                @endcan
                            </div>
                            <div class="col-md-3">
                                @can('archivos.editar')
                                    <span class="badge badge-success">Editar archivos</span>
                                @else
                                    <span class="badge badge-danger">Sin permiso para editar</span>
                                @endcan
                            </div>
                            <div class="col-md-3">
                                @can('archivos.eliminar')
                                    <span class="badge badge-success">Eliminar archivos</span>
                                @else
                                    <span class="badge badge-danger">Sin permiso para eliminar</span>
                                @endcan
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

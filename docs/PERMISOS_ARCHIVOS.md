# Permisos Básicos de Archivos

Este documento describe los permisos básicos implementados para el módulo de archivos.

## Permisos Disponibles

### 1. `archivos.ver`
- **Descripción**: Permite ver archivos
- **Roles con acceso**: Todos los roles
- **Uso**: Para listar y visualizar archivos

### 2. `archivos.crear`
- **Descripción**: Permite crear nuevos archivos
- **Roles con acceso**: Super Administrador, Administrador, Proveedor, Solicitante
- **Uso**: Para subir y crear nuevos archivos

### 3. `archivos.editar`
- **Descripción**: Permite editar archivos existentes
- **Roles con acceso**: Todos los roles excepto roles básicos
- **Uso**: Para modificar información de archivos

### 4. `archivos.eliminar`
- **Descripción**: Permite eliminar archivos
- **Roles con acceso**: Solo Super Administrador y Administrador
- **Uso**: Para eliminar archivos del sistema

## Asignación por Roles

| Rol | Ver | Crear | Editar | Eliminar |
|-----|-----|-------|--------|----------|
| Super Administrador | ✅ | ✅ | ✅ | ✅ |
| Administrador | ✅ | ✅ | ✅ | ✅ |
| Revisor Digital | ✅ | ❌ | ✅ | ❌ |
| Revisor Presencial | ✅ | ❌ | ✅ | ❌ |
| Revisor Domiciliario | ✅ | ❌ | ✅ | ❌ |
| Proveedor | ✅ | ✅ | ✅ | ❌ |
| Solicitante | ✅ | ✅ | ✅ | ❌ |

## Uso en Controladores

### Verificación de Permisos

```php
// En el constructor del controlador
public function __construct()
{
    $this->middleware('permission:archivos.ver')->only(['index', 'show']);
    $this->middleware('permission:archivos.crear')->only(['create', 'store']);
    $this->middleware('permission:archivos.editar')->only(['edit', 'update']);
    $this->middleware('permission:archivos.eliminar')->only(['destroy']);
}
```

### Verificación Manual

```php
// En métodos específicos
public function store(Request $request)
{
    if (!auth()->user()->can('archivos.crear')) {
        return redirect()->back()->with('error', 'No tienes permisos para crear archivos');
    }
    
    // Lógica para crear archivo
}
```

## Uso en Vistas Blade

### Verificación en Vistas

```php
@can('archivos.crear')
    <a href="{{ route('archivos.create') }}" class="btn btn-primary">
        Crear Archivo
    </a>
@endcan

@can('archivos.editar')
    <a href="{{ route('archivos.edit', $archivo->id) }}" class="btn btn-warning">
        Editar
    </a>
@endcan

@can('archivos.eliminar')
    <form action="{{ route('archivos.destroy', $archivo->id) }}" method="POST">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger">Eliminar</button>
    </form>
@endcan
```

## Comandos Disponibles

### Crear Permisos de Archivos

```bash
php artisan permissions:archivos
```

Este comando crea los permisos básicos y los asigna a los roles existentes.

### Ejecutar Seeder Manualmente

```bash
php artisan db:seed --class=ArchivosPermissionsSeeder
```

## Middleware Personalizado

Se ha creado un middleware específico para archivos:

```php
// En routes/web.php
Route::middleware('archivos.permission:archivos.ver')->group(function () {
    Route::get('/archivos', [ArchivoController::class, 'index'])->name('archivos.index');
});
```

## Consideraciones de Seguridad

1. **Validación de Propiedad**: Los usuarios solo pueden ver/editar archivos que les pertenecen
2. **Verificación de Roles**: Los permisos se verifican tanto a nivel de middleware como en el controlador
3. **Logs de Auditoría**: Se recomienda implementar logs para acciones críticas como eliminación

## Extensibilidad

Para agregar nuevos permisos de archivos:

1. Agregar el permiso en `ArchivosPermissionsSeeder.php`
2. Asignar el permiso a los roles correspondientes
3. Actualizar la documentación
4. Implementar la verificación en controladores y vistas

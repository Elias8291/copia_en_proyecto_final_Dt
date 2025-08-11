# Permisos de Citas

Este documento describe los permisos implementados para el módulo de citas.

## Permisos Disponibles

### 1. `citas.ver`
- **Descripción**: Permite ver citas
- **Roles con acceso**: Todos los roles
- **Uso**: Para listar y visualizar citas

### 2. `citas.crear`
- **Descripción**: Permite crear nuevas citas
- **Roles con acceso**: Super Administrador, Administrador
- **Uso**: Para crear y programar nuevas citas

### 3. `citas.editar`
- **Descripción**: Permite editar citas existentes
- **Roles con acceso**: Super Administrador, Administrador, Revisor Digital, Revisor Presencial, Revisor Domiciliario
- **Uso**: Para modificar información de citas y cambiar estados

### 4. `citas.eliminar`
- **Descripción**: Permite eliminar citas
- **Roles con acceso**: Solo Super Administrador y Administrador
- **Uso**: Para eliminar citas del sistema

## Asignación por Roles

| Rol | Ver | Crear | Editar | Eliminar |
|-----|-----|-------|--------|----------|
| Super Administrador | ✅ | ✅ | ✅ | ✅ |
| Administrador | ✅ | ✅ | ✅ | ✅ |
| Revisor Digital | ✅ | ❌ | ✅ | ❌ |
| Revisor Presencial | ✅ | ❌ | ✅ | ❌ |
| Revisor Domiciliario | ✅ | ❌ | ✅ | ❌ |
| Proveedor | ✅ | ❌ | ❌ | ❌ |
| Solicitante | ✅ | ❌ | ❌ | ❌ |

## Uso en Controlador

### Verificación de Permisos

```php
// En el constructor del controlador
public function __construct()
{
    // Middleware de permisos para citas
    $this->middleware(PermissionMiddleware::class . ':citas.ver')->only(['index', 'show']);
    $this->middleware(PermissionMiddleware::class . ':citas.crear')->only(['create', 'store']);
    $this->middleware(PermissionMiddleware::class . ':citas.editar')->only(['edit', 'update', 'marcarAsistida', 'marcarNoAsistio', 'cancelar']);
    $this->middleware(PermissionMiddleware::class . ':citas.eliminar')->only(['destroy']);
}
```

### Verificación Manual

```php
// En métodos específicos
public function store(CitaRequest $request)
{
    if (!auth()->user()->can('citas.crear')) {
        return redirect()->back()->with('error', 'No tienes permisos para crear citas');
    }
    
    // Lógica para crear cita
}
```

## Uso en Vistas Blade

### Verificación en Vistas

```php
@can('citas.ver')
    <a href="{{ route('citas.index') }}" class="btn btn-primary">
        Ver Citas
    </a>
@endcan

@can('citas.crear')
    <a href="{{ route('citas.create') }}" class="btn btn-success">
        Crear Cita
    </a>
@endcan

@can('citas.editar')
    <button type="submit" class="btn btn-warning">
        Editar Cita
    </button>
@endcan

@can('citas.eliminar')
    <button type="submit" class="btn btn-danger">
        Eliminar Cita
    </button>
@endcan
```

## Comandos Disponibles

### Crear Permisos de Citas

```bash
php artisan permissions:citas
```

Este comando crea los permisos básicos y los asigna a los roles existentes.

### Ejecutar Seeder Manualmente

```bash
php artisan db:seed --class=CitasPermissionsSeeder
```

### Listar Permisos

```bash
php artisan permissions:list-citas
```

## Funcionalidades Protegidas

### Con permiso `citas.ver`:
- Ver lista de citas
- Ver detalles de una cita específica
- Filtrar y buscar citas

### Con permiso `citas.crear`:
- Crear nuevas citas
- Asignar citas a trámites
- Programar fechas y horarios

### Con permiso `citas.editar`:
- Editar información de citas existentes
- Cambiar estado de citas (Asistida, No Asistió, Cancelada)
- Reasignar citas a diferentes revisores

### Con permiso `citas.eliminar`:
- Eliminar citas del sistema
- Cancelar citas permanentemente

## Consideraciones de Seguridad

1. **Validación de Propiedad**: Los usuarios solo pueden ver/editar citas que les corresponden
2. **Verificación de Roles**: Los permisos se verifican tanto a nivel de middleware como en el controlador
3. **Logs de Auditoría**: Se recomienda implementar logs para acciones críticas como cancelación de citas

## Extensibilidad

Para agregar nuevos permisos de citas:

1. Agregar el permiso en `CitasPermissionsSeeder.php`
2. Asignar el permiso a los roles correspondientes
3. Actualizar la documentación
4. Implementar la verificación en controladores y vistas

# Permisos de Revisiones de Trámites

Este documento describe los permisos implementados para el módulo de revisiones de trámites.

## Permisos Disponibles

### 1. `revisiones.ver`
- **Descripción**: Permite ver revisiones de trámites
- **Roles con acceso**: Todos los roles
- **Uso**: Para listar y visualizar revisiones de trámites

### 2. `revisiones.revisar`
- **Descripción**: Permite revisar trámites (procesar revisiones)
- **Roles con acceso**: Super Administrador, Administrador, Revisor Digital, Revisor Presencial, Revisor Domiciliario
- **Uso**: Para procesar revisiones digitales, presenciales y domiciliarias

## Asignación por Roles

| Rol | Ver | Revisar |
|-----|-----|---------|
| Super Administrador | ✅ | ✅ |
| Administrador | ✅ | ✅ |
| Revisor Digital | ✅ | ✅ |
| Revisor Presencial | ✅ | ✅ |
| Revisor Domiciliario | ✅ | ✅ |
| Proveedor | ✅ | ❌ |
| Solicitante | ✅ | ❌ |

## Uso en Controlador

### Verificación de Permisos

```php
// En el constructor del controlador
public function __construct()
{
    // Middleware de permisos para revisiones
    $this->middleware(PermissionMiddleware::class . ':revisiones.ver')->only([
        'index', 
        'seleccionarTipoRevision', 
        'verTramiteHistorico', 
        'mostrarArchivo', 
        'obtenerEstadoSeccion', 
        'obtenerEstadoGeneral'
    ]);
    
    $this->middleware(PermissionMiddleware::class . ':revisiones.revisar')->only([
        'iniciarRevision', 
        'revisarTramite', 
        'agendarCita', 
        'reagendarCita', 
        'obtenerHorariosDisponibles', 
        'evaluarSeccion', 
        'procesarRevisionDigital', 
        'aprobarYAgendarCita', 
        'rechazarParaCorreccion', 
        'rechazarCompleto', 
        'aprobar', 
        'rechazarTramite', 
        'procesarRevisionPresencial'
    ]);
}
```

### Verificación Manual

```php
// En métodos específicos
public function procesarRevisionDigital(Request $request, Tramite $tramite)
{
    if (!auth()->user()->can('revisiones.revisar')) {
        return response()->json([
            'success' => false,
            'message' => 'No tienes permisos para revisar trámites'
        ], 403);
    }
    
    // Lógica para procesar revisión
}
```

## Uso en Vistas Blade

### Verificación en Vistas

```php
@can('revisiones.ver')
    <a href="{{ route('revisiones.index') }}" class="btn btn-primary">
        Ver Revisiones
    </a>
@endcan

@can('revisiones.revisar')
    <button type="submit" class="btn btn-success">
        Procesar Revisión
    </button>
@endcan
```

## Comandos Disponibles

### Crear Permisos de Revisiones

```bash
php artisan permissions:revisiones
```

Este comando crea los permisos básicos y los asigna a los roles existentes.

### Ejecutar Seeder Manualmente

```bash
php artisan db:seed --class=RevisionesPermissionsSeeder
```

### Listar Permisos

```bash
php artisan permissions:list-revisiones
```

## Funcionalidades Protegidas

### Con permiso `revisiones.ver`:
- Ver lista de trámites para revisión
- Seleccionar tipo de revisión
- Ver histórico de trámites
- Mostrar archivos
- Obtener estado de secciones
- Obtener estado general

### Con permiso `revisiones.revisar`:
- Iniciar revisión
- Revisar trámite
- Agendar citas
- Reagendar citas
- Obtener horarios disponibles
- Evaluar secciones
- Procesar revisión digital
- Procesar revisión presencial
- Aprobar trámites
- Rechazar trámites
- Aprobar y agendar citas
- Rechazar para corrección
- Rechazar completamente

## Consideraciones de Seguridad

1. **Validación de Propiedad**: Los usuarios solo pueden ver/editar revisiones que les corresponden
2. **Verificación de Roles**: Los permisos se verifican tanto a nivel de middleware como en el controlador
3. **Logs de Auditoría**: Se recomienda implementar logs para acciones críticas como aprobación/rechazo

## Extensibilidad

Para agregar nuevos permisos de revisiones:

1. Agregar el permiso en `RevisionesPermissionsSeeder.php`
2. Asignar el permiso a los roles correspondientes
3. Actualizar la documentación
4. Implementar la verificación en controladores y vistas

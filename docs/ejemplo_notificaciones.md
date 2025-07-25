# Ejemplo de Uso de Notificaciones

## Cómo Probar las Notificaciones

### 1. Crear Notificaciones de Prueba

Puedes crear notificaciones de prueba directamente en la base de datos o usar el servicio:

```php
// En tinker o en un controlador de prueba
use App\Services\NotificacionService;
use App\Models\User;
use App\Models\Tramite;

$notificacionService = app(NotificacionService::class);
$user = User::first(); // O el usuario que quieras
$tramite = Tramite::first(); // O el trámite que quieras

// Crear notificación de trámite
$notificacionService->crearNotificacion(
    $user->id,
    $tramite->id,
    'Tramite',
    'Estado de trámite actualizado',
    'Su trámite #' . $tramite->id . ' ha cambiado de estado a "En Revisión".'
);

// Crear notificación de cita
$notificacionService->crearNotificacion(
    $user->id,
    $tramite->id,
    'Cita',
    'Nueva cita agendada',
    'Se ha agendado una nueva cita para su trámite #' . $tramite->id . ' el día 15/01/2024 10:00.'
);

// Crear notificación del sistema
$notificacionService->crearNotificacion(
    $user->id,
    $tramite->id,
    'Sistema',
    'Recordatorio',
    'Tiene 3 trámites pendientes por revisar.'
);
```

### 2. Probar el Cambio de Estado de Trámite

```javascript
// En la consola del navegador o en un script
fetch('/revision/1/cambiar-estado', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
    },
    body: JSON.stringify({
        nuevo_estado: 'Por Cotejar',
        observaciones: 'Documentos pendientes de verificación física',
        fecha_cita: '2024-01-15 10:00:00'
    })
})
.then(response => response.json())
.then(data => {
    console.log('Respuesta:', data);
    if (data.success) {
        console.log('Estado actualizado correctamente');
        // Esto debería crear automáticamente:
        // 1. Una notificación de cambio de estado
        // 2. Una cita automática
        // 3. Una notificación de nueva cita
    }
});
```

### 3. Verificar las Notificaciones

```javascript
// Obtener notificaciones del usuario
fetch('/notificaciones/header')
.then(response => response.json())
.then(data => {
    console.log('Notificaciones:', data);
    if (data.success) {
        console.log('Notificaciones no leídas:', data.contador_no_leidas);
        data.notificaciones.forEach(notif => {
            console.log(`${notif.titulo}: ${notif.mensaje}`);
        });
    }
});
```

### 4. Marcar como Leída

```javascript
// Marcar una notificación específica como leída
fetch('/notificaciones/marcar-leida', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
    },
    body: JSON.stringify({ notificacion_id: 1 })
})
.then(response => response.json())
.then(data => {
    console.log('Marcada como leída:', data);
});

// Marcar todas como leídas
fetch('/notificaciones/marcar-todas-leidas', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
    }
})
.then(response => response.json())
.then(data => {
    console.log('Todas marcadas como leídas:', data);
});
```

## Funcionalidades Implementadas

### ✅ Notificaciones en Tiempo Real
- Las notificaciones se actualizan automáticamente cada 30 segundos
- Contador dinámico de notificaciones no leídas
- Iconos diferentes según el tipo de notificación

### ✅ Tipos de Notificación
- **Tramite**: Cambios de estado de trámites
- **Cita**: Notificaciones relacionadas con citas
- **Sistema**: Notificaciones del sistema
- **Vencimiento**: Recordatorios de vencimiento

### ✅ Interacciones
- Marcar notificación individual como leída
- Marcar todas las notificaciones como leídas
- Ver todas las notificaciones (enlace a página completa)

### ✅ Estados Visuales
- **No leída**: Fondo azul claro, texto más oscuro
- **Leída**: Fondo gris, texto más claro
- **Loading**: Spinner mientras carga
- **Vacío**: Mensaje cuando no hay notificaciones

## Flujo Completo de Prueba

1. **Crear notificaciones de prueba** usando el servicio
2. **Verificar que aparecen** en el header
3. **Cambiar estado de trámite** para generar notificaciones automáticas
4. **Marcar como leídas** y verificar que el contador cambia
5. **Verificar actualización automática** cada 30 segundos

## Notas Importantes

- Las notificaciones se cargan automáticamente al abrir el panel
- El contador se actualiza en tiempo real
- Los iconos y colores cambian según el tipo de notificación
- Las fechas se muestran en formato relativo (ej: "Hace 5 minutos")
- El sistema maneja errores graciosamente 
# Funcionalidades Implementadas

## Cambio de Estado de Trámites con Notificaciones y Citas

### Descripción General
Se han implementado las siguientes funcionalidades para el sistema de revisión de trámites:

1. **Cambio de Estado de Trámites**: Permite cambiar el estado de un trámite y notificar automáticamente al usuario asociado.
2. **Sistema de Notificaciones**: Notifica a los usuarios sobre cambios de estado y nuevas citas.
3. **Sistema de Citas**: Agenda automáticamente citas cuando el estado cambia a "Por Cotejar".
4. **Servicios Escalables**: Arquitectura modular para fácil mantenimiento y escalabilidad.

### Componentes Implementados

#### 1. Modelo Notificacion
- **Archivo**: `app/Models/Notificacion.php`
- **Funcionalidad**: Maneja las notificaciones de usuarios
- **Relaciones**: Usuario y Trámite
- **Métodos**: Marcar como leída, scopes para filtros

#### 2. Servicio de Notificaciones
- **Archivo**: `app/Services/NotificacionService.php`
- **Funcionalidad**: Lógica de negocio para notificaciones
- **Métodos principales**:
  - `crearNotificacion()`: Crea una nueva notificación
  - `notificarCambioEstado()`: Notifica cambios de estado
  - `notificarNuevaCita()`: Notifica nuevas citas
  - `obtenerNotificacionesNoLeidas()`: Obtiene notificaciones pendientes

#### 3. Servicio de Citas
- **Archivo**: `app/Services/CitaService.php`
- **Funcionalidad**: Lógica de negocio para citas
- **Métodos principales**:
  - `agendarCitaCotejo()`: Agenda cita para cotejo de documentos
  - `verificarDisponibilidad()`: Verifica horarios disponibles
  - `cancelarCita()`: Cancela citas con notificación
  - `obtenerCitasTramite()`: Obtiene citas de un trámite

#### 4. Controlador de Citas
- **Archivo**: `app/Http/Controllers/CitaController.php`
- **Funcionalidad**: Maneja las peticiones HTTP para citas
- **Endpoints**:
  - `GET /citas`: Lista de citas
  - `POST /citas/{tramite}`: Crear nueva cita
  - `PUT /citas/{cita}`: Actualizar cita
  - `POST /citas/{cita}/cancelar`: Cancelar cita

#### 5. Controlador de Notificaciones (Actualizado)
- **Archivo**: `app/Http/Controllers/NotificacionController.php`
- **Funcionalidad**: Maneja las peticiones HTTP para notificaciones
- **Endpoints**:
  - `GET /notificaciones`: Lista de notificaciones
  - `POST /notificaciones/marcar-leida`: Marcar como leída
  - `GET /notificaciones/contador`: Contador de no leídas

#### 6. Controlador de Revisión (Actualizado)
- **Archivo**: `app/Http/Controllers/RevisionController.php`
- **Nuevas funcionalidades**:
  - `cambiarEstadoTramite()`: Cambia estado y notifica
  - `historialEstados()`: Obtiene historial de cambios

### Flujo de Funcionamiento

#### Cambio de Estado de Trámite
1. **Usuario cambia estado** → `POST /revision/{tramite}/cambiar-estado`
2. **Sistema actualiza trámite** → Estado, observaciones, revisor
3. **Sistema notifica usuario** → Notificación automática
4. **Si estado es "Por Cotejar"** → Agenda cita automáticamente
5. **Sistema registra log** → Para auditoría

#### Agendamiento de Cita
1. **Estado cambia a "Por Cotejar"** → Trigger automático
2. **Sistema verifica disponibilidad** → Horarios libres
3. **Sistema agenda cita** → Fecha y hora automática
4. **Sistema notifica usuario** → Nueva cita agendada
5. **Sistema registra log** → Para auditoría

### Estados de Trámite Soportados
- `Pendiente`: Trámite recién creado
- `En Revisión`: En proceso de revisión
- `Por Cotejar`: Requiere cita para verificación física
- `Aprobado`: Trámite aprobado
- `Rechazado`: Trámite rechazado

### Tipos de Notificación
- `Sistema`: Notificaciones del sistema
- `Tramite`: Cambios de estado de trámites
- `Cita`: Notificaciones relacionadas con citas
- `Vencimiento`: Recordatorios de vencimiento

### Rutas Implementadas

#### Revisión de Trámites
```
POST /revision/{tramite}/cambiar-estado
GET /revision/{tramite}/historial-estados
```

#### Citas
```
GET /citas
GET /citas/crear/{tramite}
POST /citas/{tramite}
GET /citas/{cita}
PUT /citas/{cita}
POST /citas/{cita}/cancelar
GET /citas/tramite/{tramite}
POST /citas/verificar-disponibilidad
```

#### Notificaciones
```
GET /notificaciones
GET /notificaciones/contador
GET /notificaciones/header
POST /notificaciones/marcar-todas-leidas
POST /notificaciones/marcar-leida
GET /notificaciones/usuario
```

### Ejemplo de Uso

#### Cambiar Estado de Trámite
```javascript
// Frontend - Cambiar estado
fetch('/revision/123/cambiar-estado', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
    },
    body: JSON.stringify({
        nuevo_estado: 'Por Cotejar',
        observaciones: 'Documentos pendientes de verificación física',
        fecha_cita: '2024-01-15 10:00:00' // Opcional
    })
})
.then(response => response.json())
.then(data => {
    if (data.success) {
        alert('Estado actualizado correctamente');
        // El sistema automáticamente:
        // 1. Notificará al usuario
        // 2. Agendará cita si es necesario
    }
});
```

#### Obtener Notificaciones
```javascript
// Frontend - Obtener notificaciones
fetch('/notificaciones/usuario')
.then(response => response.json())
.then(data => {
    if (data.success) {
        data.notificaciones.forEach(notificacion => {
            console.log(`${notificacion.titulo}: ${notificacion.mensaje}`);
        });
    }
});
```

### Ventajas de la Implementación

1. **Escalabilidad**: Servicios modulares y reutilizables
2. **Mantenibilidad**: Separación clara de responsabilidades
3. **Auditoría**: Logs detallados de todas las operaciones
4. **Flexibilidad**: Fácil agregar nuevos tipos de notificación
5. **Automatización**: Procesos automáticos para citas
6. **Notificaciones en tiempo real**: Sistema de notificaciones integrado

### Próximos Pasos Sugeridos

1. **Implementar vistas**: Crear las vistas Blade para las nuevas funcionalidades
2. **Agregar validaciones**: Validaciones adicionales según reglas de negocio
3. **Implementar eventos**: Usar eventos de Laravel para mayor desacoplamiento
4. **Agregar tests**: Tests unitarios y de integración
5. **Optimizar consultas**: Optimizar consultas de base de datos
6. **Implementar cache**: Cache para notificaciones frecuentes 
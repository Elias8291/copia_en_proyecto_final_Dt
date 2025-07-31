# Sistema de Reagendamiento de Citas

## Descripción General

El sistema de reagendamiento de citas permite actualizar automáticamente las citas existentes en lugar de cancelarlas y crear nuevas. Esto mantiene un historial más limpio y es más eficiente.

## Funcionalidades Implementadas

### 1. Reagendamiento Inteligente
- **Actualización en lugar de cancelación**: Las citas existentes se actualizan con nueva fecha y hora
- **Estado "Reagendada"**: Se marca la cita con estado "Reagendada" para indicar que fue modificada
- **Historial de cambios**: Se registra en las observaciones cuándo fue reagendada la cita

### 2. Verificación de Citas Activas
- **Estados válidos**: Programada, Confirmada, Reagendada
- **API endpoint**: `/api/tramites/{tramite}/cita-activa`
- **Información visual**: Muestra detalles de la cita existente en la interfaz

### 3. Interfaz de Usuario Mejorada
- **Detección automática**: Verifica si existe cita al cargar la página
- **Colores diferenciados**: 
  - Azul para citas normales
  - Naranja para citas reagendadas
- **Texto dinámico**: El botón cambia entre "Agendar" y "Reagendar"

## Componentes del Sistema

### 1. CitaService
**Ubicación**: `app/Services/CitaService.php`

**Métodos principales**:
- `reagendarCitaTramite(Tramite $tramite)`: Reagenda cita existente
- `obtenerCitaActiva(Tramite $tramite)`: Obtiene cita activa
- `existeCitaActiva(Tramite $tramite)`: Verifica existencia de cita

### 2. RevisionController
**Ubicación**: `app/Http/Controllers/RevisionController.php`

**Métodos principales**:
- `agendarCitaAutomatica(Tramite $tramite)`: Maneja agendamiento/reagendamiento
- `obtenerCitaActiva(Tramite $tramite)`: API para obtener cita activa

### 3. Modelo Cita
**Ubicación**: `app/Models/Cita.php`

**Estados soportados**:
- Programada
- Confirmada
- Cancelada
- Reagendada
- Completada

## Flujo de Funcionamiento

### 1. Verificación de Cita Existente
```
Usuario accede a página de revisión
    ↓
JavaScript verifica cita activa via API
    ↓
Si existe cita: Muestra información y cambia botón a "Reagendar"
Si no existe: Botón muestra "Agendar"
```

### 2. Proceso de Reagendamiento
```
Usuario hace clic en "Reagendar Cita"
    ↓
Confirmación con modal
    ↓
CitaService.reagendarCitaTramite()
    ↓
Busca cita existente
    ↓
Obtiene nuevo horario disponible
    ↓
Actualiza cita existente (fecha, estado, observaciones)
    ↓
Notifica al usuario
```

### 3. Actualización de Interfaz
```
Cita reagendada exitosamente
    ↓
JavaScript actualiza información visual
    ↓
Cambia colores a naranja (reagendada)
    ↓
Actualiza fecha y estado mostrados
```

## APIs Disponibles

### GET /api/tramites/{tramite}/cita-activa
**Descripción**: Obtiene la cita activa para un trámite

**Respuesta exitosa**:
```json
{
    "success": true,
    "cita": {
        "id": 20,
        "fecha_cita": "2025-08-01T09:15:00.000000Z",
        "estado": "Reagendada",
        "tipo_cita": "Cotejo",
        "motivo": "Cotejo presencial de documentos para trámite #19",
        "observaciones": "Cita reagendada automáticamente el 31/07/2025 14:07"
    }
}
```

### POST /api/revision/{tramite}/agendar-cita
**Descripción**: Agenda o reagenda cita automáticamente

**Respuesta exitosa**:
```json
{
    "success": true,
    "message": "Cita reagendada exitosamente",
    "cita": {
        "id": 20,
        "fecha": "01/08/2025",
        "hora": "09:15",
        "tipo": "Cotejo",
        "accion": "reagendada"
    }
}
```

## Comandos de Prueba

### Probar Reagendamiento
```bash
# Probar con trámite específico
php artisan test:reagendar-cita 19

# Probar con primer trámite disponible
php artisan test:reagendar-cita
```

## Ventajas del Sistema

### 1. Eficiencia
- No se crean registros duplicados
- Menor uso de base de datos
- Historial más limpio

### 2. Trazabilidad
- Se mantiene el ID original de la cita
- Se registra cuándo fue reagendada
- Fácil seguimiento de cambios

### 3. Experiencia de Usuario
- Interfaz intuitiva con colores diferenciados
- Confirmaciones claras antes de reagendar
- Información visual actualizada en tiempo real

## Configuración

### 1. Estados de Cita
Los estados válidos están definidos en la migración:
```php
$table->enum('estado', ['Programada', 'Confirmada', 'Cancelada', 'Reagendada', 'Completada'])
```

### 2. Horarios Disponibles
Configurados en `CitaService`:
```php
private const HORA_INICIO = 9;
private const HORA_FIN = 14;
private const DURACION_CITA = 15;
```

### 3. Días Hábiles
El sistema respeta los días inhábiles configurados en la tabla `dias_inhabiles`.

## Mantenimiento

### 1. Logs
El sistema registra logs detallados para:
- Errores de reagendamiento
- Citas agendadas exitosamente
- Problemas de disponibilidad

### 2. Notificaciones
Se envían notificaciones automáticas cuando:
- Se agenda una nueva cita
- Se reagenda una cita existente
- No se puede agendar por falta de disponibilidad

### 3. Monitoreo
Se puede monitorear el sistema mediante:
- Comandos de prueba
- Logs de aplicación
- APIs de verificación 
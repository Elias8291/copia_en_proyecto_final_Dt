# Sistema de Citas Automáticas para Cotejo

## Descripción General

El sistema de citas automáticas se activa cuando un trámite es enviado a cotejo presencial. Automáticamente agenda una cita en el próximo horario disponible, considerando días hábiles e inhábiles.

## Características Principales

### 1. Gestión de Días Hábiles/Inhábiles
- **Días hábiles**: Lunes a Viernes (excluyendo días inhábiles)
- **Días inhábiles**: Fines de semana + días festivos + vacaciones
- **Horario laboral**: 9:00 AM a 2:00 PM
- **Duración de cita**: 30 minutos

### 2. Agendación Automática
- Se activa cuando el estado del trámite cambia a "Por_Cotejar"
- Busca el próximo horario disponible en los próximos 30 días
- Considera días hábiles e inhábiles
- Evita conflictos con citas existentes

### 3. Configuración de Días Inhábiles
- **Tipos**: Feriado, Vacaciones, Otro
- **Rangos**: Fecha de inicio y fin (permite rangos de varios días)
- **Estado**: Activo/Inactivo

## Estructura de Base de Datos

### Tabla: dias_inhabiles
```sql
CREATE TABLE dias_inhabiles (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    descripcion VARCHAR(255) NOT NULL,
    fecha_inicio DATE NOT NULL,
    fecha_fin DATE NOT NULL,
    tipo ENUM('Vacaciones', 'Feriado', 'Otro') DEFAULT 'Feriado',
    observaciones TEXT NULL,
    activo BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    INDEX idx_fechas (fecha_inicio, fecha_fin),
    INDEX idx_activo (activo)
);
```

### Tabla: citas (actualizada)
```sql
CREATE TABLE citas (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    tramite_id BIGINT NULL,
    user_id BIGINT NULL,
    fecha_cita DATETIME NOT NULL,
    tipo_cita ENUM('Revision', 'Cotejo', 'Entrega', 'Consulta', 'Otro', 'Reunion', 'Administrativa'),
    estado ENUM('Programada', 'Confirmada', 'Cancelada', 'Reagendada', 'Completada') DEFAULT 'Programada',
    atendido_por BIGINT NULL,
    observaciones TEXT NULL,
    motivo TEXT NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

## Componentes del Sistema

### 1. Modelo DiaInhabil
**Ubicación**: `app/Models/DiaInhabil.php`

**Métodos principales**:
- `esInhabil(Carbon $fecha)`: Verifica si una fecha es inhábil
- `esHabil(Carbon $fecha)`: Verifica si una fecha es hábil
- `proximoDiaHabil(Carbon $fecha)`: Obtiene el próximo día hábil
- `proximoDiaHabilConHora(Carbon $fecha, int $hora, int $minuto)`: Obtiene próximo día hábil con hora específica

### 2. Servicio CitaService
**Ubicación**: `app/Services/CitaService.php`

**Métodos principales**:
- `agendarCitaCotejo(Tramite $tramite)`: Agenda cita automática para cotejo
- `obtenerProximoHorarioDisponible()`: Busca próximo horario disponible
- `buscarHorarioEnDia(Carbon $fecha)`: Busca horario en día específico
- `verificarDisponibilidadFechaHora(Carbon $fechaHora)`: Verifica disponibilidad
- `obtenerHorariosDisponibles(Carbon $fecha)`: Obtiene horarios disponibles

### 3. Controlador RevisionController
**Ubicación**: `app/Http/Controllers/RevisionController.php`

**Integración**:
- Método `actualizarTramite()`: Detecta cambio a "Por_Cotejar" y agenda cita
- Método `agendarCitaCotejo()`: Llama al servicio para agendar cita

### 4. Controlador DiaInhabilController
**Ubicación**: `app/Http/Controllers/DiaInhabilController.php`

**Funcionalidades**:
- CRUD completo para días inhábiles
- API para verificar fechas hábiles
- API para obtener próximos días hábiles

## Flujo de Funcionamiento

### 1. Cambio de Estado a Cotejo
```
Usuario cambia estado → "Por_Cotejar"
    ↓
RevisionController::actualizarTramite()
    ↓
Detecta estado "Por_Cotejar"
    ↓
Llama agendarCitaCotejo()
```

### 2. Agendación Automática
```
CitaService::agendarCitaCotejo()
    ↓
obtenerProximoHorarioDisponible()
    ↓
Busca próximo día hábil
    ↓
buscarHorarioEnDia()
    ↓
Verifica conflictos con citas existentes
    ↓
Crea cita automáticamente
```

### 3. Verificación de Días Hábiles
```
DiaInhabil::esHabil()
    ↓
Verifica que no sea fin de semana
    ↓
Verifica que no esté en días inhábiles
    ↓
Retorna true/false
```

## Configuración y Uso

### 1. Instalación
```bash
# Ejecutar migraciones
php artisan migrate

# Ejecutar seeders
php artisan db:seed --class=DiasInhabilesSeeder
```

### 2. Comando de Prueba
```bash
# Probar sistema completo
php artisan test:cita-automatica

# Probar con trámite específico
php artisan test:cita-automatica 1
```

### 3. Gestión de Días Inhábiles
```bash
# Rutas disponibles
GET    /dias-inhabiles              # Listar días inhábiles
GET    /dias-inhabiles/create       # Formulario crear
POST   /dias-inhabiles              # Crear día inhábil
GET    /dias-inhabiles/{id}         # Ver día inhábil
GET    /dias-inhabiles/{id}/edit    # Formulario editar
PUT    /dias-inhabiles/{id}         # Actualizar día inhábil
DELETE /dias-inhabiles/{id}         # Eliminar día inhábil

# APIs
POST   /dias-inhabiles/verificar-fecha    # Verificar si fecha es hábil
GET    /dias-inhabiles/proximos-dias      # Obtener próximos días hábiles
```

## Días Festivos de México (2025-2026)

### 2025
- 1 Enero: Año Nuevo
- 3 Febrero: Día de la Constitución (primer lunes)
- 17 Marzo: Natalicio de Benito Juárez (tercer lunes)
- 18 Abril: Viernes Santo
- 1 Mayo: Día del Trabajo
- 16 Septiembre: Día de la Independencia
- 2 Noviembre: Día de los Muertos
- 17 Noviembre: Día de la Revolución (tercer lunes)
- 25 Diciembre: Navidad

### 2026
- 1 Enero: Año Nuevo
- 2 Febrero: Día de la Constitución (primer lunes)
- 16 Marzo: Natalicio de Benito Juárez (tercer lunes)
- 1 Mayo: Día del Trabajo
- 16 Septiembre: Día de la Independencia
- 16 Noviembre: Día de la Revolución (tercer lunes)
- 25 Diciembre: Navidad

## Consideraciones Técnicas

### 1. Rendimiento
- Índices en fechas para consultas rápidas
- Búsqueda limitada a 30 días para evitar bucles infinitos
- Cache de días hábiles para consultas frecuentes

### 2. Validaciones
- Verificación de rangos de fechas
- Validación de conflictos de horarios
- Verificación de días hábiles antes de agendar

### 3. Logs
- Registro de citas agendadas automáticamente
- Logs de errores en agendación
- Trazabilidad de cambios de estado

### 4. Notificaciones
- Integración con sistema de notificaciones existente
- Notificación al usuario sobre cita agendada
- Recordatorios de citas próximas

## Personalización

### 1. Horarios Laborales
Modificar constantes en `CitaService`:
```php
private const HORA_INICIO = 9;    // 9:00 AM
private const HORA_FIN = 14;      // 2:00 PM
private const DURACION_CITA = 30; // 30 minutos
```

### 2. Días de la Semana
Modificar en `DiaInhabil::esHabil()`:
```php
// Verificar si es fin de semana (sábado = 6, domingo = 0)
if ($fecha->dayOfWeek === 0 || $fecha->dayOfWeek === 6) {
    return false;
}
```

### 3. Días Inhábiles Adicionales
Agregar en `DiasInhabilesSeeder` o mediante interfaz web.

## Troubleshooting

### 1. No se agendan citas
- Verificar que existan días hábiles disponibles
- Revisar logs de errores
- Verificar configuración de días inhábiles

### 2. Conflictos de horarios
- Verificar citas existentes
- Revisar duración de citas
- Verificar solapamientos

### 3. Días inhábiles no se aplican
- Verificar estado activo de días inhábiles
- Revisar rangos de fechas
- Verificar tipos de días inhábiles 
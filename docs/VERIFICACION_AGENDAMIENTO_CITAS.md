# Verificación del Agendamiento Automático de Citas

## Pregunta
¿Cuando se aprueba y agenda la cita, realmente se está agendando la cita?

## Respuesta
Sí, el sistema está diseñado para agendar citas automáticamente cuando se hace clic en "Aprobar y Agendar Cita Presencial". **Importante**: El estado del trámite se mantiene en "Revision_Digital" en lugar de cambiar a "Revision_Presencial".

## Flujo de Agendamiento

### 1. Proceso Automático
```php
// En RevisionDigitalService::procesarRevisionDigital()
if (($data['decision_final'] ?? null) === 'agendar_cita') {
    $resultadoCita = $this->citasService->agendarCitaRevisionDigital($tramite->id);
    if (!$resultadoCita['success']) {
        throw new \Exception('Error al agendar la cita: ' . $resultadoCita['message']);
    }
}
```

### 2. Método de Agendamiento
```php
// En CitasService::agendarCitaRevisionDigital()
public function agendarCitaRevisionDigital(int $tramiteId): array
{
    // 1. Buscar revisores presenciales disponibles
    $revisores = $this->obtenerRevisoresPresenciales();
    
    // 2. Buscar primer horario disponible
    $fechaHora = $this->buscarPrimerHorarioDisponible($revisores);
    
    // 3. Crear la cita
    $cita = Cita::create([
        'tramite_id' => $tramiteId,
        'tipo_cita' => 'Presencial',
        'fecha_cita' => $fechaHora['datetime'],
        'estado' => 'Asignada',
        'asignado_a' => $fechaHora['revisor_id'],
        'intento' => 1
    ]);
    
    // 4. NO cambiar el estado del trámite - se mantiene en Revision_Digital
    // $tramite->update(['status' => TramiteStatus::REVISION_PRESENCIAL->value]);
    
    // 5. Enviar notificaciones
    $this->notificacionService->notificarCitaAgendada($cita);
    
    return ['success' => true, 'cita' => $cita, ...];
}
```

### 3. Estado del Trámite
```php
// En RevisionDigitalService::determinarEstadoFinalDigital()
'agendar_cita' => TramiteStatus::REVISION_DIGITAL->value, // Mantiene en revisión digital
```

## Comportamiento del Estado

### ✅ **Estado Correcto**: Revision_Digital
- Cuando se hace clic en "Aprobar y Agendar Cita Presencial"
- El trámite **NO** cambia a "Revision_Presencial"
- Se mantiene en "Revision_Digital"
- La cita se crea automáticamente
- Se envían notificaciones

### 🔄 **Flujo de Estados**:
1. **Pendiente** → **Revision_Digital** (inicio de revisión)
2. **Revision_Digital** → **Revision_Digital** (con cita agendada) ✅
3. **Revision_Digital** → **Revision_Presencial** (solo cuando se inicia la cita presencial)

## Requisitos para que Funcione

### 1. Revisores Presenciales
**Problema más común**: No hay usuarios con el rol "Revisor Presencial"

#### Verificar:
```bash
php artisan revisores:verificar-presenciales
```

#### Solucionar:
```php
// Crear un revisor presencial
$user = User::create([
    'name' => 'Revisor Presencial',
    'email' => 'revisor.presencial@example.com',
    'password' => Hash::make('password')
]);

$user->assignRole('Revisor Presencial');
```

### 2. Horarios Disponibles
**Problema**: No hay horarios disponibles en los próximos 30 días

#### Verificar:
- Los revisores no tienen citas que se superpongan
- Los días no son inhábiles
- Los horarios están dentro del rango laboral (9:00 - 14:00)

#### Solucionar:
```php
// Verificar días inhábiles
DiaInhabil::where('fecha', '>=', now()->format('Y-m-d'))->get();

// Verificar citas existentes
Cita::where('asignado_a', $revisorId)
    ->where('estado', 'Asignada')
    ->where('fecha_cita', '>=', now())
    ->get();
```

### 3. Configuración de Horarios
```php
// En CitasService
private const HORA_INICIO = 9;        // 9:00 AM
private const HORA_FIN = 14;          // 2:00 PM
private const DURACION_CITA_MINUTOS = 25;
private const DIAS_LABORALES = [1, 2, 3, 4, 5]; // Lunes a Viernes
```

## Verificación Paso a Paso

### 1. Verificar Revisores
```bash
php artisan revisores:verificar-presenciales
```

### 2. Verificar Logs
```bash
tail -f storage/logs/laravel.log
```

Buscar mensajes como:
```
[INFO] Iniciando agendamiento de cita para trámite
[INFO] Revisores presenciales encontrados
[INFO] Horario encontrado
[INFO] Cita creada exitosamente
```

### 3. Verificar Base de Datos
```sql
-- Verificar citas creadas
SELECT * FROM citas WHERE tramite_id = [ID_DEL_TRAMITE] ORDER BY created_at DESC;

-- Verificar estado del trámite (debe ser "Revision_Digital")
SELECT id, status FROM tramites WHERE id = [ID_DEL_TRAMITE];

-- Verificar revisores presenciales
SELECT u.name, u.email, r.name as role_name 
FROM users u 
JOIN model_has_roles mhr ON u.id = mhr.model_id 
JOIN roles r ON mhr.role_id = r.id 
WHERE r.name = 'Revisor Presencial';
```

## Posibles Errores y Soluciones

### Error: "No hay revisores presenciales disponibles"
**Causa**: No hay usuarios con el rol "Revisor Presencial"

**Solución**:
1. Crear usuarios con el rol correcto
2. Verificar que el rol existe en la tabla `roles`

### Error: "No hay horarios disponibles en los próximos 30 días"
**Causa**: Todos los revisores están ocupados o no hay días laborales disponibles

**Solución**:
1. Verificar la disponibilidad de los revisores
2. Revisar los días inhábiles
3. Ajustar la configuración de horarios si es necesario

### Error: "Error al crear la cita"
**Causa**: Problema en la base de datos o validación

**Solución**:
1. Verificar que la tabla `citas` existe y tiene la estructura correcta
2. Revisar las validaciones del modelo `Cita`
3. Verificar los logs para más detalles

## Logs de Depuración

El sistema ahora incluye logs detallados para depurar problemas:

```php
\Log::info('Iniciando agendamiento de cita para trámite', ['tramite_id' => $tramiteId]);
\Log::info('Revisores presenciales encontrados', ['cantidad_revisores' => $revisores->count()]);
\Log::info('Horario encontrado', ['fecha_hora' => $fechaHora['datetime']]);
\Log::info('Cita creada exitosamente', ['cita_id' => $cita->id]);
```

## Comando de Verificación

```bash
# Verificar revisores presenciales
php artisan revisores:verificar-presenciales

# Verificar logs en tiempo real
tail -f storage/logs/laravel.log
```

## Resultado Esperado

Cuando funciona correctamente, deberías ver:

1. **En la interfaz**: Mensaje "¡Trámite Aprobado y Cita Presencial Agendada!"
2. **En la base de datos**: 
   - Nueva cita creada en la tabla `citas`
   - Estado del trámite **mantenido** en "Revision_Digital" ✅
3. **En los logs**: Mensajes de éxito del proceso de agendamiento
4. **Notificaciones**: Notificaciones enviadas al solicitante y al revisor

## Prueba Manual

Para probar manualmente:

1. Ir a un trámite en revisión digital
2. Evaluar todas las secciones
3. Hacer clic en "Aprobar y Agendar Cita Presencial"
4. Verificar que aparece el mensaje de éxito
5. Verificar en la base de datos que:
   - Se creó la cita
   - El estado del trámite sigue siendo "Revision_Digital" ✅
6. Verificar que se enviaron las notificaciones

## Cambios Recientes

### ✅ **Modificación del Estado**
- **Antes**: El trámite cambiaba a "Revision_Presencial"
- **Ahora**: El trámite se mantiene en "Revision_Digital"
- **Razón**: Mantener el control en la revisión digital hasta que se inicie la cita presencial

### ✅ **Comportamiento Correcto**
- La cita se agenda automáticamente
- El estado se mantiene en revisión digital
- Se pueden seguir haciendo revisiones digitales si es necesario
- La transición a presencial ocurre cuando se inicia la cita 
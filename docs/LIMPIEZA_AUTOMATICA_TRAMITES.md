# Limpieza Automática de Trámites Vencidos

## Descripción

Este sistema permite limpiar automáticamente trámites que están pendientes y cuya cita ya venció después de un período de tolerancia configurable.

## Comando Disponible

```bash
# Ver qué trámites serían cancelados (modo dry-run)
php artisan tramites:limpiar-vencidos --dry-run

# Ejecutar con tolerancia de 30 días (por defecto)
php artisan tramites:limpiar-vencidos

# Ejecutar con tolerancia personalizada (ej: 15 días)
php artisan tramites:limpiar-vencidos --dias=15

# Combinar opciones
php artisan tramites:limpiar-vencidos --dias=15 --dry-run
```

## Configuración Automática

### Programar Tarea Automática

El sistema está configurado para ejecutar automáticamente la limpieza todos los días a las 2:00 AM.

Para activar el scheduler en el servidor:

#### En Windows (Task Scheduler):
1. Abrir "Programador de tareas"
2. Crear tarea básica
3. Configurar para ejecutar diariamente
4. Acción: `php artisan schedule:run`
5. Programar para las 2:00 AM

#### En Linux (Cron):
```bash
# Agregar al crontab
crontab -e

# Agregar esta línea:
0 2 * * * cd /path/to/project && php artisan schedule:run >> /dev/null 2>&1
```

### Verificar Scheduler

```bash
# Ver todas las tareas programadas
php artisan schedule:list

# Ejecutar scheduler manualmente
php artisan schedule:run
```

## Criterios de Limpieza

### Trámites que se cancelan automáticamente:

1. **Estado**: `Por_Cotejar`
2. **Cita vencida**: Más de 30 días (configurable)
3. **Estado de cita**: `Programada`, `Confirmada`, o `Reagendada`

### Acciones realizadas:

1. **Actualizar trámite**:
   - Estado: `Cancelado`
   - Fecha de cancelación: `now()`

2. **Actualizar cita**:
   - Estado: `Cancelada`

3. **Registrar en logs**:
   - Información del trámite cancelado
   - Fecha de cita vencida
   - Días transcurridos

## Logs y Monitoreo

### Archivos de log:
- `storage/logs/laravel.log` - Logs generales
- `storage/logs/tramites-limpiados.log` - Log específico del scheduler

### Ejemplo de log:
```
[2025-07-31 15:30:00] local.INFO: Trámite cancelado automáticamente por cita vencida {
    "tramite_id": 123,
    "proveedor_id": 456,
    "fecha_cita": "2025-06-30 10:00:00",
    "dias_vencida": 31
}
```

## Configuración Avanzada

### Modificar días de tolerancia:

**Para comando manual**: Usar opción `--dias`
**Para scheduler automático**: Editar `app/Console/Commands/LimpiarTramitesVencidos.php`

```php
// Cambiar esta línea:
$dias = $this->option('dias');
```

### Modificar horario de ejecución:

Editar `app/Console/Kernel.php`:

```php
// Ejecutar cada 12 horas
$schedule->command('tramites:limpiar-automatico')
         ->twiceDaily(2, 14);

// Ejecutar solo los lunes
$schedule->command('tramites:limpiar-automatico')
         ->weeklyOn(1, '02:00');
```

## Seguridad y Validaciones

### Validaciones implementadas:

1. **Solo trámites en estado `Por_Cotejar`**
2. **Solo citas con estados válidos**
3. **Tolerancia mínima de 1 día**
4. **Logging completo de acciones**
5. **Manejo de errores**

### Prevención de pérdida de datos:

- **Modo dry-run**: Permite verificar antes de ejecutar
- **Logs detallados**: Registro de todas las acciones
- **Transacciones**: Rollback automático en caso de error

## Troubleshooting

### Problemas comunes:

1. **Comando no encontrado**:
   ```bash
   php artisan config:clear
   php artisan cache:clear
   ```

2. **Scheduler no ejecuta**:
   - Verificar que el cron esté activo
   - Verificar permisos de archivos
   - Revisar logs de errores

3. **No se cancelan trámites**:
   - Verificar criterios de búsqueda
   - Revisar fechas de citas
   - Verificar estados de trámites

### Comandos de diagnóstico:

```bash
# Ver trámites con citas vencidas
php artisan tinker
>>> App\Models\Tramite::where('estado', 'Por_Cotejar')->whereHas('cita', function($q) { $q->where('fecha_cita', '<', now()->subDays(30)); })->count();

# Ver logs de limpieza
tail -f storage/logs/tramites-limpiados.log
```

## Notas Importantes

- **Backup**: Siempre hacer backup antes de ejecutar en producción
- **Testing**: Probar en ambiente de desarrollo primero
- **Monitoreo**: Revisar logs regularmente
- **Comunicación**: Notificar a usuarios sobre trámites cancelados 
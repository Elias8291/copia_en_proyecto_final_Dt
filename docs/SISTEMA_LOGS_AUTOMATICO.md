# Sistema de Logs Automático

## Descripción

El sistema de logs automático captura y registra todos los errores y eventos del sistema sin mostrar notificaciones al usuario. Todos los errores se almacenan en la base de datos para auditoría y monitoreo.

## Características

- ✅ **Captura automática de errores**: Todos los errores se registran automáticamente
- ✅ **Sin notificaciones al usuario**: Los errores no interrumpen la experiencia del usuario
- ✅ **Logs detallados**: Información completa de cada error (stack trace, contexto, etc.)
- ✅ **Filtros avanzados**: Búsqueda por nivel, canal, usuario, fecha, etc.
- ✅ **Limpieza automática**: Eliminación automática de logs antiguos
- ✅ **Exportación**: Exportar logs en múltiples formatos
- ✅ **Monitoreo de rendimiento**: Detección de problemas de memoria y tiempo de ejecución

## Componentes del Sistema

### 1. ErrorLogService (`app/Services/ErrorLogService.php`)

Servicio principal que maneja el registro de errores:

```php
// Registrar error general
ErrorLogService::logError($exception, $request);

// Registrar error de API
ErrorLogService::logApiError($statusCode, $message, $request);

// Registrar error de autenticación
ErrorLogService::logAuthenticationError($message, $request);

// Registrar error de permisos
ErrorLogService::logPermissionError($permission, $request);
```

### 2. GlobalErrorLoggingMiddleware (`app/Http/Middleware/GlobalErrorLoggingMiddleware.php`)

Middleware que captura automáticamente todos los errores HTTP y excepciones.

### 3. Modelo Log (`app/Models/Log.php`)

Modelo para almacenar los logs en la base de datos.

### 4. LogController (`app/Http/Controllers/LogController.php`)

Controlador para gestionar y mostrar los logs.

## Configuración

### Variables de Entorno

```env
# Habilitar/deshabilitar sistema de logs automático
AUTO_LOGGING_ENABLED=true

# Configuración de limpieza automática
LOG_CLEANUP_ENABLED=true
LOG_DAYS_TO_KEEP=30

# Umbrales de rendimiento
LOG_MEMORY_THRESHOLD_MB=100
LOG_EXECUTION_TIME_THRESHOLD=5.0
```

### Archivo de Configuración

El archivo `config/logging_system.php` contiene toda la configuración del sistema.

## Uso

### Ver Logs

1. Acceder a `/logs` para ver todos los logs del sistema
2. Usar filtros para buscar logs específicos
3. Exportar logs en diferentes formatos

### Comandos Artisan

```bash
# Limpiar logs antiguos
php artisan logs:clean --days=30

# Limpiar logs de un nivel específico
php artisan logs:clean --days=7 --level=debug
```

### Programar Limpieza Automática

Agregar al `app/Console/Kernel.php`:

```php
protected function schedule(Schedule $schedule)
{
    // Limpiar logs diariamente
    $schedule->command('logs:clean --days=30')->daily();
}
```

## Tipos de Logs Registrados

### 1. Excepciones del Sistema
- Errores de PHP/Laravel
- Excepciones no manejadas
- Errores de base de datos

### 2. Errores de API
- Errores HTTP (4xx, 5xx)
- Errores de validación
- Errores de autenticación

### 3. Eventos de Seguridad
- Intentos de acceso no autorizado
- Tokens CSRF inválidos
- Límites de tasa excedidos

### 4. Errores de Autenticación
- Intentos de login fallidos
- Sesiones expiradas
- Errores de permisos

### 5. Problemas de Rendimiento
- Alto uso de memoria
- Tiempos de ejecución lentos
- Errores de archivos

## Estructura de la Base de Datos

```sql
CREATE TABLE logs (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    level VARCHAR(20) NOT NULL,
    message VARCHAR(1000) NOT NULL,
    channel VARCHAR(100) NULL,
    context TEXT NULL,
    user_id BIGINT UNSIGNED NULL,
    ip_address VARCHAR(45) NULL,
    user_agent VARCHAR(500) NULL,
    url VARCHAR(1000) NULL,
    method VARCHAR(10) NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);
```

## Filtros Disponibles

- **Nivel**: error, warning, info, debug
- **Canal**: exceptions, api, security, validation, etc.
- **Usuario**: Filtrar por usuario específico
- **Fecha**: Rango de fechas
- **IP**: Dirección IP específica
- **Búsqueda**: Texto libre en mensajes

## Exportación

Los logs se pueden exportar en:
- CSV
- JSON
- Excel (XLSX)

## Monitoreo y Alertas

El sistema incluye:
- Detección automática de problemas de rendimiento
- Logs de memoria y tiempo de ejecución
- Alertas para uso alto de recursos

## Seguridad

- **Redacción de datos sensibles**: Contraseñas, tokens, etc. se redactan automáticamente
- **Sanitización de entrada**: Los datos de entrada se limpian antes del registro
- **Control de acceso**: Solo usuarios autorizados pueden ver los logs

## Mantenimiento

### Limpieza Regular

```bash
# Limpiar logs de más de 30 días
php artisan logs:clean --days=30

# Limpiar solo logs de debug
php artisan logs:clean --days=7 --level=debug
```

### Backup

Se recomienda hacer backup regular de la tabla `logs`:

```bash
mysqldump -u usuario -p base_datos logs > logs_backup.sql
```

## Troubleshooting

### Problemas Comunes

1. **Logs no se registran**: Verificar que `AUTO_LOGGING_ENABLED=true`
2. **Errores de base de datos**: Verificar conexión y permisos
3. **Alto uso de memoria**: Ajustar `LOG_MEMORY_THRESHOLD_MB`

### Logs de Debug

Para debuggear el sistema de logs:

```php
// En cualquier lugar del código
\Log::debug('Mensaje de debug', ['context' => 'datos']);
```

## Integración con Otros Sistemas

El sistema se puede integrar con:
- **Sentry**: Para monitoreo de errores en producción
- **Slack**: Para alertas en tiempo real
- **Email**: Para notificaciones por correo
- **APIs externas**: Para envío de logs a servicios de terceros

## Consideraciones de Rendimiento

- Los logs se escriben de forma asíncrona cuando es posible
- Se implementa rate limiting para evitar spam de logs
- Los logs antiguos se limpian automáticamente
- Se usan índices en la base de datos para consultas rápidas


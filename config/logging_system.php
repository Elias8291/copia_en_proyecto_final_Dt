<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Configuración del Sistema de Logs Automático
    |--------------------------------------------------------------------------
    |
    | Configuración para el sistema de logs automático que captura
    | todos los errores y eventos del sistema sin mostrar notificaciones
    | al usuario.
    |
    */

    // Habilitar/deshabilitar el sistema de logs automático
    'enabled' => env('AUTO_LOGGING_ENABLED', true),

    // Niveles de log que se registrarán automáticamente
    'levels' => [
        'error',
        'warning', 
        'info',
        'debug',
    ],

    // Canales de log disponibles
    'channels' => [
        'exceptions' => 'Excepciones del sistema',
        'api' => 'Errores de API',
        'security' => 'Eventos de seguridad',
        'validation' => 'Errores de validación',
        'database' => 'Errores de base de datos',
        'files' => 'Errores de archivos',
        'auth' => 'Eventos de autenticación',
        'permissions' => 'Errores de permisos',
        'system' => 'Eventos del sistema',
    ],

    // Configuración de limpieza automática
    'cleanup' => [
        'enabled' => env('LOG_CLEANUP_ENABLED', true),
        'days_to_keep' => env('LOG_DAYS_TO_KEEP', 30),
        'schedule' => 'daily', // daily, weekly, monthly
    ],

    // Configuración de rendimiento
    'performance' => [
        'memory_threshold_mb' => env('LOG_MEMORY_THRESHOLD_MB', 100),
        'execution_time_threshold_seconds' => env('LOG_EXECUTION_TIME_THRESHOLD', 5.0),
    ],

    // Campos sensibles que se redactarán en los logs
    'sensitive_fields' => [
        'password',
        'password_confirmation',
        'current_password',
        'new_password',
        'token',
        '_token',
        'api_key',
        'secret',
        'credit_card',
        'ssn',
        'rfc',
        'email',
    ],

    // Configuración de notificaciones (deshabilitadas por defecto)
    'notifications' => [
        'enabled' => false, // No mostrar notificaciones al usuario
        'email_alerts' => false,
        'slack_alerts' => false,
    ],

    // Configuración de exportación
    'export' => [
        'enabled' => true,
        'formats' => ['csv', 'json', 'xlsx'],
        'max_records' => 10000,
    ],

    // Configuración de búsqueda y filtros
    'search' => [
        'enabled' => true,
        'max_results' => 1000,
        'filters' => [
            'level',
            'channel', 
            'user_id',
            'date_range',
            'ip_address',
        ],
    ],
];


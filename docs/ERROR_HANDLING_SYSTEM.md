# Sistema de Manejo de Errores

## Descripción General

Este sistema proporciona un manejo completo y consistente de errores tanto para rutas web como para APIs, con páginas de error personalizadas, logging detallado y respuestas JSON estructuradas.

## Componentes del Sistema

### 1. Exception Handler (`app/Exceptions/Handler.php`)
- Maneja todas las excepciones de la aplicación
- Diferencia entre requests web y API
- Logging automático de errores
- Respuestas consistentes

### 2. Páginas de Error Web (`resources/views/errors/`)
- **403.blade.php** - Acceso no autorizado
- **404.blade.php** - Página no encontrada
- **405.blade.php** - Método no permitido
- **419.blade.php** - Token CSRF expirado
- **429.blade.php** - Demasiadas solicitudes
- **500.blade.php** - Error interno del servidor
- **503.blade.php** - Servicio no disponible
- **layout.blade.php** - Layout compartido para todas las páginas de error

### 3. API Error Controller (`app/Http/Controllers/Api/ErrorController.php`)
- Maneja errores específicos de API
- Respuestas JSON consistentes
- Métodos para cada tipo de error

### 4. Middleware API (`app/Http/Middleware/ApiErrorHandler.php`)
- Intercepta errores en rutas API
- Convierte respuestas a formato JSON estándar
- Añade información de debug en desarrollo

### 5. Trait de Respuestas API (`app/Traits/ApiResponseTrait.php`)
- Métodos helper para respuestas consistentes
- Manejo de éxito y errores
- Información de debug automática

### 6. Servicio de Logging (`app/Services/ErrorLogService.php`)
- Logging detallado de errores
- Contexto de request y usuario
- Eventos de seguridad

## Tipos de Errores Manejados

### Errores Web
- **404** - Página no encontrada
- **403** - Acceso prohibido
- **419** - Token CSRF expirado
- **429** - Demasiadas solicitudes
- **500** - Error interno del servidor
- **503** - Servicio no disponible
- **405** - Método no permitido

### Errores API
- **400** - Solicitud incorrecta
- **401** - No autorizado
- **403** - Acceso prohibido
- **404** - Recurso no encontrado
- **405** - Método no permitido
- **419** - Token CSRF inválido
- **422** - Errores de validación
- **429** - Demasiadas solicitudes
- **500** - Error interno del servidor
- **503** - Servicio no disponible

## Estructura de Respuestas API

### Respuesta de Éxito
```json
{
    "success": true,
    "message": "Operación exitosa",
    "status_code": 200,
    "data": { ... }
}
```

### Respuesta de Error
```json
{
    "error": true,
    "message": "Descripción del error",
    "status_code": 400,
    "errors": { ... }, // Solo para errores de validación
    "debug": { ... }   // Solo en desarrollo
}
```

## Configuración

### 1. Middleware Registrado
El middleware `ApiErrorHandler` está registrado en el grupo `api` en `app/Http/Kernel.php`:

```php
'api' => [
    \Illuminate\Routing\Middleware\ThrottleRequests::class.':api',
    \Illuminate\Routing\Middleware\SubstituteBindings::class,
    \App\Http\Middleware\ApiErrorHandler::class,
],
```

### 2. Rutas de Fallback
Las rutas API tienen un fallback para manejar endpoints no encontrados:

```php
Route::fallback([ErrorController::class, 'notFound']);
```

## Logging de Errores

### Información Registrada
- Tipo de excepción
- Mensaje de error
- Archivo y línea
- Stack trace
- Información del request (URL, método, IP, user agent)
- ID del usuario (si está autenticado)
- Datos del request (excluyendo información sensible)

### Eventos de Seguridad
- Rate limiting
- CSRF token mismatch
- Accesos no autorizados
- Intentos de acceso a recursos prohibidos

## Uso del Sistema

### En Controladores Web
```php
// Los errores se manejan automáticamente
throw new NotFoundHttpException('Recurso no encontrado');
```

### En Controladores API
```php
use App\Traits\ApiResponseTrait;

class MiController extends Controller
{
    use ApiResponseTrait;
    
    public function miMetodo()
    {
        // Respuesta de éxito
        return $this->successResponse($data, 'Operación exitosa');
        
        // Respuesta de error
        return $this->errorResponse('Error en la operación', 400);
        
        // Error de validación
        return $this->validationErrorResponse($validator->errors());
    }
}
```

### Logging Manual
```php
use App\Services\ErrorLogService;

// Log de error general
ErrorLogService::logError($exception, $request);

// Log de evento de seguridad
ErrorLogService::logSecurityError('unauthorized_access', $request);

// Log de rate limiting
ErrorLogService::logRateLimit($request);
```

## Testing del Sistema

### Rutas de Prueba (Solo en desarrollo)
- **Web**: `/test-errors/` - Página de pruebas
- **API**: `/api/test-error?type=404` - Pruebas de API

### Tipos de Prueba Disponibles
- Todos los códigos de error HTTP
- Validación de formularios
- Respuestas API
- Páginas de error web

## Personalización

### Añadir Nuevos Tipos de Error

1. **Crear página de error web**:
```blade
@extends('errors.layout')
@section('code', '418')
@section('title', 'I\'m a teapot')
@section('header-message')
    I'm a teapot <span class="sparkle">🫖</span>
@endsection
@section('content')
    <p>Este servidor es una tetera, no puede preparar café.</p>
@endsection
```

2. **Añadir método en ErrorController**:
```php
public function teapot(Request $request): JsonResponse
{
    return $this->errorResponse('I\'m a teapot', 418);
}
```

3. **Actualizar Exception Handler**:
```php
if ($e instanceof TeapotException) {
    return $this->errorResponse('I\'m a teapot', 418);
}
```

## Mejores Prácticas

1. **Siempre usar el ApiResponseTrait** en controladores API
2. **No exponer información sensible** en respuestas de error
3. **Usar logging apropiado** para diferentes tipos de errores
4. **Mantener mensajes de error consistentes** y en español
5. **Probar todos los tipos de error** antes de desplegar
6. **Monitorear logs de error** regularmente

## Archivos Importantes

```
app/
├── Exceptions/Handler.php
├── Http/
│   ├── Controllers/Api/ErrorController.php
│   ├── Controllers/ErrorTestController.php
│   └── Middleware/ApiErrorHandler.php
├── Services/ErrorLogService.php
└── Traits/ApiResponseTrait.php

resources/views/errors/
├── layout.blade.php
├── 403.blade.php
├── 404.blade.php
├── 405.blade.php
├── 419.blade.php
├── 429.blade.php
├── 500.blade.php
├── 503.blade.php
└── test.blade.php

routes/
├── api.php (fallback route)
└── web.php (test routes)
```

## Monitoreo y Mantenimiento

1. **Revisar logs regularmente** en `storage/logs/laravel.log`
2. **Monitorear errores 500** para problemas del servidor
3. **Analizar patrones de errores 404** para URLs rotas
4. **Vigilar eventos de seguridad** (403, 419, 429)
5. **Actualizar mensajes de error** según feedback de usuarios

Este sistema proporciona una base sólida para el manejo de errores que puede expandirse según las necesidades específicas de la aplicación.
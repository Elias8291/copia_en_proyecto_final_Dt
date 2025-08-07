# Implementación AJAX para Revisión Digital

## Descripción

La funcionalidad de carga de estados y comentarios de secciones evaluadas anteriormente ahora utiliza AJAX para mejorar el rendimiento y permitir actualizaciones dinámicas.

## Arquitectura

### Frontend (JavaScript)

**Archivo**: `public/js/revision/cargar-estados.js`

#### Clase `RevisionDigitalEstados`

```javascript
class RevisionDigitalEstados {
    constructor(tramiteId) {
        this.tramiteId = tramiteId;
        this.seccionesEvaluadas = {};
        this.revisionesAnteriores = {};
        this.init();
    }
}
```

#### Métodos Principales

- **`init()`**: Inicializa la carga de datos
- **`cargarDatosRevision()`**: Realiza la petición AJAX al servidor
- **`actualizarEstadoSeccion()`**: Actualiza la UI de una sección
- **`mostrarMensajeSeccionesEvaluadas()`**: Muestra mensajes informativos
- **`mostrarInformacionRevisionesAnteriores()`**: Muestra información de revisiones previas
- **`recargarDatos()`**: Permite recargar datos manualmente

### Backend (Laravel)

#### Ruta API

```php
// routes/api.php
Route::prefix('revisiones')->group(function () {
    Route::get('/{tramite}/estados', [RevisionController::class, 'obtenerEstadosRevision']);
});
```

#### Controlador

```php
// app/Http/Controllers/RevisionController.php
public function obtenerEstadosRevision(int $tramiteId)
{
    // Retorna JSON con datos de secciones evaluadas y revisiones anteriores
}
```

#### Servicio

```php
// app/Services/Revisiones/RevisionDigitalService.php
public function cargarSeccionesEvaluadas(int $tramiteId): array
public function obtenerInformacionRevisionesAnteriores(int $tramiteId): array
```

## Flujo de Datos

1. **Inicialización**: El JavaScript se inicializa automáticamente cuando el DOM está listo
2. **Obtención del ID**: Se obtiene el ID del trámite desde un meta tag en la vista
3. **Petición AJAX**: Se hace una petición GET a `/api/revisiones/{tramite}/estados`
4. **Procesamiento**: El servidor obtiene los datos usando el `RevisionDigitalService`
5. **Respuesta JSON**: Se retorna un JSON con los datos estructurados
6. **Actualización UI**: El JavaScript actualiza la interfaz con los datos recibidos

## Ventajas de la Implementación AJAX

### Rendimiento
- **Carga diferida**: Los datos se cargan después de que la página esté lista
- **Menos datos iniciales**: La vista inicial es más ligera
- **Caché del navegador**: Las peticiones AJAX pueden ser cacheadas

### Experiencia de Usuario
- **Carga más rápida**: La página se muestra inmediatamente
- **Indicadores de carga**: Se pueden mostrar spinners mientras se cargan los datos
- **Actualizaciones dinámicas**: Se pueden recargar datos sin recargar la página

### Mantenibilidad
- **Separación de responsabilidades**: Frontend y backend están claramente separados
- **Reutilización**: El endpoint puede ser usado por otras partes de la aplicación
- **Testing**: Es más fácil hacer testing de la API y del JavaScript por separado

## Uso

### En la Vista

```html
<!-- ID del trámite para el JavaScript -->
<meta name="tramite-id" content="{{ $tramite->id }}">

<!-- Script de carga de estados -->
<script src="{{ asset('js/revision/cargar-estados.js') }}"></script>
```

### Recarga Manual

```javascript
// Recargar datos manualmente
if (window.revisionDigitalEstados) {
    window.revisionDigitalEstados.recargarDatos();
}
```

### Acceso Global

```javascript
// Acceder a la instancia desde otros scripts
const revisionEstados = window.revisionDigitalEstados;
```

## Manejo de Errores

### Frontend
- **Try-catch**: Todas las operaciones async están envueltas en try-catch
- **Mensajes de error**: Se muestran mensajes de error visuales al usuario
- **Logging**: Los errores se registran en la consola del navegador

### Backend
- **Validación**: Se verifica que el trámite existe
- **Logging**: Los errores se registran en los logs de Laravel
- **Respuestas HTTP**: Se retornan códigos de estado apropiados (404, 500, etc.)

## Seguridad

- **CSRF Protection**: Se incluye el token CSRF en las peticiones AJAX
- **Validación**: Se valida que el usuario tiene acceso al trámite
- **Sanitización**: Los datos se sanitizan antes de ser retornados

## Futuras Mejoras

1. **Caché**: Implementar caché en el servidor para mejorar el rendimiento
2. **WebSockets**: Usar WebSockets para actualizaciones en tiempo real
3. **Paginación**: Implementar paginación para revisiones con muchos datos
4. **Filtros**: Agregar filtros para buscar en revisiones anteriores 
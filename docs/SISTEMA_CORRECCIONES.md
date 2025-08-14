# Sistema de Correcciones Inteligente

## Descripción General

El sistema de correcciones inteligente permite que cuando se envía un trámite para corrección, **solo las secciones que fueron corregidas** cambien a estado "Pendiente", mientras que las secciones no corregidas mantienen su estado anterior. Esto optimiza el proceso de revisión y evita re-revisar secciones que no fueron modificadas.

## Componentes del Sistema

### 1. CorreccionService
**Ubicación**: `app/Services/Tramites/CorreccionService.php`

**Responsabilidades**:
- Identificar qué secciones fueron corregidas
- Actualizar solo las secciones modificadas
- Gestionar estados de secciones y archivos
- Proporcionar información sobre correcciones pendientes

### 2. Integración con Servicios Existentes
El `CorreccionService` reutiliza todos los servicios existentes:
- `DatosGeneralesService`
- `DomicilioService`
- `ActividadesService`
- `AccionistasService`
- `ApoderadoService`
- `ArchivosService`
- `ConstitucionService`
- `ContactoService`

## Funcionamiento

### 1. Identificación de Secciones Corregidas

El sistema identifica automáticamente qué secciones fueron corregidas basándose en los datos enviados en el request:

```php
private function obtenerSeccionesCorregidas(Request $request): array
{
    $seccionesCorregidas = [];

    // Datos Generales
    if ($request->filled(['razon_social', 'rfc', 'tipo_persona'])) {
        $seccionesCorregidas[] = 'datos_generales';
    }

    // Domicilio
    if ($request->filled(['calle', 'numero_exterior', 'codigo_postal'])) {
        $seccionesCorregidas[] = 'domicilio';
    }

    // Actividades
    if ($request->filled('actividades_seleccionadas')) {
        $seccionesCorregidas[] = 'actividades';
    }

    // Y así sucesivamente para cada sección...
}
```

### 2. Actualización Selectiva

Solo se actualizan las secciones que fueron identificadas como corregidas:

```php
private function actualizarSeccionesCorregidas(Tramite $tramite, Request $request, array $seccionesCorregidas): void
{
    foreach ($seccionesCorregidas as $seccion) {
        switch ($seccion) {
            case 'datos_generales':
                $this->datosGeneralesService->actualizar($tramite, $request);
                break;
            case 'domicilio':
                $this->domicilioService->actualizar($tramite, $request);
                break;
            // ... otras secciones
        }
    }
}
```

### 3. Gestión de Estados

#### Estados de Secciones
- **Solo las secciones corregidas** cambian a estado "Pendiente"
- Las secciones no corregidas mantienen su estado anterior
- Se limpian comentarios y revisor de secciones corregidas

#### Estados de Archivos
- **Si se corrigieron archivos**: Todos los archivos del trámite cambian a "Pendiente"
- **Si no se corrigieron archivos**: Los archivos mantienen su estado anterior

## Ventajas del Sistema

### 1. Eficiencia en Revisión
- Los revisores solo necesitan revisar las secciones que fueron corregidas
- Secciones aprobadas previamente no requieren nueva revisión
- Reduce tiempo de procesamiento

### 2. Trazabilidad
- Logs detallados de qué secciones fueron corregidas
- Historial completo de correcciones
- Contador de correcciones por trámite

### 3. Flexibilidad
- Sistema modular y extensible
- Fácil agregar nuevas secciones
- Compatible con el sistema existente

## Uso en el Controlador

### En el método `edit`:
```php
// Obtener secciones que necesitan corrección
$seccionesParaCorregir = $this->correccionService->obtenerSeccionesParaCorreccion($tramite);
$resumenCorrecciones = $this->correccionService->obtenerResumenCorrecciones($tramite);
```

### En el método `update`:
```php
// Procesar corrección usando el servicio inteligente
$tramiteActualizado = $this->correccionService->procesarCorreccion($tramite, $request);
```

## Métodos Disponibles

### CorreccionService

#### `procesarCorreccion(Tramite $tramite, Request $request): Tramite`
Procesa la corrección completa del trámite.

#### `obtenerSeccionesParaCorreccion(Tramite $tramite): array`
Obtiene las secciones que necesitan corrección.

#### `tieneSeccionesParaCorregir(Tramite $tramite): bool`
Verifica si el trámite tiene secciones pendientes de corrección.

#### `obtenerResumenCorrecciones(Tramite $tramite): array`
Obtiene un resumen de las correcciones necesarias.

## Flujo de Trabajo

### 1. Usuario Envía Corrección
```
Usuario → Formulario de Edición → CorreccionService
```

### 2. Identificación Automática
```
CorreccionService → Analiza Request → Identifica Secciones Corregidas
```

### 3. Actualización Selectiva
```
CorreccionService → Actualiza Solo Secciones Corregidas → Actualiza Estados
```

### 4. Resultado
```
Trámite → Solo Secciones Corregidas en "Pendiente" → Listo para Revisión
```

## Logs y Monitoreo

El sistema genera logs detallados para monitoreo:

```php
Log::info('CorreccionService: Secciones identificadas para corrección', [
    'tramite_id' => $tramite->id,
    'secciones_corregidas' => $seccionesCorregidas
]);
```

## Configuración

### Registro en AppServiceProvider
```php
$this->app->singleton(CorreccionService::class, function ($app) {
    return new CorreccionService(
        // Inyección de dependencias de todos los servicios
    );
});
```

## Consideraciones Técnicas

### Transacciones
- Todas las operaciones se ejecutan en una transacción de base de datos
- Garantiza consistencia de datos
- Rollback automático en caso de error

### Performance
- Solo actualiza lo necesario
- Reduce consultas a la base de datos
- Optimiza el proceso de revisión

### Mantenibilidad
- Código modular y reutilizable
- Fácil extensión para nuevas secciones
- Compatible con arquitectura existente

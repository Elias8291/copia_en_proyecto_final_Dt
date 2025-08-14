# Ejemplo de Uso del Sistema de Correcciones

## Escenario de Ejemplo

Supongamos que un trámite tiene las siguientes secciones con sus estados:

- **Datos Generales**: Aprobado ✅
- **Domicilio**: Rechazado ❌
- **Actividades**: Aprobado ✅
- **Constitución**: Aprobado ✅
- **Archivos**: Rechazado ❌

## Caso 1: Usuario Corrige Solo Domicilio

### Datos Enviados en el Request
```php
$request = [
    'calle' => 'Nueva Calle 123',
    'numero_exterior' => '456',
    'codigo_postal' => '12345',
    // ... otros campos de domicilio
];
```

### Proceso del Sistema

1. **Identificación de Secciones Corregidas**:
   ```php
   $seccionesCorregidas = ['domicilio'];
   ```

2. **Actualización Selectiva**:
   - ✅ Solo se actualiza la sección "domicilio"
   - ❌ No se tocan "datos_generales", "actividades", "constitución"

3. **Estados Finales**:
   - **Datos Generales**: Aprobado ✅ (sin cambios)
   - **Domicilio**: Pendiente ⏳ (cambió a pendiente)
   - **Actividades**: Aprobado ✅ (sin cambios)
   - **Constitución**: Aprobado ✅ (sin cambios)
   - **Archivos**: Rechazado ❌ (sin cambios)

## Caso 2: Usuario Corrige Domicilio y Sube Nuevos Archivos

### Datos Enviados en el Request
```php
$request = [
    'calle' => 'Nueva Calle 123',
    'numero_exterior' => '456',
    'codigo_postal' => '12345',
    'documentos_correccion' => [
        // Nuevos archivos subidos
    ]
];
```

### Proceso del Sistema

1. **Identificación de Secciones Corregidas**:
   ```php
   $seccionesCorregidas = ['domicilio', 'archivos'];
   ```

2. **Actualización Selectiva**:
   - ✅ Se actualiza la sección "domicilio"
   - ✅ Se actualizan los archivos

3. **Estados Finales**:
   - **Datos Generales**: Aprobado ✅ (sin cambios)
   - **Domicilio**: Pendiente ⏳ (cambió a pendiente)
   - **Actividades**: Aprobado ✅ (sin cambios)
   - **Constitución**: Aprobado ✅ (sin cambios)
   - **Archivos**: Pendiente ⏳ (cambió a pendiente)

## Caso 3: Usuario Corrige Múltiples Secciones

### Datos Enviados en el Request
```php
$request = [
    'razon_social' => 'Nueva Razón Social',
    'rfc' => 'NUEVO123456789',
    'calle' => 'Nueva Calle 123',
    'actividades_seleccionadas' => '[{"id": 1, "nombre": "Nueva Actividad"}]',
    'documentos_correccion' => [
        // Nuevos archivos
    ]
];
```

### Proceso del Sistema

1. **Identificación de Secciones Corregidas**:
   ```php
   $seccionesCorregidas = ['datos_generales', 'domicilio', 'actividades', 'archivos'];
   ```

2. **Actualización Selectiva**:
   - ✅ Se actualiza "datos_generales"
   - ✅ Se actualiza "domicilio"
   - ✅ Se actualiza "actividades"
   - ✅ Se actualizan los archivos

3. **Estados Finales**:
   - **Datos Generales**: Pendiente ⏳ (cambió a pendiente)
   - **Domicilio**: Pendiente ⏳ (cambió a pendiente)
   - **Actividades**: Pendiente ⏳ (cambió a pendiente)
   - **Constitución**: Aprobado ✅ (sin cambios)
   - **Archivos**: Pendiente ⏳ (cambió a pendiente)

## Logs Generados

### Ejemplo de Logs del Sistema

```php
// Al iniciar la corrección
Log::info('CorreccionService: Iniciando procesamiento de corrección', [
    'tramite_id' => 123,
    'user_id' => 456
]);

// Al identificar secciones
Log::info('CorreccionService: Secciones identificadas para corrección', [
    'tramite_id' => 123,
    'secciones_corregidas' => ['domicilio', 'archivos']
]);

// Al actualizar cada sección
Log::info('CorreccionService: Actualizando sección: domicilio', [
    'tramite_id' => 123
]);

// Al finalizar
Log::info('CorreccionService: Corrección procesada exitosamente', [
    'tramite_id' => 123,
    'secciones_corregidas' => ['domicilio', 'archivos'],
    'correcciones_count' => 2
]);
```

## Ventajas Demostradas

### 1. Eficiencia
- **Antes**: Todas las secciones se marcaban como "Pendiente"
- **Ahora**: Solo las secciones corregidas se marcan como "Pendiente"

### 2. Tiempo de Revisión
- **Antes**: Revisor debía revisar 5 secciones
- **Ahora**: Revisor solo revisa 2 secciones (las corregidas)

### 3. Trazabilidad
- Logs detallados de qué se corrigió
- Historial completo de cambios
- Contador de correcciones

## Código de Ejemplo

### En el Controlador
```php
public function update(Request $request, $tramiteId)
{
    $tramite = Tramite::findOrFail($tramiteId);
    
    // El sistema automáticamente identifica qué se corregió
    $tramiteActualizado = $this->correccionService->procesarCorreccion($tramite, $request);
    
    return redirect()->route('tramites.estado')
        ->with('success', 'Correcciones enviadas exitosamente');
}
```

### Verificación de Estados
```php
// Obtener secciones que necesitan corrección
$seccionesParaCorregir = $this->correccionService->obtenerSeccionesParaCorreccion($tramite);

// Verificar si hay correcciones pendientes
$tieneCorrecciones = $this->correccionService->tieneSeccionesParaCorregir($tramite);

// Obtener resumen
$resumen = $this->correccionService->obtenerResumenCorrecciones($tramite);
```

## Resultado Final

El sistema de correcciones inteligente optimiza significativamente el proceso de revisión:

- **Reduce tiempo de procesamiento**
- **Mejora la experiencia del revisor**
- **Mantiene trazabilidad completa**
- **Es compatible con el sistema existente**
- **Es fácil de mantener y extender**

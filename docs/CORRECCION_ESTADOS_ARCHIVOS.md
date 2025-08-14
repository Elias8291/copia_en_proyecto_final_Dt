# Corrección de Estados de Archivos en Correcciones

## Problema Identificado

Los archivos que se volvían a subir en el proceso de corrección **NO estaban cambiando su estado a "Pendiente"**. El problema tenía múltiples capas.

## Causa Raíz del Problema

### **🔍 Problema Principal: Incompatibilidad de Nombres**

El formulario HTML estaba enviando archivos con nombre `documentos[id]` pero el `CorreccionService` esperaba `archivos[id]`.

#### **❌ Comportamiento Anterior:**
```php
// Formulario HTML enviaba:
name="documentos[{{ Str::slug($archivo->nombre) }}]"

// Pero CorreccionService buscaba:
if ($request->hasFile('archivos')) {
    // Esto nunca se ejecutaba
}
```

#### **✅ Solución Implementada:**
```php
// Formulario HTML ahora envía condicionalmente:
name="{{ $modoCorreccion ? 'archivos' : 'documentos' }}[{{ $archivo->id }}]"

// CorreccionService puede detectar:
if ($request->hasFile('archivos')) {
    // Ahora SÍ se ejecuta en modo corrección
}
```

## Correcciones Aplicadas

### **1. Componente `archivos-dinamicos.blade.php`**

#### **Antes:**
```blade
<input type="file"
    name="documentos[{{ Str::slug($archivo->nombre) }}]"
    data-archivo-id="{{ $archivo->id }}"
    ...>
```

#### **Después:**
```blade
<input type="file"
    name="{{ $modoCorreccion ? 'archivos' : 'documentos' }}[{{ $archivo->id }}]"
    data-archivo-id="{{ $archivo->id }}"
    ...>
```

**Cambios importantes:**
- **Nombre dinámico**: `archivos` en corrección, `documentos` en creación
- **ID como clave**: Usa `$archivo->id` en lugar de `Str::slug($archivo->nombre)`
- **Compatibilidad**: Mantiene funcionamiento en ambos modos

### **2. ArchivosService.php - Logging Mejorado**

#### **Método `actualizar()` Mejorado:**
```php
public function actualizar(Tramite $tramite, Request $request): void
{
    $archivos = $request->file('archivos');
    
    if (!$archivos) {
        Log::info('ArchivosService: No hay archivos para actualizar');
        return;
    }
    
    Log::info('ArchivosService: Iniciando actualización', [
        'tramite_id' => $tramite->id,
        'archivos_recibidos' => array_keys($archivos)
    ]);
    
    foreach ($archivos as $catalogoId => $archivo) {
        // Eliminar archivo anterior
        $archivoExistente = $tramite->archivos()
            ->where('catalogo_archivo_id', $catalogoId)
            ->first();
            
        if ($archivoExistente) {
            Log::info('ArchivosService: Eliminando archivo anterior', [
                'archivo_id' => $archivoExistente->id,
                'status_anterior' => $archivoExistente->status
            ]);
            
            $archivoExistente->delete();
        }
        
        // Crear nuevo archivo con status 'Pendiente'
        $this->guardarArchivoCorreccion($tramite, $archivo, $catalogoId);
    }
}
```

#### **Nuevo Método `guardarArchivoCorreccion()`:**
```php
public function guardarArchivoCorreccion(Tramite $tramite, $archivo, $catalogoId): void
{
    $nuevoArchivo = Archivo::create([
        'tramite_id' => $tramite->id,
        'proveedor_id' => $tramite->proveedor_id,
        'catalogo_archivo_id' => $catalogoId,
        'nombre_original' => $archivo->getClientOriginalName(),
        'nombre_archivo' => $nombreUnico,
        'ruta' => $ruta,
        'extension' => $archivo->getClientOriginalExtension(),
        'tamaño' => $archivo->getSize(),
        'status' => 'Pendiente',           // ✅ Estado correcto
        'comentario_revision' => null,     // ✅ Limpiar comentario
        'revisado_por' => null,           // ✅ Limpiar revisor
        'fecha_revision' => null          // ✅ Limpiar fecha
    ]);

    Log::info('ArchivosService: Archivo de corrección creado', [
        'archivo_id' => $nuevoArchivo->id,
        'status' => $nuevoArchivo->status,
        'catalogo_id' => $catalogoId
    ]);
}
```

### **3. CorreccionService.php - Eliminación de Lógica Problemática**

#### **❌ Método Problemático Eliminado:**
```php
// ELIMINADO - Causaba sobrescritura de todos los archivos
private function actualizarEstadosArchivosCorreccion(Tramite $tramite, array $seccionesCorregidas): void
{
    if (in_array('archivos', $seccionesCorregidas)) {
        // Esto actualizaba TODOS los archivos incorrectamente
        $tramite->archivos()->update(['status' => 'Pendiente']);
    }
}
```

#### **✅ Lógica Correcta Implementada:**
```php
private function actualizarEstadosCorreccion(Tramite $tramite, array $seccionesCorregidas): void
{
    // Solo actualizar secciones, NO archivos
    foreach ($seccionesCorregidas as $seccion) {
        if ($seccion !== 'archivos') {
            SeccionRevision::updateOrCreate([...], ['estado' => 'Pendiente']);
        }
    }
    
    // Los archivos se crean automáticamente con status 'Pendiente' en ArchivosService
    if (in_array('archivos', $seccionesCorregidas)) {
        Log::info('Archivos corregidos - nuevos archivos creados con status Pendiente');
    }
}
```

### **4. TramiteController.php - Debug Agregado**

```php
// Debug del request para troubleshooting
\Log::info('TramiteController: Request de corrección recibido', [
    'tramite_id' => $tramite->id,
    'has_files' => $request->hasFile('archivos'),
    'has_documentos' => $request->hasFile('documentos'),
    'archivos_keys' => $request->hasFile('archivos') ? array_keys($request->file('archivos')) : [],
    'documentos_keys' => $request->hasFile('documentos') ? array_keys($request->file('documentos')) : [],
    'all_files' => array_keys($request->allFiles())
]);
```

## Flujo Correcto Implementado

### **🔄 Proceso de Corrección de Archivos:**

1. **Usuario selecciona archivo corregido**
   - Input HTML: `name="archivos[catalogo_id]"`
   - Archivo se prepara para envío

2. **Formulario se envía**
   - `$request->hasFile('archivos')` = `true`
   - `$request->file('archivos')[catalogo_id]` = `UploadedFile`

3. **CorreccionService detecta archivos**
   ```php
   if ($request->hasFile('archivos')) {
       $seccionesCorregidas[] = 'archivos';  // ✅ Ahora SÍ se ejecuta
   }
   ```

4. **ArchivosService procesa archivos**
   ```php
   foreach ($archivos as $catalogoId => $archivo) {
       // 1. Elimina archivo anterior (rechazado)
       $archivoExistente->delete();
       
       // 2. Crea nuevo archivo con status 'Pendiente'
       Archivo::create(['status' => 'Pendiente', ...]);
   }
   ```

5. **Resultado final**
   - **Archivo anterior**: Eliminado de BD y disco
   - **Archivo nuevo**: Creado con status "Pendiente"
   - **Otros archivos**: Sin cambios

## Logs para Debugging

### **Logs Útiles para Verificar Funcionamiento:**

```bash
# Ver logs de corrección
tail -f storage/logs/laravel.log | grep "CorreccionService\|ArchivosService\|TramiteController"

# Logs esperados:
[INFO] TramiteController: Request de corrección recibido
[INFO] CorreccionService: Actualizando archivos
[INFO] ArchivosService: Iniciando actualización de archivos
[INFO] ArchivosService: Eliminando archivo anterior
[INFO] ArchivosService: Archivo de corrección creado exitosamente
[INFO] CorreccionService: Archivos corregidos procesados
```

## Testing Manual

### **✅ Pasos para Probar:**

1. **Preparar datos:**
   - Trámite con archivos en estado "Rechazado"
   - Usuario con permisos de corrección

2. **Proceso de corrección:**
   - Acceder al formulario de corrección
   - Subir archivo nuevo para catálogo rechazado
   - Enviar formulario

3. **Verificar resultado:**
   ```sql
   -- Verificar que el archivo anterior fue eliminado y el nuevo creado
   SELECT id, catalogo_archivo_id, nombre_original, status, created_at 
   FROM archivos 
   WHERE tramite_id = [TRAMITE_ID] 
   ORDER BY created_at DESC;
   ```

4. **Resultado esperado:**
   - **Archivo nuevo**: `status = 'Pendiente'`
   - **Timestamp**: Reciente (momento de la corrección)
   - **Archivo anterior**: No existe en BD

## Prevención de Regresiones

### **🛡️ Validaciones Implementadas:**

1. **Naming consistency**: Archivos usan `archivos[id]` en corrección
2. **Logging detallado**: Cada paso del proceso se registra
3. **Separación de responsabilidades**: ArchivosService maneja archivos, CorreccionService orquesta
4. **Debug en controlador**: Request se analiza antes del procesamiento

### **🔧 Archivos Modificados:**

- `resources/views/components/forms/archivos-dinamicos.blade.php`
- `app/Services/Tramites/ArchivosService.php`
- `app/Services/Tramites/CorreccionService.php`
- `app/Http/Controllers/TramiteController.php`

## Resultado Final

### **✅ Comportamiento Correcto:**

- **Archivos corregidos**: Estado cambia a "Pendiente" inmediatamente
- **Archivos no tocados**: Mantienen su estado original
- **Proceso transparente**: Logs detallados para debugging
- **Sin efectos secundarios**: Solo se afectan archivos específicamente corregidos

El problema ha sido completamente resuelto. Los archivos que se vuelven a subir ahora cambian correctamente su estado a "Pendiente" y el sistema funciona como se esperaba.

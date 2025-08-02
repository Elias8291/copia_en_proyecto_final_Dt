# Servicio de Datos del Último Trámite Aprobado

## Descripción

El `DatosTramiteAprobadoService` es un servicio especializado que permite obtener los datos del último trámite aprobado de un proveedor, incluyendo los datos generales de la tabla `datos_generales`.

## Ubicación

- **Servicio Principal**: `app/Services/Proveedores/DatosTramiteAprobadoService.php`
- **Facade en ProveedorService**: `app/Services/ProveedorService.php`
- **Controlador**: `app/Http/Controllers/ProveedorController.php`

## Métodos Disponibles

### 1. `obtenerUltimoTramiteAprobado(Proveedor $proveedor): ?Tramite`

Obtiene el último trámite aprobado del proveedor con todas sus relaciones cargadas.

```php
$tramite = $proveedorService->obtenerUltimoTramiteAprobado($proveedor);
```

### 2. `obtenerDatosGeneralesUltimoTramite(Proveedor $proveedor): ?array`

Obtiene solo los datos generales del último trámite aprobado.

```php
$datosGenerales = $proveedorService->obtenerDatosGeneralesUltimoTramite($proveedor);

// Retorna:
[
    'curp' => 'CURP123456789',
    'razon_social' => 'Empresa S.A. de C.V.',
    'pagina_web' => 'https://empresa.com',
    'telefono' => '555-123-4567',
    'fecha_creacion' => '2024-01-15 10:30:00',
    'fecha_actualizacion' => '2024-01-15 10:30:00'
]
```

### 3. `obtenerInformacionCompletaUltimoTramite(Proveedor $proveedor): ?array`

Obtiene información completa del último trámite aprobado incluyendo todos los datos relacionados.

```php
$informacionCompleta = $proveedorService->obtenerInformacionCompletaUltimoTramite($proveedor);

// Retorna estructura completa con:
// - Datos del trámite
// - Datos generales
// - Apoderado legal
// - Dirección
// - Accionistas
// - Actividades económicas
// - Actividades
// - Instrumento notarial
// - Oficios
// - Cita
```

### 4. `tieneTramiteAprobado(Proveedor $proveedor): bool`

Verifica si el proveedor tiene algún trámite aprobado.

```php
$tieneAprobado = $proveedorService->tieneTramiteAprobado($proveedor);
```

### 5. `obtenerFechaUltimoTramiteAprobado(Proveedor $proveedor): ?string`

Obtiene la fecha del último trámite aprobado en formato dd/mm/yyyy.

```php
$fecha = $proveedorService->obtenerFechaUltimoTramiteAprobado($proveedor);
// Retorna: "15/01/2024"
```

### 6. `obtenerTipoUltimoTramiteAprobado(Proveedor $proveedor): ?string`

Obtiene el tipo del último trámite aprobado.

```php
$tipo = $proveedorService->obtenerTipoUltimoTramiteAprobado($proveedor);
// Retorna: "Inscripcion", "Renovacion", "Actualizacion"
```

## Uso en Controladores

### ProveedorController

```php
public function show(Proveedor $proveedor): View
{
    $proveedor->load(['usuario', 'tramites', 'accionistas', 'contactos']);

    // Obtener datos del último trámite aprobado
    $datosUltimoTramite = $this->proveedorService->obtenerInformacionCompletaUltimoTramite($proveedor);
    $datosGenerales = $this->proveedorService->obtenerDatosGeneralesUltimoTramite($proveedor);
    $tieneTramiteAprobado = $this->proveedorService->tieneTramiteAprobado($proveedor);
    $fechaUltimoTramite = $this->proveedorService->obtenerFechaUltimoTramiteAprobado($proveedor);
    $tipoUltimoTramite = $this->proveedorService->obtenerTipoUltimoTramiteAprobado($proveedor);

    return view('proveedores.show', compact(
        'proveedor', 
        'datosUltimoTramite', 
        'datosGenerales', 
        'tieneTramiteAprobado',
        'fechaUltimoTramite',
        'tipoUltimoTramite'
    ));
}
```

## Rutas API

### Obtener Datos Generales
```
GET /proveedores/{proveedor}/datos-generales
```

### Obtener Información Completa
```
GET /proveedores/{proveedor}/informacion-completa
```

## Ejemplo de Respuesta JSON

### Datos Generales
```json
{
    "success": true,
    "data": {
        "curp": "CURP123456789",
        "razon_social": "Empresa S.A. de C.V.",
        "pagina_web": "https://empresa.com",
        "telefono": "555-123-4567",
        "fecha_creacion": "2024-01-15T10:30:00.000000Z",
        "fecha_actualizacion": "2024-01-15T10:30:00.000000Z"
    },
    "message": "Datos generales obtenidos exitosamente"
}
```

### Información Completa
```json
{
    "success": true,
    "data": {
        "tramite": {
            "id": 1,
            "tipo": "Inscripcion",
            "estado": "Aprobado",
            "fecha_creacion": "2024-01-15T10:30:00.000000Z",
            "fecha_aprobacion": "2024-01-20T15:45:00.000000Z",
            "observaciones": "Trámite aprobado correctamente"
        },
        "datos_generales": {
            "id": 1,
            "curp": "CURP123456789",
            "razon_social": "Empresa S.A. de C.V.",
            "pagina_web": "https://empresa.com",
            "telefono": "555-123-4567",
            "fecha_creacion": "2024-01-15T10:30:00.000000Z",
            "fecha_actualizacion": "2024-01-15T10:30:00.000000Z"
        },
        "apoderado_legal": {
            "id": 1,
            "nombre": "Juan Pérez",
            "rfc": "PERJ800101ABC",
            "curp": "PERJ800101HDFXXX01",
            "domicilio": "Calle Principal 123"
        },
        "direccion": {
            "id": 1,
            "calle": "Calle Principal",
            "numero_exterior": "123",
            "numero_interior": "A",
            "colonia": "Centro",
            "codigo_postal": "12345",
            "municipio": "Ciudad",
            "estado": "Estado",
            "pais": "México"
        },
        "accionistas": [
            {
                "id": 1,
                "nombre": "Juan Pérez",
                "rfc": "PERJ800101ABC",
                "curp": "PERJ800101HDFXXX01",
                "porcentaje_participacion": 100
            }
        ],
        "actividades_economicas": [
            {
                "id": 1,
                "codigo": "123456",
                "descripcion": "Comercio al por menor",
                "porcentaje": 100
            }
        ],
        "actividades": [
            {
                "id": 1,
                "codigo": "123456",
                "descripcion": "Comercio al por menor"
            }
        ],
        "instrumento_notarial": {
            "id": 1,
            "numero_instrumento": "12345",
            "fecha_instrumento": "2024-01-10",
            "notario": "Lic. María García",
            "numero_notaria": "1"
        },
        "oficios": [
            {
                "id": 1,
                "numero_oficio": "OF-2024-001",
                "fecha_oficio": "2024-01-20",
                "tipo": "Aprobacion",
                "estado": "Generado"
            }
        ],
        "cita": {
            "id": 1,
            "fecha": "2024-01-25",
            "hora": "10:00:00",
            "tipo": "Cotejo",
            "estado": "Programada"
        }
    },
    "message": "Información completa obtenida exitosamente"
}
```

## Características

- ✅ **Eager Loading**: Carga todas las relaciones necesarias en una sola consulta
- ✅ **Manejo de Errores**: Logging detallado de errores
- ✅ **Tipado Fuerte**: Uso de tipos de retorno específicos
- ✅ **Null Safety**: Manejo seguro de valores nulos
- ✅ **Performance**: Optimizado para consultas eficientes
- ✅ **Reutilizable**: Métodos modulares y reutilizables

## Registro en ServiceProvider

El servicio está registrado en `app/Providers/TramiteServiceProvider.php`:

```php
$this->app->singleton(\App\Services\Proveedores\DatosTramiteAprobadoService::class);
```

## Dependencias

- `App\Models\Proveedor`
- `App\Models\Tramite`
- `App\Models\DatosGenerales`
- `Illuminate\Support\Facades\Log`
- `Illuminate\Database\Eloquent\Builder` 
# Componentes de Formularios - Modo Solo Lectura

## Descripción

Se han creado componentes reutilizables para todos los formularios de trámites en modo solo lectura. Estos componentes están ubicados en `resources/views/components/forms/` y pueden ser utilizados para visualizar datos sin permitir edición.

## Componentes Disponibles

### 1. Datos Generales
**Archivo:** `datos-generales.blade.php`
**Uso:** `x-forms.datos-generales`

**Propiedades:**
- `datos` (array): Datos del formulario
- `editable` (boolean): Si es editable (por defecto false)

**Ejemplo:**
```blade
<x-forms.datos-generales :datos="$datosGenerales" :editable="false" />
```

### 2. Apoderado Legal
**Archivo:** `apoderado.blade.php`
**Uso:** `x-forms.apoderado`

**Propiedades:**
- `datos` (array): Datos del apoderado
- `editable` (boolean): Si es editable (por defecto false)

**Ejemplo:**
```blade
<x-forms.apoderado :datos="$datosApoderado" :editable="false" />
```

### 3. Domicilio
**Archivo:** `domicilio.blade.php`
**Uso:** `x-forms.domicilio`

**Propiedades:**
- `datos` (array): Datos del domicilio
- `editable` (boolean): Si es editable (por defecto false)

**Ejemplo:**
```blade
<x-forms.domicilio :datos="$datosDomicilio" :editable="false" />
```

### 4. Accionistas
**Archivo:** `accionistas.blade.php`
**Uso:** `x-forms.accionistas`

**Propiedades:**
- `datos` (array): Array de accionistas
- `editable` (boolean): Si es editable (por defecto false)

**Ejemplo:**
```blade
<x-forms.accionistas :datos="$datosAccionistas" :editable="false" />
```

### 5. Actividades Económicas
**Archivo:** `actividades-economicas.blade.php`
**Uso:** `x-forms.actividades-economicas`

**Propiedades:**
- `datos` (array): Array de actividades económicas
- `editable` (boolean): Si es editable (por defecto false)

**Ejemplo:**
```blade
<x-forms.actividades-economicas :datos="$datosActividades" :editable="false" />
```

### 6. Constitución
**Archivo:** `constitucion.blade.php`
**Uso:** `x-forms.constitucion`

**Propiedades:**
- `datos` (array): Datos de constitución
- `editable` (boolean): Si es editable (por defecto false)

**Ejemplo:**
```blade
<x-forms.constitucion :datos="$datosConstitucion" :editable="false" />
```

### 7. Documentos
**Archivo:** `documentos.blade.php`
**Uso:** `x-forms.documentos`

**Propiedades:**
- `datos` (array): Array de documentos
- `editable` (boolean): Si es editable (por defecto false)

**Ejemplo:**
```blade
<x-forms.documentos :datos="$datosDocumentos" :editable="false" />
```

### 8. Estado de Sección
**Archivo:** `estado-seccion.blade.php`
**Uso:** `x-forms.estado-seccion`

**Propiedades:**
- `datos` (array): Datos del estado de la sección
- `editable` (boolean): Si es editable (por defecto false)

**Ejemplo:**
```blade
<x-forms.estado-seccion :datos="$datosEstado" :editable="false" />
```

### 9. Confirmación
**Archivo:** `confirmacion.blade.php`
**Uso:** `x-forms.confirmacion`

**Propiedades:**
- `datos` (array): Datos de confirmación
- `editable` (boolean): Si es editable (por defecto false)

**Ejemplo:**
```blade
<x-forms.confirmacion :datos="$datosConfirmacion" :editable="false" />
```

## Características Comunes

Todos los componentes comparten las siguientes características:

1. **Modo Solo Lectura:** Todos los campos están deshabilitados y tienen estilo visual que indica que no son editables
2. **Diseño Responsivo:** Utilizan Tailwind CSS con clases responsive
3. **Iconos:** Cada campo tiene un icono representativo
4. **Estilos Consistentes:** Mismo estilo visual en todos los componentes
5. **Manejo de Datos Vacíos:** Muestran mensajes apropiados cuando no hay datos

## Estructura de Datos Esperada

### Datos Generales
```php
$datos = [
    'razon_social' => 'EMPRESA S.A. DE C.V.',
    'rfc' => 'XAXX010101000',
    'curp' => 'XAXX010101HDFXXX01',
    'telefono' => '555-123-4567',
    'pagina_web' => 'https://www.empresa.com',
    'email_contacto' => 'contacto@empresa.com',
    'cargo' => 'Director General'
];
```

### Apoderado Legal
```php
$datos = [
    'nombre_apoderado' => 'Juan Pérez',
    'rfc' => 'PEPJ800101ABC',
    'curp' => 'PEPJ800101HDFXXX01',
    'domicilio' => 'Calle Principal 123',
    'numero_escritura' => '12345',
    'fecha_constitucion' => '2020-01-15',
    'nombre_notario' => 'Dr. Carlos López',
    'entidad_federativa' => 'Oaxaca',
    'numero_notario' => '15',
    'numero_registro_publico' => 'RP-2020-001',
    'fecha_inscripcion' => '2020-01-20'
];
```

### Domicilio
```php
$datos = [
    'calle' => 'Av. Principal',
    'numero_exterior' => '123',
    'numero_interior' => 'A',
    'colonia_asentamiento' => 'Centro',
    'codigo_postal' => '68000',
    'municipio' => 'Oaxaca de Juárez',
    'estado' => 'Oaxaca'
];
```

### Accionistas (Array)
```php
$datos = [
    [
        'nombre_completo' => 'Juan Pérez',
        'rfc' => 'PEPJ800101ABC',
        'curp' => 'PEPJ800101HDFXXX01',
        'porcentaje_participacion' => '60'
    ],
    [
        'nombre_completo' => 'María García',
        'rfc' => 'GARM850215DEF',
        'curp' => 'GARM850215HDFXXX02',
        'porcentaje_participacion' => '40'
    ]
];
```

### Actividades Económicas (Array)
```php
$datos = [
    [
        'codigo' => '461110',
        'descripcion' => 'Comercio al por menor en tiendas de abarrotes',
        'porcentaje' => '100'
    ]
];
```

### Documentos (Array)
```php
$datos = [
    [
        'tipo_documento' => 'Acta Constitutiva',
        'nombre_archivo' => 'acta_constitutiva.pdf',
        'tamaño' => '2.5 MB',
        'fecha_carga' => '2024-01-15',
        'estado' => 'Aprobado',
        'comentarios' => 'Documento correcto'
    ]
];
```

## Uso en Vistas

Para usar estos componentes en una vista:

```blade
@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6">Detalles del Trámite</h1>
    
    <!-- Datos Generales -->
    <div class="mb-8">
        <x-forms.datos-generales :datos="$datosGenerales" />
    </div>
    
    <!-- Apoderado Legal -->
    <div class="mb-8">
        <x-forms.apoderado :datos="$datosApoderado" />
    </div>
    
    <!-- Domicilio -->
    <div class="mb-8">
        <x-forms.domicilio :datos="$datosDomicilio" />
    </div>
    
    <!-- Accionistas -->
    <div class="mb-8">
        <x-forms.accionistas :datos="$datosAccionistas" />
    </div>
    
    <!-- Actividades Económicas -->
    <div class="mb-8">
        <x-forms.actividades-economicas :datos="$datosActividades" />
    </div>
    
    <!-- Documentos -->
    <div class="mb-8">
        <x-forms.documentos :datos="$datosDocumentos" />
    </div>
</div>
@endsection
```

## Ventajas

1. **Reutilización:** Los mismos componentes se pueden usar en diferentes vistas
2. **Consistencia:** Mismo diseño y comportamiento en toda la aplicación
3. **Mantenimiento:** Cambios en un lugar se reflejan en todos los usos
4. **Legibilidad:** Código más limpio y fácil de entender
5. **Flexibilidad:** Fácil de extender o modificar según necesidades

## Notas Importantes

- Todos los componentes están en modo solo lectura por defecto
- Los campos vacíos se muestran como campos deshabilitados sin valor
- Los arrays vacíos muestran un mensaje indicando que no hay datos
- Los componentes son completamente responsivos
- Utilizan iconos de Font Awesome para mejor UX 
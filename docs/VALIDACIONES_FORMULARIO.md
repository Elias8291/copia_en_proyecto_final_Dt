# Guía de Validaciones de Formularios

Este documento explica cómo usar las validaciones del `FormValidator` y `SectionValidator` en los formularios HTML para que coincidan con las validaciones de los servicios PHP.

## Arquitectura Simplificada

### FormValidator
- **Propósito**: Validaciones de campos individuales
- **Características**: Reutilizable, configurable, extensible
- **Métodos principales**: `validateField()`, `validateSection()`, `parseValidationRules()`

### SectionValidator
- **Propósito**: Control de secciones y navegación
- **Características**: Detección automática de tipos, validaciones específicas
- **Métodos principales**: `validateCurrentSection()`, `nextStep()`, `previousStep()`

## Validaciones Disponibles

### Validaciones Básicas

| Validación | Descripción | Uso en HTML |
|------------|-------------|-------------|
| `required` | Campo obligatorio | `data-validate="required"` |
| `minLength:X` | Longitud mínima | `data-validate="required\|minLength:3"` |
| `maxLength:X` | Longitud máxima | `data-validate="required\|maxLength:255"` |
| `email` | Formato de email | `data-validate="required\|email"` |
| `phone` | Teléfono (10 dígitos) | `data-validate="required\|phone"` |
| `numeric` | Número válido | `data-validate="required\|numeric"` |
| `percentage` | Porcentaje (0-100) | `data-validate="required\|percentage"` |
| `date` | Fecha válida | `data-validate="required\|date"` |
| `datePast` | Fecha en el pasado | `data-validate="required\|date\|datePast"` |
| `dateFuture` | Fecha en el futuro | `data-validate="required\|date\|dateFuture"` |

### Validaciones Específicas

| Validación | Descripción | Uso en HTML |
|------------|-------------|-------------|
| `rfc` | RFC (persona física o moral) | `data-validate="required\|rfc"` |
| `rfcPersonaFisica` | RFC persona física (13 chars) | `data-validate="required\|rfcPersonaFisica"` |
| `rfcPersonaMoral` | RFC persona moral (12 chars) | `data-validate="required\|rfcPersonaMoral"` |
| `curp` | CURP (18 caracteres) | `data-validate="required\|curp"` |
| `url` | URL válida | `data-validate="url"` |
| `codigoPostal` | Código postal (5 dígitos) | `data-validate="required\|codigoPostal"` |
| `entreCalles` | Entre calles (mín 3 chars) | `data-validate="required\|entreCalles"` |
| `escritura` | Número de escritura (mín 3 chars) | `data-validate="required\|escritura"` |
| `notario` | Nombre de notario (mín 3 chars) | `data-validate="required\|notario"` |
| `entidadFederativa` | Entidad federativa | `data-validate="required\|entidadFederativa"` |
| `numeroNotario` | Número de notario (mín 1 char) | `data-validate="required\|numeroNotario"` |
| `numeroRegistro` | Número de registro (mín 3 chars) | `data-validate="required\|numeroRegistro"` |
| `fechaInscripcion` | Fecha de inscripción | `data-validate="required\|date\|fechaInscripcion"` |

### Validaciones de Archivos

| Validación | Descripción | Uso en HTML |
|------------|-------------|-------------|
| `file` | Archivo seleccionado | `data-validate="required\|file"` |
| `fileSize:X` | Tamaño máximo en MB | `data-validate="required\|file\|fileSize:10"` |

## Ejemplos por Sección

### Datos Generales

```html
<!-- Razón Social -->
<input type="text" 
       name="razon_social" 
       data-validate="required|minLength:3|maxLength:255"
       class="form-input">

<!-- RFC -->
<input type="text" 
       name="rfc" 
       data-validate="required|rfc"
       class="form-input">

<!-- Email de Contacto -->
<input type="email" 
       name="email_contacto" 
       data-validate="required|email|maxLength:255"
       class="form-input">

<!-- Teléfono -->
<input type="tel" 
       name="telefono" 
       data-validate="required|phone|maxLength:20"
       class="form-input">

<!-- CURP -->
<input type="text" 
       name="curp" 
       data-validate="curp"
       class="form-input">

<!-- Página Web -->
<input type="url" 
       name="pagina_web" 
       data-validate="url|maxLength:255"
       class="form-input">
```

### Dirección

```html
<!-- Código Postal -->
<input type="text" 
       name="codigo_postal" 
       data-validate="required|codigoPostal"
       class="form-input">

<!-- Calle -->
<input type="text" 
       name="calle" 
       data-validate="required|minLength:5|maxLength:255"
       class="form-input">

<!-- Número Exterior -->
<input type="text" 
       name="numero_exterior" 
       data-validate="required|maxLength:20"
       class="form-input">

<!-- Entre Calles -->
<input type="text" 
       name="entre_calles" 
       data-validate="required|entreCalles|maxLength:200"
       class="form-input">
```

### Datos Constitutivos

```html
<!-- Número de Escritura -->
<input type="text" 
       name="numero_escritura" 
       data-validate="required|escritura|maxLength:255"
       class="form-input">

<!-- Fecha de Constitución -->
<input type="date" 
       name="fecha_constitucion" 
       data-validate="required|date|datePast"
       class="form-input">

<!-- Nombre del Notario -->
<input type="text" 
       name="notario_nombre" 
       data-validate="required|notario|maxLength:255"
       class="form-input">

<!-- Entidad Federativa -->
<input type="text" 
       name="entidad_federativa" 
       data-validate="required|entidadFederativa|maxLength:255"
       class="form-input">

<!-- Número de Notario -->
<input type="text" 
       name="notario_numero" 
       data-validate="required|numeroNotario|maxLength:10"
       class="form-input">

<!-- Número de Registro -->
<input type="text" 
       name="numero_registro" 
       data-validate="required|numeroRegistro|maxLength:255"
       class="form-input">

<!-- Fecha de Inscripción -->
<input type="date" 
       name="fecha_inscripcion" 
       data-validate="required|date|fechaInscripcion"
       class="form-input">
```

### Apoderado Legal

```html
<!-- Nombre del Apoderado -->
<input type="text" 
       name="apoderado_nombre" 
       data-validate="required|minLength:3|maxLength:255"
       class="form-input">

<!-- RFC del Apoderado (Persona Física) -->
<input type="text" 
       name="apoderado_rfc" 
       data-validate="required|rfcPersonaFisica"
       class="form-input">

<!-- Número de Escritura del Poder -->
<input type="text" 
       name="poder_numero_escritura" 
       data-validate="required|escritura|maxLength:255"
       class="form-input">

<!-- Fecha de Constitución del Poder -->
<input type="date" 
       name="poder_fecha_constitucion" 
       data-validate="required|date|datePast"
       class="form-input">

<!-- Nombre del Notario del Poder -->
<input type="text" 
       name="poder_notario_nombre" 
       data-validate="required|notario|maxLength:255"
       class="form-input">

<!-- Entidad Federativa del Poder -->
<input type="text" 
       name="poder_entidad_federativa" 
       data-validate="required|entidadFederativa|maxLength:255"
       class="form-input">

<!-- Número de Notario del Poder -->
<input type="text" 
       name="poder_notario_numero" 
       data-validate="required|numeroNotario|maxLength:10"
       class="form-input">

<!-- Número de Registro del Poder -->
<input type="text" 
       name="poder_numero_registro" 
       data-validate="required|numeroRegistro|maxLength:255"
       class="form-input">
```

### Documentos

```html
<!-- Input de archivo individual -->
<input type="file" 
       name="documentos[1]" 
       data-validate="required|file|fileSize:10"
       class="form-input">
```

### Accionistas

```html
<!-- Campos individuales de accionista (se agregan dinámicamente) -->
<input type="text" 
       name="accionistas[0][nombre]" 
       data-validate="required|minLength:3|maxLength:255"
       class="form-input">

<input type="text" 
       name="accionistas[0][rfc]" 
       data-validate="required|rfc"
       class="form-input">

<input type="number" 
       name="accionistas[0][porcentaje]" 
       data-validate="required|numeric|percentage"
       class="form-input">
```

## Estructura de Secciones

Para que el `SectionValidator` funcione correctamente, las secciones deben tener la siguiente estructura:

```html
<div class="form-section" data-step="1" id="datos-generales">
    <!-- Campos de datos generales -->
</div>

<div class="form-section hidden" data-step="2" id="direccion">
    <!-- Campos de dirección -->
</div>

<div class="form-section hidden" data-step="3" id="actividades">
    <!-- Campos de actividades -->
</div>

<div class="form-section hidden" data-step="4" id="documentos">
    <!-- Campos de documentos -->
</div>
```

## Detección Automática de Tipos

El `SectionValidator` detecta automáticamente el tipo de sección basándose en:

- **ID de la sección**: `id="datos-generales"`
- **Clases CSS**: `class="form-section datos-generales"`

Tipos soportados:
- `datos-generales`
- `direccion`
- `actividades`
- `documentos`
- `apoderado`
- `accionistas`
- `datos-constitutivos`

## Clases CSS para Estados

El sistema de validación aplica automáticamente las siguientes clases CSS:

- **Campos válidos**: `border-green-500 bg-green-50`
- **Campos inválidos**: `border-red-500 bg-red-50`
- **Campos normales**: `border-gray-200 bg-white`

## Mensajes de Error

Los mensajes de error se muestran automáticamente debajo de cada campo con la siguiente estructura:

```html
<div class="mt-2 flex items-center text-red-600">
    <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
    </svg>
    <span class="text-sm font-medium">Mensaje de error</span>
</div>
```

## Validaciones de Sección

### Actividades
- Valida que se hayan seleccionado al menos una actividad económica
- Busca elementos con clase `.actividad-item`

### Documentos
- Valida que se hayan subido todos los documentos requeridos
- Busca elementos con `data-documento-id` y sus correspondientes `file_*` inputs

### Accionistas
- Valida que se hayan agregado al menos un accionista
- Valida campos individuales de cada accionista (nombre, RFC, porcentaje)
- Busca elementos con clase `.accionista-item`

## Integración con Servicios PHP

Las validaciones del JavaScript están diseñadas para coincidir exactamente con las reglas de validación de los servicios PHP:

- `DatosGeneralesFormService`
- `DireccionFormService`
- `ActividadesFormService`
- `DocumentosFormService`
- `ApoderadoLegalFormService`
- `AccionistasFormService`
- `DatosConstitutivosFormService`

## Mejoras en la Versión Simplificada

### FormValidator
- ✅ Código más limpio y estructurado
- ✅ Validadores centralizados en objeto `validators`
- ✅ Mensajes de error configurables
- ✅ Validaciones de sección integradas
- ✅ Mejor manejo de errores

### SectionValidator
- ✅ Detección automática de tipos de sección
- ✅ Event listeners optimizados
- ✅ Navegación simplificada
- ✅ Validaciones específicas por tipo
- ✅ Observer para elementos dinámicos
- ✅ Notificaciones reutilizables

Esto asegura que la validación del lado del cliente sea consistente con la validación del lado del servidor. 
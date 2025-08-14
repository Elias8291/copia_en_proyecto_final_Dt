# Validaciones del Formulario de Edición

## Descripción General

El formulario de edición de trámites incluye un sistema completo de validaciones que se aplica de manera condicional basándose en el estado de las secciones (solo valida secciones rechazadas) y utiliza el sistema de validaciones existente en `/public/js/validations/`.

## Archivos de Validación

### 1. `edit-form-validator.js`
- **Propósito**: Configuración principal de validaciones para el formulario de edición
- **Funcionalidades**:
  - Validaciones estándar para todos los campos del formulario
  - Validación de actividades económicas
  - Validación de archivos de corrección
  - Validación de campos dinámicos de accionistas

### 2. `edit-form-conditional.js`
- **Propósito**: Validaciones condicionales basadas en el estado de las secciones
- **Funcionalidades**:
  - Solo valida secciones marcadas como "Rechazado"
  - Validación en tiempo real de campos editables
  - Validación específica por tipo de campo (email, RFC, teléfono, etc.)
  - Interceptación del envío del formulario

## Validaciones Implementadas

### Datos Generales
- **Razón Social**: Requerido, máximo 255 caracteres
- **RFC**: Requerido, formato RFC válido
- **Tipo de Persona**: Requerido
- **CURP**: Formato CURP válido (opcional)
- **Página Web**: URL válida, máximo 255 caracteres (opcional)
- **Teléfono**: Requerido, formato de teléfono válido, máximo 50 caracteres
- **Nombre de Contacto**: Requerido, máximo 255 caracteres
- **Cargo**: Requerido, máximo 255 caracteres
- **Correo de Contacto**: Requerido, email válido, máximo 255 caracteres
- **Teléfono de Contacto**: Requerido, formato de teléfono válido, máximo 50 caracteres

### Domicilio
- **Calle**: Requerido, máximo 255 caracteres
- **Entre Calle**: Requerido, máximo 255 caracteres
- **Y Calle**: Requerido, máximo 255 caracteres
- **Número Exterior**: Requerido, máximo 20 caracteres
- **Número Interior**: Máximo 20 caracteres (opcional)
- **Colonia**: Requerido, máximo 255 caracteres
- **Código Postal**: Requerido, formato de código postal válido (5 dígitos)
- **Municipio**: Requerido, máximo 100 caracteres
- **Asentamiento**: Requerido, máximo 100 caracteres
- **Estado**: Requerido
- **Latitud**: Requerido, número válido entre -90 y 90
- **Longitud**: Requerido, número válido entre -180 y 180

### Actividades Económicas
- **Actividades Seleccionadas**: Requerido, al menos una actividad económica
- **Validación JSON**: Verifica que el formato JSON sea válido
- **Validación de Contenido**: Verifica que haya al menos una actividad con nombre o ID

### Constitución (Solo Personas Morales)
- **Estado**: Requerido
- **Número de Escritura Constitutiva**: Requerido, máximo 255 caracteres
- **Fecha de Constitución**: Requerido, no puede ser futura
- **Nombre del Notario**: Requerido, máximo 255 caracteres
- **Número del Notario**: Requerido, máximo 255 caracteres
- **Número de Registro Público**: Requerido, máximo 255 caracteres
- **Fecha de Inscripción**: Requerido, no puede ser futura, debe ser posterior o igual a la fecha de constitución

### Apoderado Legal (Solo Personas Morales)
- **Nombre del Apoderado**: Requerido, máximo 255 caracteres
- **RFC del Apoderado**: Requerido, formato RFC válido
- **Número de Escritura Constitutiva del Poder**: Requerido, máximo 255 caracteres
- **Número de Registro Público del Poder**: Requerido, máximo 255 caracteres
- **Fecha de Inscripción del Poder**: Requerido, no puede ser futura
- **Nombre del Notario del Poder**: Requerido, máximo 255 caracteres
- **Número del Notario del Poder**: Requerido, máximo 255 caracteres
- **Número de Escritura del Poder**: Requerido, máximo 255 caracteres
- **Fecha del Poder**: Requerido, no puede ser futura

### Accionistas (Solo Personas Morales)
- **Nombre**: Requerido, máximo 255 caracteres
- **RFC**: Requerido, formato RFC válido
- **Porcentaje de Participación**: Requerido, porcentaje válido, suma total debe ser 100%

### Archivos de Corrección
- **Tipo de Archivo**: Debe coincidir con el tipo esperado
- **Tamaño**: Máximo 10MB por archivo
- **Formatos Soportados**: PDF, MP4, PNG, MP3, JPG, JPEG

## Comportamiento de Validación

### Validación Condicional
- Solo se validan las secciones que están marcadas como "Rechazado"
- Las secciones no rechazadas no se validan
- Los campos de secciones no rechazadas se limpian de errores automáticamente

### Validación en Tiempo Real
- Los campos se validan al perder el foco (blur)
- Los errores se limpian al empezar a escribir (focus)
- Validación específica por tipo de campo (email, RFC, teléfono, etc.)

### Validación al Enviar
- Se valida todo el formulario antes del envío
- Se bloquea el envío si hay errores
- Se muestra un mensaje general de error
- Se hace scroll automático al primer error

## Mensajes de Error

### Mensajes Generales
- "Por favor, corrija los errores marcados antes de continuar"
- "Este campo es obligatorio para la corrección"

### Mensajes Específicos por Campo
- **Email**: "Ingrese un correo electrónico válido"
- **RFC**: "Ingrese un RFC válido"
- **Teléfono**: "Ingrese un número de teléfono válido"
- **Código Postal**: "Ingrese un código postal válido (5 dígitos)"
- **Actividades**: "Debe seleccionar al menos una actividad económica"
- **Archivos**: "El archivo debe ser de tipo [TIPO]" o "El archivo es demasiado grande. Máximo: 10MB"

## Integración con el Sistema Existente

### Uso de Módulos ES6
```javascript
import FormController from './formController.js';
import FieldValidator from './validator.js';
```

### Configuración en el Formulario
```html
<!-- Scripts de validación -->
<script type="module" src="{{ asset('js/validations/edit-form-validator.js') }}"></script>
<script src="{{ asset('js/validations/edit-form-conditional.js') }}"></script>
```

### Funciones Globales Disponibles
- `validateOnlyRejectedSections()`: Valida solo secciones rechazadas
- `validateActividades()`: Valida actividades económicas
- `validateArchivosCorreccion()`: Valida archivos de corrección
- `showFieldError(field, message)`: Muestra error en un campo
- `clearFieldError(field)`: Limpia error de un campo

## Consideraciones Técnicas

### Compatibilidad
- Compatible con el sistema de validaciones existente
- Utiliza las mismas reglas y patrones de validación
- Mantiene la consistencia visual con el resto de la aplicación

### Rendimiento
- Validación condicional reduce la carga de procesamiento
- Validación en tiempo real solo para campos editables
- Limpieza automática de errores para campos no editables

### Mantenibilidad
- Código modular y reutilizable
- Configuración centralizada de validaciones
- Fácil extensión para nuevos tipos de validación

# Sistema de Validación JavaScript

## 📋 Descripción

Sistema de validación modular en JavaScript puro para formularios que proporciona validación en tiempo real, mensajes de error contextualizados y bloqueo de navegación cuando existen errores.

## 🏗️ Estructura del Proyecto

```
/public/js/validations/
├── rules.js           # Reglas de validación reutilizables
├── validator.js       # Validador de campos individuales
├── formController.js  # Controlador del formulario
└── index.js           # Punto de entrada y configuración
```

## 🚀 Características

### ✅ Validación en Tiempo Real
- Validación automática mientras el usuario escribe (`input`)
- Validación al perder el foco (`blur`)
- Limpieza automática de errores cuando el valor se vuelve válido

### ✅ Mensajes de Error Contextualizados
- Mensajes claros y específicos para cada tipo de error
- Contenedores de error únicos por campo
- Actualización dinámica de mensajes

### ✅ Bloqueo de Navegación
- Validación completa antes de enviar el formulario
- Validación por pasos antes de avanzar
- Scroll automático al primer error

### ✅ Estructura Modular
- Código limpio y mantenible
- Fácil extensión con nuevas reglas
- Reutilizable en diferentes formularios

## 📝 Reglas de Validación Disponibles

### Reglas Básicas
- `required` - Campo obligatorio
- `email` - Formato de email válido
- `url` - URL válida
- `phone` - Formato de teléfono
- `postalCode` - Código postal (5 dígitos)

### Reglas Específicas
- `rfc` - Formato RFC mexicano
- `curp` - Formato CURP mexicano
- `notFuture` - Fecha no futura
- `afterDate` - Fecha posterior a otra

### Reglas con Parámetros
- `minLength(min)` - Longitud mínima
- `maxLength(max)` - Longitud máxima
- `between(min, max)` - Número entre rangos
- `percentage` - Porcentaje (0-100)

## 🔧 Configuración

### 1. Incluir el Sistema

```html
<!-- En el head o antes del cierre del body -->
<link rel="stylesheet" href="{{ asset('css/form-validation.css') }}">
<script type="module" src="{{ asset('js/validations/index.js') }}"></script>
```

### 2. Configurar Validaciones

```javascript
// En index.js
const formConfig = [
    {
        selector: '[name="email"]',
        rules: ['required', 'email']
    },
    {
        selector: '[name="rfc"]',
        rules: ['required', 'rfc']
    },
    {
        selector: '[name="telefono"]',
        rules: ['required', 'phone', ['maxLength', 50]]
    }
];

const formController = new FormController('#mi-formulario', formConfig);
```

### 3. Estructura HTML Requerida

```html
<form id="tramite-form">
    <div class="field-container">
        <input type="text" name="razon_social" class="form-input">
        <!-- El contenedor de error se crea automáticamente -->
    </div>
</form>
```

## 🎯 Uso en el Formulario de Trámites

### Campos Configurados

#### Datos Generales
- `razon_social` - Requerido, máximo 255 caracteres
- `rfc` - Requerido, formato RFC
- `curp` - Formato CURP (opcional)
- `pagina_web` - URL válida (opcional)
- `telefono` - Requerido, formato teléfono
- `nombre_contacto` - Requerido
- `cargo` - Requerido
- `correo_contacto` - Requerido, email
- `telefono_contacto` - Requerido, formato teléfono

#### Domicilio
- `calle` - Requerido
- `numero_exterior` - Requerido
- `colonia` - Requerido
- `codigo_postal` - Requerido, 5 dígitos
- `municipio` - Requerido
- `estado_id` - Requerido
- `latitud/longitud` - Coordenadas válidas

#### Actividades
- `actividades_seleccionadas` - Requerido

#### Constitución (Personas Morales)
- `numero_escritura_constitutiva` - Requerido
- `fecha_constitucion` - Requerido, no futura
- `nombre_notario` - Requerido
- `fecha_inscripcion` - Requerido, posterior a constitución

#### Apoderado (Personas Morales)
- `nombre_apoderado` - Requerido
- `rfc_apoderado` - Requerido, formato RFC
- `fecha_poder` - Requerido, no futura

## 🔄 Eventos y Comportamientos

### Validación en Tiempo Real
```javascript
// Se ejecuta automáticamente en:
field.addEventListener('input', () => validator.validate());
field.addEventListener('blur', () => validator.validate());
```

### Validación por Pasos
```javascript
// Validar solo campos del paso actual
formController.validateCurrentStep();

// Validar todo el formulario
formController.validateAll();
```

### Navegación Bloqueada
```javascript
// Los botones de "Siguiente" y "Enviar" se bloquean automáticamente
// si hay errores en el paso actual o en todo el formulario
```

## 🎨 Estilos CSS

### Clases Aplicadas Automáticamente
- `.field-error` - Campo con error
- `.field-valid` - Campo válido
- `.error-message` - Contenedor de mensaje de error
- `.btn-disabled` - Botón deshabilitado

### Personalización
```css
.field-error {
    border-color: #dc2626 !important;
    box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1) !important;
}

.error-message {
    color: #dc2626;
    font-size: 0.875rem;
    margin-top: 0.25rem;
}
```

## 🔧 Extensión del Sistema

### Agregar Nueva Regla
```javascript
// En rules.js
const ValidationRules = {
    // ... reglas existentes
    
    nuevaRegla: (value) => {
        if (!value) return null;
        // Lógica de validación
        return esValido ? null : 'Mensaje de error';
    }
};
```

### Agregar Validación Personalizada
```javascript
// En la configuración
{
    selector: '[name="campo_personalizado"]',
    rules: [
        'required',
        (value) => {
            // Validación personalizada
            return esValido ? null : 'Error personalizado';
        }
    ]
}
```

## 🐛 Debugging

### Acceso al Controlador
```javascript
// El controlador está disponible globalmente
window.tramiteFormValidator.validateAll();
window.tramiteFormValidator.getValidationState();
```

### Estado de Validación
```javascript
// Obtener estado de todos los campos
const state = formController.getValidationState();
console.log(state);
// { campo1: true, campo2: false, ... }
```

## 📱 Compatibilidad

- ✅ Chrome 61+
- ✅ Firefox 60+
- ✅ Safari 10.1+
- ✅ Edge 79+

## 🔒 Consideraciones de Seguridad

- Las validaciones del lado del cliente son solo para UX
- Siempre validar en el servidor (Laravel)
- No confiar únicamente en validaciones JavaScript
- Sanitizar datos antes de procesar

## 📚 Referencias

- [Laravel Validation](https://laravel.com/docs/validation)
- [HTML5 Form Validation](https://developer.mozilla.org/en-US/docs/Learn/Forms/Form_validation)
- [JavaScript ES6 Modules](https://developer.mozilla.org/en-US/docs/Web/JavaScript/Guide/Modules) 
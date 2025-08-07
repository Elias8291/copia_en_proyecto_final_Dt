# Sistema de Validación JavaScript con Tailwind CSS

## 📋 Descripción

Sistema de validación modular en JavaScript puro que usa exclusivamente Tailwind CSS para los estilos. Proporciona validación en tiempo real, mensajes de error contextualizados y bloqueo de navegación cuando existen errores.

## 🎨 Características de Diseño

### ✅ Solo Tailwind CSS
- No hay archivos CSS personalizados
- Usa clases nativas de Tailwind
- Diseño limpio y consistente
- Iconos originales sin modificaciones

### ✅ Clases Utilizadas
```css
/* Campos con error */
border-red-500 focus:border-red-500 focus:ring-red-500

/* Mensajes de error */
text-red-600 text-sm mt-1 hidden/block

/* Botones deshabilitados */
opacity-50 cursor-not-allowed
```

## 🏗️ Estructura del Proyecto

```
/public/js/validations/
├── rules.js              # Reglas de validación reutilizables
├── validator.js          # Validador de campos individuales
├── formController.js     # Controlador del formulario
├── tailwind-config.js    # Configuración de clases Tailwind
└── index.js              # Punto de entrada y configuración
```

## 🚀 Características Funcionales

### ✅ Validación en Tiempo Real
- Validación automática mientras el usuario escribe
- Validación al perder el foco
- Limpieza automática de errores cuando el valor se vuelve válido

### ✅ Mensajes de Error Contextualizados
- Mensajes claros y específicos para cada tipo de error
- Contenedores de error únicos por campo
- Actualización dinámica de mensajes

### ✅ Bloqueo de Navegación
- Validación completa antes de enviar el formulario
- Validación por pasos antes de avanzar
- Scroll automático al primer error

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
<!-- Solo incluir el JavaScript, no se necesitan CSS adicionales -->
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
    }
];

const formController = new FormController('#mi-formulario', formConfig);
```

### 3. Estructura HTML Requerida

```html
<form id="tramite-form">
    <div class="relative">
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

## 🎨 Clases Tailwind Utilizadas

### Campos con Error
```css
.border-red-500
.focus:border-red-500
.focus:ring-red-500
```

### Mensajes de Error
```css
.text-red-600
.text-sm
.mt-1
.hidden (para ocultar)
```

### Botones Deshabilitados
```css
.opacity-50
.cursor-not-allowed
```

### Contenedores
```css
.relative (para posicionamiento de mensajes)
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

## 🎯 Ventajas del Sistema

### ✅ Simplicidad
- Solo Tailwind CSS, sin archivos CSS adicionales
- Código limpio y mantenible
- Fácil de entender y modificar

### ✅ Consistencia
- Usa las mismas clases de Tailwind en todo el proyecto
- No hay conflictos de estilos
- Diseño uniforme

### ✅ Rendimiento
- No hay archivos CSS adicionales que cargar
- JavaScript modular y eficiente
- Validación en tiempo real sin impacto en rendimiento

### ✅ Mantenibilidad
- Fácil de extender con nuevas reglas
- Configuración centralizada
- Documentación completa

## 📚 Referencias

- [Tailwind CSS](https://tailwindcss.com/)
- [Laravel Validation](https://laravel.com/docs/validation)
- [JavaScript ES6 Modules](https://developer.mozilla.org/en-US/docs/Web/JavaScript/Guide/Modules) 
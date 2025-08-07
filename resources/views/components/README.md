# Estructura de Componentes

Esta carpeta contiene todos los componentes Blade reutilizables del proyecto, organizados en carpetas temáticas para facilitar su mantenimiento y localización.

## 📁 Estructura de Carpetas

### 🎨 UI (Interfaz de Usuario)
Componentes básicos de interfaz reutilizables en toda la aplicación.

#### `ui/buttons/`
- **action-button.blade.php** - Botón de acción principal con múltiples variantes
- **danger-button.blade.php** - Botón de acción peligrosa (rojo)
- **secondary-button.blade.php** - Botón secundario (gris)
- **boton-descripcion.blade.php** - Botón con descripción

#### `ui/modals/`
- **modal-confirmacion.blade.php** - Modal de confirmación genérico
- **modal-exito.blade.php** - Modal de éxito/confirmación
- **modal-eliminar.blade.php** - Modal de confirmación de eliminación
- **modal-error.blade.php** - Modal de error
- **delete-confirmation-modal.blade.php** - Modal de confirmación de eliminación avanzado
- **delete-modal.blade.php** - Modal de eliminación simple
- **error-modal.blade.php** - Modal de error avanzado
- **loading-modal.blade.php** - Modal de carga con barra de progreso
- **modal.blade.php** - Modal base genérico
- **sat-data-modal.blade.php** - Modal para mostrar datos del SAT

#### `ui/alerts/`
- **alert.blade.php** - Sistema de alertas principal
- **notification.blade.php** - Componente de notificaciones

#### `ui/forms/`
- **form-field.blade.php** - Campo de formulario genérico
- **password-input.blade.php** - Campo de contraseña con toggle
- **openstreet-map.blade.php** - Componente de mapa OpenStreetMap

#### `ui/`
- **section-header.blade.php** - Encabezado de sección
- **separador-simple.blade.php** - Separador visual

### 🧭 Navigation (Navegación)
Componentes relacionados con la navegación y pasos.

- **steps.blade.php** - Componente de pasos con barra de progreso
- **step-navigation.blade.php** - Navegación entre pasos

### 📊 Data Display (Visualización de Datos)
Componentes para mostrar información estructurada.

- **data-table.blade.php** - Tabla de datos genérica
- **tramites-table.blade.php** - Tabla específica para trámites
- **tramite-card.blade.php** - Tarjeta de trámite
- **tramites-lista.blade.php** - Lista de trámites
- **status-badge.blade.php** - Badge de estado

### 📋 Tramites (Trámites)
Componentes específicos para el módulo de trámites.

- **cotejo-selector.blade.php** - Selector de cotejo
- **documento-comparador.blade.php** - Comparador de documentos

### 🔍 Revision (Revisión)
Componentes específicos para el módulo de revisiones.

*Los componentes de revisión se encuentran en la carpeta `revision/`*

### 📋 Forms (Formularios)
Componentes específicos de formularios.

*Los componentes de formularios se encuentran en la carpeta `forms/`*

### 📊 Data Table (Tabla de Datos)
Componentes específicos de tablas de datos.

*Los componentes de tabla de datos se encuentran en la carpeta `data-table/`*

## 🔧 Uso de Componentes

### Sintaxis de Referencia

Para usar un componente, utiliza la siguiente sintaxis:

```blade
<!-- Componentes UI -->
<x-ui.buttons.action-button tipo="primary" size="lg">
    Texto del botón
</x-ui.buttons.action-button>

<x-ui.modals.modal-confirmacion 
    id="mi-modal"
    title="Confirmar acción"
    message="¿Está seguro?"
/>

<x-ui.alerts.alert />

<x-ui.forms.password-input 
    name="password" 
    label="Contraseña" 
    placeholder="••••••••" 
/>

<!-- Componentes de Navegación -->
<x-navigation.steps 
    :steps="$steps" 
    :current-step="0" 
    :total-steps="$totalSteps" 
/>

<!-- Componentes de Visualización de Datos -->
<x-data-display.status-badge 
    :estado="'pendiente'" 
    :texto="'Pendiente'" 
/>

<x-data-display.tramite-card 
    :tramite="$tramite" 
/>

<!-- Componentes de Trámites -->
<x-tramites.cotejo-selector />

<!-- Componentes de Revisión -->
<x-revision.historial-item />
```

## 📝 Convenciones de Nomenclatura

1. **Archivos**: Usar kebab-case (ej: `action-button.blade.php`)
2. **Componentes**: Usar kebab-case en las referencias (ej: `x-ui.buttons.action-button`)
3. **Carpetas**: Usar kebab-case (ej: `data-display/`)

## 🚀 Agregar Nuevos Componentes

1. **Identifica la categoría** apropiada para tu componente
2. **Crea el archivo** en la carpeta correspondiente
3. **Usa la sintaxis** correcta para referenciarlo
4. **Actualiza este README** si es necesario

## 🔍 Búsqueda de Componentes

Para encontrar un componente específico:

1. **UI básicos**: Busca en `ui/`
2. **Navegación**: Busca en `navigation/`
3. **Visualización de datos**: Busca en `data-display/`
4. **Trámites específicos**: Busca en `tramites/`
5. **Revisión específica**: Busca en `revision/`

## 📋 Componentes Faltantes

- **archivos-requeridos.blade.php** - Archivo vacío, considerar eliminarlo o implementarlo 
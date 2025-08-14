# Componentes JavaScript Reutilizables

## LoadingEffects

Componente para manejar estados de carga en botones y formularios de manera consistente.

### Uso Básico

```javascript
// Para un botón individual
const button = document.getElementById('mi-boton');
LoadingEffects.showButtonLoading(button, 'Guardando...');

// Para restaurar el botón
LoadingEffects.hideButtonLoading(button);

// Para un formulario completo
const form = document.getElementById('mi-formulario');
LoadingEffects.showFormLoading(form, 'Procesando...');
LoadingEffects.hideFormLoading(form);
```

### Funciones Disponibles

#### `LoadingEffects.showButtonLoading(button, text, type)`
- **button**: Elemento del botón
- **text**: Texto a mostrar (default: 'Cargando...')
- **type**: Tipo de spinner ('simple', 'dots', 'pulse')

#### `LoadingEffects.hideButtonLoading(button)`
- **button**: Elemento del botón a restaurar

#### `LoadingEffects.showFormLoading(form, text)`
- **form**: Elemento del formulario
- **text**: Texto para los botones de submit

#### `LoadingEffects.hideFormLoading(form)`
- **form**: Elemento del formulario a restaurar

#### `LoadingEffects.setAutoTimeout(element, timeout)`
- **element**: Botón o formulario
- **timeout**: Tiempo en ms (default: 10000)

### Funciones Globales de Conveniencia

```javascript
// Equivalentes a los métodos de clase
showButtonLoading(button, 'Texto', 'tipo');
hideButtonLoading(button);
showFormLoading(form, 'Texto');
hideFormLoading(form);
```

### Tipos de Spinner

- **simple**: Spinner circular clásico (default)
- **dots**: Tres puntos que rebotan
- **pulse**: Círculo que pulsa

### Estilos CSS

Incluir `loading-effects.css` para los estilos base. Las clases principales son:

- `.loading-state`: Aplicada automáticamente a botones en carga
- `.form-loading`: Aplicada automáticamente a formularios en carga
- `.loading-spinner`: Para spinners personalizados
- `.loading-dots`: Para animación de puntos

### Ejemplo de Implementación

```html
<form id="mi-formulario">
    <button type="submit" id="submit-btn">Enviar</button>
</form>

<script>
document.getElementById('submit-btn').addEventListener('click', function(e) {
    e.preventDefault();
    const form = document.getElementById('mi-formulario');
    
    // Mostrar loading
    showFormLoading(form, 'Enviando...');
    
    // Simular envío
    setTimeout(() => {
        hideFormLoading(form);
        alert('¡Enviado!');
    }, 2000);
});
</script>
```

### Características

- ✅ **Reutilizable**: Funciona con cualquier botón o formulario
- ✅ **Auto-cleanup**: Timeout automático para evitar estados colgados
- ✅ **Fallback**: Funciona incluso si no se carga el CSS
- ✅ **Responsive**: Se adapta a diferentes tamaños de pantalla
- ✅ **Accesible**: Mantiene la accesibilidad durante el loading
- ✅ **Sin dependencias**: JavaScript vanilla puro

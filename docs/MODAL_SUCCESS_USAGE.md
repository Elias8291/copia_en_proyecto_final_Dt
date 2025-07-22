# Modal de Éxito - Guía de Uso

## Descripción
Componente reutilizable de modal de éxito construido con Alpine.js que se puede usar en toda la aplicación.

## Características
- ✅ Diseño moderno con Alpine.js
- ✅ Completamente reutilizable
- ✅ Soporte para callbacks personalizados
- ✅ Redirección automática opcional
- ✅ Cierre con ESC y click fuera del modal
- ✅ Animaciones suaves
- ✅ Responsive design

## Uso Básico

### 1. Desde JavaScript
```javascript
// Uso básico
showSuccessModal({
    title: '¡Registro Exitoso!',
    message: 'Tu cuenta ha sido creada correctamente.',
    buttonText: 'Continuar'
});

// Con callback personalizado
showSuccessModal({
    title: '¡Datos Guardados!',
    message: 'La información se ha guardado correctamente.',
    buttonText: 'Aceptar',
    onClose: function() {
        console.log('Modal cerrado');
        // Lógica adicional aquí
    }
});

// Con redirección automática
showSuccessModal({
    title: '¡Operación Completada!',
    message: 'Serás redirigido al dashboard.',
    buttonText: 'Ir al Dashboard',
    redirectTo: '/dashboard'
});
```

### 2. Desde Laravel Controller
```php
// En tu controller
public function store(Request $request)
{
    // Tu lógica aquí...
    
    return redirect()->back()->with([
        'show_success_modal' => true,
        'modal_title' => '¡Usuario Creado!',
        'modal_message' => 'El usuario se ha registrado exitosamente.',
        'modal_button_text' => 'Continuar',
        'modal_redirect_to' => route('users.index') // Opcional
    ]);
}
```

### 3. Desde Blade Template
```blade
@if(session('show_success_modal'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        showSuccessModalFromSession(@json(session()->all()));
    });
</script>
@endif
```

## Parámetros Disponibles

| Parámetro | Tipo | Default | Descripción |
|-----------|------|---------|-------------|
| `title` | string | '¡Operación Exitosa!' | Título del modal |
| `message` | string | 'La operación se ha completado exitosamente.' | Mensaje principal |
| `buttonText` | string | 'Aceptar' | Texto del botón |
| `onClose` | function | null | Función callback al cerrar |
| `redirectTo` | string | null | URL para redireccionar |

## Ejemplos de Implementación

### Ejemplo 1: Registro de Usuario
```javascript
// En tu formulario de registro
document.getElementById('registerForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Tu lógica de registro aquí...
    
    showSuccessModal({
        title: '¡Bienvenido!',
        message: 'Tu cuenta ha sido creada exitosamente. Ya puedes comenzar a usar la plataforma.',
        buttonText: 'Ir al Dashboard',
        redirectTo: '/dashboard'
    });
});
```

### Ejemplo 2: Guardado de Datos
```javascript
function saveData() {
    // Tu lógica de guardado...
    
    showSuccessModal({
        title: '¡Datos Guardados!',
        message: 'La información se ha guardado correctamente en el sistema.',
        buttonText: 'Continuar',
        onClose: function() {
            // Limpiar formulario o actualizar vista
            document.getElementById('myForm').reset();
        }
    });
}
```

### Ejemplo 3: Desde Laravel con Session
```php
// Controller
public function update(Request $request, $id)
{
    // Tu lógica de actualización...
    
    return redirect()->route('items.index')->with([
        'show_success_modal' => true,
        'modal_title' => '¡Actualización Exitosa!',
        'modal_message' => 'Los datos se han actualizado correctamente.',
        'modal_button_text' => 'Ver Lista'
    ]);
}
```

```blade
<!-- En tu vista Blade -->
@if(session('show_success_modal'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        showSuccessModalFromSession(@json(session()->all()));
    });
</script>
@endif
```

## Notas Importantes

1. **Inclusión Automática**: El componente ya está incluido en el layout principal (`app.blade.php`), por lo que está disponible en todas las páginas.

2. **Alpine.js**: Asegúrate de que Alpine.js esté cargado antes de usar el componente.

3. **Funciones Globales**: Las funciones `showSuccessModal()` y `showSuccessModalFromSession()` están disponibles globalmente.

4. **Responsive**: El modal se adapta automáticamente a diferentes tamaños de pantalla.

5. **Accesibilidad**: Incluye soporte para navegación con teclado y lectores de pantalla.
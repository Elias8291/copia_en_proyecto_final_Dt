# Organización de Modales

Esta carpeta contiene todos los modales organizados por funcionalidad para facilitar su mantenimiento y reutilización.

## Estructura de Carpetas

```
resources/views/components/modals/
├── auth/                    # Modales relacionados con autenticación
│   ├── registration-success.blade.php    # Modal de registro exitoso
│   ├── email-verified.blade.php          # Modal de correo verificado
│   └── password-reset.blade.php          # Modal de contraseña restablecida (futuro)
├── tramites/               # Modales para trámites
│   ├── tramite-success.blade.php         # Modal de trámite exitoso (futuro)
│   └── tramite-error.blade.php           # Modal de error en trámite (futuro)
├── general/                # Modales generales reutilizables
│   ├── error.blade.php                   # Modal de error genérico reutilizable
│   ├── confirmation.blade.php            # Modal de confirmación genérico (futuro)
│   └── loading.blade.php                 # Modal de carga (futuro)
└── README.md              # Este archivo de documentación
```

## Convenciones de Nomenclatura

### Archivos
- Usar kebab-case para nombres de archivos
- Incluir el propósito del modal en el nombre
- Ejemplo: `registration-success.blade.php`, `email-verified.blade.php`

### IDs y Funciones JavaScript
- **IDs**: `modal{Propósito}` (camelCase con prefijo modal)
  - Ejemplo: `modalRegistroExitoso`, `modalCorreoVerificado`, `modalError`
- **Funciones**: `mostrarModal{Propósito}()` y `cerrarModal{Propósito}()`
  - Ejemplo: `mostrarModalRegistroExitoso()`, `cerrarModalError()`

### Clases CSS
- Usar clases consistentes para animaciones y estilos
- Mantener la estructura HTML similar entre modales

## Características Estándar de Modales

Todos los modales deben incluir:

1. **Botón de cerrar (X)** en la esquina superior derecha
2. **Animaciones suaves** de entrada y salida
3. **Cierre con Escape** y clic fuera del modal
4. **Responsive design** para móviles y desktop
5. **Accesibilidad** con roles ARIA apropiados

## Uso en Vistas

### Incluir un Modal
```blade
@include('components.modals.auth.registration-success')
@include('components.modals.general.error')
```

### Mostrar un Modal
```blade
@if (session('showSuccessModal'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            mostrarModalRegistroExitoso();
        });
    </script>
@endif
```

### Modal de Error (JavaScript)
```javascript
// Mostrar modal de error con título y descripción personalizados
mostrarModalError('Error al procesar archivo', 'No se pudo leer el código QR del documento.');
```

## Modales Actuales

### Auth (Autenticación)

#### registration-success.blade.php
- **Propósito**: Confirmar registro exitoso de usuario
- **Trigger**: `session('showSuccessModal')`
- **Funciones**: `mostrarModalRegistroExitoso()`, `cerrarModalRegistroExitoso()`
- **Redirección**: Al login después de cerrar

#### email-verified.blade.php
- **Propósito**: Confirmar verificación de correo electrónico
- **Trigger**: `session('showEmailVerifiedModal')`
- **Funciones**: `mostrarModalCorreoVerificado()`, `cerrarModalCorreoVerificado()`
- **Redirección**: Al login después de cerrar

### General (Modales Reutilizables)

#### error.blade.php
- **Propósito**: Mostrar errores de forma consistente y reutilizable
- **Trigger**: `mostrarModalError(titulo, descripcion)`
- **Funciones**: `mostrarModalError()`, `cerrarModalError()`
- **Parámetros**: 
  - `titulo`: Título del error (string)
  - `descripcion`: Descripción detallada del error (string)
- **Uso**: Errores de procesamiento, validación, QR no reconocido, etc.

## Mejores Prácticas

1. **Reutilización**: Crear modales genéricos cuando sea posible
2. **Consistencia**: Mantener el mismo patrón de diseño y funcionalidad
3. **Performance**: Cargar modales solo cuando se necesiten
4. **Accesibilidad**: Incluir atributos ARIA y navegación por teclado
5. **Testing**: Probar en diferentes dispositivos y navegadores
6. **Parámetros**: Usar parámetros para hacer modales más flexibles

## Ejemplos de Uso

### Modal de Error en JavaScript
```javascript
// Error de QR no reconocido
mostrarModalError(
    'Error al procesar el archivo',
    'No se pudo extraer la información del código QR. Verifica que el archivo contenga un código QR válido.'
);

// Error de validación
mostrarModalError(
    'Datos incompletos',
    'Por favor completa todos los campos requeridos antes de continuar.'
);
```

### Modal de Éxito desde Controller
```php
return redirect()->route('register')->with([
    'showSuccessModal' => true,
    'userEmail' => $request->email
]);
```

## Futuras Expansiones

- Modales para confirmación de acciones
- Modales para formularios complejos
- Modales para visualización de documentos
- Modales para notificaciones del sistema
- Modal de loading/carga con progreso
- Modal de confirmación genérico
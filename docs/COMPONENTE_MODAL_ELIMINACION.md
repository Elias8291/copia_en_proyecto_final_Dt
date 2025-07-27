# Componente Modal de Eliminación

## Descripción

Componente reutilizable para confirmar acciones de eliminación con un modal elegante y responsivo usando Tailwind CSS.

## Características

### ✅ Diseño Moderno
- **Tailwind CSS**: Diseño limpio y moderno basado en modal-exito
- **Responsivo**: Funciona perfectamente en móviles y desktop
- **Adaptación móvil**: Diseño optimizado para dispositivos móviles
- **Accesibilidad**: Soporte para lectores de pantalla

### ✅ Funcionalidades
- **Confirmación visual**: Modal con información detallada
- **Múltiples acciones**: Soporte para diferentes tipos de eliminación
- **Teclado**: Cerrar con Escape
- **Overlay**: Cerrar haciendo clic fuera del modal
- **Formulario integrado**: Envío automático con CSRF
- **SoftDelete**: Eliminación suave por defecto

### ✅ Personalización
- **Título personalizable**: Diferentes títulos según contexto
- **Mensaje personalizable**: Explicación específica de la acción
- **Texto de botones**: Confirmar y cancelar personalizables
- **Información del elemento**: Nombre y tipo del elemento a eliminar

## Uso del Componente

### Sintaxis Básica

```blade
<x-delete-modal 
    id="deleteModal1"
    title="Eliminar Usuario"
    message="¿Está seguro de que desea eliminar este usuario?"
    confirmText="Eliminar"
    cancelText="Cancelar"
    action="/users/1"
    method="DELETE"
    itemName="Juan Pérez"
    itemType="usuario"
/>
```

### Parámetros Disponibles

| Parámetro | Tipo | Requerido | Descripción |
|-----------|------|-----------|-------------|
| `id` | string | Sí | ID único del modal |
| `title` | string | No | Título del modal (default: "Confirmar Eliminación") |
| `message` | string | No | Mensaje principal (default: "¿Está seguro...?") |
| `confirmText` | string | No | Texto del botón confirmar (default: "Eliminar") |
| `cancelText` | string | No | Texto del botón cancelar (default: "Cancelar") |
| `action` | string | Sí | URL de la acción de eliminación |
| `method` | string | No | Método HTTP (default: "DELETE") |
| `itemName` | string | No | Nombre del elemento a eliminar |
| `itemType` | string | No | Tipo del elemento (default: "elemento") |

### Ejemplos de Uso

#### Eliminación de Usuario
```blade
<x-delete-modal 
    :id="'deleteModal' . $user->id"
    title="Eliminar Usuario"
    message="¿Está seguro de que desea eliminar este usuario? Esta acción marcará al usuario como inactivo."
    confirmText="Eliminar Usuario"
    :action="route('users.destroy', $user)"
    :itemName="$user->nombre"
    itemType="usuario"
/>
```

#### Eliminación Permanente
```blade
<x-delete-modal 
    :id="'forceDeleteModal' . $user->id"
    title="Eliminar Permanentemente"
    message="¿Está seguro de que desea eliminar permanentemente este usuario? Esta acción no se puede deshacer."
    confirmText="Eliminar Permanentemente"
    :action="route('users.force-delete', $user)"
    :itemName="$user->nombre"
    itemType="usuario"
/>
```

#### Eliminación de Archivo
```blade
<x-delete-modal 
    :id="'deleteFileModal' . $file->id"
    title="Eliminar Archivo"
    message="¿Está seguro de que desea eliminar este archivo?"
    confirmText="Eliminar Archivo"
    :action="route('files.destroy', $file)"
    :itemName="$file->name"
    itemType="archivo"
/>
```

## Funciones JavaScript

### Abrir Modal
```javascript
openModal('deleteModal1');
```

### Cerrar Modal
```javascript
closeModal('deleteModal1');
```

### Eventos Automáticos

- **Escape**: Cierra el modal automáticamente
- **Overlay**: Clic fuera del modal lo cierra
- **Animaciones**: Entrada y salida suaves

## Estructura HTML

### Modal Container
```html
<div id="modalId" class="fixed z-50 inset-0 overflow-y-auto">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <!-- Overlay -->
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>
        
        <!-- Modal Content -->
        <div class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
            <!-- Close Button (Desktop) -->
            <div class="hidden sm:block absolute top-0 right-0 pt-4 pr-4">
                <button type="button" data-behavior="cancel" class="bg-white rounded-md text-gray-400 hover:text-gray-500">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            
            <!-- Content -->
            <div class="sm:flex sm:items-start">
                <!-- Icon -->
                <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                    <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                    </svg>
                </div>
                
                <!-- Text Content -->
                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                        {{ $title }}
                    </h3>
                    <div class="mt-2">
                        <p class="text-sm text-gray-500">{{ $message }}</p>
                        <!-- Item Info -->
                        <!-- Warning Message -->
                    </div>
                </div>
            </div>
            
            <!-- Footer -->
            <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                <form method="POST" action="{{ $action }}" class="inline">
                    @csrf
                    @method($method)
                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                        {{ $confirmText }}
                    </button>
                </form>
                <button type="button" data-behavior="cancel" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:w-auto sm:text-sm">
                    {{ $cancelText }}
                </button>
            </div>
        </div>
    </div>
</div>
```

## Características de Diseño

### Responsive Design
- **Mobile-first**: Diseño optimizado para dispositivos móviles
- **Desktop**: Adaptación completa para pantallas grandes
- **Flexible**: Se adapta a diferentes tamaños de pantalla

### Colores y Temas
- **Error Theme**: Colores rojos para acciones destructivas
- **Warning Icons**: Iconos de advertencia apropiados
- **Consistent**: Mantiene la consistencia con el modal de éxito

## Integración con Laravel

### Rutas
```php
Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
Route::delete('/users/{user}/force', [UserController::class, 'forceDelete'])->name('users.force-delete');
```

### Controlador
```php
public function destroy(User $user)
{
    $user->delete(); // SoftDelete
    return redirect()->route('users.index')->with('success', 'Usuario eliminado');
}

public function forceDelete(User $user)
{
    $user->forceDelete(); // Eliminación permanente
    return redirect()->route('users.index')->with('success', 'Usuario eliminado permanentemente');
}
```

## Casos de Uso

### 1. Eliminación Simple
- Usuarios, archivos, registros básicos
- Confirmación estándar
- SoftDelete opcional

### 2. Eliminación Permanente
- Datos críticos
- Advertencia especial
- Sin posibilidad de restauración

### 3. Eliminación en Lote
- Múltiples elementos
- Confirmación individual
- Proceso por lotes

## Mejoras Futuras

- [ ] Soporte para múltiples idiomas
- [ ] Temas personalizables
- [ ] Animaciones más avanzadas
- [ ] Integración con notificaciones
- [ ] Historial de eliminaciones
- [ ] Confirmación por email

## Consideraciones de Seguridad

### CSRF Protection
- Token automático en formularios
- Validación del lado servidor
- Protección contra ataques CSRF

### Validación
- Verificación de permisos
- Confirmación de propiedad
- Logs de auditoría

### UX/UI
- Confirmación clara
- Información detallada
- Opción de cancelar
- Feedback visual

## Conclusión

El componente modal de eliminación proporciona una experiencia de usuario consistente y segura para todas las operaciones de eliminación en el sistema. Es reutilizable, personalizable y sigue las mejores prácticas de diseño web moderno. 
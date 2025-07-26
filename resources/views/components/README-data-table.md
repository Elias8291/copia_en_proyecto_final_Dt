# Componente Data Table Reutilizable

## 📋 Descripción
El componente `x-data-table` es una tabla completamente reutilizable que mantiene el mismo diseño elegante pero se adapta a cualquier tipo de datos y columnas.

## 🚀 Uso Básico

```blade
<x-data-table 
    :data="$datos"
    :columns="$columnas"
    title="Mi Tabla"
/>
```

## 📊 Propiedades Disponibles

### Propiedades Principales
- `data` (array): Los datos a mostrar
- `columns` (array): Configuración de las columnas
- `title` (string): Título de la tabla
- `description` (string): Descripción debajo del título

### Propiedades Opcionales
- `icon` (string): Icono SVG path para el header
- `searchPlaceholder` (string): Placeholder del campo de búsqueda
- `emptyMessage` (string): Mensaje cuando no hay datos
- `emptyDescription` (string): Descripción cuando no hay datos
- `createButtonText` (string): Texto del botón crear
- `exportButtonText` (string): Texto del botón exportar
- `showSearch` (boolean): Mostrar campo de búsqueda
- `showFilters` (boolean): Mostrar filtros
- `showPagination` (boolean): Mostrar paginación
- `actions` (array): Acciones disponibles ['view', 'edit', 'delete']

## 🎨 Tipos de Columnas

### 1. Texto Simple (default)
```php
[
    'key' => 'nombre',
    'label' => 'Nombre'
]
```

### 2. Avatar (con imagen/letra)
```php
[
    'key' => 'nombre',
    'label' => 'Usuario',
    'type' => 'avatar',
    'subtitle' => 'email' // Campo adicional debajo
]
```

### 3. Badge (con colores)
```php
[
    'key' => 'estado',
    'label' => 'Estado',
    'type' => 'badge',
    'colors' => [
        'activo' => 'bg-green-100 text-green-700 border-green-200',
        'inactivo' => 'bg-gray-100 text-gray-700 border-gray-200'
    ]
]
```

### 4. Fecha
```php
[
    'key' => 'fecha_creacion',
    'label' => 'Fecha',
    'type' => 'date'
]
```

### 5. Progreso
```php
[
    'key' => 'progreso',
    'label' => 'Progreso',
    'type' => 'progress',
    'max' => 100 // Valor máximo para calcular porcentaje
]
```

## 📝 Ejemplos de Uso

### Ejemplo 1: Tabla de Trámites
```blade
<x-data-table 
    :data="$tramites"
    :columns="[
        [
            'key' => 'proveedor.nombre',
            'label' => 'Proveedor',
            'type' => 'avatar',
            'subtitle' => 'proveedor_id'
        ],
        [
            'key' => 'tipo_tramite',
            'label' => 'Tipo',
            'type' => 'badge',
            'colors' => [
                'Inscripcion' => 'bg-blue-100 text-blue-700 border-blue-200',
                'Renovacion' => 'bg-green-100 text-green-700 border-green-200'
            ]
        ],
        [
            'key' => 'estado',
            'label' => 'Estado',
            'type' => 'badge',
            'colors' => [
                'Pendiente' => 'bg-yellow-100 text-yellow-700 border-yellow-200',
                'Aprobado' => 'bg-green-100 text-green-700 border-green-200'
            ]
        ],
        [
            'key' => 'fecha_inicio',
            'label' => 'Fecha',
            'type' => 'date'
        ],
        [
            'key' => 'paso_actual',
            'label' => 'Progreso',
            'type' => 'progress',
            'max' => 5
        ]
    ]"
    title="Gestión de Trámites"
    description="Administra y revisa el estado de los trámites"
    :actions="['view', 'edit', 'delete']"
/>
```

### Ejemplo 2: Tabla de Usuarios
```blade
<x-data-table 
    :data="$usuarios"
    :columns="[
        [
            'key' => 'nombre',
            'label' => 'Usuario',
            'type' => 'avatar',
            'subtitle' => 'email'
        ],
        [
            'key' => 'rol',
            'label' => 'Rol',
            'type' => 'badge',
            'colors' => [
                'admin' => 'bg-red-100 text-red-700 border-red-200',
                'usuario' => 'bg-blue-100 text-blue-700 border-blue-200'
            ]
        ],
        [
            'key' => 'fecha_registro',
            'label' => 'Registro',
            'type' => 'date'
        ]
    ]"
    title="Gestión de Usuarios"
    description="Administra los usuarios del sistema"
    :actions="['view', 'edit']"
/>
```

### Ejemplo 3: Tabla Simple
```blade
<x-data-table 
    :data="$productos"
    :columns="[
        ['key' => 'nombre', 'label' => 'Nombre'],
        ['key' => 'precio', 'label' => 'Precio'],
        ['key' => 'stock', 'label' => 'Stock'],
        ['key' => 'categoria', 'label' => 'Categoría']
    ]"
    title="Productos"
    description="Lista de productos disponibles"
    :show-search="false"
    :show-filters="false"
    :actions="['view']"
/>
```

## 🎯 Características

### ✅ Responsive
- **Desktop**: Tabla tradicional
- **Móvil**: Tarjetas individuales

### ✅ Tipos de Datos
- Texto simple
- Avatares con iniciales
- Badges con colores
- Fechas formateadas
- Barras de progreso

### ✅ Funcionalidades
- Búsqueda (opcional)
- Filtros (opcional)
- Paginación (opcional)
- Acciones configurables
- Estado vacío personalizable

### ✅ Personalización
- Títulos y descripciones
- Iconos SVG
- Colores de badges
- Textos de botones
- Mensajes de error

## 🔧 Configuración en el Controlador

```php
public function index()
{
    $tramites = Tramite::with('proveedor')->get();
    
    return view('tramites.index', compact('tramites'));
}
```

## 💡 Consejos

1. **Usa `with()`** para cargar relaciones y evitar N+1 queries
2. **Define colores consistentes** para los badges
3. **Usa `date` type** para campos de fecha automáticamente
4. **Configura `max`** en progreso para calcular porcentajes correctamente
5. **Personaliza mensajes** para mejor UX

## 🎨 Personalización de Estilos

El componente usa las clases de Tailwind definidas en tu `tailwind.config.js`:
- `primary`, `primary-dark`, `primary-light`
- Breakpoints: `xs`, `md`, `lg`
- Colores de estado para badges 
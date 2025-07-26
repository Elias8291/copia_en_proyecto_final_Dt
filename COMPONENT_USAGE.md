# 📋 Componente x-data-table - Guía de Uso

## 🎯 Descripción
El componente `x-data-table` es una tabla reutilizable y responsive que incluye filtros, búsqueda, paginación y acciones. Está diseñado para mostrar cualquier tipo de datos de manera elegante y funcional.

## 🚀 Uso Básico

```blade
<x-data-table 
    title="Mi Tabla"
    description="Descripción de la tabla"
    :data="$datos"
    :columns="$columnas"
/>
```

## 📝 Parámetros Disponibles

### Parámetros Principales
- `title` (string): Título principal de la tabla
- `description` (string): Descripción de la tabla
- `data` (array/collection): Datos a mostrar
- `columns` (array): Configuración de columnas
- `filters` (array): Filtros disponibles
- `searchPlaceholder` (string): Placeholder del campo de búsqueda
- `showSearch` (boolean): Mostrar campo de búsqueda (default: true)
- `showFilters` (boolean): Mostrar filtros (default: true)
- `showActions` (boolean): Mostrar columna de acciones (default: true)
- `actions` (array): Configuración de acciones

## 🏗️ Configuración de Columnas

### Tipos de Columna Disponibles

#### 1. **Avatar** (con subcampo)
```php
[
    'label' => 'Usuario',
    'field' => 'nombre',
    'type' => 'avatar',
    'subfield' => 'email',
    'subfield_label' => 'Email'
]
```

#### 2. **Badge** (con colores personalizados)
```php
[
    'label' => 'Estado',
    'field' => 'estado',
    'type' => 'badge',
    'colors' => [
        'activo' => 'bg-green-100 text-green-700 border-green-200',
        'inactivo' => 'bg-gray-100 text-gray-700 border-gray-200'
    ]
]
```

#### 3. **Date** (formato automático)
```php
[
    'label' => 'Fecha',
    'field' => 'created_at',
    'type' => 'date'
]
```

#### 4. **Texto Simple**
```php
[
    'label' => 'Nombre',
    'field' => 'nombre'
]
```

## 🔍 Configuración de Filtros

### Tipos de Filtro

#### 1. **Select**
```php
[
    'id' => 'categoria',
    'type' => 'select',
    'placeholder' => 'Categoría',
    'options' => [
        'electronica' => 'Electrónica',
        'ropa' => 'Ropa'
    ]
]
```

#### 2. **Input**
```php
[
    'id' => 'precio',
    'type' => 'input',
    'placeholder' => 'Precio mínimo'
]
```

## ⚡ Configuración de Acciones

```php
[
    'create' => [
        'label' => 'Nuevo Usuario',
        'color' => 'text-white',
        'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>'
    ],
    'view' => [
        'label' => 'Ver detalles',
        'color' => 'text-primary',
        'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>'
    ],
    'edit' => [
        'label' => 'Editar',
        'color' => 'text-blue-600',
        'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>'
    ],
    'delete' => [
        'label' => 'Eliminar',
        'color' => 'text-red-600',
        'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>'
    ]
]
```

## 📱 Responsive Design

- **Desktop (lg+)**: Tabla tradicional con todas las columnas
- **Mobile (< lg)**: Cards individuales con información principal
- **Breakpoints**: Utiliza los breakpoints de Tailwind configurados

## 🎨 Características

### ✅ Funcionalidades Incluidas
- ✅ Búsqueda en tiempo real
- ✅ Filtros dinámicos
- ✅ Botón limpiar filtros
- ✅ Contador de resultados
- ✅ Diseño responsive
- ✅ Hover effects
- ✅ Transiciones suaves
- ✅ Paginación básica
- ✅ Estado vacío elegante

### 🎯 Tipos de Columna Soportados
- ✅ Avatar con subcampo
- ✅ Badges con colores personalizados
- ✅ Fechas con formato automático
- ✅ Texto simple
- ✅ Campos personalizados

### 🔧 Filtros Soportados
- ✅ Select dropdown
- ✅ Input de texto
- ✅ Búsqueda general
- ✅ Combinación de filtros

## 📋 Ejemplos de Uso

### Ejemplo 1: Tabla de Usuarios
```blade
<x-data-table 
    title="Gestión de Usuarios"
    description="Administra y revisa los usuarios del sistema"
    :data="$users"
    :columns="[
        [
            'label' => 'Usuario',
            'field' => 'nombre',
            'type' => 'avatar',
            'subfield' => 'email',
            'subfield_label' => 'Email'
        ],
        [
            'label' => 'Rol',
            'field' => 'rol',
            'type' => 'badge',
            'colors' => [
                'admin' => 'bg-red-100 text-red-700 border-red-200',
                'user' => 'bg-blue-100 text-blue-700 border-blue-200'
            ]
        ],
        [
            'label' => 'Fecha Registro',
            'field' => 'created_at',
            'type' => 'date'
        ]
    ]"
    :filters="[
        [
            'id' => 'rol',
            'type' => 'select',
            'placeholder' => 'Rol',
            'options' => [
                'admin' => 'Administrador',
                'user' => 'Usuario'
            ]
        ]
    ]"
    searchPlaceholder="Buscar usuarios..."
    :actions="$actions"
/>
```

### Ejemplo 2: Tabla de Productos
```blade
<x-data-table 
    title="Catálogo de Productos"
    description="Gestiona el inventario de productos"
    :data="$products"
    :columns="[
        [
            'label' => 'Producto',
            'field' => 'nombre',
            'type' => 'avatar',
            'subfield' => 'codigo',
            'subfield_label' => 'Código'
        ],
        [
            'label' => 'Categoría',
            'field' => 'categoria',
            'type' => 'badge',
            'colors' => [
                'electronica' => 'bg-blue-100 text-blue-700 border-blue-200',
                'ropa' => 'bg-purple-100 text-purple-700 border-purple-200'
            ]
        ],
        [
            'label' => 'Precio',
            'field' => 'precio'
        ]
    ]"
    :filters="[
        [
            'id' => 'categoria',
            'type' => 'select',
            'placeholder' => 'Categoría',
            'options' => [
                'electronica' => 'Electrónica',
                'ropa' => 'Ropa'
            ]
        ],
        [
            'id' => 'precio',
            'type' => 'input',
            'placeholder' => 'Precio mínimo'
        ]
    ]"
    searchPlaceholder="Buscar productos..."
    :actions="$actions"
/>
```

## 🔧 Personalización

### Colores de Badges
Puedes personalizar los colores de los badges usando las clases de Tailwind:
- `bg-{color}-100 text-{color}-700 border-{color}-200`
- Colores disponibles: red, blue, green, yellow, purple, orange, pink, gray

### Iconos SVG
Los iconos se definen como paths SVG. Puedes usar cualquier icono de Heroicons o crear los tuyos propios.

### Estilos CSS
El componente usa Tailwind CSS. Puedes personalizar los estilos modificando las clases en el componente.

## 🚀 Ventajas del Componente

1. **Reutilizable**: Un solo componente para múltiples tablas
2. **Configurable**: Fácil personalización mediante parámetros
3. **Responsive**: Se adapta automáticamente a diferentes tamaños de pantalla
4. **Funcional**: Incluye búsqueda, filtros y acciones
5. **Elegante**: Diseño moderno y profesional
6. **Accesible**: Estructura semántica correcta
7. **Performance**: Filtrado en tiempo real sin recargas

## 📝 Notas Importantes

- Los datos deben ser una colección de Eloquent o un array
- Los campos deben existir en los modelos/arrays de datos
- Los filtros funcionan con búsqueda de texto en el contenido
- El componente es completamente independiente y no requiere JavaScript adicional 
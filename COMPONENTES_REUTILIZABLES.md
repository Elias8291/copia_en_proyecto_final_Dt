# 🧩 Guía de Componentes Reutilizables

## 📋 **Lista de Trámites**
Componente modular para mostrar listas de trámites con diferentes vistas.

### Uso Básico:
```blade
<x-tramites-lista 
    :tramites="$tramites"
    titulo="Mis Trámites"
    descripcion="Administra tus trámites pendientes"
    tipo-vista="tarjetas"
    :accion-principal="[
        'texto' => 'Crear Trámite',
        'url' => route('tramites.create'),
        'icono' => '<svg>...</svg>'
    ]"
/>
```

### Vistas Disponibles:
- `completa` - Tabla completa con filtros
- `simple` - Tabla básica sin filtros  
- `tarjetas` - Vista de tarjetas responsive

---

## 🏷️ **Status Badge**
Componente para mostrar estados con colores automáticos.

### Uso:
```blade
<x-status-badge estado="aprobado" />
<x-status-badge estado="pendiente" size="lg" />
<x-status-badge 
    estado="custom" 
    texto="Mi Estado"
    :custom-colors="['bg' => 'bg-purple-100', 'text' => 'text-purple-800']"
/>
```

### Estados Predefinidos:
- `pendiente`, `en_revision`, `en_proceso` → Amarillo
- `aprobado`, `activo`, `completado` → Verde  
- `rechazado`, `cancelado`, `inactivo` → Rojo
- `para_correccion` → Naranja
- `por_cotejar` → Azul

---

## 🔘 **Botones de Acción**
Componente versátil para botones con diferentes estilos y funciones.

### Ejemplos:
```blade
<!-- Botón básico -->
<x-action-button tipo="primary" url="{{ route('tramites.create') }}">
    Crear Trámite
</x-action-button>

<!-- Con ícono -->
<x-action-button 
    tipo="success" 
    :icono="'<svg>...</svg>'"
    onclick="aprobarTramite()">
    Aprobar
</x-action-button>

<!-- Para formularios -->
<x-action-button 
    tipo="danger" 
    method="DELETE"
    :url="route('tramites.destroy', $tramite)"
    onclick="return confirm('¿Estás seguro?')">
    Eliminar
</x-action-button>

<!-- Con loading -->
<x-action-button tipo="primary" :loading="true">
    Procesando...
</x-action-button>
```

### Tipos Disponibles:
- `primary`, `secondary`, `success`, `danger`, `warning`, `info`
- `outline-primary`, `outline-secondary`, `text-primary`, `text-danger`

---

## 📋 **Header de Sección**
Componente para headers consistentes en toda la aplicación.

### Uso:
```blade
<x-section-header 
    titulo="Gestión de Trámites"
    descripcion="Administra y revisa el estado de los trámites"
    color-icono="blue"
    :icono="'<svg>...</svg>'">
    
    <x-slot name="actions">
        <x-action-button tipo="primary" url="{{ route('tramites.create') }}">
            Nuevo Trámite
        </x-action-button>
    </x-slot>
    
    <!-- Contenido adicional opcional -->
    <p>Contenido adicional del header</p>
</x-section-header>
```

---

## 🔔 **Notificaciones**
Componente para alertas y mensajes de estado.

### Ejemplos:
```blade
<!-- Notificación básica -->
<x-notification tipo="success" mensaje="Trámite creado exitosamente" />

<!-- Con título y auto-hide -->
<x-notification 
    tipo="warning"
    titulo="Atención"
    mensaje="Revisa los datos antes de continuar"
    :auto-hide="true"
    :duration="3000"
/>

<!-- Con contenido personalizado -->
<x-notification tipo="info" titulo="Información Importante">
    <p>Este es un mensaje con <strong>HTML personalizado</strong>.</p>
    <ul class="mt-2 list-disc list-inside">
        <li>Item 1</li>
        <li>Item 2</li>
    </ul>
</x-notification>
```

---

## 📝 **Campos de Formulario**
Componente unificado para todos los tipos de campos.

### Ejemplos:
```blade
<!-- Input básico -->
<x-form-field 
    name="nombre"
    label="Nombre Completo"
    placeholder="Ingresa tu nombre"
    :required="true"
/>

<!-- Select -->
<x-form-field 
    name="estado"
    label="Estado"
    type="select"
    :options="['activo' => 'Activo', 'inactivo' => 'Inactivo']"
    :required="true"
/>

<!-- Textarea -->
<x-form-field 
    name="observaciones"
    label="Observaciones"
    type="textarea"
    :rows="4"
    help="Máximo 500 caracteres"
/>

<!-- Checkbox -->
<x-form-field 
    name="acepta_terminos"
    label="Acepto los términos y condiciones"
    type="checkbox"
    :required="true"
/>

<!-- File -->
<x-form-field 
    name="documento"
    label="Subir Documento"
    type="file"
    accept=".pdf,.doc,.docx"
/>

<!-- Con ícono -->
<x-form-field 
    name="email"
    label="Email"
    type="email"
    :icono="'<svg>...</svg>'"
    placeholder="usuario@ejemplo.com"
/>
```

---

## 🔄 **Componentes de Revisión**
Sistema completo de componentes para el módulo de revisiones.

### 📊 **Información del Trámite**:
```blade
<x-revision.info-tramite 
    :tramite="$tramite"
    :tipo-revision="$tipoRevision"
    :revision="$revision"
/>
```

### 📈 **Tarjetas de Estadísticas**:
```blade
<x-revision.estadisticas-card 
    :estadisticas="[
        'total' => ['valor' => 25, 'label' => 'Total', 'color' => 'gray'],
        'aprobados' => ['valor' => 15, 'label' => 'Aprobados', 'color' => 'green'],
        'rechazados' => ['valor' => 3, 'label' => 'Rechazados', 'color' => 'red']
    ]"
    layout="grid"
/>
```

### 📋 **Panel de Historial**:
```blade
<x-revision.historial-panel 
    :tramite="$tramite"
    :estadisticas-historial="$estadisticasHistorial"
    :historial-tramites="$historialTramites"
/>
```

### 📄 **Item de Historial**:
```blade
<x-revision.historial-item 
    :tramite-historico="$tramiteHistorico"
    :tramite-actual="$tramite"
/>
```

### 📦 **Wrapper de Sección**:
```blade
<x-revision.seccion-wrapper 
    seccion="datos_generales"
    titulo="Datos Generales"
>
    <!-- Contenido de la sección -->
    @include('components.forms.datos-generales')
    
    <x-slot name="cotejoContent">
        <!-- Contenido del cotejo -->
        <x-cotejo-selector />
    </x-slot>
</x-revision.seccion-wrapper>
```

### 🔧 **Área de Decisión**:
```blade
<x-revision.area-decision 
    seccion="datos_generales" 
    titulo="Decisión - Datos Generales"
    numero-seccion="Sección 1/6"
    placeholder="Agregar observaciones específicas..." 
/>
```

### ➖ **Separadores**:
```blade
<!-- Separador normal -->
<x-revision.separador />

<!-- Separador final -->
<x-revision.separador tipo="final" />

<!-- Con ícono personalizado -->
<x-revision.separador 
    tipo="normal"
    icono="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" 
/>
```

### ⚖️ **Panel de Decisión Final**:
```blade
<x-revision.panel-decision-final 
    :tramite="$tramite"
    :tipo-revision="$tipoRevision"
    :secciones="$secciones"
    :action-url="route('revisiones.procesar-digital', $tramite->id)"
/>
```

### 📊 **Resumen de Evaluaciones**:
```blade
<x-revision.resumen-evaluaciones :secciones="$secciones" />
```

### 🧭 **Navegación Flotante**:
```blade
<x-revision.navegacion-flotante position="bottom-right" />
```

---

## 🚀 **Ejemplos de Uso Combinado**

### Página de Lista con Filtros:
```blade
<x-section-header 
    titulo="Gestión de Trámites"
    descripcion="Administra y revisa trámites de proveedores"
    color-icono="blue">
    
    <x-slot name="actions">
        <x-action-button tipo="primary" url="{{ route('tramites.create') }}">
            Nuevo Trámite
        </x-action-button>
    </x-slot>
</x-section-header>

<x-tramites-lista 
    :tramites="$tramites"
    :mostrar-filtros="true"
    tipo-vista="completa"
/>
```

### Formulario con Notificación:
```blade
@if(session('success'))
    <x-notification 
        tipo="success" 
        :mensaje="session('success')"
        :auto-hide="true"
    />
@endif

<form action="{{ route('tramites.store') }}" method="POST">
    @csrf
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <x-form-field 
            name="nombre"
            label="Nombre"
            :value="old('nombre')"
            :required="true"
        />
        
        <x-form-field 
            name="tipo"
            label="Tipo de Trámite"
            type="select"
            :options="$tipos"
            :required="true"
        />
    </div>
    
    <div class="mt-6 flex gap-3">
        <x-action-button tipo="primary" type="submit">
            Guardar
        </x-action-button>
        
        <x-action-button tipo="secondary" url="{{ route('tramites.index') }}">
            Cancelar
        </x-action-button>
    </div>
</form>
```

---

## 💡 **Ventajas de estos Componentes**

✅ **Consistencia**: Mismo look & feel en toda la app  
✅ **Mantenibilidad**: Un cambio se aplica a todas las vistas  
✅ **Rapidez**: Desarrollo más rápido con menos código  
✅ **Flexibilidad**: Altamente configurables con props  
✅ **Accesibilidad**: Mejores prácticas incluidas  
✅ **Responsive**: Diseño móvil incluido  

---

## 🔧 **Personalización**

Puedes extender cualquier componente creando variantes:

```blade
<!-- resources/views/components/mi-boton-custom.blade.php -->
<x-action-button 
    tipo="primary"
    size="lg"
    :icono="'<svg>...</svg>'"
    {{ $attributes }}>
    {{ $slot }}
</x-action-button>
```

Y usarlo como:
```blade
<x-mi-boton-custom url="/mi-ruta">Mi Botón</x-mi-boton-custom>
``` 
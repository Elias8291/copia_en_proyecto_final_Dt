# Diseño Estandarizado de Botones de Revisión

## Resumen
Este documento detalla el diseño estandarizado de los botones de comentar, aprobar y rechazar en todas las secciones del sistema de revisión, así como los botones de decisión final.

## Componentes de Botones

### 1. Botones de Evaluación por Sección (`x-revision.botones-evaluacion`)

#### **Propósito**: Botones para aprobar/rechazar secciones individuales
#### **Ubicación**: `resources/views/components/revision/botones-evaluacion.blade.php`

#### **Características**:
- **Colores**: Verde (aprobado) y Rojo (rechazado)
- **Tamaño**: Responsivo con iconos SVG
- **Funcionalidad**: Evalúa secciones individuales
- **Props configurables**: `showIcons`, `textoAprobar`, `textoRechazar`, `size`

#### **Uso**:
```blade
<x-revision.botones-evaluacion 
    seccion="datos_generales"
    titulo="Sección"
    style="compact"
/>
```

### 2. Botones de Decisión Final (`x-revision.botones-decision-final`)

#### **Propósito**: Botones para decisiones finales del trámite
#### **Ubicación**: `resources/views/components/revision/botones-decision-final.blade.php`

#### **Características**:
- **Colores**: Verde (aprobar), Naranja (correcciones), Rojo (rechazar)
- **Diseño**: Compacto y centrado
- **Funcionalidad**: Decisiones finales con confirmación modal
- **Props configurables**: `showAprobar`, `showCorrecciones`, `showRechazar`, `layout`
- **Funcionalidad integrada**: El botón de aprobar automáticamente agenda una cita

#### **Botones Disponibles**:

##### **✅ Aprobar y Agendar Cita** (Verde - Emerald)
- **Texto**: "Aprobar y Agendar Cita"
- **Acción**: `agendar_cita`
- **Funcionalidad**: Aprueba el trámite y agenda automáticamente una cita presencial
- **Estado del trámite**: Se mantiene en "Revision_Digital"
- **Confirmación**: "¿Está seguro que desea aprobar este trámite y agendar una cita presencial?"

##### **⚠️ Rechazar y Para Corrección** (Naranja)
- **Texto**: "Rechazar y Para Corrección"
- **Acción**: `correcciones`
- **Funcionalidad**: Rechaza el trámite y lo envía para corrección
- **Estado del trámite**: "Para_Correccion"
- **Confirmación**: "¿Está seguro que desea rechazar este trámite y enviarlo para corrección?"

##### **❌ Rechazar Trámite** (Rojo)
- **Texto**: "Rechazar Trámite"
- **Acción**: `rechazado`
- **Funcionalidad**: Rechaza definitivamente el trámite
- **Estado del trámite**: "Rechazado"
- **Confirmación**: "¿Está seguro que desea rechazar este trámite?"

#### **Uso**:
```blade
<x-revision.botones-decision-final 
    :showAprobar="true"
    :showCorrecciones="true"
    :showRechazar="true"
    layout="grid"
    formId="formRevisionCompleta"
/>
```

## Estandarización de Colores

### **Paleta de Colores Intuitiva**:
- **🟢 Verde (Emerald)**: Aprobar/Aceptar
- **🟠 Naranja**: Correcciones/Advertencias
- **🔴 Rojo**: Rechazar/Cancelar

### **Especificaciones Técnicas**:
```css
/* Verde para aprobar */
.bg-emerald-600 hover:bg-emerald-700

/* Naranja para correcciones */
.bg-orange-500 hover:bg-orange-600

/* Rojo para rechazar */
.bg-red-500 hover:bg-red-600
```

## Diseño Responsivo

### **Layouts Disponibles**:
1. **Grid** (por defecto): `grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3` (3 botones)
2. **Flex**: `flex flex-col sm:flex-row`

### **Tamaños Responsivos**:
- **Móvil**: 1 columna
- **Tablet**: 2 columnas
- **Desktop**: 3 columnas (3 botones)

## Funcionalidades Integradas

### **1. Confirmaciones de Seguridad**
- **Modal de confirmación** antes de ejecutar acciones críticas
- **Mensajes específicos** para cada tipo de decisión
- **Prevención de errores** por clics accidentales

### **2. Agendamiento Automático de Citas**
- **Integrado en el botón de aprobar**: Al aprobar automáticamente se agenda una cita
- **Búsqueda automática** de revisores presenciales disponibles
- **Asignación inteligente** de horarios
- **Notificaciones automáticas** al solicitante y revisor

### **3. Estados del Trámite**
- **Aprobar y Agendar**: Mantiene estado en "Revision_Digital"
- **Correcciones**: Cambia a "Para_Correccion"
- **Rechazar**: Cambia a "Rechazado"

## Beneficios de la Estandarización

### **1. Consistencia Visual**
- Mismo diseño en todas las secciones
- Colores intuitivos y reconocibles
- Iconografía consistente

### **2. Experiencia de Usuario**
- Interfaz familiar y predecible
- Flujo de trabajo optimizado
- Reducción de errores

### **3. Mantenibilidad**
- Componentes reutilizables
- Fácil personalización
- Código centralizado

### **4. Funcionalidad Integrada**
- Agendamiento automático de citas
- Confirmaciones de seguridad
- Estados de trámite coherentes

### **5. Confirmaciones de Seguridad**
- Modal de confirmación antes de ejecutar acciones críticas
- Mensajes específicos para cada tipo de decisión
- Prevención de errores por clics accidentales

## Confirmaciones de Seguridad

### **Modal de Confirmación**
- **Componente**: `x-modal-confirmacion`
- **Funcionalidad**: Confirma acciones antes de ejecutarlas
- **Mensajes específicos** para cada decisión

### **Mensajes de Confirmación**:
- **Aprobar**: "¿Está seguro que desea aprobar este trámite y agendar una cita presencial?"
- **Correcciones**: "¿Está seguro que desea rechazar este trámite y enviarlo para corrección?"
- **Rechazar**: "¿Está seguro que desea rechazar este trámite?"

### **Implementación Técnica**:
```javascript
function confirmarDecision(decision, titulo, mensaje) {
    showConfirmModal(
        'Confirmar: ' + titulo,
        mensaje,
        'formRevisionCompleta',
        function() {
            // Ejecutar acción confirmada
            const form = document.getElementById('formRevisionCompleta');
            let hiddenField = form.querySelector('input[name="decision_final"]');
            if (!hiddenField) {
                hiddenField = document.createElement('input');
                hiddenField.type = 'hidden';
                hiddenField.name = 'decision_final';
                form.appendChild(hiddenField);
            }
            hiddenField.value = decision;
            form.submit();
        }
    );
}
```

## Agendamiento Automático de Citas

### **Funcionalidad Integrada**:
- **Botón único**: "Aprobar y Agendar Cita" combina ambas acciones
- **Proceso automático**: Al aprobar se agenda automáticamente la cita
- **Búsqueda inteligente**: Encuentra revisores y horarios disponibles
- **Notificaciones**: Envía notificaciones automáticas

### **Flujo de Agendamiento**:
1. Usuario hace clic en "Aprobar y Agendar Cita"
2. Sistema busca revisores presenciales disponibles
3. Sistema encuentra primer horario disponible
4. Sistema crea la cita automáticamente
5. Sistema mantiene estado en "Revision_Digital"
6. Sistema envía notificaciones

### **Ventajas**:
- **Simplicidad**: Un solo botón para dos acciones
- **Eficiencia**: Proceso automatizado
- **Consistencia**: Estado del trámite coherente
- **Experiencia**: Flujo de trabajo optimizado 
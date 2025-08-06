# Sistema de Revisión Digital con Comentarios por Sección

## Descripción
El sistema de revisión digital permite a los revisores evaluar cada sección de un trámite de forma independiente, agregando comentarios específicos y tomando decisiones de aprobación o rechazo por sección.

## Flujo de Funcionamiento

### 1. Frontend (JavaScript)
- **Archivo**: `public/js/revision-digital.js`
- **Función principal**: `evaluarSeccion(seccion, decision)`

Cuando un revisor hace clic en "Aprobar" o "Rechazar" en una sección:

1. Se captura el comentario del textarea correspondiente (`textarea_${seccion}`)
2. Se actualiza el campo oculto de decisión (`decision_${seccion}`)
3. Se actualiza el campo oculto de comentario (`comentario_${seccion}`)
4. Se actualiza la UI para mostrar el estado visualmente
5. Se muestra una notificación al usuario

### 2. Sincronización en Tiempo Real
- Los comentarios se sincronizan automáticamente mientras el usuario escribe
- Se garantiza que todos los comentarios se capturen antes del envío del formulario
- Funciones: `sincronizarComentario(seccion)`

### 3. Estructura del Formulario HTML
```html
<!-- Campos ocultos para cada sección -->
<input type="hidden" name="secciones[datos_generales][decision]" id="decision_datos_generales" value="Pendiente">
<input type="hidden" name="secciones[datos_generales][comentario]" id="comentario_datos_generales" value="">

<!-- Área de decisión por sección -->
<textarea id="textarea_datos_generales" placeholder="Comentarios..."></textarea>
<button onclick="evaluarSeccion('datos_generales', 'Aprobado')">Aprobar</button>
<button onclick="evaluarSeccion('datos_generales', 'Rechazado')">Rechazar</button>
```

### 4. Backend (Laravel)

#### Controlador
- **Archivo**: `app/Http/Controllers/RevisionController.php`
- **Método**: `procesarRevisionDigital(Request $request, Tramite $tramite)`

#### Servicio Principal
- **Archivo**: `app/Services/RevisionService.php`
- **Método**: `procesarRevisionDigital(Tramite $tramite, array $data)`

El servicio procesa:
1. Creación/actualización de la revisión del trámite
2. Procesamiento de secciones con comentarios
3. Procesamiento de archivos si aplica
4. Determinación del estado final
5. Actualización del trámite

#### Base de Datos

##### Tabla: `secciones_revision`
```sql
- id (Primary Key)
- tramite_id (Foreign Key)
- seccion (enum: 'datos_generales', 'actividades', 'domicilio', etc.)
- estado (enum: 'Pendiente', 'Aprobado', 'Rechazado')
- comentario (text, nullable)
- revisado_por (Foreign Key a users)
- timestamps
```

##### Tabla: `archivos`
```sql
- id (Primary Key)
- tramite_id (Foreign Key)
- status (enum: 'Pendiente', 'Aprobado', 'Rechazado')
- comentario_revision (text, nullable)
- revisado_por (Foreign Key a users)
- ... otros campos ...
```

## Secciones Evaluables

### Para Persona Física
- datos_generales
- actividades
- domicilio
- archivos

### Para Persona Moral
- datos_generales
- actividades
- domicilio
- constitucion
- accionistas
- apoderado
- archivos

## Estados Posibles

### Por Sección
- **Pendiente**: No se ha tomado ninguna decisión
- **Aprobado**: La sección cumple con los requisitos
- **Rechazado**: La sección tiene problemas que requieren corrección

### Del Trámite Completo
- **En_Revision**: Estado inicial o hay secciones pendientes
- **Aprobado**: Todas las secciones están aprobadas
- **Para_Correccion**: Al menos una sección fue rechazada
- **Por_Cotejar**: Se programó una cita para revisión presencial
- **Rechazado**: Rechazo total del trámite

## Ejemplo de Uso

1. El revisor abre un trámite en revisión digital
2. Revisa la sección "Datos Generales"
3. Escribe comentarios específicos en el textarea
4. Hace clic en "Aprobar Sección" o "Rechazar Sección"
5. Repite para todas las secciones necesarias
6. Al final, hace clic en "Aprobar", "Rechazar", "Agendar Cita" o "Correcciones"
7. El sistema procesa todas las decisiones y comentarios
8. Se actualiza el estado del trámite y se guarda el historial

## Ventajas del Sistema

1. **Granularidad**: Evaluación independiente por sección
2. **Trazabilidad**: Cada comentario y decisión queda registrada
3. **Flexibilidad**: Permite decisiones mixtas (aprobar unas secciones, rechazar otras)
4. **UX Mejorada**: Feedback inmediato y visual para el revisor
5. **Historial Completo**: Se mantiene registro de quién revisó qué y cuándo

## Debugging

El sistema incluye logs de consola para debugging:
- Se muestran los valores capturados al evaluar cada sección
- Se muestran todos los datos del formulario antes del envío
- Se pueden monitorear en DevTools del navegador

## Validaciones

- Se requiere evaluar al menos una sección antes de finalizar
- Los comentarios se sincronizan automáticamente
- Se valida la integridad de los datos en el backend 
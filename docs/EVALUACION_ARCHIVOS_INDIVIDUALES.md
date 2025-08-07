# Evaluación de Archivos Individuales en Revisión Digital

## Descripción
El sistema de revisión digital ahora permite evaluar cada archivo de forma individual, agregando comentarios específicos y tomando decisiones de aprobación o rechazo por archivo.

## Funcionalidades

### 1. Evaluación Individual por Archivo
- Cada archivo tiene su propio área de comentarios
- Botones individuales de "Aprobar" y "Rechazar" por archivo
- Estados visuales independientes para cada archivo
- Comentarios específicos por documento

### 2. Integración con Decisión Final
- Los archivos individuales se procesan cuando se hace clic en "Aprobar y Agendar"
- Todos los comentarios y decisiones se guardan en la base de datos
- Se mantiene un historial de revisiones por archivo

## Estructura del Código

### Frontend (JavaScript)

#### Funciones Principales:
```javascript
// Evaluar un archivo individual
function evaluarArchivo(archivoId, decision)

// Sincronizar comentarios en tiempo real
function sincronizarComentarioArchivo(archivoId)

// Mostrar notificación de evaluación
function mostrarNotificacionArchivo(archivoId, decision, comentario)
```

#### Estructura HTML:
```html
<!-- Textarea para comentarios -->
<textarea id="textarea_archivo_{archivoId}" 
          onchange="sincronizarComentarioArchivo('{archivoId}')">
</textarea>

<!-- Botones de decisión -->
<button onclick="evaluarArchivo('{archivoId}', 'Aprobado')">Aprobar</button>
<button onclick="evaluarArchivo('{archivoId}', 'Rechazado')">Rechazar</button>

<!-- Campos ocultos -->
<input type="hidden" name="archivos[{archivoId}][decision]" 
       id="decision_archivo_{archivoId}" value="Pendiente">
<input type="hidden" name="archivos[{archivoId}][comentario]" 
       id="comentario_archivo_{archivoId}" value="">

<!-- Estado visual -->
<span id="estado_archivo_{archivoId}">Pendiente</span>
```

### Backend (Laravel)

#### Servicio: `RevisionDigitalService`
```php
// Procesar archivos individuales
private function procesarArchivos(Tramite $tramite, array $archivos, User $usuario): void

// Cargar estados de archivos
public function cargarSeccionesEvaluadas(int $tramiteId): array
```

#### Modelo: `Archivo`
```php
// Campos relevantes
'status' => 'Pendiente|Aprobado|Rechazado'
'comentario_revision' => 'text'
'revisado_por' => 'user_id'
'fecha_revision' => 'datetime'
```

## Flujo de Funcionamiento

### 1. Evaluación Individual
1. El revisor revisa cada archivo
2. Escribe comentarios específicos en el textarea
3. Hace clic en "Aprobar" o "Rechazar"
4. Se actualiza el estado visual del archivo
5. Se sincronizan los comentarios en tiempo real

### 2. Procesamiento Final
1. El revisor hace clic en "Aprobar y Agendar"
2. Se envían todos los datos (secciones + archivos individuales)
3. El backend procesa cada archivo individual
4. Se actualizan los registros en la base de datos
5. Se determina el estado final del trámite

### 3. Carga de Estados Previos
1. Al cargar la página, se obtienen los estados vía AJAX
2. Se restauran los comentarios y decisiones previas
3. Se muestran los estados visuales correspondientes
4. Se indica qué archivos ya fueron evaluados

## Estados Visuales

### Archivo Pendiente
- Badge gris: "Pendiente"
- Sin indicador de evaluación

### Archivo Aprobado
- Badge verde: "Aprobado"
- Icono de check
- Comentarios cargados

### Archivo Rechazado
- Badge rojo: "Rechazado"
- Icono de check
- Comentarios cargados

## Ventajas

1. **Granularidad**: Evaluación específica por documento
2. **Trazabilidad**: Historial completo de revisiones
3. **Flexibilidad**: Comentarios detallados por archivo
4. **Consistencia**: Mismo flujo que las secciones
5. **Persistencia**: Estados se mantienen entre sesiones

## Consideraciones Técnicas

### Seguridad
- Validación de permisos por archivo
- Sanitización de comentarios
- Verificación de propiedad del trámite

### Rendimiento
- Carga asíncrona de estados
- Actualización en tiempo real
- Optimización de consultas

### Usabilidad
- Feedback visual inmediato
- Comentarios sincronizados
- Estados claros y consistentes 
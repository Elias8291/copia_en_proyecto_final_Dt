# Manual de Usuario - Sistema de Gestión de Trámites para Proveedores

## Tabla de Contenidos

1. [Introducción](#introducción)
2. [Roles de Usuario](#roles-de-usuario)
3. [Acceso al Sistema](#acceso-al-sistema)
4. [Dashboard Principal](#dashboard-principal)
5. [Gestión de Trámites](#gestión-de-trámites)
6. [Proceso de Revisión](#proceso-de-revisión)
7. [Gestión de Citas](#gestión-de-citas)
8. [Reportes y Exportaciones](#reportes-y-exportaciones)
9. [Notificaciones](#notificaciones)
10. [Preguntas Frecuentes](#preguntas-frecuentes)

---

## Introducción

El Sistema de Gestión de Trámites para Proveedores es una plataforma web diseñada para facilitar el proceso de inscripción, renovación y actualización de proveedores en el padrón oficial. El sistema automatiza los procesos de revisión y seguimiento de trámites, garantizando un flujo de trabajo eficiente y transparente.

### Características Principales

- ✅ **Gestión integral de trámites**: Inscripción, renovación y actualización
- ✅ **Múltiples tipos de revisión**: Digital, presencial y domiciliaria
- ✅ **Sistema de correcciones inteligente**: Solo se revisan las secciones modificadas
- ✅ **Agendamiento automático de citas**: Para revisiones presenciales y domiciliarias
- ✅ **Generación de reportes**: Exportación de datos en múltiples formatos
- ✅ **Sistema de notificaciones**: Seguimiento en tiempo real del estado de trámites
- ✅ **Validación automática de documentos**: Extracción de datos de constancias del SAT

---

## Roles de Usuario

El sistema maneja diferentes tipos de usuarios con permisos específicos:

### 👑 Super Administrador
- **Descripción**: Acceso total al sistema, gestión de usuarios y configuraciones
- **Permisos**: Control completo sobre todas las funcionalidades
- **Responsabilidades**: Configuración del sistema, gestión de usuarios, supervisión general

### ⚡ Administrador
- **Descripción**: Gestión general del sistema y supervisión de procesos
- **Permisos**: Gestión de usuarios, asignación de roles, supervisión de trámites
- **Responsabilidades**: Administración operativa, resolución de incidencias

### 💻 Revisor Digital
- **Descripción**: Especialista en revisión de documentos digitales y validaciones online
- **Permisos**: Revisión y evaluación de trámites en modalidad digital
- **Responsabilidades**: Validación de documentos, evaluación de secciones, decisiones de aprobación/rechazo

### 👥 Revisor Presencial
- **Descripción**: Especialista en cotejo presencial y validación física de documentos
- **Permisos**: Gestión de citas presenciales, cotejo de documentos físicos
- **Responsabilidades**: Verificación presencial, validación de documentos originales

### 🏠 Revisor Domiciliario
- **Descripción**: Especialista en verificaciones domiciliarias e inspecciones de campo
- **Permisos**: Gestión de citas domiciliarias, inspecciones in situ
- **Responsabilidades**: Verificación física de domicilios, inspecciones de campo

### 🏢 Proveedor
- **Descripción**: Empresa o persona física que solicita servicios y gestiona trámites
- **Permisos**: Creación y seguimiento de trámites propios
- **Responsabilidades**: Envío de documentación, seguimiento de trámites

### 👤 Solicitante
- **Descripción**: Usuario individual que realiza solicitudes específicas
- **Permisos**: Gestión de trámites personales
- **Responsabilidades**: Gestión de documentación personal

---

## Acceso al Sistema

### Registro de Nuevos Usuarios

1. **Acceder a la página de registro**
   - Navegar a `/registro`
   - Completar el formulario con los datos requeridos:
     - Nombre completo
     - Correo electrónico
     - RFC
     - Contraseña (mínimo 8 caracteres)
     - Confirmación de contraseña

2. **Verificación de correo electrónico**
   - Revisar el correo electrónico registrado
   - Hacer clic en el enlace de verificación recibido
   - El enlace tiene el formato: `/verificar-email/{id}/{token}`

3. **Activación de cuenta**
   - Una vez verificado el correo, la cuenta queda activa
   - Se puede proceder al inicio de sesión

### Inicio de Sesión

1. **Acceder a la página de login**
   - Navegar a `/iniciar-sesion`
   - Ingresar credenciales:
     - Correo electrónico
     - Contraseña

2. **Recuperación de contraseña** (si es necesario)
   - Hacer clic en "¿Olvidaste tu contraseña?"
   - Ingresar el correo electrónico registrado
   - Seguir las instrucciones del correo de recuperación

### Cierre de Sesión

- Hacer clic en el botón "Cerrar Sesión" en el menú principal
- La sesión se cerrará automáticamente por seguridad

---

## Dashboard Principal

El dashboard es la pantalla principal tras iniciar sesión y varía según el rol del usuario:

### Dashboard para Proveedores/Solicitantes
- **Resumen de trámites**: Estado actual de todos los trámites
- **Acciones rápidas**: Crear nuevo trámite, ver estado de trámites
- **Notificaciones recientes**: Actualizaciones importantes
- **Enlaces útiles**: Acceso directo a funciones principales

### Dashboard para Revisores
- **Cola de trabajo**: Trámites asignados para revisión
- **Estadísticas personales**: Trámites procesados, pendientes
- **Citas programadas**: Agenda de citas asignadas
- **Acciones rápidas**: Acceso a herramientas de revisión

### Dashboard para Administradores
- **Estadísticas generales**: Resumen del sistema completo
- **Gestión de usuarios**: Acceso rápido a administración de usuarios
- **Reportes ejecutivos**: Métricas y análisis del sistema
- **Configuración del sistema**: Acceso a configuraciones avanzadas

---

## Gestión de Trámites

### Tipos de Trámites

El sistema maneja tres tipos principales de trámites:

#### 1. 📝 Inscripción
- **Propósito**: Registro inicial de un proveedor en el padrón
- **Duración**: Válido por 3 años
- **Requisitos**: Documentación completa según el tipo de persona (física o moral)

#### 2. 🔄 Renovación
- **Propósito**: Renovación de la inscripción existente
- **Cuándo**: Antes del vencimiento de la inscripción actual
- **Proceso**: Actualización de datos y documentos

#### 3. ✏️ Actualización
- **Propósito**: Modificación de datos sin cambiar la vigencia
- **Cuándo**: Cambios en actividades, domicilio, o representantes legales
- **Proceso**: Actualización selectiva de secciones específicas

### Crear un Nuevo Trámite

#### Paso 1: Selección del Tipo de Trámite
1. Acceder a **Trámites > Crear Nuevo**
2. Seleccionar el tipo de trámite requerido
3. El sistema determinará los pasos necesarios según el tipo

#### Paso 2: Carga de Constancia del SAT (Opcional)
1. **Para agilizar el proceso**, se puede cargar la constancia de situación fiscal del SAT
2. El sistema extraerá automáticamente los datos básicos
3. **Formatos aceptados**: PDF
4. **Funcionalidad**: Extracción automática de QR y datos fiscales

#### Paso 3: Completar Formulario por Pasos

El formulario se divide en secciones según el tipo de persona:

##### Para Persona Física (5 pasos):
1. **Datos Generales**: Información básica del solicitante
2. **Actividades**: Actividades económicas a desarrollar
3. **Domicilio**: Dirección fiscal
4. **Documentos**: Carga de archivos requeridos
5. **Términos y Condiciones**: Aceptación final

##### Para Persona Moral (8 pasos):
1. **Datos Generales**: Información básica de la empresa
2. **Actividades**: Actividades económicas
3. **Domicilio**: Dirección fiscal
4. **Constitución**: Datos de constitución de la empresa
5. **Accionistas**: Información de accionistas
6. **Apoderado**: Datos del apoderado legal
7. **Documentos**: Carga de archivos requeridos
8. **Términos y Condiciones**: Aceptación final

#### Paso 4: Carga de Documentos

**Documentos Requeridos** (varían según el tipo de trámite):

**Para Persona Física:**
- RFC (Cédula de identificación fiscal)
- CURP
- Identificación oficial
- Comprobante de domicilio
- Constancia de situación fiscal

**Para Persona Moral:**
- Acta constitutiva
- RFC de la empresa
- Poder notarial del representante legal
- RFC del representante legal
- CURP del representante legal
- Identificación del representante legal
- Comprobante de domicilio fiscal

**Características de los archivos:**
- **Formatos aceptados**: PDF, JPG, PNG
- **Tamaño máximo**: 10MB por archivo
- **Calidad**: Documentos legibles y completos

#### Paso 5: Revisión y Envío
1. **Revisar** toda la información ingresada
2. **Verificar** que todos los documentos estén cargados
3. **Aceptar** términos y condiciones
4. **Enviar** el trámite para revisión

### Estados de Trámites

Los trámites pasan por diferentes estados durante su procesamiento:

#### 🔵 Pendiente
- **Descripción**: Trámite recién creado, en espera de asignación
- **Duración típica**: 1-2 días hábiles
- **Acciones del usuario**: Ninguna, esperar asignación

#### 💻 Revisión Digital
- **Descripción**: Trámite en proceso de revisión documental
- **Duración típica**: 3-5 días hábiles
- **Acciones del usuario**: Responder a solicitudes de corrección si es necesario

#### 👥 Revisión Presencial
- **Descripción**: Requiere cotejo presencial de documentos
- **Duración típica**: Según disponibilidad de citas
- **Acciones del usuario**: Asistir a la cita programada con documentos originales

#### 🏠 Revisión Domiciliaria
- **Descripción**: Requiere verificación del domicilio fiscal
- **Duración típica**: Según programación de inspecciones
- **Acciones del usuario**: Estar disponible en el domicilio fiscal

#### ✅ Aprobado
- **Descripción**: Trámite completado exitosamente
- **Resultado**: Proveedor activado en el padrón
- **Acciones del usuario**: Descargar documentos oficiales

#### ❌ Rechazado
- **Descripción**: Trámite rechazado definitivamente
- **Motivos**: Documentación insuficiente o irregularidades graves
- **Acciones del usuario**: Revisar observaciones y crear nuevo trámite

#### 🔶 Para Corrección
- **Descripción**: Requiere correcciones antes de continuar
- **Acciones del usuario**: Realizar las correcciones solicitadas

#### ⭕ Cancelado
- **Descripción**: Trámite cancelado por el usuario o por el sistema
- **Motivos**: Solicitud del usuario o vencimiento de plazos

### Seguimiento de Trámites

#### Consultar Estado de Trámites
1. **Acceder a**: Trámites > Ver Estado
2. **Información disponible**:
   - Estado actual del trámite
   - Fecha de última actualización
   - Observaciones del revisor
   - Próximos pasos
   - Historial completo

#### Mi Estado (Vista Personalizada)
- **Acceso**: Menú principal > Mi Estado
- **Funcionalidad**: Vista consolidada de todos los trámites del usuario
- **Filtros disponibles**: Por estado, por fecha, por tipo

---

## Proceso de Revisión

### Para Revisores Digitales

#### Acceso a la Cola de Trabajo
1. **Dashboard**: Ver trámites asignados
2. **Revisiones > Lista**: Acceder a todos los trámites disponibles
3. **Filtros**: Por estado, fecha, tipo de trámite

#### Proceso de Revisión Digital

##### Paso 1: Seleccionar Trámite
1. Hacer clic en "Revisar" en el trámite seleccionado
2. El sistema carga toda la información del trámite
3. Se muestran todas las secciones para evaluación

##### Paso 2: Evaluación por Secciones
El sistema presenta las siguientes secciones para evaluación:

1. **Datos Generales**
   - Verificar RFC, razón social, tipo de persona
   - Validar consistencia de datos
   - **Opciones**: Aprobar, Rechazar, Solicitar corrección

2. **Actividades Económicas**
   - Revisar actividades seleccionadas
   - Verificar compatibilidad y legalidad
   - **Opciones**: Aprobar, Rechazar, Solicitar corrección

3. **Domicilio Fiscal**
   - Validar dirección completa
   - Verificar código postal y localidad
   - **Opciones**: Aprobar, Rechazar, Solicitar corrección

4. **Documentos**
   - Revisar cada documento individualmente
   - Verificar legibilidad y vigencia
   - **Opciones**: Aprobar, Rechazar, Solicitar corrección

5. **Secciones Adicionales** (para Persona Moral):
   - Datos de constitución
   - Información de accionistas
   - Datos del apoderado legal

##### Paso 3: Evaluación Individual de Archivos
Para cada archivo el revisor puede:
- **Ver el documento**: Visualización en línea
- **Descargar**: Para revisión detallada
- **Evaluar**: Aprobar, rechazar o solicitar corrección
- **Comentar**: Agregar observaciones específicas

##### Paso 4: Decisión Final
Una vez evaluadas todas las secciones, el revisor tiene tres opciones:

1. **✅ Aprobar y Agendar Cita**
   - Todas las secciones están correctas
   - Se programa automáticamente cita presencial
   - El trámite pasa a "Revisión Presencial"

2. **🔶 Enviar para Corrección**
   - Algunas secciones requieren corrección
   - Solo las secciones con observaciones quedan pendientes
   - El trámite pasa a "Para Corrección"

3. **❌ Rechazar Completamente**
   - Irregularidades graves o documentación insuficiente
   - El trámite se marca como "Rechazado"
   - Se envían las observaciones al solicitante

#### Sistema de Correcciones Inteligente

**Característica clave**: Cuando se envía un trámite para corrección, **solo las secciones corregidas** cambian a estado "Pendiente", mientras que las secciones no modificadas mantienen su estado anterior.

**Beneficios**:
- Optimiza el tiempo de revisión
- Evita re-revisar secciones ya aprobadas
- Mejora la eficiencia del proceso

### Para Revisores Presenciales

#### Gestión de Citas
1. **Citas > Lista**: Ver citas asignadas
2. **Información disponible**:
   - Datos del proveedor
   - Documentos a cotejar
   - Horario y fecha
   - Estado de la cita

#### Proceso de Cotejo Presencial
1. **Verificar asistencia** del solicitante
2. **Cotejar documentos** originales vs. digitalizados
3. **Validar identidad** del representante legal
4. **Completar evaluación** en el sistema
5. **Tomar decisión final**:
   - Aprobar trámite
   - Rechazar por inconsistencias
   - Solicitar documentación adicional

### Para Revisores Domiciliarios

#### Gestión de Inspecciones
1. **Programar visita** al domicilio fiscal
2. **Verificar ubicación** y existencia del domicilio
3. **Validar actividad económica** en el lugar
4. **Documentar hallazgos** con fotografías
5. **Registrar resultado** en el sistema

---

## Gestión de Citas

### Tipos de Citas

#### 👥 Citas Presenciales
- **Propósito**: Cotejo de documentos originales
- **Duración**: 30-60 minutos
- **Ubicación**: Oficinas del organismo
- **Requisitos**: Asistir con documentos originales

#### 🏠 Citas Domiciliarias
- **Propósito**: Verificación del domicilio fiscal
- **Duración**: 45-90 minutos
- **Ubicación**: Domicilio fiscal del proveedor
- **Requisitos**: Estar disponible en el domicilio

### Agendamiento de Citas

#### Agendamiento Automático
- Se realiza cuando el revisor digital aprueba el trámite
- El sistema busca el primer horario disponible
- Se envía notificación automática al solicitante

#### Reagendamiento
Los usuarios pueden reagendar citas:
1. **Acceder a**: Citas > Mis Citas
2. **Seleccionar cita** a reagendar
3. **Elegir nuevo horario** de los disponibles
4. **Confirmar** el cambio

#### Estados de Citas

- **🔵 Asignada**: Cita programada, pendiente de confirmación
- **✅ Asistida**: El solicitante asistió a la cita
- **❌ No Asistió**: El solicitante no se presentó
- **⭕ Cancelada**: Cita cancelada por cualquier motivo

### Notificaciones de Citas

El sistema envía notificaciones automáticas:
- **Confirmación** al agendar
- **Recordatorio** 24 horas antes
- **Reagendamiento** cuando se modifica
- **Cancelación** si es necesario

---

## Reportes y Exportaciones

### Tipos de Reportes Disponibles

#### 📊 Dashboard Ejecutivo
- **Acceso**: Reportes > Dashboard Ejecutivo
- **Contenido**: Métricas generales del sistema
- **Formato**: Excel
- **Datos incluidos**:
  - Total de proveedores por estado
  - Trámites procesados por período
  - Estadísticas de aprobación/rechazo
  - Tiempos promedio de procesamiento

#### 🗺️ Reporte Geográfico
- **Acceso**: Reportes > Geográfico
- **Contenido**: Distribución geográfica de proveedores
- **Formato**: Excel con gráficos
- **Filtros**:
  - Por estado
  - Por municipio
  - Por período de registro

#### 💼 Reporte por Giro Económico
- **Acceso**: Reportes > Giro Económico
- **Contenido**: Clasificación por actividades económicas
- **Formato**: Excel
- **Análisis**:
  - Actividades más comunes
  - Distribución por sectores
  - Tendencias temporales

#### 📋 Lista de Contactos
- **Acceso**: Reportes > Lista de Contactos
- **Contenido**: Información de contacto de proveedores
- **Formato**: Excel
- **Uso**: Comunicaciones masivas, seguimiento

#### ⏰ Proveedores por Vencer
- **Acceso**: Reportes > Por Vencer
- **Contenido**: Proveedores próximos a vencer
- **Formato**: Excel
- **Filtros**:
  - Próximos 30 días
  - Próximos 60 días
  - Próximos 90 días

#### 📈 Reportes Trimestrales
- **Acceso**: Reportes > Trimestrales
- **Contenido**: Análisis estadístico por trimestres
- **Formato**: Excel con gráficos
- **Comparativas**: Entre trimestres y años

### Cómo Generar Reportes

1. **Acceder al módulo de reportes**
   - Menú principal > Reportes

2. **Seleccionar tipo de reporte**
   - Elegir el reporte deseado de la lista

3. **Configurar filtros** (si aplica)
   - Rango de fechas
   - Estados geográficos
   - Tipos de trámite
   - Estados de trámites

4. **Generar y descargar**
   - Hacer clic en "Generar Reporte"
   - El archivo se descargará automáticamente

### Reportes Personalizados

#### Filtrado Avanzado
Los usuarios pueden crear reportes personalizados:
- **Múltiples filtros**: Combinar diferentes criterios
- **Exportación flexible**: Seleccionar campos específicos
- **Formatos**: Excel, CSV
- **Programación**: Reportes automáticos (función administrativa)

---

## Notificaciones

### Tipos de Notificaciones

#### 🔔 Notificaciones del Sistema
- **Cambios de estado**: Cuando un trámite cambia de estado
- **Citas programadas**: Confirmación y recordatorios
- **Correcciones requeridas**: Cuando se solicitan correcciones
- **Aprobaciones**: Cuando un trámite es aprobado

#### 📧 Notificaciones por Correo
- **Registro exitoso**: Confirmación de registro
- **Verificación de cuenta**: Enlace de activación
- **Recuperación de contraseña**: Enlaces de restablecimiento
- **Estados de trámite**: Actualizaciones importantes

### Gestión de Notificaciones

#### Centro de Notificaciones
- **Acceso**: Icono de campana en la barra superior
- **Funciones**:
  - Ver notificaciones recientes
  - Marcar como leídas
  - Eliminar notificaciones
  - Filtrar por tipo

#### Configuración de Notificaciones
Los usuarios pueden configurar:
- **Frecuencia**: Inmediata, diaria, semanal
- **Canales**: Sistema, correo electrónico
- **Tipos**: Seleccionar qué notificaciones recibir

### Historial de Notificaciones

- **Acceso completo**: Notificaciones > Historial
- **Búsqueda**: Por fecha, tipo, estado
- **Exportación**: Lista de notificaciones en Excel
- **Limpieza automática**: Notificaciones antiguas se archivan automáticamente

---

## Preguntas Frecuentes

### Registro y Acceso

**P: ¿Cómo puedo registrarme en el sistema?**
R: Accede a la página de registro (`/registro`), completa el formulario con tus datos y verifica tu correo electrónico haciendo clic en el enlace que recibirás.

**P: No recibí el correo de verificación, ¿qué hago?**
R: Verifica tu carpeta de spam. Si no lo encuentras, puedes solicitar el reenvío del correo desde la página de login.

**P: Olvidé mi contraseña, ¿cómo la recupero?**
R: En la página de login, haz clic en "¿Olvidaste tu contraseña?", ingresa tu correo y sigue las instrucciones del correo de recuperación.

### Trámites

**P: ¿Qué documentos necesito para un trámite de inscripción?**
R: Los documentos varían según el tipo de persona:
- **Persona Física**: RFC, CURP, identificación oficial, comprobante de domicilio, constancia de situación fiscal
- **Persona Moral**: Acta constitutiva, RFC de la empresa, poder notarial, RFC del representante, CURP del representante, identificación del representante, comprobante de domicilio fiscal

**P: ¿Cuánto tiempo tarda en procesarse un trámite?**
R: Los tiempos varían según el tipo de revisión:
- **Revisión Digital**: 3-5 días hábiles
- **Revisión Presencial**: Según disponibilidad de citas (generalmente 1-2 semanas adicionales)
- **Revisión Domiciliaria**: Según programación de inspecciones (2-3 semanas adicionales)

**P: ¿Puedo corregir mi trámite después de enviarlo?**
R: Solo puedes realizar correcciones cuando el revisor te lo solicite. En ese caso, recibirás una notificación con las observaciones específicas y podrás editar únicamente las secciones que requieren corrección.

**P: ¿Qué significa "Sistema de Correcciones Inteligente"?**
R: Cuando realizas correcciones a tu trámite, solo las secciones que modificaste volverán a estado "Pendiente" para revisión. Las secciones que ya fueron aprobadas mantendrán su estado, optimizando el tiempo de procesamiento.

### Citas

**P: ¿Cómo puedo reagendar mi cita?**
R: Accede a "Citas > Mis Citas", selecciona la cita que deseas cambiar y elige un nuevo horario de los disponibles.

**P: ¿Qué debo llevar a mi cita presencial?**
R: Debes llevar todos los documentos originales que subiste al sistema para realizar el cotejo correspondiente.

**P: ¿Qué pasa si no puedo asistir a mi cita domiciliaria?**
R: Puedes reagendar la cita o contactar al revisor asignado para coordinar una nueva fecha.

### Documentos y Archivos

**P: ¿Qué formatos de archivo acepta el sistema?**
R: Se aceptan archivos en formato PDF, JPG y PNG, con un tamaño máximo de 10MB por archivo.

**P: ¿Puedo subir documentos después de enviar mi trámite?**
R: Solo puedes subir documentos adicionales cuando el revisor te lo solicite específicamente.

**P: ¿Cómo puedo asegurarme de que mis documentos son legibles?**
R: Asegúrate de que los documentos estén bien escaneados, con buena resolución y que todo el texto sea claramente visible.

### Estados y Seguimiento

**P: ¿Cómo puedo saber el estado actual de mi trámite?**
R: Puedes consultar el estado en "Trámites > Ver Estado" o en "Mi Estado" desde el menú principal.

**P: ¿Qué significa cada estado de trámite?**
R: 
- **Pendiente**: Recién creado, esperando asignación
- **Revisión Digital**: En proceso de revisión documental
- **Revisión Presencial**: Requiere cita para cotejo de documentos
- **Revisión Domiciliaria**: Requiere verificación del domicilio
- **Para Corrección**: Necesita correcciones antes de continuar
- **Aprobado**: Completado exitosamente
- **Rechazado**: No aprobado definitivamente

### Notificaciones

**P: ¿Cómo puedo activar/desactivar las notificaciones por correo?**
R: Accede a tu perfil de usuario y modifica las preferencias de notificación según tus necesidades.

**P: ¿Por qué no recibo notificaciones?**
R: Verifica que tu correo electrónico esté correctamente registrado y revisa tu carpeta de spam. También puedes verificar la configuración de notificaciones en tu perfil.

### Reportes

**P: ¿Quién puede generar reportes?**
R: Los reportes están disponibles principalmente para usuarios con roles administrativos y de supervisión. Los proveedores pueden acceder a reportes básicos de sus propios trámites.

**P: ¿Con qué frecuencia se actualizan los reportes?**
R: Los reportes se generan en tiempo real con los datos más actuales del sistema.

### Soporte Técnico

**P: ¿Qué hago si encuentro un error en el sistema?**
R: Contacta al administrador del sistema o reporta el problema a través de los canales oficiales de soporte.

**P: ¿El sistema está disponible 24/7?**
R: El sistema está diseñado para estar disponible continuamente, aunque puede haber ventanas de mantenimiento programado que se anunciarán con anticipación.

**P: ¿Puedo acceder al sistema desde mi teléfono móvil?**
R: Sí, el sistema es responsive y se adapta a dispositivos móviles, aunque algunas funciones complejas pueden ser más cómodas de usar en una computadora.

---

## Contacto y Soporte

Para soporte adicional o consultas específicas, contacta a:

- **Soporte Técnico**: [Información de contacto del soporte]
- **Administración**: [Información de contacto administrativo]
- **Documentación Adicional**: Consulta la carpeta `docs/` para documentación técnica específica

---

*Este manual se actualiza regularmente. Versión actual: 1.0*
*Fecha de última actualización: [Fecha actual]*

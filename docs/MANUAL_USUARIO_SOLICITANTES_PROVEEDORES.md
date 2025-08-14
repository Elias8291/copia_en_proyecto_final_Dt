# 📋 Manual de Usuario - Solicitantes y Proveedores

## 🎯 Introducción

Este manual está diseñado para guiar a **solicitantes** y **proveedores** en el proceso completo de registro y gestión de trámites en el Sistema de Padrón de Proveedores.

### Diferencias entre Roles

| **Rol** | **Descripción** | **Permisos Principales** |
|---------|-----------------|-------------------------|
| **Solicitante** | Usuario individual que realiza solicitudes específicas | • Crear trámites<br>• Ver sus propios trámites<br>• Subir documentos<br>• Ver notificaciones |
| **Proveedor** | Empresa o persona física que solicita servicios y gestiona trámites | • Crear trámites<br>• Ver sus propios trámites<br>• Subir documentos<br>• Descargar constancias<br>• Ver citas programadas |

---

## 🚀 Capítulo 1: Registro en el Sistema

### 1.1 Crear una Cuenta Nueva

1. **Acceder al formulario de registro**
   - Visita la página de registro del sistema
   - Haz clic en "Registrarse" o "Crear cuenta"

2. **Completar información básica**
   - **Email**: Ingresa tu correo electrónico válido
   - **Contraseña**: Mínimo 8 caracteres
   - **Confirmar contraseña**: Debe coincidir exactamente

3. **Información adicional del SAT (Opcional)**
   - Si tienes constancia de situación fiscal del SAT, puedes extraer los datos automáticamente
   - **RFC del SAT**: Se completará automáticamente si subes la constancia
   - **Nombre del SAT**: Se completará automáticamente
   - **Tipo de persona**: Física o Moral (automático según RFC)

4. **Verificación de email**
   - Recibirás un correo de verificación en tu bandeja de entrada
   - Haz clic en el enlace de verificación dentro de las 24 horas
   - Si no recibes el correo, revisa tu carpeta de spam

### 1.2 Casos Especiales

#### Usuario No Verificado Existente
Si intentas registrarte con un email que ya existe pero no está verificado:
- El sistema actualizará tus datos automáticamente
- Se enviará un nuevo correo de verificación
- Podrás usar la nueva contraseña una vez verificado

#### Validaciones del Registro
- **Email**: Debe ser único y tener formato válido
- **Contraseña**: Mínimo 8 caracteres, debe confirmarse
- **RFC**: Si se proporciona, debe tener formato válido (13 caracteres)
- **Documentos**: Formatos permitidos: PDF, PNG, JPG, JPEG (máx. 5MB)

---

## 📝 Capítulo 2: Crear un Nuevo Trámite

### 2.1 Tipos de Trámite Disponibles

| **Tipo** | **Descripción** | **Cuándo Usar** |
|----------|-----------------|-----------------|
| **Inscripción** | Registro inicial en el padrón | Primera vez que te registras como proveedor |
| **Renovación** | Actualizar registro existente | Cuando tu registro está próximo a vencer |
| **Actualización** | Modificar información existente | Cambios en datos, domicilio o actividades |

### 2.2 Proceso Paso a Paso

#### Paso 1: Datos Generales
**Información Requerida:**
- **Razón Social/Nombre**: Nombre completo o razón social de la empresa
- **RFC**: Registro Federal de Contribuyentes (formato: ABCD123456ABC)
- **Tipo de Persona**: Física o Moral (se determina automáticamente por el RFC)
- **CURP**: Solo para personas físicas (opcional pero recomendado)
- **Página Web**: URL completa (opcional)
- **Teléfono**: Número de contacto principal

**Información de Contacto:**
- **Nombre del Contacto**: Persona responsable
- **Cargo**: Puesto o función
- **Correo de Contacto**: Email específico para el trámite
- **Teléfono de Contacto**: Número directo del contacto

**💡 Consejos:**
- El RFC determina automáticamente si eres Persona Física o Moral
- Asegúrate de que el email de contacto esté activo
- El teléfono debe incluir código de área

#### Paso 2: Actividades Económicas
**Información Requerida:**
- **Actividades Principales**: Selecciona de la lista disponible
- **Descripción Detallada**: Explica específicamente qué servicios ofreces
- **Experiencia**: Años de experiencia en cada actividad

**💡 Consejos:**
- Puedes seleccionar múltiples actividades
- Sé específico en las descripciones
- La experiencia ayuda en la evaluación

#### Paso 3: Domicilio Fiscal
**Información Requerida:**
- **Calle**: Nombre de la calle
- **Entre calles**: Referencias de ubicación (opcional)
- **Número Exterior**: Obligatorio
- **Número Interior**: Solo si aplica
- **Colonia**: Selecciona de la lista según tu código postal
- **Código Postal**: 5 dígitos numéricos
- **Municipio**: Se completa automáticamente
- **Estado**: Selecciona de la lista
- **Coordenadas**: Latitud y longitud (opcional pero recomendado)

**💡 Consejos:**
- El código postal determina automáticamente las colonias disponibles
- Las coordenadas ayudan en verificaciones domiciliarias
- Verifica que toda la información coincida con tu comprobante de domicilio

### 2.3 Pasos Adicionales para Personas Morales

#### Paso 4: Datos de Constitución
**Información Requerida:**
- **Fecha de Constitución**: Cuando se constituyó la empresa
- **Notario**: Número y nombre del notario público
- **Número de Escritura**: Número del acta constitutiva
- **Fecha de Inscripción RPP**: Registro Público de la Propiedad
- **Folio Mercantil**: Número de folio en el registro

#### Paso 5: Accionistas
**Para cada accionista:**
- **Nombre Completo**
- **Porcentaje de Participación**: Debe sumar 100% entre todos
- **Nacionalidad**
- **RFC**: Si es mexicano

**💡 Consejos:**
- Los porcentajes deben sumar exactamente 100%
- Incluye todos los accionistas, sin importar el porcentaje

#### Paso 6: Apoderado Legal
**Información Requerida:**
- **Nombre Completo**
- **Cargo**
- **RFC**
- **Datos del Poder**: Notario, escritura, fecha

### 2.4 Documentos Requeridos

#### Para Personas Físicas:
- **Identificación Oficial**: INE o pasaporte vigente
- **Comprobante de Domicilio**: No mayor a 3 meses
- **RFC**: Constancia de situación fiscal
- **CURP**: Documento oficial
- **Comprobante de Actividad**: Facturas, contratos, etc.

#### Para Personas Morales:
**Documentos Básicos:**
- **Acta Constitutiva**: Escritura pública
- **RFC**: Constancia de situación fiscal
- **Comprobante de Domicilio**: Fiscal, no mayor a 3 meses
- **Identificación del Representante Legal**
- **Poder del Representante Legal**

**Documentos Adicionales:**
- **Estados Financieros**: Último ejercicio
- **Comprobante de Experiencia**: Contratos, facturas
- **Póliza de Seguro**: Si aplica según la actividad

#### Formatos Permitidos:
- **PDF**: Para documentos legales (máx. 100MB)
- **PNG/JPG**: Para imágenes (máx. 100MB)
- **MP4**: Para videos (máx. 100MB)
- **MP3**: Para audio (máx. 100MB)

**💡 Consejos:**
- Escanea documentos en alta calidad
- Asegúrate de que todos los textos sean legibles
- Los archivos deben estar completos (todas las hojas)
- Nombra tus archivos de manera descriptiva

### 2.5 Términos y Condiciones
**Paso Final:**
- Lee cuidadosamente todos los términos
- Acepta las condiciones del servicio
- Confirma que toda la información es correcta
- Envía el trámite

---

## 🔍 Capítulo 3: Seguimiento del Trámite

### 3.1 Estados del Trámite

| **Estado** | **Descripción** | **Acciones Disponibles** |
|------------|-----------------|---------------------------|
| **Pendiente** | Trámite recién enviado | • Ver detalles<br>• Esperar asignación |
| **Revisión Digital** | Documentos en revisión | • Ver progreso<br>• Esperar resultados |
| **Revisión Presencial** | Requiere cotejo presencial | • Agendar cita<br>• Preparar documentos originales |
| **Revisión Domiciliaria** | Inspección en sitio | • Coordinar visita<br>• Preparar instalaciones |
| **Para Corrección** | Requiere correcciones | • Ver observaciones<br>• Subir documentos corregidos |
| **Aprobado** | Trámite aprobado | • Descargar constancia<br>• Imprimir certificado |
| **Rechazado** | Trámite rechazado | • Ver motivos<br>• Crear nuevo trámite |

### 3.2 Notificaciones
- **Email**: Recibirás notificaciones en tu correo registrado
- **Sistema**: Revisa la sección de notificaciones en tu dashboard
- **SMS**: Para cambios de estado críticos (si proporcionaste teléfono)

### 3.3 Correcciones
Si tu trámite requiere correcciones:

1. **Revisar observaciones**: Lee cuidadosamente todos los comentarios
2. **Identificar archivos rechazados**: Se mostrarán marcados en rojo
3. **Corregir documentos**: Prepara nuevas versiones
4. **Subir archivos corregidos**: Solo los que fueron rechazados
5. **Enviar correcciones**: Confirma el reenvío

**💡 Consejos para Correcciones:**
- Lee todos los comentarios del revisor
- Corrige exactamente lo que se solicita
- Si tienes dudas, contacta al soporte técnico
- Mantén la calidad de imagen alta en nuevos escaneos

---

## 📅 Capítulo 4: Gestión de Citas

### 4.1 Cuándo se Requiere Cita
- **Revisión Presencial**: Cotejo de documentos originales
- **Revisión Domiciliaria**: Inspección de instalaciones

### 4.2 Agendar una Cita
1. **Recibir notificación**: Te informarán cuando necesites agendar
2. **Acceder al calendario**: Desde tu dashboard
3. **Seleccionar fecha y hora**: Entre las opciones disponibles
4. **Confirmar cita**: Recibirás confirmación por email

### 4.3 Preparación para la Cita

#### Revisión Presencial:
- **Documentos originales**: Lleva todos los documentos físicos
- **Identificación**: INE o pasaporte vigente
- **Comprobantes actualizados**: No mayores a 3 meses
- **Llegar puntual**: 10 minutos antes de la hora programada

#### Revisión Domiciliaria:
- **Instalaciones limpias**: Área de trabajo accesible
- **Documentos disponibles**: Originales y copias
- **Personal disponible**: Representante legal o autorizado
- **Equipos funcionando**: Si aplica según tu actividad

### 4.4 Reagendar o Cancelar
- **Reagendar**: Con al menos 24 horas de anticipación
- **Cancelar**: Solo en casos justificados
- **Penalizaciones**: Múltiples cancelaciones pueden afectar tu trámite

---

## ⚠️ Capítulo 5: Errores Comunes y Soluciones

### 5.1 Errores en el Registro

| **Error** | **Causa** | **Solución** |
|-----------|-----------|--------------|
| "Email ya registrado" | El correo ya existe y está verificado | Usa otro email o recupera tu contraseña |
| "RFC inválido" | Formato incorrecto | Verifica que tenga 13 caracteres exactos |
| "Contraseñas no coinciden" | Error al confirmar | Escribe cuidadosamente ambas contraseñas |

### 5.2 Errores en Formularios

| **Campo** | **Error Común** | **Formato Correcto** |
|-----------|-----------------|---------------------|
| **RFC** | Formato incorrecto | ABCD123456ABC (13 caracteres) |
| **CURP** | Formato incorrecto | ABCD123456HDFGHI01 (18 caracteres) |
| **Email** | Formato inválido | usuario@dominio.com |
| **Teléfono** | Solo números | 55-1234-5678 o 5512345678 |
| **Código Postal** | No son 5 dígitos | 12345 (exactamente 5 números) |

### 5.3 Errores en Archivos

| **Error** | **Causa** | **Solución** |
|-----------|-----------|--------------|
| "Archivo muy grande" | Supera 100MB | Comprimir o reducir calidad |
| "Formato no permitido" | Extensión incorrecta | Usar PDF, PNG, JPG, MP3, MP4 |
| "Archivo corrupto" | Archivo dañado | Volver a escanear o crear |
| "No se puede leer" | Mala calidad | Escanear en mayor resolución |

### 5.4 Problemas de Navegación

| **Problema** | **Causa** | **Solución** |
|--------------|-----------|--------------|
| No puedo avanzar de paso | Campos obligatorios vacíos | Revisar campos marcados con * |
| Se perdió mi información | Sesión expirada | Volver a llenar (usar borrador) |
| Botones no funcionan | Error de JavaScript | Refrescar página o cambiar navegador |

---

## 📞 Capítulo 6: Soporte y Contacto

### 6.1 Canales de Soporte
- **Mesa de Ayuda**: [Insertar teléfono]
- **Email de Soporte**: [Insertar email]
- **Chat en Línea**: Disponible en horario de oficina
- **Oficinas Físicas**: [Insertar direcciones]

### 6.2 Horarios de Atención
- **Lunes a Viernes**: 8:00 AM - 6:00 PM
- **Sábados**: 9:00 AM - 2:00 PM
- **Domingos**: Cerrado

### 6.3 Información para Proporcionar al Contactar Soporte
- **Número de trámite**: Si ya tienes uno asignado
- **RFC**: Tu registro federal de contribuyentes
- **Email registrado**: El que usaste para crear la cuenta
- **Descripción detallada**: Del problema que experimentas
- **Capturas de pantalla**: Si es posible

### 6.4 Preguntas Frecuentes

**P: ¿Cuánto tiempo tarda la revisión de mi trámite?**
R: El tiempo varía según el tipo de revisión:
- Revisión Digital: 5-10 días hábiles
- Revisión Presencial: 15-20 días hábiles
- Revisión Domiciliaria: 20-30 días hábiles

**P: ¿Puedo modificar mi trámite después de enviarlo?**
R: No puedes modificar un trámite enviado, pero puedes crear un trámite de actualización.

**P: ¿Qué pasa si mi trámite es rechazado?**
R: Puedes crear un nuevo trámite incorporando las correcciones sugeridas.

**P: ¿Cómo descargo mi constancia?**
R: Una vez aprobado, encontrarás el botón "Descargar Constancia" en tu dashboard.

---

## 📋 Capítulo 7: Lista de Verificación

### 7.1 Antes de Enviar tu Trámite

#### Información Personal ✅
- [ ] Nombre completo correcto
- [ ] RFC con formato válido
- [ ] Email activo y verificado
- [ ] Teléfonos actualizados
- [ ] Dirección fiscal completa

#### Documentos ✅
- [ ] Todos los archivos requeridos subidos
- [ ] Documentos legibles y completos
- [ ] Formatos correctos (PDF, PNG, JPG)
- [ ] Tamaños dentro del límite
- [ ] Nombres descriptivos de archivos

#### Información Específica ✅
**Para Personas Físicas:**
- [ ] CURP (si aplica)
- [ ] Actividades económicas seleccionadas
- [ ] Experiencia documentada

**Para Personas Morales:**
- [ ] Datos constitutivos completos
- [ ] Lista de accionistas (suma 100%)
- [ ] Información del apoderado legal
- [ ] Estados financieros actualizados

### 7.2 Después de Enviar tu Trámite

#### Seguimiento ✅
- [ ] Número de trámite guardado
- [ ] Notificaciones de email activadas
- [ ] Dashboard revisado regularmente
- [ ] Documentos originales organizados

#### Preparación para Revisiones ✅
- [ ] Documentos físicos disponibles
- [ ] Instalaciones preparadas (si aplica)
- [ ] Personal autorizado identificado
- [ ] Horarios de disponibilidad definidos

---

## 🎯 Conclusión

Este manual te proporciona toda la información necesaria para completar exitosamente tu proceso de registro y gestión de trámites como solicitante o proveedor. Recuerda:

1. **Preparación es clave**: Revisa todos los requisitos antes de comenzar
2. **Documentación completa**: Asegúrate de tener todos los documentos necesarios
3. **Información precisa**: Verifica todos los datos antes de enviar
4. **Seguimiento activo**: Mantente al pendiente del estado de tu trámite
5. **Comunicación**: No dudes en contactar al soporte si necesitas ayuda

### Recursos Adicionales
- **Catálogo de Actividades**: Lista completa de actividades económicas
- **Formatos de Documentos**: Plantillas y ejemplos
- **Calculadora de Tiempos**: Estimaciones de duración por tipo de trámite
- **Glosario de Términos**: Definiciones de conceptos técnicos

---

*Versión: 1.0*  
*Fecha: Diciembre 2024*  
*Sistema de Padrón de Proveedores*

---

## 📄 Anexos

### Anexo A: Formatos de Documentos Válidos

| **Tipo** | **Extensiones** | **Tamaño Máximo** | **Uso Recomendado** |
|----------|-----------------|-------------------|---------------------|
| **PDF** | .pdf | 100MB | Documentos legales, actas, contratos |
| **Imagen** | .png, .jpg, .jpeg, .gif, .webp | 100MB | Identificaciones, comprobantes |
| **Audio** | .mp3, .wav, .ogg | 100MB | Declaraciones, testimonios |
| **Video** | .mp4, .avi, .mov, .wmv, .flv, .webm | 100MB | Presentaciones, instalaciones |

### Anexo B: Códigos de Error Comunes

| **Código** | **Descripción** | **Acción Requerida** |
|------------|-----------------|---------------------|
| **E001** | RFC inválido | Verificar formato de 13 caracteres |
| **E002** | Email duplicado | Usar email diferente o recuperar cuenta |
| **E003** | Archivo muy grande | Comprimir archivo a menos de 100MB |
| **E004** | Formato no soportado | Usar formatos permitidos |
| **E005** | Campos obligatorios vacíos | Completar todos los campos marcados con * |

### Anexo C: Plantillas de Documentos

#### Carta de Experiencia Laboral
```
[Membrete de la empresa]

A QUIEN CORRESPONDA:

Por medio de la presente, hacemos constar que [NOMBRE COMPLETO] 
con RFC [RFC] ha prestado servicios de [DESCRIPCIÓN DE SERVICIOS] 
durante el período comprendido del [FECHA INICIO] al [FECHA FIN].

Durante este tiempo demostró [COMPETENCIAS Y HABILIDADES].

Se extiende la presente para los fines que al interesado convengan.

Atentamente,
[NOMBRE Y FIRMA DEL RESPONSABLE]
[CARGO]
[FECHA]
```

#### Lista de Verificación de Documentos Persona Física
- [ ] INE o Pasaporte (vigente)
- [ ] Comprobante de domicilio (máx. 3 meses)
- [ ] Constancia de situación fiscal (RFC)
- [ ] CURP
- [ ] Comprobante de experiencia
- [ ] Estados de cuenta bancarios (3 meses)
- [ ] Póliza de seguro (si aplica)

#### Lista de Verificación de Documentos Persona Moral
- [ ] Acta constitutiva
- [ ] Constancia de situación fiscal
- [ ] Comprobante de domicilio fiscal
- [ ] Identificación del representante legal
- [ ] Poder del representante legal
- [ ] Estados financieros del último ejercicio
- [ ] Relación de accionistas actualizada
- [ ] Comprobantes de experiencia
- [ ] Pólizas de seguro vigentes

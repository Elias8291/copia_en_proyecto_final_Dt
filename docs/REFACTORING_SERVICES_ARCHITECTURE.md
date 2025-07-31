# 🏗️ Plan de Refactoring - Arquitectura de Servicios

## 📋 Resumen Ejecutivo

Este documento detalla el plan completo para refactorizar la arquitectura de servicios del proyecto, separando responsabilidades, eliminando duplicación de código y creando una estructura más mantenible y escalable.

## 🎯 Objetivos del Refactoring

### ✅ Objetivos Principales
1. **Eliminar duplicación de código** (especialmente RFC y validaciones)
2. **Separar responsabilidades** claramente
3. **Reducir complejidad** de servicios grandes
4. **Mejorar testabilidad** de componentes
5. **Facilitar mantenimiento** y escalabilidad
6. **Preparar para migración a React**

### 📊 Métricas de Éxito
- Reducir TramiteService de 401 a ~150 líneas
- Eliminar 80% de código duplicado
- Aumentar cobertura de tests al 90%
- Reducir tiempo de desarrollo en 40%

## 🏗️ Nueva Arquitectura de Servicios

### **Estructura Final Objetivo**

```
app/Services/
├── Core/                          # 🎯 SERVICIOS UTILITARIOS GLOBALES
│   ├── RfcService.php            # Todo lo relacionado con RFC
│   ├── ValidationService.php     # Validaciones comunes
│   ├── PersonaService.php        # Lógica de tipo de persona
│   ├── FileService.php           # Manejo de archivos
│   ├── NotificationService.php   # Notificaciones
│   └── BaseService.php           # Servicio base con métodos comunes
├── Tramite/                       # 🎯 LÓGICA DE TRÁMITES
│   ├── TramiteService.php        # Servicio principal de trámites
│   ├── RevisionService.php       # Lógica de revisiones
│   ├── CitaService.php           # Lógica de citas
│   └── TramiteStateService.php   # Manejo de estados
├── Proveedor/                     # 🎯 LÓGICA DE PROVEEDORES
│   ├── ProveedorService.php      # Servicio principal
│   ├── ProveedorDataService.php  # Datos de proveedores
│   └── ProveedorValidationService.php # Validaciones específicas
├── Formularios/                   # 🎯 PROCESAMIENTO DE FORMULARIOS
│   ├── BaseFormService.php       # Servicio base para formularios
│   ├── DatosGeneralesFormService.php
│   ├── DireccionFormService.php
│   ├── DatosConstitutivosFormService.php
│   ├── ApoderadoLegalFormService.php
│   ├── AccionistasFormService.php
│   ├── ActividadesFormService.php
│   └── DocumentosFormService.php
└── Legacy/                        # 🎯 SERVICIOS EXISTENTES (migrar gradualmente)
    ├── TramiteService.php        # Versión actual (eliminar después)
    ├── ProveedorService.php      # Versión actual (eliminar después)
    └── ...
```

## 📋 Mapeo Detallado de Métodos

### **1. RfcService.php (NUEVO)**

#### **Métodos a Extraer:**
| Método Actual | Ubicación | Nuevo Método | Descripción |
|---------------|-----------|---------------|-------------|
| `normalizarRfc()` | TramiteService:385 | `normalizarRfc()` | Normaliza RFC a formato estándar |
| `esPersonaMoral()` | TramiteService:394 | `esPersonaMoral()` | Determina si RFC es persona moral |
| `validarRfcConstancia()` | TramiteService:214 | `validarRfcConstancia()` | Valida RFC de constancia SAT |
| Validaciones RFC | TramiteFormularioRequest | `validarFormatoRfc()` | Validación de formato RFC |
| Validaciones RFC | UserRequest | `validarRfcUnico()` | Validación de RFC único |

#### **Métodos Nuevos a Agregar:**
```php
class RfcService
{
    public function normalizarRfc(?string $rfc): string
    public function validarFormatoRfc(string $rfc): bool
    public function esPersonaMoral(string $rfc): bool
    public function esPersonaFisica(string $rfc): bool
    public function obtenerTipoPersona(string $rfc): string
    public function generarRfcTemporal(): string
    public function validarRfcUnico(string $rfc, ?int $excludeId = null): bool
    public function validarRfcConstancia(array $datosSat): void
    public function extraerRfcDeDatosSat(array $datosSat): string
}
```

### **2. ValidationService.php (NUEVO)**

#### **Métodos a Extraer:**
| Método Actual | Ubicación | Nuevo Método | Descripción |
|---------------|-----------|---------------|-------------|
| Validaciones email | Múltiples requests | `validarEmail()` | Validación de email |
| Validaciones teléfono | Múltiples requests | `validarTelefono()` | Validación de teléfono |
| Validaciones CP | Múltiples requests | `validarCodigoPostal()` | Validación de CP |
| Validaciones CURP | TramiteFormularioRequest | `validarCurp()` | Validación de CURP |

#### **Métodos Nuevos a Agregar:**
```php
class ValidationService
{
    public function validarEmail(string $email): bool
    public function validarTelefono(string $telefono): bool
    public function validarCodigoPostal(string $cp): bool
    public function validarCurp(string $curp): bool
    public function validarRazonSocial(string $razonSocial): bool
    public function validarPorcentaje(float $porcentaje): bool
    public function validarFechaConstitucion(string $fecha): bool
    public function validarNumeroEscritura(string $numero): bool
}
```

### **3. PersonaService.php (NUEVO)**

#### **Métodos a Extraer:**
| Método Actual | Ubicación | Nuevo Método | Descripción |
|---------------|-----------|---------------|-------------|
| `determinarTramitesDisponibles()` | ProveedorService:186 | `determinarTramitesDisponibles()` | Determina trámites disponibles |
| `calcularTipoPersonaPorRfc()` | ProveedorService:297 | `calcularTipoPersonaPorRfc()` | Calcula tipo por RFC |
| `getTipoPersona()` | ProveedorService:309 | `getTipoPersona()` | Obtiene tipo de persona |

#### **Métodos Nuevos a Agregar:**
```php
class PersonaService
{
    public function __construct(
        private RfcService $rfcService
    ) {}
    
    public function determinarTipoPersona(string $rfc): string
    public function obtenerCamposRequeridos(string $tipoPersona): array
    public function validarCamposPorTipo(array $data, string $tipoPersona): array
    public function procesarDatosPorTipo(array $data, string $tipoPersona): array
    public function determinarTramitesDisponibles(?Proveedor $proveedor): array
    public function calcularTipoPersonaPorRfc(?Proveedor $proveedor): ?string
    public function getTipoPersona(?Proveedor $proveedor): ?string
}
```

### **4. FileService.php (NUEVO)**

#### **Métodos a Extraer:**
| Método Actual | Ubicación | Nuevo Método | Descripción |
|---------------|-----------|---------------|-------------|
| Subida archivos | DocumentosService | `subirArchivo()` | Sube archivo al servidor |
| Validación tipos | DocumentosService | `validarTipoArchivo()` | Valida tipo de archivo |
| Generación nombres | DocumentosService | `generarNombreArchivo()` | Genera nombre único |

#### **Métodos Nuevos a Agregar:**
```php
class FileService
{
    public function subirArchivo($file, string $path): string
    public function validarTipoArchivo($file, array $tiposPermitidos): bool
    public function generarNombreArchivo(string $originalName): string
    public function eliminarArchivo(string $path): bool
    public function obtenerUrlArchivo(string $path): string
    public function validarTamanoArchivo($file, int $maxSize): bool
}
```

## 🔄 Plan de Migración por Fases

### **FASE 1: Crear Servicios Core (Semana 1)**

#### **Día 1-2: RfcService**
```bash
# Crear app/Services/Core/RfcService.php
# Extraer métodos de TramiteService
# Actualizar dependencias
# Crear tests unitarios
```

#### **Día 3-4: ValidationService**
```bash
# Crear app/Services/Core/ValidationService.php
# Extraer validaciones de requests
# Crear tests unitarios
```

#### **Día 5-7: PersonaService**
```bash
# Crear app/Services/Core/PersonaService.php
# Extraer métodos de ProveedorService
# Crear tests unitarios
```

### **FASE 2: Refactorizar Servicios Existentes (Semana 2)**

#### **Día 1-3: TramiteService**
```bash
# Simplificar TramiteService usando servicios core
# Reducir de 401 a ~150 líneas
# Actualizar tests
```

#### **Día 4-5: ProveedorService**
```bash
# Simplificar ProveedorService usando servicios core
# Eliminar métodos duplicados
# Actualizar tests
```

#### **Día 6-7: Formularios**
```bash
# Crear BaseFormService
# Refactorizar servicios de formularios
# Actualizar tests
```

### **FASE 3: Actualizar Controllers y Requests (Semana 3)**

#### **Día 1-3: Controllers**
```bash
# Inyectar nuevos servicios en controllers
# Simplificar lógica de controllers
# Actualizar tests
```

#### **Día 4-5: Requests**
```bash
# Usar servicios core en requests
# Simplificar validaciones
# Actualizar tests
```

#### **Día 6-7: Testing**
```bash
# Ejecutar tests completos
# Corregir errores
# Optimizar performance
```

### **FASE 4: Limpieza y Optimización (Semana 4)**

#### **Día 1-3: Limpieza**
```bash
# Eliminar código duplicado
# Optimizar imports
# Limpiar archivos no usados
```

#### **Día 4-5: Documentación**
```bash
# Actualizar documentación
# Crear ejemplos de uso
# Documentar APIs
```

#### **Día 6-7: Validación Final**
```bash
# Testing completo
# Code review
# Deploy a staging
```

## 📊 Métricas de Progreso

### **Antes del Refactoring:**
- TramiteService: 401 líneas
- ProveedorService: 494 líneas
- Código duplicado: ~30%
- Tests: ~60% cobertura

### **Después del Refactoring:**
- TramiteService: ~150 líneas
- ProveedorService: ~200 líneas
- Código duplicado: <5%
- Tests: >90% cobertura

## 🧪 Estrategia de Testing

### **Tests Unitarios por Servicio:**

#### **RfcService Tests:**
```php
class RfcServiceTest extends TestCase
{
    public function test_normalizar_rfc()
    public function test_es_persona_moral()
    public function test_validar_formato_rfc()
    public function test_generar_rfc_temporal()
    public function test_validar_rfc_unico()
}
```

#### **ValidationService Tests:**
```php
class ValidationServiceTest extends TestCase
{
    public function test_validar_email()
    public function test_validar_telefono()
    public function test_validar_codigo_postal()
    public function test_validar_curp()
}
```

#### **PersonaService Tests:**
```php
class PersonaServiceTest extends TestCase
{
    public function test_determinar_tipo_persona()
    public function test_obtener_campos_requeridos()
    public function test_validar_campos_por_tipo()
}
```

## 🚨 Riesgos y Mitigaciones

### **Riesgos Identificados:**

#### **1. Breaking Changes**
- **Riesgo:** Cambios que rompan funcionalidad existente
- **Mitigación:** Tests exhaustivos, migración gradual, rollback plan

#### **2. Performance**
- **Riesgo:** Overhead por inyección de dependencias
- **Mitigación:** Cache de servicios, lazy loading, profiling

#### **3. Complejidad**
- **Riesgo:** Aumento de complejidad por más servicios
- **Mitigación:** Documentación clara, ejemplos de uso, training

### **Plan de Rollback:**
```bash
# Si algo sale mal, revertir a versión anterior
git checkout main
git revert <commit-hash>
```

## 📈 Beneficios Esperados

### **Corto Plazo (1 mes):**
- ✅ Código más limpio y mantenible
- ✅ Eliminación de duplicación
- ✅ Mejor testabilidad
- ✅ Reducción de bugs

### **Mediano Plazo (3 meses):**
- ✅ Facilita migración a React
- ✅ Mejor performance
- ✅ Escalabilidad mejorada
- ✅ Desarrollo más rápido

### **Largo Plazo (6 meses):**
- ✅ Arquitectura sólida
- ✅ Fácil agregar nuevas funcionalidades
- ✅ Mantenimiento reducido
- ✅ Team productivity aumentada

## 🎯 Criterios de Éxito

### **Técnicos:**
- [ ] TramiteService reducido a <150 líneas
- [ ] Código duplicado <5%
- [ ] Cobertura de tests >90%
- [ ] 0 breaking changes en producción

### **Funcionales:**
- [ ] Todas las funcionalidades existentes funcionan
- [ ] Performance no degradada
- [ ] APIs mantienen compatibilidad
- [ ] UX no afectada

### **Organizacionales:**
- [ ] Documentación actualizada
- [ ] Team capacitado en nueva arquitectura
- [ ] Proceso de desarrollo optimizado
- [ ] Preparado para migración a React

## 📞 Próximos Pasos

1. **Revisar y aprobar este plan**
2. **Crear branch de desarrollo**
3. **Empezar con Fase 1 (RfcService)**
4. **Establecer métricas de seguimiento**
5. **Programar code reviews semanales**

---

**Documento creado:** [Fecha]
**Última actualización:** [Fecha]
**Responsable:** [Nombre]
**Estado:** Pendiente de aprobación 
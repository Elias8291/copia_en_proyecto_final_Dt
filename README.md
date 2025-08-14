# Sistema de Gestión de Proveedores y Trámites

## 📋 Descripción

Sistema web desarrollado en Laravel 11 para la gestión integral de proveedores y trámites de inscripción, revisión y validación de documentos empresariales. Permite el control completo de flujos de trabajo, asignación de revisores, gestión de citas y generación de reportes.

## 🚀 Características Principales

- **Gestión de Usuarios**: Sistema de roles y permisos granulares
- **Gestión de Proveedores**: Registro y validación de empresas
- **Trámites**: Flujo completo de solicitudes y revisiones
- **Revisiones**: Digital, presencial y domiciliaria
- **Citas**: Programación y gestión de citas
- **Reportes**: Generación de reportes y exportaciones
- **Notificaciones**: Sistema de alertas y comunicaciones
- **API REST**: Interfaz de programación completa
- **Responsive Design**: Interfaz adaptativa para móviles

## 🛠️ Stack Tecnológico

### Backend
- **Framework**: Laravel 11.x
- **PHP**: 8.2+
- **Base de Datos**: MySQL 8.0+ / PostgreSQL 13+
- **Cache**: Redis 6.0+
- **Autenticación**: Laravel Sanctum + Spatie Permissions

### Frontend
- **Templates**: Blade
- **CSS**: Tailwind CSS 3.4+
- **JavaScript**: Alpine.js
- **Componentes**: Preline UI
- **Build Tool**: Vite

### Dependencias Principales
- **Laravel Sanctum**: Autenticación API
- **Spatie Permissions**: Gestión de roles y permisos
- **Laravel Excel**: Exportación de datos
- **mPDF**: Generación de PDFs
- **QR Code**: Generación de códigos QR

## 📁 Estructura del Proyecto

```
📁 Sistema de Gestión de Proveedores
├── 📁 app/                    # Lógica de aplicación
│   ├── 📁 Auth/              # Autenticación personalizada
│   ├── 📁 Console/           # Comandos Artisan
│   ├── 📁 Enums/             # Enumeraciones del sistema
│   ├── 📁 Events/            # Eventos del sistema
│   ├── 📁 Exports/           # Exportaciones Excel/PDF
│   ├── 📁 Helpers/           # Funciones auxiliares
│   ├── 📁 Http/              # Controladores y Middleware
│   ├── 📁 Jobs/              # Tareas en cola
│   ├── 📁 Mail/              # Plantillas de correo
│   ├── 📁 Models/            # Modelos Eloquent
│   ├── 📁 Policies/          # Políticas de autorización
│   ├── 📁 Providers/         # Proveedores de servicios
│   ├── 📁 Services/          # Lógica de negocio
│   ├── 📁 Traits/            # Traits reutilizables
│   └── 📁 ViewModels/        # View Models
├── 📁 config/                # Configuraciones
├── 📁 database/              # Migraciones y seeders
├── 📁 public/                # Archivos públicos
├── 📁 resources/             # Recursos (vistas, assets)
├── 📁 routes/                # Definición de rutas
├── 📁 storage/               # Almacenamiento
├── 📁 tests/                 # Tests automatizados
└── 📁 docs/                  # Documentación adicional
```

## 🚀 Instalación Rápida

### Prerrequisitos
- PHP 8.2 o superior
- Composer 2.0+
- Node.js 18+ y npm
- MySQL 8.0+ o PostgreSQL 13+
- Redis 6.0+ (opcional, para cache)

### Pasos de Instalación

1. **Clonar el repositorio**
   ```bash
   git clone <repository-url>
   cd copia_en_proyecto_final_Dt
   ```

2. **Instalar dependencias**
   ```bash
   composer install
   npm install
   ```

3. **Configurar variables de entorno**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Configurar base de datos**
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=nombre_base_datos
   DB_USERNAME=usuario
   DB_PASSWORD=password
   ```

5. **Ejecutar migraciones y seeders**
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

6. **Compilar assets**
   ```bash
   npm run build
   ```

7. **Configurar permisos**
   ```bash
   chmod -R 775 storage bootstrap/cache
   ```

8. **Iniciar servidor de desarrollo**
   ```bash
   php artisan serve
   ```

## 📚 Documentación

### Manual Técnico
- **[MANUAL_TECNICO_SIMPLIFICADO.md](MANUAL_TECNICO_SIMPLIFICADO.md)** - **📖 Manual rápido para instalar y ejecutar**
- **[MANUAL_TECNICO.md](MANUAL_TECNICO.md)** - Documentación técnica completa
- **[DIAGRAMAS_SISTEMA.md](DIAGRAMAS_SISTEMA.md)** - Diagramas y flujos del sistema
- **[docs/ASIGNACION_PV.md](docs/ASIGNACION_PV.md)** - Documentación del módulo PV

### Secciones del Manual Técnico
1. [Descripción General](MANUAL_TECNICO.md#descripción-general)
2. [Arquitectura del Sistema](MANUAL_TECNICO.md#arquitectura-del-sistema)
3. [Requisitos Técnicos](MANUAL_TECNICO.md#requisitos-técnicos)
4. [Instalación y Configuración](MANUAL_TECNICO.md#instalación-y-configuración)
5. [Estructura de la Base de Datos](MANUAL_TECNICO.md#estructura-de-la-base-de-datos)
6. [Módulos del Sistema](MANUAL_TECNICO.md#módulos-del-sistema)
7. [API y Endpoints](MANUAL_TECNICO.md#api-y-endpoints)
8. [Seguridad y Autenticación](MANUAL_TECNICO.md#seguridad-y-autenticación)
9. [Frontend y UI/UX](MANUAL_TECNICO.md#frontend-y-uiux)
10. [Servicios y Lógica de Negocio](MANUAL_TECNICO.md#servicios-y-lógica-de-negocio)
11. [Comandos y Tareas Programadas](MANUAL_TECNICO.md#comandos-y-tareas-programadas)
12. [Testing](MANUAL_TECNICO.md#testing)
13. [Despliegue](MANUAL_TECNICO.md#despliegue)
14. [Mantenimiento](MANUAL_TECNICO.md#mantenimiento)

## 🔐 Roles del Sistema

| Rol | Descripción | Permisos |
|-----|-------------|----------|
| **Super Administrador** | Acceso total al sistema | CRUD usuarios, roles, permisos, configuración |
| **Administrador** | Gestión general | Gestión proveedores, trámites, reportes |
| **Revisor Digital** | Revisión de documentos digitales | Validar documentos, aprobar digital |
| **Revisor Presencial** | Cotejo físico de documentos | Cotejo presencial, aprobar presencial |
| **Revisor Domiciliario** | Verificación en sitio | Verificación domiciliaria, aprobar domiciliaria |
| **Proveedor** | Empresa que solicita servicios | Gestión propia, subir documentos |
| **Solicitante** | Usuario individual | Solicitar servicios, información pública |

## 🔄 Flujo de Trámites

```
Pendiente → Revisión Digital → Revisión Presencial → Revisión Domiciliaria → Aprobado
    ↓              ↓                    ↓                      ↓
Para Corrección ← ← ← ← ← ← ← ← ← ← ← ← ← ← ← ← ← ← ← ← ← ← ← ←
    ↓
Cancelado
```

## 🛠️ Comandos Útiles

### Gestión de Proveedores
```bash
# Actualizar proveedores vencidos
php artisan proveedores:actualizar-vencidos

# Probar asignación de PV
php artisan proveedores:probar-asignacion-pv 901323 --ultimo-pv=PV901323
```

### Gestión de Usuarios
```bash
# Eliminar usuarios no verificados
php artisan users:eliminar-no-verificados

# Crear administrador
php artisan users:crear-admin
```

### Reportes
```bash
# Generar reporte trimestral
php artisan reportes:generar-trimestral

# Exportar proveedores
php artisan reportes:exportar-proveedores
```

### Mantenimiento
```bash
# Limpiar logs
php artisan logs:limpiar

# Limpiar cache
php artisan cache:clear

# Optimizar para producción
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## 🧪 Testing

```bash
# Ejecutar todos los tests
php artisan test

# Tests específicos
php artisan test --testsuite=Unit
php artisan test --testsuite=Feature

# Test específico
php artisan test tests/Unit/AsignacionPvServiceTest.php
```

## 📊 API Endpoints

### Autenticación
- `POST /iniciar-sesion` - Login de usuario
- `POST /registro` - Registro de usuario
- `POST /cerrar-sesion` - Logout

### Proveedores
- `GET /proveedores` - Listar proveedores
- `POST /proveedores` - Crear proveedor
- `GET /proveedores/{id}` - Ver proveedor
- `PUT /proveedores/{id}` - Actualizar proveedor
- `DELETE /proveedores/{id}` - Eliminar proveedor

### Trámites
- `GET /tramites` - Listar trámites
- `POST /tramites` - Crear trámite
- `GET /tramites/{id}` - Ver trámite
- `PUT /tramites/{id}` - Actualizar trámite
- `POST /tramites/{id}/correcciones` - Solicitar correcciones

### Revisiones
- `GET /revisiones` - Listar revisiones
- `POST /revisiones/{tramite}/procesar` - Procesar revisión
- `POST /revisiones/{tramite}/decision-final` - Decisión final

## 🔧 Configuración de Desarrollo

### Variables de Entorno Importantes
```env
APP_NAME="Sistema de Gestión de Proveedores"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=proveedores_db
DB_USERNAME=root
DB_PASSWORD=

CACHE_DRIVER=file
QUEUE_CONNECTION=sync
SESSION_DRIVER=file

MAIL_MAILER=smtp
MAIL_HOST=mailpit
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="noreply@example.com"
MAIL_FROM_NAME="${APP_NAME}"
```

### Comandos de Desarrollo
```bash
# Servidor de desarrollo
php artisan serve

# Compilar assets en modo desarrollo
npm run dev

# Compilar assets en modo watch
npm run build:watch

# Limpiar y recompilar
npm run build:clean
```

## 🚀 Despliegue

### Configuración de Producción
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://tu-dominio.com

DB_CONNECTION=mysql
DB_HOST=tu-servidor-db
DB_DATABASE=nombre_produccion
DB_USERNAME=usuario_produccion
DB_PASSWORD=password_seguro

CACHE_DRIVER=redis
QUEUE_CONNECTION=redis
SESSION_DRIVER=redis
```

### Optimización para Producción
```bash
# Optimizar autoloader
composer install --optimize-autoloader --no-dev

# Cache de configuración
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Compilar assets
npm run build
```

## 📞 Soporte

Para soporte técnico o consultas sobre el sistema:

- **Email**: soporte@empresa.com
- **Documentación**: docs.empresa.com
- **Repositorio**: github.com/empresa/sistema-proveedores

## 📄 Licencia

Este proyecto es propiedad de la empresa y está protegido por derechos de autor.

---

## 🎯 Próximos Pasos

1. **📖 Leer Manual Rápido**: Comenzar con [MANUAL_TECNICO_SIMPLIFICADO.md](MANUAL_TECNICO_SIMPLIFICADO.md)
2. **🚀 Instalar Sistema**: Seguir los pasos de instalación rápida
3. **🔧 Configurar Entorno**: Ajustar configuración según necesidades
4. **🧪 Ejecutar Tests**: Verificar que todo funcione correctamente
5. **📚 Consultar Documentación Completa**: Para desarrollo avanzado

---

*Este README sirve como punto de entrada al sistema. Para instalar y ejecutar rápidamente, consultar el [Manual Simplificado](MANUAL_TECNICO_SIMPLIFICADO.md).*

# Instrucciones de Despliegue con Cloudflare Tunnel

## ⚠️ IMPORTANTE: Antes de Desplegar

### 1. Compilar Assets (OBLIGATORIO)

**Ejecuta estos comandos ANTES de iniciar Cloudflare Tunnel:**

```bash
# En Windows
npm install
npm run build

# O usar el script automatizado
scripts\build-assets.bat
```

### 2. Verificar Archivos de Build

Después del build, verifica que existen estos archivos:
```
public/build/
├── manifest.json
└── assets/
    ├── app-[hash].css
    └── app-[hash].js
```

## Configuración de Cloudflare Tunnel

### 1. Iniciar el Túnel

```bash
# Asegúrate de estar en el directorio raíz del proyecto
cloudflared tunnel --url http://localhost:8000
```

### 2. Verificar que Laravel esté ejecutándose

```bash
# En otra terminal
php artisan serve
```

## Verificación del Despliegue

### 1. Verificar Assets en el Navegador

1. Abre la URL de Cloudflare Tunnel
2. Presiona F12 para abrir las herramientas de desarrollador
3. Ve a la pestaña "Network"
4. Recarga la página
5. Busca archivos que se carguen desde `/build/assets/`

### 2. Verificar Estilos de Tailwind

1. Inspecciona cualquier elemento con clases de Tailwind
2. Deberías ver los estilos aplicados en el panel de estilos
3. Los elementos deberían tener el diseño correcto

## Solución de Problemas

### ❌ Los estilos no se cargan

**Causa:** Los assets no están compilados
**Solución:**
```bash
npm run build
```

### ❌ Error 404 en archivos CSS/JS

**Causa:** Los archivos de build no existen
**Solución:**
```bash
# Limpiar y recompilar
npm run build:clean
```

### ❌ Los estilos se ven diferentes

**Causa:** Caché del navegador
**Solución:**
1. Presiona Ctrl+F5 para recarga forzada
2. O limpia la caché del navegador

## Comandos de Verificación

```bash
# Verificar que los archivos existen
ls -la public/build/assets/

# Verificar el contenido del manifest
cat public/build/manifest.json

# Verificar que el CSS contiene Tailwind
head -n 5 public/build/assets/app-*.css
```

## Flujo de Trabajo Recomendado

### Para Desarrollo:
```bash
npm run dev
```

### Para Despliegue:
```bash
# 1. Compilar assets
npm run build

# 2. Verificar archivos
ls public/build/assets/

# 3. Iniciar Laravel
php artisan serve

# 4. Iniciar Cloudflare Tunnel
cloudflared tunnel --url http://localhost:8000
```

## Notas Importantes

1. **NUNCA inicies Cloudflare Tunnel sin compilar los assets primero**
2. **Los archivos en `public/build/` son necesarios para producción**
3. **Si cambias clases de Tailwind, vuelve a ejecutar `npm run build`**
4. **El directorio `public/build/` debe existir y contener archivos**

## Verificación Final

✅ Assets compilados correctamente
✅ Archivos CSS y JS existen en `public/build/assets/`
✅ `manifest.json` existe y es válido
✅ Laravel está ejecutándose en `localhost:8000`
✅ Cloudflare Tunnel está activo
✅ Los estilos se cargan correctamente en el navegador

¡Tu aplicación ahora debería funcionar perfectamente con Cloudflare Tunnel!
